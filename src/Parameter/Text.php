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
use Stringable;
use Tobento\Service\Pdf\ParameterInterface;

/**
 * Represents plain text content to be written into the PDF.
 */
class Text implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new instance.
     *
     * @param string|Stringable $text
     */
    public function __construct(
        protected string|Stringable $text
    ) {}

    /**
     * Returns the text.
     *
     * @return string
     */
    public function text(): string
    {
        return (string)$this->text;
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