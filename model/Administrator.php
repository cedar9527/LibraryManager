<?php
namespace model;
use io\IPdoProvider;
use PDO;
use OutOfBoundsException;
use RuntimeException;

/**
 * Administator model
 *
 * Simple storage class, indirectly handles db operations.
 * @property int $id The id
 * @property string $name The name
 * @property string $login The login
 * @property string $email The email
 * @property string $password The hashed password
 * @see io\MySqlProvider
 */
class Administrator {
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

    /**
     * Initialize an instance
     *
     * @param $pdoProvider IPdoProvider
     * @param string $login
     * @param string $password The hashed password 
     * @param string $name OPTIONAL
     * @param string $email OPTIONAL
     */
    public function __construct(IPdoProvider $pdoProvider, $login, $password, $name = NULL, $email = NULL) {
        $this->_pdoProvider = $pdoProvider;
        $this->_login = $login;
        $this->_password = $password;
        $this->_name = $name;
        $this->_email = $email;
    }

    /**
     * Gets an administrator by credentials.
     *
     * @return model\Administrator
     * @throws RuntimeException In case we didn't find seeked administrator
     */
    public function getFromCredentials() {
        $ok = $this->_loadFromLogin();
        if(!$ok) {
            throw new RuntimeException("Unable to load " .__CLASS__. " { login: " .$login. ", password: ".$password." }. Admin not found, check login and password.");
        }
        return $admin;
    }

    
    /**
     * Loads the administrator from database.
     * @return boolean true if Administrator was successfully loaded, false otherwise
     */
    private function _loadFromLogin() {
        $ok = false;
        $params = array(
            "login" => array("value" => $this->_login, "type" => PDO::PARAM_STR),
            "mdp" => array( "value" => $this->_password, "type" => PDO::PARAM_STR )
        );
        $statement = $this->_pdoProvider->query('get_admin_byCredentials', $params);
        
        if(
                $statement != NULL &&
                ($result = $statement->fetch(PDO::FETCH_ASSOC)) !== FALSE
        ) {
            $ok = true;
            $this->_name = $result["nom"];
            $this->_email = $result["email"];
            $this->_id = $result["id"];
        }
        return $ok;
    }

    public function __get($name) {
        $props = array(
            'id' => $this->_id,
            'name' => $this->_name,
            'email' => $this->_email,
            'login' => $this->_login,
            'password' => $this->_password
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
            'id' => '_id',
            'name' => '_name',
            'email' => '_email',
            'login' => '_login',
            'password' => '_password'
        );
        if(array_key_exists($name, $props)) {
            $this->{$props[$name]} = $value;
        } else {
            throw new OutOfBoundsException($name. " cannot be written in (class " .__CLASS__. ")");
        }
    }
    /**
     * Creates this administrator in database.
     * @return boolean true if it succeeded, false otherwise
     */
    public function create() {
        $params = array(
            "id" => array("value" => $this->_id, "type" => PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT),
            "nom" => array("value" => $this->_name, "type" => PDO::PARAM_STR),
            "login" => array("value" => $this->_login, "type" => PDO::PARAM_STR),
            "mdp" => array("value" => $this->_password, "type" => PDO::PARAM_STR),
            "email" => array("value" => $this->_email, "type" => PDO::PARAM_STR)
        );
        $affectedRowCount = $this->_pdoProvider->exec('create_admin', $params);
        return $affectedRowCount > 0;
    }

    /**
     * Updates this administrator (email / password) in database.
     * @return boolean true if it succeeded, false otherwise
     */
    public function update() {
        $params = array(
            "id" => array("value" => $this->_id, "type" => PDO::PARAM_INT),
            "email" => array("value" => $this->_email, "type" => PDO::PARAM_STR),
            "mdp" => array("value" > $this->_password, "type" => PDO::PARAM_STR)
        );
        
        $affectedRowCount = $this->_pdoProvider->exec('update_admin', $params);
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
        $rowCount = $this->_pdoProvider->exec('delete_admin', $params);
        return $rowCount > 0;
    }

}
