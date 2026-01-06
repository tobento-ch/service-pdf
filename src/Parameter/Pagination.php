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
 * Defines pagination settings for the PDF.
 */
class Pagination implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new Pagination instance.
     *
     * @param string $format
     */
    public function __construct(
        protected string $format = '{PAGE}/{PAGES}',
    ) {}

    /**
     * Returns the pagination format.
     *
     * @return string
     */
    public function format(): string
    {
        return $this->format;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['format' => $this->format()];
    }
}