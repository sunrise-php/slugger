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
 * Import functions
 */
use function in_array;
use function preg_replace;
use function sprintf;
use function str_replace;
use function transliterator_create;
use function transliterator_list_ids;
use function transliterator_transliterate;
use function trim;

/**
 * Slugger
 *
 * @link http://userguide.icu-project.org/transforms/general
 */
class Slugger implements SluggerInterface
{

    /**
     * The transliterator ID
     *
     * @var string
     */
    protected $transliteratorId = 'Russian-Latin/BGN';

    /**
     * Sets the transliterator ID
     *
     * @param string $transliteratorId
     *
     * @return void
     *
     * @throws Exception\UnsupportedTransliteratorIdentifierException
     *
     * @link http://userguide.icu-project.org/transforms/general#TOC-Basic-IDs
     */
    public function setTransliteratorId(string $transliteratorId) : void
    {
        $supportedTransliteratorIds = $this->getSupportedTransliteratorIds();
        if (!in_array($transliteratorId, $supportedTransliteratorIds)) {
            throw new Exception\UnsupportedTransliteratorIdentifierException(
                sprintf('The transliterator ID "%s" is not supported', $transliteratorId)
            );
        }

        $this->transliteratorId = $transliteratorId;
    }

    /**
     * Gets the transliterator ID
     *
     * @return string
     */
    public function getTransliteratorId() : string
    {
        return $this->transliteratorId;
    }

    /**
     * Gets supported transliterator IDs
     *
     * @return string[]
     */
    public function getSupportedTransliteratorIds() : array
    {
        return transliterator_list_ids();
    }

    /**
     * Transliterates the given string using the given compound
     *
     * @param string $string
     * @param string $compound
     *
     * @return string
     *
     * @throws Exception\UnableToCreateTransliteratorException
     * @throws Exception\UnableToTransliterateException
     */
    public function transliterate(string $string, string $compound) : string
    {
        $compound = $this->getTransliteratorId() . '; ' . $compound;
        $transliterator = transliterator_create($compound);
        if (null === $transliterator) {
            throw new Exception\UnableToCreateTransliteratorException(
                sprintf('Unable to create transliterator with compound "%s"', $compound)
            );
        }

        $transliterated = transliterator_transliterate($transliterator, $string);
        if (false === $transliterated) {
            throw new Exception\UnableToTransliterateException(
                sprintf('Unable to transliterate string with compound "%s"', $compound)
            );
        }

        return $transliterated;
    }

    /**
     * Converts the given string to slug
     *
     * @param string $string
     * @param string $delimiter
     *
     * @return string
     *
     * @throws Exception\UnableToCreateTransliteratorException
     * @throws Exception\UnableToTransliterateException
     */
    public function slugify(string $string, string $delimiter = '-') : string
    {
        $compound = 'Any-Latin; Latin-ASCII; Lower(); [^\x20\x30-\x39\x41-\x5A\x61-\x7A] Remove';

        $slug = $this->transliterate($string, $compound);
        $slug = preg_replace('/[\x20]{2,}/', ' ', $slug);
        $slug = trim($slug);
        $slug = str_replace(' ', $delimiter, $slug);

        return $slug;
    }
}
