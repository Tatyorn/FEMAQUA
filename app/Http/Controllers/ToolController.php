<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListToolsResource;
use App\Queries\ListToolsQuery;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index(ListToolsQuery $toolsQuery)
    {
        $tools = $toolsQuery->get();

        return ListToolsResource::collection($tools);
    }

}
