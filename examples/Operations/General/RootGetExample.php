<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Examples\Operations\General;

use Prefabcortex\DhlParcelDeShippingV2\Client;
use Prefabcortex\DhlParcelDeShippingV2\Exception\ApiException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\RootGetInternalServerErrorException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\RootGetTooManyRequestsException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\RootGetUnauthorizedException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\TransportException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnexpectedStatusCodeException;
use Prefabcortex\DhlParcelDeShippingV2\Exception\UnsupportedValueException;
use Prefabcortex\DhlParcelDeShippingV2\Model\RootGetAccept;
use Prefabcortex\DhlParcelDeShippingV2\Model\ServiceInformation;
use Prefabcortex\DhlParcelDeShippingV2\Validation\ValidationException;

final class RootGetExample
{
    /**
     * Returns the current version of the API as major.minor.patch. Furthermore, it will also return
     * more details (semantic version number, revision, environment) of the API layer.
     *
     * Usage: pass an already-authenticated Client (see examples/Auth/).
     *
     *   $client = Client::withBasicAuth(...); // withApiKey/withOAuth also available, see examples/Auth/
     *   RootGetExample::rootGet($client);
     *
     * @throws ApiException
     * @throws UnsupportedValueException
     * @throws TransportException
     * @throws ValidationException
     * @throws MalformedDataException
     * @throws RootGetUnauthorizedException
     * @throws RootGetTooManyRequestsException
     * @throws RootGetInternalServerErrorException
     * @throws UnexpectedStatusCodeException
     */
    public static function rootGet(Client $client): ServiceInformation
    {
        $accept = [RootGetAccept::application_json, RootGetAccept::application_problem_json];

        return $client->general()->rootGet($accept);
    }
}
