<div class="row">
<div class="col-sm-3">
<div class="restaurantsTypes form">
<?php echo $this->Form->create('RestaurantsType'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Restaurants Type'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
</div>
<div class="col-sm-3">
<div class="actions action_menu">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('RestaurantsType.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('RestaurantsType.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Restaurants Types'), array('action' => 'index')); ?></li>
	</ul>
</div>
</div>
</div>