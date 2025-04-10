<?php

declare(strict_types=1);

namespace App\Filament\Resources\WebsiteLanguageResource\Pages;

use App\Filament\Resources\WebsiteLanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWebsiteLanguage extends ViewRecord
{
    protected static string $resource = WebsiteLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
