<?php


namespace Ndm\JsonRpc\Server\Dispatch;

/**
 * An interface used by MapDispatch to interact with methods
 *
 */
interface MethodInterface
{

    /**
     * Returns the method alias utilised by json-rpc calls
     * @return string
     */
    public function getAlias();

    /**
     * @param array $arguments
     *
     * @throws \Ndm\JsonRpc\Server\Exception\InvalidArgumentException
     * @throws \Ndm\JsonRpc\Server\Exception\RuntimeException
     */
    public function invoke($arguments);
}
