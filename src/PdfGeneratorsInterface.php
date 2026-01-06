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
use Tobento\Service\Pdf\Exception\PdfGeneratorNotFoundException;

interface PdfGeneratorsInterface
{
    /**
     * Returns the PDF generator registered under the given name.
     *
     * @param string $name The generator name.
     * @return PdfGeneratorInterface
     * @throws PdfGeneratorNotFoundException If no generator is registered with the given name.
     */
    public function get(string $name): PdfGeneratorInterface;

    /**
     * Checks whether a PDF generator with the given name exists.
     *
     * @param string $name The generator name.
     * @return bool True if the generator exists, otherwise false.
     */
    public function has(string $name): bool;

    /**
     * Returns all registered generator names.
     *
     * @return array<int, string> A list of generator names.
     */
    public function names(): array;
}