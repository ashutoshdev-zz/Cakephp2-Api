<div class="con_main">
    <div class="container">
        <div class="edit">
            <h2>Edit Profile</h2>
            <h4><?php
                $x = $this->Session->flash();
                if ($x) {
                    echo $x;
                }
                ?></h4>
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="edit_box">
                    <?php echo $this->Form->create('User', array('id' => 'editform')); ?>
                    <label>Username</label>
                    <input name="data[User][username]" value="<?php echo $data['User']['username']; ?>" type="text" readonly/><br/>
                    <label>Full Name</label>
                    <input name="data[User][name]" value="<?php echo $data['User']['name']; ?>" type="text"/><br/>
                    <label>E-mail</label>
                    <input name="data[User][email]" value="<?php echo $data['User']['email']; ?>" type="text"/><br/>
                    <label>Address</label>
                    <input name="data[User][address]" value="<?php echo $data['User']['address']; ?>" type="text"/><br/>
                    <label>City</label>
                    <input name="data[User][city]" value="<?php echo $data['User']['city']; ?>" type="text"/><br/>
                    <label>State</label>
                    <input name="data[User][state]" value="<?php echo $data['User']['state']; ?>" type="text"/><br/>
                    <label>Country</label>
                    <input name="data[User][country]" value="<?php echo $data['User']['country']; ?>" type="text"/><br/>
                    <label>Zip</label>
                    <input name="data[User][zip]" value="<?php echo $data['User']['zip']; ?>" type="text"/><br/>
                    <label>Phone</label>
                    <input name="data[User][phone]" value="<?php echo $data['User']['phone']; ?>" type="text"/><br/>
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
        $("#editform").validate({
            errorElement: 'span',
            rules: {
                "data[User][fname]": "required",
                "data[User][lname]": "required",
                "data[User][email]": "required"
            },
            messages: {
                "data[User][fname]": "Please enter your First Name",
                "data[User][lname]": "Please enter your Last Name",
                "data[User][cor]": "Please enter your Country of residence",
                "data[User][currency]": "Please enter your currency",
                "data[User][email]": "Please enter your E-mail ID"
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>