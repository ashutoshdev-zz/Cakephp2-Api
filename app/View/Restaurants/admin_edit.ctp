<div class="content">
    <div class="header">
<?php 
//print_r(unserialize($Restaurant['Restaurant']['typeid']));
?>
        <h1 class="page-title">Edit Store</h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->Html->url('/admin/Restaurants/'); ?>">Store Management</a> </li>
            <li class="active">Edit Store</li>
        </ul>

    </div>
    <div class="main-content">  
        <p>
            <?php $x = $this->Session->flash(); ?>
            <?php if ($x) { ?>
        <div class="alert success">
            <span class="icon"></span>
            <strong></strong><?php echo $x; ?>
        </div>
        <?php } ?>
        </p>
        <div class="row">
            <?php echo $this->Form->create('Restaurant', array('id' => 'tab', 'type' => 'file')); ?>
            <div class="col-md-4">
                  <div class="form-group">
                   <?php echo "Username:- ". $uname['User']['username']; ?>
                      
                   
                </div>
                
                <div class="form-group">
                    <?php echo $this->Form->input('typeid', ['options' => $restype, 'label' => 'Store Type:','multiple'=>'multiple','selected'=>unserialize($Restaurant['Restaurant']['typeid'])]); ?>
                    <?php //echo $this->Form->input('name',array('class'=>'form-control','label'=>'Restaurant Name:')); 	?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('name', array('class' => 'form-control', 'label' => 'Store Name:','required' => true)); ?>
                </div>
                <!--                <div class="form-group">
                    <?php //echo $this->Form->input('barcodeno', array('class' => 'form-control', 'label' => 'Barcode NO.')); ?>
                                </div>-->
                <div class="form-group">
                    <?php echo $this->Form->input('phone', array('class' => 'form-control', 'label' => 'Store Phone:','required' => true)); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('owner_name', array('class' => 'form-control', 'label' => 'Owner Name:','required' => true)); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('owner_phone', array('class' => 'form-control', 'label' => 'Owner Phone:','required' => true)); ?>
                </div>
                <!--                <div class="form-group">
                                    <label>Select Address:</label>
                                    <input id="autocomplete" placeholder="Select Address" onFocus="geolocate()" type="text" class="form-control"></input>
                                </div>-->
                
                <div class="form-group">          
                    <?php //echo $this->Form->input('street_number', array('class' => 'form-control', 'label' => 'Street Number:', 'id' => 'street_number')); ?>
                </div>
                <div class="form-group">
                    <?php //echo $this->Form->input('street', array('class' => 'form-control', 'label' => 'Street:', 'id' => 'route')); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('address', array('class' => 'form-control', 'label' => 'Address:','required' => true)); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('city', array('class' => 'form-control', 'label' => 'City:', 'id' => 'locality','required' => true)); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('state', array('class' => 'form-control', 'label' => 'State:', 'id' => 'administrative_area_level_1','required' => true)); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('zip', array('class' => 'form-control', 'label' => 'Zip:','id' => 'postal_code','required' => true)); ?>
                </div>          
                <div class="form-group">
                    <?php echo $this->Form->input('description', array('class' => 'form-control', 'label' => 'Description:','required' => true)); ?>
                </div>
                <div class="form-group">
                    <?php
                    $restaurantPath = '/files/restaurants/';
                    echo $this->Html->image($restaurantPath . $Restaurant['Restaurant']['logo'], array('alt' => 'Store Logo', 'width' => 100));
                    ?>
                </div> 
                <div class="form-group">
                    <label>Logo:</label>
                    <input type="file" name="data[Restaurant][logo]" class="form-control" id="RestaurantLogo" value="<?php echo $Restaurant['Restaurant']['logo']; ?>">
                    <?php //echo $this->Form->input('logo', array('type' => 'file', 'class' => 'form-control', 'label' => 'Logo:','value'=>'efr')); ?>
                </div>
                <div class="form-group">
                    <?php
                    $restaurantPath = '/files/restaurants/';
                    echo $this->Html->image($restaurantPath . $Restaurant['Restaurant']['banner'], array('alt' => 'Store banner', 'width' => 100));
                    ?>
                </div> 
                <div class="form-group">
                    <label>Banner Image:</label>
                    <input type="file" name="data[Restaurant][banner]" class="form-control" id="RestaurantLogo" value="<?php echo $Restaurant['Restaurant']['banner']; ?>">
                    <?php //echo $this->Form->input('logo', array('type' => 'file', 'class' => 'form-control', 'label' => 'Logo:','value'=>'efr')); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('website', array('class' => 'form-control', 'label' => 'Website:','required' => true)); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('email', array('class' => 'form-control', 'label' => 'Email:','required' => true)); ?>
                </div>
               
                <div class="form-group">
                    <?php echo $this->Form->input('delivery_distance', array('class' => 'form-control', 'label' => 'Delivery Distance in miles:','required' => true)); ?>
                </div> 
                <div class="form-group">
                    <?php echo $this->Form->input('delivery', array('class' => 'form-control1', 'type'=>'checkbox', 'label' => 'Delivery:')); ?>
                </div>
               
                 
                <div class="form-group">
                    <?php echo $this->Form->input('takeaway', array('class' => 'form-control1', 'type'=>'checkbox', 'label' => 'Takeaway:')); ?>
                </div>
                   <div class="form-group">
                    <?php echo $this->Form->input('reservation', array('class' => 'form-control1', 'type'=>'checkbox', 'label' => 'Reservation:')); ?>
                </div>
                 
                  <div class="form-group">
                    <?php echo $this->Form->input('table_pricing', array('class' => 'form-control', 'type'=>'text', 'label' => 'Table Price per person:','required' => true)); ?>
                </div>
                 
                <!--                   <div class="form-group">
                    <?php //echo $this->Form->input('delivery_fee', array('class' => 'form-control', 'label' => 'Delivery Fee:')); ?>
                                </div>-->
                <div class="form-group">
