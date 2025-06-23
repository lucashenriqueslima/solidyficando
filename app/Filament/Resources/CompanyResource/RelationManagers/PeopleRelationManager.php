<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Enums\PersonType;
use App\Enums\EducationLevel;
use App\Enums\Housing;
use App\Enums\PixKeyType;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;

class PeopleRelationManager extends RelationManager
{
    protected static string $relationship = 'people';

    protected static ?string $title = 'Pessoas';
    protected static ?string $modelLabel = 'Pessoa';



    public function form(Form $form): Form
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
                            ->disabled(fn(Get $get) => $get('pix_key_type') == null)
                            ->mask(fn(Get $get) => PixKeyType::getMask($get('pix_key_type')))
                            ->minLength(fn(Get $get) => PixKeyType::getMinLength($get('pix_key_type')))
                            ->rule(fn(Get $get) => PixKeyType::getRule($get('pix_key_type')))
                            ->unique(ignoreRecord: true),
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Pessoa')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone'),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Foto de Perfil')
                    ->circular(),
                Tables\Columns\TextColumn::make('financialMovements_sum_value')
                    ->label('Valor Gasto')
                    ->color('danger')
                    ->money('BRL', locale: 'pt-BR')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->financialMovements()
                            ->selectRaw('SUM(financial_movement_person.value) as total')
                            ->pluck('total')
                            ->first() ?? 0;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
