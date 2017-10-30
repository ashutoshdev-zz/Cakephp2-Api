<h2>Users</h2>
<div class="table-responsive">
         <?php //print_r($orders); exit;
     echo $this->Form->create('User', array()); ?>
    <div class="col-lg-2"> 
       <?php echo  $this->Form->input('search',array('type'=>'text','class'=>'form-control','label'=>false,'placeholder'=>'Enter Username')); ?>
</div>       
   <div class="col-lg-4">
        <?php echo $this->Form->button('Search', array('class' => 'btn btn-default')); ?>
        &nbsp; &nbsp;
        <?php echo $this->Html->link('View All', array('controller' => 'users', 'action' => 'index', 'admin' => true), array('class' => 'btn btn-danger')); ?>  
            
    </div><br/><br/>
<table class="table table-striped table-bordered table-condensed table-hover">
    <tr>

        <th><?php echo $this->Paginator->sort('role');?></th>
        <th><?php echo $this->Paginator->sort('name');?></th>
        <th><?php echo $this->Paginator->sort('username');?></th>
        <th><?php echo $this->Paginator->sort('active');?></th>
        <th><?php echo $this->Paginator->sort('created');?></th>
        <th><?php echo $this->Paginator->sort('modified');?></th>
        <th class="actions">Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo h($user['User']['role']); ?></td>
        <td><?php echo h($user['User']['name']); ?></td>
        <td><?php echo h($user['User']['username']); ?></td>
        <td><?php echo h($user['User']['active']); ?></td>
        <td><?php echo h($user['User']['created']); ?></td>
        <td><?php echo h($user['User']['modified']); ?></td>
        <td class="actions">
            <?php echo $this->Html->link('View', array('action' => 'view', $user['User']['id']), array('class' => 'btn btn-default btn-xs')); ?>
            <?php echo $this->Html->link('Change Password', array('action' => 'password', $user['User']['id']), array('class' => 'btn btn-default btn-xs')); ?>
            <?php //echo $this->Html->link('Edit', array('action' => 'edit', $user['User']['id']), array('class' => 'btn btn-default btn-xs')); ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>
<br />

<?php echo $this->element('pagination-counter'); ?>

<?php echo $this->element('pagination'); ?>

<br />
<br />
