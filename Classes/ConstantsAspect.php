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
		list($sourceCode, $constants) = $this->constantsParser->extractConstants($sourceCode, $contextPathAndFilename);

		//
		// Create Magic Constants
		//
		if (array_key_exists('__DIR__', $constants)) {
			throw new ParserException(
				sprintf('Cannot redeclare constant "__DIR__". (file: %s)', $contextPathAndFilename),
				1523458997
			);
		}

		$constants['__DIR__'] = dirname($contextPathAndFilename);

		if (array_key_exists('__FILE__', $constants)) {
			throw new ParserException(
				sprintf('Cannot redeclare constant "__FILE__". (file: %s)', $contextPathAndFilename),
				1523458997
			);
		}

		$constants['__FILE__'] = $contextPathAndFilename;

		//
		// Replace constants in remaining source code
		//
		$sourceCode = $this->constantsInterpolator->replaceConstants($sourceCode, $constants, $contextPathAndFilename);
		$joinPoint->setMethodArgument('sourceCode', $sourceCode);
	}
}
