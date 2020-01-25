![travis-ci-status](https://travis-ci.org/moay/opensensemap-api-php-client.svg?branch=master)

# opensensemap-api-php-client

<img src="https://user-images.githubusercontent.com/3605512/62291810-2495bb80-b465-11e9-932e-2271f7e0167b.png?v=4&s=20" width="300">

A lightweight client for retrieving sensor data from a senseBox from the opensensemap api.

**This is not an official project from the senseBox team.**

## Setup

Install via composer:

```
composer require moay/opensensemap-api-php-client
```

## Usage

Take a look at the implementation example in the directory `example`.

```php
$client = OpensensemapApiClientFactory::create();

// Change senseBox id
$senseBoxData = $client->getSenseBoxData('someSenseBoxId');

foreach ($senseBoxData as $sensorValue) {
    // $sensorValue->getValueType()
    // $sensorValue->getValue()
    // $sensorValue->getUnit()
    // $sensorValue->getSensorType()
    // $sensorValue->getMeasurementTime()->format('Y-m-d H:i:s')
}
```

You don't have to iterate over all values, just use this handy function:

```php
$temperature = $senseBoxData->getValueByType(SensorValue::TYPE_TEMPERATURE);
```

Outputting values can be done directly by casting the values to strings:

```php
echo 'Temperature: '.$temperature; // Output: Temperature: 12 °C
echo $temperature; // Output: 12 °C
$temperatureString = (string) $temperature // $temperatureString = '12 °C' 
```

## Features

The client gives you the latest data for a specific senseBox from the OpenSenseMap Api.

As there was no need for further features, none have been implemented. This could easily be done, though. Feel free to submit a PR.

## License

The client is published under the [MIT license](LICENSE).

[senseBox](https://sensebox.de) and all related contents, visuals and brands are published under CC licenses or other public domain licenses.
Make sure to check out the project.
