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

namespace Tobento\Service\Pdf\Test\Exception;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Pdf\Exception\PdfGeneratorNotFoundException;
use Tobento\Service\Pdf\Exception\PdfException;

class PdfGeneratorNotFoundExceptionTest extends TestCase
{
    public function testException()
    {
        $previous = new \RuntimeException();
        $e = new PdfGeneratorNotFoundException(message: 'msg', code: 1, previous: $previous);
        
        $this->assertInstanceof(PdfException::class, $e);
        $this->assertSame('msg', $e->getMessage());
        $this->assertSame(1, $e->getCode());
        $this->assertSame($previous, $e->getPrevious());
    }
}