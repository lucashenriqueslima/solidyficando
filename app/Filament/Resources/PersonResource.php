<?php

namespace App\Filament\Resources;

use App\Enums\EducationLevel;
use App\Enums\Housing;
use App\Filament\Resources\PersonResource\Pages;
use App\Filament\Resources\PersonResource\RelationManagers;
use App\Filament\Resources\PersonResource\RelationManagers\FinancialMovementsRelationManager;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Pessoas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cpf')
                    ->label('CPF')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
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
