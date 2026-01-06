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

use Psr\Http\Message\StreamInterface;
use Tobento\Service\Pdf\Exception\PdfGenerationException;
use Tobento\Service\Pdf\Exception\PdfGeneratorNotFoundException;

class PdfGenerators implements PdfGeneratorsInterface, PdfGeneratorInterface
{
    /**
     * @var array<string, PdfGeneratorInterface>
     */
    protected array $generators = [];
    
    /**
     * Create a new instance.
     *
     * @param PdfGeneratorInterface ...$generators
     */
    public function __construct(
        PdfGeneratorInterface ...$generators,
    ) {
        foreach($generators as $generator) {
            $this->generators[$generator->name()] = $generator;
        }
    }
    
    /**
     * Returns the PDF generator registered under the given name.
     *
     * @param string $name The generator name.
     * @return PdfGeneratorInterface
     * @throws PdfGeneratorNotFoundException If no generator is registered with the given name.
     */
    public function get(string $name): PdfGeneratorInterface
    {
        // Not registered?
        if (!isset($this->generators[$name])) {
            throw new PdfGeneratorNotFoundException(sprintf('PDF generator "%s" not found.', $name));
        }
        
        return $this->generators[$name];
    }
    
    /**
     * Checks whether a PDF generator with the given name exists.
     *
     * @param string $name The generator name.
     * @return bool True if the generator exists, otherwise false.
     */
    public function has(string $name): bool
    {
        return isset($this->generators[$name]);
    }
    
    /**
     * Returns all registered generator names.
     *
     * @return array<int, string> A list of generator names.
     */
    public function names(): array
    {
        return array_keys($this->generators);
    }
    
    /**
     * Returns the generator name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->getFirstGenerator()->name();
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
        return $this->getFirstGenerator()->generate(pdf: $pdf);
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
        return $this->getFirstGenerator()->stream(pdf: $pdf);
    }

    /**
     * Returns the first generator.
     *
     * @return PdfGeneratorInterface
     */
    protected function getFirstGenerator(): PdfGeneratorInterface
    {
        $firstKey = array_key_first($this->generators);

        if ($firstKey === null) {
            throw new PdfGeneratorNotFoundException('No PDF generators registered.');
        }
        
        return $this->get($firstKey);
    }
}