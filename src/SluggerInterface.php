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
 * SluggerInterface
 *
 * @link http://userguide.icu-project.org/transforms/general
 */
interface SluggerInterface
{

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
	public function setTransliteratorId(string $transliteratorId) : void;

	/**
	 * Gets the transliterator ID
	 *
	 * @return string
	 */
	public function getTransliteratorId() : string;

	/**
	 * Gets supported transliterator IDs
	 *
	 * @return array
	 */
	public function getSupportedTransliteratorIds() : array;

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
	public function transliterate(string $string, string $compound) : string;

	/**
	 * Converts the given string to slug
	 *
	 * @param string $string
	 * @param string $delimiter
	 *
	 * @return string
	 */
	public function slugify(string $string, string $delimiter = '-') : string;
}
