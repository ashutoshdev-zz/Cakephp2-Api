<div class="row">
<div class="col-sm-3">
<div class="dishCategories view">
<h2><?php echo __('Dish Category'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
                
		<dd>
			<?php echo h($dishCategory['DishCategory']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($dishCategory['DishCategory']['name']); ?>
			&nbsp;
		</dd>
                 <dd>
                    <dt><?php echo __('Image'); ?></dt>
                    <img src="<?php echo $this->webroot;?>files/catimage/<?php echo $dishCategory['DishCategory']['image']; ?>" width="100" height="100"/>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($dishCategory['DishCategory']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($dishCategory['DishCategory']['modified']); ?>
			&nbsp;
		</dd>
<!--		<dt><?php //echo __('Status'); ?></dt>
		<dd>
			<?php //echo h($dishCategory['DishCategory']['status']); ?>
			&nbsp;
		</dd>-->
	</dl>
</div>
</div>
<div class="col-sm-3">
<div class="actions action_menu">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Dish Category'), array('action' => 'assoedit', $dishCategory['DishCategory']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Dish Category'), array('action' => 'assodelete', $dishCategory['DishCategory']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $dishCategory['DishCategory']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Dish Categories'), array('action' => 'assoindex')); ?> </li>
		<li><?php echo $this->Html->link(__('New Dish Category'), array('action' => 'assoadd')); ?> </li>
	</ul>
</div>
</div>
</div>
