<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum CustomsDetailsExportType: string
{
    case OTHER = 'OTHER';
    case PRESENT = 'PRESENT';
    case COMMERCIAL_SAMPLE = 'COMMERCIAL_SAMPLE';
    case DOCUMENT = 'DOCUMENT';
    case RETURN_OF_GOODS = 'RETURN_OF_GOODS';
    case COMMERCIAL_GOODS = 'COMMERCIAL_GOODS';
}
