<?php

namespace Ndm\JsonRpc\Core;

/**
 * A basic class that encapsulates a valid (non-erroneous) response
 *
 */
class Response implements ExportableInterface
{

    /**
     * @var int
     */
    public $id;
    /**
     * @var mixed
     */
    public $result;

    /**
     * @param int $id
     * @param mixed $result
     */
    public function __construct($id, $result)
    {
        $this->id = $id;
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function toJsonNatives()
    {
        return array(
            "result" => $this->result,
            "error" => null,
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
