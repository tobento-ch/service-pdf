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
use Tobento\Service\Pdf\Parameter\DocumentInfo;
use Tobento\Service\Pdf\ParameterInterface;

class DocumentInfoTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $info = new DocumentInfo();

        $this->assertInstanceOf(ParameterInterface::class, $info);
        $this->assertInstanceOf(JsonSerializable::class, $info);
    }

    public function testValuesAreReturned()
    {
        $info = new DocumentInfo(
            title: 'My Title',
            author: 'John Doe',
            subject: 'Testing PDF',
            keywords: ['php', 'pdf', 'test']
        );

        $this->assertSame('My Title', $info->title());
        $this->assertSame('John Doe', $info->author());
        $this->assertSame('Testing PDF', $info->subject());
        $this->assertSame(['php', 'pdf', 'test'], $info->keywords());
    }

    public function testJsonSerialize()
    {
        $info = new DocumentInfo(
            title: 'Doc',
            author: 'Alice',
            subject: 'Unit Test',
            keywords: ['a', 'b']
        );

        $this->assertSame(
            [
                'title' => 'Doc',
                'author' => 'Alice',
                'subject' => 'Unit Test',
                'keywords' => ['a', 'b'],
            ],
            $info->jsonSerialize()
        );
    }

    public function testAllowsNullValues()
    {
        $info = new DocumentInfo();

        $this->assertNull($info->title());
        $this->assertNull($info->author());
        $this->assertNull($info->subject());
        $this->assertSame([], $info->keywords());
    }
}