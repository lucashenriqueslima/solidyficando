<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DonationResource\Pages;
use App\Filament\App\Resources\DonationResource\RelationManagers;
use App\Models\Company;
use App\Models\Donation;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DonationResource extends Resource
{

    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $modelLabel = 'Doações';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('company_id', Auth::user()->id);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantia')
                    ->columnSpanFull()
                    ->numeric()
                    ->dehydrated(false)
                    ->readOnly(),
                Forms\Components\Fieldset::make('Atribuir doação para...')
                    ->schema([

                        Forms\Components\Select::make('people')
                            ->label('Pessoas')
                            ->columnSpanFull()
                            ->multiple()
                            ->preload()
                            ->relationship(
                                name: 'people',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query, Get $get) => $query->orderBy('name')
                                    ->where(
                                        'company_id',
                                        Auth::user()->id,
                                    ),
                            )
                            ->searchable(['name', 'cpf'])
                            ->getOptionLabelFromRecordUsing(
                                fn(Person $record) => "{$record->name} | {$record->cpf}"
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('donationCategory.name')
                    ->label('Categoria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Instituição')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantia')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListDonations::route('/'),
            // 'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
