<?php

namespace App\Http\Controllers;

use App\Actions\CreateToolAction;
use App\Http\Requests\StoreToolRequest;
use App\Http\Resources\StoreToolResource;
use App\Http\Resources\ListToolsResource;
use App\Queries\ListToolsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class ToolController extends Controller
{
    public function index(ListToolsQuery $toolsQuery): AnonymousResourceCollection
    {
        $tools = $toolsQuery->get();

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
}
