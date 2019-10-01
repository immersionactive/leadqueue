<?php

namespace App\Providers;

use App\DestinationConfigTypeRegistry;
use App\SourceConfigTypeRegistry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Sets third party service providers that are only needed on local/testing environments
        if ($this->app->environment() !== 'production') {
            /**
             * Loader for registering facades.
             */
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();

            // Load third party local aliases
            $loader->alias('Debugbar', \Barryvdh\Debugbar\Facade::class);
        }

        $this->app->singleton(SourceConfigTypeRegistry::class);
        $this->app->singleton(DestinationConfigTypeRegistry::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        /*
         * Application locale defaults for various components
         *
         * These will be overridden by LocaleMiddleware if the session local is set
         */

        // setLocale for php. Enables ->formatLocalized() with localized values for dates
        setlocale(LC_TIME, config('app.locale_php'));

        // setLocale to use Carbon source locales. Enables diffForHumans() localized
        Carbon::setLocale(config('app.locale'));

        /*
         * Set the session variable for whether or not the app is using RTL support
         * For use in the blade directive in BladeServiceProvider
         */
        if (! app()->runningInConsole()) {
            if (config('locale.languages')[config('app.locale')][2]) {
                session(['lang-rtl' => true]);
            } else {
                session()->forget('lang-rtl');
            }
        }

        // Force SSL in production
        /*if ($this->app->environment() === 'production') {
            URL::forceScheme('https');
        }*/

        // Set the default template for Pagination to use the included Bootstrap 4 template
        \Illuminate\Pagination\AbstractPaginator::defaultView('pagination::bootstrap-4');
        \Illuminate\Pagination\AbstractPaginator::defaultSimpleView('pagination::simple-bootstrap-4');

        // Custom Blade Directives

        /*
         * The block of code inside this directive indicates
         * the project is currently running in read only mode.
         */
        Blade::if('readonly', function () {
            return config('app.read_only');
        });

        /*
         * The block of code inside this directive indicates
         * the chosen language requests RTL support.
         */
        Blade::if('langrtl', function ($session_identifier = 'lang-rtl') {
            return session()->has($session_identifier);
        });

        $this->app->make(SourceConfigTypeRegistry::class);
        $this->app->make(DestinationConfigTypeRegistry::class);

        Blade::directive('request_headers_json', function ($expression) {

            // Yes, this is really how custom directives work in Blade. Yikes.

            return '<?php
                $request_headers_json = ' . $expression . ';
                $request_headers_object = json_decode($request_headers_json);
                // Convert the object to an array, so we can sort it alphabetically
                $request_headers_array = [];
                foreach ($request_headers_object as $key => $value) {
                    $request_headers_array[$key] = $value;
                }
                ksort($request_headers_array);
                // Render output
                echo "<table class=\"table table-bordered\">";
                echo "<tbody>";
                foreach ($request_headers_array as $header_name => $header_values) {
                    $is_first = true;
                    foreach ($header_values as $header_value) {
                        echo "<tr>";
                        if ($is_first) {
                            echo "<th scope=\"row\"" . (count($header_values) > 1 ? " rowspan=\"" . count($header_values) . "\"" : "") . ">" . e($header_name) . "</th>";
                            $is_first = false;
                        }
                        echo "<td>" . e($header_value) . "</td>";
                        echo "</tr>";
                    }
                }
                echo "</tbody>";
                echo "</table>";
                ?>';

        });

        Blade::directive('form_request_body_json', function ($expression) {

            return '<?php
                $request_body_json = ' . $expression . ';
                if (mb_strlen($request_body_json) > 0) {
                    $request_body = json_decode($request_body_json);
                    echo "<table class=\"table table-bordered\">";
                    echo "<tbody>";
                    foreach ($request_body as $name => $value) {
                        echo "<tr>";
                        echo "<th scope=\"row\">" . e($name) . "</th>";
                        echo "<td>" . e($value) . "</td>";
                        echo "</tr>";                        
                    }
                    echo "</tbody>";
                    echo "</table>";
                }
                ?>';

        });

    }
}
