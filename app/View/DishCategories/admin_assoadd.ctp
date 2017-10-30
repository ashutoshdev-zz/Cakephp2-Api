<div class="row">
<div class="col-sm-3">
<div class="dishCategories form">
<?php echo $this->Form->create('DishCategory',array('type'=>'file')); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Dish Category'); ?></legend>
	<?php
		echo $this->Form->input('name',array('required' => true));
		echo $this->Form->input('isshow',array('type'=>'hidden','value'=>'1'));
		echo $this->Form->input('res_id', array('type' => 'hidden'));
                 echo $this->Form->input('image',array('type'=>'file','required' => true));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
</div>
<div class="col-sm-3">
<div class="actions action_menu">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Dish Categories'), array('action' => 'assoindex')); ?></li>
	</ul>
</div>
</div>
</div>
