<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
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

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $modelLabel = 'Doações';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('donation_category_id')
                    ->label('Categoria')
                    ->columnSpanFull()
                    ->relationship(
                        name: 'donationCategory',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query, Get $get) => $query->orderBy('name'),
                    )
                    ->searchable(['name'])
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                    ]),
                Forms\Components\Fieldset::make('Atribuir doação para...')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label(label: 'Instituição')
                            ->columnSpanFull()
                            ->relationship(
                                name: 'company',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query, Get $get) => $query->orderBy('name'),
                            )
                            ->searchable(['name', 'cnpj'])
                            ->preload()
                            ->getOptionLabelFromRecordUsing(
                                fn(Company $record) => "{$record->name} | {$record->cnpj}"
                            ),
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
                                        $get('company_id'),
                                    ),
                            )
                            ->searchable(['name', 'cpf'])
                            ->getOptionLabelFromRecordUsing(
                                fn(Person $record) => "{$record->name} | {$record->cpf}"
                            ),
                    ]),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantia')
                    ->columnSpanFull()
                    ->numeric()
                    ->required(),
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
            'index' => Pages\ListDonations::route('/'),
            // 'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
