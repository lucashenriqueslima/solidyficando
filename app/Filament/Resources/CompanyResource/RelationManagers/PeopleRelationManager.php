<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Enums\PersonType;
use App\Enums\EducationLevel;
use App\Enums\Housing;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeopleRelationManager extends RelationManager
{
    protected static string $relationship = 'people';

    protected static ?string $title = 'Pessoas';
    protected static ?string $modelLabel = 'Pessoa';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                Forms\Components\TextInput::make('cpf')
                    ->label('CPF')
                    ->mask('999.999.999-99')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefone')
                    ->mask('(99) 99999-9999')
                    ->required(),
                Forms\Components\DatePicker::make('birthday')
                    ->label('Data de Nascimento')
                    ->date()
                    ->required(),
                Forms\Components\TextInput::make('family_income')
                    ->label('Renda Familiar')
                    ->prefix('R$')
                    ->required()
                    ->numeric()
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

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Pessoa')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome'),
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
