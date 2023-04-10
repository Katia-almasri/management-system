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
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            //ceo api custome route
            Route::prefix('ceo-api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/ceo_api.php'));

             //sales manager api custome route
             Route::prefix('sales-api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/sales_api.php'));

             Route::prefix('machenism-api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/machenism_api.php'));

             Route::prefix('selling-port-api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/selling_port_api.php'));

             Route::prefix('farms-api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/farms_api.php'));

             Route::prefix('production-api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/prodaction_api.php'));


            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
