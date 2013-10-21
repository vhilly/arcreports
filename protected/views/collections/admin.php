<div class='well span10'>
<h1>Manage Collections</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
'id'=>'collections-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'date',
		'seller',
		'value',
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>
</div>
