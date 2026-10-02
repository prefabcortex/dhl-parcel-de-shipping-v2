<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Operations\General;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedResponseException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ResponseValidationException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\RootGetInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\RootGetTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\RootGetUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedContentTypeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\ServiceInformation;

final class RootGetExample
{
    /**
     * Usage: pass an already-authenticated Client (see examples/Auth/).
     *
     *   $client = Client::withBasicAuth($username, $password, $config); // withApiKey/withOAuth also available, see examples/Auth/
     *   RootGetExample::rootGet($client);
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     * @throws ResponseValidationException
     * @throws MalformedResponseException
     * @throws RootGetUnauthorizedException
     * @throws RootGetTooManyRequestsException
     * @throws RootGetInternalServerErrorException
     * @throws UnexpectedContentTypeException
     * @throws UnexpectedStatusCodeException
     */
    public static function rootGet(Client $client): ServiceInformation
    {
        return $client->general()->rootGet();
    }
}
