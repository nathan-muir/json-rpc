<?php

namespace Ndm\JsonRpc\Core;

/**
 * An object representation of a single request
 *
 * @author Matt Morley (MPCM)
 *
 *
 *
 */
class Request implements ExportableInterface
{
    /**
     * @var mixed
     */
    public $id = null;

    /**
     * @var array
     */
    public $params = array();

    /**
     * @var string
     */
    public $method;

    /**
     * @param string $method
     * @param array|\stdClass $params
     * @param int|null|string|float $id
     * @internal param bool $hasId As the $id can be null, this is to indicate whether the request has an id
     */
    public function __construct($method, $params = array(), $id = null)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isNotification()
    {
        return $this->id === null;
    }

    /**
     * @return mixed
     */
    public function toJsonNatives()
    {
        return array(
            "method" => $this->method,
            "params" => $this->params,
            "id" => $this->id
        );
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return Json::encode($this->toJsonNatives());
    }
}
