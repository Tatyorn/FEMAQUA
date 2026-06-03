<?php

namespace App\Filament\Resources\Tools\Pages;

use App\Actions\CreateToolAction;
use App\Filament\Resources\Tools\ToolResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CreateTool extends CreateRecord
{
    protected static string $resource = ToolResource::class;

    /**
     * @throws Throwable
     */
    public function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = auth()->id();

        return app(CreateToolAction::class)->handle($data);
    }
}
