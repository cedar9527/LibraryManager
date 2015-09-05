<?php

namespace io;
use PDO;

/**
 * Mysql Provider.
 */
class MySqlProvider implements IPdoProvider {

    /** @var string */
    private $_host;
    /** @var string */
    private $_dbName;
    /** @var string */
    private $_user;
    /** @var string */
    private $_password;
    /** @var PDO */
    private $_pdo;

    /**
     * Initializes an instance.
     * @param $host string
     * @param $dbName string
     * @param $user string
     * @param $password string
     */
    public function __constructor($host, $dbName, $user, $password) {
        $this->_host = $host;
        $this->_dbName = $dbName;
        $this->_user = $user;
        $this->_password = $password;
        $this->_pdo = new PDO("mysl:host={$this->_host};dbname={$this->_dbName};charset=utf8", $this->_user, $this->_password);
    }

    /**
     * Gets a Statement reprensenting the given procedure bound with parameters.
     * @param $procedure string the procedure name
     * @param $parameters a hash in the form paramName => { value => val, type: PDO::PARAM_*}
     * @return PDOStatement the prepared / sanitized statement
     */
    private function _getStatement($procedure, array $parameters) {
        $parameterNames = ':' . array_join(',:', array_keys($parameters));
        $statement = $this->_pdo->prepare("CALL $procedure($parameterNames)");
        foreach ($parameters as $key => $val) {
            $statement->bindValue(":$key", $val["value"], $val["type"]);
        }
        return $statement;
    }

    /**
     * Executes a modification query.
     * @param $procedure string
     * @param $parameters a hash in the form paramName => { value => val, type: PDO::PARAM_*}
     * @return int the number of affected rows
     */
    public function exec($procedure, array $parameters) {
        $statement = $this->_getStatement($procedure, $parameters);
        $statement->execute();
        return $statement->rowCount();
    }

    /**
     * Executes a selection query.
     * @param $procedure string
     * @param $parameters a hash in the form paramName => { value => val, type: PDO::PARAM_*}
     * @return PDOStatement
     */
    public function query($procedure, array $parameters) {
        $statement = $this->_getStatement($procedure, $parameters);
        return $statement;
    }

}
