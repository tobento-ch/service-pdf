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

namespace Tobento\Service\Pdf\Mpdf;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Stringable;
use Throwable;
use Tobento\Service\Pdf\Exception\PdfGenerationException;
use Tobento\Service\Pdf\Parameter;
use Tobento\Service\Pdf\ParameterInterface;
use Tobento\Service\Pdf\PdfGeneratorInterface;
use Tobento\Service\Pdf\PdfInterface;
use Tobento\Service\Pdf\RendererInterface;
use Tobento\Service\Pdf\TemplateInterface;

class PdfGenerator implements PdfGeneratorInterface
{
    /**
     * Create a new instance.
     *
     * @param RendererInterface $renderer
     * @param StreamFactoryInterface $streamFactory
     * @param array $config
     * @param string $name The generator name.
     */
    public function __construct(
        protected RendererInterface $renderer,
        protected StreamFactoryInterface $streamFactory,
        protected array $config = [],
        protected string $name = 'mpdf',
    ) {}
    
    /**
     * Returns the generator name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Generates the PDF and returns the raw binary string.
     *
     * @param PdfInterface $pdf
     * @return string
     * @throws PdfGenerationException If PDF generation fails.
     */
    public function generate(PdfInterface $pdf): string
    {
        try {
            $config = $this->applyConfigParameters($pdf, $this->config);
            
            $mpdf = new Mpdf($config);

            $this->applyParameters($pdf, $mpdf);

            return $mpdf->Output('', Destination::STRING_RETURN);
        } catch (Throwable $e) {
            throw new PdfGenerationException(
                'Failed to generate PDF: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Generates the PDF and returns it as a PSR-7 stream.
     *
     * @param PdfInterface $pdf
     * @return StreamInterface
     * @throws PdfGenerationException If PDF generation fails.
     */
    public function stream(PdfInterface $pdf): StreamInterface
    {
        try {
            $binary = $this->generate($pdf);
            return $this->streamFactory->createStream($binary);
        } catch (Throwable $e) {
            throw new PdfGenerationException(
                'Failed to generate PDF stream: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
    
    /**
     * Apply parameters that must be set in the mPDF config array.
     */
    protected function applyConfigParameters(PdfInterface $pdf, array $config): array
    {
        foreach($pdf->parameters() as $parameter) {
            $config = $this->applyConfigParameter($parameter, $config);
        }
        
        return $config;
    }

    /**
     * Applies a single parameter to config.
     */
    protected function applyConfigParameter(ParameterInterface $parameter, array $config): array
    {
        switch (true) {
            case $parameter instanceof Parameter\Paper:
                $config['format'] = $parameter->paper()->value;
                break;

            case $parameter instanceof Parameter\Orientation:
                $config['orientation'] = $parameter->orientation()->value === 'landscape' ? 'L' : 'P';
                break;

            case $parameter instanceof Parameter\Margins:
                if (!is_null($parameter->left())) {
                    $config['margin_left'] = $this->normalizeMargin($parameter->left());
                }
                if (!is_null($parameter->right())) {
                    $config['margin_right'] = $this->normalizeMargin($parameter->right());
                }
                if (!is_null($parameter->top())) {
                    $config['margin_top'] = $this->normalizeMargin($parameter->top());
                }
                if (!is_null($parameter->bottom())) {
                    $config['margin_bottom'] = $this->normalizeMargin($parameter->bottom());
                }
                break;

            case $parameter instanceof Parameter\PageSize:
                $config['format'] = [$parameter->width(), $parameter->height()];
                break;

            case $parameter instanceof Parameter\Dpi:
                $config['dpi'] = $parameter->dpi();
                break;

            default:
                break;
        }

        return $config;
    }
    
    /**
     * Applies all parameters to mPDF.
     */
    protected function applyParameters(PdfInterface $pdf, Mpdf $mpdf): void
    {
        foreach($pdf->parameters() as $parameter) {
            $this->applyParameter($parameter, $mpdf);
        }
    }
    
    /**
     * Applies a single parameter to mPDF.
     */
    protected function applyParameter(ParameterInterface $parameter, Mpdf $mpdf): void
    {
        switch (true) {
            case $parameter instanceof Parameter\Compression:
                $mpdf->SetCompression($parameter->level() > 0);
                break;

            case $parameter instanceof Parameter\Header:
                $content = $this->resolveContent($parameter->html());
                $mpdf->SetHTMLHeader($content);
                break;

            case $parameter instanceof Parameter\Footer:
                $content = $this->resolveContent($parameter->html());
                $mpdf->SetHTMLFooter($content);
                break;

            case $parameter instanceof Parameter\Pagination:
                $format = $this->translatePaginationFormat($parameter->format());
                $mpdf->SetFooter($format);
                break;

            case $parameter instanceof Parameter\Watermark:
                $mpdf->SetWatermarkText($parameter->text());
                $mpdf->showWatermarkText = true;
                break;

            case $parameter instanceof Parameter\Password:
                $mpdf->SetProtection([], $parameter->password());
                break;

            case $parameter instanceof Parameter\DocumentInfo:
                if ($parameter->title()) {
                    $mpdf->SetTitle($parameter->title());
                }
                if ($parameter->author()) {
                    $mpdf->SetAuthor($parameter->author());
                }
                if ($parameter->subject()) {
                    $mpdf->SetSubject($parameter->subject());
                }
                if ($parameter->keywords()) {
                    $mpdf->SetKeywords(implode(', ', $parameter->keywords()));
                }
                break;

            case $parameter instanceof Parameter\Template:
                $mpdf->WriteHTML($this->renderer->renderTemplate($parameter->template()));
                break;
                
            case $parameter instanceof Parameter\Html:
                $mpdf->WriteHTML($parameter->html());
                break;
                
            case $parameter instanceof Parameter\Text:
                $mpdf->WriteHTML($parameter->text());
                break;
                
            case $parameter instanceof Parameter\PageBreak:
                $mpdf->AddPage();
                break;

            default:
                break;
        }
    }
    
    /**
     * Resolves content.
     */
    protected function resolveContent(string|Stringable|TemplateInterface $content): string
    {
        if ($content instanceof TemplateInterface) {
            return $this->renderer->renderTemplate($content);
        }
        
        return (string)$content;
    }
    
    /**
     * Normalize a margin value into a numeric float for mPDF.
     */
    protected function normalizeMargin(int|string|null $value): float
    {
        if ($value === null) {
            return 0.0; // or your default
        }

        if (is_int($value)) {
            return (float) $value;
        }

        // Remove units like mm, cm, in
        $numeric = preg_replace('/[^\d.]/', '', $value);

        return (float) $numeric;
    }
    
    /**
     * Translate pagination format.
     */    
    protected function translatePaginationFormat(string $format): string
    {
        return str_replace(
            ['{PAGE}', '{PAGES}'],
            ['{PAGENO}', '{nbpg}'],
            $format
        );
    }
}