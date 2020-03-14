<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 17:17
 */

namespace vsetigoroda\services;
use Exception;
use mysqli;

class MysqliDbService
{
    /**
     * @var mysqli
     */
    public $dbCat;
    /**
     * @var mysqli
     */
    public $dbInfo;
    /**
     * @var mysqli
     */
    public $dbTest;

    /**
     * MysqliDbService constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return mysqli
     * @throws Exception
     */
    public function getDbCat()
    {
        if (!$this->dbCat) {
            $this->dbCat = new mysqli('localhost', 'vsetig_cat', 'vsetigoroda', 'vsetig_cat');
            if ($this->dbCat->errno) {
                throw new Exception($this->dbCat->error);
            }
        }
        return $this->dbCat;
    }

    /**
     * @return mysqli
     * @throws Exception
     */
    public function getDbInfo()
    {
        if (!$this->dbInfo) {
            $this->dbInfo = new mysqli('localhost', 'vsetig_info_com', 'vsetigoroda', 'vsetig_info_com');
            if ($this->dbInfo->errno) {
                throw new Exception($this->dbInfo->error);
            }
        }
        return $this->dbInfo;
    }

    /**
     * @return mysqli
     * @throws Exception
     */
    public function getDbTest()
    {
        if (!$this->dbTest) {
            $this->dbTest = new mysqli('localhost', 'vsetig_test', 'vsetigoroda', 'vsetig_test');
            if ($this->dbTest->errno) {
                throw new Exception($this->dbTest->error);
            }
        }
        return $this->dbTest;
    }

    /**
     * @return mysqli
     * @throws Exception
     */
    public function getDb()
    {
        return self::getDbTest();
    }

    /**
     * @return MysqliDbService
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new self;
        }
        return $instance;
    }
}