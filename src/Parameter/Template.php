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
use Tobento\Service\Pdf\Template as Templ;
use Tobento\Service\Pdf\TemplateInterface;

/**
 * Represents a template that will be rendered into HTML.
 */
class Template implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new instance.
     *
     * @param TemplateInterface $template
     */
    public function __construct(
        protected TemplateInterface $template
    ) {}

    /**
     * Returns the template.
     *
     * @return TemplateInterface
     */
    public function template(): TemplateInterface
    {
        return $this->template;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->template()->name(),
            'data' => $this->template()->data(),
        ];
    }
    
    /**
     * Reconstructs the parameter instance from a serialized array.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            template: new Templ(
                name: $data['name'] ?? '',
                data: $data['data'] ?? [],
            ),
        );
    }
}