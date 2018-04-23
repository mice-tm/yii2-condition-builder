<?php


namespace micetm\conditions\widgets;

use yii\helpers\ArrayHelper;
use yii\base\Widget;

class FieldSet extends Widget
{
    public $model;
    public $path;
    public $level = 0;
    public $position = 0;
    public $disabled = false;
    public $attribute = null;
    public $availableAttributes = [];

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
            "availableComparisons" => $availableComparisons
        ]);
        $value = Value::widget([
            "model" => $this->model,
            "path" => "{$this->path}[{$this->position}][value]",
            "attribute" => $this->availableAttributes[$this->model->attribute],
            "level" => $this->level,
            "position" => $this->position,
            "disabled" => empty($this->model->attribute)
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
                    "availableAttributes" => $this->availableAttributes
                ]);
            }
        }
        return sprintf($multiRowTemplate, $operator.$attribute.$comparison.$value.$deleteButton, $conditions);
    }
}