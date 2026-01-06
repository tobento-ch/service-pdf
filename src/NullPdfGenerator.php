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

use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Tobento\Service\Pdf\Exception\PdfGenerationException;

class NullPdfGenerator implements PdfGeneratorInterface
{
    /**
     * Create a new instance.
     *
     * @param StreamFactoryInterface $streamFactory
     * @param string $name
     */
    public function __construct(
        protected StreamFactoryInterface $streamFactory,
        protected string $name = 'null',
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
        return '';
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
        return $this->streamFactory->createStream('');
    }
}