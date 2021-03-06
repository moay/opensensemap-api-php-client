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
     * Looks for the first sensor of the given type and returns it
     *
     * @param string $sensorType
     *
     * @return SensorValue|null
     */
    public function getValueBySensor(string $sensorType): ?SensorValue
    {
        foreach ($this->sensorValues as $sensorValue) {
            if ($sensorType === $sensorValue->getSensorType()) {
                return $sensorValue;
            }
        }

        return null;
    }

    /**
     * Looks for the first value of the given type and returns it
     *
     * @param string $valueType
     *
     * @return SensorValue|null
     */
    public function getValueByType(string $valueType): ?SensorValue
    {
        foreach ($this->sensorValues as $sensorValue) {
            if ($valueType === $sensorValue->getValueType()) {
                return $sensorValue;
            }
        }

        return null;
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
