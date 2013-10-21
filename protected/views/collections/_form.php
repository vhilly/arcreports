<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'collections-form',
	'enableAjaxValidation'=>false,
)); 
$seller=CHtml::listData(Sellers::model()->findAll(),'id','name');
?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

        <?php echo $form->datePickerRow($model, 'date', array('append'=>'<i class="icon-calendar" style="cursor:pointer"></i>','class'=>'span2',
        'options'=>array( 'format' => 'yyyy-mm-dd')))?>

	<?php echo $form->dropDownListRow($model,'seller',$seller,array('class'=>'span3')); ?>

	<?php echo $form->textAreaRow($model,'value',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
</div>

<?php $this->endWidget(); ?>
