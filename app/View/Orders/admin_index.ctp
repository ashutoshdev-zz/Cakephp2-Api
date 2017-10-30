<h2>Orders</h2>
<div class="table-responsive">
    
    <?php
     if($loggedUserRole!='rest_admin'){ //print_r($orders); exit;
    echo $this->Form->create('Order', array());
    ?>
    <div class="col-lg-2"> 
<?php echo $this->Form->input('restaurant_id', ['options' => $res, 'label' => false, 'class' => 'form-control', 'empty' => 'Choose Store']); ?>
    </div>
    <div class="col-lg-2">
        <?php        
        echo $this->Form->input('filter', array(
            'label' => false,
            'class' => 'form-control',
            'options' => array(
                'id' => 'Order ID',
                'phone' => 'Phone',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
            ),
            'selected' => $all['filter']
        ));
        ?>
    </div>    
      <div class="col-lg-2">
        <?php       
        echo $this->Form->input('type', array(
            'label' => false,
            'class' => 'form-control',
            'options' => array(               
               
                 "1" => 'PickUp',
                 "0" => 'Delivery',
                 "2" => 'QR Code Order',
            ),'empty' => 'Choose Order Type',
            'selected' => $all['filter']
        ));
        ?>
    </div>
    <div class="col-lg-2">
<?php echo $this->Form->input('name', array('label' => false, 'id' => false, 'class' => 'form-control', 'value' => $all['name'])); ?>
    </div>
    <div class="col-lg-2">
        <input type="date" name="data[Order][date]" value=""/>
        
    </div>
     <div class="col-lg-2">
       
         <input type="date" name="data[Order][date1]" value=""/>
    </div>
    <div class="col-lg-4">
        <?php echo $this->Form->button('Search', array('class' => 'btn btn-danger')); ?>
        &nbsp; &nbsp;
<?php echo $this->Html->link('Reload', array('controller' => 'orders', 'action' => 'reset', 'admin' => true), array('class' => 'btn btn-default')); ?>
    </div><br/><br/>
    <?php }?>
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr>
            <th><?php echo $this->Paginator->sort('Order ID'); ?></th>
            <th><?php echo $this->Paginator->sort('Store Name'); ?></th>
            <th><?php echo $this->Paginator->sort('first_name'); ?></th>
            <th><?php echo $this->Paginator->sort('last_name'); ?></th>
            <th><?php echo $this->Paginator->sort('email'); ?></th>
            <th><?php echo $this->Paginator->sort('phone'); ?></th>
            <th><?php echo $this->Paginator->sort('Order Type'); ?></th>
<!--            <th><?php echo $this->Paginator->sort('billing_city'); ?></th>
          <th><?php echo $this->Paginator->sort('billing_zip'); ?></th>
          <th><?php echo $this->Paginator->sort('billing_state'); ?></th>
          <th><?php echo $this->Paginator->sort('billing_country'); ?></th>
          <th><?php echo $this->Paginator->sort('shipping_city'); ?></th>
          <th><?php echo $this->Paginator->sort('shipping_zip'); ?></th>
          <th><?php echo $this->Paginator->sort('shipping_state'); ?></th>
          <th><?php echo $this->Paginator->sort('shipping_country'); ?></th>
          <th><?php echo $this->Paginator->sort('weight'); ?></th>-->
            <th><?php echo $this->Paginator->sort('subtotal'); ?></th>
<!--            <th><?php echo $this->Paginator->sort('tax'); ?></th>
            <th><?php echo $this->Paginator->sort('shipping'); ?></th>-->
            <th><?php echo $this->Paginator->sort('total'); ?></th>
<!--            <th><?php //echo $this->Paginator->sort('status');  ?></th>-->
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th>Actions</th>
        </tr>
<?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo h($order['Order']['id']); ?></td>
                <td><?php echo h($order['Restaurant']['name']); ?></td>
                <td><?php echo h($order['Order']['first_name']); ?></td>
                <td><?php echo h($order['Order']['last_name']); ?></td>
                <td><?php echo h($order['Order']['email']); ?></td>
                <td><?php echo h($order['Order']['phone']); ?></td>
                <td><?php if ($order['Order']['delivery_status'] == 0) {
        echo "Delivery";
    } else if ($order['Order']['delivery_status'] == 1) {
        echo "PickUp";
    } else {
        echo "QR Code Order";
    } ?></td>
    <!--                <td><?php //echo h($order['Order']['billing_city']);  ?></td>
                <td><?php //echo h($order['Order']['billing_zip']);  ?></td>
                <td><?php //echo h($order['Order']['billing_state']);  ?></td>
                <td><?php //echo h($order['Order']['billing_country']);  ?></td>
                <td><?php //echo h($order['Order']['shipping_city']);  ?></td>
                <td><?php //echo h($order['Order']['shipping_zip']);  ?></td>
                <td><?php //echo h($order['Order']['shipping_state']);  ?></td>
                <td><?php //echo h($order['Order']['shipping_country']);  ?></td>
                <td><?php //echo h($order['Order']['weight']);  ?></td>-->
                <td><?php echo h($order['Order']['subtotal']); ?></td>
    <!--                <td><?php //echo h($order['Order']['tax']); ?></td>
                <td><?php //echo h($order['Order']['shipping']);  ?></td>-->
                <td><?php echo h($order['Order']['total']); ?></td>
    <!--                <td><?php //echo h($order['Order']['status']);  ?></td>-->
                <td><?php echo h($order['Order']['created']); ?></td>
                <td class="actions">
                        <?php echo $this->Html->link('View', array('action' => 'view', $order['Order']['id']), array('class' => 'btn btn-default btn-xs')); ?>
                        <?php echo $this->Html->link('Edit', array('action' => 'edit', $order['Order']['id']), array('class' => 'btn btn-default btn-xs')); ?>
                    <select name="dlsts" class="dlsts">
                        <?php if ($order['Order']['dl_status'] == 0) { ?>
                            <option value="<?php echo $order['Order']['uid'] . '-' . $order['Order']['id']; ?>-1" selected>Pending</option>
                        <?php } if ($order['Order']['dl_status'] == 1) { ?>
                            <option value="<?php echo $order['Order']['uid'] . '-' . $order['Order']['id']; ?>-1" selected>Confirmed</option>
                        <?php } else { ?>
                            <option value="<?php echo $order['Order']['uid'] . '-' . $order['Order']['id']; ?>-1">Confirmed</option>
                        <?php } if ($order['Order']['dl_status'] == 2) { ?>
                            <option value="<?php echo $order['Order']['uid'] . '-' . $order['Order']['id']; ?>-2" selected>Cancelled</option>
    <?php } else { ?>
                            <option value="<?php echo $order['Order']['uid'] . '-' . $order['Order']['id']; ?>-2">Cancelled</option>
    <?php } if ($order['Order']['dl_status'] == 3) { ?>
                            <option value="<?php echo $order['Order']['uid'] . '-' . $order['Order']['id']; ?>-3" selected>Delivered</option>
            <?php } else { ?>
                            <option value="<?php echo $order['Order']['uid'] . '-' . $order['Order']['id']; ?>-3">Delivered</option>
    <?php } ?>

                    </select>

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
<script type="text/javascript">
    $(".dlsts").change(function () {
        var a = $(this).val();
        $.post('http://rajdeep.crystalbiltech.com/ecasnik/admin/orders/dlstas', {id: a}, function (d) {
            console.log(d);
        });
        //alert(a);
    });
</script>