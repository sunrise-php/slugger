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
use Transliterator;
use Sunrise\Slugger\Exception\UnableToCreateTransliteratorException;
use Sunrise\Slugger\Exception\UnableToTransliterateException;

/**
 * Import functions
 */
use function preg_replace;
use function strtr;
use function trim;

/**
 * Slugger
 */
class Slugger implements SluggerInterface
{

    /**
     * Transliterator
     *
     * @var Transliterator
     */
    private $transliterator;

    /**
     * Replacements
     *
     * @var array<string,string>
     */
    private $replacements = [
        "'" => '', // <= <Ь>
        '"' => '', // <= <Ъ>
    ];

    /**
     * Constructor of the class
     *
     * @param string $basicId
     * @param array<string,string> $replacements
     *
     * @throws UnableToCreateTransliteratorException
     */
    public function __construct(?string $basicId = null, array $replacements = [])
    {
        // http://userguide.icu-project.org/transforms/general#TOC-Basic-IDs
        // http://userguide.icu-project.org/transforms/general#TOC-Compound-IDs
        $compoundIds = ($basicId ?? 'Russian-Latin/BGN') . '; Any-Latin; Latin-ASCII; Lower()';

        $transliterator = Transliterator::create($compoundIds, Transliterator::FORWARD);
        if (null === $transliterator) {
            throw new UnableToCreateTransliteratorException('Unable to create transliterator');
        }

        $this->transliterator = $transliterator;
        $this->replacements += $replacements;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnableToTransliterateException
     */
    public function slugify(string $string, string $separator = '-') : string
    {
        $result = $this->transliterator->transliterate($string);
        if (false === $result) {
            throw new UnableToTransliterateException('Unable to transliterate');
        }

        $result = strtr($result, $this->replacements);
        $result = preg_replace('/[^0-9A-Za-z]++/', $separator, $result);
        $result = trim($result, $separator);

        return $result;
    }
}
