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
 * Defines the PDF DPI resolution.
 */
class Dpi implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new Dpi instance.
     *
     * @param int $dpi
     */
    public function __construct(
        protected int $dpi,
    ) {
        if ($dpi < 72 || $dpi > 600) {
            throw new \InvalidArgumentException(
                sprintf('Invalid DPI value %d. Must be between 72 and 600.', $dpi)
            );
        }
    }

    /**
     * Returns the DPI.
     *
     * @return int
     */
    public function dpi(): int
    {
        return $this->dpi;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['dpi' => $this->dpi()];
    }
}