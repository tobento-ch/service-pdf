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

namespace Tobento\Service\Pdf\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Pdf\Template;
use Tobento\Service\Pdf\TemplateInterface;

class TemplateTest extends TestCase
{
    public function testImplementsInterface()
    {
        $template = new Template('invoice', ['id' => 123]);

        $this->assertInstanceOf(TemplateInterface::class, $template);
    }

    public function testNameReturnsName()
    {
        $template = new Template('invoice', ['id' => 123]);

        $this->assertSame('invoice', $template->name());
    }

    public function testDataReturnsData()
    {
        $data = ['id' => 123, 'customer' => 'John Doe'];

        $template = new Template('invoice', $data);

        $this->assertSame($data, $template->data());
    }

    public function testTemplateStoresEmptyData()
    {
        $template = new Template('empty', []);

        $this->assertSame([], $template->data());
    }
}