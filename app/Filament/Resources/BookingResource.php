<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Reservas';
    protected static ?string $navigationLabel = 'Reservas';

    // Desactivamos la capacidad de crear reservas desde el panel admin
    public static function canCreate(): bool
    {
        return false; // No permitir creación manual
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student.name')
                    ->label('Estudiante')
                    ->disabled(),

                Forms\Components\TextInput::make('session.sportClass.name')
                    ->label('Clase')
                    ->disabled(),

                Forms\Components\DateTimePicker::make('session.start_time')
                    ->label('Fecha y hora de la sesión')
                    ->disabled(),

                Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pendiente',
                        'Confirmed' => 'Confirmada',
                        'Cancelled' => 'Cancelada',
                        'Completed' => 'Completada',
                    ])
                    ->label('Estado'),

                Forms\Components\TextInput::make('amount')
                    ->label('Importe')
                    ->numeric()
                    ->prefix(fn (callable $get) => $get('currency') === 'EUR' ? '€' : '$'),

                Forms\Components\Select::make('payment_status')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'Completado' => 'Completado',
                    ])
                    ->default('Pendiente')
                    ->label('Estado de pago')
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Estudiante')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('session.sportClass.name')
                    ->label('Clase')
                    ->searchable(),

                Tables\Columns\TextColumn::make('session.start_time')
                    ->label('Fecha y hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Estado')
                    ->colors([
                        'danger' => 'Cancelled',
                        'warning' => 'Pending',
                        'success' => fn ($state) => in_array($state, ['Confirmed', 'Completed']),
                        'gray' => fn ($state) => !in_array($state, ['Cancelled', 'Pending', 'Confirmed', 'Completed']),
                    ]),

                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->label('Estado de pago')
                    ->colors([
                        'warning' => 'Pendiente',
                        'success' => 'Completado',
                    ]),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Importe')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pendiente',
                        'Confirmed' => 'Confirmada',
                        'Cancelled' => 'Cancelada',
                        'Completed' => 'Completada',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'Completado' => 'Completado',
                    ])
                    ->label('Estado de pago'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Creada desde'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Creada hasta'),
                    ])
                    ->query(function (EloquentBuilder $query, array $data): EloquentBuilder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (EloquentBuilder $query, $date): EloquentBuilder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (EloquentBuilder $query, $date): EloquentBuilder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('confirm')
                    ->label('Confirmar')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(function (Booking $record) {
                        $record->update(['status' => 'Confirmed']);
                    })
                    ->visible(fn (Booking $record) => $record->status === 'Pending'),

                Tables\Actions\Action::make('cancel')
                    ->label('Cancelar')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->action(function (Booking $record) {
                        $record->update(['status' => 'Cancelled']);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record) => in_array($record->status, ['Pending', 'Confirmed'])),

                Tables\Actions\Action::make('markAsPaid')
                    ->label('Marcar como pagado')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Booking $record) => $record->payment_status === 'Pendiente')
                    ->action(fn (Booking $record) => $record->markAsPaid())
                    ->requiresConfirmation()
                    ->modalHeading('¿Marcar reserva como pagada?')
                    ->modalDescription('Confirma que has recibido el pago para esta reserva.')
                    ->modalSubmitActionLabel('Sí, marcar como pagada')
                    ->successNotificationTitle('Reserva marcada como pagada'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('confirmSelected')
                        ->label('Confirmar seleccionadas')
                        ->color('success')
                        ->icon('heroicon-o-check')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                if ($record->status === 'Pending') {
                                    $record->update(['status' => 'Confirmed']);
                                }
                            });
                        }),

                    Tables\Actions\BulkAction::make('markSelectedAsPaid')
                        ->label('Marcar como pagadas')
                        ->color('success')
                        ->icon('heroicon-o-banknotes')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                if ($record->payment_status === 'Pendiente') {
                                    $record->markAsPaid();
                                }
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('¿Marcar reservas seleccionadas como pagadas?')
                        ->modalDescription('Confirma que has recibido el pago para estas reservas.')
                        ->modalSubmitActionLabel('Sí, marcar como pagadas')
                        ->successNotificationTitle('Reservas marcadas como pagadas'),
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
            'index' => Pages\ListBookings::route('/'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
