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
 * Slugger
 */
class Slugger implements SluggerInterface
{

	/**
	 * Default transliterator ID
	 *
	 * @var string
	 */
	protected $transliteratorId = 'Russian-Latin/BGN';

	/**
	 * {@inheritDoc}
	 */
	public function setTransliteratorId(string $transliteratorId) : void
	{
		$supported = $this->getSupportedTransliteratorIds();

		if (! \in_array($transliteratorId, $supported))
		{
			throw new Exception\UnsupportedTransliteratorIdentifierException(
				\sprintf('The transliterator identifier "%s" is not supported', $transliteratorId)
			);
		}

		$this->transliteratorId = $transliteratorId;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTransliteratorId() : string
	{
		return $this->transliteratorId;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSupportedTransliteratorIds() : array
	{
		return \transliterator_list_ids();
	}

	/**
	 * {@inheritDoc}
	 */
	public function transliterate(string $string, string $compound) : string
	{
		$id = $this->getTransliteratorId();

		$compound = \sprintf('%s; %s', $id, $compound);

		$transliterator = \transliterator_create($compound);

		if (null === $transliterator)
		{
			throw new Exception\UnableToCreateTransliteratorException(
				\sprintf('Unable to create transliterator with compound "%s"', $compound)
			);
		}

		$transliterated = \transliterator_transliterate($transliterator, $string);

		if (false === $transliterated)
		{
			throw new Exception\UnableToTransliterateException(
				\sprintf('Unable to transliterate string with compound "%s"', $compound)
			);
		}

		return $transliterated;
	}

	/**
	 * {@inheritDoc}
	 */
	public function slugify(string $string, string $delimiter = '-') : string
	{
		$slug = $this->transliterate($string, 'Any-Latin; Latin-ASCII; Lower(); [^\x20\x41-\x5A\x61-\x7A] Remove');

		$slug = \preg_replace('/[\x20]{2,}/', ' ', $slug);

		$slug = \trim($slug);

		$slug = \str_replace(' ', $delimiter, $slug);

		return $slug;
	}
}
