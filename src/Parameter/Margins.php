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
use Tobento\Service\Pdf\Exception\ParametersException;
use Tobento\Service\Pdf\ParameterInterface;

/**
 * Defines the page margins in millimeters.
 */
class Margins implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new Margins instance.
     *
     * @param null|int|string $top In millimeters, e.g. 10 or '10mm'.
     * @param null|int|string $right In millimeters, e.g. 10 or '10mm'.
     * @param null|int|string $bottom In millimeters, e.g. 10 or '10mm'.
     * @param null|int|string $left In millimeters, e.g. 10 or '10mm'.
     * @throws ParametersException If a margin is not in millimeters.
     */
    public function __construct(
        protected null|int|string $top = null,
        protected null|int|string $right = null,
        protected null|int|string $bottom = null,
        protected null|int|string $left = null,
    ) {
        $margins = ['top' => $top, 'right' => $right, 'bottom' => $bottom, 'left' => $left];
        
        foreach ($margins as $name => $margin) {
            if (is_string($margin) && !preg_match('/^\d+(\.\d+)?(mm)?$/', $margin)) {
                throw new ParametersException(sprintf(
                    'The %s margin "%s" must be in millimeters, e.g. "10" or "10mm".',
                    $name,
                    $margin
                ));
            }
        }
    }

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