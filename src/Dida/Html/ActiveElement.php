<?php
/**
 * Dida Framework  -- A Rapid Development Framework
 * Copyright (c) Zeupin LLC. (http://zeupin.com)
 *
 * Licensed under The MIT License.
 * Redistributions of files MUST retain the above copyright notice.
 */

namespace Dida\Html;

/**
 * ActiveElement
 */
class ActiveElement
{
    /**
     * Version
     */
    const VERSION = '20171122';

    /**
     * 布尔属性的列表
     *
     * @var array
     */
    protected $bool_prop_list = [
        'disabled'       => null,
        'readonly'       => null,
        'required'       => null,
        'hidden'         => null,
        'checked'        => null,
        'selected'       => null,
        'autofocus'      => null,
        'multiple'       => null,
        'formnovalidate' => null,
    ];

    /**
     * 自闭合元素表。
     *
     * @var array
     */
    protected $autoclose_element_list = [
        'input' => null,
        'br'    => null,
        'hr'    => null,
    ];

    /**
     * @var array
     */
    protected $props = [
        'type'  => null,
        'id'    => null,
        'class' => null,
        'name'  => null,
        'value' => null,
    ];

    /**
     * @var string
     */
    protected $tag = '';

    /**
     * @var boolean
     */
    protected $autoclose = false;

    /**
     * @var string
     */
    protected $opentag = '';

    /**
     * @var string
     */
    protected $innerHTML = '';

    /**
     * 本元素归属于哪个元素
     *
     * @var \Dida\HTML\ActiveElement
     */
    public $belongsTo = null;

    /**
     * 包装元素
     * @var \Dida\HTML\ActiveElement
     */
    protected $wrapper = null;

    /**
     * 前元素
     * @var \Dida\HTML\ActiveElement
     */
    protected $before = null;

    /**
     * 后元素
     * @var \Dida\HTML\ActiveElement
     */
    protected $after = null;

    /**
     * 子元素集合
     * @var array
     */
    protected $children = [];


    /**
     * 类初始化。
     *
     * @param string $tag
     * @param string $more
     */
    public function __construct($tag = null, $more = null)
    {
        if (!is_null($tag)) {
            $this->setTag($tag, $more);
        }
    }


    /**
     * 创建一个ActiveElement。
     *
     * @param string $tag
     * @param string $more
     *
     * @return \Dida\Html\ActiveElement
     */
    public static function &make($tag = null, $more = null)
    {
        $element = new ActiveElement();
        $element->setTag($tag, $more);

        return $element;
    }


    /**
     * 设置标签。
     *
     * @param string $tag    标签。
     * @param string $more   自定义的属性。
     */
    public function setTag($tag = null, $more = null)
    {
        if (is_null($tag)) {
            $this->tag = null;
            $this->opentag = '';
            $this->autoclose = false;
            return $this;
        }

        if (is_string($tag)) {
            $tag = trim($tag);

            // tag为空
            if ($tag === '') {
                $this->tag = null;
                $this->opentag = '';
                $this->autoclose = false;
                return $this;
            }

            // 普通元素
            $this->tag = $tag;
            if ($more) {
                $this->opentag = $this->tag . ' ' . trim($more);
            } else {
                $this->opentag = $this->tag;
            }

            // 是否是自闭合元素
            $this->autoclose = array_key_exists($tag, $this->autoclose_element_list);

            return $this;
        }

        // 其它情况抛异常
        throw new HtmlException('', HtmlException::INVALID_TAG_TYPE);
    }


    public function setType($type)
    {
        $this->props['type'] = $type;
        return $this;
    }


    public function setID($id)
    {
        $this->props['id'] = $id;
        return $this;
    }


    public function setName($name)
    {
        $this->props['name'] = $name;
        return $this;
    }


    public function setClass($class)
    {
        $this->props['class'] = $class;
        return $this;
    }


    public function setStyle($style)
    {
        $this->props['style'] = $style;
        return $this;
    }


    public function getType()
    {
        return $this->props['type'];
    }


    public function getID()
    {
        return $this->props['id'];
    }


    public function getName()
    {
        return $this->props['name'];
    }


    public function getClass()
    {
        return $this->props['class'];
    }


    public function getStyle()
    {
        return (isset($this->props['style'])) ? $this->props['style'] : null;
    }


    public function setProp($name, $value)
    {
        // 属性名是否合法
        if (!is_string($name)) {
            throw new HtmlException($name, HtmlException::INVALID_PROPERTY_NAME);
        }

        // 属性值是否合法
        if (!is_scalar($value) && !is_null($value)) {
            throw new HtmlException($name, HtmlException::INVALID_PROPERTY_VALUE);
        }

        // 属性名转小写
        $name = strtolower($name);

        // 如果值为null
        if (is_null($value)) {
            $this->removeProp($name);
            return $this;
        }

        // 如果是布尔型的属性
        if (array_key_exists($name, $this->bool_prop_list)) {
            if ($value) {
                $this->props[$name] = true;
            } else {
                unset($this->props[$name]);
            }
            return $this;
        }

        // 一般的属性，则设置属性值
        $this->props[$name] = $value;

        // 返回
        return $this;
    }


