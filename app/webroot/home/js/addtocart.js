$(document).ready(function () {
    $('.addtocart').off("click").on('click', function (event) {
        $.ajax({
            type: "POST",
            url: "http://rajdeep.crystalbiltech.com/ecasnik/shop/itemupdate",
            data: {
                id: $(this).attr("id"),
                mods: $("#modselected").attr("value"),
                quantity: 1
            },
            dataType: "json",
            success: function (data) {
                console.log(data);
                var html = '<table class="table table_summary">';
                html += '<tbody>';
                $.each(data['OrderItem'], function (index, value) {
                    html += '<tr><td><a id=' + index + ' class="remove_item"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
                    html += '<td style="width:47px"><a href="#" class="cmins" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" class="cplus" id=' + index + '><i class="icon_plus_alt"></i></a>';
                    html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
                });
                html += '</tbody></table>';
                console.log(html);
                $('#added_items').html(html);
                $('#added_items').delay(2000).fadeIn('slow');
                var totalhtml = '<table class="table table_summary">';
                totalhtml += '<tbody>';
                totalhtml += '<tr><td>';
                totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
                totalhtml += '</td></tr>';
                totalhtml += '<tr><td>';
                totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
                totalhtml += '</td></tr>';
                totalhtml += '</tbody></table>';
                $('#total_items').html(totalhtml);
               // alert(data['alergi']);
                if((data['alergi']) || (data['productasso'])){
                   $('#myModal-'+data['id']).modal('show') ;
                }
                // $('#total_items').delay(2000).fadeIn('slow');
                rmv();
            },
            error: function () {
                alert('Error!');
            }
        });
        return false;
    });




    $.getJSON("http://rajdeep.crystalbiltech.com/ecasnik/shop/getcartitem", function (data) {
        var html = '<table class="table table_summary">';
        html += '<tbody>';
        $.each(data['OrderItem'], function (index, value) {
            html += '<tr><td><a  id=' + index + ' class="remove_item"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
            html += '<td style="width:47px"><a href="#" class="cmins" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" class="cplus" id=' + index + '><i class="icon_plus_alt"></i></a>';
            html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
        });
        html += '</tbody></table>';
        $('#added_items').html(html);

        var totalhtml = '<table class="table table_summary">';
        totalhtml += '<tbody>';
        totalhtml += '<tr><td>';
        totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
        totalhtml += '</td></tr>';
        totalhtml += '<tr><td>';
        totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
        totalhtml += '</td></tr>';
        totalhtml += '</tbody></table>';
        $('#total_items').html(totalhtml);
        rmv();
        //$('#total_items').delay(2000).fadeIn('slow');
    });
    $.getJSON("http://rajdeep.crystalbiltech.com/ecasnik/shop/reviewgetcartitem", function (data) {
        var html = '<table class="table table_summary">';
        html += '<tbody>';
        $.each(data['OrderItem'], function (index, value) {
            html += '<tr><td> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
            html += '<td style="width:47px">';
            html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
        });
        html += '</tbody></table>';
        $('#added_items_chk').html(html);

        var totalhtml = '<table class="table table_summary">';
        totalhtml += '<tbody>';
        totalhtml += '<tr><td>';
        totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
        totalhtml += '</td></tr>';
        totalhtml += '<tr><td>';
        totalhtml += '  Tax <span class="pull-right">$' + data['Order']['tax'] + '</span>';
        totalhtml += '</td></tr>';
        totalhtml += '<tr><td>';
        totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
        totalhtml += '</td></tr>';
        totalhtml += '</tbody></table>';
        $('#total_items_chk').html(totalhtml);
        rmv();
        //$('#total_items').delay(2000).fadeIn('slow');
    });




    $.getJSON("http://rajdeep.crystalbiltech.com/ecasnik/admin/shop/getcartitem", function (data) {
        if(data){
            adminrmv();
            var html = '<table class="table table_summary">';
            html += '<tbody>';
            $.each(data['OrderItem'], function (index, value) {
                html += '<tr><td><a data-tid = ' + value['tno'] + ' id=' + index + ' class="remove_itema"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
                html += '<td style="width:47px"><a href="#" data-tid = ' + value['tno'] + ' class="cminsa" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" data-tid = ' + value['tno'] + ' class="cplusa" id=' + index + '><i class="icon_plus_alt"></i></a>';
                html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
            });
            html += '</tbody></table>';
            $('#added_items_admin').html(html);

            var totalhtml = '<table class="table table_summary">';
            totalhtml += '<tbody>';
            totalhtml += '<tr><td>';
            totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
            totalhtml += '</td></tr>';
            totalhtml += '<tr><td>';
            totalhtml += '  Tax <span class="pull-right">$' + data['Order']['tax'] + '</span>';
            totalhtml += '</td></tr>';
            totalhtml += '<tr><td>';
            totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
            totalhtml += '</td></tr>';
            totalhtml += '</tbody></table>';
            $('#total_items_admin').html(totalhtml);
            adminrmv();
            //$('#total_items').delay(2000).fadeIn('slow');
        }else{
            console.log('No response');
        }
        
    });
    $('.adminaddtocart').off("click").on('click', function (event) {
        $.ajax({
            type: "POST",
            url: "http://rajdeep.crystalbiltech.com/ecasnik/admin/shop/itemupdate",
            data: {
                id: $(this).attr("id"),
                tid: $(this).data('tid'),
                mods: $("#modselected").attr("value"),
                quantity: 1
            },
            dataType: "json",
            success: function (data) {
                console.log(data);
                var html = '<table class="table table_summary">';
                html += '<tbody>';
                $.each(data['OrderItem'], function (index, value) {
                    html += '<tr><td><a id=' + index + ' data-tid = ' + value['tno'] + ' class="remove_itema"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
                    html += '<td style="width:47px"><a href="#" data-tid = ' + value['tno'] + ' class="cminsa" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" data-tid = ' + value['tno'] + ' class="cplusa" id=' + index + '><i class="icon_plus_alt"></i></a>';
                    html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
                });
                html += '</tbody></table>';
                console.log(html);
                $('#added_items_admin').html(html);
                $('#added_items_admin').delay(2000).fadeIn('slow');
                var totalhtml = '<table class="table table_summary">';
                totalhtml += '<tbody>';
                totalhtml += '<tr><td>';
                totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
                totalhtml += '</td></tr>';
                totalhtml += '<tr><td>';
                totalhtml += '  Tax <span class="pull-right">$' + data['Order']['tax'] + '</span>';
                totalhtml += '</td></tr>';
                totalhtml += '<tr><td>';
                totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
                totalhtml += '</td></tr>';
                totalhtml += '</tbody></table>';
                $('#total_items_admin').html(totalhtml);
                // $('#total_items').delay(2000).fadeIn('slow');
                adminrmv();
            },
            error: function () {
                alert('Error!');
            }
        });
        return false;
    });
    function adminrmv() {
        $('.remove_itema').off("click").on('click', function () {
            $.ajax({
                type: "POST",
                url: "http://rajdeep.crystalbiltech.com/ecasnik/admin/shop/crtremove",
                data: {
                    id: $(this).attr("id"),
                    tid: $(this).data('tid'),
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    var html = '<table class="table table_summary">';
                    html += '<tbody>';
                    $.each(data['OrderItem'], function (index, value) {
                        html += '<tr><td><a id=' + index + ' data-tid = ' + value['tno'] + ', class="remove_itema"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
                        html += '<td style="width:47px"><a href="#" data-tid = ' + value['tno'] + ' class="cminsa" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" class="cplusa" data-tid = ' + value['tno'] + ' id=' + index + '><i class="icon_plus_alt"></i></a>';
                        html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
                    });
                    html += '</tbody></table>';
                    console.log(html);
                    $('#added_items_admin').html(html);
                    //$('#added_items').delay(2000).fadeIn('slow');
                    var totalhtml = '<table class="table table_summary">';
                    totalhtml += '<tbody>';
                    totalhtml += '<tr><td>';
                    totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  Tax <span class="pull-right">$' + data['Order']['tax'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '</tbody></table>';
                    $('#total_items_admin').html(totalhtml);
                    adminrmv();
                    // $('#total_items').delay(2000).fadeIn('slow');
                },
                error: function () {
                    alert('Error!');
                }
            });
            return false;
        });
        $('.cplusa').off("click").on('click', function () {
            //  alert('hello');
            $.ajax({
                type: "POST",
                url: "http://rajdeep.crystalbiltech.com/ecasnik/admin/shop/addremovecart",
                data: {
                    id: $(this).attr("id"),
                    tid: $(this).data('tid'),
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    var html = '<table class="table table_summary">';
                    html += '<tbody>';
                    $.each(data['OrderItem'], function (index, value) {
                        html += '<tr><td><a id=' + index + ' data-tid = ' + value['tno'] + ' class="remove_itema"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
                        html += '<td style="width:47px"><a href="#" data-tid = ' + value['tno'] + ' class="cminsa" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" data-tid = ' + value['tno'] + ' class="cplusa" id=' + index + '><i class="icon_plus_alt"></i></a>';
                        html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
                    });
                    html += '</tbody></table>';
                    console.log(html);
                    $('#added_items_admin').html(html);
                    //$('#added_items').delay(2000).fadeIn('slow');
                    var totalhtml = '<table class="table table_summary">';
                    totalhtml += '<tbody>';
                    totalhtml += '<tr><td>';
                    totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  Tax <span class="pull-right">$' + data['Order']['tax'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '</tbody></table>';
                    $('#total_items_admin').html(totalhtml);
                    adminrmv();
                    // $('#total_items').delay(2000).fadeIn('slow');
                },
                error: function () {
                    alert('Error!');
                }
            });
            return false;
        });
        $('.cminsa').off("click").on('click', function () {
            $.ajax({
                type: "POST",
                url: "http://rajdeep.crystalbiltech.com/ecasnik/admin/shop/minusremovecart",
                data: {
                    id: $(this).attr("id"),
                    tid: $(this).data('tid'),
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    var html = '<table class="table table_summary">';
                    html += '<tbody>';
                    $.each(data['OrderItem'], function (index, value) {
                        html += '<tr><td><a id=' + index + ' data-tid = ' + value['tno'] + ' class="remove_itema"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
                        html += '<td style="width:47px"><a href="#" data-tid = ' + value['tno'] + ' class="cminsa" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" data-tid = ' + value['tno'] + ' class="cplusa" id=' + index + '><i class="icon_plus_alt"></i></a>';
                        html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
                    });
                    html += '</tbody></table>';
                    console.log(html);
                    $('#added_items_admin').html(html);
                    //$('#added_items').delay(2000).fadeIn('slow');
                    var totalhtml = '<table class="table table_summary">';
                    totalhtml += '<tbody>';
                    totalhtml += '<tr><td>';
                    totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  Tax <span class="pull-right">$' + data['Order']['tax'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '</tbody></table>';
                    $('#total_items_admin').html(totalhtml);
                    adminrmv();
                    // $('#total_items').delay(2000).fadeIn('slow');
                },
                error: function () {
                    alert('Error!');
                }
            });
            return false;
        });
    }
});

