<?php
namespace micetm\conditions\widgets;

use yii\base\Widget;

class DeleteButton extends Widget
{
    public $path = 0;

    public function run()
    {
        return <<<TXT
<div class="col-sm-1">
    <span data-for="{$this->path}" id="delete-{$this->path}"
    class="delete-condition glyphicon glyphicon-remove red vertical-middle"><span>
</div>
TXT;
    }
}
