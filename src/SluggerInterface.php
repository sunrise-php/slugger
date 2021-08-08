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
 * SluggerInterface
 */
interface SluggerInterface
{

    /**
     * Slugifies the given string
     *
     * @param string $string
     * @param string $separator
     *
     * @return string
     *
     * @throws Exception\ExceptionInterface
     */
    public function slugify(string $string, string $separator) : string;
}
