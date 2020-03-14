<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 21:01
 */

namespace vsetigoroda;

use mysqli;
use Exception;
use vsetigoroda\services\MysqliDbService;

class ConfigMysqliRepository implements IConfigRepository
{
    /**
     * @var MysqliDbService
     */
    public $db;
    /**
     * @var array
     */
    private $configCache;

    /**
     * ConfigRepository constructor.
     */
    public function __construct()
    {
        $this->db = MysqliDbService::getInstance();
    }

    /**
     * @throws Exception
     * @return array
     */
    public function read()
    {
        if ($this->configCache) {
            return $this->configCache;
        }
        $db = $this->db->getDbTest();
        $result = $db->query('SELECT * FROM aw_config');
        if ($db->errno) {
            throw new Exception($db->error);
        }
        $result = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($result as $row) {
            $this->configCache[$row['setname']] = $row['value'];
        }
        return $this->configCache;
    }

    /**
     * @param string $name
     * @return string
     * @throws ConfigBadKeyException
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->config)) {
            return $this->config[$name];
        }
        throw new ConfigBadKeyException("Key {$name} not found.");
    }
}