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

namespace Tobento\Service\Pdf\Test\Helper;

use Tobento\Service\Pdf\RendererInterface;
use Tobento\Service\Pdf\TemplateInterface;

class FakeRenderer implements RendererInterface
{
    public function renderTemplate(TemplateInterface $template): string
    {
        return 'fake:' . $template->name();
    }
}