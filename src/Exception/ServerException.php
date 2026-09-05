<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Exception;

/**
 * The 5xx side of the error surface: the API is at fault.
 *
 * The counterpart of {@see ClientException}, and split from it for the one decision a consumer
 * actually makes on the difference — whether retrying the same request could ever help.
 */
interface ServerException extends ResponseException
{
}
