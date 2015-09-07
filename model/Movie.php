<?php
namespace model;
use io\IPdoProvider;
use PDO;
use RecordsCollection;
use OutOfBoundsException;
use RuntimeException;

/**
 * Movie model.
 * @property-read int $id The id
 * @property-read string $title The title
 * @property-read string $author The author
 * @property-read int $year The year
 * @property-read string $description The description
 * @property-read string $content The content
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
     * 
     * @param IPdoProvider $pdoProvider
     * @param string $title OPTIONAL
     * @param string $author OPTIONAL
     * @param int $year OPTIONAL
     * @param string $description OPTIONAL
     * @param string $content OPTIONAL
     */
    public function __construct(IPdoProvider $pdoProvider, string $title=NULL, string $author=NULL, int $year=NULL, string $description=NULL, string $content=NULL) {
        $this->_pdoProvider = $pdoProvider;
        $this->_title = $title;
        $this->_author = $author;
        $this->_year = $year;
        $this->_description = $description;
        $this->_content = $content;
    }
    
    /**
     * Loads current instance from database based on its id.
     * @param int $id
     * @throws RuntimeException In case we didn't find seeked Movie
     */
    public function getFromId($id) {
        $this->_id = $id;
        $ok = $movie->_loadFromId();
        if(!$ok) {
            throw new RuntimeException("Unable to load " .__CLASS__. " { id: " .$id. " }. Movie not found, perhaps it's been deleted.");
        }
        return $movie;
    }
    
    /**
     * Convenience method to get _loadFromId parameter's array
     * @return array the 
     */
    private function _getLoadFromIdParams() {
        return array(
            "_id" => "id",
            "_author" => "realisateur",
            "_content" => "contenu",
            "_description" => "description",
            "_title" => "titre",
            "_year" => "annee"  
        );
    }
    
    /**
     * Gets a movie based on its id.
     * @return boolean true if the movie was successfully loaded, false otherwise
     */
    private function _loadFromId() {
        $ok = false;
        $params = array(
            "id" => $this->_id
        );
        $statement = $this->_pdoProvider->query('get_film', $params);
        if(
                $statement != NULL &&  
                ($result = $statement->fetch(PDO::FETCH_ASSOC)) !== FALSE
         ) {
            $ok = true;
            $props = $this->_getLoadFromIdParams();
            foreach($props as $field => $column) {
                $this->{$field} = $result[$column];
            }
        }
        return $ok;
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
            'author' => '_author',
            'content' => '_content',
            'description' => '_description',
            'id' => '_id',
            'title' => '_title',
            'year' => '_year'
        );
        
        if(array_key_exists($name, $props)) {
            $this->{$props[$name]} = $value;
        } else {
            throw new OutOfBoundsException($name. " cannot be written in (class " .__CLASS__. ")");
        }
    }
    
    /**
     * Extracts a movie from an hash with the following keys: titre, realisateur,
     *   annee, description, contenu
     * @param $record array an hash
     * @see Movie::retrieveBatch
     */
    public static function extract(array $record) {
        return new Movie(
                $this->_pdoProvider, 
                $record["titre"], 
                $record["realisateur"], 
                $record["annee"], 
                $record["description"], 
                $record["contenu"]
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
     * Creates this movie in database.
     */
    public function create() {
        $params = array(
            "id" => array("value" => $this->_id, "type" => PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT),
            "titre" => array("value" => $this->_title, "type" => PDO::PARAM_STR),
            "realisateur" => array("value" => $this->_author, "type" => PDO::PARAM_STR),
            "annee" => array("value" => $this->_year, "type" => PDO::PARAM_INT),
            "description" => array("value" => $this->_description, "type" => PDO::PARAM_STR),
            "contenu" => array("value" => $this->_content, "type" => PDO::PARAM_LOB)
        );
        
            $params["id"]["type"] = PDO::PARAM_INT ;
            $this->_pdoProvider->exec('create_film', $params);
            $this->_id = $params['id'];
    }
    
    /**
     * Updates this movie in database.
     */
    public function update() {
        $params = array(
            "id" => array("value" => $this->_id, "type" => PDO::PARAM_INT),
            "titre" => array("value" => $this->_title, "type" => PDO::PARAM_STR),
            "realisateur" => array("value" => $this->_author, "type" => PDO::PARAM_STR),
            "annee" => array("value" => $this->_year, "type" => PDO::PARAM_INT),
            "description" => array("value" => $this->_description, "type" => PDO::PARAM_STR),
            "contenu" => array("value" => $this->_content, "type" => PDO::PARAM_LOB)
        );
        $this->_pdoProvider->exec('update_film', $params);
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
