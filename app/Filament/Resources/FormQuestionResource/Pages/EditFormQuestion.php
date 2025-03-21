<?php

declare(strict_types=1);

namespace App\Filament\Resources\FormQuestionResource\Pages;

use App\Filament\Resources\FormQuestionResource;
use App\Livewire\FormQuestionForm;
use App\Models\SystemChatParameter;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\EditRecord;

class EditFormQuestion extends EditRecord
{
    protected static string $resource = FormQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('Send selected data to (ai) process')
                ->form([
                    Select::make('fields')
                        ->options(SystemChatParameter::all()->pluck('form_field_name', 'form_field_id'))
                        ->multiple(),
                ])
                ->action(function (array $data): void {
                    // dispatc job with data
                    dump($data);
                })
                ->slideOver(),
            Action::make('Send all data to (ai) process')
                ->action(function (FormQuestionForm $form) {})
                ->requiresConfirmation(),

        ];
    }
}
