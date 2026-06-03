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
use Illuminate\Support\Facades\Gate;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

#[OA\Tag(name: 'Tools (Autenticado)', description: 'Endpoints de gerenciamento de ferramentas do usuário autenticado')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'Insira o token de autenticação (Sanctum/API) no formato: Bearer {token}', bearerFormat: 'JWT', scheme: 'bearer')]
class AuthToolController extends Controller
{
    #[OA\Get(
        path: '/auth/tools',
        description: 'Retorna a lista de ferramentas pertencentes ao usuário autenticado. Permite ordenação e filtro por tag.',
        summary: 'Listar e filtrar ferramentas',
        security: [['bearerAuth' => []]],
        tags: ['Tools (Autenticado)']
    )]
    #[OA\Parameter(name: 'tag', description: 'Filtra as ferramentas por nome de tag (ex: php)', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'sort', description: 'Ordena os resultados. Use o sinal de menos para decrescente (ex: -id, title, created_at)', in: 'query', required: false, schema: new OA\Schema(type: 'string', default: '-id'))]
    #[OA\Response(response: 200, description: 'Lista de ferramentas retornada com sucesso.')]
    #[OA\Response(response: 401, description: 'Não autenticado.')]
    public function index(ListToolsQuery $toolsQuery): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Tool::class);

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
        path: '/auth/tools',
        description: 'Cria uma nova ferramenta associada ao usuário autenticado.',
        summary: 'Cadastrar uma nova ferramenta',
        security: [['bearerAuth' => []]],
        tags: ['Tools (Autenticado)']
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
    #[OA\Response(response: 401, description: 'Não autenticado.')]
    public function store(StoreToolRequest $request, CreateToolAction $createToolAction): StoreToolResource
    {
        Gate::authorize('create', Tool::class);

        $tool = $createToolAction->handle($request->validated());

        return new StoreToolResource($tool);
    }

    #[OA\Delete(
        path: '/auth/tools/{id}',
        description: 'Exclui uma ferramenta do banco de dados se ela pertencer ao usuário autenticado.',
        summary: 'Remover uma ferramenta',
        security: [['bearerAuth' => []]],
        tags: ['Tools (Autenticado)']
    )]
    #[OA\Parameter(name: 'id', description: 'ID numérico da ferramenta a ser deletada', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Ferramenta removida com sucesso.')]
    #[OA\Response(response: 403, description: 'Usuário não tem permissão para deletar esta ferramenta.')]
    #[OA\Response(response: 401, description: 'Não autenticado.')]
    #[OA\Response(response: 404, description: 'Ferramenta não encontrada.')]
    public function destroy(Tool $tool): JsonResponse
    {
        Gate::authorize('delete', $tool);

        $tool->delete();

        return response()->json();
    }
}
