<?php

namespace App\Http\Controllers;

use App\Actions\CreateToolAction;
use App\Http\Requests\StoreToolRequest;
use App\Http\Resources\ListToolsResource;
use App\Http\Resources\StoreToolResource;
use App\Models\Tool;
use App\Queries\ListToolsQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[OA\Tag(name: 'Tools', description: 'Endpoints de gerenciamento de ferramentas')]
class ToolController extends Controller
{
    #[OA\Get(
        path: '/tools',
        description: 'Retorna a lista de todas as ferramentas. Permite ordenação e filtro por tag.',
        summary: 'Listar e filtrar ferramentas',
        tags: ['Tools']
    )]
    #[OA\Parameter(name: 'tag', description: 'Filtra as ferramentas por nome de tag (ex: php)', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'sort', description: 'Ordena os resultados. Use o sinal de menos para decrescente (ex: -id, title, created_at)', in: 'query', required: false, schema: new OA\Schema(type: 'string', default: '-id'))]
    #[OA\Response(response: 200, description: 'Lista de ferramentas retornada com sucesso.')]
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
    #[OA\Post(
        path: '/tools',
        description: 'Cria uma nova ferramenta no sistema.',
        summary: 'Cadastrar uma nova ferramenta',
        tags: ['Tools']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['title', 'link'],
            properties: [
                new OA\Property(property: 'title', description: 'Título da ferramenta', type: 'string', example: 'Notion'),
                new OA\Property(property: 'link', description: 'Link da ferramenta', type: 'string', example: 'https://notion.so'),
                new OA\Property(property: 'description', description: 'Descrição opcional', type: 'string', example: 'All-in-one workspace'),
                new OA\Property(property: 'tags', description: 'Array de tags', type: 'array', items: new OA\Items(type: 'string'), example: ['organization', 'planning']),
            ]
        )
    )]
    #[OA\Response(response: 201, description: 'Ferramenta criada com sucesso.')]
    #[OA\Response(response: 422, description: 'Erro de validação nos campos enviados.')]
    public function store(StoreToolRequest $request, CreateToolAction $createToolAction): StoreToolResource
    {
        $tool = $createToolAction->handle($request->validated());

        return new StoreToolResource($tool);
    }

    #[OA\Delete(
        path: '/tools/{id}',
        description: 'Exclui uma ferramenta do banco de dados.',
        summary: 'Remover uma ferramenta',
        tags: ['Tools']
    )]
    #[OA\Parameter(name: 'id', description: 'ID numérico da ferramenta a ser deletada', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Ferramenta removida com sucesso.')]
    #[OA\Response(response: 404, description: 'Ferramenta não encontrada.')]
    public function destroy(Tool $tool): JsonResponse
    {
        $tool->delete();

        return response()->json();
    }
}
