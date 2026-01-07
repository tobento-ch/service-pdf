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

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Tobento\Service\Filesystem\File;
use Tobento\Service\View\ViewInterface;

class ViewRenderer implements RendererInterface
{
    /**
     * Create a new instance.
     *
     * @param ViewInterface $view
     * @param string $colorMode 'light' or 'dark'
     */
    public function __construct(
        protected ViewInterface $view,
        protected string $colorMode = 'light',
    ) {}
    
    /**
     * Returns the evaluated content of the template rendered.
     *
     * @param TemplateInterface $template
     * @param bool $withInlineCssStyles
     * @return string
     */
    public function renderTemplate(TemplateInterface $template, bool $withInlineCssStyles = true): string
    {
        $data = $template->data();
        $data['withInlineCssStyles'] = $withInlineCssStyles;
        
        // Render view:
        $html = $this->view->render($template->name(), $data);
        
        if (!$withInlineCssStyles || empty($this->view->assets()->all())) {
            $this->view->assets()->clear();
            return $html;
        }
        
        // Collect all css content from view assets:
        $css = '';
        
        foreach($this->view->assets()->all() as $asset) {
            $file = new File($asset->getDir().$asset->getFile());
            
            if (!$file->isFile()) {
                continue;
            }
            
            if ($file->getExtension() !== 'css') {
                continue;
            }
            
            $css .= $file->getContent();
        }
        
        $this->view->assets()->clear();
        
        if ($css === '') {
            return $html;
        }
        
        if (str_contains($css, ':root')) {
            $css = $this->replaceRootVars($css);
        }
        
        $css = $this->replaceLightDark($css);
        
        // Convert css to inline styles:
        return new CssToInlineStyles()->convert(
            $html,
            $css
        );
    }
    
    /**
     * Returns the css with the replaced root vars.
     *
     * @param string $css
     * @return string
     */
    protected function replaceRootVars(string $css): string
    {
        $variables = $this->collectVariables($css);
        /*$variables = [
            '--font-primary' => 'Georgia, "Times New Roman", Times, serif',
        ];*/
        
        // replace variables:
        $callback = function (array $match) use ($variables): string {
            if (array_key_exists($match[1], $variables)) {
                return $variables[$match[1]];
            }
            
            return $match[0];
        };
		
        $css = preg_replace_callback('/var\((--[a-zA-Z0-9-_]+)(?:\)|,\s*(.*)\))/', $callback, $css);
        
        // replaces :root vars:
        $css = (string)preg_replace('/--[a-zA-Z0-9-_]+[:](.*)\;/', '', $css);
        
        return $css;
    }
    
    /**
     * Returns the collected css variables.
     *
     * @param string $css
     * @return array
     */
    protected function collectVariables(string $css): array
    {
        preg_match_all('/--[a-zA-Z0-9-_]+[:](.*)\;/', $css, $matches);
        
        $variables = [];
        
        foreach($matches[0] ?? [] as $match) {
            $data = explode(':', $match);
            $variables[$data[0]] = rtrim($data[1], ';');
        }

        return $variables;
    }
    
    /**
     * Replaces CSS `light-dark()` color function calls with a static color
     * depending on the current color mode.
     *
     * Example:
     *   light-dark(#ccc, #555) → #ccc   (light mode)
     *   light-dark(#ccc, #555) → #555   (dark mode)
     *
     * @param string $css The raw CSS content.
     * @return string The CSS with all light-dark() calls replaced.
     */
    protected function replaceLightDark(string $css): string
    {
        $replaced = preg_replace_callback(
            '/light-dark\(([^,]+),\s*([^)]+)\)/',
            function ($m) {
                return $this->colorMode === 'dark' ? trim($m[2]) : trim($m[1]);
            },
            $css
        );
        
        return $replaced ?: $css;
    }
}