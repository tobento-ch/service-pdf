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
 * Defines a custom page size.
 */
class PageSize implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new PageSize instance.
     *
     * @param float $width
     * @param float $height
     */
    public function __construct(
        protected float $width,
        protected float $height,
    ) {}

    /**
     * Returns the page width.
     *
     * @return float
     */
    public function width(): float
    {
        return $this->width;
    }

    /**
     * Returns the page height.
     *
     * @return float
     */
    public function height(): float
    {
        return $this->height;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'width' => $this->width(),
            'height' => $this->height(),
        ];
    }
}