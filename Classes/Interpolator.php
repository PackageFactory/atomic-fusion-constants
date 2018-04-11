<?php
namespace PackageFactory\AtomicFusion\Constants;

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Interpolator
{
	public function replaceConstants(string $source, array $constants) : string
	{
		$result = $source;

		foreach($constants as $key => $value) {
			$result = str_replace(sprintf('const::%s', $key), $this->sanitizeValue($value), $result);
		}

		return $result;
	}

	public function sanitizeValue($value)
	{
		if (is_string($value)) {
			return sprintf('"%s"', addslashes($value));
		}

		return $value;
	}
}
