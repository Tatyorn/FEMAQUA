<?php

namespace App\Http\Traits;

trait HasQueryFilters
{
    protected function setFilters(): void
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
