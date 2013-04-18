<?php
namespace Ndm\JsonRpc\Server\Exception;

/**
 * Basic exception - not encapsulated by Ndm\JsonRpc\Exception as it should only occur pre-init
 */
class ConfigurationException extends RuntimeException
{

}
