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

use Tobento\Service\Pdf\Exception\PdfGeneratorCreationException;

/**
 * Factory interface for creating PDF generator instances.
 *
 * Implementations of this interface are responsible for constructing
 * and configuring a PdfGeneratorInterface based on the provided name
 * and configuration array. This allows different PDF generators or
 * differently configured instances to be created through a single
 * factory implementation.
 */
interface PdfGeneratorFactoryInterface
{
    /**
     * Create and return a new PDF generator instance.
     *
     * @param string $name The generator name, used for identification.
     * @param array $config Configuration values for the generator.
     * @return PdfGeneratorInterface
     * @throws PdfGeneratorCreationException If the generator cannot be created.
     */
    public function createGenerator(string $name, array $config = []): PdfGeneratorInterface;
}