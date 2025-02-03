<?php

namespace SchantlDev\StatamicPhosphoricons;

use Illuminate\Support\Facades\File;
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
            if (! File::exists(resource_path('svg/phosphoricons'))) {
                File::link(__DIR__.'/../assets', resource_path('svg/phosphoricons'));
            }

            Sets::setIconsDirectory(resource_path('svg/phosphoricons'), config('phosphoricons.variant', 'regular'));
        }
    }
}
