<?php

namespace Sunrise\Slugger\Tests;

use PHPUnit\Framework\TestCase;
use Sunrise\Slugger\Exception\Exception;
use Sunrise\Slugger\Exception\UnableToCreateTransliteratorException;
use Sunrise\Slugger\Exception\UnableToTransliterateException;
use Sunrise\Slugger\Exception\UnsupportedTransliteratorIdentifierException;
use Sunrise\Slugger\Slugger;
use Sunrise\Slugger\SluggerInterface;

class SluggerTest extends TestCase
{
	private const RUSSIAN_LATIN_TRANSLITERATOR_ID = 'Russian-Latin/BGN';
	private const CYRILLIC_LATIN_TRANSLITERATOR_ID = 'Cyrillic-Latin';

	public function testConstructor()
	{
		$slugger = new Slugger();

		$this->assertInstanceOf(SluggerInterface::class, $slugger);
	}

	public function testTransliteratorId()
	{
		$slugger = new Slugger();

		$slugger->setTransliteratorId(self::RUSSIAN_LATIN_TRANSLITERATOR_ID);
		$this->assertEquals(self::RUSSIAN_LATIN_TRANSLITERATOR_ID, $slugger->getTransliteratorId());

		$slugger->setTransliteratorId(self::CYRILLIC_LATIN_TRANSLITERATOR_ID);
		$this->assertEquals(self::CYRILLIC_LATIN_TRANSLITERATOR_ID, $slugger->getTransliteratorId());
	}

	public function testDefaultTransliteratorId()
	{
		$slugger = new Slugger();

		$this->assertEquals(self::RUSSIAN_LATIN_TRANSLITERATOR_ID, $slugger->getTransliteratorId());
	}

	public function testUnsupportedTransliterationId()
	{
		$this->expectException(UnsupportedTransliteratorIdentifierException::class);

		$slugger = new Slugger();

		$slugger->setTransliteratorId('Morrowind-Oblivion/KFC');
	}

	public function testSupportedTransliteratorIds()
	{
		$slugger = new Slugger();

		$this->assertEquals(\transliterator_list_ids(), $slugger->getSupportedTransliteratorIds());
	}

	public function testTransliterateRussianLatin()
	{
		$input = 'съешь ещё этих мягких французских булок, да выпей чаю';
		$output = 's”yesh’ yeshche etikh myagkikh frantsuzskikh bulok, da vypey chayu';

		$slugger = new Slugger();

		$slugger->setTransliteratorId(self::RUSSIAN_LATIN_TRANSLITERATOR_ID);

		$this->assertEquals($output, $slugger->transliterate($input, 'Any-Latin; Latin-ASCII; Publishing'));
	}

	public function testTransliterateCyrillicLatin()
	{
		$input = 'съешь ещё этих мягких французских булок, да выпей чаю';
		$output = 's”es’ ese etih magkih francuzskih bulok, da vypej cau';

		$slugger = new Slugger();

		$slugger->setTransliteratorId(self::CYRILLIC_LATIN_TRANSLITERATOR_ID);

		$this->assertEquals($output, $slugger->transliterate($input, 'Any-Latin; Latin-ASCII; Publishing'));
	}

	public function testSlugifyRussianLatin()
	{
		$input = 'съешь ещё этих мягких французских булок, да выпей чаю';
		$output = 'syesh-yeshche-etikh-myagkikh-frantsuzskikh-bulok-da-vypey-chayu';

		$slugger = new Slugger();

		$slugger->setTransliteratorId(self::RUSSIAN_LATIN_TRANSLITERATOR_ID);

		$this->assertEquals($output, $slugger->slugify($input));
	}

	public function testSlugifyCyrillicLatin()
	{
		$input = 'съешь ещё этих мягких французских булок, да выпей чаю';
		$output = 'ses-ese-etih-magkih-francuzskih-bulok-da-vypej-cau';

		$slugger = new Slugger();

		$slugger->setTransliteratorId(self::CYRILLIC_LATIN_TRANSLITERATOR_ID);

		$this->assertEquals($output, $slugger->slugify($input));
	}

	public function testSlugifyWithDelimiter()
	{
		$input = '   А   Б   В   ';
		$output = 'a_b_v';

		$slugger = new Slugger();

		$slugger->setTransliteratorId(self::RUSSIAN_LATIN_TRANSLITERATOR_ID);

		$this->assertEquals($output, $slugger->slugify($input, '_'));
	}

	public function testTransliterateWithInvalidCompound()
	{
		$this->expectException(UnableToCreateTransliteratorException::class);

		$slugger = new Slugger();

		$slugger->transliterate('', 'UndefinedCommand()');
	}

	public function testExceptions()
	{
		$this->assertInstanceOf(\RuntimeException::class, new Exception(''));
		$this->assertInstanceOf(Exception::class, new UnableToCreateTransliteratorException(''));
		$this->assertInstanceOf(Exception::class, new UnableToTransliterateException(''));
		$this->assertInstanceOf(Exception::class, new UnsupportedTransliteratorIdentifierException(''));
	}
}
