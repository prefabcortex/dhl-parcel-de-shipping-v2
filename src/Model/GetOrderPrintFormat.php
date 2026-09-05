<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum GetOrderPrintFormat: string
{
    case A4 = 'A4';
    case _910_300_600 = '910-300-600';
    case _910_300_610 = '910-300-610';
    case _910_300_700 = '910-300-700';
    case _910_300_700_oz = '910-300-700-oz';
    case _910_300_710 = '910-300-710';
    case _910_300_300 = '910-300-300';
    case _910_300_300_oz = '910-300-300-oz';
    case _910_300_400 = '910-300-400';
    case _910_300_410 = '910-300-410';
    case _100x70mm = '100x70mm';
}
