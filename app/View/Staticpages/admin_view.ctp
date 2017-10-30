

<div class="content">
    <div class="header">

        <h1 class="page-title">View Staticpage</h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->Html->url(array('controller' => 'Staticpages', 'action' => 'admin_index')); ?>">Staticpages Management</a> </li>
            <li class="active">View Staticpage</li>
        </ul>

    </div>
    <div class="main-content"> 
        <p>
            <?php $x=$this->Session->flash(); ?>
                    <?php if($x){ ?>
                    <div class="alert success">
                        <span class="icon"></span>
                    <strong>Success!</strong><?php echo $x; ?>
                    </div>
                    <?php } ?>
        </p>
        <div class="row">
            <div class="col-md-4">
                    <div class="form-group">
                        <label><h4>Position:</h4></label><br>
                        <?php echo h($staticpage['Staticpage']['position']); ?>
                    </div>
                    <div class="form-group">
                        <label><h4>Title:</h4></label><br>
                        <?php echo h($staticpage['Staticpage']['title']); ?>
                    </div>
                    <div class="form-group">
                        <label><h4>Image:</h4></label><br>
                        <?php echo $this->Html->image('../files/staticpage/'.$staticpage['Staticpage']['image'],
                           array('alt'=>'Staticpage Image','style'=>'height:150px;')); ?>
                    </div>
                    <div class="form-group">
                        <label><h4>Description:</h4></label><br>
                        <?php echo htmlspecialchars($staticpage['Staticpage']['description']); ?>
                    </div>
                    <div class="form-group">
                        <label><h4>Created:</h4></label><br>
                        <?php echo h($staticpage['Staticpage']['created']); ?>
                    </div>
                    <div class="form-group">
                        <label><h4>Status:</h4></label><br>
                       <?php  if($staticpage['Staticpage']['status']==1) { echo 'Active';}else{echo "Deactive";} ?>
                    </div>
            </div>
        </div>
    </div>
