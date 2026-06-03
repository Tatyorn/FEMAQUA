<?php

namespace App\Queries;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Builder;

class ListToolsQuery extends Builder
{
    public function __construct()
    {
        $query = Tool::query()
            ->byUser()
            ->with('tags');

        parent::__construct($query->getQuery());
        $this->setModel($query->getModel());
    }
}
