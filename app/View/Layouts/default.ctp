<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo "Ecasnik";//$title_for_layout; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php echo $this->webroot; ?>home/img/favicon.png" type="image/x-icon">
        <link rel="apple-touch-icon" type="image/x-icon" href="<?php echo $this->webroot; ?>home/img/apple-touch-icon-57x57-precomposed.png">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="<?php echo $this->webroot; ?>home/img/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="<?php echo $this->webroot; ?>home/img/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="<?php echo $this->webroot; ?>home/img/apple-touch-icon-144x144-precomposed.png">

        <!-- GOOGLE WEB FONT -->
        <link href='https://fonts.googleapis.com/css?family=Lato:400,700,900,400italic,700italic,300,300italic' rel='stylesheet' type='text/css'>

        <!-- BASE CSS -->
        <link href="<?php echo $this->webroot; ?>home/css/base.css" rel="stylesheet">

        <!-- Radio and check inputs -->
        <link href="<?php echo $this->webroot; ?>home/css/skins/square/grey.css" rel="stylesheet">
        <!-- Modernizr -->
        <script src="<?php echo $this->webroot; ?>home/js/modernizr.js"></script> 


        <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <!--[if lt IE 9]>
          <script src="js/html5shiv.min.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
        <style> 

            .glyphicon-unchecked,.glyphicon-check{
                font-size: 23px !important;
                color: #dedede !important;
                margin-bottom: -21px !important;
                padding-bottom: 0;
                font-weight: normal;
                margin: 0;
                padding: 0;
                width: 29px !important;
                float: left;
                margin-top: -4px;
            }
   
            .chk{
                width: 23px !important;
                height: 23px;
                float:left;

            }
            .lable1{
                margin-left: 7px !important;	
            }
            .bs-checkbox{
                width: 23px !important;
                height: 23px;
                float: left;
                /* margin-top: -4px; */	
            }
            .search_n > button{
                background: none;
                border: none;
                padding: 7px 0;	
                outline: none; 

            }
            #autocomplete_a,#autocomplete_b{
                margin-left: -7px; 
            }
            .search_n {
                color: #fff !important;
                float: left;
                margin: 4px 0 4px 0;
                text-align: center;
                height: 35px;
                width: 98%;
            }
            .tab-content,.nav-tabs {

                border: none; 
            }
            .span2{    height: 37px;  left: -7px !important;
                       width: 104.5% !important;}
            .tab-pane {
                padding: 0;
                margin: 0;
                width: 99.8%;
                margin-left: 1px;	
            }

        </style>

    </head>

    <!--[if lte IE 8]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a>.</p>
    <![endif]-->
    <?php if ($this->Session->check('Shop')) : ?>
        <script type="text/javascript">
            //            $(document).ready(function() {
            //                $('#cartbutton').show();
            //            });
        </script>
    <?php endif; ?>
    <body>
        <div id="preloader">
            <div class="sk-spinner sk-spinner-wave" id="status">
                <div class="sk-rect1"></div>
                <div class="sk-rect2"></div>
                <div class="sk-rect3"></div>
                <div class="sk-rect4"></div>
                <div class="sk-rect5"></div>
            </div>
        </div><!-- End Preload -->

        <!-- Header ================================================== -->
        <header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col--md-4 col-sm-4 col-xs-4">
                        <a href="<?php echo $this->webroot; ?>" id="logo">
                            <img src="<?php echo $this->webroot; ?>home/img/logo.png" width="120" alt="" data-retina="true" class="hidden-xs">
                            <img src="<?php echo $this->webroot; ?>home/img/logo_mobile.png" width="120" alt="" data-retina="true" class="hidden-lg hidden-md hidden-sm">
                        </a>
                    </div>
                    <nav class="col--md-8 col-sm-8 col-xs-8">
                        <a class="cmn-toggle-switch cmn-toggle-switch__htx open_close" href="javascript:void(0);"><span>Menu mobile</span></a>
                        <div class="main-menu">
                            <div id="header_menu">
                                <img src="<?php echo $this->webroot; ?>home/img/logo.png" width="120" alt="" data-retina="true">
                            </div>
                            <a href="#" class="open_close" id="close_in"><i class="icon_close"></i></a>
                            <ul>
                                <li><a href="<?php echo $this->webroot; ?>">Home</a></li>


                                <!--                                <li class="submenu">
                                                                    <a href="javascript:void(0);" class="show-submenu">Pages<i class="icon-down-open-mini"></i></a>
                                                                    <ul>
                                                                        <li><a href="#">Restaurant Menu</a></li>
                                
                                                                    </ul>
                                                                </li>-->

                                <?php if (empty($loggeduser)) { ?>
                                    <li><a href="#0" data-toggle="modal" data-target="#register">Register</a></li>
                                    <li><a href="#0" data-toggle="modal" data-target="#login_2">Log In</a></li>
                                <?php } else { ?>
                                    <li><a href="<?php echo $this->webroot ?>users/logout" >Log Out</a></li>
                                    <li><a href="<?php echo $this->webroot; ?>users/myaccount">Myaccount</a></li>
                                <?php } ?>

                                <li><a href="<?php echo $this->webroot; ?>pages/about">About Us</a></li>   


                            </ul>
                        </div><!-- End main-menu -->
                    </nav>
                </div><!-- End row -->
            </div><!-- End container -->
        </header>
        <!-- End Header =============================================== -->   
        <?php echo $this->fetch('content'); ?>

        <!-- Footer ================================================== -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-3">
                        <h3>Secure payments with</h3>
                        <p>
                            <img src="<?php echo $this->webroot; ?>home/img/cards.png" alt="" class="img-responsive">
                        </p>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <h3>About</h3>
                        <ul>
                            <li><a href="about.html">About us</a></li>
                            <li><a href="faq.html">Faq</a></li>
                            <li><a href="contacts.html">Contact</a></li>
                            <li><a href="#0" data-toggle="modal" data-target="#login_2">Login</a></li>
                            <li><a href="#0" data-toggle="modal" data-target="#register">Register</a></li>
                            <li><a href="#0">Terms and conditions</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-3" id="newsletter">
                        <h3>Newsletter</h3>
                        <p>
                            Join our newsletter to keep be informed about offers and news.
                        </p>
                        <div id="message-newsletter_2">
                        </div>
                        <div class="message"></div>
                        <form  role="form" method="post" id="subscribe">

                            Email :<input type="email"  id="email" name="email" placeholder="Write your email" > <br>
                            <button type="button" id="nwsltr">Subscribe Now !</button>

                        </form>
                    </div>
             
                </div><!-- End row -->
                <div class="row">
                    <div class="col-md-12">
                        <div id="social_footer">
                            <ul>
                                <li><a href="#0"><i class="icon-facebook"></i></a></li>
                                <li><a href="#0"><i class="icon-twitter"></i></a></li>
                                <li><a href="#0"><i class="icon-google"></i></a></li>
                                <li><a href="#0"><i class="icon-instagram"></i></a></li>
                                <li><a href="#0"><i class="icon-pinterest"></i></a></li>
                                <li><a href="#0"><i class="icon-vimeo"></i></a></li>
                                <li><a href="#0"><i class="icon-youtube-play"></i></a></li>
                            </ul>
                            <p>
                                © eCasnik 2016
                            </p>
                        </div>
                    </div>
                </div><!-- End row -->
            </div><!-- End container -->
        </footer>
        <!-- End Footer =============================================== -->

        <div class="layer"></div><!-- Mobile menu overlay mask -->

        <!-- Login modal -->   

        <div class="modal fade" id="login_2" tabindex="-1" role="dialog" aria-labelledby="myLogin" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-popup">
                    <a href="#" class="close-link"><i class="icon_close_alt2"></i></a>
                    <form action="<?php echo $this->webroot; ?>users/login" method="post"class="popup-form" id="myLogin">
                        <div class="login_icon"><i class="icon_lock_alt"></i></div>
                        <input type="text" name="data[User][username]" class="form-control form-white" placeholder="Username">
                        <input type="password" name="data[User][password]" class="form-control form-white" placeholder="Password">
                        <input type="hidden" name="data[User][server]" value="<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
                        <div class="text-left">
                            <a href="<?php echo $this->webroot; ?>users/forgetpwd">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn btn-submit">Submit</button>
                    </form>
                </div>
            </div>

        </div><!-- End modal -->   

        <!-- Register modal -->   
        <div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="myRegister" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-popup">
                    <a href="#" class="close-link"><i class="icon_close_alt2"></i></a>
                    <form action="<?php echo $this->webroot; ?>users/add" class="popup-form" method="post" id="myRegister">
                        <div class="login_icon"><i class="icon_lock_alt"></i></div>
                        <input type="text" class="form-control form-white" name="data[User][fname]" placeholder="First Name">
                        <input type="text" class="form-control form-white" name="data[User][lname]"  placeholder="Last Name">
                        <input type="email" class="form-control form-white" name="data[User][username]" placeholder="Email">
                        <input type="password" class="form-control form-white" name="data[User][password]" placeholder="Password"  id="password1">
                        <input type="password" class="form-control form-white" placeholder="Confirm password"  id="password2">
                        <input type="hidden" name="data[User][server]" value="<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
                        <div id="pass-info" class="clearfix"></div>
                        <div class="checkbox-holder text-left">
                            <div class="checkbox">
                                <input type="checkbox" value="accept_2" id="check_2" name="check_2" />
                                <label for="check_2"><span>I Agree to the <strong>Terms &amp; Conditions</strong></span></label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-submit">Register</button>
                    </form>
                </div>
            </div>
        </div><!-- End review modal -->

        <!-- COMMON SCRIPTS -->
        <script src="<?php echo $this->webroot; ?>home/js/jquery-1.11.2.min.js"></script>
        <script src="<?php echo $this->webroot; ?>home/js/common_scripts_min.js"></script>
        <script src="<?php echo $this->webroot; ?>home/js/functions.js"></script>
        <script src="<?php echo $this->webroot; ?>home/assets/validate.js"></script>
        <script src="http://maps.googleapis.com/maps/api/js"></script>
        <script src="<?php echo $this->webroot; ?>home/js/map.js"></script>
        <script src="<?php echo $this->webroot; ?>home/js/infobox.js"></script>
        <script src="<?php echo $this->webroot; ?>home/js/addtocart.js"></script>
        <!-- SPECIFIC SCRIPTS -->
        <script src="<?php echo $this->webroot; ?>home/js/video_header.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
        <script src="http://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
        <script type="text/javascript">            
            function getQueryStringValue(key) {
                return unescape(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + escape(key).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));
            }
            var myURL = document.location;
            $(function () {
               $("#slider-range-max").slider({
                    range: "max",
                    min: 1,
                    max: 15,
                    value: 1,
                    slide: function (event, ui) {
                        $("#amount").val(ui.value);
                        var amt = ui.value;
                        //alert(amt);
                       
                       if (getQueryStringValue("distance")) { 
                                              
                            document.location=myURL.search.replace('&distance='+/\d+/+'','&distance='+amt+'');
                     
                            
                        } else {
                           
                            document.location = myURL + "&distance=" + amt;
                        }
                    }
                });
                HeaderVideo.init({
                    container: $('.header-video'),
                    header: $('.header-video--media'),
                    videoTrigger: $("#video-trigger"),
                    autoPlayVideo: true
                });
            });
            $('.alltype').on("click", function () {
               
                var checkboxes = document.getElementsByName('location[]');

                var vals = "";
                for (var i = 0, n = checkboxes.length; i < n; i++)
                {
                    
                    if (checkboxes[i].checked)
                    {
                        vals += "," + checkboxes[i].value;
                    }
                }
                if (vals)
                    vals = vals.substring(1);

                if (getQueryStringValue("type")) {
                    document.location =myURL+ "&type=" + vals;
                } else {
                    document.location = myURL + "&type=" + vals;
                }

            });

            $('.rtngs').on("click", function () {
                var checkboxes = document.getElementsByName('ratings[]');
                var vals = "";
                for (var i = 0, n = checkboxes.length; i < n; i++)
                {
                    if (checkboxes[i].checked)
                    {
                        vals += "," + checkboxes[i].value;
                    }
                }
                if (vals)
                    vals = vals.substring(1);
                
                if (getQueryStringValue("rate")) {
                    document.location = myURL+"&rate=" + vals;
                } else {
                    document.location = myURL + "&rate=" + vals;
                    
                }


            });
            
            $('#dlchk').on("click", function () {
                 var vals= $(this).val();
                
                if (getQueryStringValue("dlchk")) {
                    document.location = myURL.search.replace('&dlchk=delivery',''); 
                } else {
                    document.location = myURL + "&dlchk=" + vals;
                    
                }


            });
            $('#tkchk').on("click", function () {
                         var vals= $(this).val();
                
                if (getQueryStringValue("tkchk")) {
                           
                     document.location = myURL.search.replace('&tkchk=takeaway','') ;
                } else {
                    document.location = myURL + "&tkchk=" + vals;
                    
                }


            });
              
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
        <script type="text/javascript">
            $("#autocomplete").on('focus', function () {
                geolocate();
            });
            $("#autocomplete_a").on('focus', function () {
                geolocate_a();
            });
            $("#autocomplete_b").on('focus', function () {
                geolocate_b();
            });
            var placeSearch, autocomplete, autocomplete_a, autocomplete_b;
            var componentForm = {
                street_number: 'short_name',
                route: 'long_name',
                locality: 'long_name',
                administrative_area_level_1: 'short_name',
                country: 'long_name',
                postal_code: 'short_name'
            };
            function initialize() {
                // Create the autocomplete object, restricting the search
                // to geographical location types.
                autocomplete = new google.maps.places.Autocomplete(
                        /** @type {HTMLInputElement} */ (document.getElementById('autocomplete')), {
                    types: ['geocode']
                });
                autocomplete_a = new google.maps.places.Autocomplete(
                        /** @type {HTMLInputElement} */ (document.getElementById('autocomplete_a')), {
                    types: ['geocode']
                });
                autocomplete_b = new google.maps.places.Autocomplete(
                        /** @type {HTMLInputElement} */ (document.getElementById('autocomplete_b')), {
                    types: ['geocode']
                });
                // When the user selects an address from the dropdown,
                // populate the address fields in the form.
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    fillInAddress();
                });
                google.maps.event.addListener(autocomplete_a, 'place_changed', function () {
                    fillInAddress_a();
                });
                google.maps.event.addListener(autocomplete_b, 'place_changed', function () {
                    fillInAddress_b();
                });
            }


            // [START region_fillform]
            function fillInAddress() {
                // Get the place details from the autocomplete object.
                var place = autocomplete.getPlace();
                document.getElementById("latitude").value = place.geometry.location.lat();
                document.getElementById("longitude").value = place.geometry.location.lng();
                localStorage.setItem("lat", place.geometry.location.lat());
                localStorage.setItem("long", place.geometry.location.lng());

                for (var component in componentForm) {
                    document.getElementById(component).value = '';
                    document.getElementById(component).disabled = false;
                }

                // Get each component of the address from the place details
                // and fill the corresponding field on the form.
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i][componentForm[addressType]];
                        document.getElementById(addressType).value = val;
                    }
                }


            }
            function fillInAddress_a() {
                // Get the place details from the autocomplete object.
                var place = autocomplete_a.getPlace();

                document.getElementById("lat_a").value = place.geometry.location.lat();
                document.getElementById("long_a").value = place.geometry.location.lng();
                localStorage.setItem("lat_a", place.geometry.location.lat());
                localStorage.setItem("long_a", place.geometry.location.lng());
                for (var component in componentForm) {
                    document.getElementById(component).value = '';
                    document.getElementById(component).disabled = false;
                }

                // Get each component of the address from the place details
                // and fill the corresponding field on the form.
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i][componentForm[addressType]];
                        document.getElementById(addressType).value = val;
                    }
                }


            }
            function fillInAddress_b() {
                // Get the place details from the autocomplete object.


                var place = autocomplete_b.getPlace();
                document.getElementById("lat_b").value = place.geometry.location.lat();
                document.getElementById("long_b").value = place.geometry.location.lng();
                localStorage.setItem("lat_b", place_b.geometry.location.lat());
                localStorage.setItem("long_b", place_b.geometry.location.lng());
                for (var component in componentForm) {
                    document.getElementById(component).value = '';
                    document.getElementById(component).disabled = false;
                }

                // Get each component of the address from the place details
                // and fill the corresponding field on the form.
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (componentForm[addressType]) {
                        var val = place.address_components[i][componentForm[addressType]];
                        document.getElementById(addressType).value = val;
                    }
                }

            }
            // [END region_fillform]

            // [START region_geolocation]
            // Bias the autocomplete object to the user's geographical location,
            // as supplied by the browser's 'navigator.geolocation' object.
            function geolocate() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {

                        var geolocation = new google.maps.LatLng(
                                position.coords.latitude, position.coords.longitude);


                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;

                        document.getElementById("latitude").value = latitude;
                        document.getElementById("longitude").value = longitude;

                        autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));

                    });
                }



            }
            function geolocate_a() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {

                        var geolocation = new google.maps.LatLng(
                                position.coords.latitude, position.coords.longitude);


                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;

                        document.getElementById("latitude").value = latitude;
                        document.getElementById("longitude").value = longitude;

                        autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));

                    });
                }

            }
            function geolocate_b() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {

                        var geolocation = new google.maps.LatLng(
                                position.coords.latitude, position.coords.longitude);


                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;

                        document.getElementById("latitude").value = latitude;
                        document.getElementById("longitude").value = longitude;

                        autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));

                    });
                }

            }

            initialize();
            // [END region_geolocation]

            $('#grd').on('click', function (e) {
                e.preventDefault();
                $('.lst,#grd').hide();
                $('.grd,#lst').show();
            });
            $('#lst').on('click', function (e) {
                e.preventDefault();
                $('.grd,#lst').hide();
                $('.lst,#grd').show();
            });
            function verhoz() {
                $('#grd').on('click', function (e) {
                    e.preventDefault();
                    $('.lst,#grd').hide();
                    $('.grd,#lst').show();
                });
                $('#lst').on('click', function (e) {
                    e.preventDefault();
                    $('.grd,#lst').hide();
                    $('.lst,#grd').show();
                });
            }
        </script>
        <script>
            /*
             * bsCheckbox 0.2
             * Docs: https://github.com/ktasos/bs-checkbox
             * Author: Tasos Karagiannis
             * Website: http://codingstill.com
             * Twitter: https://twitter.com/codingstill
             */

            (function ($) {
                $.fn.bsCheckbox = function (options) {
                    options = $.extend({}, $.fn.bsCheckbox.defaultOptions, options);

                    this.each(function (idx, item) {
                        var jThis = jQuery(item);
                        var jCheck = jThis.find('input');
                        jThis.addClass('glyphicon').addClass(jCheck[0].checked ? 'glyphicon-check' : 'glyphicon-unchecked');

                        if (jThis.closest('label').length === 0) {
                            jThis.click(function () {
                                jCheck[0].checked = !jCheck[0].checked;
                                jThis.removeClass('glyphicon-check').removeClass('glyphicon-unchecked');
                                jThis.addClass(jCheck[0].checked ? 'glyphicon-check' : 'glyphicon-unchecked');
                                jCheck.trigger('change');
                            });
                        }

                        if (jThis.closest('.checkbox').length === 0 && jThis.closest('.form-group').length > 0) {
                            jThis.addClass('checkbox');
                        }

                        jCheck.change(function () {
                            jThis.removeClass('glyphicon-check').removeClass('glyphicon-unchecked');
                            jThis.addClass(jCheck[0].checked ? 'glyphicon-check' : 'glyphicon-unchecked');
                        });
                    });
                };

                $.fn.bsCheckbox.defaultOptions = {};
            })(jQuery);
        </script>

        <script>
            jQuery('.bs-checkbox').bsCheckbox();
        </script>
    </body>
    
    <script type="text/javascript">
        function valid_email_address(email)
        {
            var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
            return pattern.test(email);
        }
        $('#nwsltr').on("click", function () {
            if (!valid_email_address($("#email").val()))
            {
                $(".message").html('Please make sure you enter a valid email address.');
            }
            else
            {

                $(".message").html("<span style='color:green;'>Almost done, please check your email address to confirmation.</span>");
                $.ajax({
                    url: 'http://rajdeep.crystalbiltech.com/ecasnik/shop/newsletter',
                    data: $('#subscribe').serialize(),
                    type: 'POST',
                    success: function (msg) {
                        if (msg == "success")
                        {
                            $("#email").val("");
                            $(".message").html('<span style="color:green;">You have successfully subscribed to our mailing list.</span>');

                        }
                        else
                        {
                            $(".message").html('Please make sure you enter a valid email address.');
                        }
                    }
                });
            }
            return false;
        });
        
    </script>
</body>
</html>