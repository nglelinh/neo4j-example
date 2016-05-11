<?php

namespace database\seeds;


use GraphAware\Neo4j\Client\ClientBuilder;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
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

		$faker = \Faker\Factory::create();
		$faker->seed(5);

		for ($i = 0; $i<=10; $i++)
		{
			$movieName = $faker->streetName;
			$query = 'CREATE (' . $movieName . ':Movie {title:\' ' . $movieName . '\', released:' . $faker->year . ', tagline:\' ' . $faker->realText(20)  . ' \'})';

			$faker->name;

		}
	}
}