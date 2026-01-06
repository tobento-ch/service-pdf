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

use Tobento\Service\View\ViewInterface;

class ViewRenderer implements RendererInterface
{
    /**
     * Create a new instance.
     *
     * @param ViewInterface $view
     */
    public function __construct(
        protected ViewInterface $view,
    ) {}
    
    /**
     * Returns the evaluated content of the template rendered.
     *
     * @param TemplateInterface $template
     * @return string
     */
    public function renderTemplate(TemplateInterface $template): string
    {
        return $this->view->render(
            view: $template->name(),
            data: $template->data(),
        );
    }
}