<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

enum Product: string
{
    case V01PAK = 'V01PAK';
    case V53WPAK = 'V53WPAK';
    case V54EPAK = 'V54EPAK';
    case V62WP = 'V62WP';
    case V62KP = 'V62KP';
    case V66WPI = 'V66WPI';
}
