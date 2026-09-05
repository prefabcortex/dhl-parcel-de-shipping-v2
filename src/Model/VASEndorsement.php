<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum VASEndorsement: string
{
    case RETURN = 'RETURN';
    case ABANDON = 'ABANDON';
}
