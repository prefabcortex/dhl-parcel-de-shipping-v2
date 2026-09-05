<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Parameter;

use Prefabcortex\DhlParcelDeShippingV2\Http\HeaderParameter;
use Prefabcortex\DhlParcelDeShippingV2\Http\HeaderParameters;
use Prefabcortex\DhlParcelDeShippingV2\Http\ScalarValue;
use Prefabcortex\DhlParcelDeShippingV2\Optional\None;
use Prefabcortex\DhlParcelDeShippingV2\Optional\Option;
use Prefabcortex\DhlParcelDeShippingV2\Optional\Some;

final class ManifestsPostHeaderParameters
{
    /**
     * @var Option<string>
     *                     Control the APIs response language via locale abbreviation. English (en-US) and german (de-DE) are supported. If not specified, the default is english.
     */
    private Option $acceptLanguage;

    public function __construct()
    {
        $this->acceptLanguage = None::create();
    }

    /** @return Option<string> */
    public function getAcceptLanguage(): Option
    {
        return $this->acceptLanguage;
    }

    public function setAcceptLanguage(string $acceptLanguage): self
    {
        $this->acceptLanguage = Some::create($acceptLanguage);

        return $this;
    }

    /**
     * @internal called by the generated operation, not part of this package's public
     *                  contract: it may change in any release
     */
    public function toHeaderParameters(): HeaderParameters
    {
        $parameters = [];
        if ($this->acceptLanguage->isDefined()) {
            $parameters[] = new HeaderParameter('Accept-Language', new ScalarValue($this->acceptLanguage->get()));
        }

        return new HeaderParameters(...$parameters);
    }
}
