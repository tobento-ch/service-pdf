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

namespace Tobento\Service\Pdf\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Dir\Dir;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Pdf\Template;
use Tobento\Service\Pdf\ViewRenderer;
use Tobento\Service\View\Data;
use Tobento\Service\View\PhpRenderer;
use Tobento\Service\View\View;
use Tobento\Service\View\Assets;

class ViewRendererTest extends TestCase
{
    protected string $viewsPath = __DIR__ . '/tmp/views/';

    protected function setUp(): void
    {
        // Ensure directory exists
        if (!is_dir($this->viewsPath)) {
            mkdir($this->viewsPath, 0777, true);
        }

        // Create a real PHP template file
        file_put_contents(
            $this->viewsPath . 'invoice.php',
            '<p>Invoice ID: <?= $id ?></p>'
        );
    }

    protected function tearDown(): void
    {
        // Clean up test directory
        if (is_dir($this->viewsPath)) {
            array_map('unlink', glob($this->viewsPath . '*'));
            rmdir($this->viewsPath);
        }
    }

    public function testRenderTemplateWithRealView()
    {
        $view = new View(
            new PhpRenderer(
                new Dirs(
                    new Dir($this->viewsPath)
                )
            ),
            new Data(),
            new Assets('src/', 'https://example.com/src/')
        );

        $renderer = new ViewRenderer($view);

        $template = new Template('invoice', ['id' => 123]);

        $output = $renderer->renderTemplate($template);

        $this->assertSame('<p>Invoice ID: 123</p>', $output);
    }
}