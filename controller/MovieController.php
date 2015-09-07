<?php
namespace controller;
use model\Movie;

/**
 * Movie Controller
 *
 */
class MovieController {
    /** @var Movie */
    private $_movie;
    
    /**
     * Initializes an instance.
     * @param Movie $movie The movie this controller will operate upon
     */
    public function __construct(Movie $movie) {
        $this->_movie = $movie;
    }
    
    /**
     * Creates or Updates this movie in database.
     */
    public function save() {
        if(isset($this->_movie->id)) {
            $this->_movie->update();
        } else {
            $this->_movie->create();
        }
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
     */
    public function delete() {
        $this->_movie->delete();
    }
}
