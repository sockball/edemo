<?php
namespace backend\widgets;
use Yii;
use yii\widgets\InputWidget;
use yii\helpers\HTML;

/**
  * 继承InputWidget后可获取 ActiveField中model 和 attribute值
*/
class FormWidget extends InputWidget
{
    public $params;
    public $method;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $method     = 'create' . ucfirst($this->method);
        $column     = $this->attribute;
        $controller = ucfirst($this->params['controller']);

        return $this->$method($this->params, $column, $controller);
    }

    public function createUpload($params, $column, $ucController)
    {
        $uploadHTML     = '';
        $url            = $this->model->$column;
        $previewUrl     = HTML::img($url, ['class' => 'logo-pre', 'id' => 'preview-' . $column]);
        $uploadHTML    .= <<<start
<div class='col-md-8'>
    <input type='text' value='{$url}' id='{$params["controller"]}-{$column}' readonly class='form-control' name='{$ucController}[{$column}]' aria-invalid='false'>
</div>
    <span class='btn btn-success fileinput-button'>
        <i class='glyphicon glyphicon-plus'></i>
        <span>{$params['hint']}</span>
        <input type='file' name='{$column}'>
    </span>
    <label class='col-md-2 control-label'></label>
    <div class='col-md-2 img-margin-top'>{$previewUrl}</div>
start;

        return $uploadHTML;
    }

    public function createRadio($params, $column, $ucController)
    {
        $radioHTML = '';
        $check = $this->model->$column;
        foreach ($params['radios'] as $key => $value)
        {
            $checked    = ($check == $key) ? 'checked' : '';  
            $radioHTML .= <<<start

<input type='radio' id='{$params["controller"]}-{$column}' name='{$ucController}[{$column}]' title='{$value}' value='{$key}' {$checked}>
<div class='layui-unselect layui-form-radio'>
    <i class='layui-anim layui-icon'></i>
    <div></div>
</div>
start;
        }
        return $radioHTML;
    }

}
