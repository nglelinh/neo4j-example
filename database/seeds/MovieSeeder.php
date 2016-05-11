<?php
/**
 * Created by PhpStorm.
 * User: nglelinh
 * Date: 11/05/2016
 * Time: 08:13
 */

use GraphAware\Neo4j\Client\ClientBuilder;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$neo4j = ClientBuilder::create()
							  ->addConnection('default', config('database.connections.neo4j.host') )
							  ->build();

		$query = trim(file_get_contents(storage_path('movies.cypher')));

		$neo4j->run($query);
	}
}