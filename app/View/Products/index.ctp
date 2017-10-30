<!-- SubHeader =============================================== -->
<section class="header-video">
    <div id="hero_video">
        <div id="sub_content">
            <h1>Order Takeaway or Delivery Food</h1>
            <p>
                Ridiculus sociosqu cursus neque cursus curae ante scelerisque vehicula.
            </p>
            <!-------tab------------------------------------->		
            <div class="tabs">
                <ul role="tablist" class="nav nav-tabs" id="myTabs">
                    <li class="active" role="presentation"><a aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="home-tab" href="#home">Delivery</a></li>
                    <li role="presentation"><a aria-controls="profile" data-toggle="tab" id="profile-tab" role="tab" href="#profile">Reservation</a></li>
                    <li role="presentation"><a aria-controls="delivery" data-toggle="tab" id="delivery-tab" role="tab" href="#delivery">Discovery</a></li>
                </ul>

                <div class="tab-content" id="myTabContent" style="padding:0;">                
                    <div aria-labelledby="home-tab" id="home" class="tab-pane fade in active" role="tabpanel">
                        <form action="<?php echo $this->webroot; ?>restaurants/restaurantsearch?search" method="post">
                            <div class="col-sm-8">
                                <div class="row">
                                   
                                    <div class="col-sm-12">
                                        <div class="select_menu">

                                            <input id="autocomplete" type="text"  placeholder="Enter your address" class="span2">
                                            <input type="hidden" name=data[Restaurant][lat] class="field" id="latitude"></input>
                                            <input type="hidden" name=data[Restaurant][long] class="field" id="longitude"></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="col-sm-4">
                                <div class="search_n"><button> Search <span><img src="<?php echo $this->webroot; ?>home/img/search.png" alt="image"></span></button> </div>
                            </div>
                            
                            
                            
                            
                        </form>
                    </div>
                    <div aria-labelledby="profile-tab" id="profile" class="tab-pane fade" role="tabpanel">
                         <form action="<?php echo $this->webroot; ?>restaurants/tablesearch?search" method="post">
              <div class="col-sm-8">
                           
                            <div class="row">
                             
                                <div class="col-sm-12">
                                    <div class="select_menu">
                                        <input id="autocomplete_a" type="text"  placeholder="Enter your address" class="span2">
                                         <input type="hidden" name=data[Restaurant][lat] class="field" id="lat_a"></input>
                                            <input type="hidden" name=data[Restaurant][long] class="field" id="long_a"></input>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                     
                      <div class="col-sm-4">
                                <div class="search_n"><button> Search <span><img src="<?php echo $this->webroot; ?>home/img/search.png" alt="image"></span></button> </div>
                            </div>
                            
                            
                            
                            
                         </form>
                    </div>
                    <div aria-labelledby="delivery-tab" id="delivery" class="tab-pane fade" role="tabpanel">
                        <form action="<?php echo $this->webroot; ?>restaurants/discovery?search" method="post">
                        <div class="col-sm-8">
                            <div class="row">
                             
                                <div class="col-sm-12">
                                    <div class="select_menu">
                                        <input id="autocomplete_b" type="text"  placeholder="Enter your address" class="span2">
                                            <input type="hidden" name=data[Restaurant][lat] class="field" id="lat_b"></input>
                                            <input type="hidden" name=data[Restaurant][long] class="field" id="long_b"></input>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                                <div class="search_n"><button> Search <span><img src="<?php echo $this->webroot; ?>home/img/search.png" alt="image"></span></button> </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          
        </div><!-- End sub_content -->
    </div>
    <img src="<?php echo $this->webroot; ?>home/img/video_fix.png" alt="" class="header-video--media" data-video-src="<?php echo $this->webroot; ?>home/video/intro" data-teaser-source="<?php echo $this->webroot; ?>home/video/intro" data-provider="Vimeo" data-video-width="1920" data-video-height="960">
    <div id="count" class="hidden-xs">
        <ul>
            <li><span class="number">2650</span> Restaurant</li>
            <li><span class="number">5350</span> People Served</li>
            <li><span class="number">12350</span> Registered Users</li>
        </ul>
    </div>
</section><!-- End Header video -->
<!-- End SubHeader ============================================ -->

