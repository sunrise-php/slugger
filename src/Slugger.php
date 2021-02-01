<?php declare(strict_types=1);

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
use function str_replace;
use function trim;

/**
 * Slugger
 */
class Slugger implements SluggerInterface
{

    /**
     * Transliterator instance
     *
     * @var Transliterator
     */
    private $transliterator;

    /**
     * Constructor of the class
     *
     * @param string $basicId
     *
     * @throws UnableToCreateTransliteratorException
     */
    public function __construct(string $basicId = 'Russian-Latin/BGN')
    {
        // http://userguide.icu-project.org/transforms/general#TOC-Basic-IDs
        // http://userguide.icu-project.org/transforms/general#TOC-Compound-IDs
        $compoundIds = $basicId . '; Any-Latin; Latin-ASCII; Lower(); [^\x20\x30-\x39\x41-\x5A\x61-\x7A] Remove';

        $transliterator = Transliterator::create($compoundIds, Transliterator::FORWARD);
        if (null === $transliterator) {
            throw new UnableToCreateTransliteratorException('Unable to create transliterator');
        }

        $this->transliterator = $transliterator;
    }

    /**
     * Converts the given string to slug
     *
     * @param string $string
     * @param string $delimiter
     *
     * @return string
     *
     * @throws UnableToTransliterateException
     */
    public function slugify(string $string, string $delimiter = '-') : string
    {
        $transliteratedString = $this->transliterator->transliterate($string);
        if (false === $transliteratedString) {
            throw new UnableToTransliterateException('Unable to transliterate');
        }

        $slug = preg_replace('/[\x20]{2,}/', ' ', $transliteratedString);
        $slug = trim($slug);
        $slug = str_replace(' ', $delimiter, $slug);

        return $slug;
    }
}
