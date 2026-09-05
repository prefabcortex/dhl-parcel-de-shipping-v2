<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Model;

use Override;
use Prefabcortex\DhlParcelDeShippingV2\Exception\MalformedDataException;
use Prefabcortex\DhlParcelDeShippingV2\Optional\None;
use Prefabcortex\DhlParcelDeShippingV2\Optional\Option;
use Prefabcortex\DhlParcelDeShippingV2\Optional\Some;

use function array_key_exists;
use function array_replace;
use function get_debug_type;
use function is_string;
use function sprintf;

final readonly class ServiceInformationBackend implements SelfNormalizingModel
{
    /**
     * @param Option<string>           $env
     * @param Option<string>           $version
     * @param array<int|string, mixed> $additionalProperties
     */
    public function __construct(private Option $env, private Option $version, private array $additionalProperties)
    {
    }

    /** @param array<int|string, mixed> $additionalProperties */
    public static function create(array $additionalProperties = []): self
    {
        return new self(None::create(), None::create(), $additionalProperties);
    }

    /**
     * environment.
     *
     * @return Option<string>
     */
    public function getEnv(): Option
    {
        return $this->env;
    }

    public function withEnv(string $env): self
    {
        return new self(Some::create($env), $this->version, $this->additionalProperties);
    }

    /**
     * version of backend.
     *
     * @return Option<string>
     */
    public function getVersion(): Option
    {
        return $this->version;
    }

    public function withVersion(string $version): self
    {
        return new self($this->env, Some::create($version), $this->additionalProperties);
    }

    /** @return array<int|string, mixed> */
    public function getAdditionalProperties(): array
    {
        return $this->additionalProperties;
    }

    /**
     * @param array<int|string, mixed> $data
     *
     * @throws MalformedDataException
     */
    public static function fromArray(array $data): self
    {
        $env = None::create();
        $version = None::create();
        if (array_key_exists('env', $data)) {
            $envRaw = $data['env'];
            if (!is_string($envRaw)) {
                throw new MalformedDataException(sprintf('Property "env" must be string, got %s.', get_debug_type($envRaw)));
            }
            $env = Some::create($envRaw);
            unset($data['env']);
        }
        if (array_key_exists('version', $data)) {
            $versionRaw = $data['version'];
            if (!is_string($versionRaw)) {
                throw new MalformedDataException(sprintf('Property "version" must be string, got %s.', get_debug_type($versionRaw)));
            }
            $version = Some::create($versionRaw);
            unset($data['version']);
        }
        $additionalProperties = $data;

        return new self($env, $version, $additionalProperties);
    }

    /** @return array<int|string, mixed> */
    #[Override]
    public function toArray(): array
    {
        $dataArray = [];
        $envOption = $this->env;
        if ($envOption->isDefined()) {
            $env = $envOption->get();
            $dataArray['env'] = $env;
        }
        $versionOption = $this->version;
        if ($versionOption->isDefined()) {
            $version = $versionOption->get();
            $dataArray['version'] = $version;
        }
        $dataArray = array_replace($dataArray, $this->getAdditionalProperties());

        return $dataArray;
    }
}
