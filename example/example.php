<?php

require __DIR__.'/../vendor/autoload.php';

$client = \Moay\OpensensemapApiClient\OpensensemapApiClientFactory::create();
$senseBoxData = $client->getSenseBoxData('5a0c2cc89fd3c200111118f0');

foreach ($senseBoxData->getSensorValues() as $sensorValue) {
    echo sprintf(
        '%s: %s %s (Sensor: %s, %s)<br/>',
        $sensorValue->getValueType(),
        $sensorValue->getValue(),
        $sensorValue->getUnit(),
        $sensorValue->getSensorType(),
        $sensorValue->getMeasurementTime()->format('Y-m-d H:i:s')
    );
}
