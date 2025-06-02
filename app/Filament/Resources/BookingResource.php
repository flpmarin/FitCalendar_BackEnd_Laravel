<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Reservas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('specific_availability_id')
                    ->relationship('specificAvailability', 'id')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('type')
                    ->required(),

                Forms\Components\DateTimePicker::make('session_at')
                    ->required(),

                Forms\Components\TextInput::make('session_duration_minutes')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pendiente',
                        'Confirmed' => 'Confirmada',
                        'Cancelled' => 'Cancelada',
                        'Completed' => 'Completada',
                        'Rejected' => 'Rechazada',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('total_amount')
                    ->numeric()
                    ->prefix('€'),

                Forms\Components\TextInput::make('platform_fee')
                    ->numeric()
                    ->prefix('€'),

                Forms\Components\TextInput::make('currency')
                    ->default('EUR'),

                Forms\Components\TextInput::make('payment_status'),

                Forms\Components\DateTimePicker::make('cancelled_at'),

                Forms\Components\TextInput::make('cancelled_reason')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')->label('Estudiante'),
                Tables\Columns\TextColumn::make('specificAvailability.coach.name')->label('Coach'),
                Tables\Columns\TextColumn::make('specificAvailability.date')->label('Fecha'),
                Tables\Columns\TextColumn::make('specificAvailability.start_time')->label('Inicio'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('total_amount')->money('eur'),
                Tables\Columns\TextColumn::make('session_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