<?php //echo $this->Form->input('opening_time', array('class' => 'form-control', 'label' => 'Opening Time:', 'type' => 'time')); ?>
                </div>

                <div class="form-group">
<?php //echo $this->Form->input('closing_time', array('class' => 'form-control', 'label' => 'Closing Time:', 'type' => 'time')); ?>
                </div>
                
               <?php   if($loggedUserRole!='rest_admin'){ ?>
                <div class="form-group">
<?php echo $this->Form->input('taxes', array('class' => 'form-control', 'label' => 'Tax', 'type' => 'text','required' => true)); ?>
                    </div>
                  <?php if($loggeduser!=417){  ?>
                  <div class="form-group">
<?php echo $this->Form->input('commission_qr', array('class' => 'form-control', 'label' => 'Commission Qrcode Order', 'type' => 'text','required' => true)); ?>
                    </div>
                  <div class="form-group">
<?php echo $this->Form->input('commission_del', array('class' => 'form-control', 'label' => 'Commission Delivery Order', 'type' => 'text','required' => true)); ?>
                    </div>
                  <?php } ?>
                  <div class="form-group">
<?php echo $this->Form->input('commission_pick', array('class' => 'form-control', 'label' => 'Commission Pickup Order', 'type' => 'text','required' => true)); ?>
                    </div>
                  <div class="form-group">
<?php echo $this->Form->input('commission_tr', array('class' => 'form-control', 'label' => 'Commission Table Reservation Order', 'type' => 'text','required' => true)); ?>
                    </div>
               <?php } ?>
                <input type="hidden" name="data[Restaurant][created]" value="<?php echo date('Y-m-d H:i:s'); ?>">
                
                <input type="hidden" name="data[Restaurant][latitude]" value="" id="latitude">
                <input type="hidden" name="data[Restaurant][longitude]" value="" id="longitude">
                <input type="hidden" name="data[Restaurant][status]" value="1">
                <div class="btn-toolbar list-toolbar">
                    <?php echo $this->Form->submit('Save', array('formnovalidate' => true, 'class' => "submitres", 'div' => array('class' => 'btn btn-primary'))); ?>
                    <a href="<?php echo $this->Html->url(array('controller' => 'Restaurants', 'action' => 'admin_index')); ?>" data-toggle="modal" class="btn btn-danger">Cancel</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        country: 'long_name',
        postal_code: 'short_name'
    };


    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
                {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
    }

// [START region_fillform]
    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        for (var component in componentForm) {
            document.getElementById(component).value = '';
        }
        var lat = place.geometry.location.lat();
        var lng = place.geometry.location.lng();
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
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
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
// [END region_geolocation]

</script>
<script src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initAutocomplete"
async defer></script>
<style>
   .submitres {
    border: none;
    background: none;
}
</style>