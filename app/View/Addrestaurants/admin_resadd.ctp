<h2>Add Restaurant User</h2>

<br />

<div class="row">
    <div class="col-sm-4">

        <?php echo $this->Form->create('User');?>
    
       <?php echo $this->Form->input('role', array('class' => 'form-control', 'options' => array('rest_admin'=> 'Store User'))); ?>
        <br />
        <?php echo $this->Form->input('name', array('class' => 'form-control','label'=>'Restaurant Name','required' => true)); ?>
        <br />
        <?php echo $this->Form->input('username', array('class' => 'form-control','placeholder'=>'E-mail','type'=>'email','required' => true)); ?>
        <br />
        <?php echo $this->Form->input('password', array('class' => 'form-control','required' => true)); ?>
        <br />
        <?php echo $this->Form->input('active', array('type' => 'checkbox','required' => true)); ?>
        <br />
        <?php echo $this->Form->button('Submit', array('class' => 'btn btn-primary')); ?>
        <?php echo $this->Form->end(); ?>

    </div>
</div>