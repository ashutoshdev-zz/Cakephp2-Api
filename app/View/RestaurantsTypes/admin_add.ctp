<div class="row">
<div class="col-sm-3">
<div class="restaurantsTypes form">
<?php echo $this->Form->create('RestaurantsType'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Restaurants Type'); ?></legend>
	<?php
		echo $this->Form->input('name',array('required' => true));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
</div>
<div class="col-sm-3">
<div class="actions action_menu">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Restaurants Types'), array('action' => 'index')); ?></li>
	</ul>
</div>
</div>

</div>

