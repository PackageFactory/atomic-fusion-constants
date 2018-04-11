<?php
namespace PackageFactory\AtomicFusion\Constants;

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Parser
{
	/**
	 * Example:
	 *    const: MY_CONSTANT = 'My Value'
	 *
	 * @var string
	 */
	const PATTERN_CONSTANT_DECLARATION = '/^\s*const:\s*(?P<name>[A-Z_][A-Z0-9_]*)\s*=\s*(?P<value>.*)\s*$/';

	/**
	 * Example:
	 *     'My Value'
	 *
	 * @var string
	 */
	const PATTERN_SINGLE_QUOTE_STRING = '/^\'(?P<value>.*)\'$/';

	/**
	 * Example:
	 *     "My Value"
	 *
	 * @var string
	 */
	const PATTERN_DOUBLE_QUOTE_STRING = '/^\"(?P<value>.*)\"$/';

	/**
	 * Example:
	 *     true
	 *
	 * @var string
	 */
	const PATTERN_BOOLEAN = '/^\s*(TRUE|FALSE|true|false)\s*$/';

	/**
	 * Example:
	 *     42
	 *
	 * @var string
	 */
	const PATTERN_INTEGER = '/^\s*-?\d+\s*$/';

	/**
	 * Example:
	 *     11.38
	 *
	 * @var string
	 */
	const PATTERN_FLOAT = '/^\s*-?\d+(\.\d+)?\s*$/';

	public function extractConstants(string $source, array &$constants = []) : array
	{
		$lines = explode(PHP_EOL, $source);
		$result = '';

		foreach($lines as $line) {
			if (preg_match(self::PATTERN_CONSTANT_DECLARATION, $line, $matches)) {
				$constants[$matches['name']] = $this->parseValue($matches['value']);
				continue;
			}

			$result .= $line . PHP_EOL;
		}

		return [$result, $constants];
	}

	protected function parseValue($value)
	{
		switch (true) {
			case preg_match(self::PATTERN_SINGLE_QUOTE_STRING, $value, $matches):
			case preg_match(self::PATTERN_DOUBLE_QUOTE_STRING, $value, $matches):
				return $matches['value'];

			case preg_match(self::PATTERN_BOOLEAN, $value):
				return strtolower($value) === 'true';

			case preg_match(self::PATTERN_INTEGER, $value):
				return intval($value);

			case preg_match(self::PATTERN_FLOAT, $value):
				return floatval($value);
		}
	}
}
