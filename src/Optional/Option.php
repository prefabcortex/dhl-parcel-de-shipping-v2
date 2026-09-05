<?php

declare(strict_types=1);

namespace Prefabcortex\DhlParcelDeShippingV2\Optional;

/** @template-covariant T */
interface Option
{
    public function isDefined(): bool;

    /** @return T */
    public function get(): mixed;

    /**
     * The value, or the one to use when there is none.
     *
     * Every consumer wrote `$o->isDefined() ? $o->get() : $default` by hand, which is three
     * mentions of `$o` to express one thing and puts the reader in front of a conditional where
     * there is no decision to make. The two methods stay: `isDefined()` for the branch that really
     * is one, `get()` for the value that must be there.
     *
     * @template U
     *
     * @param U $default
     *
     * @return T|U
     */
    public function getOrElse(mixed $default): mixed;
}
