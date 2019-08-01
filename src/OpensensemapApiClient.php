<?php

namespace Moay\OpensensemapApiClient;

use GuzzleHttp\Client;
use Moay\OpensensemapApiClient\Exceptions\OpensensemapApiClientException;
use Moay\OpensensemapApiClient\SensorValue\SensorValue;
use Moay\OpensensemapApiClient\SensorValue\SensorValueCollection;

/**
 * Class OpensensemapApiClient.
 */
class OpensensemapApiClient
{
    const SENSEBOX_DATA_URL = 'https://api.opensensemap.org/boxes/:senseBoxId?format=json';

    /** @var Client */
    private $httpClient;

    /**
     * OpensensemapApiClient constructor.
     *
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $senseBoxId
     *
     * @return SensorValueCollection
     *
     * @throws OpensensemapApiClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSenseBoxData(string $senseBoxId): SensorValueCollection
    {
        $sensorData = $this->getSenseBoxDataFromApi($senseBoxId);

        return $this->mapSensorData($sensorData);
    }

    /**
     * @param string $senseBoxId
     *
     * @return mixed
     *
     * @throws OpensensemapApiClientException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getSenseBoxDataFromApi(string $senseBoxId): array
    {
        $url = str_replace(':senseBoxId', $senseBoxId, self::SENSEBOX_DATA_URL);
        $response = $this->httpClient->request('GET', $url);

        if ($response->getStatusCode() !== 200) {
            throw new OpensensemapApiClientException(sprintf(
                'Could not retrieve valid data for senseBox with id %s',
                $senseBoxId
            ));
        }

        return json_decode($response->getBody(), JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @param array $sensorData
     *
     * @return SensorValueCollection
     *
     * @throws OpensensemapApiClientException
     * @throws \Exception
     */
    private function mapSensorData(array $sensorData): SensorValueCollection
    {
        $collection = new SensorValueCollection();

        foreach ($sensorData['sensors'] as $sensorValueData) {
            if(!is_array($sensorValueData['lastMeasurement'])) {
                continue;
            }

            $sensorValue = new SensorValue(
                $sensorValueData['sensorType'],
                $sensorValueData['_id'],
                SensorValue::KNOWN_VALUE_TYPE_TITLES[$sensorValueData['title']]
            );
            $sensorValue->setValue($sensorValueData['lastMeasurement']['value']);
            $sensorValue->setMeasurementTime(new \DateTimeImmutable($sensorValueData['lastMeasurement']['createdAt']));
            $sensorValue->setUnit($sensorValueData['unit']);

            $collection->addSensorValue($sensorValue);
        }

        return $collection;
    }
}
