<section class="parallax-window" id="short" data-parallax="scroll" data-image-src="<?php echo $this->webroot . "restaurants/" . $restaurant['Restaurant']['banner']; ?>" data-natural-width="1400" data-natural-height="350">
    <div id="subheader">
        <div id="sub_content">
            <h1>Place your order</h1>
            <div class="bs-wizard">
                <div class="col-xs-4 bs-wizard-step active">
                    <div class="text-center bs-wizard-stepnum"><strong>1.</strong> Your details</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#0" class="bs-wizard-dot"></a>
                </div>
                <div class="col-xs-4 bs-wizard-step disabled">
                    <div class="text-center bs-wizard-stepnum"><strong>2.</strong> Payment</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="cart_2.html" class="bs-wizard-dot"></a>
                </div>

                <div class="col-xs-4 bs-wizard-step disabled">
                    <div class="text-center bs-wizard-stepnum"><strong>3.</strong> Finish!</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="cart_3.html" class="bs-wizard-dot"></a>
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
            <li>Address</li>
        </ul>
    </div>
</div><!-- Position -->

<!-- Content ================================================== -->
<?php echo $this->Form->create('Order', array('novalidate')); ?>
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
            </div><!-- End box_style_1 -->

            <div class="box_style_2 hidden-xs" id="help">
                <i class="icon_lifesaver"></i>
                <h4>Need <span>Help?</span></h4>
                <a href="tel://004542344599" class="phone">+45 423 445 99</a>
                <small>Monday to Friday 9.00am - 7.30pm</small>
            </div>

        </div><!-- End col-md-3 -->

        <div class="col-md-6">
            <div class="box_style_2" id="order_process">
                <h2 class="inner">Your order details</h2>
                <div class="form-group">

                    <?php echo $this->Form->input('first_name', array('class' => 'form-control', 'placeholder' => 'First name')); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('last_name', array('class' => 'form-control', 'placeholder' => 'Last name')); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('phone', array('class' => 'form-control', 'placeholder' => 'Telephone/mobile')); ?>	
                </div>

                <div class="form-group">
                    <?php echo $this->Form->input('email', array('class' => 'form-control', 'placeholder' => 'Email')); ?>
                </div>
                <div class="form-group">

                    <?php echo $this->Form->input('shipping_address', array('class' => 'form-control', 'placeholder' => 'Your full address')); ?>

                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?php echo $this->Form->input('shipping_city', array('class' => 'form-control', 'placeholder' => 'Your City')); ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?php echo $this->Form->input('shipping_zip', array('class' => 'form-control', 'placeholder' => 'Your postal code')); ?>	
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Delivery Date</label>
                            <select class="form-control" name="data[Order][delivery_schedule_day]" id="delivery_schedule_day">                                                  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Delivery time</label>
                            <select class="form-control" name="data[Order][delivery_schedule_time]" id="delivery_schedule_time">
                                <option value="" selected>Select time</option>
                                <option value="11.30am">11.30am</option>

                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">


                        <?php echo $this->Form->textarea('notes', array('class' => 'form-control', 'placeholder' => 'Notes for the restaurant')); ?>

                    </div>
                </div>
            </div><!-- End box_style_1 -->
        </div><!-- End col-md-6 -->

        <div class="col-md-3" id="sidebar">
            <div class="theiaStickySidebar">
                <div id="cart_box">
                    <h3>Your order <i class="icon_cart_alt pull-right"></i></h3>
                    <div id="added_items_chk">

                    </div>
                    <hr>
                    <input type="hidden" value="<?php echo $restaurant['Restaurant']['id']; ?>"  name="data[Order][restaurant_id]" >
                    <input type="hidden" value="<?php echo $loggeduser; ?>"  name="data[Order][uid]" >
                    <div class="row" id="options_2">

                        <?php if ($restaurant['Restaurant']['delivery'] == 1) { ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                <label> <input type="radio" id="deli" value="delivery"  name="delivery" >Delivery</label>
                            </div>
                        <?php } if ($restaurant['Restaurant']['takeaway'] == 1) { ?>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                                <label>  <input type="radio" value="takeaway"  id="tkway"   name="takeaway" >Take Away</label>
                            </div>
                        <?php } ?>
                    </div><!-- Edn options 2 -->                   
                    <input type="hidden" name="rid" id="reid" value="<?php echo $restaurant['Restaurant']['id']; ?>">
                    <div id="showdiv" style="display: none">
                        Pin Code<input type="text" value="" required="" id="chpin" name="pin">
                        <div id="pincheck">check</div>
                        <div id="dlchrg"> </div>
                    </div>
                    <hr>
                    <div id="total_items_chk"></div> 
                    <hr>
                    <button class="btn_full" style="display: none">Go to checkout</button>
                    <a class="btn_full_outline" href="<?php echo $this->webroot ?>restaurants/addresmenu/<?php echo $restaurant['Restaurant']['id']; ?>"><i class="icon-right"></i> Add other items</a>
                </div><!-- End cart_box -->
            </div><!-- End theiaStickySidebar -->
        </div><!-- End col-md-3 -->

    </div><!-- End row -->
</div><!-- End container -->
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    $.getJSON('http://rajdeep.crystalbiltech.com/ecasnik/api/shop/webtime', function (d) {
        html = '';
        html += '<option value="" selected>Select date</option>';
        $.each(d.day, function (index, value) {
            html += '<option value="' + value + '">' + value + '</option>';

        });
        $('#delivery_schedule_day').html(html);
        console.log(html);
    });

    var date = '<?php echo $day ?>';
    $('#delivery_schedule_day').off("change").on('change', function () {
        var a = $(this).val();
        var d=a.split('-');
        // alert(a);
        if (date == d[0]) {

            $.post('http://rajdeep.crystalbiltech.com/ecasnik/api/shop/webtime', {id: "1"}, function (data) {
                var daa = JSON.parse(data);
                htmlac = '';
                htmlac += '<option value="" selected>Select Time</option>';
                $.each(daa.time, function (i, v) {
                    htmlac += '<option value="' + v + '">' + v + '</option>';

                });

                $('#delivery_schedule_time').html(htmlac);

            });
        } else if (a == '') {
            htmlc = '';
            htmlc += '<option value="" selected>Select Time</option>';
            $('#delivery_schedule_time').html(htmlc);
        } else {
            $.getJSON('http://rajdeep.crystalbiltech.com/ecasnik/api/shop/webtime', function (da) {
                htmlb = '';
                htmlb += '<option value="" selected>Select Time</option>';
                $.each(da.time, function (index, value) {
                    htmlb += '<option value="' + value + '">' + value + '</option>';

                });
                console.log(htmlb);
                $('#delivery_schedule_time').html(htmlb);

            });
        }


    });
</script>