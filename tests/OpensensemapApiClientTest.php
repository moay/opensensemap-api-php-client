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
 *
 * @author Manuel Voss <manuel.voss@i22.de>
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
        $this->assertCount(5, $sensorData->getSensorValues());

        /** @var SensorValue $sensorValue */
        foreach ($sensorData->getSensorValues() as $sensorValue) {
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
        $this->assertCount(5, $sensorData->getSensorValues());

        /** @var SensorValue $sensorValue */
        foreach ($sensorData->getSensorValues() as $sensorValue) {
            $this->assertInstanceOf(SensorValue::class, $sensorValue);
            $this->assertNotNull($sensorValue->getValue());
            $this->assertNotNull($sensorValue->getUnit());
            $this->assertInstanceOf(\DateTimeImmutable::class, $sensorValue->getMeasurementTime());
        }
    }

    public function testInvalidSensorThrowsException()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"_id":"123456789123456","createdAt":"2019-07-31T19:52:40.731Z","updatedAt":"2019-08-01T07:33:39.097Z","name":"Somewhere","currentLocation":{"timestamp":"2019-07-31T19:52:40.721Z","coordinates":[37.234332396,-115.80666344],"type":"Point"},"exposure":"indoor","sensors":[{"title":"Temperatur","unit":"°C","sensorType":"HDC1080","icon":"osem-thermometer","_id":"123456789123456","lastMeasurement":{"value":"24.90","createdAt":"2019-08-01T07:33:39.093Z"}},{"title":"rel. Luftfeuchte","unit":"%","sensorType":"HDC1080","icon":"osem-humidity","_id":"123456789123456","lastMeasurement":{"value":"49.58","createdAt":"2019-08-01T07:33:39.093Z"}},{"title":"Luftdruck","unit":"hPa","sensorType":"UNKOWNSENSOR","icon":"osem-barometer","_id":"123456789123456","lastMeasurement":{"value":"1006.33","createdAt":"2019-08-01T07:33:39.093Z"}},{"title":"Beleuchtungsstärke","unit":"lx","sensorType":"TSL45315","icon":"osem-brightness","_id":"123456789123456","lastMeasurement":{"value":"116.00","createdAt":"2019-08-01T07:33:39.093Z"}},{"title":"UV-Intensität","unit":"μW/cm²","sensorType":"VEML6070","icon":"osem-brightness","_id":"123456789123456","lastMeasurement":{"value":"5.62","createdAt":"2019-08-01T07:33:39.093Z"}}],"model":"homeV2Wifi","lastMeasurementAt":"2019-08-01T07:33:39.093Z","loc":[{"geometry":{"timestamp":"2019-07-31T19:52:40.721Z","coordinates":[37.234332396,-115.80666344],"type":"Point"},"type":"Feature"}]}'),
        ]);

        $handler = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handler]);
        $client = new OpensensemapApiClient($httpClient);

        $this->expectException(OpensensemapApiClientException::class);
        $sensorData = $client->getSenseBoxData('123456789123456');
    }
}
