<?php

namespace App\Console\Commands;

use GraphAware\Neo4j\Client\ClientBuilder;
use Illuminate\Console\Command;

class CleanMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:cleanMovies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $neo4j = ClientBuilder::create()
                              ->addConnection('default', config('database.connections.neo4j.host') )
                              ->build();

        $query = 'MATCH (n:Movie) DETACH DELETE n';

        return $neo4j->run($query);
    }
}
