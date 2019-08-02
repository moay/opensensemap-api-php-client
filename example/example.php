<?php

require __DIR__.'/../vendor/autoload.php';

use \Moay\OpensensemapApiClient\OpensensemapApiClientFactory;
use \Moay\OpensensemapApiClient\SensorValue\SensorValue;

$client = OpensensemapApiClientFactory::create();
$senseBoxData = $client->getSenseBoxData('5a0c2cc89fd3c200111118f0');

foreach ($senseBoxData as $sensorValue) {
    echo sprintf(
        '%s: %s %s (Sensor: %s, %s)'."\n",
        $sensorValue->getValueType(),
        $sensorValue->getValue(),
        $sensorValue->getUnit(),
        $sensorValue->getSensorType(),
        $sensorValue->getMeasurementTime()->format('Y-m-d H:i:s')
    );
}

$temperature = $senseBoxData->getValueByType(SensorValue::TYPE_TEMPERATURE);
echo 'Temperature by type: '.$temperature->getValue().' '.$temperature->getUnit()."\n";

// Shorthand string output
echo 'Temperature by type shorthand: '.$temperature."\n";
