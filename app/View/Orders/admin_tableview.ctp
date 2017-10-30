<h2>Order</h2>
<table class="table-striped table-bordered table-condensed table-hover">
    <tr>
        <td>Order Id</td>
        <td><?php echo h($order['TableReservation']['id']); ?></td>
    </tr>
    <tr>
        <td>First Name</td>
        <td><?php echo h($order['TableReservation']['fname']); ?></td>
    </tr>
    <tr>
        <td>Last Name</td>
        <td><?php echo h($order['TableReservation']['lname']); ?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><?php echo h($order['TableReservation']['email']); ?></td>
    </tr>
    <tr>
        <td>Phone</td>
        <td><?php echo h($order['TableReservation']['phone']); ?></td>
    </tr>
    <tr>
        <td>Address</td>
        <td><?php echo h($order['TableReservation']['address']); ?></td>
    </tr>
    <tr>
        <td>City</td>
        <td><?php echo h($order['TableReservation']['city']); ?></td>
    </tr>
    <tr>
        <td>Zip</td>
        <td><?php echo h($order['TableReservation']['zip']); ?></td>
    </tr>
    <tr>
        <td>Delivery Day</td>
        <td><?php echo h($order['TableReservation']['d_day']); ?></td>
    </tr>
    <tr>
        <td>Delivery Time</td>
        <td><?php echo h($order['TableReservation']['d_time']); ?></td>
    </tr>
    <tr>
        <td>Notes</td>
        <td><?php echo h($order['TableReservation']['notes']); ?></td>
    </tr>
    <tr>
        <td>No. of People</td>
        <td><?php echo h($order['TableReservation']['no_of_people']); ?></td>
    </tr>
    <tr>
        <td>Table No</td>
        <td><?php echo h($order['TableReservation']['table_no']); ?></td>
    </tr>
    <tr>
        <td>Created</td>
        <td><?php echo h($order['TableReservation']['created']); ?></td>
    </tr>
</table>
<br />
<h3>Actions</h3>
<?php echo $this->Html->link('Edit Order', array('action' => 'tableedit', $order['TableReservation']['id']), array('class' => 'btn btn-default')); ?>
<br />
<br />
<?php echo $this->Form->postLink('Delete Order', array('action' => 'tabledelete', $order['TableReservation']['id']), array('class' => 'btn btn-default btn-danger'), __('Are you sure you want to delete # %s?', $order['TableReservation']['id'])); ?>