<?php

declare(strict_types=1);

/**
 * It's free open-source software released under the MIT License.
 *
 * @author Anatoly Fenric <anatoly@fenric.ru>
 * @copyright Copyright (c) 2018, Anatoly Fenric
 * @license https://github.com/sunrise-php/slugger/blob/master/LICENSE
 * @link https://github.com/sunrise-php/slugger
 */

namespace Sunrise\Slugger;

/**
 * Import classes
 */
use Sunrise\Slugger\Exception\InvalidArgumentException;
use Sunrise\Slugger\Exception\UnableToCreateTransliteratorException;
use Sunrise\Slugger\Exception\UnableToTransliterateException;
use Transliterator;

/**
 * Import functions
 */
use function in_array;
use function preg_replace;
use function str_replace;
use function strtr;
use function trim;

/**
 * Slugger
 */
class Slugger implements SluggerInterface
{

    /**
     * Default Basic ID
     *
     * @var string
     *
     * @link http://userguide.icu-project.org/transforms/general#TOC-Basic-IDs
     */
    protected const DEFAULT_BASIC_ID = 'Russian-Latin/BGN';

    /**
     * Transliterator
     *
     * @var Transliterator
     */
    protected $transliterator;

    /**
     * Replacements
     *
     * @var array<string, string>
     */
    protected $replacements = [];

    /**
     * Constructor of the class
     *
     * @param string|null $basicId
     * @param array<string, string> $replacements
     *
     * @throws InvalidArgumentException
     * @throws UnableToCreateTransliteratorException
     */
    public function __construct(?string $basicId = null, array $replacements = [])
    {
        // http://userguide.icu-project.org/transforms/general#TOC-Basic-IDs
        /** @var string */
        $basicId = $basicId ?? static::DEFAULT_BASIC_ID;
        if (!in_array($basicId, Transliterator::listIDs(), true)) {
            throw new InvalidArgumentException('Unknown Basic ID');
        }

        // http://userguide.icu-project.org/transforms/general#TOC-Compound-IDs
        $compoundIds = $basicId . '; Any-Latin; Latin-ASCII; Lower()';
        $transliterator = Transliterator::create($compoundIds);
        if ($transliterator === null) {
            throw new UnableToCreateTransliteratorException('Unable to create transliterator');
        }

        $this->transliterator = $transliterator;
        $this->replacements = $replacements;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnableToTransliterateException
     */
    public function slugify(string $string, string $separator = '-') : string
    {
        $result = $this->transliterator->transliterate($string);
        if ($result === false) {
            throw new UnableToTransliterateException('Unable to transliterate');
        }

        $result = strtr($result, $this->replacements);
        $result = str_replace(['"', "'"], '', $result);
        $result = preg_replace('/[^0-9A-Za-z]++/', $separator, $result);
        $result = trim($result, $separator);

        return $result;
    }
}
