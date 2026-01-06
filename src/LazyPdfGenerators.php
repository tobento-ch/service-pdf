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

use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamInterface;
use Tobento\Service\Autowire\Autowire;
use Tobento\Service\Pdf\Exception\PdfGenerationException;
use Tobento\Service\Pdf\Exception\PdfGeneratorNotFoundException;

class LazyPdfGenerators implements PdfGeneratorsInterface, PdfGeneratorInterface
{
    /**
     * @var Autowire
     */
    protected Autowire $autowire;
    
    /**
     * @var array<string, PdfGeneratorInterface>
     */
    protected array $createdGenerators = [];
    
    /**
     * Create a new instance.
     *
     * @param ContainerInterface $container
     * @param array $generators
     */
    public function __construct(
        ContainerInterface $container,
        protected array $generators,
    ) {
        $this->autowire = new Autowire($container);
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
        // Already created?
        if (isset($this->createdGenerators[$name])) {
            return $this->createdGenerators[$name];
        }
        
        // Not registered?
        if (!isset($this->generators[$name])) {
            throw new PdfGeneratorNotFoundException(sprintf('PDF generator "%s" not found.', $name));
        }
        
        $definition = $this->generators[$name];

        // Direct instance
        if ($definition instanceof PdfGeneratorInterface) {
            return $this->createdGenerators[$name] = $definition;
        }

        // Callable factory
        if (is_callable($definition)) {
            return $this->createdGenerators[$name] = $this->autowire->call(
                $definition,
                ['name' => $name]
            );
        }

        // Array definition with factory
        if (!isset($definition['factory'])) {
            throw new PdfGeneratorNotFoundException(
                sprintf('Missing "factory" for PDF generator "%s".', $name)
            );
        }

        $factory = $this->autowire->resolve($definition['factory']);

        if (!$factory instanceof PdfGeneratorFactoryInterface) {
            throw new PdfGeneratorNotFoundException(
                sprintf('Invalid factory for PDF generator "%s".', $name)
            );
        }

        $config = $definition['config'] ?? [];

        return $this->createdGenerators[$name] = $factory->createGenerator($name, $config);
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