<?php
namespace model;
public class Movie implements SplSubject {
  private $_id;
  private $_title;
  private $_author;
  private $_year;
  private $_description;
  private $_content;
  private $_observers;

  public function __constructor(IDbProvider $dbProvider, $title, $author, $year,
  $description, $content) {
    $this->_title = $title;
    $this->_author = $author;
    $this->_year = $year;
    $this->_description = $description;
    $this->_content = $content;
    $this->_observers = new SplObjectStorage();
  }

  /**
	 * Save this movie in database.
	 */
	public function save() {
		$params = array(
			"id" => array( "value" => $this->_id, "type" => PDO::PARAM_INT)
		);
		$movieStatement = $this->_dbProvider->query('get_movie', $params);
		$movie = $movieStatement->fetch(PDO::FETCH_ASSOC):

		if($movie !== false) {
      $params = array(
        "titre" => array( "value" => $this->_title, "type" => PDO::PARAM_STR),
  			"realisateur" => array( "value" => $this->_author, "type" => PDO::PARAM_STR),
  			"annee" => array( "value" => $this->_year, "type" => PDO::PARAM_INT),
        "description" => array( "value" => $this->_description, "type" => PDO::PARAM_STR),
  			"contenu" => array( "value" => $this->_content, "type" => PDO::PARAM_LOB)
  		);
			$this->_dbProvider->exec('update_film', $params);
		} else {
      $params = array(
        "id" => array( "value" => $this->_id, "type" => PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT),
  			"titre" => array( "value" => $this->_title, "type" => PDO::PARAM_STR),
  			"realisateur" => array( "value" => $this->_author, "type" => PDO::PARAM_STR),
  			"annee" => array( "value" => $this->_year, "type" => PDO::PARAM_INT),
        "description" => array( "value" => $this->_description, "type" => PDO::PARAM_STR),
  			"contenu" => array( "value" => $this->_content, "type" => PDO::PARAM_LOB)
  		);
			$this->_dbProvider->exec('create_film', $params);
		}
	}

	/**
	 * Removes this movie from the database.
	 */
	public function delete() {
		$params = array (
			"id" => array("value" => $this->_id, "type" => PDO::PARAM_INT)
		);
		$this->_dbProvider->exec('delete_film', $params);
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
