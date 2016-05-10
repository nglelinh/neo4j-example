<?php
/**
 * Created by PhpStorm.
 * User: nglelinh
 * Date: 08/05/2016
 * Time: 21:52
 */

namespace App\Http\Controllers;


use GraphAware\Neo4j\Client\Client;
use Illuminate\Support\Facades\Input;

class GraphController extends Controller
{
	protected $neo4j;

	public function __construct(Client $neo4j)
	{
		$this->neo4j = $neo4j;
	}
	
	public function index()
	{
		$limit = Input::get('limit', 50);
		$params = ['limit' => $limit];
		$query = 'MATCH (m:Movie)<-[r:ACTED_IN]-(p:Person) RETURN m,r,p LIMIT {limit}';
		$result = $this->neo4j->run($query, $params);

		$nodes = [];
		$edges = [];
		$identityMap = [];

		foreach ($result->records() as $record){
			$nodes[] = [
				'title' => $record->get('m')->value('title'),
				'label' => $record->get('m')->labels()[0]
			];
			$identityMap[$record->get('m')->identity()] = count($nodes)-1;
			$nodes[] = [
				'title' => $record->get('p')->value('name'),
				'label' => $record->get('p')->labels()[0]
			];
			$identityMap[$record->get('p')->identity()] = count($nodes)-1;

			$edges[] = [
				'source' => $identityMap[$record->get('r')->startNodeIdentity()],
				'target' => $identityMap[$record->get('r')->endNodeIdentity()]
			];
		}

		$data = [
			'nodes' => $nodes,
			'links' => $edges
		];

		return response()->json($data);
	}
}