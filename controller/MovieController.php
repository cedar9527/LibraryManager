<?php
namespace controller;
use model\Movie;
use io\MySqlProvider;
use utils\db\ConnectionManager;

/**
 * Movie Controller
 *
 */
class MovieController {
    /** @var Movie */
    private $_movie;
    /** @var IPdoProvider */
    private $_pdoProvider;
    
    public function __construct() {
        $this->_pdoProvider = ConnectionManager::getPdoProvider();
    }
    
    public function create($title, $author, $year, $description, $content) {
        $this->_movie = new Movie($this->_pdoProvider, $title, $author, $year, $description, $content);
        $this->_movie->save();
    }
    
    /**
     * Gets a block of Movies.
     * @param int $batchSize Maximum desired size
     * @return array an array of at most $batchSize Movies, an empty array when we've no more left
     */
    public function getBatch($batchSize) {
        return Movie::retrieveBatch($this->_pdoProvider, $batchSize);
    }
    
    /**
     * Removes a Movie.
     * @param int $id The movie to delete's id
     */
    public function delete($id) {
        $this->_movie = new Movie($this->_pdoProvider, $id);
        $this->_movie->delete();
    }
    
    public function update() {
        
    }
}
