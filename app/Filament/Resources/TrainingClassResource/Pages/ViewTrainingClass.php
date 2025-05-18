<?php

namespace App\Filament\Resources\TrainingClassResource\Pages;

use App\Filament\Resources\TrainingClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainingClass extends ViewRecord
{
    protected static string $resource = TrainingClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
