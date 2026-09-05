<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum GetOrderIncludeDocs: string
{
    case include = 'include';
    case URL = 'URL';
}
