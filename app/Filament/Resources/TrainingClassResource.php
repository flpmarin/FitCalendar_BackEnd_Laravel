<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingClassResource\Pages;
use App\Models\TrainingClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainingClassResource extends Resource
{
    protected static ?string $model = TrainingClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('coach_id')
                    ->relationship('coach', 'id')
                    ->required(),
                Forms\Components\Select::make('sport_id')
                    ->relationship('sport', 'id')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->required(),
                Forms\Components\TextInput::make('duration_minutes')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('location_detail')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_online')
                    ->required(),
                Forms\Components\TextInput::make('price_per_person')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('max_capacity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('min_required')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\DateTimePicker::make('enrollment_deadline'),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('Scheduled'),
                Forms\Components\DateTimePicker::make('cancelled_at'),
                Forms\Components\TextInput::make('cancelled_reason')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('coach.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sport.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_detail')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_online')
                    ->boolean(),
                Tables\Columns\TextColumn::make('price_per_person')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_capacity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_required')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrollment_deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cancelled_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cancelled_reason')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListTrainingClasses::route('/'),
            'create' => Pages\CreateTrainingClass::route('/create'),
            'view' => Pages\ViewTrainingClass::route('/{record}'),
            'edit' => Pages\EditTrainingClass::route('/{record}/edit'),
        ];
    }
}
