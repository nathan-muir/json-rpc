<?php

namespace Ndm\JsonRpc\Core;

/**
 *
 */
class ResponseParser
{

    /**
     * @const int Limits the depth parsed by JSON response
     */
    const JSON_DECODE_DEPTH_LIMIT = 512;

    /**
     * @var int
     */
    private $jsonDecodeDepthLimit;

    /**
     * @param int $jsonDecodeDepthLimit
     */
    public function __construct($jsonDecodeDepthLimit = self::JSON_DECODE_DEPTH_LIMIT)
    {
        $this->jsonDecodeDepthLimit = $jsonDecodeDepthLimit;
    }

    /**
     * @param $response
     *
     * @throws Exception\InvalidResponseException
     *
     * @return Response|ResponseError|null
     */
    private function createFrom($response)
    {
        // response must be an object
        if (!is_object($response)) {
            throw new Exception\InvalidResponseException("The response was not an object.");
        }
        // must contain an ID
        if (!property_exists($response, 'id')) {
            throw new Exception\InvalidResponseException("The response did not specify attribute 'id'");
        }
        if (!property_exists($response, 'result')) {
            throw new Exception\InvalidResponseException("The response did not specify attribute 'result'");
        }
        if (!property_exists($response, 'error')) {
            throw new Exception\InvalidResponseException("The response did not specify attribute 'method'");
        }
        // detect whether response is a result or an error
        $hasResult = isset($response->result);
        $hasError = isset($response->error);
        // if missing, or containing both result and error - throw relevant exceptions
        if ($hasResult && $hasError) {
            throw new Exception\InvalidResponseException("The response contains both 'result' and 'error'. One of these attributes must be null.");
        } elseif (!$hasError) {
            // must be "!$hasError", as the result could be null
            // create a standard response using id & result
            return new Response($response->id, $response->result);
        } else {
            // create a new error response
            return new ResponseError($response->id, $response->error);
        }
    }

    /**
     * @param $json
     *
     * @throws Exception\JsonParseException
     * @throws Exception\InvalidResponseException
     *
     * @return ResponseError|null
     */
    public function parse($json)
    {
        // if there's no data- return null
        if ($json === '') {
            return null;
        }
        // decode the string
        $response = Json::decode($json, $this->jsonDecodeDepthLimit);
        // all valid json is treated as a single request
        return $this->createFrom($response);
    }
}
