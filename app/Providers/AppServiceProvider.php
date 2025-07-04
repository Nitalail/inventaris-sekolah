<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\AuthHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register AuthHelper as a global helper
        Blade::directive('authuser', function () {
            return "<?php echo App\Helpers\AuthHelper::userName(); ?>";
        });

        Blade::directive('authinitials', function () {
            return "<?php echo App\Helpers\AuthHelper::userInitials(); ?>";
        });

        Blade::directive('authcheck', function () {
            return "<?php echo App\Helpers\AuthHelper::check(); ?>";
        });
    }
}
