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
}
