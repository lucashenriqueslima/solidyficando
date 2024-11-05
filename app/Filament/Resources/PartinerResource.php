<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartinerResource\Pages;
use App\Filament\Resources\PartinerResource\RelationManagers;
use App\Models\Partiner;
use Filament\Forms;
use Filament\Forms\Form;
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
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cpf')
                    ->label('CPF')
                    ->mask('999.999.999-99')
                    ->required()
                    ->maxLength(255),
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
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
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
