<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum CustomsDetailsShippingConditions: string
{
    case DAP = 'DAP';
    case DDP = 'DDP';
}
