<div class="socials form">
<?php echo $this->Form->create('Social'); ?>
	<fieldset>
		<legend><?php echo __('Add Social'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('icon');
		echo $this->Form->input('link');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Socials'), array('action' => 'index')); ?></li>
	</ul>
</div>
