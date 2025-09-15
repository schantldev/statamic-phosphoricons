<?php

namespace SchantlDev\StatamicPhosphoricons;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Fieldtypes\Sets;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    public function bootAddon()
    {
        $this->publishes([
            __DIR__.'/../config/phosphoricons.php' => config_path('phosphoricons.php'),
        ], 'phosphoricons-config');

        $this->publishes([
            __DIR__.'/../assets' => resource_path('svg/phosphoricons'),
        ], 'phosphoricons-assets');
        
        $this->mergeConfigFrom(__DIR__.'/../config/phosphoricons.php', 'phosphoricons');

        if (!$this->app->runningInConsole()) {
            File::ensureDirectoryExists(resource_path('svg'));

            if (! File::exists(resource_path('svg/phosphoricons'))) {
                File::link(__DIR__.'/../assets', resource_path('svg/phosphoricons'));

                // add directory link to .gitignore to prevent problems with deployments
                if (! File::exists(resource_path('svg/.gitignore'))) {
                    File::put(resource_path('svg/.gitignore'), '!.gitignore'.PHP_EOL.'phosphoricons');
                } else if (! Str::of(File::get(resource_path('svg/.gitignore')))->contains('phosphoricons')) {
                    File::append(resource_path('svg/.gitignore'), PHP_EOL.'phosphoricons');
                }
            }

            Sets::useIcons(sprintf('%s/%s', resource_path('svg/phosphoricons'), config('phosphoricons.variant', 'regular')));
        }
    }
}
