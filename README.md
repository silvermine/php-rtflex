# RTFLex

[![Build Status](https://travis-ci.org/silvermine/php-rtflex.png?branch=master)](https://travis-ci.org/silvermine/php-rtflex)

RTFLex is a simple lexer / tokenizer for RTF formatted data.

## Example Usage

RTFLex allows you to easily extract plain text from an RTF formatted file.
Here how you would do that:

    require_once "rtflex/RTFLexer.php";

    use RTFLex\io\StreamReader;
    use RTFLex\tokenizer\RTFTokenizer;
    use RTFLex\tree\RTFDocument;

    $reader = new StreamReader('/path/to/myFile.rtf');
    $tokenizer = new RTFTokenizer($reader);
    $doc = new RTFDocument($tokenizer);
    echo $doc->extractText();

While RTFLex uses namespaces to organize it's inner-workings, it also provides
a simple, global, front-end class for easy of use. This accomplishes the same
as the above code:

    require_once "rtflex/RTFLexer.php";

    $doc = RTFLexer::file('/path/to/myFile.rtf');
    echo $doc->extractText();

RTFLex also lets you easily extract hidden metadata from an RTF file. Take, for example, the follow RTF header:

    {\rtf1\ansi\ansicpg1252\cocoartf1187\cocoasubrtf370
    {\fonttbl\f0\fswiss\fcharset0 Helvetica;}
    {\colortbl;\red255\green255\blue255;}
    {\info
    {\title Sample Title}
    {\subject Sample Subject}
    {\author Craig Weber}
    {\*\company silvermine}
    {\*\copyright 2013 silvermine.}}\margl1440\margr1440\vieww10800\viewh8400\viewkind0
\pard\tx720\tx1440\tx2160\tx2880\tx3600\tx4320\tx5040\tx5760\tx6480\tx7200\tx7920\tx8640\pardirnatural
    &hellip;

Using the RTFDocument class, it's easy to extract those field.

    $doc = RTFLexer::file('/path/to/myFile.rtf');
    echo $doc->extractMetadata('title');     // => "Sample Title"
    echo $doc->extractMetadata('copyright'); // => "2013 silvermine."


