<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum ManifestsPostAccept: string
{
    case application_json = 'application/json';
    case application_problem_json = 'application/problem+json';
}
