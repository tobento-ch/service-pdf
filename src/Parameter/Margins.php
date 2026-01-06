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
 * Defines the page margins.
 */
class Margins implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new Margins instance.
     *
     * @param null|int|string $top
     * @param null|int|string $right
     * @param null|int|string $bottom
     * @param null|int|string $left
     */
    public function __construct(
        protected null|int|string $top = null,
        protected null|int|string $right = null,
        protected null|int|string $bottom = null,
        protected null|int|string $left = null,
    ) {}

    /**
     * Returns the top margin.
     *
     * @return null|int|string
     */
    public function top(): null|int|string
    {
        return $this->top;
    }

    /**
     * Returns the right margin.
     *
     * @return null|int|string
     */
    public function right(): null|int|string
    {
        return $this->right;
    }

    /**
     * Returns the bottom margin.
     *
     * @return null|int|string
     */
    public function bottom(): null|int|string
    {
        return $this->bottom;
    }

    /**
     * Returns the left margin.
     *
     * @return null|int|string
     */
    public function left(): null|int|string
    {
        return $this->left;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'top' => $this->top(),
            'right' => $this->right(),
            'bottom' => $this->bottom(),
            'left' => $this->left(),
        ];
    }
}