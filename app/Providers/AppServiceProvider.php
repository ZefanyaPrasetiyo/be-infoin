<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (env('APP_ENV') === 'production') {
            $this->app->bind('path.public', function () {
                return base_path('public');
            });
        }
    }

    public function boot(): void
    {
        $this->configureDefaults();
        
        // MATIKAN SEMUA YG PAKAI VIEW
        if (env('APP_ENV') === 'production') {
            // Ganti view resolver dengan dummy
            $this->app->bind('view', function () {
                return new class {
                    public function make($view, $data = [], $mergeData = [])
                    {
                        return response()->json([
                            'error' => 'View not supported in API mode'
                        ], 500);
                    }
                };
            });
        }
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);
        DB::prohibitDestructiveCommands(app()->isProduction());
        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)->mixedCase()->letters()->numbers()->symbols()->uncompromised()
            : null,
        );
    }
}