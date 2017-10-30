<div class="dishSubcats form">
<?php echo $this->Form->create('DishSubcat',array('type'=>'file')); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Dish Subcat'); ?></legend>
	<?php
		echo $this->Form->input('id');
		 echo $this->Form->input('dish_catid', ['options' => $dishCategories, 'label' => 'Dish Category Name:']); 
		echo $this->Form->input('name',array('required' => true));
		//echo $this->Form->input('status');
	?>
                <img src="<?php echo $this->webroot;?>files/subcatimage/<?php echo $this->request->data['DishSubcat']['image']; ?>" width="100" height="100"/>
                <input type="hidden" value="<?php echo $this->request->data['DishSubcat']['image']; ?>" name="data[DishSubcat][img]"/>	
                <?php 
        
        echo $this->Form->input('image',array('type'=>'file'));
        ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'assodelete', $this->Form->value('DishSubcat.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('DishSubcat.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Dish Subcats'), array('action' => 'assoindex')); ?></li>
		<li><?php echo $this->Html->link(__('List Dish Categories'), array('controller' => 'dish_categories', 'action' => 'assoindex')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dish Category'), array('controller' => 'dish_categories', 'action' => 'assoadd')); ?> </li>
	</ul>
</div>
