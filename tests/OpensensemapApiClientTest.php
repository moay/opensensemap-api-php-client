<?php

namespace Moay\OpensensemapApiClient\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Moay\OpensensemapApiClient\Exceptions\OpensensemapApiClientException;
use Moay\OpensensemapApiClient\OpensensemapApiClient;
use Moay\OpensensemapApiClient\SensorValue\SensorValue;
use Moay\OpensensemapApiClient\SensorValue\SensorValueCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class OpensensemapApiClientTest.
 */
class OpensensemapApiClientTest extends TestCase
{
    public function testRequestReturnsProperlyMappedSensorValuesSample1()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"_id":"123456789123456","createdAt":"2018-07-28T07:11:40.555Z","updatedAt":"2019-08-01T07:34:13.111Z","name":"Somewhere","currentLocation":{"timestamp":"2018-07-28T07:11:40.543Z","coordinates":[37.234332396,-115.80666344],"type":"Point"},"exposure":"indoor","sensors":[{"_id":"1234567891234568","title":"Temperatur","unit":"°C","sensorType":"BME280","icon":"osem-thermometer","lastMeasurement":{"value":"20.36","createdAt":"2019-08-01T07:34:12.873Z"}},{"_id":"1234567891234567","title":"rel. Luftfeuchte","unit":"%","sensorType":"BME280","icon":"osem-humidity","lastMeasurement":{"value":"62.26","createdAt":"2019-08-01T07:34:12.873Z"}},{"_id":"1234567891234566","title":"Luftdruck","unit":"hPa","sensorType":"BME280","icon":"osem-barometer","lastMeasurement":{"value":"99133.97","createdAt":"2019-08-01T07:34:12.873Z"}},{"title":"Beleuchtungsstärke","unit":"lx","sensorType":"TSL45315","icon":"osem-brightness","_id":"1234567891234565","lastMeasurement":null},{"title":"UV-Intensität","unit":"μW/cm²","sensorType":"VEML6070","icon":"osem-brightness","_id":"1234567891234564","lastMeasurement":null},{"title":"PM10","unit":"µg/m³","sensorType":"SDS 011","icon":"osem-cloud","_id":"1234567891234563","lastMeasurement":{"value":"2.93","createdAt":"2019-08-01T07:34:12.873Z"}},{"title":"PM2.5","unit":"µg/m³","sensorType":"SDS 011","icon":"osem-cloud","_id":"1234567891234562","lastMeasurement":{"value":"2.20","createdAt":"2019-08-01T07:34:12.873Z"}}],"model":"homeWifiFeinstaub","lastMeasurementAt":"2019-08-01T07:34:12.873Z","loc":[{"geometry":{"timestamp":"2018-07-28T07:11:40.543Z","coordinates":[37.234332396,-115.80666344],"type":"Point"},"type":"Feature"}]}'),
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handler]);
        $client = new OpensensemapApiClient($httpClient);

        $sensorData = $client->getSenseBoxData('123456789123456');
        $this->assertInstanceOf(SensorValueCollection::class, $sensorData);
        $this->assertCount(5, $sensorData);

        /** @var SensorValue $sensorValue */
        foreach ($sensorData as $sensorValue) {
            $this->assertInstanceOf(SensorValue::class, $sensorValue);
            $this->assertNotNull($sensorValue->getValue());
            $this->assertNotNull($sensorValue->getUnit());
            $this->assertInstanceOf(\DateTimeImmutable::class, $sensorValue->getMeasurementTime());
        }
    }

    public function testRequestReturnsProperlyMappedSensorValuesSample2()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"_id":"123456789123456","createdAt":"2019-07-31T19:52:40.731Z","updatedAt":"2019-08-01T07:33:39.097Z","name":"Somewhere","currentLocation":{"timestamp":"2019-07-31T19:52:40.721Z","coordinates":[37.234332396,-115.80666344],"type":"Point"},"exposure":"indoor","sensors":[{"title":"Temperatur","unit":"°C","sensorType":"HDC1080","icon":"osem-thermometer","_id":"123456789123456","lastMeasurement":{"value":"24.90","createdAt":"2019-08-01T07:33:39.093Z"}},{"title":"rel. Luftfeuchte","unit":"%","sensorType":"HDC1080","icon":"osem-humidity","_id":"123456789123456","lastMeasurement":{"value":"49.58","createdAt":"2019-08-01T07:33:39.093Z"}},{"title":"Luftdruck","unit":"hPa","sensorType":"BMP280","icon":"osem-barometer","_id":"123456789123456","lastMeasurement":{"value":"1006.33","createdAt":"2019-08-01T07:33:39.093Z"}},{"title":"Beleuchtungsstärke","unit":"lx","sensorType":"TSL45315","icon":"osem-brightness","_id":"123456789123456","lastMeasurement":{"value":"116.00","createdAt":"2019-08-01T07:33:39.093Z"}},{"title":"UV-Intensität","unit":"μW/cm²","sensorType":"VEML6070","icon":"osem-brightness","_id":"123456789123456","lastMeasurement":{"value":"5.62","createdAt":"2019-08-01T07:33:39.093Z"}}],"model":"homeV2Wifi","lastMeasurementAt":"2019-08-01T07:33:39.093Z","loc":[{"geometry":{"timestamp":"2019-07-31T19:52:40.721Z","coordinates":[37.234332396,-115.80666344],"type":"Point"},"type":"Feature"}]}'),
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handler]);
        $client = new OpensensemapApiClient($httpClient);

        $sensorData = $client->getSenseBoxData('123456789123456');
        $this->assertInstanceOf(SensorValueCollection::class, $sensorData);
        $this->assertCount(5, $sensorData);

        /** @var SensorValue $sensorValue */
        foreach ($sensorData as $sensorValue) {
            $this->assertInstanceOf(SensorValue::class, $sensorValue);
            $this->assertNotNull($sensorValue->getValue());
            $this->assertNotNull($sensorValue->getUnit());
            $this->assertInstanceOf(\DateTimeImmutable::class, $sensorValue->getMeasurementTime());
        }
    }

    public function testUnknownSensorGetsMappedAsUnkown()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"_id":"123456789123456df","createdAt":"2019-07-10T07:36:22.817Z","updatedAt":"2019-08-02T06:13:10.358Z","name":"SomeWhere","currentLocation":{"type":"Point","coordinates":[37.234332396,-115.80666344],"timestamp":"2019-07-13T10:10:29.245Z"},"exposure":"outdoor","sensors":[{"icon":"osem-thermometer","title":"Temperature","unit":"°C","sensorType":"BME280","_id":"123456789123456e2","lastMeasurement":{"value":"17","createdAt":"2019-08-02T06:13:10.046Z"}},{"_id":"123456789123456e1","title":"Luftfeuchte","unit":"rel.%","sensorType":"BME280","icon":"osem-humidity","lastMeasurement":{"value":"71.7","createdAt":"2019-08-02T06:13:10.147Z"}},{"_id":"123456789123456e0","title":"Luftdruck","unit":"hPa","sensorType":"BME280","icon":"osem-dashboard","lastMeasurement":{"value":"983.9","createdAt":"2019-08-02T06:13:10.243Z"}},{"_id":"5d2795f130bde6001a1d2b7a","title":"Taupunkt","unit":"°C","sensorType":"Calc from BME","icon":"osem-moisture","lastMeasurement":{"value":"11.9","createdAt":"2019-08-02T06:13:10.353Z"}}],"model":"custom","description":"First Sensorbox DIY with ESp8266 and BME280.","lastMeasurementAt":"2019-08-02T06:13:10.353Z","loc":[{"geometry":{"type":"Point","coordinates":[37.234332396,-115.80666344],"timestamp":"2019-07-13T10:10:29.245Z"},"type":"Feature"}]}'),
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handler]);
        $client = new OpensensemapApiClient($httpClient);

        $sensorData = $client->getSenseBoxData('123456789123456');
        $this->assertCount(4, $sensorData);
        $unknownSensorsValue = $sensorData->getValueBySensor(SensorValue::SENSOR_UNKOWN);
        $this->assertInstanceOf(SensorValue::class, $unknownSensorsValue);
    }

    public function testValuesCanBeAccessedByType()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"_id":"123456789123456df","createdAt":"2019-07-10T07:36:22.817Z","updatedAt":"2019-08-02T06:13:10.358Z","name":"SomeWhere","currentLocation":{"type":"Point","coordinates":[37.234332396,-115.80666344],"timestamp":"2019-07-13T10:10:29.245Z"},"exposure":"outdoor","sensors":[{"icon":"osem-thermometer","title":"Temperature","unit":"°C","sensorType":"BME280","_id":"123456789123456e2","lastMeasurement":{"value":"17","createdAt":"2019-08-02T06:13:10.046Z"}},{"_id":"123456789123456e1","title":"Luftfeuchte","unit":"rel.%","sensorType":"BME280","icon":"osem-humidity","lastMeasurement":{"value":"71.7","createdAt":"2019-08-02T06:13:10.147Z"}},{"_id":"123456789123456e0","title":"Luftdruck","unit":"hPa","sensorType":"BME280","icon":"osem-dashboard","lastMeasurement":{"value":"983.9","createdAt":"2019-08-02T06:13:10.243Z"}},{"_id":"5d2795f130bde6001a1d2b7a","title":"Taupunkt","unit":"°C","sensorType":"Calc from BME","icon":"osem-moisture","lastMeasurement":{"value":"11.9","createdAt":"2019-08-02T06:13:10.353Z"}}],"model":"custom","description":"First Sensorbox DIY with ESp8266 and BME280.","lastMeasurementAt":"2019-08-02T06:13:10.353Z","loc":[{"geometry":{"type":"Point","coordinates":[37.234332396,-115.80666344],"timestamp":"2019-07-13T10:10:29.245Z"},"type":"Feature"}]}'),
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handler]);
        $client = new OpensensemapApiClient($httpClient);

        $sensorData = $client->getSenseBoxData('123456789123456');

        $dewpoint = $sensorData->getValueBySensor(SensorValue::SENSOR_UNKOWN);
        $this->assertInstanceOf(SensorValue::class, $dewpoint);
        $this->assertEquals(SensorValue::TYPE_DEWPOINT, $dewpoint->getValueType());
        $this->assertEquals(11.9, $dewpoint->getValue());
        unset($dewpoint);

        $dewpoint2 = $sensorData->getValueByType(SensorValue::TYPE_DEWPOINT);
        $this->assertInstanceOf(SensorValue::class, $dewpoint2);
        $this->assertEquals(SensorValue::SENSOR_UNKOWN, $dewpoint2->getSensorType());
        $this->assertEquals(11.9, $dewpoint2->getValue());
        unset($dewpoint2);

        $temperature = $sensorData->getValueByType(SensorValue::TYPE_TEMPERATURE);
        $this->assertInstanceOf(SensorValue::class, $temperature);
        $this->assertEquals(SensorValue::SENSOR_BME280, $temperature->getSensorType());
        $this->assertEquals(17, $temperature->getValue());
    }

    public function testValuesAreIterable()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"_id":"123456789123456df","createdAt":"2019-07-10T07:36:22.817Z","updatedAt":"2019-08-02T06:13:10.358Z","name":"SomeWhere","currentLocation":{"type":"Point","coordinates":[37.234332396,-115.80666344],"timestamp":"2019-07-13T10:10:29.245Z"},"exposure":"outdoor","sensors":[{"icon":"osem-thermometer","title":"Temperature","unit":"°C","sensorType":"BME280","_id":"123456789123456e2","lastMeasurement":{"value":"17","createdAt":"2019-08-02T06:13:10.046Z"}},{"_id":"123456789123456e1","title":"Luftfeuchte","unit":"rel.%","sensorType":"BME280","icon":"osem-humidity","lastMeasurement":{"value":"71.7","createdAt":"2019-08-02T06:13:10.147Z"}},{"_id":"123456789123456e0","title":"Luftdruck","unit":"hPa","sensorType":"BME280","icon":"osem-dashboard","lastMeasurement":{"value":"983.9","createdAt":"2019-08-02T06:13:10.243Z"}},{"_id":"5d2795f130bde6001a1d2b7a","title":"Taupunkt","unit":"°C","sensorType":"Calc from BME","icon":"osem-moisture","lastMeasurement":{"value":"11.9","createdAt":"2019-08-02T06:13:10.353Z"}}],"model":"custom","description":"First Sensorbox DIY with ESp8266 and BME280.","lastMeasurementAt":"2019-08-02T06:13:10.353Z","loc":[{"geometry":{"type":"Point","coordinates":[37.234332396,-115.80666344],"timestamp":"2019-07-13T10:10:29.245Z"},"type":"Feature"}]}'),
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handler]);
        $client = new OpensensemapApiClient($httpClient);

        $sensorData = $client->getSenseBoxData('123456789123456');
        $this->assertIsIterable($sensorData);
    }

    public function testValuesCanBeUsedAsString()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"_id":"123456789123456df","createdAt":"2019-07-10T07:36:22.817Z","updatedAt":"2019-08-02T06:13:10.358Z","name":"SomeWhere","currentLocation":{"type":"Point","coordinates":[37.234332396,-115.80666344],"timestamp":"2019-07-13T10:10:29.245Z"},"exposure":"outdoor","sensors":[{"icon":"osem-thermometer","title":"Temperature","unit":"°C","sensorType":"BME280","_id":"123456789123456e2","lastMeasurement":{"value":"17","createdAt":"2019-08-02T06:13:10.046Z"}},{"_id":"123456789123456e1","title":"Luftfeuchte","unit":"rel.%","sensorType":"BME280","icon":"osem-humidity","lastMeasurement":{"value":"71.7","createdAt":"2019-08-02T06:13:10.147Z"}},{"_id":"123456789123456e0","title":"Luftdruck","unit":"hPa","sensorType":"BME280","icon":"osem-dashboard","lastMeasurement":{"value":"983.9","createdAt":"2019-08-02T06:13:10.243Z"}},{"_id":"5d2795f130bde6001a1d2b7a","title":"Taupunkt","unit":"°C","sensorType":"Calc from BME","icon":"osem-moisture","lastMeasurement":{"value":"11.9","createdAt":"2019-08-02T06:13:10.353Z"}}],"model":"custom","description":"First Sensorbox DIY with ESp8266 and BME280.","lastMeasurementAt":"2019-08-02T06:13:10.353Z","loc":[{"geometry":{"type":"Point","coordinates":[37.234332396,-115.80666344],"timestamp":"2019-07-13T10:10:29.245Z"},"type":"Feature"}]}'),
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handler]);
        $client = new OpensensemapApiClient($httpClient);

        $sensorData = $client->getSenseBoxData('123456789123456');
        $temperature = $sensorData->getValueByType(SensorValue::TYPE_TEMPERATURE);
        $this->assertEquals('17 °C', (string)$temperature);
    }
}
