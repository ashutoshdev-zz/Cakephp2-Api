<div class="row">
<div class="col-sm-3">
<div class="dishCategories form">
<?php echo $this->Form->create('DishCategory',array('type'=>'file')); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Dish Category'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name',array('required' => true));
                ?>
                <img src="<?php echo $this->webroot;?>files/catimage/<?php echo $this->request->data['DishCategory']['image']; ?>" width="100" height="100"/>
                <input type="hidden" value="<?php echo $this->request->data['DishCategory']['image']; ?>" name="data[DishCategory][img]"/>	
	<?php 
        
        echo $this->Form->input('image',array('type'=>'file'));
        ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
</div>
<div class="col-sm-3">
<div class="actions action_menu">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('DishCategory.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('DishCategory.id')))); ?></li>
		<li><?php echo $this->Html->link(__('List Dish Categories'), array('action' => 'index')); ?></li>
	</ul>
</div>
</div>
</div>
