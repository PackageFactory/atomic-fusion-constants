<?php
namespace PackageFactory\AtomicFusion\Constants\Tests\Unit;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\Constants\Interpolator;

class InterpolatorTest extends UnitTestCase
{
	/**
     * @var Parser
     */
    protected $constantsInterpolator;

    public function setUp()
    {
        $this->constantsInterpolator = new Interpolator();
    }

    /**
     * @test
     */
    public function shouldReplaceConstantPlaceholderWithConstantValue()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/interpolation.fusion');
        $constants = [
            'MY_CONSTANT' => 'resource://Neos.Demo/Test'
        ];

        $result = $this->constantsInterpolator->replaceConstants($fixture, $constants);

        $this->assertContains('path = ${"resource://Neos.Demo/Test" + \'/foo.md\'}', $result);
    }

    /**
     * @test
     * @expectedException \PackageFactory\AtomicFusion\Constants\InterpolatorException
     */
    public function shouldComplainIfConstantIsNotDeclared()
    {
        $fixture = file_get_contents(__DIR__ . '/../Fixtures/interpolation.fusion');
        $this->constantsInterpolator->replaceConstants($fixture, []);
    }
}
