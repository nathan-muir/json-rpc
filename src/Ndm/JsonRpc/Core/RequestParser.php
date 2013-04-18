<?php
namespace Ndm\JsonRpc\Core;


/**
 *
 */
class RequestParser
{

    /**
     * @const int Limits the depth parsed by JSON requests
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
     * @author Matt Morley (MPCM)
     * @param mixed $request
     * @return bool
     */
    private function isValidRequest($request)
    {
        // per the 2.0 specification
        // a request object must:
        // be an object
        if (!is_object($request)) {
            return false;
        }

        // contain a method member that is a string
        if (!isset($request->method) || !is_string($request->method) || strlen($request->method) === 0) {
            return false;
        }

        // must contain a params member
        //    that member must be an array
        if (!isset($request->params) || !is_array($request->params)) {
            return false;
        }

        // must contain an id member, (can be null for notification)
        if (!property_exists($request, 'id')) {
            return false;
        }

        // it passes the tests
        return true;
    }

    /**
     * @param string $json
     *
     * @throws Exception\JsonParseException
     *
     * @return Request
     */
    public function parse($json)
    {
        // decode the string
        $request = Json::decode($json, $this->jsonDecodeDepthLimit);

        // check if the request is valid
        if (!$this->isValidRequest($request)) {
            return null;
        }
        // import the id, method and params from the object
        return new Request(
            $request->method, $request->params, $request->id
        );
    }
}
