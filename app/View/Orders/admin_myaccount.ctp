<h2>User</h2>
<?php //print_r($user); ?>
<table class="table-striped table-bordered table-condensed table-hover">
    <tr>
        <td>Id</td>
        <td><?php echo h($user['User']['id']); ?></td>
    </tr>
    <tr>
        <td>Role</td>
        <td><?php echo h($user['User']['role']); ?></td>
    </tr>
    <tr>
        <td>Username</td>
        <td><?php echo h($user['User']['username']); ?></td>
    </tr>
    <tr>
        <td>Name</td>
        <td><?php echo h($user['User']['name']); ?></td>
    </tr>
    <tr>
        <td>E-mail</td>
        <td><?php echo h($user['User']['email']); ?></td>
    </tr>
    <tr>
        <td>Active</td>
        <td><?php echo h($user['User']['active']); ?></td>
    </tr>
    <tr>
        <td>Created</td>
        <td><?php echo h($user['User']['created']); ?></td>
    </tr>
    <tr>
        <td>Modified</td>
        <td><?php echo h($user['User']['modified']); ?></td>
    </tr>
</table>

<br />
<br />

<h3>Actions</h3>

<br />

<?php echo $this->Html->link('Change Password', array('action' => 'password', $user['User']['id']), array('class' => 'btn btn-default')); ?> </li>

<br />
<br />