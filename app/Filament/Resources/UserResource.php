<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Sección de información básica
                Forms\Components\Section::make('Información básica')
                    ->description('Datos principales del usuario')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nombre'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email verificado'),

                        Forms\Components\Select::make('role')
                            ->options([
                                'Student' => 'Student',
                                'Coach' => 'Coach',
                                'Admin' => 'Admin',
                            ])
                            ->required()
                            ->label('Rol'),
                    ])
                    ->columns(2), // Organiza los campos en 2 columnas

                // Sección de seguridad
                Forms\Components\Section::make('Seguridad')
                    ->description('Contraseña y configuración de acceso')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) =>
                            filled($state) ? bcrypt($state) : null)
                            ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255)
                            ->label('Contraseña'),
                    ])
                    ->collapsible(), // Permite colapsar esta sección

                // Sección de preferencias
                Forms\Components\Section::make('Preferencias')
                    ->description('Opciones y configuración del usuario')
                    ->schema([
                        Forms\Components\TextInput::make('language')
                            ->required()
                            ->maxLength(255)
                            ->default('es')
                            ->label('Idioma'),

                        Forms\Components\TextInput::make('profile_picture_url')
                            ->maxLength(255)
                            ->label('URL de imagen de perfil'),
                    ])
                    ->collapsed(), // Inicia esta sección colapsada

                // Sección de integración de pagos
                Forms\Components\Section::make('Información de pago')
                    ->description('Datos de integración con Stripe')
                    ->schema([
                        Forms\Components\TextInput::make('stripe_customer_id')
                            ->maxLength(255)
                            ->label('ID de cliente en Stripe'),

                        Forms\Components\TextInput::make('stripe_account_id')
                            ->maxLength(255)
                            ->label('ID de cuenta en Stripe'),
                    ])
                    ->collapsed() // Inicia esta sección colapsada
                    ->hidden(fn ($livewire) => $livewire instanceof Pages\CreateUser), // Oculta en creación
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('language')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
