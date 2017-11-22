<?php
/**
 * Dida Framework  -- A Rapid Development Framework
 * Copyright (c) Zeupin LLC. (http://zeupin.com)
 *
 * Licensed under The MIT License.
 * Redistributions of files must retain the above copyright notice.
 */
require('D:/Projects/github/dida-autoloader--dev/src/Dida/Autoloader.php');
\Dida\Autoloader::init();
\Dida\Autoloader::addPsr4('Dida\\', 'D:/Projects/github/dida-html--dev/src/Dida');

require('D:/Projects/github/dida-debug--dev/src/Dida/Debug/Debug.php');

// require __DIR__ . '/../vendor/autoload.php';
require('D:/Projects/github/composer/vendor/autoload.php');