<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;

class CheckRow
{
    protected $id;
    protected $url;
    protected $name;

    public function __construct($id,$url,$name)
    {
        $this->id = $id;
        $this->url = $url;
        $this->name = $name;
    }

    protected function script()
    {
        return <<<SCRIPT

$('.grid-check-row').on('click', function () {

    // Your code.
    console.log($(this).data('id'));

});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='' href='{$this->url}?aid={$this->id}' data-id='{$this->id}'>{$this->name}</a>　";
    }

    public function __toString()
    {
        return $this->render();
    }
}