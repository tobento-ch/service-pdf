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
 * Defines a text watermark.
 */
class Watermark implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new Watermark instance.
     *
     * @param string $text
     */
    public function __construct(
        protected string $text,
    ) {}

    /**
     * Returns the watermark text.
     *
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['text' => $this->text()];
    }
}