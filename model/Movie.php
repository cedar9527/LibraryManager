<?php

namespace model;

use io\IPdoProvider;
use PDO;
use RecordsCollection;
use SplObjectStorage;
use OutOfBoundsException;

/**
 * Movie model.
 * @property-read int $id The id
 * @property-read string $title The title
 * @property-read string $author The author
 * @property-read int $year The year
 * @property string $description The description
 * @property string $content The content
 */
class Movie {
    /** @var int */
    private $_id;
    /** @var string */
    private $_title;
    /** @var string */
    private $_author;
    /** @var int */
    private $_year;
    /** @var string */
    private $_description;
    /** @var string */
    private $_content;
    /** @var IPdoProvider */
    private $_pdoProvider;

    /**
     * Initializes an instance.
     * @param $pdoProvider IPdoProvider
     * @param int $id OPTIONAL
     * @param $title string OPTIONAL
     * @param $author string OPTIONAL
     * @param $year int OPTIONAL
     * @param $description string OPTIONAL
     * @param $content string OPTIONAL
     */
    public function __constructor(IPdoProvider $pdoProvider, $id, $title = NULL, $author = NULL, $year = NULL, $description = NULL, $content = NULL) {
        $this->_pdoProvider = $pdoProvider;
        if(isset($title)) {
            $this->_title = $title;
            $this->_author = $author;
            $this->_year = $year;
            $this->_description = $description;
            $this->_content = $content;
        } else {
            $params = array(
                "id" => $id
            );
            $statement = $this->_pdoProvider->query('get_film', $params);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if($result) {
                $this->_grabMovie($result);
            }
        }
        
    }
    
    /**
     * Copies a db record into current instance
     * @param array $record a hash with the following keys: titre, realisateur,
     *   annee, description, contenu
     * @see self::extract
     */
    private function _grabMovie(array $record) {
        $movie = self::extract($record);
        foreach($movie as $prop => $val){ 
            $this->{$prop} = $val; 
        }
    }
    
    public function __get($name) {
        $props = array(
            'author' => $this->_author,
            'content' => $this->_content,
            'description' => $this->_description,
            'id' => $this->_id,
            'title' => $this->_title,
            'year' => $this->_year
        );
        $value = null;
        if(array_key_exists($name, $props)) {
            $value = $props[$name];
        } else {
            throw new OutOfBoundsException($name. " is not a valid property (class " .__CLASS__. ")");
        }
        return $value;
    }
    
    public function __set($name, $value) {
        $props = array(
            'content' => $this->_content,
            'description' => $this->_description
        );
        if(array_key_exists($name, $props)) {
            $oldValue = $props[$name];
            $props[$name] = $value;
            $this->notify(array(
                $name => [$oldValue, $value]
            ));
        } else {
            throw new OutOfBoundsException($name. " is not a valid property (class " .__CLASS__. ")");
        }
    }
    
    /**
     * Extracts a movie from an hash with the following keys: titre, realisateur,
     *   annee, description, contenu
     * @param $record array an hash
     */
    public static function extract(array $record) {
        return new Movie($this->_pdoProvider, $record["titre"], $record["realisateur"], $record["annee"], $record["description"], $record["contenu"]
        );
    }

    /**
     * Returns a batch of at most $batchCnt Movies
     * @param IPdoProvider $pdoProvider The PDO Provider
     * @param $batchCnt int the maximum number of Movies to retrieve
     * @see utils\db\RecordsCollection
     */
    public static function retrieveBatch(IPdoProvider $pdoProvider, $batchCnt) {
        $recordsCollection = new RecordsCollection();
        $movies = $recordsCollection->getBatch(
                $pdoProvider, 
                'get_movies_batch', 
                array(__CLASS__, 'extract'),
                $batchCnt
        );
        return $movies;
    }

    /**
     * Save this movie in database.
     */
    public function save() {
        $params = array(
            "id" => array("value" => $this->_id, "type" => PDO::PARAM_INT),
            "titre" => array("value" => $this->_title, "type" => PDO::PARAM_STR),
            "realisateur" => array("value" => $this->_author, "type" => PDO::PARAM_STR),
            "annee" => array("value" => $this->_year, "type" => PDO::PARAM_INT),
            "description" => array("value" => $this->_description, "type" => PDO::PARAM_STR),
            "contenu" => array("value" => $this->_content, "type" => PDO::PARAM_LOB)
        );
        if ($this->_id != undefined) {
            $this->_pdoProvider->exec('update_film', $params);
        } else {
            $params["id"]["type"] = PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT;
            $this->_pdoProvider->exec('create_film', $params);
            $this->_id = $params['id'];
        }
    }

    /**
     * Removes this movie from the database.
     */
    public function delete() {
        $params = array(
            "id" => array("value" => $this->_id, "type" => PDO::PARAM_INT)
        );
        $this->_pdoProvider->exec('delete_film', $params);
    }
}
