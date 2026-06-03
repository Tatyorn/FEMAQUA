<?php

namespace App\Observers;

use App\Models\Tool;

class ToolObserver
{
    /**
     * Handle the Tool "deleting" event.
     */
    public function deleting(Tool $tool): void
    {
        $tool->tags()->detach();
    }
}
