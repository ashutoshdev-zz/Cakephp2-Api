<?php
//debug($shop['Order']);exit;
echo $this->Html->script(array('jquery.validate.js', 'additional-methods.js', 'shop_review.js'), array('inline' => false));
?>
<!-- Content ================================================== -->
<?php echo $this->Form->create('Order'); ?>
<div class="container margin_60_35">
    <div class="row">
        <div class="col-md-3">
            <div class="box_style_2 hidden-xs info">
                <h4 class="nomargin_top">Delivery time <i class="icon_clock_alt pull-right"></i></h4>
                <p>
                    Lorem ipsum dolor sit amet, in pri partem essent. Qui debitis meliore ex, tollit debitis conclusionemque te eos.
                </p>
                <hr>
                <h4>Secure payment <i class="icon_creditcard pull-right"></i></h4>
                <p>
                    Lorem ipsum dolor sit amet, in pri partem essent. Qui debitis meliore ex, tollit debitis conclusionemque te eos.
                </p>
            </div><!-- End box_style_2 -->
            <div class="box_style_2 hidden-xs" id="help">
                <i class="icon_lifesaver"></i>
                <h4>Need <span>Help?</span></h4>
                <a href="tel://004542344599" class="phone">+45 423 445 99</a>
                <small>Monday to Friday 9.00am - 7.30pm</small>
            </div>
        </div><!-- End col-md-3 -->
        <div class="col-md-6">
            <div class="box_style_2">
                <h2 class="inner">Payment methods</h2>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Customer Info</h3>
                    </div>
                    <div class="panel-body">
                        First Name: <?php echo $shop['Order']['first_name']; ?><br />
                        Last Name: <?php echo $shop['Order']['last_name']; ?><br />
                        E-mail: <?php echo $shop['Order']['email']; ?><br />
                        Phone: <?php echo $shop['Order']['phone']; ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Shipping Address</h3>
                    </div>
                    <div class="panel-body">
                        Shipping Address: <?php echo $shop['Order']['shipping_address']; ?><br />
                        Shipping City: <?php echo $shop['Order']['shipping_city']; ?><br />
                        Shipping Zip: <?php echo $shop['Order']['shipping_zip']; ?><br />
                        Delivery Schedule Day: <?php echo $shop['Order']['delivery_schedule_day']; ?><br />
                        Delivery Schedule Time: <?php echo $shop['Order']['delivery_schedule_time']; ?><br />
                        Notes: <?php echo $shop['Order']['notes']; ?><br />
                    </div>
                </div>
               
<!--                   <div class="payment_select">
                       <label><input type="radio" value="wallet"  name="payment_method" id="wallet" class="icheck">Use Wallet Money: $<?php echo $walletmoney['User']['loyalty_points']; ?></label>
                    <i class="icon-wallet"></i>
                    <?php //$x=$this->Session->flash(); echo $x; ?>
                </div>-->
                  <div class="payment_select" id="paypal">
                    <label><input type="radio" value="paypal" checked name="payment_method" class="icheck">Pay with paypal</label>
                </div>
                <div class="payment_select nomargin">
                    <label><input type="radio" value="cod" name="payment_method" class="icheck">Pay with cash</label>
                    <i class="icon_wallet"></i>
                </div>
                <div class="payment_select">
                    <label><input type="radio" value="creditcard"  name="payment_method" class="icheck">Credit card</label>
                    <i class="icon_creditcard"></i>
                </div>
                <!--                <div class="form-group">
                                    <label>Name on card</label>
                                    <input type="text" class="form-control" id="name_card_order" name="name_card_order" placeholder="First and last name">
                                </div>
                                <div class="col col-sm-4">
                
                                    <strong>Credit or debit card</strong>
                
                                    <br />
                
                <?php echo $this->Form->input('creditcard_number', array('label' => false, 'class' => 'form-control ccinput', 'type' => 'tel', 'maxLength' => 16, 'autocomplete' => 'off')); ?>
                
                                </div>
                                <div class="col col-sm-2">
                
                                    <strong>Card Security Code</strong>
                
                                    <a tabindex="9999" id="cscpop" role="button" data-placement="top" data-toggle="popover" data-trigger="focus" title="Card Security Code (CSC)" data-content="<small><strong>Visa, MasterCard, Discover</strong><br /><img src=<?php echo Router::url('/'); ?>img/visa.png><br / >The security code is the last three digits of the number that appears on the back of your card in the signature bar. <br /><br /><strong>American Express</strong><br /><img src=<?php echo Router::url('/'); ?>img/amex.png><br />The security code is the four digits located on the front of the card, on the right side.</small>"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a>
                
                                    <br />
                
                <?php echo $this->Form->input('creditcard_code', array('label' => false, 'class' => 'form-control', 'type' => 'tel', 'maxLength' => 4)); ?>
                
                                </div>
                            </div>
                
                            <br />
                
                            <div class="row">
                                <div class="col col-sm-3">
                <?php
                echo $this->Form->input('creditcard_month', array(
                    'label' => 'Expiration Month',
                    'class' => 'form-control',
                    'options' => array(
                        '01' => '01 - January',
                        '02' => '02 - February',
                        '03' => '03 - March',
                        '04' => '04 - April',
                        '05' => '05 - May',
                        '06' => '06 - June',
                        '07' => '07 - July',
                        '08' => '08 - August',
                        '09' => '09 - September',
                        '10' => '10 - October',
                        '11' => '11 - November',
                        '12' => '12 - December'
                    )
                ));
                ?>
                                </div>
                                <div class="col col-sm-3">
                <?php
                echo $this->Form->input('creditcard_year', array(
                    'label' => 'Expiration Year',
                    'class' => 'form-control',
                    'options' => array_combine(range(date('y'), date('y') + 10), range(date('Y'), date('Y') + 10))
                ));
                ?>
                                </div>
                            </div>-->
              
            </div><!-- End box_style_1 -->
        </div><!-- End col-md-6 -->
        <div class="col-md-3" id="sidebar">
            <div class="theiaStickySidebar">
                <div id="cart_box">
                    <h3>Your order <i class="icon_cart_alt pull-right"></i></h3>
                    <div id="added_items_chk"></div>
                    <hr>
                    <div class="row" id="options_2">
                        <?php if ($shop['Order']['delivery_status'] == 0) { ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                <label><input type="radio" value="delivery" checked  name="option_2" class="icheck">Delivery</label>
                            </div>
                        <?php } else { ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                <label><input type="radio" value="takeaway" checked name="option_2" class="icheck">Take Away</label>
                            </div>
                        <?php } ?>
                    </div><!-- Edn options 2 -->
                    <hr>
                    <div id="total_items_chk"></div> 
                    <hr>
                    <button class="btn_full" >Confirm your order</a>
                </div><!-- End cart_box -->
            </div><!-- End theiaStickySidebar -->
        </div><!-- End col-md-3 -->
    </div><!-- End row -->
</div><!-- End container -->
<?php echo $this->Form->end(); ?>