<?php

namespace Ndm\JsonRpc\Client\Transport;


/**
 *
 */
interface TransportInterface
{

    /**
     * @param string $request
     *
     * @throws \Ndm\JsonRpc\Client\Exception\TransportException
     *
     * @return string
     */
    public function send($request);
}
