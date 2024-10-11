<?php

namespace Fadllabanie\EnvEditor;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class EnvEditorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes from the package
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'env-editor');
        $this->publishes([
            __DIR__ . '/../config/env-editor.php' => config_path('env-editor.php'),
        ], 'config');

    }

    public function register()
    {
        if (is_null(env('ENV_EDITOR_USERNAME')) || is_null(env('ENV_EDITOR_PASSWORD'))) {
            $envUsername = 'ENV_EDITOR_USERNAME=' . Crypt::encryptString(Str::random(24));
            $envPassword = 'ENV_EDITOR_PASSWORD=' . Crypt::encryptString(Str::random(24));

            // Append to .env file
            File::append(base_path('.env'), "\n$envUsername\n$envPassword\n");
        }


        $this->app->singleton('env-editor', function ($app) {
            return new EnvEditor();
        });
    }
}
