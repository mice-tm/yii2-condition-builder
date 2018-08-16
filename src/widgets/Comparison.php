<?php
namespace micetm\conditions\widgets;

use kartik\depdrop\DepDrop;
use micetm\conditions\models\constructor\attributes\AbstractAttribute;
use yii\base\Widget;
use yii\web\View;

class Comparison extends Widget
{
    public $model;
    public $path;
    public $level = 0;
    public $position = 0;
    public $disabled = false;
    public $availableComparisons = [];
    public $dataUrl;



    public function run()
    {
        $data = ($this->model->comparison && $this->availableComparisons[$this->model->attribute])
            ? array_combine(
                $this->availableComparisons[$this->model->attribute],
                $this->availableComparisons[$this->model->attribute]
            )
            : [AbstractAttribute::EQUAL_TO_COMPARISON => AbstractAttribute::EQUAL_TO_COMPARISON];
        $input = DepDrop::widget([
            'hashVarLoadPosition' => View::POS_END,
            'name' => $this->path,
            'value' => $this->model->comparison,
            'disabled' => $this->disabled,
            'data' => $data,
            'options' => [
                'id' => "comparison-$this->level-$this->position",
                'class' => "depdrop-comparison form-control comparison-comparison",
            ],
            'pluginOptions' => [
                'depends' => ["attribute-$this->level-$this->position"],
                "loading" => false,
                'placeholder' => '...',
                'url' => $this->dataUrl,
                'initialize' => true,
                'initDepends' => "attribute-$this->level-$this->position",
            ]
        ]);

        return <<<TXT
<div class="col-sm-2 field-condition-comparison">
    <div class="form-group">
        $input
        <div class="help-block"></div>
    </div>
</div>
TXT;
    }
}
