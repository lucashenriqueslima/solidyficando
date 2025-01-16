<?php

namespace App\Filament\Resources;

use App\Enums\SignInAccountType;
use App\Filament\Resources\PartinerResource\Pages;
use App\Filament\Resources\PartinerResource\RelationManagers;
use App\Models\Partiner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
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
            'index' => Pages\ListPartiners::route('/'),
            // 'create' => Pages\CreatePartiner::route('/create'),
            // 'edit' => Pages\EditPartiner::route('/{record}/edit'),
        ];
    }
}
