<?php
namespace PackageFactory\AtomicFusion\Constants\Tests\Unit;


use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\Constants\ConstantsAspect;
use PackageFactory\AtomicFusion\Constants\Parser;
use PackageFactory\AtomicFusion\Constants\Interpolator;
use Neos\Flow\Aop\JoinPointInterface;
use Prophecy\Argument;

class ConstantsAspectTest extends UnitTestCase
{
    /**
     * @test
     */
    public function shouldInjectMagicConstants()
    {
        $constantsAspect = new ConstantsAspect();

        $joinPoint = $this->prophesize(JoinPointInterface::class);
        $parser = $this->prophesize(Parser::class);
        $interpolator = $this->prophesize(Interpolator::class);

        $joinPoint->getMethodArgument('sourceCode')->willReturn('TheOriginalSource');
        $joinPoint->getMethodArgument('contextPathAndFilename')->willReturn('resource://Vendor.Site/Private/File.fusion');

        $parser->extractConstants('TheOriginalSource', 'resource://Vendor.Site/Private/File.fusion')->willReturn([
            'TheAlteredSource',
            ['SOME' => 'constant']
        ]);

        $interpolator->replaceConstants('TheAlteredSource', [
            'SOME' => 'constant',
            '__FILE__' => 'resource://Vendor.Site/Private/File.fusion',
            '__DIR__' => 'resource://Vendor.Site/Private'
        ], 'resource://Vendor.Site/Private/File.fusion')->willReturn('TheFinalSource');

        $joinPoint->setMethodArgument('sourceCode', 'TheFinalSource')
            ->shouldBeCalled();

        $this->inject($constantsAspect, 'constantsParser', $parser->reveal());
        $this->inject($constantsAspect, 'constantsInterpolator', $interpolator->reveal());

        $constantsAspect->preProcessFusionFileOnInclude($joinPoint->reveal());
    }

    /**
     * @test
     * @expectedException \PackageFactory\AtomicFusion\Constants\ParserException
     */
    public function shouldComplainIfFileConstantIsRedeclared()
    {
        $constantsAspect = new ConstantsAspect();

        $joinPoint = $this->prophesize(JoinPointInterface::class);
        $parser = $this->prophesize(Parser::class);
        $interpolator = $this->prophesize(Interpolator::class);

        $joinPoint->getMethodArgument('sourceCode')->willReturn('TheOriginalSource');
        $joinPoint->getMethodArgument('contextPathAndFilename')->willReturn('resource://Vendor.Site/Private/File.fusion');

        $parser->extractConstants('TheOriginalSource', 'resource://Vendor.Site/Private/File.fusion')->willReturn([
            'TheAlteredSource',
            ['__FILE__' => 'forbidden']
        ]);

        $this->inject($constantsAspect, 'constantsParser', $parser->reveal());
        $this->inject($constantsAspect, 'constantsInterpolator', $interpolator->reveal());

        $constantsAspect->preProcessFusionFileOnInclude($joinPoint->reveal());
    }

    /**
     * @test
     * @expectedException \PackageFactory\AtomicFusion\Constants\ParserException
     */
    public function shouldComplainIfDirectoryConstantIsRedeclared()
    {
        $constantsAspect = new ConstantsAspect();

        $joinPoint = $this->prophesize(JoinPointInterface::class);
        $parser = $this->prophesize(Parser::class);
        $interpolator = $this->prophesize(Interpolator::class);

        $joinPoint->getMethodArgument('sourceCode')->willReturn('TheOriginalSource');
        $joinPoint->getMethodArgument('contextPathAndFilename')->willReturn('resource://Vendor.Site/Private/File.fusion');

        $parser->extractConstants('TheOriginalSource', 'resource://Vendor.Site/Private/File.fusion')->willReturn([
            'TheAlteredSource',
            ['__DIR__' => 'forbidden']
        ]);

        $this->inject($constantsAspect, 'constantsParser', $parser->reveal());
        $this->inject($constantsAspect, 'constantsInterpolator', $interpolator->reveal());

        $constantsAspect->preProcessFusionFileOnInclude($joinPoint->reveal());
    }
}
