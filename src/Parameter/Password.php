<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Pdf\Parameter;

use JsonSerializable;
use Tobento\Service\Pdf\ParameterInterface;

/**
 * Defines a PDF password.
 */
class Password implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new Password instance.
     *
     * @param string $password
     */
    public function __construct(
        protected string $password,
    ) {}

    /**
     * Returns the password.
     *
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['password' => $this->password()];
    }
}