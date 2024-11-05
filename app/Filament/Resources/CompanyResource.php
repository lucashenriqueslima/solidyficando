<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Filament\Resources\CompanyResource\RelationManagers\EventsRelationManager;
use App\Filament\Resources\CompanyResource\RelationManagers\PeopleRelationManager;
use App\Filament\Resources\CompanyResource\RelationManagers\PresidentRelationManager;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Instiuição';

    protected static ?string $pluralModelLabel = 'Instituições';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Instiuição')
                    ->columns(2)
                    ->schema([
                        Fieldset::make('Identificação')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nome')
                                    ->columnSpanFull()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('cnpj')
                                    ->label('CNPJ')
                                    ->mask('99.999.999/9999-99')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('cmas')
                                    ->label('CMAS')
                                    ->maxLength(255),
                            ]),

                        Fieldset::make('Contato')
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('E-mail')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Telefone')
                                    ->tel()
                                    ->mask('(99) 99999-9999')
                                    ->maxLength(255)
                                    ->required(),
                            ]),
                        Fieldset::make('Endereço')
                            ->schema([
                                Forms\Components\TextInput::make('cep')
                                    ->label('CEP')
                                    ->required()
                                    ->maxLength(9)
                                    ->mask('99999-999')
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, callable $set) => static::buscarEnderecoPorCep($state, $set)),

                                Forms\Components\TextInput::make('address')
                                    ->label('Endereço')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('number')
                                    ->label('Número')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('neighborhood')
                                    ->label('Bairro')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('city')
                                    ->label('Cidade')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('state')
                                    ->label('Estado')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\TextInput::make('church')
                            ->label('Igreja Vinculada')
                            ->columnSpanFull()
                            ->maxLength(255),

                    ]),
                Section::make('Presidente')
                    ->columns(2)
                    ->relationship('president')
                    ->schema([
                        Fieldset::make('Identificação')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('cpf')
                                    ->label('CPF')
                                    ->mask('999.999.999-99')
                                    ->required(),
                            ]),
                        Fieldset::make('Contato')
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('E-mail')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Telefone')
                                    ->required()
                                    ->mask('(99) 99999-9999')
                                    ->maxLength(255),
                            ]),
                        Fieldset::make('Endereço')
                            ->schema([
                                Forms\Components\TextInput::make('cep')
                                    ->label('CEP')
                                    ->required()
                                    ->maxLength(9)
                                    ->mask('99999-999')
                                    ->reactive() // Para detectar mudanças no valor
                                    ->afterStateUpdated(fn($state, callable $set) => static::buscarEnderecoPorCep($state, $set)),

                                Forms\Components\TextInput::make('address')
                                    ->label('Endereço')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('number')
                                    ->label('Número')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('neighborhood')
                                    ->label('Bairro')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('city')
                                    ->label('Cidade')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('state')
                                    ->label('Estado')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cnpj')
                    ->label('CNPJ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cmas')
                    ->label('CMAS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            PeopleRelationManager::class,
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }

    public static function buscarEnderecoPorCep($cep, callable $set)
    {
        if (!$cep) {
            return;
        }
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) === 8) { // Verifica se o CEP é válido
            $response = file_get_contents("https://viacep.com.br/ws/{$cep}/json/");

            if ($response) {
                $data = json_decode($response, true);

                if (!isset($data['erro'])) {
                    // Preenche os campos com os dados do endereço retornado
                    $set('address', $data['logradouro'] ?? '');
                    $set('neighborhood', $data['bairro'] ?? '');
                    $set('city', $data['localidade'] ?? '');
                    $set('state', $data['uf'] ?? '');
                }
            }
        }
    }
}
