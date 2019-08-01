![travis-ci-status](https://travis-ci.org/moay/opensensemap-api-php-client.svg?branch=master)

# opensensemap-api-php-client

A client for retrieving sensor data from a senseBox from the opensensemap api

## Usage

Take a look at the implementation example in the directory `example`.

```php
$client = \Moay\OpensensemapApiClient\OpensensemapApiClientFactory::create();

// Change senseBox id
$senseBoxData = $client->getSenseBoxData('someSenseBoxId');

foreach ($senseBoxData->getSensorValues() as $sensorValue) {
    // $sensorValue->getValueType()
    // $sensorValue->getValue()
    // $sensorValue->getUnit()
    // $sensorValue->getSensorType()
    // $sensorValue->getMeasurementTime()->format('Y-m-d H:i:s')
}
```

## Features

The client gives you the latest data for a specific senseBox from the OpenSenseMap Api.

## License

The client is published under the [MIT license](LICENSE.md).

senseBox and all related contents, visuals and brands are published under CC licenses or other public domain licenses.
Make sure to check out the project.
