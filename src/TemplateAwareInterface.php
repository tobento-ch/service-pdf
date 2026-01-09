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

namespace Tobento\Service\Pdf;

/**
 * Indicates that a parameter may contain a template
 * which can be rendered into HTML.
 */
interface TemplateAwareInterface
{
    /**
     * Returns the template if present, otherwise null.
     *
     * @return null|TemplateInterface
     */
    public function template(): null|TemplateInterface;

    /**
     * Returns a new parameter instance containing the rendered HTML.
     *
     * Implementations may return a different parameter type,
     * depending on how the rendered HTML should be represented.
     *
     * @param string $html The rendered HTML.
     * @return ParameterInterface A new parameter instance holding the rendered HTML.
     */
    public function withRenderedHtml(string $html): ParameterInterface;
}