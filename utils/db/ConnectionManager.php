<?php

namespace utils\db;
use io;

/**
 * Description of ConnectionManager
 *
 * @author yves
 */
class ConnectionManager {
    const HOST = "localhost";
    const DB_NAME = "LibraryManager";
    const USER = "root";
    const PASSWORD = "1234";
    
    /** @var io\IPdoProvider */
    private static $_pdoProvider;
    
    public static function getPdoProvider() {
        if(!isset(self::$_pdoProvider)) {
            self::$_pdoProvider = new MySqlProvider(self::HOST, self::DB_NAME, self::USER, self::PASSWORD);
        }
        return self::$_pdoProvider;
    }
}
