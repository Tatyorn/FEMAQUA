<?php

namespace App\Http\Controllers;

use App\Actions\CreateToolAction;
use App\Http\Requests\StoreToolRequest;
use App\Http\Resources\StoreToolResource;
use App\Http\Resources\ListToolsResource;
use App\Models\Tool;
use App\Queries\ListToolsQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class ToolController extends Controller
{
    public function index(ListToolsQuery $toolsQuery): AnonymousResourceCollection
    {
        $tools = QueryBuilder::for($toolsQuery)
            ->allowedFilters(AllowedFilter::scope('tag', 'byTag'))
            ->defaultSort('-id')
            ->allowedSorts('created_at', 'title', 'id')
            ->get();

        return ListToolsResource::collection($tools);
    }

    /**
     * @throws Throwable
     */
    public function store(StoreToolRequest $request, CreateToolAction $createToolAction): StoreToolResource
    {
        $tool = $createToolAction->handle($request->validated());

        return new StoreToolResource($tool);
    }

    public function destroy(Tool $tool): JsonResponse
    {
        $tool->delete();

        return response()->json();
    }
}
