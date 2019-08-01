<?php

namespace Moay\OpensensemapApiClient\SensorValue;

/**
 * Class DataCollection.
 */
class SensorValueCollection implements \JsonSerializable
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


}
