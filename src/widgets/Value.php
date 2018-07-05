<?php


namespace micetm\conditions\widgets;


use kartik\widgets\DateTimePicker;
use micetm\conditions\models\constructor\attributes\MultipleAttribute;
use micetm\conditions\models\constructor\attributes\TimestampAttribute;
use yii\base\Widget;
use yii\bootstrap\Html;
use kartik\depdrop\DepDrop;
use yii\web\View;

class Value extends Widget
{
    public $model;
    public $path;
    public $level = 0;
    public $position = 0;
    public $disabled = false;
    public $attribute = null;
    public $dataUrl;

    public function run()
    {
        if ($this->attribute && $this->attribute instanceof MultipleAttribute) {
            return $this->renderList();
        } if ($this->attribute && $this->attribute instanceof TimestampAttribute) {
            return $this->renderTimestamp();
        } else {
            return $this->renderInput();
        }
    }

    private function renderTimestamp()
    {
        $input = DateTimePicker::widget([
            'name' => $this->path,
            'value' => \Yii::$app->formatter->asDatetime($this->model->value),
            'convertFormat' => true,
        ]);

        return <<<TXT
<div class="col-sm-3 field-condition-value">
    <div class="form-group">
        $input
        <div class="help-block"></div>
    </div>
</div>
TXT;
    }

    public function renderList()
    {
        $input = DepDrop::widget([
            'hashVarLoadPosition' => View::POS_END,
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options'=>[
                'pluginLoading' => false,
                    'hashVarLoadPosition' => View::POS_END,
                'pluginOptions'=>[
                    'multiple' => $this->attribute->multiple,
                    'allowClear'=>true,
                ]
            ],
            'name' => $this->path,
            'value' => $this->model->value,
            'disabled' => $this->disabled,
            'data' => array_combine($this->attribute->getData(), $this->attribute->getData()),
            'options' => [
                'id' => "value-$this->level-$this->position",
                'class' => "depdrop-value form-control comparison-value"
            ],
            'pluginOptions' => [
                'depends' => ["attribute-{$this->level}-{$this->position}"],
                'placeholder' => '...',
                'url' => $this->dataUrl,
                'multiple' => $this->attribute->multiple,
                'initDepends' => "attribute-$this->level-$this->position",
                'allowClear'=>true,
            ]
        ]);

        return <<<TXT
<div class="col-sm-3 field-condition-value">
    <div class="form-group">
        $input
        <div class="help-block"></div>
    </div>
</div>
TXT;
    }


    public function renderInput()
    {
        $input = Html::textInput(
            $this->path,
            $this->model->value,
            [
                'class' => 'form-control comparison-value',
                'disabled' => $this->disabled
            ]
        );

        return <<<TXT
<div class="col-sm-3 field-condition-value">
    <div class="form-group">
        $input
        <div class="help-block"></div>
    </div>
</div>
TXT;
    }
}