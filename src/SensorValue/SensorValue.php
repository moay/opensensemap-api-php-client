<?php

namespace Moay\OpensensemapApiClient\SensorValue;

use Moay\OpensensemapApiClient\Exceptions\OpensensemapApiClientException;

/**
 * Class SensorValue.
 */
class SensorValue implements \JsonSerializable
{
    const TYPE_TEMPERATURE = 'temperature';
    const TYPE_HUMIDITY = 'humidity';
    const TYPE_AIR_PRESSURE = 'air-pressure';
    const TYPE_UV_INTENSITY = 'uv-intensity';
    const TYPE_ILLUMINANCE = 'illuminance';
    const TYPE_POLLUTION_LARGE = 'pollution-large';
    const TYPE_POLLUTION_SMALL = 'pollution-small';

    const UNIT_DEGREE_CELSIUS = '°C';
    const UNIT_PERCENT = '%';
    const UNIT_HECTOPASCAL = 'hPa';
    const UNIT_LUX = 'lx';
    const UNIT_MICROWATT_PER_CENTIMETER_SQUARE = 'μW/cm²';
    const UNIT_MICROGRAM_PER_METER_CUBE = 'µg/m³';

    const KNOWN_VALUE_TYPES = [
        self::TYPE_TEMPERATURE,
        self::TYPE_HUMIDITY,
        self::TYPE_AIR_PRESSURE,
        self::TYPE_UV_INTENSITY,
        self::TYPE_ILLUMINANCE,
        self::TYPE_POLLUTION_LARGE,
        self::TYPE_POLLUTION_SMALL,
    ];

    const KNOWN_SENSOR_UNITS = [
        'HDC1080' => [self::UNIT_DEGREE_CELSIUS, self::UNIT_PERCENT],
        'HDC1008' => [self::UNIT_DEGREE_CELSIUS, self::UNIT_PERCENT],
        'SDS 011' => [self::UNIT_MICROGRAM_PER_METER_CUBE],
        'BME280' => [self::UNIT_HECTOPASCAL, self::UNIT_DEGREE_CELSIUS, self::UNIT_PERCENT],
        'BMP280' => [self::UNIT_HECTOPASCAL],
        'TSL45315' => [self::UNIT_LUX],
        'VEML6070' => [self::UNIT_MICROWATT_PER_CENTIMETER_SQUARE],
    ];

    const KNOWN_VALUE_TYPE_TITLES = [
        'Temperatur' => self::TYPE_TEMPERATURE,
        'Beleuchtungsstärke' => self::TYPE_ILLUMINANCE,
        'UV-Intensität' => self::TYPE_UV_INTENSITY,
        'PM10' => self::TYPE_POLLUTION_LARGE,
        'PM2.5' => self::TYPE_POLLUTION_SMALL,
        'Luftdruck' => self::TYPE_AIR_PRESSURE,
        'rel. Luftfeuchte' => self::TYPE_HUMIDITY,
    ];

    const VALUE_TYPE_UNITS = [
        self::TYPE_TEMPERATURE => self::UNIT_DEGREE_CELSIUS,
        self::TYPE_HUMIDITY => self::UNIT_PERCENT,
        self::TYPE_AIR_PRESSURE => self::UNIT_HECTOPASCAL,
        self::TYPE_UV_INTENSITY => self::UNIT_MICROWATT_PER_CENTIMETER_SQUARE,
        self::TYPE_ILLUMINANCE => self::UNIT_LUX,
        self::TYPE_POLLUTION_LARGE => self::UNIT_MICROGRAM_PER_METER_CUBE,
        self::TYPE_POLLUTION_SMALL => self::UNIT_MICROGRAM_PER_METER_CUBE,
    ];

    /** @var string */
    private $sensorType;

    /** @var string */
    private $sensorId;

    /** @var string */
    private $valueType;

    /** @var string */
    private $unit;

    /** @var \DateTimeImmutable */
    private $measurementTime;

    /** @var mixed */
    private $value;

    /**
     * SensorValue constructor.
     *
     * @param string $sensorType
     * @param string $sensorId
     * @param string $valueType
     *
     * @throws OpensensemapApiClientException
     */
    public function __construct(string $sensorType, string $sensorId, string $valueType)
    {
        if (!array_key_exists($sensorType, self::KNOWN_SENSOR_UNITS)) {
            throw new OpensensemapApiClientException(sprintf(
                'Unknown sensor type %s',
                $sensorType
            ));
        }

        if (!in_array($valueType, self::KNOWN_VALUE_TYPES)) {
            throw new OpensensemapApiClientException(sprintf(
                'Unknown value type %s',
                $valueType
            ));
        }

        if (!in_array(self::VALUE_TYPE_UNITS[$valueType], self::KNOWN_SENSOR_UNITS[$sensorType])) {
            throw new OpensensemapApiClientException(sprintf(
                'Unknown value type %s for sensor %s',
                $valueType,
                $sensorType
            ));
        }

        $this->sensorType = $sensorType;
        $this->sensorId = $sensorId;
        $this->valueType = $valueType;
    }

    /**
     * @return string
     */
    public function getSensorType(): string
    {
        return $this->sensorType;
    }

    /**
     * @return string
     */
    public function getSensorId(): string
    {
        return $this->sensorId;
    }

    /**
     * @param string $unit
     *
     * @throws OpensensemapApiClientException
     */
    public function setUnit(string $unit)
    {
        if (!in_array($unit, self::KNOWN_SENSOR_UNITS[$this->sensorType])) {
            throw new OpensensemapApiClientException(sprintf(
                'Unit %s not known for sensor %s',
                $unit,
                $this->sensorType
            ));
        }
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getValueType(): string
    {
        return $this->valueType;
    }

    /**
     * @param \DateTimeImmutable $measurementTime
     */
    public function setMeasurementTime(\DateTimeImmutable $measurementTime)
    {
        $this->measurementTime = $measurementTime;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getMeasurementTime(): \DateTimeImmutable
    {
        return $this->measurementTime;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->valueType,
            'sensor' => $this->sensorType,
            'id' => $this->sensorId,
            'value' => $this->value,
            'unit' => $this->unit,
            'time' => $this->measurementTime,
        ];
    }


}
