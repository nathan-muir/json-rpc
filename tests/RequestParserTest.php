<?php
namespace Ndm\JsonRpc\Core;

/**
 *
 */
class RequestParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Ndm\JsonRpc\Core\RequestParser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new \Ndm\JsonRpc\Core\RequestParser();
    }

    public function tearDown()
    {
        unset ($this->parser);
    }

    /**
     * @return array
     */
    public static function validSingleRequestProvider()
    {
        return array(
            array('{"method":"foobar","params":[6],"id":1}', "foobar", array(6), true, 1,),
            array('{"method":"foobar","params":[6],"id":null}', "foobar", array(6), true, null),
            array('{"method":"subtract","params":[42,23],"id":1}', "subtract", array(42, 23), true, 1),
        );
    }

    /**
     * @param string $json
     * @param string $method
     * @param object|array|null $params
     * @param bool $hasId
     * @param null|int|string|float $id
     *
     * @dataProvider validSingleRequestProvider
     */
    public function testValidSingleRequest($json, $method, $params, $hasId, $id)
    {
        $request = $this->parser->parse($json);
        $expect = new Request($method, $params, $id);
        $this->assertEquals($request, $expect);

    }

    /**
     * @param $json
     * @dataProvider validSingleRequestProvider
     */
    public function testValidSingleRequestRender($json)
    {
        $request = $this->parser->parse($json);
        $this->assertNotNull($request);
        $this->assertEquals($request->toJson(), $json);
    }

    /**
     * @return array
     */
    public static function requestValidIdProvider()
    {
        return array(
            array('{"method": "foobar", "params": [6], "id": 1}', 1),
            array('{"method": "foobar", "params": [6], "id": 1.1}', 1.1),
            // MESSAGES 'SHOULD NOT' CONTAIN fractional numerics
            array('{"method": "foobar", "params": [6], "id": "abc"}', 'abc'),
        );
    }

    /**
     * Tests a selection of ID valid ID values- that are not notifications
     * @param $json
     * @param $id
     * @dataProvider requestValidIdProvider
     */
    public function testRequestValidId($json, $id)
    {
        $request = $this->parser->parse($json);
        $this->assertNotNull($request);
        $this->assertFalse($request->isNotification());
        $this->assertEquals($request->id, $id);
    }


    /**
     * @return array
     */
    public static function validNotificationProvider()
    {
        return array(
            array('{"method": "foobar", "params": [6], "id":null}'),
        );
    }

    /**
     * Tests requests that are notifications- they must have no ID property
     * @param $json
     * @dataProvider validNotificationProvider
     */
    public function testValidNotification($json)
    {
        $request = $this->parser->parse($json);
        $this->assertTrue($request->isNotification());
    }

    /**
     * @return array
     */
    public static function validParamsProvider()
    {
        return array(
            array('{"method": "foobar", "params": [6], "id": 1}', array(6)),
            array('{"method": "foobar", "params": [], "id": 1}', array()),
        );
    }


    /**
     * @param $json
     * @param $params
     * @dataProvider validParamsProvider
     */
    public function testValidParams($json, $params)
    {
        $request = $this->parser->parse($json);
        $this->assertEquals($request->params, $params);
    }

    /**
     *
     */
    public function testParamsOmitted()
    {
        $request = $this->parser->parse('{"method": "foobar"}');
        $this->assertNull($request);
    }

    /**
     * @return array
     */
    public static function invalidParamsProvider()
    {
        return array(
            array('{"method": "foobar", "params": null, "id": 1}'),
            array('{"method": "foobar", "params": 1, "id": 1}'),
            array('{"method": "foobar", "params": 1.1, "id": 1}'),
            array('{"method": "foobar", "params": "", "id": 1}'),
            array('{"method": "foobar", "params": "abc", "id": 1}'),
            array('{"method": "foobar", "params": true, "id": 1}'),
        );
    }

    /**
     * @param $json
     * @dataProvider invalidParamsProvider
     */
    public function testInvalidParams($json)
    {
        $request = $this->parser->parse($json);
        $this->assertNull($request, 'Due to the value of the "params" the request should be invalid');
    }


    /**
     * @return array
     */
    public static function invalidSingleRequestProvider()
    {
        return array(
            array('4'),
            array('[]'),
            array('{}'),
            array('{"abc": 123}')
        );
    }

    /**
     * @param $json
     * @dataProvider invalidSingleRequestProvider
     */
    public function testInvalidSingleRequest($json)
    {
        $request = $this->parser->parse($json);
        $this->assertNull($request);
    }


    /**
     * @return array
     */
    public static function invalidJsonRequestProvider()
    {
        return array(
            array('{'),
            array('{]'),
            array('"4')
        );
    }

    /**
     * @expectedException \Ndm\JsonRpc\Core\Exception\JsonParseException
     * @dataProvider invalidJsonRequestProvider
     */
    public function testInvalidJson($json)
    {
        $this->parser->parse($json);
    }


    /**
     *
     */
    public function testValidMethod()
    {
        $request = $this->parser->parse('{"method": "foobar", "params":[], "id": null}');
        $this->assertEquals($request->method, "foobar");
    }

    /**
     * @return array
     */
    public static function invalidMethodProvider()
    {
        return array(
            array('{"method": ""}'),
            array('{"method": 0 }'),
            array('{"method": 1.1 }'),
            array('{"method": true}'),
            array('{"method": null}'),
            array('{"method": []}'),
            array('{"method": [1,2]}'),
            array('{"method": {}}'),
            array('{"method": {"abc":1234}}'),
            array('{"method": {"abc":[1,2]}}'),
            array('{"method": {"abc":{"def":"hgi"}}}'),
        );
    }

    /**
     * @param $json
     * @dataProvider invalidMethodProvider
     */
    public function testInvalidMethod($json)
    {
        $request = $this->parser->parse($json);
        $this->assertNull($request);
    }
}
