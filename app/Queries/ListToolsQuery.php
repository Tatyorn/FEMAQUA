<?php

namespace App\Queries;

use App\Models\Tool;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ListToolsQuery extends QueryBuilder
{
    public function __construct()
    {
        $query = Tool::query()->with('tags');
        parent::__construct($query);

        $this->allowedFilters(AllowedFilter::scope('tag', 'byTag'))
            ->defaultSort('-id')
            ->allowedSorts('created_at', 'title', 'id');
    }
}
