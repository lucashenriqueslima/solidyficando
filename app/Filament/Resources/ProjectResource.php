<?php

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Company;
use App\Models\Person;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Projeto';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome do Projeto'),
                Forms\Components\Select::make('project_category_id')
                    ->label('Categoria do Projeto')
                    ->relationship('projectCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                    ])
                    ->required(),
                Forms\Components\MorphToSelect::make('projectable')
                    ->label('Atribuir Projeto para:')
                    ->searchable()
                    ->columnSpanFull()
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(Company::class)
                            ->label('Instituição')
                            ->getOptionLabelFromRecordUsing(fn(Company $record) => "{$record->name} | {$record->cnpj}")
                            ->searchColumns(['name', 'cnpj']),
                        Forms\Components\MorphToSelect\Type::make(Person::class)
                            ->label('Assistido')
                            ->getOptionLabelFromRecordUsing(fn(Person $record) => "{$record->name} | {$record->cpf}")
                            ->searchColumns(['name', 'cpf']),
                    ])
                    ->hiddenOn('edit'),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição do Projeto')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options(
                        ProjectStatus::class
                    )
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Data de Início')
                    ->placeholder('Selecione a data de início')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('planned_end_date')
                    ->label('Data de Término Prevista')
                    ->placeholder('Selecione a data de término prevista'),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Data de Término')
                    ->placeholder('Selecione a data de término'),
                Money::make('budget')
                    ->label('Orçamento')
                    ->default(0)
                    ->helperText('Valor Orçado.'),
                Money::make('spent')
                    ->label('Gasto')
                    ->default(0)
                    ->helperText('Valor Gasto.'),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
