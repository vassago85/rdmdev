<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action): void {
                    $record = $this->getRecord();

                    if ($record->id === Auth::id()) {
                        Notification::make()
                            ->title('You cannot delete your own account.')
                            ->danger()
                            ->send();
                        $action->cancel();
                        return;
                    }

                    if (User::count() <= 1) {
                        Notification::make()
                            ->title('Cannot delete the last remaining user.')
                            ->body('Create another admin first.')
                            ->danger()
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }
}
