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
     * 1. 在 ActiveElement->addChild($element)时，$element只能是一个标签名或者是一个
     *    有build()方法的对象，否则就会抛出这个异常。
     */
    const INVALID_ELEMENT_TYPE = 1003;

}
