<?php //print_r($data); ?>
 <div id="subheader">
    
	</div>

        <?php 
        $x=$this->Session->flash(); echo $x; 
        if($data['User']['image']) { $image=$data['User']['image']; } else { $image="no-avatar.png"; }  ?>
 <div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="well well-sm">
                <div class="row">
                    <div class="col-sm-6 col-md-4">
                        <img src="<?php echo $this->webroot; ?>files/profile_pic/<?php echo $image; ?>" width="380" height="500" alt="" class="img-rounded img-responsive" />
                    </div>
                    <div class="col-sm-6 col-md-8">
                        <h4>
                             <?php echo $data['User']['name']; ?></h4>
<!--                        <small><cite title="San Francisco, USA">San Francisco, USA <i class="glyphicon glyphicon-map-marker">
                        </i></cite></small>-->
                        <p>
                            E-mail:-<?php echo $data['User']['email']; ?>
                            <br />
                             Username:-<?php echo $data['User']['username']; ?>
                             <br />
                               Loyalty Points:- <?php echo $data['User']['loyalty_points']; ?>
                                <br />
                               <?php //echo Wallet:- $data['User']['loyalty_points']; ?>
<!--                            <i class="glyphicon glyphicon-globe"></i><a href="http://www.jquery2dotnet.com">www.jquery2dotnet.com</a>
                            <br />
                            <i class="glyphicon glyphicon-gift"></i>June 02, 1988</p>-->
                
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
        
<a href="<?php echo $this->webroot; ?>users/changepassword">Change Password</a><br/>
 <a href="<?php echo $this->webroot; ?>users/edit">Edit Profile</a>   <br/> 
  <a href="<?php echo $this->webroot; ?>shop/tablehistry">Table Reservation History</a>   <br/> 
    <a href="<?php echo $this->webroot; ?>orders/orderhistry">Order History</a>   <br/> 
<form action="<?php echo $this->webroot; ?>users/myaccount" method="POST" enctype= multipart/form-data >
    Profile pic<input type="file" name="data[User][image]" value=""/>
    <input type="submit" name="submit" value="submit">
</form>

<!--<form action="<?php echo $this->webroot; ?>users/wallet" method="POST" enctype= multipart/form-data >
    Enter Money<input type="number" name="data[User][money]" value=""/>
    <input type="hidden" name="data[User][uid]" value="<?php echo $data['User']['id']; ?>"/>
    <input type="submit" name="submit" value="Add Wallet Money">
</form>-->