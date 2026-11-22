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

namespace Tobento\Service\Pdf\Test\Mpdf;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Tobento\Service\Pdf\Mpdf\PdfGenerator;
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\RendererInterface;
use Tobento\Service\Pdf\Template;
use Tobento\Service\Pdf\Exception\PdfGenerationException;
use Tobento\Service\Pdf\Parameter;
use Tobento\Service\Pdf\Enums\Paper;
use Tobento\Service\Pdf\Enums\Orientation;
use Tobento\Service\Pdf\Test\Helper\FakeRenderer;

/**
 * Fake mPDF class to capture calls.
 */
class FakeMpdf
{
    public static ?FakeMpdf $lastInstance = null;

    public array $calls = [];
    public string $output = 'PDF_BINARY';
    public bool $showWatermarkText = false;
    public mixed $HTMLFooter = '';
    public mixed $HTMLFooterE = '';

    public function __construct(array $config = [])
    {
        self::$lastInstance = $this;
        $this->calls[] = ['__construct', $config];

        // Force failure for exception test
        if (isset($config['invalid'])) {
            throw new \Exception("Invalid config");
        }
    }

    public function Output($name = '', $dest = '')
    {
        $this->calls[] = ['Output', $name, $dest];
        return $this->output;
    }

    public function SetHTMLHeader($html)
    {
        $this->calls[] = ['SetHTMLHeader', $html];
    }

    public function SetHTMLFooter($html, $OE = '')
    {
        $this->calls[] = ['SetHTMLFooter', $html, $OE];
    }

    public function SetFooter($html)
    {
        $this->calls[] = ['SetFooter', $html];
        
        // like mPDF, creates the html of the default footer:
        $this->HTMLFooter = ['html' => '<div>'.$html.'</div>'];
        $this->HTMLFooterE = ['html' => '<div>'.$html.'</div>'];
    }

    public function SetCompression($flag)
    {
        $this->calls[] = ['SetCompression', $flag];
    }
    
    public function SetWatermarkText($text)
    {
        $this->calls[] = ['SetWatermarkText', $text];
    }

    public function SetProtection($perms, $password)
    {
        $this->calls[] = ['SetProtection', $perms, $password];
    }

    public function SetTitle($title)
    {
        $this->calls[] = ['SetTitle', $title];
    }

    public function SetAuthor($author)
    {
        $this->calls[] = ['SetAuthor', $author];
    }

    public function SetSubject($subject)
    {
        $this->calls[] = ['SetSubject', $subject];
    }

    public function SetKeywords($keywords)
    {
        $this->calls[] = ['SetKeywords', $keywords];
    }

    public function WriteHTML($html)
    {
        $this->calls[] = ['WriteHTML', $html];
    }

    public function AddPage()
    {
        $this->calls[] = ['AddPage'];
    }
}

/**
 * Override real mPDF with FakeMpdf.
 */
class_alias(FakeMpdf::class, \Mpdf\Mpdf::class);

class PdfGeneratorTest extends TestCase
{
    protected function generator(): PdfGenerator
    {
        return new PdfGenerator(
            renderer: new FakeRenderer(),
            streamFactory: new Psr17Factory(),
            config: ['default' => true],
            name: 'mpdf'
        );
    }

    public function testName()
    {
        $gen = $this->generator();
        $this->assertSame('mpdf', $gen->name());
    }

    public function testGenerateReturnsBinaryPdf()
    {
        $pdf = new Pdf();
        $pdf->html('<p>Hello</p>');

        $gen = $this->generator();

        $binary = $gen->generate($pdf);

        $this->assertSame('PDF_BINARY', $binary);
    }

    public function testStreamReturnsPsr7Stream()
    {
        $pdf = new Pdf();
        $pdf->text('Hello');

        $gen = $this->generator();

        $stream = $gen->stream($pdf);

        $this->assertSame('PDF_BINARY', (string)$stream);
    }

    public function testConfigParametersAreApplied()
    {
        $pdf = new Pdf();
        $pdf->paper(Paper::A4);
        $pdf->orientation(Orientation::LANDSCAPE);
        $pdf->margins('10mm', '20mm', '30mm', '40mm');
        $pdf->dpi(300);

        $gen = $this->generator();
        $gen->generate($pdf);

        $mpdf = \Mpdf\Mpdf::$lastInstance;

        $construct = $mpdf->calls[0];
        $this->assertSame('__construct', $construct[0]);

        $config = $construct[1];

        $this->assertSame('A4', $config['format']);
        $this->assertSame('L', $config['orientation']);
        $this->assertSame(40.0, $config['margin_left']);
        $this->assertSame(20.0, $config['margin_right']);
        $this->assertSame(10.0, $config['margin_top']);
        $this->assertSame(30.0, $config['margin_bottom']);
        $this->assertSame(300, $config['dpi']);
    }

    public function testParametersAreAppliedToMpdf()
    {
        $pdf = new Pdf();
        $pdf->orientation(Orientation::PORTRAIT);
        $pdf->header('<h1>Header</h1>');
        $pdf->footer('<p>Footer</p>');
        $pdf->pagination('{PAGE}/{PAGES}');
        $pdf->watermark('CONFIDENTIAL');
        $pdf->compression(8);
        $pdf->password('secret');
        $pdf->documentInfo('Title', 'Author', 'Subject', ['php', 'pdf']);
        $pdf->html('<b>HTML</b>');
        $pdf->text('TEXT');
        $pdf->pageBreak();

        $gen = $this->generator();
        $gen->generate($pdf);

        $mpdf = \Mpdf\Mpdf::$lastInstance;

        $calls = array_column($mpdf->calls, 0);

        $this->assertContains('SetHTMLHeader', $calls);
        $this->assertContains('SetHTMLFooter', $calls);
        $this->assertContains('SetCompression', $calls);
        $this->assertContains('SetWatermarkText', $calls);
        $this->assertContains('SetProtection', $calls);
        $this->assertContains('SetTitle', $calls);
        $this->assertContains('SetAuthor', $calls);
        $this->assertContains('SetSubject', $calls);
        $this->assertContains('SetKeywords', $calls);
        $this->assertContains('WriteHTML', $calls);
        $this->assertContains('AddPage', $calls);
    }

