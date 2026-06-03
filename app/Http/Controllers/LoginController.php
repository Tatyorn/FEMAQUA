<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Autenticação', description: 'Endpoints de autenticação de usuários')]
class LoginController extends Controller
{
    #[OA\Post(
        path: '/login',
        description: 'Autentica um usuário e retorna um token de acesso.',
        summary: 'Login de usuário',
        tags: ['Autenticação']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', description: 'E-mail do usuário', type: 'string', format: 'email', example: 'user@example.com'),
                new OA\Property(property: 'password', description: 'Senha do usuário', type: 'string', format: 'password', example: 'secret'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Login realizado com sucesso.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'access_token', type: 'string', example: '1|abc123token'),
                new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
            ]
        )
    )]
    #[OA\Response(response: 422, description: 'Credenciais inválidas ou erro de validação.')]
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! auth()->attempt($validated)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais informadas estão incorretas.'],
            ]);
        }

        /** @var User $user */
        $user = auth()->user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);

    }
}
