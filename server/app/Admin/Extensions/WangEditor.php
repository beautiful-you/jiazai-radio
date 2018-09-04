<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class WangEditor extends Field
{
	protected $view = 'wangEditor.editor';

	protected static $css = [
	'/packages/wangEditor/dist/css/wangEditor.min.css',
	];

	protected static $js = [
	'/packages/wangEditor/dist/js/wangEditor.min.js',
	];

	public function render()
	{
		$token = csrf_token();
		$this->script = <<<EOT

var editor = new wangEditor('{$this->id}');
editor.config.uploadImgFileName = 'image';
editor.config.uploadImgUrl = '/admin/api/image';
editor.config.uploadParams = {
        _token: '$token'
    };
    editor.create();

EOT;
		return parent::render();

	}
}

?>