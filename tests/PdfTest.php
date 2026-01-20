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
use Tobento\Service\Pdf\Enums\Orientation;
use Tobento\Service\Pdf\Enums\Paper;
use Tobento\Service\Pdf\Parameter;
use Tobento\Service\Pdf\Parameter\Parameters;
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\PdfInterface;
use Tobento\Service\Pdf\Template;

class PdfTest extends TestCase
{
    public function testPdfImplementsInterface()
    {
        $pdf = new Pdf();

        $this->assertInstanceOf(PdfInterface::class, $pdf);
    }

    public function testNameMethods()
    {
        $pdf = new Pdf();

        // Default name should fall back to the class name
        $this->assertSame(Pdf::class, $pdf->getName());

        // Setting a custom name should override the default
        $pdf->name('invoice');
        $this->assertSame('invoice', $pdf->getName());

        // Ensure fluent interface returns the same instance
        $returned = $pdf->name('report');
        $this->assertSame($pdf, $returned);
        $this->assertSame('report', $pdf->getName());
    }

    public function testParametersAreLazyInitialized()
    {
        $pdf = new Pdf();

        $this->assertInstanceOf(Parameters::class, $pdf->parameters());
        $this->assertSame($pdf->parameters(), $pdf->parameters());
    }

    public function testWithParametersClonesInstance()
    {
        $pdf = new Pdf();
        $params = new Parameters();

        $new = $pdf->withParameters($params);

        $this->assertNotSame($pdf, $new);
        $this->assertSame($params, $new->parameters());
    }

    public function testPaperAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->paper(Paper::A4);

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Paper::class, $param);
        $this->assertSame(Paper::A4, $param->paper());
    }

    public function testOrientationAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->orientation(Orientation::LANDSCAPE);

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Orientation::class, $param);
        $this->assertSame(Orientation::LANDSCAPE, $param->orientation());
    }

    public function testMarginsAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->margins('10mm', '15mm', '20mm', '25mm');

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Margins::class, $param);
        $this->assertSame('10mm', $param->top());
        $this->assertSame('15mm', $param->right());
        $this->assertSame('20mm', $param->bottom());
        $this->assertSame('25mm', $param->left());
    }

    public function testPageSizeAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->pageSize(210.0, 297.0);

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\PageSize::class, $param);
        $this->assertSame(210.0, $param->width());
        $this->assertSame(297.0, $param->height());
    }

    public function testDpiAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->dpi(300);

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Dpi::class, $param);
        $this->assertSame(300, $param->dpi());
    }

    public function testCompressionAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->compression(5);

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Compression::class, $param);
        $this->assertSame(5, $param->level());
    }

    public function testHeaderAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->header('<h1>Header</h1>');

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Header::class, $param);
        $this->assertSame('<h1>Header</h1>', $param->html());
    }

    public function testFooterAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->footer('<p>Footer</p>');

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Footer::class, $param);
        $this->assertSame('<p>Footer</p>', $param->html());
    }

    public function testPaginationAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->pagination('{PAGE} of {PAGES}');

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Pagination::class, $param);
        $this->assertSame('{PAGE} of {PAGES}', $param->format());
    }

    public function testWatermarkAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->watermark('CONFIDENTIAL');

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Watermark::class, $param);
        $this->assertSame('CONFIDENTIAL', $param->text());
    }

    public function testPasswordAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->password('secret');

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Password::class, $param);
        $this->assertSame('secret', $param->password());
    }

    public function testDocumentInfoAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->documentInfo(
            title: 'My Doc',
            author: 'John',
            subject: 'Testing',
            keywords: ['php', 'pdf']
        );

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\DocumentInfo::class, $param);
        $this->assertSame('My Doc', $param->title());
        $this->assertSame('John', $param->author());
        $this->assertSame('Testing', $param->subject());
        $this->assertSame(['php', 'pdf'], $param->keywords());
    }

    public function testHtmlAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->html('<p>Hello</p>');

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Html::class, $param);
        $this->assertSame('<p>Hello</p>', $param->html());
    }

    public function testTextAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->text('Hello World');

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Text::class, $param);
        $this->assertSame('Hello World', $param->text());
    }

    public function testTemplateAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->template('invoice', ['id' => 123]);

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\Template::class, $param);
        $this->assertInstanceOf(Template::class, $param->template());
        $this->assertSame('invoice', $param->template()->name());
        $this->assertSame(['id' => 123], $param->template()->data());
    }

    public function testPageBreakAddsParameter()
    {
        $pdf = new Pdf();

        $pdf->pageBreak();

        $param = $pdf->parameters()->all()[0];

        $this->assertInstanceOf(Parameter\PageBreak::class, $param);
    }
}