# Pdf Service

The PDF service offers a set of interfaces for creating, streaming, and downloading PDF documents. It includes a default implementation powered by the [mPDF library](https://github.com/mpdf/mpdf).

## Table of Contents

- [Getting started](#getting-started)
    - [Requirements](#requirements)
    - [Highlights](#highlights)
- [Documentation](#documentation)
    - [Basic Usage](#basic-usage)
        - [Generating Pdf](#generating-pdf)
        - [Streaming Pdf](#streaming-pdf)
        - [Downloading Pdf](#downloading-pdf)
    - [Pdf](#pdf)
        - [Contents](#contents)
        - [Headers and Footers](#headers-and-footers)
        - [Page Setup](#page-setup)
        - [Page Break](#page-break)
        - [Paging](#paging)
        - [Document Info](#document-info)
        - [Compression](#compression)
        - [Security](#security)
    - [Custom Pdf](#custom-pdf)
    - [Pdf Generator](#pdf-generator)
        - [Mpdf Pdf Generator](#mpdf-pdf-generator)
        - [Null Pdf Generator](#null-pdf-generator)
    - [Pdf Generators](#pdf-generators)
        - [Default Pdf Generators](#default-pdf-generators)
        - [Lazy Pdf Generators](#lazy-pdf-generators)
    - [Mpdf](#mpdf)
        - [Pdf Generator - Mpdf](#pdf-generator---mpdf)
        - [Pdf Generator Factory - Mpdf](#pdf-generator-factory---mpdf)
    - [Learn More](#learn-more)
        - [Queueing PDF](#queueing-pdf)
        - [Prerendering Templates](#prerendering-templates)
- [Credits](#credits)
___

# Getting started

Add the latest version of the Pdf service project running this command.

```
composer require tobento/service-pdf
```

## Requirements

- PHP 8.4 or above

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

# Documentation

## Basic Usage

### Generating Pdf

To generate a PDF, create a `Pdf` instance with your desired content and pass it to a PDF generator.  
The generator processes the configuration and returns the final PDF as a binary string, ready to store, stream, or return in a response.


```php
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\PdfGeneratorInterface;

class SomeService
{
    public function generate(PdfGeneratorInterface $pdfGenerator): void
    {
        $pdf = new Pdf()
            ->html('<p>Lorem Ipsum</p>');

        $binaryString = $pdfGenerator->generate(pdf: $pdf);
    }
}
```

Check out the [Pdf](#pdf) to learn more about building PDF content.

Check out the available [Pdf Generators](#pdf-generator) to explore the different generator implementations.

### Streaming Pdf

Stream the generated PDF directly as a PSR-7 `StreamInterface`.  
This is useful when returning the PDF from a controller or middleware.

```php
use Psr\Http\Message\StreamInterface;
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\PdfGeneratorInterface;

class SomeService
{
    public function stream(PdfGeneratorInterface $pdfGenerator): void
    {
        $pdf = new Pdf()
            ->html('<p>Lorem Ipsum</p>');

        $stream = $pdfGenerator->stream(pdf: $pdf);
        
        var_dump($stream instanceof StreamInterface);
        // bool(true)
    }
}
```

Check out the [Pdf](#pdf) to learn more about building PDF content.

Check out the available [Pdf Generators](#pdf-generator) to explore the different generator implementations.

### Downloading Pdf

Create a downloadable PDF response using the `PdfResponseFactoryInterface`.  
The factory generates the PDF using the configured generator and returns a PSR-7 response with the correct download headers.

```php
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\PdfResponseFactoryInterface;

class SomeService
{
    public function download(PdfResponseFactoryInterface $pdfResponseFactory): void
    {
        $pdf = new Pdf()
            ->html('<p>Lorem Ipsum</p>');

        // Create a PSR-7 download response:
        $response = $pdfResponseFactory->download(
            pdf: $pdf,
            filename: 'document.pdf',
        );

        // Return or emit the response depending on your framework.
    }
}
```

Check out the [Pdf](#pdf) to learn more about building PDF content.

Check out the available [Pdf Generators](#pdf-generator) to explore the different generator implementations.

## Pdf

The `Pdf` object provides a fluent API for building PDF documents.  
You can add content, configure layout, and then generate the final PDF using a [PDF Generator](#pdf-generator).

### Contents

You can add HTML, plain text, or templates to the document:

```php
use Tobento\Service\Pdf\Pdf;

$pdf = new Pdf()
    // Add HTML content:
    ->html('<p>Lorem Ipsum</p>')

    // Add plain text:
    ->text('Lorem ipsum')

    // Add a template:
    ->template(name: 'shop/invoice', data: []);
```

#### Templates

Use the `template` method to render a named template with the renderer provided by the PDF generator, allowing you to generate structured and reusable PDF content.

```php
use Tobento\Service\Pdf\Pdf;

$pdf = new Pdf()->template(
    name: 'shop/invoice',
    data: [
        'title' => 'Title',
        'items' => $items,
    ]
);
```

**Example Template**

```php
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $view->esc($title) ?></title>

        <?php
        // Render linked CSS only when inline styles are disabled:
        if (!$withInlineCssStyles) {
            echo $view->assets()->render();
        }
        ?>

        <?php
        // Add CSS assets (also works inside subviews):
        $view->asset('pdf.css');
        ?>
    </head>
    <body>
        <?= $view->render('pdf/header') ?>

        <h1><?= $view->esc($title) ?></h1>

        <?= $view->render('pdf/footer') ?>
    </body>
</html>
```

### Headers and Footers

You can define custom HTML for the header and footer of each page.

```php
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\Template;

$pdf = new Pdf()
    ->header('<div>My Header</div>')
    
    ->footer('<div>My Footer</div>')

    // or using a template
    ->header(new Template(name: 'inc/header', data: []));
```

### Page Setup

Configure the page layout of your PDF, including paper size, orientation, margins, DPI, and custom dimensions.

```php
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\Enums\Paper;
use Tobento\Service\Pdf\Enums\Orientation;

$pdf = new Pdf()
    // Set paper size:
    ->paper(Paper::A4)

    // Set orientation:
    ->orientation(Orientation::Landscape)

    // Set margins:
    ->margins(
        top: '10mm',
        right: '10mm',
        bottom: '10mm',
        left: '10mm',
    )

    // Set custom page size (width, height in mm):
    ->pageSize(width: 210.0, height: 297.0)

    // Set DPI:
    ->dpi(300);
```

### Page Break

Insert a manual page break into the PDF.  
This forces the next content to start on a new page.

```php
use Tobento\Service\Pdf\Pdf;

$pdf = new Pdf()
    // Add some content:
    ->html('<p>First page content</p>')

    // Insert a page break:
    ->pageBreak()

    // Content after this will appear on the next page:
    ->html('<p>Second page content</p>');
```

### Paging

Configure how page numbers are displayed in the PDF.  
Pagination uses generator-agnostic placeholders, which are translated internally by each PDF generator (e.g., Mpdf, Dompdf, TCPDF).

**Supported placeholders**

| Placeholder | Meaning |
|------------|---------|
| `{PAGE}`   | Current page number |
| `{PAGES}`  | Total number of pages |

**Example**

```php
use Tobento\Service\Pdf\Pdf;

$pdf = new Pdf()
    // Set pagination format:
    ->pagination('{PAGE}/{PAGES}');
```

### Document Info

Set metadata for the PDF document such as title, author, subject, and keywords.  
This information is embedded into the generated PDF and can be viewed in most PDF readers.

```php
use Tobento\Service\Pdf\Pdf;

$pdf = new Pdf()
    ->documentInfo(
        title: 'Monthly Report',
        author: 'John Doe',
        subject: 'Finance Overview',
        keywords: ['finance', 'report', 'monthly']
    );
```

### Compression

Set the compression level used when generating the PDF.  
The level must be an integer from **0** (no compression) to **9** (maximum compression).

```php
use Tobento\Service\Pdf\Pdf;

$pdf = new Pdf()
    // Compression level from 0-9:
    ->compression(6);
```

### Security

Protect the generated PDF by setting a password.  
The document will require this password to be opened in any PDF viewer.

```php
use Tobento\Service\Pdf\Pdf;

$pdf = new Pdf()
    ->password('secret123');
```

## Custom Pdf

You can create your own PDF classes by extending the base `Pdf` class.  
This is useful when you want to encapsulate reusable layouts, default settings, or domain-specific PDF structures such as invoices, reports, or certificates.

```php
use Tobento\Service\Pdf\Pdf;

class InvoicePdf extends Pdf
{
    public function __construct(
        protected Invoice $invoice,
    ) {
        // Set document metadata:
        $this->documentInfo(
            title: 'Invoice #' . $invoice->number,
        );
        
        // Render a template with invoice data:
        $this->template(
            name: 'shop/invoice',
            data: [
                'invoice' => $invoice,
            ]
        );
    }
}

// Generate the PDF:
$binaryString = $pdfGenerator->generate(
    pdf: new InvoicePdf($invoice)
);
```

## Pdf Generator

### Mpdf Pdf Generator

For detailed documentation on the Mpdf implementation, see the  
[Pdf Generator - Mpdf](#pdf-generator---mpdf) section.

### Null Pdf Generator

The `NullPdfGenerator` is a simple implementation of `PdfGeneratorInterface` that produces **no output**.  
It follows the *Null Object Pattern*, making it useful as a safe fallback when PDF generation is optional, disabled, or not configured.

This generator never throws exceptions and never performs any real rendering.  
It always returns an empty string or an empty PSR-7 stream.

**Example**

```php
use Nyholm\Psr7\Factory\Psr17Factory;
use Tobento\Service\Pdf\NullPdfGenerator;

$generator = new NullPdfGenerator(
    streamFactory: new Psr17Factory(),
    name: 'disabled',
);

// Returns an empty string:
$content = $generator->generate($pdf);

// Returns an empty PSR-7 stream:
$stream = $generator->stream($pdf);
```

## Pdf Generators

### Default Pdf Generators

The `PdfGenerators` class provides a simple, eager registry for PDF generators.  
Unlike `LazyPdfGenerators`, all generators are **fully constructed upfront** and registered directly through the constructor.

This is ideal when:

- you want predictable, immediate initialization  
- your generators are lightweight to construct  
- you prefer explicit wiring without factories or callables  
- you only need a small number of generators

`PdfGenerators` implements both `PdfGeneratorsInterface` and `PdfGeneratorInterface`, allowing it to be used either as a **registry** or as a **default generator** (delegating to the first registered generator).

**Example**

```php
use Tobento\Service\Pdf\Mpdf;
use Tobento\Service\Pdf\PdfGenerators;
use Tobento\Service\Pdf\PdfGeneratorInterface;

// Create generator instances:
$mpdf = new Mpdf\PdfGenerator(
    renderer: $renderer,
    streamFactory: $streamFactory,
    name: 'foo',
);

$another = new Mpdf\PdfGenerator(
    renderer: $renderer2,
    streamFactory: $streamFactory2,
    name: 'bar',
);

// Register them:
$generators = new PdfGenerators(
    $mpdf,
    $another,
);

// Retrieve a generator by name:
$mpdfGenerator = $generators->get($mpdf->name());

// Use PdfGenerators as the default generator:
$output = $generators->generate($pdf);
```

### Lazy Pdf Generators

The `LazyPdfGenerators` class allows you to register PDF generators without creating them upfront.  
Generators are instantiated **only when first accessed**, which is useful when using factories, callables, or configuration-based setups.

`LazyPdfGenerators` implements both `PdfGeneratorsInterface` and `PdfGeneratorInterface`, so it can be used as a **registry** or as a **default generator** (delegating to the first registered generator).

**Example**

```php
use Psr\Container\ContainerInterface;
use Tobento\Service\Pdf\LazyPdfGenerators;
use Tobento\Service\Pdf\Mpdf;
use Tobento\Service\Pdf\PdfGeneratorInterface;

$generators = new LazyPdfGenerators(
    container: $container,
    generators: [

        // 1. Direct instance:
        'mpdf-direct' => new Mpdf\PdfGenerator(
            renderer: $renderer,
            streamFactory: $streamFactory,
        ),

        // 2. Callable definition:
        'mpdf-callable' => function (ContainerInterface $c): PdfGeneratorInterface {
            return $c->get(Mpdf\PdfGeneratorFactory::class)->createGenerator('mpdf-callable');
        },

        // 3. Factory definition:
        'mpdf-factory' => [
            'factory' => Mpdf\PdfGeneratorFactory::class,
            'config' => [
                'tempDir' => 'var/tmp/',
            ],
        ],
    ]
);

// Retrieve a generator by name:
$mpdf = $generators->get('mpdf-factory');

// Use LazyPdfGenerators as a default generator:
$output = $generators->generate($pdf);
```

## Mpdf

### Pdf Generator - Mpdf

The Mpdf generator integrates with the [View Service](https://github.com/tobento-ch/service-view) and requires a PSR-7 implementation such as [Nyholm PSR-7](https://github.com/Nyholm/psr7).  
You may also use **any other PSR-7 implementation** or **any other renderer** supported by the view service. If needed, you can also implement your own renderer entirely by providing a custom `RendererInterface` implementation.

```php
use Nyholm\Psr7\Factory\Psr17Factory;
use Tobento\Service\Dir;
use Tobento\Service\Pdf\Mpdf;
use Tobento\Service\Pdf\PdfGeneratorInterface;
use Tobento\Service\Pdf\RendererInterface;
use Tobento\Service\Pdf\ViewRenderer;
use Tobento\Service\View;

// Create the view renderer:
$view = new View\View(
    new View\PhpRenderer(
        new Dir\Dirs(
            new Dir\Dir('dir/views/'),
        )
    ),
    new View\Data(),
    new View\Assets('dir/src/', 'https://example.com/src/')
);

$renderer = new ViewRenderer($view); // RendererInterface

// Create the Mpdf generator:
$pdfGenerator = new Mpdf\PdfGenerator(
    renderer: $renderer,
    streamFactory: new Psr17Factory(),
);

var_dump($pdfGenerator instanceof PdfGeneratorInterface);
// bool(true)
```

### Pdf Generator Factory - Mpdf

The Mpdf generator also provides a factory implementation, allowing you to create PDF generators on demand.  
As with the direct generator setup, you can use any renderer supported by the view service and any PSR-7 implementation such as Nyholm PSR-7.

```php
use Nyholm\Psr7\Factory\Psr17Factory;
use Tobento\Service\Dir;
use Tobento\Service\Pdf\Mpdf;
use Tobento\Service\Pdf\PdfGeneratorFactoryInterface;
use Tobento\Service\Pdf\ViewRenderer;
use Tobento\Service\View;

// create the view renderer:
$view = new View\View(
    new View\PhpRenderer(
        new Dir\Dirs(
            new Dir\Dir('dir/views/'),
        )
    ),
    new View\Data(),
    new View\Assets('dir/src/', 'https://example.com/src/')
);

$renderer = new ViewRenderer($view);

// Create the Mpdf generator factory:
$pdfGeneratorFactory = new Mpdf\PdfGeneratorFactory(
    renderer: $renderer,
    streamFactory: new Psr17Factory(),
);

var_dump($pdfGeneratorFactory instanceof PdfGeneratorFactoryInterface);
// bool(true)
```

## Learn More

### Queueing PDF

PDF parameters are fully serializable, which makes it easy to store PDF jobs in a queue and recreate them later in a worker process.

```php
use Tobento\Service\Pdf\ParametersFactory;
use Tobento\Service\Pdf\Pdf;

// Create a PDF definition:
$pdf = new Pdf()->html('<p>Lorem Ipsum</p>');

// Serialize parameters for queue storage:
$serializedParams = $pdf->parameters()->jsonSerialize();

// Later in a queue worker, recreate the parameters:
$parameters = new ParametersFactory()->createFromArray($serializedParams);

// Rebuild the PDF object:
$pdf = new Pdf()->withParameters($parameters);
```

This allows you to:

- push lightweight PDF jobs into a queue
- reconstruct the full PDF definition in a worker
- generate the PDF asynchronously using any registered generator

### Prerendering Templates

Some PDF parameters (such as `Template`, `Header`, `Footer`, or custom parameters) may contain a `TemplateInterface` instead of raw HTML. These parameters implement `TemplateAwareInterface`, which allows you to detect and render templates before generating the final PDF.

Normally, when generating a PDF directly, PDF generators will render templates automatically. However, there are situations where you may want to prerender templates yourself:

- When queueing a PDF job and you want the job payload to contain only final HTML  
- When running PDF generation in an isolated or restricted environment  
- When you want to avoid executing template logic outside your main application  
- When you want to serialize parameters safely without relying on autowiring a custom PDF class  
- When you want to ensure deterministic HTML output before passing it to the PDF engine  

Below is an example of prerendering templates before queueing a PDF job:

```php
use Tobento\Service\Pdf\Parameter\Parameters;
use Tobento\Service\Pdf\PdfInterface;
use Tobento\Service\Pdf\RendererInterface;
use Tobento\Service\Pdf\TemplateAwareInterface;

protected function renderTemplates(PdfInterface $pdf, RendererInterface $renderer): PdfInterface
{
    $parameters = new Parameters();

    foreach ($pdf->parameters() as $parameter) {

        if (
            $parameter instanceof TemplateAwareInterface
            && $parameter->template()
        ) {
            $rendered = $renderer->renderTemplate($parameter->template());
            $parameter = $parameter->withRenderedHtml($rendered);
        }

        $parameters->add($parameter);
    }

    return new Pdf()->withParameters($parameters);
}
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)
- [mPDF](https://github.com/mpdf/mpdf) - PDF rendering engine used by the Mpdf generator