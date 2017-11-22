<?php
/**
 * Dida Framework  -- A Rapid Development Framework
 * Copyright (c) Zeupin LLC. (http://zeupin.com)
 *
 * Licensed under The MIT License.
 * Redistributions of files must retain the above copyright notice.
 */

namespace Dida\Html;

/**
 * HtmlException
 */
class HtmlException extends \Exception
{
    /**
     * Version
     */
    const VERSION = '20171122';

    //////////////////////////////////////////////////////////
    // ActiveElement 类
    //////////////////////////////////////////////////////////

    /**
     * 属性名无效
     */
    const INVALID_PROPERTY_NAME = 1001;

    /**
     * 属性值无效
     */
    const INVALID_PROPERTY_VALUE = 1002;

}
