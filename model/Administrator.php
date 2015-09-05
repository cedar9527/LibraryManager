<?php

namespace model;

use io\IPdoProvider;
use PDO;
use SplObserver;
use SplSubject;
use SplObjectStorage;
use OutOfBoundsException;

/**
 * Administator model
 *
 * Simple storage class, indirectly handles db operations.
 * @property-read string $name The name
 * @property-read string $login The login
 * @property-read array $observers The observers listenning for changes
 * @property-read boolean $loaded True if this entity was found in db, false otherwise
 * @property string $email The email
 * @property string $password The hashed password
 * @see io\MySqlProvider
 */
class Administrator implements SplSubject {
    /** @var int */
    private $_id;
    /** @var string */
    private $_name;
    /** @var string */
    private $_login;
    /** @var string */
    private $_password;
    /** @var string */
    private $_email;
    /** @var IPdoProvider */
    private $_pdoProvider;
    /** @var SplObjectStorage */
    private $_observers;
    /** @var boolean */
    private $_loaded;

    /**
     * Initialize an instance
     *
     * @param $pdoProvider IPdoProvider
     * @param $login string
     * @param $nom string OPTIONAL
     * @param $mdp string OPTIONAL The hashed password 
     * @param $email string OPTIONAL
     */
    public function __construct(IPdoProvider $pdoProvider, $login, $nom = NULL, $mdp = NULL, $email = NULL) {
        $this->_pdoProvider = $pdoProvider;
        $this->_login = $login;
        $this->_observers = new SplObjectStorage();
        if ($nom == NULL) {
            $params = array(
                "login" => array("value" => $login, "type" => PDO::PARAM_STRING)
            );
            $statement = $this->_pdoProvider->query('get_admin_byLogin', $params);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if($result) {
                $this->_load($result);
            }
        } else {
            $this->_name = $nom;
            $this->_password = $mdp;
            $this->_email = $email;
        }
    }

    /**
     * Loads a hash record into current instance.
     * @param $record array a hash with the following keys: nom, login, mdp, email
     */
    private function _load(array $record) {
        $this->_name = $record["nom"];
        $this->_login = $record["login"];
        $this->_password = $record["mdp"];
        $this->_email = $record["email"];
        $this->_loaded = true;
    }

    public function __get($name) {
        $props = array(
            'name' => $this->_name,
            'email' => $this->_email,
            'login' => $this->_login,
            'password' => $this->_password,
            'observers' => $this->_observers,
            'loaded' => $this->_loaded
        );
        $value = null;
        if(array_key_exists($name, $props)) {
            $value = $props[$name];
        }
        return $value;
    }
    
    public function __set($name, $value) {
        $props = array(
            'email' => $this->_email,
            'password' => $this->_password
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
     * Save this administrator in database.
     * @return boolean true if it succeeded, false otherwise.
     */
    public function save() {
        $affectedRowCount = 0;
        if ($this->_id != undefined) {
            $params = array(
                "id" => array("value" => $this->_id, "type" => PDO::PARAM_INT),
                "email" => array("value" => $this->_email, "type" => PDO::PARAM_STR),
                "mdp" => array("value" > $this->_password, "type" => PDO::PARAM_STR)
            );

            $affectedRowCount = $this->_dbProvider->exec('update_admin', $params);
        } else {
            $params = array(
                "id" => array("value" => $this->_id, "type" => PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT),
                "nom" => array("value" => $this->_name, "type" => PDO::PARAM_STR),
                "login" => array("value" => $this->_login, "type" => PDO::PARAM_STR),
                "mdp" => array("value" => $this->_password, "type" => PDO::PARAM_STR),
                "email" => array("value" => $this->_email, "type" => PDO::PARAM_STR)
            );
            $affectedRowCount = $this->_dbProvider->exec('create_admin', $params);
        }
        return $affectedRowCount > 0;
    }

    /**
     * Removes this administrator from the database.
     * @return boolean true if it succeeded, false otherwise
     */
    public function delete() {
        $params = array(
            "id" => array("value" => $this->_id, "type" => PDO::PARAM_INT)
        );
        $rowCount = $this->_dbProvider->exec('delete_admin', $params);
        return $rowCount > 0;
    }

    /**
     * Adds an IObserver to this observable.
     * @param $observer SplObserver
     */
    public function attach(SplObserver $observer) {
        $this->_observers->attach($observer);
    }

    /**
     * Removes an IObserver from this observable.
     * @param $observer SplObserver
     */
    public function detach(SplObserver $observer) {
        $this->_observers->detach($observer);
    }

    /**
     * Notify Observers about a change.
     * @param array $changedData an array in the form key => [oldValue, newValue]
     */
    public function notify(array $changedData = NULL) {
        foreach ($this->_observers as $observer) {
            $observer->update($this, $changedData);
        }
    }

}
