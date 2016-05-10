<?php

namespace App\Providers;

use GraphAware\Neo4j\Client\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class Neo4jServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('GraphAware\Neo4j\Client\Client', function ($app) {
            return ClientBuilder::create()
                                ->addConnection('default', config('database.connections.neo4j.host') )
                                ->build();
        });
    }
}
