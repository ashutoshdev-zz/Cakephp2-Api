<div class="con_main">
    <div class="container">
        <div class="edit">
            <h2>Change Password</h2>
            <h4><?php
                $x = $this->Session->flash();
                if ($x) {
                    echo $x;
                }
                ?></h4>
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="edit_box">
                    <?php echo $this->Form->create('User', array('id' => 'changepassword')); ?>
                    <label>Old password</label>
                    <input type="password" name="data[User][old_password]" value="" type="text"/><br/>

                    <label>New Password</label>
                    <input type="password" name="data[User][new_password]" id="pass1" value="" type="text"/><br/>
                    <label>Confirm Password</label>
                    <input type="password" name="data[User][cpassword]" value="" type="text"/><br/>
                </div>
                <input name="submit" type="submit" value="Submit"/>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
        <div class="col-sm-3"></div>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
                $("#changepassword").validate({
                    errorElement: 'span',
                    rules: {
                        "data[User][old_password]": "required",
                         "data[User][new_password]": "required",
                        "data[User][cpassword]": {
                            required: true,
                            minlength: 8,
                            equalTo: "#pass1"
                        }

                    },
                    messages: {
                        "data[User][old_password]": "Please Enter Old password",
                        "data[User][new_password]": "Please Enter New password",
                        "data[User][cpassword]": {
                            required: "Please Enter confirm password",
                            equalTo: "Confirm Password is not matching your Password"
                        }
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            });
</script>