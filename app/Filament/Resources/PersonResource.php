<?php

namespace App\Filament\Resources;

use App\Enums\EducationLevel;
use App\Enums\Housing;
use App\Enums\PixKeyType;
use App\Filament\Resources\PersonResource\Pages;
use App\Filament\Resources\PersonResource\RelationManagers;
use App\Filament\Resources\PersonResource\RelationManagers\FinancialMovementsRelationManager;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Assistidos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Informação Pessoais')
                    ->schema(
                        [
                            Forms\Components\Select::make('company_id')
                                ->label('Instituição')
                                ->options(fn() => \App\Models\Company::pluck('name', 'id'))
                                ->required(),
                            Forms\Components\TextInput::make('name')
                                ->label('Nome')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('cpf')
                                ->label('CPF')
                                ->required()
                                ->mask('999.999.999-99')
                                ->rule('cpf')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('phone')
                                ->label('Telefone')
                                ->tel()
                                ->required()
                                ->mask('(99) 99999-9999')
                                ->maxLength(255),
                            Forms\Components\DatePicker::make('birthday')
                                ->label('Data de Nascimento')
                                ->date()
                                ->required(),
                            Money::make('family_income')
                                ->label('Renda Mensal Familiar')
                                ->required(),
                            Forms\Components\Select::make('education')
                                ->label('Escolaridade')
                                ->options(EducationLevel::class)
                                ->required(),
                            Forms\Components\Select::make('housing')
                                ->label('Moradia')
                                ->options(Housing::class)
                                ->required(),
                            Forms\Components\TextInput::make('children')
                                ->label('Quantidade de Filhos')
                                ->numeric()
                                ->required(),

                        ]
                    ),
                Fieldset::make('Informações Bancárias')
                    ->schema([
                        Forms\Components\Select::make('pix_key_type')
                            ->label('Tipo de Chave PIX')
                            ->options(PixKeyType::class)
                            ->live(),
                        Forms\Components\TextInput::make('pix_key')
                            ->label('Chave PIX')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled(fn(Get $get) => $get('pix_key_type') == null)
                            ->mask(fn(Get $get) => PixKeyType::getMask($get('pix_key_type')))
                            ->minLength(fn(Get $get) => PixKeyType::getMinLength($get('pix_key_type')))
                            ->rule(fn(Get $get) => PixKeyType::getRule($get('pix_key_type'))),
                    ]),

                Fieldset::make('Informações de Endereço')
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
                            ->required(),
                        Forms\Components\TextInput::make('number')
                            ->label('Número')
                            ->required(),
                        Forms\Components\TextInput::make('neighborhood')
                            ->label('Bairro')
                            ->required(),
                        Forms\Components\TextInput::make('city')
                            ->label('Cidade')
                            ->required(),
                        Forms\Components\TextInput::make('state')
                            ->label('Estado')
                            ->required(),

                    ]),

                Forms\Components\FileUpload::make('image_path')
                    ->label('Foto de Perfil')
                    ->columnSpanFull()
                    ->visibility('public')
                    ->maxSize(8 * 1024)
                    ->directory('public/people')
                    ->image()
                    ->imageEditor(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Instituição')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Foto de Perfil')
                    ->circular(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criação')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Atualização')
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
            FinancialMovementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }

    public static function buscarEnderecoPorCep($cep, callable $set)
    {

        if (!$cep) {
            return;
        }
        // Remove caracteres indesejados do CEP
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
