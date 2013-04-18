<?php

namespace Ndm\JsonRpc\Core;

/**
 *
 */
interface ExportableInterface
{

    /**
     * @return mixed
     */
    public function toJsonNatives();

    /**
     * @return string
     */
    public function toJson();

}
