<?php
class TCKEditor extends CInputWidget{
	public $model;
	// options specific to ckeditor
	public $options = array();

	public function run(){
		$cs = Yii::app()->getClientScript();
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
		$cs->registerScriptFile($assets . '/ckeditor/ckeditor.js');

		$id;
		if($this->hasModel()){
			list($name, $id) = $this->resolveNameId();
			echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
		}else{
			$id=$this->getId();
			$this->name=$id;
			$defaults=array(
				'id'=>$id,
			);
			$htmlOptions=array_merge($defaults, $this->htmlOptions);
			echo CHtml::textArea($this->name, $this->value, $htmlOptions);
		}

		$joptions=CJavaScript::encode($this->options);
		$jscode='var ckInstance=CKEDITOR.replace( '.$id.', '.$joptions.' );';
		$innerCode='return function(){$("textarea#'.$id.'").html(ckInstance.getData());}';
		$jscode.='ckInstance.on("blur",(function(ckInstance){'.$innerCode.'})(ckInstance));';

		Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, $jscode);
	}
}