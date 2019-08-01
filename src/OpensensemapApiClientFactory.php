<?php

namespace Moay\OpensensemapApiClient;

use GuzzleHttp\Client;

/**
 * Class OpensensemapApiClientFactory.
 */
class OpensensemapApiClientFactory
{
    /**
     * @return OpensensemapApiClient
     */
    public static function create(): OpensensemapApiClient
    {
        $httpClient = new Client();

        return new OpensensemapApiClient($httpClient);
    }
}
