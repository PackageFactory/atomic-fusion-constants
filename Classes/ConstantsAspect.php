<?php
namespace PackageFactory\AtomicFusion\Constants;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;

/**
 * @Flow\Aspect
 */
class ConstantsAspect
{
	/**
	 * @Flow\Inject
	 * @var Parser
	 */
	protected $constantsParser;

	/**
	 * @Flow\Inject
	 * @var Interpolator
	 */
	protected $constantsInterpolator;

	/**
	 * @Flow\Before("method(Neos\Fusion\Core\Parser->parse())")
	 * @param JoinPointInterface $joinPoint
	 */
	public function preProcessFusionFileOnInclude(JoinPointInterface $joinPoint)
	{
		$sourceCode = $joinPoint->getMethodArgument('sourceCode');
		$contextPathAndFilename = $joinPoint->getMethodArgument('contextPathAndFilename');

		//
		// Parse constants into $constants
		//
		list($sourceCode, $constants) = $this->constantsParser->extractConstants($sourceCode);

		//
		// Create Magic Constants
		//
		$constants['__DIR__'] = dirname($contextPathAndFilename);
		$constants['__FILE__'] = $contextPathAndFilename;

		$sourceCode = $this->constantsInterpolator->replaceConstants($sourceCode, $constants);
		$joinPoint->setMethodArgument('sourceCode', $sourceCode);
	}
}
