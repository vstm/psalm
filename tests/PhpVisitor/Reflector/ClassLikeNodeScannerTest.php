<?php

namespace Psalm\Tests\Internal\PhpVisitor\Reflector;

use PhpParser\Comment\Doc;
use Psalm\Aliases;
use Psalm\Internal\PhpVisitor\Reflector\ClassLikeNodeScanner;
use Psalm\Tests\TestCase;

use function array_map;

class ClassLikeNodeScannerTest extends TestCase
{
    public function testComplexPsalmType(): void
    {
        $doc = '/**
 * @psalm-type TypedArrayHandler callable(array<string>): void
 */
';
        $php_parser_doc = new Doc($doc);
        $type_aliases = ClassLikeNodeScanner::getTypeAliasesFromComment($php_parser_doc, new Aliases(), [], null);
        $this->assertArrayHasKey('TypedArrayHandler', $type_aliases);
        $this->assertSame(
            [
                ['callable', 0],
                ['(', 8],
                ['array', 9],
                ['<', 14],
                ['string', 15],
                ['>', 21],
                [')', 22],
                [':', 23],
                ['void', 25],
            ],
            $type_aliases['TypedArrayHandler']->replacement_tokens
        );
    }

    public function testComplexPsalmType1(): void
    {
        $doc = '/**
 * @psalm-type TypedArrayHandler callable(
                                    array<string>
                                 ): void
 */
';
        $php_parser_doc = new Doc($doc);
        $type_aliases = ClassLikeNodeScanner::getTypeAliasesFromComment($php_parser_doc, new Aliases(), [], null);
        $this->assertArrayHasKey('TypedArrayHandler', $type_aliases);
        $this->assertSame(
            [
                'callable',
                '(',
                'array',
                '<',
                'string',
                '>',
                ')',
                ':',
                'void',
            ],
            array_map(static fn($token_info) => $token_info[0], $type_aliases['TypedArrayHandler']->replacement_tokens)
        );
    }

    public function testComplexPsalmType2(): void
    {
        $doc = '/**
 * @psalm-type TypedArrayHandler callable(
                                    array<string>
                                 ): void
                                 stefan
 */
';
        $php_parser_doc = new Doc($doc);
        $type_aliases = ClassLikeNodeScanner::getTypeAliasesFromComment($php_parser_doc, new Aliases(), [], null);
        $this->assertArrayHasKey('TypedArrayHandler', $type_aliases);
        $this->assertSame(
            [
                'callable',
                '(',
                'array',
                '<',
                'string',
                '>',
                ')',
                ':',
                'void',
            ],
            array_map(static fn($token_info) => $token_info[0], $type_aliases['TypedArrayHandler']->replacement_tokens)
        );
    }
}
