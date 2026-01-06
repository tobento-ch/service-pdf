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

use Stringable;
use JsonSerializable;
use Tobento\Service\Pdf\ParameterInterface;

/**
 * Represents raw HTML content to be written into the PDF.
 */
class Html implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new instance.
     *
     * @param string|Stringable $html
     */
    public function __construct(
        protected string|Stringable $html
    ) {}

    /**
     * Returns the HTML.
     *
     * @return string
     */
    public function html(): string
    {
        return (string)$this->html;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['html' => $this->html()];
    }
}