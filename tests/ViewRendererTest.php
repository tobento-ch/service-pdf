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
use Tobento\Service\Filesystem\File;

class ViewRendererTest extends TestCase
{
    protected string $viewsPath = __DIR__ . '/tmp/views/';
    protected string $assetsPath = __DIR__ . '/tmp/assets/';

    protected function setUp(): void
    {
        // Ensure directories exist
        if (!is_dir($this->viewsPath)) {
            mkdir($this->viewsPath, 0777, true);
        }
        if (!is_dir($this->assetsPath)) {
            mkdir($this->assetsPath, 0777, true);
        }

        // Create a simple PHP template
        file_put_contents(
            $this->viewsPath . 'invoice.php',
            '<p class="border">Invoice ID: <?= $id ?></p>'
        );

        // Create a CSS file
        file_put_contents(
            $this->assetsPath . 'style.css',
            <<<CSS
:root {
    --border-color: #ccc;
}
.border {
    border-bottom: 1px solid var(--border-color);
    color: light-dark(#000, #fff);
}
CSS
        );
    }

    protected function tearDown(): void
    {
        // Clean up test directories
        foreach (glob($this->viewsPath . '*') as $file) {
            unlink($file);
        }
        foreach (glob($this->assetsPath . '*') as $file) {
            unlink($file);
        }
        @rmdir($this->viewsPath);
        @rmdir($this->assetsPath);
    }

    public function testRenderTemplateBasic()
    {
        $view = new View(
            new PhpRenderer(new Dirs(new Dir($this->viewsPath))),
            new Data(),
            new Assets($this->assetsPath, $this->assetsPath)
        );

        $renderer = new ViewRenderer($view);

        $template = new Template('invoice', ['id' => 123]);

        $output = $renderer->renderTemplate($template);

        $this->assertStringContainsString('Invoice ID: 123', $output);
    }

    public function testInlineCssIsApplied()
    {
        $view = new View(
            new PhpRenderer(new Dirs(new Dir($this->viewsPath))),
            new Data(),
            new Assets($this->assetsPath, $this->assetsPath)
        );

        // Add CSS asset
        $view->assets()->asset('style.css');

        $renderer = new ViewRenderer($view);

        $template = new Template('invoice', ['id' => 123]);

        $output = $renderer->renderTemplate($template);

        // CSS should be inlined
        $this->assertStringContainsString('border-bottom: 1px solid #ccc', $output);
    }

    public function testLightDarkReplacedInLightMode()
    {
        $view = new View(
            new PhpRenderer(new Dirs(new Dir($this->viewsPath))),
            new Data(),
            new Assets($this->assetsPath, $this->assetsPath)
        );

        $view->assets()->asset('style.css');

        $renderer = new ViewRenderer($view, colorMode: 'light');

        $template = new Template('invoice', ['id' => 123]);

        $output = $renderer->renderTemplate($template);

        $this->assertStringContainsString('color: #000', $output);
    }

    public function testLightDarkReplacedInDarkMode()
    {
        $view = new View(
            new PhpRenderer(new Dirs(new Dir($this->viewsPath))),
            new Data(),
            new Assets($this->assetsPath, $this->assetsPath)
        );

        $view->assets()->asset('style.css');

        $renderer = new ViewRenderer($view, colorMode: 'dark');

        $template = new Template('invoice', ['id' => 123]);

        $output = $renderer->renderTemplate($template);

        $this->assertStringContainsString('color: #fff', $output);
    }

    public function testInlineCssDisabled()
    {
        $view = new View(
            new PhpRenderer(new Dirs(new Dir($this->viewsPath))),
            new Data(),
            new Assets($this->assetsPath, $this->assetsPath)
        );

        $view->assets()->asset('style.css');

        $renderer = new ViewRenderer($view);

        $template = new Template('invoice', ['id' => 123]);

        $output = $renderer->renderTemplate($template, withInlineCssStyles: false);

        // Should NOT inline CSS
        $this->assertStringNotContainsString('border-bottom:', $output);
    }
}