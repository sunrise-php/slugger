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
 */
interface SluggerInterface
{

    /**
     * Converts the given string to slug
     *
     * @param string $string
     * @param string $delimiter
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function slugify(string $string, string $delimiter) : string;
}
