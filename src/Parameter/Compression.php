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
 * Defines the PDF compression level.
 */
class Compression implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new Compression instance.
     *
     * @param int $level
     */
    public function __construct(
        protected int $level,
    ) {
        if ($level < 0 || $level > 9) {
            throw new \InvalidArgumentException(
                sprintf('Invalid compression level %d. Must be between 0 and 9.', $level)
            );
        }
    }

    /**
     * Returns the compression level.
     *
     * @return int
     */
    public function level(): int
    {
        return $this->level;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['level' => $this->level()];
    }
}