<!-- Content ================================================== -->
<div class="container margin_60">

    <div class="main_title">
        <h2 class="nomargin_top" style="padding-top:0">How it works</h2>
        <p>
            Cum doctus civibus efficiantur in imperdiet deterruisset.
        </p>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="box_home" id="one">
                <span>1</span>
                <h3>Search by address</h3>
                <p>
                    Find all restaurants available in your zone.
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box_home" id="two">
                <span>2</span>
                <h3>Choose a restaurant</h3>
                <p>
                    We have more than 1000s of menus online.
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box_home" id="three">
                <span>3</span>
                <h3>Pay by card or cash</h3>
                <p>
                    It's quick, easy and totally secure.
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box_home" id="four">
                <span>4</span>
                <h3>Delivery or takeaway</h3>
                <p>
                    You are lazy? Are you backing home?
                </p>
            </div>
        </div>
    </div><!-- End row -->

    <div id="delivery_time" class="hidden-xs">
        <strong><span>2</span><span>5</span></strong>
        <h4>The minutes that usually takes to deliver!</h4>
    </div>
</div><!-- End container -->

<div class="white_bg">
    <div class="container margin_60">

        <div class="main_title">
            <h2 class="nomargin_top">Choose from Most Popular</h2>
            <p>
                Cum doctus civibus efficiantur in imperdiet deterruisset.
            </p>
        </div>

        <div class="row">
            <?php foreach ($homeproducts as $d): ?>
                <div class="col-md-6">
                    <a href="<?php echo $this->webroot; ?>restaurants/menu/<?php echo $d['Restaurant']['id']; ?>" class="strip_list">
                        <div class="ribbon_1">Popular</div>
                        <div class="desc">
                            <div class="thumb_strip">
                                <img src="<?php echo $this->webroot; ?>files/restaurants/<?php echo $d['Restaurant']['logo']; ?>" alt="">
                            </div>
                            <div class="rating">
                                <?php
                                $i = $d['Restaurant']['review_avg'];

                                for ($j = 0; $j < $i; $j++) {
                                    ?>

                                    <i class="icon_star voted"></i>

                                <?php } for ($h = 0; $h < 5 - $i; $h++) { ?>  
                                    <i class="icon_star"></i>
    <?php } ?>
                            </div>
                            <h3><?php echo $d['Restaurant']['name']; ?></h3>
                            <div class="type">
    <?php $d['RestaurantsType']['name']; ?>
                            </div>
                            <div class="location">
    <?php echo $d['Restaurant']['address']; ?> <span class="opening">Opens at  <?php echo $d['Restaurant']['opening_time']; ?></span>
                            </div>
                            <ul>
                                <?php if ($d['Restaurant']['delivery'] == 1) { ?>                         
                                    <li>Delivery<i class="icon_check_alt2 ok"></i></li>
                                <?php } else { ?>
                                    <li>Delivery<i class="icon_close_alt2 no"></i></li>
                                <?php } ?>
                                <?php if ($d['Restaurant']['takeaway'] == 1) { ?>                         
                                    <li>Take away<i class="icon_check_alt2 ok"></i></li>
                                <?php } else { ?>
                                    <li>Take away<i class="icon_close_alt2 no"></i></li>
    <?php } ?>


                            </ul>
                        </div><!-- End desc-->
                    </a><!-- End strip_list-->
                </div>
<?php endforeach; ?>
        </div><!-- End row -->   

    </div><!-- End container -->
</div><!-- End white_bg -->

<div class="high_light">
    <div class="container">
        <h3>Choose from over 2,000 Restaurants</h3>
        <p>Ridiculus sociosqu cursus neque cursus curae ante scelerisque vehicula.</p>
        <a href="list_page.html">View all Restaurants</a>
    </div><!-- End container -->
</div><!-- End hight_light -->

<section class="parallax-window" data-parallax="scroll" data-image-src="<?php echo $this->webroot; ?>home/img/bg_office.jpg" data-natural-width="1200" data-natural-height="600">
    <div class="parallax-content">
        <div class="sub_content">
            <i class="icon_mug"></i>
            <h3>We also deliver to your office</h3>
            <p>
                Ridiculus sociosqu cursus neque cursus curae ante scelerisque vehicula.
            </p>
        </div><!-- End sub_content -->
    </div><!-- End subheader -->
</section><!-- End section -->
<!-- End Content =============================================== -->


<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
<style>
    #locationField, #controls {
        position: relative;
        width: 480px;
    }
    #autocomplete {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 99%;
    }
    #autocomplete1 {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 99%;
    }
    #autocomplete2 {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 99%;
    }
    .label {
        text-align: right;
        font-weight: bold;
        width: 100px;
        color: #303030;
    }
    #address {
        border: 1px solid #000090;
        background-color: #f0f0ff;
        width: 480px;
        padding-right: 2px;
    }
    #address td {
        font-size: 10pt;
    }
    .field {
        width: 99%;
    }
    .slimField {
        width: 80px;
    }
    .wideField {
        width: 200px;
    }
    #locationField {
        height: 20px;
        margin-bottom: 2px;
    }
</style>