<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum GetLabelAccept: string
{
    case application_pdf = 'application/pdf';
    case application_problem_json = 'application/problem+json';
}
