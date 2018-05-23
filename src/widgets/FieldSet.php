<?php


namespace micetm\conditions\widgets;

use yii\helpers\ArrayHelper;
use yii\base\Widget;
use yii\helpers\Url;

class FieldSet extends Widget
{
    public $model;
    public $path;
    public $level = 0;
    public $position = 0;
    public $disabled = false;
    public $attribute = null;
    public $availableAttributes = [];
    public $comparisonUrl = '@constructor-comparison-url';
    public $valueUrl = '@constructor-value-url';

    public function run()
    {
        $path = str_replace(['[',']'], ['-','-'], "{$this->path}[{$this->position}]");
        $path = trim(str_replace('--', '-', $path), '-');

        $multiRowTemplate = <<<TXT
<div class="fieldset fieldset-{$path} col-sm-12 group-fieldset"
 data-level="{$this->level}" data-position="{$this->position}" data-path="{$this->path}[{$this->position}]">
    <div class="row">
        %s
    </div>
    <div class="row">
        %s
    </div>
</div>
TXT;

        $operator = Operator::widget([
            "model" => $this->model,
            "path" => "{$this->path}[{$this->position}][operator]",
            "level" => $this->level,
            "position" => $this->position,
//            "disabled" => $this->model->attribute
        ]);
        $attribute = Attribute::widget([
            "model" => $this->model,
            "path" => "{$this->path}[{$this->position}][attribute]",
            "level" => $this->level,
            "position" => $this->position,
            "disabled" => empty($this->model->attribute),
            "availableAttributes" => $this->availableAttributes
        ]);
        $availableComparisons = ArrayHelper::map($this->availableAttributes, 'key', 'comparisons');
        $comparison = Comparison::widget([
            "model" => $this->model,
            "path" => "{$this->path}[{$this->position}][comparison]",
            "level" => $this->level,
            "position" => $this->position,
            "disabled" => empty($this->model->attribute),
            "availableComparisons" => $availableComparisons,
            "dataUrl" => Url::to([$this->comparisonUrl]),
        ]);
        $value = Value::widget([
            "model" => $this->model,
            "path" => "{$this->path}[{$this->position}][value]",
            "attribute" => $this->availableAttributes[$this->model->attribute],
            "level" => $this->level,
            "position" => $this->position,
            "disabled" => empty($this->model->attribute),
            "dataUrl" => Url::to([$this->valueUrl]),
        ]);
        $deleteButton = DeleteButton::widget([
            "path" => $path
        ]);

        $conditions = '';
        if (count($this->model->conditionModels)) {
            $p = $this->path . "[{$this->position}][conditions]";
            $conditions = '';
            foreach ($this->model->conditionModels as $i => $conditionModel) {
                $conditions .= FieldSet::widget([
                    "model" => $conditionModel,
                    "position" => $i,
                    "path" => $p,
                    "level" => $this->level+1,
                    "availableAttributes" => $this->availableAttributes,
                    'comparisonUrl' => $this->comparisonUrl,
                    'valueUrl' => $this->valueUrl
                ]);
            }
        }
        return sprintf($multiRowTemplate, $operator.$attribute.$comparison.$value.$deleteButton, $conditions);
    }
}