    public function getProp($name)
    {
        // 属性名是否合法
        if (!is_string($name)) {
            throw new HtmlException($name, HtmlException::INVALID_PROPERTY_NAME);
        }

        // 属性名转小写
        $name = strtolower($name);

        // 常用属性
        switch ($name) {
            case 'type':
            case 'id':
            case 'class':
            case 'name':
            case 'value':
                return $this->props[$name];
                break;
        }

        // 如果属性存在，返回属性值，不存在返回null
        if (array_key_exists($name, $this->props)) {
            return $this->props[$name];
        } else {
            return null;
        }
    }


    public function removeProp($name)
    {
        $name = strtolower($name);

        switch ($name) {
            case 'type':
            case 'id':
            case 'class':
            case 'name':
            case 'value':
                $this->props[$name] = null;
                break;
            default:
                unset($this->props[$name]);
        }

        return $this;
    }


    /**
     * 设置当前元素的innerHTML。
     *
     * 注意：依照对innerHTML的定义，执行本方法后，children会被重置为空数组。
     *
     * @param string $html
     * @return $this
     */
    public function setInnerHTML($html)
    {
        $this->innerHTML = $html;
        $this->children = [];
        return $this;
    }


    /**
     * 获取当前元素的innerHTML。
     *
     * @return string
     */
    public function getInnerHTML()
    {
        return $this->innerHTML . $this->buildChildren();
    }


    /**
     * 在本元素的外面包一个元素。
     *
     * @param string $tag
     *
     * @return \Dida\HTML\ActiveElement
     */
    public function &wrap($tag = 'div')
    {
        $this->wrapper = new \Dida\HTML\ActiveElement($tag);
        return $this->wrapper;
    }


    /**
     * 创建或拿到一个新元素。
     *
     * @param mixed $tag
     */
    protected function &addNew(&$element = null)
    {
        if (is_null($element) || is_string($element)) {
            // 如果element为null或者为字符串
            $ele = new \Dida\HTML\ActiveElement($element);
        } elseif (is_object($element) && is_a($element, __CLASS__)) {
            // 如果$element是个对象，且可以build()
            $ele = &$element;
        } else {
            // 其它情况就抛异常
            throw new HtmlException(null, HtmlException::INVALID_ELEMENT_TYPE);
        }

        // 设置ele元素归属于本元素
        $ele->belongsTo = $this;

        return $ele;
    }


    /**
     * 在本元素的前面插一个元素。
     *
     * @param string|null|\Dida\HTML\ActiveElement   $element
     *
     * @return \Dida\HTML\ActiveElement
     */
    public function &addBefore($element = null)
    {
        $ele = $this->addNew($element);
        $this->before = &$ele;
        return $ele;
    }


    /**
     * 在本元素的后面插一个元素。
     *
     * @param string|null|\Dida\HTML\ActiveElement   $element
     *
     * @return \Dida\HTML\ActiveElement
     */
    public function &addAfter($element = null)
    {
        $ele = $this->addNew($element);
        $this->after = &$ele;
        return $ele;
    }


    /**
     * 新增一个子节点。
     *
     * @param string|null|\Dida\HTML\ActiveElement   $element
     *
     * @return \Dida\HTML\ActiveElement
     */
    public function &addChild($element = null)
    {
        $ele = $this->addNew($element);
        $this->children[] = &$ele;
        return $ele;
    }


    /**
     * 构建属性集合
     */
    protected function buildProps()
    {
        $output = [];

        foreach ($this->props as $name => $value) {
            if (is_null($value)) {
                // do nothing
            } elseif ($value === true) {
                $output[] = ' ' . htmlspecialchars($name);
            } elseif ($name === 'style') {
                $output[] = ' style' . '="' . htmlspecialchars($string, ENT_COMPAT | ENT_HTML5) . '"';
            } else {
                $output[] = ' ' . htmlspecialchars($name) . '="' . htmlspecialchars($value) . '"';
            }
        }

        // result
        return implode('', $output);
    }


    /**
     * 构建子节点集合
     */
    protected function buildChildren()
    {
        /**
         * 如果没有子节点
         */
        if (empty($this->children)) {
            return '';
        }

        /**
         * 合并子节点
         */
        $output = [];
        foreach ($this->children as $element) {
            if ($element->belongsTo === $this) {
                $output[] = $element->build();
            }
        }
        return implode('', $output);
    }


    /**
     * 构建本元素
     */
    protected function buildMe()
    {
        // 如果没有设置tag，只要返回innerHTML即可。
        if (!$this->tag) {
            return $this->getInnerHTML();
        }

        // 如果是自闭合元素
        if ($this->autoclose) {
            return "<" . $this->opentag . $this->buildProps() . '>';
        }

        // 如果是普通元素
        return "<" . $this->opentag . $this->buildProps() . '>' . $this->getInnerHTML() . "</{$this->tag}>";
    }


    /**
     * 构建ActiveElement
     */
    public function build()
    {
        $output = [];

        // 前元素
        if (!is_null($this->before) && ($this->before->belongsTo === $this)) {
            $output[] = $this->before->build();
        }

        // 本元素
        $output[] = $this->buildMe();

        // 后元素
        if (!is_null($this->after) && ($this->after->belongsTo === $this)) {
            $output[] = $this->after->build();
        }

        // 本层：前元素+本元素+后元素
        $result = implode('', $output);

        // 是否有包装元素
        if (is_null($this->wrapper)) {
            return $result;
        } else {
            $this->wrapper->innerHTML = &$result;
            return $this->wrapper->build();
        }
    }
}
