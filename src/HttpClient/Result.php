<?php

namespace Cometcast\Openapi\HttpClient;

use Exception;
use Traversable;
use JmesPath\Env as JmesPath;


class Result implements \ArrayAccess, \IteratorAggregate, \Countable
{

    /** @var array */
    private $data = [];
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
        if (isset($this->data[$offset])) {
            return $this->data[$offset];
        }

        $value = null;
        return $value;
    }
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
        $this->data[$offset] = $value;
    }
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
        unset($this->data[$offset]);
    }
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        // TODO: Implement getIterator() method.
        return new \ArrayIterator($this->data);
    }
    #[\ReturnTypeWillChange]
    public function count()
    {
        // TODO: Implement count() method.
        return count($this->data);
    }

    /**
     * Returns the result of executing a JMESPath expression on the contents
     * of the Result model.
     *
     *     $result = $client->execute($command);
     *     $jpResult = $result->search('foo.*.bar[?baz > `10`]');
     *
     * @param string $expression JMESPath expression to execute
     *
     * @return mixed Returns the result of the JMESPath expression.
     * @link http://jmespath.readthedocs.org/en/latest/ JMESPath documentation
     */
    #[\ReturnTypeWillChange]
    public function search($expression)
    {
        return JmesPath::search($expression, $this->toArray());
    }
    #[\ReturnTypeWillChange]
    public function toArray()
    {
        return $this->data;
    }
}