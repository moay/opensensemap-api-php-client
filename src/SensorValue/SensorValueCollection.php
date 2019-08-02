<?php

namespace Moay\OpensensemapApiClient\SensorValue;

use Traversable;

/**
 * Class DataCollection.
 */
class SensorValueCollection implements \JsonSerializable, \Countable, \IteratorAggregate
{
    /** @var SensorValue[] */
    private $sensorValues;

    /**
     * @param SensorValue $sensorValue
     */
    public function addSensorValue(SensorValue $sensorValue)
    {
        $this->sensorValues[] = $sensorValue;
    }

    /**
     * @return SensorValue[]
     */
    public function getSensorValues(): array
    {
        return $this->sensorValues;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function (SensorValue $sensorValue) {
            return $sensorValue->jsonSerialize();
        }, $this->sensorValues);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->sensorValues);
    }

    /**
     * @return \ArrayIterator|Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->sensorValues);
    }


}
