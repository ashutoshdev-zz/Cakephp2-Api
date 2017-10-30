<div class="row">
<div class="col-sm-3">
<div class="dishSubcats form">
<?php echo $this->Form->create('DishSubcat',array('type'=>'file')); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Dish Subcat'); ?></legend>
	<?php
                echo $this->Form->input('dish_catid', ['options' => $dishCategories, 'label' => 'Dish Category Name:']); 
		echo $this->Form->input('name',array('required' => true));
		echo $this->Form->input('isshow',array('type'=>'hidden','value'=>'1'));
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

		<li><?php echo $this->Html->link(__('List Dish Subcats'), array('action' => 'assoindex')); ?></li>
		<li><?php echo $this->Html->link(__('List Dish Categories'), array('controller' => 'dish_categories', 'action' => 'assoindex')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dish Category'), array('controller' => 'dish_categories', 'action' => 'assoadd')); ?> </li>
	</ul>
</div>
</div>
</div>
