<div class="socials form">
<?php echo $this->Form->create('Social',array('enctype'=>'multipart/form-data')); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Social'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
              
                    $imgpath = '/files/social/';
                    echo $this->Html->image($imgpath . $this->request->data['Social']['icon'], array('alt' => 'Social Icon', 'width' => 100));
                 
		 echo $this->Form->input('icon',array('type'=>'file'));
		echo $this->Form->input('link');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Social.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Social.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Socials'), array('action' => 'index')); ?></li>
	</ul>
</div>
