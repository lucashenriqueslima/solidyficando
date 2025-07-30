<?php

namespace App\Filament\Resources;

use App\Actions\HandleBillingGenerationAction;
use App\Enums\SignInAccountType;
use App\Filament\Resources\PartinerResource\Pages;
use App\Filament\Resources\PartinerResource\RelationManagers;
use App\Filament\Resources\PartinerResource\RelationManagers\FinancialMovementsRelationManager;
use App\Jobs\HandleBillingGenerationJob;
use App\Models\FinancialMovementCategory;
use App\Models\Partiner;
use App\Services\Asaas\AsaasApiService;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\Money;

class PartinerResource extends Resource
{
    protected static ?string $model = Partiner::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $modelLabel = 'Parceiros';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('document_type')
                    ->label('Cadastrar por CPF ou CNPJ')
                    ->options(SignInAccountType::class)
                    ->default(SignInAccountType::CPF->value)
                    ->dehydrated(false)
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('cpf')
                    ->label(fn(Get $get) => $get('document_type') === SignInAccountType::CPF->value ? 'CPF' : 'CNPJ')
                    ->mask(fn(Get $get) => $get('document_type') === SignInAccountType::CPF->value ? '999.999.999-99' : '99.999.999/9999-99')
                    ->rule(fn(Get $get) => $get('document_type') === SignInAccountType::CPF->value ? 'cpf' : 'cnpj')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('institution_id')
                    ->label('Empresa')
                    ->relationship('institution', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                    ]),
                Forms\Components\Select::make('department_id')
                    ->label('Departamento')
                    ->relationship('department', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                    ]),
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birthday')
                    ->label('Data de Nascimento')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefone')
                    ->mask('(99) 99999-9999')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required(),
                Money::make('monthly_contribution')
                    ->label('Contribuição Mensal')
                    ->columnSpanFull()
                    ->required(),

                Fieldset::make('Configurações de Cobrança')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_to_charge')
                            ->label('Cobrar Contribuição Mensal')
                            ->onIcon('heroicon-o-check-circle')
                            ->offIcon('heroicon-o-x-circle')
                            ->default(false)
                            ->inline(false)
                            ->helperText('Ativar para cobrar a contribuição mensal do parceiro.')
                            ->live()
                            ->required(),
                        Forms\Components\TextInput::make('billing_day')
                            ->label('Dia de Cobrança')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(31)
                            ->default(15)
                            ->helperText('Dia do mês em que a cobrança será realizada.')
                            ->required(fn(Get $get) => $get('is_to_charge') === true)
                            ->visible(fn(Get $get) => $get('is_to_charge') === true)
                            ->dehydrated(fn(Get $get) => $get('is_to_charge') === true)
                            ->live(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('institution.name')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departamento')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF/CNPJ')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('monthly_contribution')
                    ->label('Contribuição Mensal')
                    ->money('BRL', locale: 'pt-BR')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('generate_bank_slip')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->label('Gerar Boleto')
                    ->form(form: [
                        Grid::make('Gerar Boleto')
                            ->columns(2)
                            ->schema([
                                Money::make('amount')
                                    ->label('Valor do Boleto')
                                    ->columnSpan(1)
                                    ->required()
                                    ->minValue(5)
                                    ->maxValue(1000000)
                                    ->default(0)
                                    ->helperText('Valor a ser cobrado no boleto.'),
                                DatePicker::make('due_date')
                                    ->label('Data de Vencimento')
                                    ->columnSpan(1)
                                    ->default(now()->addMonth())
                                    ->required()
                                    ->helperText('Data de vencimento do boleto.')
                                    ->minDate(now()),
                            ]),
                    ])
                    ->action(
                        function (Partiner $record, array $data): void {
                            dispatch(
                                new HandleBillingGenerationJob(
                                    partiner: $record,
                                    value: (float) $data['amount'],
                                    dueDate: Carbon::parse($data['due_date']),
                                )
                            );

                            Notification::make()
                                ->title('Boleto gerado com sucesso!')
                                ->success()
                                ->send();
                        }
                    ),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FinancialMovementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartiners::route('/'),
            'create' => Pages\CreatePartiner::route('/create'),
            'edit' => Pages\EditPartiner::route('/{record}/edit'),
        ];
    }
}
