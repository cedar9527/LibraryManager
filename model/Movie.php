<?php
namespace model;
use \utils\db;
use \io;
public class Movie implements SplSubject {
  private $_id;
  private $_title;
  private $_author;
  private $_year;
  private $_description;
  private $_content;
  private $_observers;
  private $_pdoProvider;

/**
 * Initializes an instance.
 * @param $dbProvider IDbProvider
 * @param $title string
 * @param $author string
 * @param $year int
 * @param $description string
 * @param $content string
 */
  public function __constructor(IPdoDbProvider $pdoProvider, $title, $author, $year,
    $description, $content) {

    $this->$_pdoProvider = $pdoProvider;
    $this->_title = $title;
    $this->_author = $author;
    $this->_year = $year;
    $this->_description = $description;
    $this->_content = $content;
    $this->_observers = new SplObjectStorage();
  }

/**
 * Extracts a movie from an hash with the following keys: titre, realisateur,
 *   annee, description, contenu
 * @param $record array an hash
 */
  public static function extract(array $record) {
    return new Movie($this->_pdoProvider,
      $record["titre"],
      $record["realisateur"],
      $record["annee"],
      $record["description"],
      $record["contenu"]
    );
  }

  /**
   * Returns a batch of at most $batchCnt Movies
   * @param $batchCnt int the maximum number of Movies to retrieve
   * @see \utils\db\RecordsCollection
   */
  public function getBatch($batchCnt) {
    $recordsCollection = new RecordsCollection();
    $movies = $recordsCollection->getBatch($this->_pdoProvider, 'get_movies_batch',
      array(__CLASS__, 'extract'), $batchCnt
    );
    return $movies;
  }

  /**
	 * Save this movie in database.
	 */
	public function save() {
		if($this->_id != undefined) {
      $params = array(
        "id" => array("value" => $this->_id, "type" => PDO::PARAM_INT),
        "titre" => array( "value" => $this->_title, "type" => PDO::PARAM_STR),
  			"realisateur" => array( "value" => $this->_author, "type" => PDO::PARAM_STR),
  			"annee" => array( "value" => $this->_year, "type" => PDO::PARAM_INT),
        "description" => array( "value" => $this->_description, "type" => PDO::PARAM_STR),
  			"contenu" => array( "value" => $this->_content, "type" => PDO::PARAM_LOB)
  		);
			$this->_pdoProvider->exec('update_film', $params);
		} else {
      $params = array(
        "id" => array( "value" => 0, "type" => PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT),
  			"titre" => array( "value" => $this->_title, "type" => PDO::PARAM_STR),
  			"realisateur" => array( "value" => $this->_author, "type" => PDO::PARAM_STR),
  			"annee" => array( "value" => $this->_year, "type" => PDO::PARAM_INT),
        "description" => array( "value" => $this->_description, "type" => PDO::PARAM_STR),
  			"contenu" => array( "value" => $this->_content, "type" => PDO::PARAM_LOB)
  		);
			$this->_pdoProvider->exec('create_film', $params);
		}
	}

	/**
	 * Removes this movie from the database.
	 */
	public function delete() {
		$params = array (
			"id" => array("value" => $this->_id, "type" => PDO::PARAM_INT)
		);
		$this->_pdoProvider->exec('delete_film', $params);
	}

  /**
   * Adds an observer to this subject.
   * @param $observer SplObserver
   */
  public function attach(SplObserver $observer) {
    $this->_observers->attach($observer);
  }
  /**
   * Removes an observer from this subject.
   * @param $observer SplObserver
   */
  public function removeObserver(SplObserver $observer) {
      $this->_observers->detach($observer);
  }
  /**
   * Notifies observers about a change.
   */
  public function notify() {
    foreach($this->_observers as $observer) {
      $observer->update($this);
    }
  }
}
