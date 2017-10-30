<div style="width: 60%;">
    <table width="100%" align="center" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td width="100%">

                </td>
            </tr>
            <tr>

            </tr>
            <tr>
                <td align="center" valign="top">
                    <table width="100%" align="center" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0">
                        <tbody><tr>
                                <td height="20" valign="top"></td>
                            </tr>

                            <tr>
                                <td align="center" valign="top">
                                    <font style="font-family:Arial,Helvetica,sans-serif;font-size:20px;color:#353535">     
                                    <strong>Order Details</strong>
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td height="20" valign="top"></td>
                            </tr>
                            <tr>
                                <td height="20" valign="top"></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"> 
                                    <font style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#212121;line-height:20px">
                                    Hi  <?php echo $shop['Order']['first_name'] ." ".$shop['Order']['last_name']; ?>,<br><br>
                                   
                                        <?php //echo $i++; ?> Notes:- <?php echo $shop['Order']['notes']; ?><br/>
                                        - For Order status call -123456789 <br></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="20" valign="top"></td>
                                </tr>
                                <tr>	
                                    <td align="center" style="padding:10px;border:2px dashed #999999">
                                        <strong>
                                            <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535">
                                            <?php echo "Date:- " .$shop['Order']['delivery_schedule_day'] ." Time- ". $shop['Order']['delivery_schedule_time']; ?>
                                           
                                            </font>
                                            <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#eb603b"></font></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td height="20" valign="top"></td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top"> 
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tbody><tr>
                                                    <td colspan="2" bgcolor="#f3f3f3" style="padding:5px">
                                                        <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535"><strong>Information about your order:</strong></font>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                    </td></tr>
                                                 <?php
                                    //$i = 1;
                                    foreach ($shop['OrderItem'] as $orderitem) {
                                        ?>
                                                <tr><td colspan="3" style="font-size:14px;padding:14px;padding-right:5px">
                                                        <table width="100%" bgcolor="" cellpadding="0" cellspacing="0" border="0" style="line-height:20px;">
                                                            <tbody><tr>
                                                                    <td width="30"><strong><?php echo $orderitem['quantity']; ?> X</strong></td>
                                                                    <td><strong><?php echo $orderitem['name']; ?></strong></td>
                                                                    <td width="60" align="right"><strong>$<?php echo $orderitem['subtotal']; ?></strong></td>
                                                                </tr>

                                                            </tbody></table>
                                                    </td></tr>
                                    <?php }?>
                                                <tr>
                                                <td bgcolor="#f3f3f3" style="padding:5px">
                                                <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535"><strong>Tax:</strong></font>
                                            </td>
                                            <td bgcolor="#f3f3f3" style="padding:5px" align="right">
                                                <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535">
                                                <strong> <?php  if($shop['Order']['tax']){
                                                      echo $shop['Order']['tax'];
                                                }else {
                                                    echo "$0";
                                                } ?></strong></font>
                                            </td>
                                        </tr>
                                        <br/><br/>

                                                <tr>
                                                    <td bgcolor="#f3f3f3" style="padding:5px">
                                                        <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535"><strong>Total:</strong></font>
                                                    </td>
                                                    <td bgcolor="#f3f3f3" style="padding:5px" align="right">
                                                        <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535">
                                                         <strong>  $<?php echo $shop['Order']['total']; ?></strong></font>
                                                    </td>
                                                </tr><br/><br/>
                                                 <tr>
                                                    <td bgcolor="#f3f3f3" style="padding:5px">
                                                        <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535"><strong>Total Order:</strong></font>
                                                    </td>
                                                    <td bgcolor="#f3f3f3" style="padding:5px" align="right">
                                                        <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535">
                                                         <strong>  $<?php echo $shop['Order']['order_item_count']; ?></strong></font>
                                                    </td>
                                                </tr><br/><br/>
                                                  <tr>
                                                    <td bgcolor="#f3f3f3" style="padding:5px">
                                                        <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535"><strong>Delivery Type:</strong></font>
                                                    </td>
                                                    <td bgcolor="#f3f3f3" style="padding:5px" align="right">
                                                        <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535">
                                                         <strong>  <?php if ($shop['Order']['delivery_status'] == 0) { echo "Delivery"; } else { echo "Take Away"; } ?></strong></font>
                                                    </td>
                                                </tr><br/><br/>
                                       
                                       
                                       
                                         <tr>
                                            <td bgcolor="#f3f3f3" style="padding:5px">
                                                <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535"><strong>Total Quantity:</strong></font>
                                            </td>
                                            <td bgcolor="#f3f3f3" style="padding:5px" align="right">
                                                <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535">
                                                <strong> <?php echo $shop['Order']['quantity']; ?></strong></font>
                                            </td>
                                        </tr><br/><br/>
                                        <tr>
                                            <td valign="top">
                                                <font style="font-family:Helvetica,sans-serif;font-size:14px;color:#353535"><strong>Customer Information:</strong></font><br>
                                                Name:- <?php echo $shop['Order']['first_name'] . " " . $shop['Order']['last_name']; ?>
                                                <br>
                                                Address:- <?php echo $shop['Order']['shipping_address']; ?>
                                                <br>  City:- <?php echo $shop['Order']['shipping_city']; ?> 
                                                <br>  Zip:- <?php echo $shop['Order']['shipping_zip']; ?> 
                                                <br>

                                                Email:- <?php echo $shop['Order']['email']; ?><br>
                                                Phone:- <?php echo $shop['Order']['phone']; ?><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="left" style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#212121">
                                                For any other questions, please call us at 123456789.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
<!--                                        <tr>
                                            <td colspan="2" align="left" style="font-family:Arial,Helvetica,sans-serif;font-size:14px">
                                                <a href="#"> <img src="<?php //echo $this->Html->url('/img/email_logoa.png', true); ?>" width="255" height="53"></a></td> 
                                        <a href="#"> We love your feedback</a></td>  
                                </td>
                            </tr>-->
                            <tr style="float: right;">
                                <td>
                                    Like us on: <a href="#"> <img src="<?php echo $this->Html->url('/img/email_facebook.png', true); ?>" width="40" height="40"></a>
                                    <a href="#"> <img src="<?php echo $this->Html->url('/img/email_twitter.png', true); ?>" width="40" height="40"></a>
                                    <a href="#"> <img src="<?php echo $this->Html->url('/img/email_Instagram.png', true); ?>" width="40" height="40"></a></td> 
                                </td>
                            </tr>

                        </tbody></table>
                </td>
            </tr>
            <tr>
                <td height="10" valign="top"></td>
            </tr>
        </tbody></table>
</td></tr><tr>
    <td height="10" valign="top"></td>
</tr>
<tr>
    <td valign="top" align="left"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody><tr> 
                </tr>
            </tbody></table></td>
</tr>
</tbody></table>
</div>