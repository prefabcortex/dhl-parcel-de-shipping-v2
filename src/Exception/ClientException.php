<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Exception;

/**
 * The 4xx side of the error surface: the request is at fault.
 *
 * Implemented by the abstract class the generator writes for every declared status below 500, and
 * through it by every operation exception under one. A response is always in hand by the time one
 * of these is raised, hence {@see ResponseException} rather than the bare marker.
 */
interface ClientException extends ResponseException
{
}
