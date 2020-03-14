<?php
/**
 * Created by PhpStorm.
 * User: gabid
 * Date: 09.04.2018
 * Time: 1:15
 */

namespace vsetigoroda\vo;

class DateTime extends \DateTime
{
    const FORMAT = 'd.m.Y';
    /**
     * @var string
     */
    private $format = self::FORMAT;

    /**
     * DateTime constructor.
     * @param string|\DateTime $stringOrDateTime
     * @param DateTimeZone|null $timezone
     */
    public function __construct($stringOrDateTime = 'now', DateTimeZone $timezone = null)
    {
        if ($stringOrDateTime instanceof \DateTime) {
            parent::__construct();
            parent::setTimestamp($stringOrDateTime->getTimestamp());
            if ($timezone) {
                parent::setTimezone($timezone);
            }
        } else {
            parent::__construct($stringOrDateTime, $timezone);
        }
    }

    /**
     * @param string|null $format
     * @return string
     */
    public function toString($format = null)
    {
        if (is_null($format)) {
            $format = $this->format;
        }
        return $this->format($format);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function toISO()
    {
        return parent::format('Y-m-d H:i:s');
    }
}