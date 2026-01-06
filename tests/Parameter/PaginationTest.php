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

namespace Tobento\Service\Pdf\Test\Parameter;

use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Tobento\Service\Pdf\Parameter\Pagination;
use Tobento\Service\Pdf\ParameterInterface;

class PaginationTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $pagination = new Pagination();

        $this->assertInstanceOf(ParameterInterface::class, $pagination);
        $this->assertInstanceOf(JsonSerializable::class, $pagination);
    }

    public function testDefaultFormatIsReturned()
    {
        $pagination = new Pagination();

        $this->assertSame('{PAGE}/{PAGES}', $pagination->format());
    }

    public function testCustomFormatIsReturned()
    {
        $pagination = new Pagination('Page {PAGE} of {PAGES}');

        $this->assertSame('Page {PAGE} of {PAGES}', $pagination->format());
    }

    public function testJsonSerialize()
    {
        $pagination = new Pagination('Pg {PAGE}');

        $this->assertSame(
            ['format' => 'Pg {PAGE}'],
            $pagination->jsonSerialize()
        );
    }
}