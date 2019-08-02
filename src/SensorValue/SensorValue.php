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
    const TYPE_DEWPOINT = 'dewpoint';

    const UNIT_DEGREE_CELSIUS = '°C';
    const UNIT_PERCENT = '%';
    const UNIT_PERCENT_RELATIVE = 'rel.%';
    const UNIT_HECTOPASCAL = 'hPa';
    const UNIT_LUX = 'lx';
    const UNIT_MICROWATT_PER_CENTIMETER_SQUARE = 'μW/cm²';
    const UNIT_MICROGRAM_PER_METER_CUBE = 'µg/m³';

    const SENSOR_HDC1008 = 'HDC1008';
    const SENSOR_HDC1080 = 'HDC1080';
    const SENSOR_SDS011 = 'SDS 011';
    const SENSOR_BME280 = 'BME280';
    const SENSOR_BMP280 = 'BMP280';
    const SENSOR_TSL45315 = 'TSL45315';
    const SENSOR_VEML6070 = 'VEML6070';
    const SENSOR_UNKOWN = 'unknown';

    const KNOWN_VALUE_TYPES = [
        self::TYPE_TEMPERATURE,
        self::TYPE_HUMIDITY,
        self::TYPE_AIR_PRESSURE,
        self::TYPE_UV_INTENSITY,
        self::TYPE_ILLUMINANCE,
        self::TYPE_POLLUTION_LARGE,
        self::TYPE_POLLUTION_SMALL,
        self::TYPE_DEWPOINT,
    ];

    const KNOWN_SENSORS = [
        self::SENSOR_HDC1008,
        self::SENSOR_HDC1080,
        self::SENSOR_SDS011,
        self::SENSOR_BME280,
        self::SENSOR_BMP280,
        self::SENSOR_TSL45315,
        self::SENSOR_VEML6070,
    ];

    const KNOWN_SENSOR_UNITS = [
        self::SENSOR_HDC1080 => [self::UNIT_DEGREE_CELSIUS, self::UNIT_PERCENT],
        self::SENSOR_HDC1008 => [self::UNIT_DEGREE_CELSIUS, self::UNIT_PERCENT],
        self::SENSOR_SDS011 => [self::UNIT_MICROGRAM_PER_METER_CUBE],
        self::SENSOR_BME280 => [self::UNIT_HECTOPASCAL, self::UNIT_DEGREE_CELSIUS, self::UNIT_PERCENT, self::UNIT_PERCENT_RELATIVE],
        self::SENSOR_BMP280 => [self::UNIT_HECTOPASCAL],
        self::SENSOR_TSL45315 => [self::UNIT_LUX],
        self::SENSOR_VEML6070 => [self::UNIT_MICROWATT_PER_CENTIMETER_SQUARE],
    ];

    const KNOWN_VALUE_TYPE_TITLES = [
        'Temperatur' => self::TYPE_TEMPERATURE,
        'Temperature' => self::TYPE_TEMPERATURE,
        'Beleuchtungsstärke' => self::TYPE_ILLUMINANCE,
        'UV-Intensität' => self::TYPE_UV_INTENSITY,
        'PM10' => self::TYPE_POLLUTION_LARGE,
        'PM2.5' => self::TYPE_POLLUTION_SMALL,
        'Luftdruck' => self::TYPE_AIR_PRESSURE,
        'rel. Luftfeuchte' => self::TYPE_HUMIDITY,
        'Luftfeuchte' => self::TYPE_HUMIDITY,
        'Taupunkt' => self::TYPE_DEWPOINT,
    ];

    const VALUE_TYPE_UNITS = [
        self::TYPE_TEMPERATURE => self::UNIT_DEGREE_CELSIUS,
        self::TYPE_HUMIDITY => self::UNIT_PERCENT,
        self::TYPE_AIR_PRESSURE => self::UNIT_HECTOPASCAL,
        self::TYPE_UV_INTENSITY => self::UNIT_MICROWATT_PER_CENTIMETER_SQUARE,
        self::TYPE_ILLUMINANCE => self::UNIT_LUX,
        self::TYPE_POLLUTION_LARGE => self::UNIT_MICROGRAM_PER_METER_CUBE,
        self::TYPE_POLLUTION_SMALL => self::UNIT_MICROGRAM_PER_METER_CUBE,
        self::TYPE_DEWPOINT => self::UNIT_DEGREE_CELSIUS,
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
        if (!in_array($sensorType, self::KNOWN_SENSORS)) {
            $sensorType = self::SENSOR_UNKOWN;
        }

        if ($sensorType !== self::SENSOR_UNKOWN && !in_array(self::VALUE_TYPE_UNITS[$valueType], self::KNOWN_SENSOR_UNITS[$sensorType])) {
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
        if ($this->sensorType !== self::SENSOR_UNKOWN && !in_array($unit, self::KNOWN_SENSOR_UNITS[$this->sensorType])) {
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
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s %s',
            $this->getValue() ?? '--',
            $this->getUnit() ?? 'XX'
        );
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
