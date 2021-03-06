<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014-2016, Ripa Club <ripaclub@gmail.com>
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

chdir(__DIR__);

if (!file_exists('../vendor/autoload.php')) {
    throw new \RuntimeException('vendor/autoload.php not found. Run a composer install.');
}
