<?php

namespace App\Filament\Resources;

use App\Enums\EducationLevel;
use App\Enums\JobType;
use App\Filament\Resources\RecruitResource\Pages;
use App\Filament\Resources\RecruitResource\RelationManagers;
use App\Models\Recruit;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Tables\Columns\TextColumn;

class RecruitResource extends Resource
{
    protected static ?string $model = Recruit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'Banco de Talentos';

    protected static ?string $modelLabel = 'Talento';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Dados Pessoais e Endereço')
                        ->icon('heroicon-o-user')
                        ->description('Insira os dados pessoais do candidato.')
                        ->schema([
                            Fieldset::make('Dados Pessoais')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nome')
                                        ->columnSpan(1)
                                        ->maxLength(255)
                                        ->required(),
                                    Forms\Components\TextInput::make('cpf')
                                        ->label('CPF')
                                        ->columnSpan(1)
                                        ->mask('999.999.999-99')
                                        ->rule('cpf')
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('phone')
                                        ->label('Telefone')
                                        ->columnSpan(1)
                                        ->tel()
                                        ->mask('(99) 99999-9999')
                                        ->maxLength(255),
                                    Forms\Components\DatePicker::make('birth_date')
                                        ->label('Data de Nascimento')
                                        ->columnSpan(1)
                                        ->date(),
                                    Forms\Components\Select::make('education')
                                        ->label('Escolaridade')
                                        ->columnSpanFull()
                                        ->options(EducationLevel::class),
                                ]),
                            Fieldset::make('Endereço')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\TextInput::make('cep')
                                        ->label('CEP')

                                        ->maxLength(9)
                                        ->mask('99999-999')
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, callable $set) => static::buscarEnderecoPorCep($state, $set)),
                                    Forms\Components\TextInput::make('address')
                                        ->label('Endereço'),
                                    Forms\Components\TextInput::make('number')
                                        ->label('Número'),
                                    Forms\Components\TextInput::make('neighborhood')
                                        ->label('Bairro'),
                                    Forms\Components\TextInput::make('city')
                                        ->label('Cidade'),
                                    Forms\Components\TextInput::make('state')
                                        ->label('Estado'),
                                ])
                        ]),
                    Wizard\Step::make('Histórico Profissional')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->description('Insira o histórico profissional do candidato.')
                        ->schema([
                            Forms\Components\Repeater::make('recruitJobs')
                                ->label('Experiências Profissionais')
                                ->itemLabel(fn(array $state): ?string => ($state['job_title'] && $state['company_name']) ? "{$state['job_title']} | {$state['company_name']}" : null)
                                ->relationship()
                                ->columns(2)
                                ->orderColumn('sort')
                                ->schema(
                                    [
                                        Forms\Components\Toggle::make('is_current')
                                            ->label('Atual')
                                            ->live()
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('job_title')
                                            ->label('Cargo')
                                            ->columnSpan(1)
                                            ->maxLength(255)
                                            ->required()
                                            ->live(onBlur: true),
                                        Forms\Components\TextInput::make('company_name')
                                            ->label('Empresa')
                                            ->columnSpan(1)
                                            ->maxLength(255)
                                            ->required()
                                            ->live(onBlur: true),
                                        Forms\Components\Select::make('type')
                                            ->label('Tipo')
                                            ->columnSpanFull()
                                            ->options(JobType::class)
                                            ->enum(JobType::class),
                                        Forms\Components\DatePicker::make('started_at')
                                            ->label('Início')
                                            ->columnSpan(1)
                                            ->date(),
                                        Forms\Components\DatePicker::make('ended_at')
                                            ->label('Término')
                                            ->columnSpan(1)
                                            ->disabled(fn(Get $get): bool => $get('is_current'))
                                            ->date(),
                                        Forms\Components\RichEditor::make('description')
                                            ->label('Descrição')
                                            ->columnSpanFull()
                                            ->placeholder('Descreva as atividades realizadas no cargo.')
                                    ]
                                )
                                ->columnSpanFull()
                                ->addActionLabel('Adicionar Experiência')
                                ->reorderableWithButtons()
                                ->collapsed()
                                ->cloneable()
                                ->defaultItems(0)
                                ->deleteAction(
                                    fn(Action $action) => $action->requiresConfirmation(),
                                )
                        ]),
                    Wizard\Step::make('Cursos de Aperfeiçoamento')
                        ->icon('heroicon-o-academic-cap')
                        ->description('Insira os cursos de aperfeiçoamento do candidato.')

                        ->schema([
                            Forms\Components\Repeater::make('recruitCourses')
                                ->label('Cursos')
                                ->itemLabel(fn(array $state): ?string => ($state['name'] && $state['institution']) ? "{$state['name']} | {$state['institution']}" : null)
                                ->relationship()
                                ->columns(2)
                                ->orderColumn('sort')
                                ->schema(
                                    [
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nome')
                                            ->columnSpan(1)
                                            ->live(onBlur: true)
                                            ->maxLength(255)
                                            ->required(),
                                        Forms\Components\TextInput::make('institution')
                                            ->label('Instituição')
                                            ->columnSpan(1)
                                            ->live(onBlur: true)
                                            ->maxLength(255)
                                            ->required(),
                                        Forms\Components\Select::make('status')
                                            ->label('Status')
                                            ->columnSpanFull()
                                            ->options([
                                                'completed' => 'Concluído',
                                                'in_progress' => 'Em andamento',
                                                'dropped_out' => 'Incompleto',
                                            ]),
                                        Forms\Components\DatePicker::make('started_at')
                                            ->label('Início')
                                            ->columnSpan(1)
                                            ->date(),
                                        Forms\Components\DatePicker::make('ended_at')
                                            ->label('Término')
                                            ->columnSpan(1)
                                            ->date(),
                                        Forms\Components\RichEditor::make('description')
                                            ->label('Descrição')
                                            ->columnSpanFull()
                                            ->placeholder('Descreva o conteúdo do curso.')
                                    ]
                                )
                                ->columnSpanFull()
                                ->addActionLabel('Adicionar Curso')
                                ->reorderableWithButtons()
                                ->collapsed()
                                ->cloneable()
                                ->defaultItems(0)
                                ->deleteAction(
                                    fn(Action $action) => $action->requiresConfirmation(),
                                )
                        ])
                ])
                    ->columnSpanFull()
                    ->columns(2)
                    ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        type="submit"
                        size="md"
                    >
                        Salvar
                    </x-filament::button>
                BLADE)))


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome'),
                TextColumn::make('cpf')
                    ->label('CPF'),
                TextColumn::make('phone')
                    ->label('Telefone'),
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
            'index' => Pages\ListRecruits::route('/'),
            'create' => Pages\CreateRecruit::route('/create'),
            'edit' => Pages\EditRecruit::route('/{record}/edit'),
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
