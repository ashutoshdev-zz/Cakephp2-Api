<?php echo $this->Html->css(array('bootstrap-editable.css', '/select2/select2.css'), 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script(array('bootstrap-editable.js', '/select2/select2.js'), array('inline' => false)); ?>
<script type="text/javascript" src="http://rajdeep.crystalbiltech.com/ecasnik/home/js/addtocart.js"></script>
<link rel="stylesheet" href="http://rajdeep.crystalbiltech.com/ecasnik/home/css/elegant_font/elegant_font.min.css" type="text/css">

<div class="content">
    <div class="header">
        <h1 class="page-title">Edit Admin</h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->Html->url('/admin/Restaurants/'); ?>">Restaurant Management</a> </li>
            <li class="active">Edit Admin</li>
        </ul>
    </div>
    <div class="main-content">  
        <p>
            <?php 
           // debug($restaurant);exit;
              $x = $this->Session->flash(); ?>
            <?php if ($x) { ?>
            <div class="alert success">
                <span class="icon"></span>
                <strong></strong><?php echo $x; ?>
            </div>
        <?php } ?>
        </p>

<!-- Content ================================================== -->

<div class="container margin_60_35">
    <div class="row">

        <div class="col-md-3">
            <p><a href="list_page.html" class="btn_side">Back to search</a></p>
            <div class="box_style_1">
                <ul id="cat_nav">
                    <?php foreach ($DishSubcat['DishSubcat'] as $discat) { ?>
                        <li><a href="#starters-<?php echo $discat['id']; ?>" class="active"><?php echo $discat['name']; ?> <span>(<?php echo $discat['cnt']; ?>)</span></a></li>
                    <?php } ?>
                </ul>
            </div><!-- End box_style_1 -->

            <div class="box_style_2 hidden-xs" id="help">
                <i class="icon_lifesaver"></i>
                <h4>Need <span>Help?</span></h4>
                <a href="tel://004542344599" class="phone">+45 423 445 99</a>
                <small>Monday to Friday 9.00am - 7.30pm</small>
            </div>
        </div><!-- End col-md-3 -->

        <div class="col-md-6">
            <div class="box_style_2" id="main_menu">
                <h2 class="inner">Menu</h2>
                <?php foreach ($DishSubcat['DishSubcat'] as $discat) { ?>
                    <h3 class="nomargin_top" id="starters-<?php echo $discat['id']; ?>"><?php echo $discat['name']; ?></h3>
                    <hr>
                    <table class="table table-striped cart-list ">
                        <thead>
                            <tr>
                                <th>
                                    Item
                                </th>
                                <th>
                                    Price
                                </th>
                                <th>
                                    Order
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($product as $pdata){;  if($discat['id']==$pdata['Product']['dishsubcat_id']) {?>
                            <tr>
                                <td style="width:75%">
                                    <h5><?php echo $pdata['Product']['name']; ?></h5>
                                    <p>
                                        <?php echo $pdata['Product']['description']; ?>
                                    </p>
                                </td>
                                <td>
                                    <strong>$ <?php echo $pdata['Product']['price']; ?></strong>
                                </td>
                                <td class="options">
                         <?php echo $this->Form->button('Add to Cart', array('class' => 'btn btn-primary addtocart', 'id' => 'addtocart', 'id' => $pdata['Product']['id']));?>
                                    <!--<i class="icon_plus_alt2"></i>-->
                                </td>
                            </tr>
                            <?php } }?>
                        </tbody>
                    </table>
                    <hr>
                <?php } ?>
            </div><!-- End box_style_1 -->
        </div><!-- End col-md-6 -->

        <div class="col-md-3" id="sidebar">
            <div class="theiaStickySidebar">
                <div id="cart_box" >
                    <h3>Your order <i class="icon_cart_alt pull-right"></i></h3>
                    <div id="added_items">
                       
                    </div>

                    <hr>
                    <div id="total_items"></div>                   
                    <hr>
                    <?php echo $this->Form->create('Order'); ?>
                    <?php 
                   if($loggeduser){ ?>
                    <button type="button" id='save_cart' value="Save table item">
                   <?php }?>
                   <?php echo $this->Form->end(); ?>
                </div><!-- End cart_box -->
            </div><!-- End theiaStickySidebar -->
        </div><!-- End col-md-3 -->
</div>
    </div><!-- End row -->
</div><!-- End container -->
<!-- End Content =============================================== -->
<script>
$(document).ready(function(){
    var pID = [];
    var pqty = [];
    var final_ar = {};
    var ar = [];
    var res_id = '<?php echo $restaurant['Restaurant']['id']; ?>';
    var table = '<?php echo $table; ?>';
    console.log(table);
    $('#save_cart').click(function(){
    $('#added_items .table_summary tr').each(function(){
        var pro_id = $(this).children().find('a').attr('id');
        
        var productID = pro_id.split('_');
        var productId = productID[0];
        pID.push(productId);
        var qty = $(this).children().find('strong').text();
        qty.split('x');
        var quantity = qty[0];
        pqty.push(quantity);
        //console.log(quantity);
        for(var i=0,len=pID.length; i < len ;i++) {
         final_ar[pID[i]] = pqty[i];
         
        }
    });
    var total = $('#total_items .pull-right').text();
    var t = total.split('$');
    console.log(t);
    var totals = t[1];
    console.log(final_ar);
    $.post( "<?php echo $this->webroot; ?>restaurants/cart_ajax", { prodata:final_ar,total:totals,res_id:res_id,tableNo:table},function(d) {
        console.log(d);
    });
    });
});
</script>