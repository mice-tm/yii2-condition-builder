<?php


namespace micetm\conditions\widgets;

use micetm\conditions\models\constructor\conditions\Condition;
use yii\base\Widget;
use yii\bootstrap\Html;

class Operator extends Widget
{
    public $model;
    public $path;
    public $level = 0;
    public $position = 0;
    public $disabled = false;


    public function run()
    {

        $input = Html::dropDownList(
            $this->path,
            $this->model->operator ?? '',
            Condition::$operators,
            [
                'class' => 'form-control condition-operator',
                'disabled' => $this->disabled
            ]
        );

        $templateOperation = <<<TXT
<div class="col-sm-3 offset-{$this->level}">
    <div class="row">
        <div class="counter col-sm-2">
            <span class="number number-level-{$this->level}" ></span>
        </div>
        <div class="col-sm-5 field-condition-operator">
            <div class="form-group">
                %s
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-sm-1">%s</div>
    </div>
</div>
TXT;

        return sprintf(
            $templateOperation,
            $input,
            AddButton::widget([
                'disabled' => (bool) $this->model->attribute
            ])
        );
    }
}