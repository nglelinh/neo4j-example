<?php
/**
 * Created by PhpStorm.
 * User: nglelinh
 * Date: 08/05/2016
 * Time: 21:49
 */

namespace App\Http\Controllers;


use GraphAware\Neo4j\Client\Client;

class MovieController extends Controller
{
	/**
	 * @var Client
	 */
	protected $neo4j;

	public function __construct(Client $neo4j)
	{
		$this->neo4j = $neo4j;
	}

	public function index($title)
	{
		$query = 'MATCH (m:Movie) WHERE m.title = {title} OPTIONAL MATCH p=(m)<-[r]-(a:Person) RETURN m, collect({rel: r, actor: a}) as plays';
		$params = ['title' => $title];

		$result = $this->neo4j->run($query, $params);

		$movie = $result->firstRecord()->get('m');
		$mov = [
			'title' => $movie->value('title'),
			'cast' => []
		];

		foreach ($result->firstRecord()->get('plays') as $play) {
			$actor = $play['actor']->value('name');
			$job = explode('_', strtolower($play['rel']->type()))[0];
			$mov['cast'][] = [
				'job' => $job,
				'name' => $actor,
				'role' => array_key_exists('roles', $play['rel']->values()) ? $play['rel']->value('roles') : null
			];
		}

		return response()->json($mov);
	}
}