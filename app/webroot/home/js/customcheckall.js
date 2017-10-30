function selectallCHBox() {
    if ($('#selectall').prop('checked')) {      
        var inputs = document.getElementsByClassName('chechid');
        var checkboxes = [];
        for (var i = 0; i < inputs.length; i++) {

            if (inputs[i].type == 'checkbox') {
                inputs[i].checked = true;
            }
        }
    }
    else {
        var inputs = document.getElementsByClassName('chechid');
        var checkboxes = [];
        for (var i = 0; i < inputs.length; i++) {

            if (inputs[i].type == 'checkbox') {
                inputs[i].checked = false;
            }
        }

    }
}



////// JavaScript Document
//$(document).ready(function(){
//$('#titleCheck').live('click',function(){
//	var checked = $(this).attr('checked')?true:false;
//	/*if(checked==true){
//		$('#uniform-titleCheck span').addClass('checked');
//		}
//		if(checked==false){
//		$('#uniform-titleCheck span').removeClass('checked');
//		}*/
//    $('.checkAll').attr('checked',checked);
//});
//$('#titleCheck1').live('click',function(){
//	var checked = $(this).attr('checked')?true:false;
//	/*if(checked==true){
//		$('#uniform-titleCheck span').addClass('checked');
//		}
//		if(checked==false){
//		$('#uniform-titleCheck span').removeClass('checked');
//		}*/
//    $('.checkAll').attr('checked',checked);
//});
//$('#titleCheck2').live('click',function(){
//	var checked = $(this).attr('checked')?true:false;
//	/*if(checked==true){
//		$('#uniform-titleCheck span').addClass('checked');
//		}
//		if(checked==false){
//		$('#uniform-titleCheck span').removeClass('checked');
//		}*/
//    $('.checkAll').attr('checked',checked);
//});
//});