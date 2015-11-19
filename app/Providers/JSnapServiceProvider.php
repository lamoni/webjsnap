<?php

namespace App\Providers;

use \Lamoni\JSnapCommander\JSnapCommander;
use \Lamoni\JSnapCommander\JSnapTriggerDriver\JSnapTriggerDriverShell;
use \Lamoni\JSnapCommander\JSnapConfig\JSnapConfigTrigger\JSnapConfigTriggerShell;
use \Lamoni\JSnapCommander\JSnapIODriver\JSnapIODriverFiles;
use \Lamoni\JSnapCommander\JSnapConfig\JSnapConfigIO\JSnapConfigIOFiles;

use Illuminate\Support\ServiceProvider;

class JSnapServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Lamoni\JSnapCommander\JSnapCommander', function ($app) {
            return new JSnapCommander(
                new JSnapTriggerDriverShell(
                    new JSnapConfigTriggerShell(
                        \Config::get('jsnap.trigger')
                    )
                ),
                new JSnapIODriverFiles(
                    new JSnapConfigIOFiles(
                        \Config::get('jsnap.io')
                    )
                )
            );
        });
    }

    public function provides()
    {
        return ['Lamoni\JSnapCommander\JSnapCommander'];
    }
}