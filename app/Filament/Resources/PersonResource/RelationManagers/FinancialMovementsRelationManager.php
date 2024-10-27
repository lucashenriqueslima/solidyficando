<?php

namespace App\Filament\Resources\PersonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinancialMovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'financialMovements';
    protected static ?string $title = 'Movimentações Financeiras';
    protected static ?string $modelLabel = 'Movimentação Financeira';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('value')
            ->columns([



                Tables\Columns\TextColumn::make('financialMovementCategory.name')
                    ->label('Categoria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição'),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Data Pagamento')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valor Total')
                    ->color('danger')
                    ->money('BRL', locale: 'pt-BR'),
                Tables\Columns\TextColumn::make('financialMovements_value_by_person')
                    ->label('Valor por Pessoa')
                    ->color('danger')
                    ->money('BRL', locale: 'pt-BR')
                    ->getStateUsing(function ($record) {
                        return $record->pivot->pivot_value;
                    })
                    ->getStateUsing(function ($record) {
                        return $record->getOriginal('pivot_value');
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
}
