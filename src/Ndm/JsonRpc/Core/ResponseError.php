<?php

namespace Ndm\JsonRpc\Core;

/**
 * A basic class that encapsulates an erroneous response
 *
 * Also contains additional constructor methods for standard error types
 *
 */
class ResponseError implements ExportableInterface
{

    /**
     * @var mixed
     */
    public $id;
    /**
     * @var int
     */
    public $error;

    /**
     * @param int $id
     * @param mixed $error
     */
    public function __construct($id, $error)
    {
        $this->id = $id;
        $this->error = $error;
    }

    /**
     * @return array
     */
    public function toJsonNatives()
    {
        return array(
            "id" => $this->id,
            "result" => null,
            "error" => $this->error
        );
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return Json::encode($this->toJsonNatives());
    }

    /**
     * @return ResponseError
     */
    public static function createInvalidRequest()
    {
        return new self(null, array("code"=>-32600, "message"=>"Invalid Request."));
    }

    /**
     * @param mixed $id
     * @param mixed $data
     * @return ResponseError
     */
    public static function createMethodNotFound($id = null, $data = null)
    {
        return new self($id, array("code"=>-32601, "message"=>"Method not found.", "data"=>$data));
    }

    /**
     * @param mixed $id
     * @param mixed $data
     * @return ResponseError
     */
    public static function createInvalidParams($id, $data = null)
    {
        return new self($id, array("code"=>-32602, "message"=>"Invalid params.", "data"=>$data));
    }

    /**
     * @param mixed $id
     * @param mixed $data
     * @return ResponseError
     */
    public static function createInternalError($id = null, $data = null)
    {
        return new self($id, array("code"=>-32603, "message"=>"Internal error.", "data"=>$data));
    }

    /**
     * @return ResponseError
     */
    public static function createParseError()
    {
        return new self(null, array("code"=>-32700, "message"=>"Parse error."));
    }
}
