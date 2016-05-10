<?php

namespace App\Http\Controllers;

use GraphAware\Neo4j\Client\Client;
use Illuminate\Support\Facades\Input;

class SearchController extends Controller
{
	protected $neo4j;

	public function __construct(Client $neo4j)
	{
		$this->neo4j = $neo4j;
	}

	public function index()
	{
		$searchTerm = Input::get('q');
		$term = '(?i).*'.$searchTerm.'.*';
		$query = 'MATCH (m:Movie) WHERE m.title =~ {term} RETURN m';
		$params = ['term' => $term];

		$result = $this->neo4j->run($query, $params);
		$movies = [];
		foreach ($result->records() as $record){
			$movies[] = ['movie' => $record->get('m')->values()];
		}

		return response()->json($movies);
	}

}