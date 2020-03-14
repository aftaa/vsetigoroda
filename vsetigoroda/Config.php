<?php
/**
 * Created by PhpStorm.
 * User: Maxim Gabidullin <after@ya.ru>
 * Date: 01.04.2018
 * Time: 20:57
 */

namespace vsetigoroda;
use vsetigoroda\IConfigRepository;


final class Config
{
    /**
     * @var array
     */
    private $cfg = [];
    /**
     * @var Config
     */
    private static $instance = null;

    /**
     * Config constructor.
     * @param IConfigRepository $repository
     */
    private function __construct(IConfigRepository $repository)
    {
        $this->cfg = $repository->read();
    }

    /**
     * @param $name
     * @return mixed|null
     * @throws ConfigBadKeyException
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->cfg)) {
            throw new ConfigBadKeyException($name);
        }
        return isset($this->cfg[$name]) ? $this->cfg[$name] : null;
    }

    /**
     * @param IConfigRepository $repository
     * @return Config
     */
    public static function getInstance(IConfigRepository $repository)
    {
        if (null === self::$instance) {
            self::$instance = new self($repository);
        }
        return self::$instance;
    }
}