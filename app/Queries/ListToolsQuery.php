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

        $this->setFilters();

        parent::__construct($query->getQuery());
        $this->setModel($query->getModel());
    }

    private function setFilters(): void
    {
        $request = request();
        $queryParams = $request->query();
        unset($queryParams['sort']);

        if (! empty($queryParams)) {
            $request->merge([
                'filter' => array_merge(
                    $request->input('filter', []),
                    $queryParams
                ),
            ]);
        }
    }
}
