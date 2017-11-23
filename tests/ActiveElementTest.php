<?php
/**
 * Dida Framework  -- A Rapid Development Framework
 * Copyright (c) Zeupin LLC. (http://zeupin.com)
 *
 * Licensed under The MIT License.
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * use
 */
use \PHPUnit\Framework\TestCase;
use \Dida\Debug\Debug;
use \Dida\Html\ActiveElement;

/**
 * ActiveElementTest
 */
class ActiveElementTest extends TestCase
{


    public function test_1()
    {
        $input1 = new ActiveElement('input', 'type="text"');
        $input1->setID('id1')->setName('name1');
        $input2 = new ActiveElement('input', 'type="text"');
        $input2->setID('id2')->setName('name2');

        $form = new ActiveElement('form');

        $form->addChild($input1);  // 建立关联关系
        $form->addChild($input2);  // 建立关联关系

        $html = $form->build();
        echo PHP_EOL;
        echo $html;

        $exp = '<form>'
            . '<input type="text" id="id1" name="name1">'
            . '<input type="text" id="id2" name="name2">'
            . '</form>';
        $this->assertEquals($exp, $html);

        $input1->wrap("div")->setClass("class1 class2");
        $input2->wrap("div")->setClass("class3 class4");

        $html = $form->build();
        echo PHP_EOL;
        echo $html;

        $exp = '<form>'
            . '<div class="class1 class2"><input type="text" id="id1" name="name1"></div>'
            . '<div class="class3 class4"><input type="text" id="id2" name="name2"></div>'
            . '</form>';
        $this->assertEquals($exp, $html);
    }


    public function test_addAfter()
    {
        $father = ActiveElement::make('div')->setClass('father');

        $son1 = ActiveElement::make('div')->setID('son1');
        $son2 = ActiveElement::make('div')->setID('son2');

        $son1->addBefore()->setInnerHTML('aaa');

        $html = $son1->build();
        echo PHP_EOL;
        echo $html;

        $exp = 'aaa<div id="son1"></div>';
        $this->assertEquals($exp, $html);

        $father->addChild($son1);

        $html = $father->build();
        echo PHP_EOL;
        echo $html;

        $exp = '<div class="father">aaa<div id="son1"></div></div>';
        $this->assertEquals($exp, $html);
    }


    public function test_setProp()
    {
        echo ' ' . __METHOD__ . "\n";

        $ele = ActiveElement::make("div");
        $ele->setProp('aaa', 1)
            ->setProp('selected', true)
            ->setProp('checked', true);
        echo $ele->build();
    }
}
