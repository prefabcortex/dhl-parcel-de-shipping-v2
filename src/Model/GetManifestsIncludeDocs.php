<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum GetManifestsIncludeDocs: string
{
    case include = 'include';
    case URL = 'URL';
}