function rmv() {
        $('.remove_item').off("click").on('click', function () {
            $.ajax({
                type: "POST",
                url: "http://rajdeep.crystalbiltech.com/ecasnik/shop/crtremove",
                data: {
                    id: $(this).attr("id"),
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    var html = '<table class="table table_summary">';
                    html += '<tbody>';
                    $.each(data['OrderItem'], function (index, value) {
                        html += '<tr><td><a id=' + index + ' class="remove_item"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
                        html += '<td style="width:47px"><a href="#" class="cmins" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" class="cplus" id=' + index + '><i class="icon_plus_alt"></i></a>';
                        html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
                    });
                    html += '</tbody></table>';
                    console.log(html);
                    $('#added_items').html(html);
                    //$('#added_items').delay(2000).fadeIn('slow');
                    var totalhtml = '<table class="table table_summary">';
                    totalhtml += '<tbody>';
                    totalhtml += '<tr><td>';
                    totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '</tbody></table>';
                    $('#total_items').html(totalhtml);
                    rmv();
                    // $('#total_items').delay(2000).fadeIn('slow');
                },
                error: function () {
                    alert('Error!');
                }
            });
            return false;
        });
        $('.cplus').off("click").on('click', function () {
            //  alert('hello');
            $.ajax({
                type: "POST",
                url: "http://rajdeep.crystalbiltech.com/ecasnik/shop/addremovecart",
                data: {
                    id: $(this).attr("id"),
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    var html = '<table class="table table_summary">';
                    html += '<tbody>';
                    $.each(data['OrderItem'], function (index, value) {
                        html += '<tr><td><a id=' + index + ' class="remove_item"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
                        html += '<td style="width:47px"><a href="#" class="cmins" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" class="cplus" id=' + index + '><i class="icon_plus_alt"></i></a>';
                        html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
                    });
                    html += '</tbody></table>';
                    console.log(html);
                    $('#added_items').html(html);
                    //$('#added_items').delay(2000).fadeIn('slow');
                    var totalhtml = '<table class="table table_summary">';
                    totalhtml += '<tbody>';
                    totalhtml += '<tr><td>';
                    totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '</tbody></table>';
                    $('#total_items').html(totalhtml);
                    rmv();
                    // $('#total_items').delay(2000).fadeIn('slow');
                },
                error: function () {
                    alert('Error!');
                }
            });
            return false;
        });
        $('.cmins').off("click").on('click', function () {
            $.ajax({
                type: "POST",
                url: "http://rajdeep.crystalbiltech.com/ecasnik/shop/minusremovecart",
                data: {
                    id: $(this).attr("id"),
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    var html = '<table class="table table_summary">';
                    html += '<tbody>';
                    $.each(data['OrderItem'], function (index, value) {
                        html += '<tr><td><a id=' + index + ' class="remove_item"><i class="icon_minus_alt"></i></a> <strong>' + value['quantity'] + 'x</strong>' + value['name'] + '';
                        html += '<td style="width:47px"><a href="#" class="cmins" id=' + index + '><i class="icon_minus_alt"></i></a> <a href="#" class="cplus" id=' + index + '><i class="icon_plus_alt"></i></a>';
                        html += '<td><strong class="pull-right">' + value['price'] + '</strong></td></tr>';
                    });
                    html += '</tbody></table>';
                    console.log(html);
                    $('#added_items').html(html);
                    //$('#added_items').delay(2000).fadeIn('slow');
                    var totalhtml = '<table class="table table_summary">';
                    totalhtml += '<tbody>';
                    totalhtml += '<tr><td>';
                    totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '</tbody></table>';
                    $('#total_items').html(totalhtml);
                    rmv();
                    // $('#total_items').delay(2000).fadeIn('slow');
                },
                error: function () {
                    alert('Error!');
                }
            });
            return false;
        });
        $('#pincheck').off("click").on('click', function () {
            $.ajax({
                type: "POST",
                url: "http://rajdeep.crystalbiltech.com/ecasnik/shop/checkpin",
                data: {
                    id: $('#chpin').val(),
                    rid: $('#reid').val(),
                },
                dataType: "json",
                success: function (data) {
                    //console.log(JSON.stringify(data));
                    //alert("sss");
                    //console.log(data.status);
                    if(data.status == false){
                       
                        $("#dlchrg").show();
                        $('.btn_full').hide();
                        $('#dlchrg').html('');
                        $('#dlchrg').html('not available on this pin please try again');
                    }else{
                        var cart = data.cart;
                        var totalhtml = '<table class="table table_summary">';
                        totalhtml += '<tbody>';
                        totalhtml += '<tr><td>';
                        totalhtml += 'Subtotal <span class="pull-right">$' + cart['Order']['subtotal'] + '</span>';
                        totalhtml += '</td></tr>';
                        totalhtml += '<tr><td>';
                        totalhtml += 'Tax <span class="pull-right">$' + cart['Order']['tax'] + '</span>';
                        totalhtml += '</td></tr>';
                        totalhtml += '<tr><td>';
                        totalhtml += 'Delivery Charge <span class="pull-right">$' + cart['Order']['dlcharge'] + '</span>';
                        totalhtml += '</td></tr>';
                        totalhtml += '<tr><td>';
                        totalhtml += '  TOTAL <span class="pull-right">$' + cart['Order']['total'] + '</span>';
                        totalhtml += '</td></tr>';
                        totalhtml += '</tbody></table>';
                        $('#total_items').html(totalhtml);
                        $('#dlchrg').html('');
                        $('#dlchrg').html('Delivery charge: $' + cart['Order']['dlcharge']+'<span id="confirmpin" onclick="displayPin('+cart['Order']['pin']+')">&nbsp; OK</span>');
                        $('#dlchrg').show();
                        //$('#chpin').hide();
                        $('#reid').hide();
                        
                        localStorage.setItem('dlcharge', cart['Order']['dlcharge']);
                        rmv();
                    }
                    $('#total_items').delay(2000).fadeIn('slow');
                },
                error: function () {
                    alert('Error!');
                }
            });
            return false;
        });
        
//        $('#confirmpin').off("click").on("click", function () {
//            $('#chpin').replaceWith("<span>" + this.value + "</span>");
//            $('#chpin').hide();
//            $(this).hide();
//        })

        $('#deli').off("click").on("click", function () {
            if (this.checked) {
                $('#showdiv').show();
                $('#chpin').show();
                $('#chpin').val('');
                $('#pincheck').show();
                $('#selected_pin').hide();
                $('#dlchrg').hide();
                $('.btn_full').hide();
            }
        });
       
        $('#tkway').off("click").on("click", function () {
            if (this.checked) {
                $('#showdiv').hide();
                 $('.btn_full').show();
            }
            $.ajax({
                type: "POST",
                url: "http://rajdeep.crystalbiltech.com/ecasnik/shop/takeawaypin",
                data: {
                    id: $('#chpin').val(),
                    rid: $('#reid').val(),
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    var totalhtml = '<table class="table table_summary">';
                    totalhtml += '<tbody>';
                    totalhtml += '<tr><td>';
                    totalhtml += 'Subtotal <span class="pull-right">$' + data['Order']['subtotal'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += 'Tax <span class="pull-right">$' + data['Order']['tax'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '<tr><td>';
                    totalhtml += '  TOTAL <span class="pull-right">$' + data['Order']['total'] + '</span>';
                    totalhtml += '</td></tr>';
                    totalhtml += '</tbody></table>';
                    $('#total_items_chk').html(totalhtml);
                    rmv();
                    // $('#total_items').delay(2000).fadeIn('slow');
                },
                error: function () {
                    alert('Error!');
                }
            });
           // return false;

        });
    }

function displayPin(pin){
    $('#chpin').after("<span id='selected_pin'>: " + pin + "</span>");
    $('#chpin').hide();
    $('#pincheck').hide();
    $('#confirmpin').hide();
    $('.btn_full').show();
    
    $.ajax({
        type: "GET",
        url: "http://rajdeep.crystalbiltech.com/ecasnik/shop/updateTotal",
        dataType: "json",
        success: function (data) {
            console.log(data);
            var cart = data.cart;
            var totalhtml = '<table class="table table_summary">';
            totalhtml += '<tbody>';
            totalhtml += '<tr><td>';
            totalhtml += 'Subtotal <span class="pull-right">$' + cart['Order']['subtotal'] + '</span>';
            totalhtml += '</td></tr>';
            totalhtml += '<tr><td>';
            totalhtml += 'Tax <span class="pull-right">$' + cart['Order']['tax'] + '</span>';
            totalhtml += '</td></tr>';
            totalhtml += '<tr><td>';
            totalhtml += 'Delivery Charge <span class="pull-right">$' + cart['Order']['dlcharge'] + '</span>';
            totalhtml += '</td></tr>';
            totalhtml += '<tr><td>';
            totalhtml += '  TOTAL <span class="pull-right">$' + cart['Order']['total'] + '</span>';
            totalhtml += '</td></tr>';
            totalhtml += '</tbody></table>';
            $('#total_items_chk').html(totalhtml);
            rmv();
            // $('#total_items').delay(2000).fadeIn('slow');
        },
        error: function () {
            alert('Error!');
        }
    });

}