<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Registro';
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $modelLabel= 'Clientes Registrados';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información Personal')->schema([
                    Forms\Components\Grid::make(2) // Divide en dos columnas
                        ->schema([
                            TextInput::make('nombres')
                                ->required()->label('Nombres'),
                            TextInput::make('apellidos')
                                ->required()->label('Apellidos'),
                            Select::make('tipo_documento')
                                ->options([
                                    'cedula_de_ciudadania' => 'Cédula de Ciudadanía',
                                    'nit' => 'NIT',
                                    'cedula_extranjera' => 'Cédula Extranjera',
                                ])->required()->label('Tipo de Documento'),
                            TextInput::make('numero_documento')
                                ->numeric()->required()->label('Número de Documento'),
                            TextInput::make('telefono')
                                ->numeric()->required()->label('Teléfono')->prefix('+57'),
                            TextInput::make('ciudad')
                                ->required()->label('Ciudad'),
                            TextInput::make('direccion')
                                ->required()->label('Dirección')->prefixIcon('heroicon-o-home'),
                            TextInput::make('email')
                                ->email()->required()->label('Email'),
                        ])
                ]),

                Section::make('Información del Codeudor')->schema([
                    Forms\Components\Grid::make(2) // Divide en dos columnas
                        ->schema([
                            TextInput::make('codeudor_nombres')
                                ->required()->label('Nombres'),
                            TextInput::make('codeudor_apellidos')
                                ->required()->label('Apellidos'),
                            Select::make('codeudor_tipo_documento')
                                ->options([
                                    'cedula_de_ciudadania' => 'Cédula de Ciudadanía',
                                    'nit' => 'NIT',
                                    'cedula_extranjera' => 'Cédula Extranjera',
                                ])->required()->label('Tipo de Documento'),
                            TextInput::make('codeudor_numero_documento')
                                ->numeric()->required()->label('Número de Documento'),
                            TextInput::make('codeudor_telefono')
                                ->numeric()->required()->label('Teléfono'),
                            TextInput::make('codeudor_ciudad')
                                ->required()->label('Ciudad'),
                            TextInput::make('codeudor_direccion')
                                ->required()->label('Dirección'),
                            TextInput::make('codeudor_email')
                                ->email()->required()->label('Email'),
                        ])
                ]),

                Section::make('Documento de Compromiso')->schema([
                    Forms\Components\FileUpload::make('archivo')
                        ->label('Subir Archivo')
                        ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/*'])
                        ->directory('uploads/documentos')
                        ->maxSize(2048) // Tamaño máximo en KB (2MB)
                        ->preserveFilenames()
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('numero_documento')->label('CC')->searchable(),
            Tables\Columns\TextColumn::make('nombres')->label('Nombres')->searchable(),
            Tables\Columns\TextColumn::make('apellidos')->label('Apellidos')->searchable(),
            Tables\Columns\TextColumn::make('email')->label('Email'),
            Tables\Columns\TextColumn::make('archivo')->label('Garantía')
                ->url(fn($record) => $record->archivo ? Storage::url($record->archivo) : null)
                ->formatStateUsing(fn($state) => 'Ver Documento')
                ->color('info')
                ->openUrlInNewTab()
                ->default('No hay archivo'),
        ])
            ->headerActions([
                Tables\Actions\Action::make('exportPdf')
                    ->label('Exportar a PDF')
                    ->url(route('exportar.clientes.pdf'))
                    ->openUrlInNewTab(true)
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Ver'),
                Tables\Actions\EditAction::make(),

                //->url(fn($record) => route('clientes.show', $record)),
                Tables\Actions\Action::make('Exportar PDF')
                    ->url(fn($record) => route('exportar.cliente.pdf', $record->id))
                    ->color('success')
                    ->label('Exportar PDF')
                    ->openUrlInNewTab(true),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Eliminar'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            // 'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
