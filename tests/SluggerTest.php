<?php declare(strict_types=1);

namespace Sunrise\Slugger\Tests;

/**
 * Import classes
 */
use PHPUnit\Framework\TestCase;
use Sunrise\Slugger\Exception\Exception;
use Sunrise\Slugger\Exception\UnableToCreateTransliteratorException;
use Sunrise\Slugger\Exception\UnableToTransliterateException;
use Sunrise\Slugger\Slugger;
use Sunrise\Slugger\SluggerInterface;
use RuntimeException;

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
    public function testConstructor() : void
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

        $this->assertEquals($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testSlugifyWithNumbers() : void
    {
        $input = '0123456789 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz';
        $output = '0123456789-abcdefghijklmnopqrstuvwxyz-abcdefghijklmnopqrstuvwxyz';
        $slugger = new Slugger();

        $this->assertEquals($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testSlugifyWithDelimiter() : void
    {
        $input = '   А   Б   В   ';
        $output = 'a_b_v';
        $slugger = new Slugger();

        $this->assertEquals($output, $slugger->slugify($input, '_'));
    }

    /**
     * @return void
     */
    public function testSlugifyWithRussianLatinTransliteratorBasicId() : void
    {
        $input = 'съешь ещё этих мягких французских булок, да выпей чаю';
        $output = 'syesh-yeshche-etikh-myagkikh-frantsuzskikh-bulok-da-vypey-chayu';
        $slugger = new Slugger(self::RUSSIAN_LATIN_TRANSLITERATOR_BASIC_ID);

        $this->assertEquals($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testSlugifyWithCyrillicLatinTransliteratorBasicId() : void
    {
        $input = 'съешь ещё этих мягких французских булок, да выпей чаю';
        $output = 'ses-ese-etih-magkih-francuzskih-bulok-da-vypej-cau';
        $slugger = new Slugger(self::CYRILLIC_LATIN_TRANSLITERATOR_BASIC_ID);

        $this->assertEquals($output, $slugger->slugify($input));
    }

    /**
     * @return void
     */
    public function testExceptions() : void
    {
        $this->assertInstanceOf(\RuntimeException::class, new Exception());
        $this->assertInstanceOf(Exception::class, new UnableToCreateTransliteratorException());
        $this->assertInstanceOf(Exception::class, new UnableToTransliterateException());
    }
}
