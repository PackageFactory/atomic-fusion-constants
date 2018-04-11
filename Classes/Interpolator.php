<?php
namespace PackageFactory\AtomicFusion\Constants;

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Interpolator
{
	/**
	 * Example:
	 *    const::MY_CONSTANT
	 *
	 * @var string
	 */
	const PATTERN_CONSTANT = '/const::(?P<name>[A-Z][A-Z0-9_]*)/';

	public function replaceConstants(string $source, array $constants) : string
	{
		return preg_replace_callback(
			self::PATTERN_CONSTANT,
			function ($matches)	use ($constants) {
				if (!array_key_exists($matches['name'], $constants)) {
					throw new InterpolatorException(
						sprintf('Constant "%s" has not been declared!', $matches['name']),
						1523449249
					);
				}

				$value = $constants[$matches['name']];

				return $this->sanitizeValue($value);
			},
			$source
		);
	}

	public function sanitizeValue($value)
	{
		if (is_string($value)) {
			return sprintf('"%s"', addslashes($value));
		}

		return $value;
	}
}
