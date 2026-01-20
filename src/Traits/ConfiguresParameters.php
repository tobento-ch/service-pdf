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

namespace Tobento\Service\Pdf\Traits;

use Tobento\Service\Pdf\Enums\Paper;
use Tobento\Service\Pdf\Enums\Orientation;
use Tobento\Service\Pdf\Parameter;
use Tobento\Service\Pdf\Template;
use Tobento\Service\Pdf\TemplateInterface;

trait ConfiguresParameters
{
    /**
     * Set PDF name.
     */
    public function name(string $name): static
    {
        $this->parameters()->add(new Parameter\Name(name: $name));
        return $this;
    }
    
    /**
     * Set paper size.
     */
    public function paper(Paper $paper): static
    {
        $this->parameters()->add(new Parameter\Paper(paper: $paper));
        return $this;
    }

    /**
     * Set orientation.
     */
    public function orientation(Orientation $orientation): static
    {
        $this->parameters()->add(new Parameter\Orientation(orientation: $orientation));
        return $this;
    }

    /**
     * Set margins.
     */
    public function margins(string $top, string $right, string $bottom, string $left): static
    {
        $this->parameters()->add(
            new Parameter\Margins(
                top: $top,
                right: $right,
                bottom: $bottom,
                left: $left
            )
        );
        return $this;
    }

    /**
     * Set custom page size.
     */
    public function pageSize(float $width, float $height): static
    {
        $this->parameters()->add(
            new Parameter\PageSize(width: $width, height: $height)
        );
        return $this;
    }

    /**
     * Set DPI.
     */
    public function dpi(int $dpi): static
    {
        $this->parameters()->add(new Parameter\Dpi(dpi: $dpi));
        return $this;
    }

    /**
     * Set compression level.
     */
    public function compression(int $level): static
    {
        $this->parameters()->add(new Parameter\Compression(level: $level));
        return $this;
    }

    /**
     * Set header html.
     */
    public function header(string|\Stringable|TemplateInterface $html): static
    {
        $this->parameters()->add(new Parameter\Header(html: $html));
        return $this;
    }

    /**
     * Set footer html.
     */
    public function footer(string|\Stringable|TemplateInterface $html): static
    {
        $this->parameters()->add(new Parameter\Footer(html: $html));
        return $this;
    }

    /**
     * Set pagination format.
     */
    public function pagination(string $format = '{PAGE}/{PAGES}'): static
    {
        $this->parameters()->add(new Parameter\Pagination(format: $format));
        return $this;
    }

    /**
     * Set watermark text.
     */
    public function watermark(string $text): static
    {
        $this->parameters()->add(new Parameter\Watermark(text: $text));
        return $this;
    }

    /**
     * Set PDF password.
     */
    public function password(string $password): static
    {
        $this->parameters()->add(new Parameter\Password(password: $password));
        return $this;
    }

    /**
     * Set document metadata.
     */
    public function documentInfo(
        ?string $title = null,
        ?string $author = null,
        ?string $subject = null,
        array $keywords = []
    ): static {
        $this->parameters()->add(
            new Parameter\DocumentInfo(
                title: $title,
                author: $author,
                subject: $subject,
                keywords: $keywords
            )
        );
        return $this;
    }

    /**
     * Add HTML content.
     */
    public function html(string $html): static
    {
        $this->parameters()->add(new Parameter\Html(html: $html));
        return $this;
    }

    /**
     * Add plain text content.
     */
    public function text(string $text): static
    {
        $this->parameters()->add(new Parameter\Text(text: $text));
        return $this;
    }

    /**
     * Add a template.
     */
    public function template(string $name, array $data = []): static
    {
        $this->parameters()->add(
            new Parameter\Template(
                new Template(name: $name, data: $data)
            )
        );
        return $this;
    }

    /**
     * Insert a page break.
     */
    public function pageBreak(): static
    {
        $this->parameters()->add(new Parameter\PageBreak());
        return $this;
    }
}