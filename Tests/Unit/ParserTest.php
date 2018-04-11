<?php
namespace PackageFactory\AtomicFusion\Constants\Tests\Unit;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\Constants\Parser;

class ParserTest extends UnitTestCase
{
    /**
     * @var Parser
     */
    protected $constantsParser;

    public function setUp()
    {
        $this->constantsParser = new Parser();
    }

    /**
     * @test
     */
    public function shouldAllowToDeclareSingleQuoteStringConstants()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('SINGLE_QUOTE_STRING_CONSTANT', $constants);
        $this->assertEquals('foo', $constants['SINGLE_QUOTE_STRING_CONSTANT']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowToDeclareDoubleQuoteStringConstants()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('DOUBLE_QUOTE_STRING_CONSTANT', $constants);
        $this->assertEquals('bar', $constants['DOUBLE_QUOTE_STRING_CONSTANT']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowToDeclareBooleanConstants()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('BOOLEAN_CONSTANT', $constants);
        $this->assertSame(true, $constants['BOOLEAN_CONSTANT']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowToDeclareIntegerConstants()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('INTEGER_CONSTANT', $constants);
        $this->assertSame(42, $constants['INTEGER_CONSTANT']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowToDeclareFloatConstants()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('FLOAT_CONSTANT', $constants);
        $this->assertSame(11.38, $constants['FLOAT_CONSTANT']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsAtTheBeginningOfTheFile()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('BEGINNING_OF_THE_FILE', $constants);
        $this->assertSame('beginning', $constants['BEGINNING_OF_THE_FILE']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsAtBeforeAnyOtherStatements()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('AFTER_COMMENT_BUT_STILL_BEFORE_OTHER_STATEMENTS', $constants);
        $this->assertSame('before other statement', $constants['AFTER_COMMENT_BUT_STILL_BEFORE_OTHER_STATEMENTS']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsAfterIncludeStatements()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('AFTER_INCLUDE_DECLARATION', $constants);
        $this->assertSame('after include', $constants['AFTER_INCLUDE_DECLARATION']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsAfterNamespaceStatements()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('AFTER_NAMESPACE_DECELRATION', $constants);
        $this->assertSame('after namespace', $constants['AFTER_NAMESPACE_DECELRATION']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsWithinPrototypes()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('WITHIN_PROTOTYPE', $constants);
        $this->assertSame('within prototype', $constants['WITHIN_PROTOTYPE']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsAtferPrototypes()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('AFTER_PROTOTYPE', $constants);
        $this->assertSame('after prototype', $constants['AFTER_PROTOTYPE']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsWithinPaths()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('WITHIN_PATH', $constants);
        $this->assertSame('within path', $constants['WITHIN_PATH']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsWithinDeeplyNestedPaths()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('WITHIN_DEEPLY_NESTED_PATH', $constants);
        $this->assertSame('within deeply nested path', $constants['WITHIN_DEEPLY_NESTED_PATH']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsAfterDeeplyNestedPaths()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('AFTER_DEEPLY_NESTED_PATH', $constants);
        $this->assertSame('after deeply nested path', $constants['AFTER_DEEPLY_NESTED_PATH']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsAtTheEndOfPaths()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('END_OF_PATH', $constants);
        $this->assertSame('end of path', $constants['END_OF_PATH']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsAfterPaths()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('AFTER_PATH', $constants);
        $this->assertSame('after path', $constants['AFTER_PATH']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     */
    public function shouldAllowDeclarationOfConstantsAtTheEndOfTheFile()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/declaration-position.fusion');
        list($sourceCode, $constants) = $this->constantsParser->extractConstants($fixture);

        $this->assertNotNull($constants);
        $this->assertArrayHasKey('END_OF_THE_FILE', $constants);
        $this->assertSame('end', $constants['END_OF_THE_FILE']);
        $this->assertNotContains('const:', $sourceCode);
    }

    /**
     * @test
     * @expectedException \PackageFactory\AtomicFusion\Constants\ParserException
     */
    public function shouldComplainIfConstantHasBeenRedeclared()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/redeclaration.fusion');
        $this->constantsParser->extractConstants($fixture);
    }
}
