<?php

namespace model;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-08-30 at 11:03:46.
 */
class AdministratorTest extends \PHPUnit_Extensions_Database_TestCase {

    /** @var Administrator */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Administrator;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers model\Administrator::save
     * @todo   Implement testSave().
     */
    public function testSave() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers model\Administrator::delete
     * @todo   Implement testDelete().
     */
    public function testDelete() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * Returns the connection for this test.
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection The connection used
     */
    public function getConnection() {
        $pdo = new PDO('mysql:host=localhost;charset=utf8', 'root', '1234');
        return $this->createDefaultDBConnection($pdo, 'LibraryManager');
    } 
    /**
     * Returns the dataset for this test's fixture.
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet The DataSet used
     */
    public function getDataSet() {
        
    }
    
}
