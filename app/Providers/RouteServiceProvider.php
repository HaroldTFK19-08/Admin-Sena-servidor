<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Ruta de inicio de cada portal segun el rol del usuario.
     *
     * Se comparte entre el login (LoginController) y el middleware `guest`
     * (RedirectIfAuthenticated) para que ambos redirijan al mismo sitio.
     */
    public const ROLE_HOME = [
        'Admin' => 'admin.home',
        'Instructor' => 'instructor.home',
        'Aprendiz' => 'aprendiz.home',
        'Aspirante' => 'aspirante.home',
    ];

    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(1000)
                ->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // =====================
            // RUTAS GENERALES
            // =====================
            Route::middleware('api')
                ->prefix('v1')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // =====================
            // RUTAS API POR DOMINIO
            // =====================
            $domains = [
                'Auth', 'Usuarios', 'Centros', 'Ambientes', 'Ofertas', 'Noticias',
                'Competencias', 'Areas', 'Asignaciones', 'Equipos', 'Fichas',
                'Programas', 'Resultados', 'Inscripciones',
            ];

            foreach ($domains as $domain) {
                $file = app_path("Modules/{$domain}/Routes/api.php");

                if (file_exists($file)) {
                    Route::middleware('api')
                        ->prefix('v1')
                        ->group($file);
                }
            }
        });
    }
}
