<?php //$this->Html->addCrumb('Order Success'); ?>

<!-- SubHeader =============================================== -->
<section class="parallax-window" id="short" data-parallax="scroll" data-image-src="img/sub_header_cart.jpg" data-natural-width="1400" data-natural-height="350">
    <div id="subheader">
    	<div id="sub_content">
    	 <h1>Place your order</h1>
            <div class="bs-wizard">
                <div class="col-xs-4 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum"><strong>1.</strong> Your details</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="cart.html" class="bs-wizard-dot"></a>
                </div>
                               
                <div class="col-xs-4 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum"><strong>2.</strong> Payment</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="cart_2.html" class="bs-wizard-dot"></a>
                </div>
            
              <div class="col-xs-4 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum"><strong>3.</strong> Finish!</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#0" class="bs-wizard-dot"></a>
                </div>  
		</div><!-- End bs-wizard --> 
        </div><!-- End sub_content -->
	</div><!-- End subheader -->
</section><!-- End section -->
<!-- End SubHeader ============================================ -->

    <div id="position">
        <div class="container">
            <ul>
                <li><a href="#0">Home</a></li>
                <li><a href="#0">Store</a></li>
                <li>Sucess</li>
            </ul>
        </div>
    </div><!-- Position -->
<!-- Content ================================================== -->
<div class="container margin_60_35">
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
			<div class="box_style_2">
				<h2 class="inner">Order confirmed!</h2>
				<div id="confirm">
					<i class="icon_check_alt2"></i>
					<h3>Thank you!</h3>
					<p>
						Thank You for Your Order !
					</p>
				</div>
				<h4>Order Summary</h4>
				<table class="table table-striped nomargin">
				<tbody>
                                    <?php foreach($shop['OrderItem'] as $key=>$value) { ?>
				<tr>
					<td>
						<strong><?php echo $shop['OrderItem'][$key]['quantity']; ?>x</strong> <?php echo $shop['OrderItem'][$key]['name']; ?>
					</td>
					<td>
						<strong class="pull-right">$<?php echo$shop['OrderItem'][$key]['price']; ?></strong>
					</td>
				</tr>
                                    <?php }?>
				<tr>
					<td class="total_confirm">
						Quantity
					</td>
					<td class="total_confirm">
						<span class="pull-right"><?php echo $shop['Order']['quantity']?></span>
					</td>
				</tr>
				
				<tr>
					<td class="total_confirm">
						 Sub TOTAL
					</td>
					<td class="total_confirm">
						<span class="pull-right">$<?php echo $shop['Order']['subtotal']?></span>
					</td>
				</tr>
                                <tr>
					<td class="total_confirm">
						 Tax
					</td>
					<td class="total_confirm">
						<span class="pull-right">$<?php echo $shop['Order']['tax']?></span>
					</td>
				</tr>
				<tr>
					<td class="total_confirm">
						 TOTAL
					</td>
					<td class="total_confirm">
						<span class="pull-right">$<?php echo $shop['Order']['total']?></span>
					</td>
				</tr>
				</tbody>
				</table>
			</div>
		</div>
	</div><!-- End row -->
</div><!-- End container -->


