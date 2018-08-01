<?php
namespace micetm\conditions\widgets;

use yii\base\Widget;

class AddButton extends Widget
{
    public $disabled = false;

    public function run()
    {
        $text = <<<TXT
    <span class="glyphicon glyphicon-plus green vertical-middle add-condition"
        style="%s"><span>
TXT;
        return sprintf($text, $this->disabled ? "display:none" : "");
    }
}
