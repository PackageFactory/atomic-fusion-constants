<?php
namespace PackageFactory\AtomicFusion\Constants;

use Neos\Flow\Annotations as Flow;

/**
 * Replaces constant tokens in fusion code with their corresponding values
 *
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
    const PATTERN_CONSTANT = '/const::(?P<name>[A-Z_][A-Z0-9_]*)/';

    /**
     * Replace all ocurrences of constants in the given source code
     *
     * @param string $source
     * @param array $constants
     * @param string $contextPathAndFilename
     * @return string
     */
    public function replaceConstants(string $source, array $constants, string $contextPathAndFilename = '') : string
    {
        return preg_replace_callback(
            self::PATTERN_CONSTANT,
            function ($matches) use ($constants, $contextPathAndFilename) {
                if (!array_key_exists($matches['name'], $constants)) {
                    throw new InterpolatorException(
                        sprintf('Constant "%s" has not been declared.', $matches['name']) .
                        ($contextPathAndFilename ? sprintf(' (file: %s)', $contextPathAndFilename) : ''),
                        1523449249
                    );
                }

                $value = $constants[$matches['name']];

                return $this->sanitizeValue($value);
            },
            $source
        );
    }

    /**
     * Sanitize a value before it is added to the source code
     *
     * @param mixed $value
     * @return mixed
     */
    public function sanitizeValue($value)
    {
        if (is_string($value)) {
            return sprintf('"%s"', addslashes($value));
        }

        return $value;
    }
}
