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

use Psr\Http\Message\StreamFactoryInterface;
use Tobento\Service\Pdf\Exception\PdfGeneratorCreationException;
use Tobento\Service\Pdf\PdfGeneratorFactoryInterface;
use Tobento\Service\Pdf\PdfGeneratorInterface;
use Tobento\Service\Pdf\RendererInterface;

class PdfGeneratorFactory implements PdfGeneratorFactoryInterface
{
    /**
     * Create a new instance.
     */
    public function __construct(
        protected RendererInterface $renderer,
        protected StreamFactoryInterface $streamFactory,
    ) {}

    /**
     * Create and return a new PDF generator instance.
     *
     * @param string $name The generator name, used for identification.
     * @param array $config Configuration values for the generator.
     * @return PdfGeneratorInterface
     * @throws PdfGeneratorCreationException If the generator cannot be created.
     */
    public function createGenerator(string $name, array $config = []): PdfGeneratorInterface
    {
        return new PdfGenerator(
            renderer: $this->renderer,
            streamFactory: $this->streamFactory,
            config: $config,
            name: $name,
        );
    }
}