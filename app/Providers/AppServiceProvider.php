<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use L5Swagger\L5SwaggerServiceProvider;

use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", description: "API para gerenciamento de ferramentas úteis", title: "VUTTR (Very Useful Tools to Remember) API")]
#[OA\Server(url: "/", description: "Servidor Principal")]
#[OA\Contact(email: "suporte@exemplo.com")]
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
