<?php

namespace model;

use io\IPdoProvider;
use PDO;
use SplObserver;
use SplSubject;

/**
 * Administator model
 *
 * Simple storage class, indirectly handles db operations.
 * @see io\MySqlProvider
 */
class Administrator implements SplSubject {

    private $_id;
    private $_name;
    private $_login;
    private $_password;
    private $_email;
    private $_pdoProvider;
    private $_observers;

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
        if ($nom == NULL) {
            $params = array(
                "login" => array("value" => $login, "type" => PDO::PARAM_STRING)
            );

            $statement = $this->_pdoProvider->query('get_admin_byLogin', $params);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->_load($result);
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
    }

    /**
     * @return string the name
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * @return string the login
     * */
    public function getLogin() {
        return $this->_login;
    }

    /**
     * @return string the password
     */
    public function getPassword() {
        return $this->_password;
    }

    /**
     * Sets the password.
     * @param $newPassword string the new hashed password
     */
    public function setPassword($newPassword) {
        $this->_password = $newPassword;
        $this->notify();
    }

    /**
     * @return string the email
     */
    public function getEmail() {
        return $this->_email;
    }

    /**
     * Sets the email.
     * @param $newEmail string
     */
    public function setEmail($newEmail) {
        $this->_email = $newEmail;
        $this->notify();
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
     */
    public function notify() {
        foreach ($this->_observers as $observer) {
            $observer->update($this);
        }
    }

}
