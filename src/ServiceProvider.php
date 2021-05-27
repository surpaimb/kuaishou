<?php

namespace Surpaimb\KuaiShou;

use Surpaimb\KuaiShou\Factory;
use Surpaimb\KuaiShou\MiniProgram\Application as MiniProgram;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;
class ServiceProvider extends LaravelServiceProvider
{
    protected $defer = true;

    /**
     * Boot the provider.
     */
    public function boot()
    {
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/config/kuaishou.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('kuaishou.php')], 'kuaishou');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('kuaishou');
        }

        $this->mergeConfigFrom($source, 'kuaishou');
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->setupConfig();

        $apps = [
            'mini_program' => MiniProgram::class,
        ];

        foreach ($apps as $name => $class) {
            if (empty(config('kuaishou.'.$name))) {
                continue;
            }

            if (!empty(config('kuaishou.'.$name.'.app_id')) ) {
                $accounts = [
                    'default' => config('kuaishou.'.$name),
                ];
                config(['kuaishou.'.$name.'.default' => $accounts['default']]);
            } else {
                $accounts = config('kuaishou.'.$name);
            }

            foreach ($accounts as $account => $config) {
                $this->app->singleton("kuaishou.{$name}.{$account}", function ($laravelApp) use ($name, $account, $config, $class) {
                    $app = new $class(array_merge(config('kuaishou.defaults', []), $config));
                    if (config('kuaishou.defaults.use_laravel_cache')) {
                        $app['cache'] = $laravelApp['cache.store'];
                    }
                    $app['request'] = $laravelApp['request'];

                    return $app;
                });
            }
            $this->app->alias("kuaishou.{$name}.default", 'kuaishou.'.$name);

            $this->app->alias('kuaishou.'.$name, $class);
        }
    }
}