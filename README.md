# RTFLex

[![Build Status](https://travis-ci.org/crgwbr/php-rtflex.png?branch=master)](https://travis-ci.org/crgwbr/php-rtflex)

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

    $doc = RTFLexer::file('/path/to/myFile.rtf')
    echo $doc->extractText();

