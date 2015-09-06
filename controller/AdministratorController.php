<?php
namespace controller;
use model\Administrator;
use utils\ShaHashProvider;
use utils\db\ConnectionManager;
use RuntimeException;

/**
 * Administrator Controller
 */
class AdministratorController {
        /** @var Administrator */
	private $_admin;
        /** @var \io\IPdoProvider */
        private $_pdoProvider;
	
        public function __construct() {
            $this->_pdoProvider = ConnectionManager::getPdoProvider();
        }
        
        /**
         * Stores a new Administrator in the db.
         * @param type $login
         * @param type $name
         * @param type $password
         * @param type $email
         */
        public function create($login, $name, $password, $email) {
            $hashProvider = new ShaHashProvider();
            $hashedPassword = $hashProvider->hash($password);
            $this->_admin = new Administrator($this->_pdoProvider, $login, $name, $hashedPassword, $email);
            $this->_admin->save();
        }
        
        /**
         * Logs an Administrator in.
         * @param string $login
         * @param string $password
         * @throws RuntimeException when login was not found or password was incorrect.
         */
	public function login($login, $password) {
            $hashProvider = new ShaHashProvider();
            $hashedPassword = $hashProvider->hash($password);
            $this->_admin = new Administrator($this->_pdoProvider, $login);
            
            if(!$this->_admin->loaded || !$this->_admin->password == $hashedPassword) {
                throw new RuntimeException("Administrator " .$login. " not found or invalid password.");
            }
	}
        
        /**
         * Removes an Administrator's account.
         * @param string $login
         * @param string $password
         */
        public function removeAccount($login, $password) {
            $this->login($login, $password);
            $this->_admin->delete();
        }

}