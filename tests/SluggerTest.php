<?php

declare(strict_types=1);

namespace Sunrise\Slugger\Tests;

use PHPUnit\Framework\TestCase;
use Sunrise\Slugger\Exception\ExceptionInterface;
use Sunrise\Slugger\Exception\InvalidArgumentException;
use Sunrise\Slugger\Exception\UnableToCreateTransliteratorException;
use Sunrise\Slugger\Exception\UnableToTransliterateException;
use Sunrise\Slugger\Slugger;
use Sunrise\Slugger\SluggerInterface;

class SluggerTest extends TestCase
{
    private const RUSSIAN_LATIN_TRANSLITERATOR_BASIC_ID = 'Russian-Latin/BGN';
    private const CYRILLIC_LATIN_TRANSLITERATOR_BASIC_ID = 'Cyrillic-Latin';

    public function testContracts() : void
    {
        $slugger = new Slugger();

        $this->assertInstanceOf(SluggerInterface::class, $slugger);
    }

    public function testConstructorWithUnsupportedTransliteratorBasicId() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown Basic ID');

        new Slugger('Morrowind-Oblivion/KFC');
    }

    public function testSlugify() : void
    {
        $input = 'съешь ещё этих мягких французских булок, да выпей чаю';
        $output = 'syesh-yeshche-etikh-myagkikh-frantsuzskikh-bulok-da-vypey-chayu';
        $slugger = new Slugger();

        $this->assertSame($output, $slugger->slugify($input));
    }

    public function testSlugifyWithNumbers() : void
    {
        $input = '0123456789 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz';
        $output = '0123456789-abcdefghijklmnopqrstuvwxyz-abcdefghijklmnopqrstuvwxyz';
        $slugger = new Slugger();

        $this->assertSame($output, $slugger->slugify($input));
    }

    public function testSlugifyWithSeparator() : void
    {
        $input = '   А   Б   В   ';
        $output = 'a_b_v';
        $slugger = new Slugger();

        $this->assertSame($output, $slugger->slugify($input, '_'));
    }

    public function testSlugifyWithRussianLatinTransliteratorBasicId() : void
    {
        $input = 'съешь ещё этих мягких французских булок, да выпей чаю';
        $output = 'syesh-yeshche-etikh-myagkikh-frantsuzskikh-bulok-da-vypey-chayu';
        $slugger = new Slugger(self::RUSSIAN_LATIN_TRANSLITERATOR_BASIC_ID);

        $this->assertSame($output, $slugger->slugify($input));
    }

    public function testSlugifyWithCyrillicLatinTransliteratorBasicId() : void
    {
        $input = 'съешь ещё этих мягких французских булок, да выпей чаю';
        $output = 'ses-ese-etih-magkih-francuzskih-bulok-da-vypej-cau';
        $slugger = new Slugger(self::CYRILLIC_LATIN_TRANSLITERATOR_BASIC_ID);

        $this->assertSame($output, $slugger->slugify($input));
    }

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

    public function testPunctuations() : void
    {
        $input = 'С.Т.А.Л.К.Е.Р.';
        $output = 's-t-a-l-k-ye-r';
        $slugger = new Slugger(self::RUSSIAN_LATIN_TRANSLITERATOR_BASIC_ID);

        $this->assertSame($output, $slugger->slugify($input));
    }

    public function testExceptions() : void
    {
        $this->assertInstanceOf(ExceptionInterface::class, new InvalidArgumentException());
        $this->assertInstanceOf(\InvalidArgumentException::class, new InvalidArgumentException());

        $this->assertInstanceOf(ExceptionInterface::class, new UnableToCreateTransliteratorException());
        $this->assertInstanceOf(\RuntimeException::class, new UnableToCreateTransliteratorException());

        $this->assertInstanceOf(ExceptionInterface::class, new UnableToTransliterateException());
        $this->assertInstanceOf(\RuntimeException::class, new UnableToTransliterateException());
    }
}
