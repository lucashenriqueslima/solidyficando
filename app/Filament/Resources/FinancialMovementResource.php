<?php

namespace App\Filament\Resources;

use App\Enums\FinancialMovementFlowType;
use App\Enums\FinancialMovementStatus;
use App\Filament\Resources\FinancialMovementResource\Pages;
use App\Models\Company;
use App\Models\FinancialMovement;
use App\Models\FinancialMovementCategory;
use App\Models\Partiner;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;

class FinancialMovementResource extends Resource
{
    protected static ?string $model = FinancialMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'Movimentação Financeira';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ToggleButtons::make('flow_type')
                    ->label('Entrada/Saída')
                    ->options([
                        'in' => 'Entrada',
                        'out' => 'Saída',
                    ])
                    ->colors([
                        'in' => 'success',
                        'out' => 'danger',
                    ])
                    ->live()
                    ->columnSpanFull()
                    ->grouped()
                    ->required(),
                Forms\Components\Fieldset::make('Atribuir Movimentação Financeira para...')
                    ->schema([
                        Forms\Components\MorphToSelect::make('movementable')
                            ->label('Entidade')
                            ->searchable()
                            ->columnSpanFull()
                            ->types([
                                Forms\Components\MorphToSelect\Type::make(Partiner::class)
                                    ->label('Parceiro')
                                    ->getOptionLabelFromRecordUsing(fn(Partiner $record) => "{$record->name} | {$record->cpf}")
                                    ->searchColumns(['name', 'cpf']),
                                Forms\Components\MorphToSelect\Type::make(Company::class)
                                    ->label('Instituição')
                                    ->getOptionLabelFromRecordUsing(fn(Company $record) => "{$record->name} | {$record->cnpj}")
                                    ->searchColumns(['name', 'cnpj']),
                            ])
                            ->hiddenOn('edit'),
                    ]),
                Forms\Components\Select::make('financial_movement_category_id')
                    ->label('Categoria')
                    ->columnSpanFull()
                    ->relationship(
                        name: 'financialMovementCategory',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query->orderBy('name')
                            ->where('flow_type', $get('flow_type')),
                    )
                    ->searchable(['name'])
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn(FinancialMovementCategory $record) => "{$record->name} | {$record->flow_type->getLabel()}")
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\Select::make('flow_type')
                            ->label('Entrada/Saída')
                            ->options(FinancialMovementFlowType::class)
                            ->required(),
                    ]),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull()
                    ->maxLength(255),
                Money::make('value')
                    ->label('Valor')
                    ->required()
                    ->minValue(5)
                    ->maxValue(1000000)
                    ->default(0)
                    ->suffixIconColor(fn(Get $get) => $get('flow_type') === 'in' ? 'success' : 'danger')
                    ->columnSpanFull()
                    ->live()
                    ->helperText('Valor a ser cobrado.'),
                Forms\Components\Select::make('status')
                    ->disabled(fn(Get $get) => !$get('flow_type'))
                    ->columnSpanFull()
                    ->options(FinancialMovementStatus::class)
                    ->default(FinancialMovementStatus::PAID)
                    ->live()
                    ->afterStateUpdated(fn(callable $set) => $set('payment_date', null))
                    ->required(),
                Forms\Components\DatePicker::make('payment_date')
                    ->label('Data de Recebimento/Pagamento')
                    ->disabled(fn(Get $get) => $get('status') !== FinancialMovementStatus::PAID->value)
                    ->default(fn() => now()),
                Forms\Components\DatePicker::make('due_date')
                    ->label('Data de Vencimento'),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('flow_type')
                    ->label('Entrada/Saída')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('movementable.name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valor')
                    ->color(
                        fn(FinancialMovement $record): string =>
                        $record->flow_type === FinancialMovementFlowType::IN
                            ? 'success'
                            : 'danger'
                    )
                    ->money('BRL', locale: 'pt-BR')
                    ->sortable()
                    ->searchable()
                    ->summarize([
                        Sum::make()
                            ->label('Saldo')
                            ->money('BRL', locale: 'pt-BR')
                            ->query(fn($query) => $query->where('status', FinancialMovementStatus::PAID)),
                        Sum::make()
                            ->label('Saldo Futuro')
                            ->money('BRL', locale: 'pt-BR')
                            ->query(fn($query) => $query->where('status', FinancialMovementStatus::PENDING)),
                    ]),
                Tables\Columns\TextColumn::make('financialMovementCategory.name')
                    ->label('Categoria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Instituição')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Data Pagamento')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Data de Vencimento')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_url')
                    ->label('Link de Pagamento')
                    ->url(fn(FinancialMovement $record): ?string => $record->invoice_url, true),
                Tables\Columns\TextColumn::make('bank_slip_url')
                    ->label('Link de Boleto')
                    ->url(fn(FinancialMovement $record): ?string => $record->bank_slip_url, true),



                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters(
                [
                    SelectFilter::make('flow_type')
                        ->label('Entrada/Saída')
                        ->options(FinancialMovementFlowType::class)
                        ->multiple()
                        ->preload(),
                    SelectFilter::make('status')
                        ->label('Status')
                        ->options(FinancialMovementStatus::class)
                        ->multiple()
                        ->preload(),
                    SelectFilter::make('financial_movement_category_id')
                        ->label('Categoria')
                        ->relationship(
                            name: 'financialMovementCategory',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn(Builder $query) => $query->orderBy('name'),
                        )
                        ->searchable(['name'])
                        ->preload()
                        ->multiple()
                        ->getOptionLabelFromRecordUsing(fn(FinancialMovementCategory $record) => "{$record->name} | {$record->flow_type->getLabel()}"),
                    SelectFilter::make('movementable')
                        ->label('Parceiro/Instituição')
                        ->relationship(
                            name: 'movementable',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn(Builder $query) => $query->orderBy('name'),
                        )
                        ->preload()
                        ->multiple()
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->name ?? 'Sem nome'),
                    Filter::make('payment_date')
                        ->label('Data de Pagamento')
                        ->form([
                            Section::make('Data de Pagamento')
                                ->schema([
                                    DatePicker::make('initial_date')
                                        ->label('Data Inicial'),
                                    DatePicker::make('final_date')
                                        ->label('Data Final'),
                                ])
                                ->columns(2)
                                ->columnSpanFull(),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when($data['initial_date'], fn($query, $initial_date) => $query->where('payment_date', '>=', $initial_date))
                                ->when($data['final_date'], fn($query, $final_date) => $query->where('payment_date', '<=', $final_date));
                        }),
                    Filter::make('due_date')
                        ->label('Data de Vencimento')
                        ->form([
                            Section::make('Data de Vencimento')
                                ->schema([
                                    DatePicker::make('initial_date')
                                        ->label('Data Inicial'),
                                    DatePicker::make('final_date')
                                        ->label('Data Final'),
                                ])
                                ->columns(2)
                                ->columnSpanFull(),
                        ])
                        ->query(callback: function (Builder $query, array $data): Builder {
                            return $query
                                ->when($data['initial_date'], fn($query, $initial_date) => $query->where('due_date', '>=', $initial_date))
                                ->when($data['final_date'], fn($query, $final_date) => $query->where('due_date', '<=', $final_date));
                        })
                ],

            )
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinancialMovements::route('/'),
            // 'create' => Pages\CreateFinancialMovement::route('/create'),
            // 'edit' => Pages\EditFinancialMovement::route('/{record}/edit'),
        ];
    }
}
