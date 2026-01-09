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
use Tobento\Service\Pdf\Template;
use Tobento\Service\Pdf\TemplateAwareInterface;
use Tobento\Service\Pdf\TemplateInterface;

/**
 * Defines the PDF header html.
 */
class Header implements ParameterInterface, JsonSerializable, TemplateAwareInterface
{
    /**
     * Create a new Header instance.
     *
     * @param string|Stringable|TemplateInterface $html
     */
    public function __construct(
        protected string|Stringable|TemplateInterface $html,
    ) {}

    /**
     * Returns the header html.
     *
     * @return string|Stringable|TemplateInterface
     */
    public function html(): string|Stringable|TemplateInterface
    {
        return $this->html;
    }
    
    /**
     * Returns the template if present, otherwise null.
     *
     * @return null|TemplateInterface
     */
    public function template(): null|TemplateInterface
    {
        $html = $this->html();
        
        if ($html instanceof TemplateInterface) {
            return $html;
        }
        
        return null;
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
        return new static(html: $html);
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        if ($this->html() instanceof TemplateInterface) {
            return [
                'name' => $this->html()->name(),
                'data' => $this->html()->data(),
            ];
        }

        return [
            'html' => (string)$this->html(),
        ];
    }

    /**
     * Reconstructs the parameter instance from an array.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        if (array_key_exists('html', $data)) {
            return new static($data['html']);
        }

        return new static(
            new Template(
                name: $data['name'] ?? '',
                data: $data['data'] ?? [],
            )
        );
    }
}