    public function testNumericFontWeightsAreNormalized()
    {
        $pdf = new Pdf();
        $pdf->header('<p style="font-weight: 700">Header</p>');
        $pdf->footer('<p style="font-weight:300">Footer</p>');
        $pdf->html('<style>.title {font-weight: 600}</style><p style="font-weight: 900">Html</p>');
        
        $gen = $this->generator();
        $gen->generate($pdf);
        
        $calls = \Mpdf\Mpdf::$lastInstance->calls;
        $html = array_column($calls, 1, 0);
        
        $this->assertSame('<p style="font-weight: bold">Header</p>', $html['SetHTMLHeader']);
        $this->assertSame('<p style="font-weight: normal">Footer</p>', $html['SetHTMLFooter']);
        $this->assertSame(
            '<style>.title {font-weight: bold}</style><p style="font-weight: bold">Html</p>',
            $html['WriteHTML']
        );
    }
    
    public function testPaginationOnlyUsesDefaultFooter()
    {
        $pdf = new Pdf();
        $pdf->pagination('{PAGE}/{PAGES}');
        
        $gen = $this->generator();
        $gen->generate($pdf);
        
        $calls = \Mpdf\Mpdf::$lastInstance->calls;
        $html = array_column($calls, 1, 0);
        
        $this->assertSame('{PAGENO}/{nbpg}', $html['SetFooter']);
        $this->assertNotContains('SetHTMLFooter', array_column($calls, 0));
    }
    
    public function testPaginationIsAddedBelowFooter()
    {
        $pdf = new Pdf();
        $pdf->pagination('{PAGE}/{PAGES}');
        $pdf->footer('<p>Footer</p>');
        
        $gen = $this->generator();
        $gen->generate($pdf);
        
        $calls = \Mpdf\Mpdf::$lastInstance->calls;
        $footerCalls = array_values(array_filter($calls, fn (array $call): bool => $call[0] === 'SetHTMLFooter'));
        
        // the default footer of mPDF with the pagination is added below the footer, for odd and even pages:
        $this->assertSame(['SetFooter', '{PAGENO}/{nbpg}'], array_values(array_filter($calls, fn (array $call): bool => $call[0] === 'SetFooter'))[0]);
        $this->assertSame([
            ['SetHTMLFooter', '<p>Footer</p><div>{PAGENO}/{nbpg}</div>', 'O'],
            ['SetHTMLFooter', '<p>Footer</p><div>{PAGENO}/{nbpg}</div>', 'E'],
        ], $footerCalls);
    }
    
    public function testPagePlaceholdersInHeaderAndFooterAreTranslated()
    {
        $pdf = new Pdf();
        $pdf->header('<p>{PAGE}/{PAGES}</p>');
        $pdf->footer('<p>Page {PAGE} of {PAGES}</p>');
        
        $gen = $this->generator();
        $gen->generate($pdf);
        
        $html = array_column(\Mpdf\Mpdf::$lastInstance->calls, 1, 0);
        
        $this->assertSame('<p>{PAGENO}/{nbpg}</p>', $html['SetHTMLHeader']);
        $this->assertSame('<p>Page {PAGENO} of {nbpg}</p>', $html['SetHTMLFooter']);
    }
    
    public function testTextIsEscaped()
    {
        $pdf = new Pdf();
        $pdf->text('Price: a<b & "c" <you@example.com>');
        
        $gen = $this->generator();
        $gen->generate($pdf);
        
        $html = array_column(\Mpdf\Mpdf::$lastInstance->calls, 1, 0);
        
        $this->assertSame('Price: a&lt;b &amp; &quot;c&quot; &lt;you@example.com&gt;', $html['WriteHTML']);
    }
    
    public function testTextKeepsLineBreaks()
    {
        $pdf = new Pdf();
        $pdf->text("line1\nline2");
        
        $gen = $this->generator();
        $gen->generate($pdf);
        
        $html = array_column(\Mpdf\Mpdf::$lastInstance->calls, 1, 0);
        
        $this->assertSame("line1<br />\nline2", $html['WriteHTML']);
    }
    
    public function testHeaderAndFooterAreSetBeforeContent()
    {
        $pdf = new Pdf();
        $pdf->html('<p>Content</p>');
        $pdf->header('<p>Header</p>');
        $pdf->footer('<p>Footer</p>');
        
        $gen = $this->generator();
        $gen->generate($pdf);
        
        $calls = array_column(\Mpdf\Mpdf::$lastInstance->calls, 0);
        
        $this->assertLessThan(array_search('WriteHTML', $calls), array_search('SetHTMLHeader', $calls));
        $this->assertLessThan(array_search('WriteHTML', $calls), array_search('SetHTMLFooter', $calls));
    }
    
    public function testGenerateThrowsPdfGenerationException()
    {
        $this->expectException(PdfGenerationException::class);

        $pdf = new Pdf();

        $gen = new PdfGenerator(
            renderer: new FakeRenderer(),
            streamFactory: new Psr17Factory(),
            config: ['invalid' => true], // triggers FakeMpdf exception
            name: 'mpdf'
        );

        $gen->generate($pdf);
    }
}