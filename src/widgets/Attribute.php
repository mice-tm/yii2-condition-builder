<?php


namespace micetm\conditions\widgets;


use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class Attribute extends Widget
{
    public $model;
    public $path;
    public $level = 0;
    public $position = 0;
    public $disabled = false;
    public $availableAttributes = [];


    public function run()
    {
        $items =  ArrayHelper::map($this->availableAttributes, 'key', 'key', 'level');

        $input = Html::dropDownList(
            $this->path,
            $this->model->attribute,
            $items,
            [
                'id' => "attribute-{$this->level}-{$this->position}",
                'class' => 'form-control condition-attribute',
                'disabled' => $this->disabled,
                'prompt' => '...',
            ]
        );

        return <<<TXT
<div class="col-sm-3 field-condition-attribute">
    <div class="form-group">
        $input
        <div class="help-block"></div>
    </div>
</div>
TXT;
    }
}