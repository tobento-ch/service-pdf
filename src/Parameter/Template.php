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
use Tobento\Service\Pdf\TemplateAwareInterface;
use Tobento\Service\Pdf\TemplateInterface;

/**
 * Represents a template that will be rendered into HTML.
 */
class Template implements ParameterInterface, JsonSerializable, TemplateAwareInterface
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
    public function getTemplate(): TemplateInterface
    {
        return $this->template;
    }
    
    /**
     * Returns the template if present, otherwise null.
     *
     * @return null|TemplateInterface
     */
    public function template(): null|TemplateInterface
    {
        return $this->getTemplate();
    }

    /**
     * Returns a new parameter instance containing the rendered HTML.
     *
     * Implementations may return a different parameter type,
     * depending on how the rendered HTML should be represented.
     *
     * @param string $html The rendered HTML.
     * @return ParameterInterface A new parameter instance holding the rendered HTML.
     */
    public function withRenderedHtml(string $html): ParameterInterface
    {
        return new Html(html: $html);
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getTemplate()->name(),
            'data' => $this->getTemplate()->data(),
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