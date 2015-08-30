<?php
namespace utils\db;
class RecordsCollection {
  private $_retrievedCount = 0;


  /**
	 * Reset Batch retrieval.
	 */
	public function resetBatch() {
		$this->_retrievedCount = 0;
	}
	/**
	 * Gets a batch of at most $batchCnt Entities (depending on the callback)
   * @param $pdoProvider IPdoProvider
   * @param $procedure string
   * @param $load callable a method which takes a hash {col => value} and returns an Object
   * @param batchCnt int
   * @example:
   * $recordsCollection = new RecordsCollection();
   * $movies = null;
   * // grab $pdoProvider from somewhere
   * $batchCnt = 20;
   * do {
   *  $movies = $recordsCollection->getBatch($pdoProvider, 'get_movies_batch',
   *     array('\utils\db\Movie', 'load'), $batchCnt
   *  );
   * doSomethingWithMovies($movies);
   * } while(count($movies) > 0);
   *
	 */
	public function getBatch(IPdoProvider $pdoProvider, $procedure, callable $load, $batchCnt) {
		$params = array(
			"ignorePremiers" => array( "value" => self::$this->_retrievedCount, "type" => PDO::PARAM_INT ),
			"tailleBloc" => array( "value" => $batchCnt, "type"=>PDO::PARAM_INT )
		);
		$statement = $pdoProvider->query($procedure, $params);
		$resultSet = $statement->fetchAll(PDO::FETCH_ASSOC);
		$entities = array_map(call_user_func($load), $resultSet);
		return $entities;
	}
}
