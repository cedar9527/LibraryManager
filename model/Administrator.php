<?php

namespace model;
use \io;
use \utils;
/**
 * Administator model
 *
 * Simple storage class, indirectly handles backend operations.
 * @see \io\IBackendManager
 */
class Administrator implements SplSubject {
	private $_id;
	private $_name;
	private $_login;
	private $_password;
	private $_email;
	private $_authenticated;
	private $_pdoProvider;
	private $_observers;
	private $_hashProvider;

	/**
	 * Initialize an instance
	 *
	 * @param $backendManager IBackendManager
	 * @param $nom string
	 * @param $login string
	 * @param $msp string
	 * @param $email string
	 */
	public function __constructor(IPdoDbProvider $pdoProvider, $nom, $login, $mdp, $email) {
		$this->_pdoProvider = $pdoProvider;
		$this->_name = $nom;
		$this->_login = $login;
		$this->_password = $mdp;
		$this->_email = $email;
		$this->_authenticated = false;
		$this->_observers = new SplObjectStorage();
	}

	/**
	 * @return string the name.
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return string the login.
	 **/
  public function getLogin() {
    return $this->_login;
  }

	/**
	 * @return string the password.
	 */
	public function getPassword() {
		return $this->_password;
	}

	/**
	 * @return string the email.
	 */
	public function getEmail() {
		return $this->_email;
	}

	/**
	 * @return boolean true if this administrator is authenticated, false otherwise.
	 */
	public function isAuthenticated() {
		return $this->_authenticated;
	}

	/**
	 * Authenticate this administrator.
	 * @return boolean true if authentication was successfull false otherwise.
	 */
	public function connect() {
		$checkedHash = $this->_hashProvider->hash($this->_password);
		$params = array(
			"id" => array("value" => $this->_id, "type" => PDO::PARAM_INT)
		);
		$passwordHashStatement = $this->_pdoProvider->query('get_admin_passwordHash', $params);
		$row = $passwordHashStatement->fetch(PDO::FETCH_ASSOC);

		if($row !== false && $row["hash"] == $checkedHash) {
				$this->_online = true;
		}
		return $this->_online;
	}

	/**
	 * Save this administrator in database.
	 */
	public function save() {
		$params = array(
			"id" => array( "value" => $this->_id, "type" => PDO::PARAM_INT)
		);
		$adminStatement = $this->_dbProvider->query('get_admin', $params);
		$admin = $adminStatement->fetch(PDO::FETCH_ASSOC):

		if($admin !== false) {
			$params = array(
				"id" => array( "value" => $this->_id, "type" => PDO::PARAM_INT)
			);

			if($this->_email != $admin["email"]) {
				$params["email"] = array("value" => $this->_email, "type" => PDO::PARAM_STR);
			} else {
				$hash = $this->_hashProvider->hash($this->_password);
				if($hash != $admin["mdp"]) {
					$params["mdp"] = array("value" => $hash, "type" => PDO::PARAM_STR);
				}
			}

			$this->_dbProvider->exec('update_admin', $params);
		} else {
			$hash = $this->_hashProvider->hash($this->_password);
			$params = array(
				"id" => array( "value" => $this->_id, "type" => PDO::PARAM_STR | PDO_PARAM_INPUT_OUTPUT),
				"nom" => array( "value" => $this->_name, "type" => PDO::PARAM_STR),
				"login" => array( "value" => $this->_login, "type" => PDO::PARAM_STR),
				"mdp" => array( "value" => $hash, "type" => PDO::PARAM_STR),
				"email" => array( "value" => $this->_email, "type" => PDO::PARAM_STR)
			);
			$createRowCount = $this->_dbProvider->exec('create_admin', $params);
		}
	}

	/**
	 * Removes this administrator from the database.
	 */
	public function delete() {
		$params = array (
			"id" => array("value" => $this->_id, "type" => PDO::PARAM_INT)
		);
		$this->_dbProvider->exec('delete_admin', $params);
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
		foreach($this->_observers as $observer) {
			$observer->update($this);
		}
	}

}
