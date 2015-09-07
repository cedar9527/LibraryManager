<?php
namespace controller;
use model\Administrator;
use utils\ShaHashProvider;
use RuntimeException;

/**
 * Administrator Controller
 */
class AdministratorController {
        /** @var Administrator */
	private $_admin;
	
        /**
         * Initializes an instance.
         * @param Administrator $admin The administrator this controller will operate upon 
         */
        public function __construct(Administrator $admin) {
            $this->_admin = $admin;
        }
        
        /**
         * Creates Or Updates a new Administrator in the db.
         * @todo OPTIONAL Deals with email/password update case.
         */
        public function save() {
            if(!isset($this->_admin->id)) {
                if(!isset($this->_admin->login) || !isset($this->_admin->password)) {
                    throw new RuntimeException("Login and password must be set in order to create an administrator.");
                }
               $this->_admin->create();
            } else {
                $this->_admin->update();
            }
        }
        
        /**
         * Logs an Administrator in.
         * @throws RuntimeException when login was not found or password was incorrect.
         */
	public function login() {
            $this->_admin->getFromCredentials();
            
            if($this->_admin->password != $hashedPassword) {
                throw new RuntimeException("Administrator " .$this->_admin->login. " not found or invalid password.");
            }
	}
        
        /**
         * Removes an Administrator's account.
         */
        public function removeAccount() {
            $this->login();
            $this->_admin->delete();
        }

}