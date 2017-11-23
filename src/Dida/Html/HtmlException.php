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

    /**
     * 无效的元素类型
     * 1. addNew()时，$element只准是 <字符串><null><ActiveElement对象>
     */
    const INVALID_ELEMENT_TYPE = 1003;

    /**
     * 无效的Tag类型
     * 1. setTag()时，$tag只准是 <字符串><null>。
     */
    const INVALID_TAG_TYPE = 1004;

}
