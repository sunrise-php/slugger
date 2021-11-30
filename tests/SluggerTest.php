<?php

declare(strict_types=1);

namespace Sunrise\Slugger\Tests;

/**
 * Import classes
 */
use PHPUnit\Framework\TestCase;
use Sunrise\Slugger\Exception\Exception;
use Sunrise\Slugger\Exception\ExceptionInterface;
use Sunrise\Slugger\Exception\UnableToCreateTransliteratorException;
use Sunrise\Slugger\Exception\UnableToTransliterateException;
use Sunrise\Slugger\Slugger;
use Sunrise\Slugger\SluggerInterface;

/**
 * SluggerTest
 */
class SluggerTest extends TestCase
{

    /**
     * @var string
     */
    private const RUSSIAN_LATIN_TRANSLITERATOR_BASIC_ID = 'Russian-Latin/BGN';

    /**
     * @var string
     */
    private const CYRILLIC_LATIN_TRANSLITERATOR_BASIC_ID = 'Cyrillic-Latin';

    /**
     * @return void
     */
    public function testContracts() : void
    {
        $slugger = new Slugger();

        $this->assertInstanceOf(SluggerInterface::class, $slugger);
    }

    /**
     * @return void
     */
    public function testConstructorWithUnsupportedTransliteratorBasicId() : void
    {
        $this->expectException(UnableToCreateTransliteratorException::class);
        $this->expectExceptionMessage('Unable to create transliterator');

        new Slugger('Morrowind-Oblivion/KFC');
    }

    /**
     * @return void
     */
    public function testSlugify() : void
    {
        $input = 'съешь ещё этих мягких французских булок, да выпей чаю';
        $output = 'syesh-yeshche-etikh-myagkikh-frantsuzskikh-bulok-da-vypey-chayu';
        $slugger = new Slugger();

        $this->assertSame($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testSlugifyWithNumbers() : void
    {
        $input = '0123456789 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz';
        $output = '0123456789-abcdefghijklmnopqrstuvwxyz-abcdefghijklmnopqrstuvwxyz';
        $slugger = new Slugger();

        $this->assertSame($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testSlugifyWithSeparator() : void
    {
        $input = '   А   Б   В   ';
        $output = 'a_b_v';
        $slugger = new Slugger();

        $this->assertSame($output, $slugger->slugify($input, '_'));
    }

    /**
     * @return void
     */
    public function testSlugifyWithRussianLatinTransliteratorBasicId() : void
    {
        $input = 'съешь ещё этих мягких французских булок, да выпей чаю';
        $output = 'syesh-yeshche-etikh-myagkikh-frantsuzskikh-bulok-da-vypey-chayu';
        $slugger = new Slugger(self::RUSSIAN_LATIN_TRANSLITERATOR_BASIC_ID);

        $this->assertSame($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testSlugifyWithCyrillicLatinTransliteratorBasicId() : void
    {
        $input = 'съешь ещё этих мягких французских булок, да выпей чаю';
        $output = 'ses-ese-etih-magkih-francuzskih-bulok-da-vypej-cau';
        $slugger = new Slugger(self::CYRILLIC_LATIN_TRANSLITERATOR_BASIC_ID);

        $this->assertSame($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testReplacements() : void
    {
        $input = 'У меня есть 1$ и 2€';
        $output = 'u-menya-yest-1-dollar-i-2-euro';
        $slugger = new Slugger(self::RUSSIAN_LATIN_TRANSLITERATOR_BASIC_ID, [
            '$' => ' dollar ',
            '€' => ' euro ',
        ]);

        $this->assertSame($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testPunctuations() : void
    {
        $input = 'С.Т.А.Л.К.Е.Р.';
        $output = 's-t-a-l-k-ye-r';
        $slugger = new Slugger(self::RUSSIAN_LATIN_TRANSLITERATOR_BASIC_ID);

        $this->assertSame($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testExceptions() : void
    {
        $this->assertInstanceOf(\Throwable::class, new Exception());
        $this->assertInstanceOf(ExceptionInterface::class, new Exception());

        $this->assertInstanceOf(ExceptionInterface::class, new UnableToCreateTransliteratorException());
        $this->assertInstanceOf(Exception::class, new UnableToCreateTransliteratorException());

        $this->assertInstanceOf(ExceptionInterface::class, new UnableToTransliterateException());
        $this->assertInstanceOf(Exception::class, new UnableToTransliterateException());
    }
}
