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
 * Element
 */
class Element
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
     * 包元素
     * @var \Dida\HTML\Element
     */
    protected $wrapper = null;

    /**
     * 前元素
     * @var \Dida\HTML\Element
     */
    protected $before = null;

    /**
     * 后元素
     * @var \Dida\HTML\Element
     */
    protected $after = null;

    /**
     * 子元素集合
     * @var array
     */
    protected $children = [];


    public function __construct($tag = null, $more = null)
    {
        if (!is_null($tag)) {
            $this->setTag($tag, $more);
        }
    }


    /**
     * 初始化。
     *
     * @param string $tag   标签。
     * @param boolean $autoclose   是否是自闭合。
     * @param string $more   自定义的属性。
     */
    public function setTag($tag = null, $more = null)
    {
        $this->tag = $tag;
        if ($this->tag) {
            if ($more) {
                $this->opentag = $this->tag . ' ' . trim($more);
            } else {
                $this->opentag = $this->tag;
            }
        } else {
            $this->opentag = '';
        }
        $this->autoclose = array_key_exists($tag, $this->autoclose_element_list);
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


    public function getID()
    {
        return $this->props('id');
    }


    public function getName()
    {
        return $this->props('name');
    }


    public function getClass()
    {
        return $this->props('class');
    }


    public function getStyle()
    {
        return $this->props('style');
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
     * @return \Dida\HTML\Element
     */
    public function &wrap($tag = 'div')
    {
        $this->wrapper = new HtmlElement($tag);
        return $this->wrapper;
    }


    /**
     * 在本元素的前面插一个元素。
     *
     * @param string $tag
     *
     * @return \Dida\HTML\Element
     */
    public function &insertBefore($tag = null)
    {
        $this->before = new HtmlElement($tag);
        return $this->before;
    }


    /**
     * 在本元素的后面插一个元素。
     *
     * @param string $tag
     *
     * @return \Dida\HTML\Element
     */
    public function &insertAfter($tag = null)
    {
        $this->after = new HtmlElement($tag);
        return $this->after;
    }


    /**
     * 新增一个子节点。
     *
     * @param  $tag
     * @return \Dida\HTML\Element
     */
    public function &addChild($tag = null)
    {
        $element = new HtmlElement($tag);
        $this->children[] = &$element;
        return $element;
    }


    /**
     * 构建元素的属性表达式
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
                $output[] = ' style' . '="' . htmlspecialchars($string, ENT_COMPAT | ENT_HTML401) . '"';
            } else {
                $output[] = ' ' . htmlspecialchars($name) . '="' . htmlspecialchars($value) . '"';
            }
        }

        // result
        return implode('', $output);
    }


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
            $output[] = $element->build();
        }
        return implode('', $output);
    }


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


    public function build()
    {
        $output = [];

        // 前元素
        if (!is_null($this->before)) {
            $output[] = $this->before->build();
        }

        // 本元素
        $output[] = $this->buildMe();

        // 后元素
        if (!is_null($this->after)) {
            $output[] = $this->after->build();
        }

        // 本层：前元素+本元素+后元素
        $result = implode('', $output);

        // 是否有wrapper
        if (is_null($this->wrapper)) {
            return $result;
        } else {
            $this->wrapper->innerHTML = &$result;
            return $this->wrapper->build();
        }
    }
}
