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
        $input1 = ActiveElement::make('input', 'type="text"')->setID('id1')->setName('name1');
        $input2 = ActiveElement::make('input', 'type="text"')->setID('id2')->setName('name2');

        $form = ActiveElement::make('form');

        $form->addChild($input1);  // 建立关联关系
        $form->addChild($input2);  // 建立关联关系

        echo PHP_EOL;
        echo $form->build();
        // <form>
        //   <input type="text" id="id1" name="name1">
        //   <input type="text" id="id2" name="name2">
        // </form>
        // -------------------------------------------------------------------------
        // 下面就开始玩高级的了 ^_^
        // -------------------------------------------------------------------------

        $input1->wrap("div")->setClass("class1 class2");  // 我们把input1加个wrapper
        $input2->wrap("div")->setClass("class3 class4");  // 我们把input2加个wrapper

        echo PHP_EOL;
        echo $form->build();  // form什么都不改，直接build
        // <form>
        //   <div class="class1 class2"><input type="text" id="id1" name="name1"></div>
        //   <div class="class3 class4"><input type="text" id="id2" name="name2"></div>
        // </form>
        // 看，是不是感觉很拽？
    }


    public function test_2()
    {
        $input1 = ActiveElement::make('input', 'type="text"')->setID('id1')->setName('name1');
        $input2 = ActiveElement::make('input', 'type="text"')->setID('id2')->setName('name2');

        $form = ActiveElement::make('form');

        $form->addChild($input1);  // 建立关联关系
        $form->addChild($input2);  // 建立关联关系

        echo PHP_EOL;
        echo $html = $form->build();


        // <form>
        //   <input type="text" id="id1" name="name1">
        //   <input type="text" id="id2" name="name2">
        // </form>

        $exp = '<form><input type="text" id="id1" name="name1"><input type="text" id="id2" name="name2"></form>';
        $this->assertEquals($exp, $html);

        // -------------------------------------------------------------------------
        // 下面就开始玩高级的了 ^_^
        // -------------------------------------------------------------------------

        $input1->wrap("div")->setClass("class1 class2");  // 我们把input1加个wrapper
        $input2->wrap("div")->setClass("class3 class4");  // 我们把input2加个wrapper

        echo PHP_EOL;
        echo $html = $form->build();

        // <form>
        //   <div class="class1 class2"><input type="text" id="id1" name="name1"></div>
        //   <div class="class3 class4"><input type="text" id="id2" name="name2"></div>
        // </form>
        // 看，是不是感觉很拽？

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
        echo "\n\n" . __METHOD__ . "\n";

        $ele = ActiveElement::make("div");
        $ele->setProp('aaa', 1)
            ->setProp('selected', true)
            ->setProp('checked', true);
        echo $ele->build();
    }


    public function test_issue1_1()
    {
        echo "\n\n" . __METHOD__ . "\n";

        $divMain = ActiveElement::make('div')->setID('divMain');

        $divBefore = ActiveElement::make('div')->setID('divBefore');
        $divAfter = ActiveElement::make('div')->setID('divAfter');

        // 验证 addBefore()和addAfter()
        $divMain->addBefore($divBefore);
        $divMain->addAfter($divAfter);
        $html = $divMain->build();
        echo "\n$html";
        $exp = '<div id="divBefore"></div><div id="divMain"></div><div id="divAfter"></div>';
        $this->assertEquals($exp, $html);

        // 验证 wrap()
        $wrapper1 = ActiveElement::make('div')->setID('wrapper1');
        $divMain->wrap($wrapper1);
        $html = $divMain->build();
        echo "\n$html";
        $exp = '<div id="wrapper1"><div id="divBefore"></div><div id="divMain"></div><div id="divAfter"></div></div>';
        $this->assertEquals($exp, $html);
    }


    public function test_issue1_2()
    {
        echo "\n\n" . __METHOD__ . "\n";

        // a系列
        $a1 = ActiveElement::make('div')->setID('a1');
        $a2 = ActiveElement::make('div')->setID('a2');
        $a3 = ActiveElement::make('div')->setID('a3');
        $a4 = ActiveElement::make('div')->setID('a4');

        $a1->addChild($a2)->addChild($a3)->addChild($a4);

        $html = $a1->build();
        echo "\n$html";

        $exp = '<div id="a1"><div id="a2"><div id="a3"><div id="a4"></div></div></div></div>';
        $this->assertEquals($exp, $html);

        // b系列
        $b1 = ActiveElement::make('div')->setID('b1');
        $b2 = ActiveElement::make('div')->setID('b2');
        $b3 = ActiveElement::make('div')->setID('b3');
        $b4 = ActiveElement::make('div')->setID('b4');

        $b1->addChild($b2)->addChild($b3)->addChild($b4);

        $html = $b1->build();
        echo "\n$html";

        $exp = '<div id="b1"><div id="b2"><div id="b3"><div id="b4"></div></div></div></div>';
        $this->assertEquals($exp, $html);

        // a3 抢 b3
        $a3->addAfter($b3);

        $html_a = $a1->build();
        $html_b = $b1->build();

        echo "\n$html_a";
        echo "\n$html_b";

        $this->assertEquals('<div id="a1"><div id="a2">'
            . '<div id="a3"><div id="a4"></div></div>'
            . '<div id="b3"><div id="b4"></div></div>'
            . '</div></div>', $html_a);

        $this->assertEquals('<div id="b1"><div id="b2"></div></div>', $html_b);
    }


    public function test_issue1_3()
    {
        echo "\n\n" . __METHOD__ . "\n";

        // a系列
        $a1 = ActiveElement::make('div')->setID('a1');
        $a2 = ActiveElement::make('div')->setID('a2');
        $a3 = ActiveElement::make('div')->setID('a3');
        $a4 = ActiveElement::make('div')->setID('a4');

        $a1->addChild($a2)->addChild($a3)->addChild($a4);

        // b系列
        $b1 = ActiveElement::make('div')->setID('b1');
        $b2 = ActiveElement::make('div')->setID('b2');
        $b3 = ActiveElement::make('div')->setID('b3');
        $b4 = ActiveElement::make('div')->setID('b4');

        $b1->addChild($b2)->addChild($b3)->addChild($b4);

        // 测试递归调用
        $a1->wrap($b1);

        $html_a = $a1->build();
        $html_b = $b1->build();

        echo "\n$html_a";
        echo "\n$html_b";

        $this->assertEquals(''
            . '<div id="b1">'
            . '<div id="a1"><div id="a2"><div id="a3"><div id="a4"></div></div></div></div>'
            . '</div>', $html_a);

        $this->assertTrue($b1->belongsTo === $a1);
        $this->assertFalse($a1->belongsTo === $b1);

        $this->assertEquals('<div id="b1"></div>', $html_b);
    }
}
