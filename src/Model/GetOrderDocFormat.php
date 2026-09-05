<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum GetOrderDocFormat: string
{
    case ZPL2 = 'ZPL2';
    case PDF = 'PDF';
}
