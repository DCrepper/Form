<?php

declare(strict_types=1);

namespace App\Filament\Resources\RequestQuoteResource\Pages;

use App\Enums\ProjectStatus;
use App\Filament\Resources\RequestQuoteResource;
use App\Mail\QuotationSendedToUser;
use App\Models\Project;
use App\Models\RequestQuote;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EditRequestQuote extends EditRecord
{
    protected static string $resource = RequestQuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ActionGroup::make([
                Action::make('convertToProject')
                    ->label('Convert to Project')
                    ->form([
                        TextInput::make('project_name')
                            ->label('Project Name')
                            ->required()
                            ->maxLength(255),
                        Select::make('status')
                            ->label('Project Status')
                            ->options(ProjectStatus::class)
                            ->default(ProjectStatus::PENDING)
                            ->required(),
                    ])
                    ->action(function (array $data, RequestQuote $record) {

                        $project = Project::create([
                            'name' => $data['project_name'],
                            'start_date' => now(),
                            'end_date' => now()->addDays(30), // Example: 30 days from now
                            'status' => $data['status'],
                        ]);

                        return redirect()->route('filament.admin.resources.projects.edit', ['record' => $project->id]);

                    })
                    ->color('primary'),
                Action::make('createPdf')
                    ->label('createPdf')
                    ->action('createPdf')
                    ->color('secondary'),
                Action::make('sendToEmail')
                    ->label('Send to Email')
                    ->action('createPdfAndSendToCurrentUser')
                    ->color('secondary'),
            ]),
        ];
    }

    public function createPdf()
    {
        $record = $this->record;

        return redirect()->route('quotation.preview.id', ['requestQuote' => $record->id]);
    }

    public function createPdfAndSendToCurrentUser()
    {
        $record = $this->record;
        Mail::to(Auth::user()->email)->send(new QuotationSendedToUser($record));
        Notification::make()
            ->title('Quotation has been sent to your email')
            ->success()
            ->send();
    }
}
