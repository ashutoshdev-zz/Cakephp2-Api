<!-- SubHeader =============================================== -->
<section class="parallax-window" data-parallax="scroll" data-image-src="<?php echo $this->webroot; ?>files/restaurants/<?php echo $restaurant['Restaurant']['banner']; ?>" data-natural-width="1400" data-natural-height="470">
    <div id="subheader">
        <div id="sub_content">
            <div id="thumb"><img src="<?php echo $this->webroot; ?>files/restaurants/<?php echo $restaurant['Restaurant']['logo']; ?>" alt=""></div>
            <div class="rating">
                        <?php $i=$restaurant['Restaurant']['review_avg'];
                                        
                                        for($j=0;$j<$i;$j++){
                                        ?>

                <i class="icon_star voted"></i>

                                        <?php } for($h=0;$h<5-$i;$h++){?>  
                <i class="icon_star"></i>
                                        <?php } ?>
                (<small><a href="<?php echo $this->webroot ?>restaurants/review/<?php echo $restaurant['Restaurant']['id'] ?>"><?php echo $restaurant['Restaurant']['review_count']; ?> reviews</a></small>)

            </div>
            <h1><?php echo $restaurant['Restaurant']['name']; ?></h1>
            <div><em><?php foreach($RestaurantsType as $rt) {
                echo $rt['RestaurantsType']['name']; 
                echo "<br/>";
                
            } ?></em></div>
            <div><i class="icon_pin"></i><?php echo $restaurant['Restaurant']['address']; ?><?php //echo $restaurant['Restaurant']['delivery_fee']; ?></div>
        </div><!-- End sub_content -->
    </div><!-- End subheader -->
</section><!-- End section -->
<!-- End SubHeader ============================================ -->

<div id="position">
    <div class="container">
        <ul>
            <li><a href="#0">Home</a></li>
            <li><a href="#0">Store</a></li>
            <li>Menu Details</li>
        </ul>
    </div>
</div><!-- Position -->

<!-- Content ================================================== -->
<div class="container margin_60_35">
    <div class="row">

        <div class="col-md-3">
            <p><a href="<?php echo $this->webroot ?>" class="btn_side">Back to search</a></p>
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
                            foreach($product as $pdata){  if($discat['id']==$pdata['Product']['dishsubcat_id']) {?>
                        <tr>
                            <td style="width:75%">
                                <h5><?php echo $pdata['Product']['name']; ?></h5>
<!--                                <h5><?php //echo unserialize($pdata['Product']['pro_id']); ?></h5>
                                <h5><?php //echo unserialize($pdata['Product']['alergi']); ?></h5>-->
                                <p>
                                        <?php echo $pdata['Product']['description']; ?>
                                </p>
                            </td>
                            <td>
                                <strong>$ <?php echo $pdata['Product']['price']; ?>(Box price:$<?php  if($pdata['Product']['box']){echo $pdata['Product']['box']; }else {echo "0";}  ?>)</strong>
                            </td>
                            <td class="options">
                         <?php echo $this->Form->button('Add to Cart', array('class' => 'btn btn-primary addtocart', 'id' => 'addtocart', 'id' => $pdata['Product']['id']));?>
                                <!--<i class="icon_plus_alt2"></i>-->
                            </td>
                        </tr>
                      <?php   if(($pdata['Product']['Alergy']) || ($pdata['Product']['asp'])) { ?>
                        
                    <div class="modal fade" id="myModal-<?php echo $pdata['Product']['id'] ; ?>" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="box_style_2" id="main_menu">
                                    <?php if(!empty($pdata['Product']['asp'])) {?>
                                    <h2 class="inner">Associated product</h2>


                                    <hr>
                                  <?php    //print_r($pdata['Product']['asp']); 
                                  foreach($pdata['Product']['asp'] as $asp){ ?>
                                       
                                <h5><?php echo $asp['Product']['name']; ?></h5>
                               
                                <p>
                                        <?php echo $asp['Product']['description']; ?>
                                </p>
                           
                                <strong>$ <?php echo $asp['Product']['price']; ?></strong>
                            
                          
                         <?php echo $this->Form->button('Add to Cart', array('class' => 'btn btn-primary addtocart', 'id' => 'addtocart', 'id' => $asp['Product']['id']));
                                    } }
                                  ?>
                                <br/><br/><br/><br/> <br/><br/><br/><br/>
                                <?php if(!empty($pdata['Product']['Alergy'])) {?>
                               <h2 class="inner">Alergy</h2>    
                                    
                                   <hr>
                                  <?php    //print_r($pdata['Product']['Alergy']); 
                                  foreach($pdata['Product']['Alergy'] as $alg){ ?>
                                       
                                <h5><?php echo $alg['Alergy']['name']; ?></h5>
                               
                                <p>
                                        <?php echo $alg['Alergy']['about']; ?>
                                </p>
                           
                               
                            
                          
                        <?php 
                                } }
                                  ?>       
                                    <hr>

                                </div><!-- End box_style_1 -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                      <?php } } }?>
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
                    <div class="row" id="options_2">
                       <?php if($restaurant['Restaurant']['delivery']==1){ ?>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                            <label><input type="radio" value="" checked name="option_1" class="icheck">Delivery</label>
                        </div>
                        <?php } if($restaurant['Restaurant']['takeaway']==1) {?>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                            <label><input type="radio" value="" checked name="option_2" class="icheck">Take Away</label>
                        </div>
                        <?php } ?>
                    </div><!-- Edn options 2 -->

                    <hr>
                    <div id="total_items"></div>                   
                    <hr>
                    <?php 
                   if($loggeduser){ ?>
                    <a class="btn_full" href="<?php echo $this->webroot ?>shop/address/<?php echo $restaurant['Restaurant']['id']; ?>">Order now</a>
                   <?php } else{?>
                    <a href="#0" data-toggle="modal" class="btn_full" data-target="#login_2">Order now</a>
                   <?php }?>
                </div><!-- End cart_box -->
            </div><!-- End theiaStickySidebar -->
        </div><!-- End col-md-3 -->

    </div><!-- End row -->
</div><!-- End container -->
<!-- End Content =============================================== -->
