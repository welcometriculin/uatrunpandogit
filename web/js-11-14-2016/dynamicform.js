jQuery(document).ready(function(){
	ajaxRequest();
	//setTimeout(function(){ accordionOpen(); }, 500);
	stepsDisabledLabels();
	function toggleChevron(e) {
        $(e.target)
            .prev('.panel-heading')
            .find("i.indicator")
            .toggleClass('fa-plus fa-minus');

    }
    $('#accordion').on('hidden.bs.collapse', toggleChevron);
    $('#accordion').on('shown.bs.collapse', toggleChevron);
    $('#accordion5').on('hidden.bs.collapse', toggleChevron);
    $('#accordion5').on('shown.bs.collapse', toggleChevron);
    
	function toggleChevronProductSetting(e) {
        $(e.target)
            .prev('.panel-heading')
            .find("i.indicator")
            .toggleClass('fa-plus fa-plus2 fa-minus fa-minus2');

    }
    $('#accordion2').on('hidden.bs.collapse', toggleChevronProductSetting);
    $('#accordion2').on('shown.bs.collapse', toggleChevronProductSetting);
    /* dynamic form switch button start*/
	$('.toggle-switch').find('label span:first').addClass('input-checked');
	  /* dynamic form switch button end*/
	//if one open other is closer accordian start
	$myGroup1 = $('#refresh_form ');
	$myGroup1.on('show.bs.collapse','.collapse', function() {
	    $myGroup1.find('.collapse.in').collapse('hide');
	});
	//if one open other is closer accordian end
$("input:radio[name='FormBuilder[step_1_field1_require]']").on('change',function() {
	value = $(this).val();
	if(value == 1) {
		$('#step_1_subb').css('display','block');
		$('#step_1_subb input').removeAttr('disabled');
		//$("input:radio[name='FormBuilder[step_1_field1_mandatory]'][value='1']").prop("checked",true);
		$("input[name='FormBuilder[step_1_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
		$("input[name='FormBuilder[step_1_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
		$("input:radio[name='FormBuilder[step_1_field1_mandatory]'][value='1']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_1_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
		//$("input:radio[name='FormBuilder[step_1_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
		$("input[name='FormBuilder[step_1_field1_mandatory]'][value='1']").click();
		$("input[name='FormBuilder[step_1_field1_mandatory]'][value='1']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_1_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
	} else if(value == 0) {
		$('#step_1_subb input').attr('disabled','disabled');
		//$("input:radio[name='FormBuilder[step_1_field1_mandatory]'][value='0']").prop("checked",true);
		$("#formbuilder-step_1_field1_label").val('');
		$('#step_1_subb').css('display','none');
		$("input:radio[name='FormBuilder[step_1_field1_require]']").parents(':eq(2)').addClass('grey-bg');
		$("input[name='FormBuilder[step_1_field1_mandatory]'][value='0']").click();
		$("input[name='FormBuilder[step_1_field1_mandatory]'][value='0']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_1_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
	} 
});

$("input:radio[name='FormBuilder[step_1_field1_mandatory]']").on('change',function() {
	value = $(this).val();
	if(value == 1) {
		$("input:radio[name='FormBuilder[step_1_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
	} else if(value == 0) {
		$("input:radio[name='FormBuilder[step_1_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
	} 
});
// step 2 hide and show	
$("input:radio[name='FormBuilder[step_2_field1_require]']").on('change',function() {
    var value = $(this).val();
	if(value == 1) {
		$('#step_2_sub').css('display','block');
		$('#step_2_sub input').removeAttr('disabled');
		$('#step_2_sub select').removeAttr('disabled');
		//$("input:radio[name='FormBuilder[step_2_field1_mandatory]'][value='1']").prop("checked",true);
		$("input[name='FormBuilder[step_2_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
		$("input[name='FormBuilder[step_2_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
		$("input:radio[name='FormBuilder[step_2_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
		//$("input:radio[name='FormBuilder[step_2_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
		$("input[name='FormBuilder[step_2_field1_mandatory]'][value='1']").click();
		$("input[name='FormBuilder[step_2_field1_mandatory]'][value='1']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_2_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
	} else if(value == 0) {
		$('#step_2_sub input').attr('disabled','disabled');
		$('#step_2_sub select').attr('disabled','disabled');
		//$("input:radio[name='FormBuilder[step_2_field1_mandatory]'][value='0']").prop("checked",true);
		$("input[name='FormBuilder[step_2_field1_mandatory]'][value='0']").click();
		$("input[name='FormBuilder[step_2_field1_mandatory]'][value='0']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_2_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
		$("#formbuilder-step_2_field1_label").val('');
		if ($("#formbuilder-step_2_field1_data_type").val() == 'edittext') {
			$("#formbuilder-step_2_field1_validation_type").val('');
			$("#step_2_field1_no_of_chars").hide();
			$("#step_2_field1_validation_type").css("display","none");
			$(".step_2_field1_field_boxes").remove();
		} else if ($("#formbuilder-step_2_field1_data_type").val() == 'radio') {
			$("#formbuilder-step_2_field1_field_value").val('');
			$(".step_2_field1_field_boxes").remove();
			$("#step_2_field1_feild_vlaue").css("display","none");
		} else if ($("#formbuilder-step_2_field1_data_type").val() == 'checkbox') {
			$("#formbuilder-step_2_field1_field_value").val('');
			$(".step_2_field1_field_boxes").remove();
			$("#step_2_field1_feild_vlaue").css("display","none");
		} else if ($("#formbuilder-step_2_field1_data_type").val() == 'selectbox') {
			$("#formbuilder-step_2_field1_field_value").val('');
			$(".step_2_field1_field_boxes").remove();
			$("#step_2_field1_feild_vlaue").css("display","none");
		} else if ($("#formbuilder-step_2_field1_data_type").val() == 'rating') {
			$("#formbuilder-step_2_field1_field_value").val('');
			$(".step_2_field1_field_boxes").remove();
			$("#step_2_field1_feild_vlaue").css("display","none");
		}
		$("#formbuilder-step_2_field1_data_type").val('');
		$("#formbuilder-step_2_field1_no_chars").val('');
		$("#formbuilder-step_2_field1_validation_type").val('');
		$("input:radio[name='FormBuilder[step_2_field1_require]']").parents(':eq(2)').addClass('grey-bg');
		$('#step_2_sub').css('display','none');
		$(".step_2_field1_field_boxes").remove();
    } 
});
$("input:radio[name='FormBuilder[step_2_field1_mandatory]']").on('change',function() {
	//alert('value');
	value = $(this).val();
	if(value == 1) {
		$("input:radio[name='FormBuilder[step_2_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
	} else if(value == 0) {
		$("input:radio[name='FormBuilder[step_2_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
	} 
});
//step 2		
$('#formbuilder-step_2_field1_data_type').on('change',function(){
	var data_type_value = $('#formbuilder-step_2_field1_data_type').val();
	if(data_type_value == 'edittext') {
	$('#formbuilder-step_2_field1_validation_type').val('');
	$('#step_2_field1_feild_vlaue input').attr("disabled","disabled");
	$('#step_2_field1_feild_vlaue').css("display","none");
	//$('#step_2_field1_validation_type select').removeAttr('disabled');;
	$('#step_2_field1_no_of_chars').show();
	$('.field-formbuilder-step_2_field1_no_chars').show();
	$('#step_2_field1_validation_type').css("display","block");
	$('.field-formbuilder-step_2_field1_validation_type').css("display","block");
	$(".step_2_field1_field_boxes").remove();
  } else if(data_type_value == 'radio' || data_type_value == 'selectbox' || data_type_value == 'checkbox') {
	//$('#step_2_field1_validation_type select').attr("disabled","disabled");
	 $("#formbuilder-step_2_field1_no_of_chars").val('');
	 $('#formbuilder-step_2_field1_field_value').val('');
	 $('#step_2_field1_no_of_chars').hide();
	 $('.field-formbuilder-step_2_field1_no_chars').hide();
	 $('#step_2_field1_validation_type').css("display","none");
	 $('#step_2_field1_feild_vlaue input').removeAttr('disabled');
	 $('#step_2_field1_feild_vlaue').css("display","block");
	 $("#formbuilder-step_2_field1_field_value").val('');
	 $(".step_2_field1_field_boxes").remove();
 }  else if(data_type_value == 'textarea') { 
	 $('#step_2_field1_no_of_chars').show();
	 $('.field-formbuilder-step_2_field1_no_chars').show();
	// $("#formbuilder-step_2_field1_no_of_chars").val('');
	 $("#formbuilder-step_2_field1_validation_type").val('');
	 $('#step_2_field1_validation_type').css("display","none");
	 $("#formbuilder-step_2_field1_field_value").val('');
	 $(".step_2_field1_field_boxes").remove();
	 $("#step_2_field1_feild_vlaue").css("display","none");
	 $('#step_2_field1_feild_vlaue input').attr("disabled","disabled");
 }else if(data_type_value == 'rating') { 
	 $('#step_2_field1_no_of_chars').hide();
	 $('.field-formbuilder-step_2_field1_no_chars').hide();
	 $("#formbuilder-step_2_field1_no_of_chars").val('');
	 $("#formbuilder-step_2_field1_validation_type").val('');
	 $('#step_2_field1_validation_type').css("display","none");
	 $("#formbuilder-step_2_field1_field_value").val('');
	 $(".step_2_field1_field_boxes").remove();
	 $("#step_2_field1_feild_vlaue").css("display","none");
	 $('#step_2_field1_feild_vlaue input').attr("disabled","disabled");
 }  
 
 else {
	//$('#step_2_field1_validation_type select').attr("disabled","disabled");
	$('#step_2_field1_validation_type').css("display","none");
	$("#formbuilder-step_2_field1_validation_type").val('');
	$('#step_3_field1_no_of_chars').hide();
	$('.field-formbuilder-step_3_field3_no_chars').hide();
	$("#formbuilder-step_3_field3_no_of_chars").val('');
}
});		
//add more
$("#step_2_field1_feild_vlaue .form-group .add-txt").click(function(){
           var no = $(".form-group").length + 1;
            var more_textbox = $('<div class="form-group step_2_field1_field_boxes">' +
            '</span></label>' +
            '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class = "dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_2_field1_field_boxes[]" id="txtbox' + no + '" />' +
            '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
            '</div></div><div class="help-block"></div></div></div> ');
            more_textbox.hide();
            $("#step_2_field1_feild_vlaue .form-group:last").after(more_textbox);
            more_textbox.fadeIn("slow");
            $(".field-formbuilder-step_2_field1_field_value").find('.help-error').html('');
            return false;
        });

// Remove 

	//Remove
        $('#step_2_field1_feild_vlaue').on('click', '.remove-txt', function(){
            $(this).parents(':eq(4)').fadeOut("slow", function() {
                $(this).remove();
              /*   $('.label-numbers').each(function( index ){
                    $(this).text( index + 1 );
                }); */
            });
            return false;
        });
//step 3 all fiels
    	$("input:radio[name='FormBuilder[step_3_require]']").on('change',function() {
    	    var value = $(this).val();
    		if(value == 1) {
    			$('#step_3_fields').css('display','block');
    			$("input:radio[name='FormBuilder[step_3_field1_require]'][value='1']").prop("checked",true);
    			$("input:radio[name='FormBuilder[step_3_field2_require]'][value='1']").prop("checked",true);
    			$("input:radio[name='FormBuilder[step_3_field3_require]'][value='1']").prop("checked",true);
    			$("input:radio[name='FormBuilder[step_3_field4_require]'][value='1']").prop("checked",true);
    			$("input:radio[name='FormBuilder[step_3_field5_require]'][value='1']").prop("checked",true);
    			$('#step_3_fields input').removeAttr('disabled');
    			$('#step_3_fields select').removeAttr('disabled');
    			$("input:radio[name='FormBuilder[step_3_require]']").parents(':eq(2)').removeClass('grey-bg');
    			stepsDisabled();
    		} else if(value == 0) {
    			$('#step_3_fields').css('display','none');
    			$("input:radio[name='FormBuilder[step_3_field1_require]'][value='0']").prop("checked",true);
    			$("input:radio[name='FormBuilder[step_3_field2_require]'][value='0']").prop("checked",true);
    			$("input:radio[name='FormBuilder[step_3_field3_require]'][value='0']").prop("checked",true);
    			$("input:radio[name='FormBuilder[step_3_field4_require]'][value='0']").prop("checked",true);
    			$("input:radio[name='FormBuilder[step_3_field5_require]'][value='0']").prop("checked",true);
    			$('#step_3_fields input').attr('disabled','disabled');
    			$('#step_3_fields select').attr('disabled','disabled');
    			$("input:radio[name='FormBuilder[step_3_require]']").parents(':eq(2)').addClass('grey-bg');
    			$(".step_3_field1_field_boxes").remove();
    			$(".step_3_field2_field_boxes").remove();
    			$(".step_3_field3_field_boxes").remove();
    			$(".step_3_field4_field_boxes").remove();
    			$(".step_3_field5_field_boxes").remove();
    	    } 
    		
    	});
    	$("input:radio[name='FormBuilder[step_3_field1_mandatory]']").on('change',function() {
    		value = $(this).val();
    		if(value == 1) {
    			$("input:radio[name='FormBuilder[step_3_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
    		} else if(value == 0) {
    			$("input:radio[name='FormBuilder[step_3_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
    		} 
    	});
//step 3 	
	//field 1 
	$("input:radio[name='FormBuilder[step_3_field1_require]']").on('change',function() {
    var value = $(this).val();
	if(value == 1) {
		$('#step_3_field1').css('display','block');
	//$('#step_3_field1 input').removeAttr('disabled');
	//$('#step_3_field1 select').removeAttr('disabled');
		//$("input:radio[name='FormBuilder[step_3_field1_mandatory]'][value='1']").prop("checked",true);
		$("input[name='FormBuilder[step_3_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
		$("input[name='FormBuilder[step_3_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
		$("input:radio[name='FormBuilder[step_3_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
		//$("input:radio[name='FormBuilder[step_3_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
		$("input[name='FormBuilder[step_3_field1_mandatory]'][value='1']").click();
		$("input[name='FormBuilder[step_3_field1_mandatory]'][value='1']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_3_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
	} else if(value == 0) {
	//$('#step_3_field1 input').attr('disabled','disabled');
	//$('#step_3_field1 select').attr('disabled','disabled');
		//$("input:radio[name='FormBuilder[step_3_field1_mandatory]'][value='0']").prop("checked",true);
		$("input[name='FormBuilder[step_3_field1_mandatory]'][value='0']").click();
		$("input[name='FormBuilder[step_3_field1_mandatory]'][value='0']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_3_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
		$('#step_3_field1_vlaue input').attr("disabled","disabled");
		$("#formbuilder-step_3_field1_label").val('');
		if ($('#formbuilder-step_3_field1_data_type').val() == 'edittext') {
			$("#formbuilder-step_3_field1_validation_type").val('');
			$("#step_3_field1_validation_type").css("display","none");
			$('#step_3_field1_vlaue input').attr("disabled","disabled");
			$("#formbuilder-step_3_field1_no_chars").val('');
			$('#step_3_field1_no_of_chars').hide();
			$('.field-formbuilder-step_3_field1_no_chars').hide();
			$(".step_3_field1_field_boxes").remove();
		} else if ($('#formbuilder-step_3_field1_data_type').val() == 'radio') {
			$("#formbuilder-step_3_field1_field_value").val('');
			$(".step_3_field1_field_boxes").remove();
			$("#step_3_field1_vlaue").css("display","none");
		} else if ($('#formbuilder-step_3_field1_data_type').val() == 'checkbox') {
			$("#formbuilder-step_3_field1_field_value").val('');
			$(".step_3_field1_field_boxes").remove();
			$("#step_3_field1_vlaue").css("display","none");
		} else if ($('#formbuilder-step_3_field1_data_type').val() == 'selectbox') {
			$("#formbuilder-step_3_field1_field_value").val('');
			$(".step_3_field1_field_boxes").remove();
			$("#step_3_field1_vlaue").css("display","none");
		} else if ($('#formbuilder-step_3_field1_data_type').val() == 'rating') {
			$("#formbuilder-step_3_field1_field_value").val('');
			$(".step_3_field1_field_boxes").remove();
			$("#step_3_field1_vlaue").css("display","none");
		}
		$("#formbuilder-step_3_field1_no_chars").val('');
		$('#formbuilder-step_3_field1_data_type').val('');
		$("#formbuilder-step_3_field1_validation_type").val('');
		$("input:radio[name='FormBuilder[step_3_field1_require]']").parents(':eq(2)').addClass('grey-bg');
		$('#step_3_field1').css('display','none');
		$(".step_3_field1_field_boxes").remove();
    } 
	
});		
$('#formbuilder-step_3_field1_data_type').on('change',function(){
	var data_type_value = $('#formbuilder-step_3_field1_data_type').val();
	//alert(data_type_value);
	if(data_type_value == 'edittext') {
	//$('#step_3_field1_validation_type select').removeAttr('disabled');;
	$('#step_3_field1_no_of_chars').show();
	$('.field-formbuilder-step_3_field1_no_chars').show();
	$("#formbuilder-step_3_field1_no_of_chars").val('');
	$("#formbuilder-step_3_field1_validation_type").val('');
	$('#step_3_field1_validation_type').css("display","block");
	$('.field-formbuilder-step_3_field1_validation_type').css("display","block");
	$('#step_3_field1_vlaue input').attr("disabled","disabled");
	$('#step_3_field1_vlaue').css("display","none");
	$(".step_3_field1_field_boxes").remove();
  } else if(data_type_value == 'radio' || data_type_value == 'selectbox' || data_type_value == 'checkbox' || data_type_value == 'linear') {
	//$('#step_3_field1_validation_type select').attr("disabled","disabled");
	$("#formbuilder-step_3_field1_field_value").val('');
	$(".step_3_field1_field_boxes").remove();
	$('#step_3_field1_no_of_chars').hide();
	$('.field-formbuilder-step_3_field1_no_chars').hide();
	$("#formbuilder-step_3_field1_no_of_chars").val('');
	$("#formbuilder-step_3_field1_validation_type").val('');
	$('#step_3_field1_validation_type').css("display","none");
	$('#step_3_field1_vlaue input').removeAttr('disabled');
	$('#step_3_field1_vlaue').css("display","block");
 } else if(data_type_value == 'textarea') {
	$('#step_3_field1_no_of_chars').show();
	$('.field-formbuilder-step_3_field1_no_chars').show();
	$("#formbuilder-step_3_field1_validation_type").val('');
	$('#step_3_field1_validation_type').css("display","none");
	$("#formbuilder-step_3_field1_field_value").val('');
	$(".step_3_field1_field_boxes").remove();
	$("#step_3_field1_vlaue").css("display","none");
	$('#step_3_field1_vlaue input').attr("disabled","disabled");
 } else if(data_type_value == 'rating') {
	$('#step_3_field1_no_of_chars').hide();
	$('.field-formbuilder-step_3_field1_no_chars').hide();
	$("#formbuilder-step_3_field1_no_of_chars").val('');
	$("#formbuilder-step_3_field1_validation_type").val('');
	$('#step_3_field1_validation_type').css("display","none");
	$("#formbuilder-step_3_field1_field_value").val('');
	$(".step_3_field1_field_boxes").remove();
	$("#step_3_field1_vlaue").css("display","none");
	$('#step_3_field1_vlaue input').attr("disabled","disabled");
	 }else {
		//$('#step_2_field1_validation_type select').attr("disabled","disabled");
	$('#step_3_field1_no_of_chars').hide();
	$('.field-formbuilder-step_3_field1_no_chars').hide();
	$("#formbuilder-step_3_field1_no_chars").val('');
	$("#formbuilder-step_3_field1_validation_type").val('');
	$('#step_3_field1_validation_type').css("display","none");
	$("#formbuilder-step_3_field1_field_value").val('');
	$(".step_3_field1_field_boxes").remove();
	$("#step_3_field1_vlaue").css("display","none");
	}
})		
//add more
$("#step_3_field1_vlaue .form-group .add-txt").click(function(){
           var no = $(".form-group").length + 1;
            var more_textbox = $('<div class="form-group step_3_field1_field_boxes">' +
            '</span></label>' +
            '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_3_field1_field_boxes[]" id="txtbox' + no + '" />' +
            '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
            '</div></div><div class="help-block"></div></div></div>');
            more_textbox.hide();
            $("#step_3_field1_vlaue .form-group:last").after(more_textbox);
            more_textbox.fadeIn("slow");
            $(".field-formbuilder-step_3_field1_field_value").find('.help-error').html('');
            return false;
        });

// Remove 

	//Remove
        $('#step_3_field1_vlaue').on('click', '.remove-txt', function(){
            $(this).parents(':eq(4)').fadeOut("slow", function() {
                $(this).remove();
              /*   $('.label-numbers').each(function( index ){
                    $(this).text( index + 1 );
                }); */
            });
            return false;
        });    
//step 3 field 2
	//field 3 
	$("input:radio[name='FormBuilder[step_3_field2_require]']").on('change',function() {
    var value = $(this).val();
	if(value == 1) {
		$('#step_3_field2').css('display','block');
	//$('#step_3_field2 input').removeAttr('disabled');
	//$('#step_3_field2 select').removeAttr('disabled');
		//$("input:radio[name='FormBuilder[step_3_field2_mandatory]'][value='1']").prop("checked",true);
		$("input[name='FormBuilder[step_3_field2_mandatory]'][value='0']").next('span').removeClass('input-checked');
		$("input[name='FormBuilder[step_3_field2_mandatory]'][value='1']").next('span').addClass('input-checked');
		$("input:radio[name='FormBuilder[step_3_field2_require]']").parents(':eq(2)').removeClass('grey-bg');
		$("input:radio[name='FormBuilder[step_3_field2_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
		$("input[name='FormBuilder[step_3_field2_mandatory]'][value='1']").click();
		$("input[name='FormBuilder[step_3_field2_mandatory]'][value='1']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_3_field2_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
	} else if(value == 0) {
	//$('#step_3_field2 input').attr('disabled','disabled');
	//$('#step_3_field2 select').attr('disabled','disabled');
		//$("input:radio[name='FormBuilder[step_3_field2_mandatory]'][value='0']").prop("checked",true);
		$("input[name='FormBuilder[step_3_field2_mandatory]'][value='0']").click();
		$("input[name='FormBuilder[step_3_field2_mandatory]'][value='0']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_3_field2_mandatory]']").parents(':eq(2)').addClass('grey-bg');
		$("#formbuilder-step_3_field2_label").val('');
		if ($('#formbuilder-step_3_field2_data_type').val() == 'edittext') {
			$("#formbuilder-step_3_field2_validation_type").val('');
			$("#step_3_field2_validation_type").css("display","none");
			$("#formbuilder-step_3_field2_no_chars").val('');
			$('#step_3_field2_no_of_chars').hide();
			$(".step_3_field2_field_boxes").remove();
		} else if ($('#formbuilder-step_3_field2_data_type').val() == 'radio') {
			$("#formbuilder-step_3_field2_field_value").val('');
			$(".step_3_field2_field_boxes").remove();
			$("#step_3_field2_vlaue").css("display","none");
		} else if ($('#formbuilder-step_3_field2_data_type').val() == 'checkbox') {
			$("#formbuilder-step_3_field2_field_value").val('');
			$(".step_3_field2_field_boxes").remove();
			$("#step_3_field2_vlaue").css("display","none");
		} else if ($('#formbuilder-step_3_field2_data_type').val() == 'selectbox') {
			$("#formbuilder-step_3_field2_field_value").val('');
			$(".step_3_field2_field_boxes").remove();
			$("#step_3_field2_vlaue").css("display","none");
		} else if ($('#formbuilder-step_3_field2_data_type').val() == 'rating') {
			$("#formbuilder-step_3_field2_field_value").val('');
			$(".step_3_field2_field_boxes").remove();
			$("#step_3_field2_vlaue").css("display","none");
		}
		$('#formbuilder-step_3_field2_data_type').val('');
		$("#formbuilder-step_3_field2_no_chars").val('');
		$("#formbuilder-step_3_field2_validation_type").val('');
		$("input:radio[name='FormBuilder[step_3_field2_require]']").parents(':eq(2)').addClass('grey-bg');
		$('#step_3_field2').css('display','none');
		$(".step_3_field2_field_boxes").remove();
    } 
});
	$("input:radio[name='FormBuilder[step_3_field2_mandatory]']").on('change',function() {
		value = $(this).val();
		if(value == 1) {
			$("input:radio[name='FormBuilder[step_3_field2_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
		} else if(value == 0) {
			$("input:radio[name='FormBuilder[step_3_field2_mandatory]']").parents(':eq(2)').addClass('grey-bg');
		} 
	});
$('#formbuilder-step_3_field2_data_type').on('change',function(){
	var data_type_value = $('#formbuilder-step_3_field2_data_type').val();
	//alert(data_type_value);
	if(data_type_value == 'edittext') {
	//$('#step_3_field2_validation_type select').removeAttr('disabled');;
	$('#step_3_field2_no_of_chars').show();
	$('.field-formbuilder-step_3_field2_no_chars').show();
	$("#formbuilder-step_3_field2_no_of_chars").val('');	
	$('.field-formbuilder-step_3_field2_validation_type').css("display","block");
	$("#formbuilder-step_3_field2_validation_type").val('');
	$('#step_3_field2_validation_type').css("display","block");
	$('#step_3_field2_vlaue input').attr("disabled","disabled");
	$('#step_3_field2_vlaue').css("display","none");
	$(".step_3_field2_field_boxes").remove();
  } else if(data_type_value == 'radio' || data_type_value == 'selectbox' || data_type_value == 'checkbox') {
	//$('#step_3_field2_validation_type select').attr("disabled","disabled");
	$("#formbuilder-step_3_field2_field_value").val('');
	$(".step_3_field2_field_boxes").remove();
	$('#step_3_field2_no_of_chars').hide();
	$('.field-formbuilder-step_3_field2_no_chars').hide();
	$("#formbuilder-step_3_field2_no_of_chars").val('');
	$("#formbuilder-step_3_field2_validation_type").val('');
	$('#step_3_field2_validation_type').css("display","none");
	$('#step_3_field2_vlaue input').removeAttr('disabled');
	$('#step_3_field2_vlaue').css("display","block");
 }  else if(data_type_value == 'textarea') {
	$('#step_3_field2_no_of_chars').show();
	$('.field-formbuilder-step_3_field2_no_chars').show();
	$("#formbuilder-step_3_field2_no_of_chars").val('');
	$("#formbuilder-step_3_field2_validation_type").val('');
	$('#step_3_field2_validation_type').css("display","none");
	$("#formbuilder-step_3_field2_field_value").val('');
	$(".step_3_field2_field_boxes").remove();
	$("#step_3_field2_vlaue").css("display","none");
	$('#step_3_field2_vlaue input').attr("disabled","disabled");
} else if(data_type_value == 'rating') {
	$('#step_3_field2_no_of_chars').hide();
	$('.field-formbuilder-step_3_field2_no_chars').hide();
	$("#formbuilder-step_3_field2_no_of_chars").val('');
	$("#formbuilder-step_3_field2_validation_type").val('');
	$('#step_3_field2_validation_type').css("display","none");
	$("#formbuilder-step_3_field2_field_value").val('');
	$(".step_3_field2_field_boxes").remove();
	$("#step_3_field2_vlaue").css("display","none");
	$('#step_3_field2_vlaue input').attr("disabled","disabled");
} else {
			//$('#step_2_field1_validation_type select').attr("disabled","disabled");
	$('#step_3_field3_no_of_chars').hide();
	$('.field-formbuilder-step_3_field2_no_chars').hide();
	$("#formbuilder-step_3_field2_no_chars").val('');
	$("#formbuilder-step_3_field2_validation_type").val('');
	$('#step_3_field2_validation_type').css("display","none");
	$("#formbuilder-step_3_field2_field_value").val('');
	$(".step_3_field2_field_boxes").remove();
	$("#step_3_field2_vlaue").css("display","none");
}
})		
//add more
$("#step_3_field2_vlaue .form-group .add-txt").click(function(){
           var no = $(".form-group").length + 1;
            var more_textbox = $('<div class="form-group step_3_field2_field_boxes">' +
            '</span></label>' +
            '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_3_field2_field_boxes[]" id="txtbox' + no + '" />' +
            '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
            '</div></div><div class="help-block"></div></div></div>');
            more_textbox.hide();
            $("#step_3_field2_vlaue .form-group:last").after(more_textbox);
            more_textbox.fadeIn("slow");
            $(".field-formbuilder-step_3_field2_field_value").find('.help-error').html('');
            return false;
        });

// Remove 

	//Remove
        $('#step_3_field2_vlaue').on('click', '.remove-txt', function(){
            $(this).parents(':eq(4)').fadeOut("slow", function() {
                $(this).remove();
              /*   $('.label-numbers').each(function( index ){
                    $(this).text( index + 1 );
                }); */
            });
            return false;
        });   		

	//step 3 field 3
	$("input:radio[name='FormBuilder[step_3_field3_require]']").on('change',function() {
    var value = $(this).val();
	if(value == 1) {
	$('#step_3_field3').css('display','block');
	//$('#step_3_field3 input').removeAttr('disabled');
	//$('#step_3_field3 select').removeAttr('disabled');
	//$("input:radio[name='FormBuilder[step_3_field3_mandatory]'][value='1']").prop("checked",true);
	$("input[name='FormBuilder[step_3_field3_mandatory]'][value='0']").next('span').removeClass('input-checked');
	$("input[name='FormBuilder[step_3_field3_mandatory]'][value='1']").next('span').addClass('input-checked');
	$("input:radio[name='FormBuilder[step_3_field3_require]']").parents(':eq(2)').removeClass('grey-bg');
	$("input:radio[name='FormBuilder[step_3_field3_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
	$("input[name='FormBuilder[step_3_field3_mandatory]'][value='1']").click();
	$("input[name='FormBuilder[step_3_field3_mandatory]'][value='1']").prop("checked",true);
	$("input:radio[name='FormBuilder[step_3_field3_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
	} else if(value == 0) {
	//$('#step_3_field3 input').attr('disabled','disabled');
	//$('#step_3_field3 select').attr('disabled','disabled');
		//$("input:radio[name='FormBuilder[step_3_field3_mandatory]'][value='0']").prop("checked",true);
		$("input[name='FormBuilder[step_3_field3_mandatory]'][value='0']").click();
		$("input[name='FormBuilder[step_3_field3_mandatory]'][value='0']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_3_field3_mandatory]']").parents(':eq(2)').addClass('grey-bg');
		$("#formbuilder-step_3_field3_label").val('');
		if ($('#formbuilder-step_3_field3_data_type').val() == 'edittext') {
			$("#formbuilder-step_3_field3_validation_type").val('');
			$("#step_3_field3_validation_type").css("display","none");
			$("#formbuilder-step_3_field3_no_chars").val('');
			$('#step_3_field3_no_of_chars').hide();
			$(".step_3_field3_field_boxes").remove();
		} else if ($('#formbuilder-step_3_field3_data_type').val() == 'radio') {
			$("#formbuilder-step_3_field3_field_value").val('');
			$(".step_3_field3_field_boxes").remove();
			$("#step_3_field3_vlaue").css("display","none");
		} else if ($('#formbuilder-step_3_field3_data_type').val() == 'checkbox') {
			$("#formbuilder-step_3_field3_field_value").val('');
			$(".step_3_field3_field_boxes").remove();
			$("#step_3_field3_vlaue").css("display","none");
		} else if ($('#formbuilder-step_3_field3_data_type').val() == 'selectbox') {
			$("#formbuilder-step_3_field3_field_value").val('');
			$(".step_3_field3_field_boxes").remove();
			$("#step_3_field3_vlaue").css("display","none");
		} else if ($('#formbuilder-step_3_field3_data_type').val() == 'rating') {
			$("#formbuilder-step_3_field3_field_value").val('');
			$(".step_3_field3_field_boxes").remove();
			$("#step_3_field3_vlaue").css("display","none");
		}
		$('#formbuilder-step_3_field3_data_type').val('');
		$("#formbuilder-step_3_field3_no_chars").val('');
		$("#formbuilder-step_3_field3_validation_type").val('');
		$("input:radio[name='FormBuilder[step_3_field3_require]']").parents(':eq(2)').addClass('grey-bg');
		$('#step_3_field3').css('display','none');
		$(".step_3_field3_field_boxes").remove();
    } 	
});	
	$("input:radio[name='FormBuilder[step_3_field3_mandatory]']").on('change',function() {
		value = $(this).val();
		if(value == 1) {
			$("input:radio[name='FormBuilder[step_3_field3_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
		} else if(value == 0) {
			$("input:radio[name='FormBuilder[step_3_field3_mandatory]']").parents(':eq(2)').addClass('grey-bg');
		} 
	});
$('#formbuilder-step_3_field3_data_type').on('change',function(){
	var data_type_value = $('#formbuilder-step_3_field3_data_type').val();
	//alert(data_type_value);
	if(data_type_value == 'edittext') {
	//$('#step_3_field3_validation_type select').removeAttr('disabled');;
	$('#step_3_field3_no_of_chars').show();
	$('.field-formbuilder-step_3_field3_no_chars').show();
	$("#formbuilder-step_3_field3_no_of_chars").val('');	
	$("#formbuilder-step_3_field3_validation_type").val('');
	$('#step_3_field3_validation_type').css("display","block");
	$(".field-formbuilder-step_3_field3_validation_type").css("display","block");
	$('#step_3_field3_vlaue input').attr("disabled","disabled");
	$('#step_3_field3_vlaue').css("display","none");
	$(".step_3_field3_field_boxes").remove();
  } else if(data_type_value == 'radio' || data_type_value == 'selectbox' || data_type_value == 'checkbox') {
	//$('#step_3_field3_validation_type select').attr("disabled","disabled");
	$('#step_3_field3_no_of_chars').hide();
	$('.field-formbuilder-step_3_field3_no_chars').hide();
	$("#formbuilder-step_3_field3_no_chars").val('');
	$("#formbuilder-step_3_field3_field_value").val('');
	$(".step_3_field3_field_boxes").remove();
	$('#step_3_field3_validation_type').css("display","none");
	$('#step_3_field3_vlaue input').removeAttr('disabled');;
	$('#step_3_field3_vlaue').css("display","block");
 }  else if(data_type_value == 'textarea') {
 	$('#step_3_field3_no_of_chars').show();
	$('.field-formbuilder-step_3_field3_no_chars').show();
	$("#formbuilder-step_3_field3_no_of_chars").val('');
	$("#formbuilder-step_3_field3_validation_type").val('');
	$('#step_3_field3_validation_type').css("display","none");
	$("#formbuilder-step_3_field3_field_value").val('');
	$(".step_3_field3_field_boxes").remove();
	$("#step_3_field3_vlaue").css("display","none");
	$('#step_3_field3_vlaue input').attr("disabled","disabled");
} else if(data_type_value == 'rating') {
	$('#step_3_field3_no_of_chars').hide();
	$('.field-formbuilder-step_3_field3_no_chars').hide();
	$("#formbuilder-step_3_field3_no_of_chars").val('');
	$("#formbuilder-step_3_field3_validation_type").val('');
	$('#step_3_field3_validation_type').css("display","none");
	$("#formbuilder-step_3_field3_field_value").val('');
	$(".step_3_field3_field_boxes").remove();
	$("#step_3_field3_vlaue").css("display","none");
	$('#step_3_field3_vlaue input').attr("disabled","disabled");
} else {
			//$('#step_2_field1_validation_type select').attr("disabled","disabled");
	$('#step_3_field3_no_of_chars').hide();
	$('.field-formbuilder-step_3_field3_no_chars').hide();
	$("#formbuilder-step_3_field3_no_chars").val('');
	$("#formbuilder-step_3_field3_validation_type").val('');
	$('#step_3_field3_validation_type').css("display","none");
	$("#formbuilder-step_3_field3_field_value").val('');
	$(".step_3_field3_field_boxes").remove();
	$("#step_3_field3_vlaue").css("display","none");
	}
})		
//add more
$("#step_3_field3_vlaue .form-group .add-txt").click(function(){
           var no = $(".form-group").length + 1;
            var more_textbox = $('<div class="form-group step_3_field3_field_boxes">' +
            '</span></label>' +
            '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_3_field3_field_boxes[]" id="txtbox' + no + '" />' +
            '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
            '</div></div><div class="help-block"></div></div></div>');
            more_textbox.hide();
            $("#step_3_field3_vlaue .form-group:last").after(more_textbox);
            more_textbox.fadeIn("slow");
            $(".field-formbuilder-step_3_field3_field_value").find('.help-error').html('');
            return false;
        });

// Remove 

	//Remove
        $('#step_3_field3_vlaue').on('click', '.remove-txt', function(){
            $(this).parents(':eq(4)').fadeOut("slow", function() {
                $(this).remove();
              /*   $('.label-numbers').each(function( index ){
                    $(this).text( index + 1 );
                }); */
            });
            return false;
        });   		
	
      //step 3
    	//field 4
    	$("input:radio[name='FormBuilder[step_3_field4_require]']").on('change',function() {
        var value = $(this).val();
    	if(value == 1) {
    	$('#step_3_field4').css('display','block');
    	//$('#step_3_field4 input').removeAttr('disabled');
    	//$('#step_3_field4 select').removeAttr('disabled');
    	//$("input:radio[name='FormBuilder[step_3_field4_mandatory]'][value='1']").prop("checked",true);
		$("input[name='FormBuilder[step_3_field4_mandatory]'][value='0']").next('span').removeClass('input-checked');
		$("input[name='FormBuilder[step_3_field4_mandatory]'][value='1']").next('span').addClass('input-checked');
		$("input:radio[name='FormBuilder[step_3_field4_require]']").parents(':eq(2)').removeClass('grey-bg');
		$("input:radio[name='FormBuilder[step_3_field4_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
		$("input[name='FormBuilder[step_3_field4_mandatory]'][value='1']").click();
		$("input[name='FormBuilder[step_3_field4_mandatory]'][value='1']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_3_field4_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
    	} else if(value == 0) {
    	//$('#step_3_field4 input').attr('disabled','disabled');
    	//$('#step_3_field4 select').attr('disabled','disabled');
    		//$("input:radio[name='FormBuilder[step_3_field4_mandatory]'][value='0']").prop("checked",true);
    		$("input[name='FormBuilder[step_3_field4_mandatory]'][value='0']").click();
    		$("input[name='FormBuilder[step_3_field4_mandatory]'][value='0']").prop("checked",true);
    		$("input:radio[name='FormBuilder[step_3_field4_mandatory]']").parents(':eq(2)').addClass('grey-bg');
    		$("#formbuilder-step_3_field4_label").val('');
    		if ($('#formbuilder-step_3_field4_data_type').val() == 'edittext') {
    			$('#step_3_field4_no_of_chars').hide();
    			$('.field-formbuilder-step_3_field4_no_chars').hide();
    			$("#formbuilder-step_3_field4_no_chars").val('');
    			$("#formbuilder-step_3_field4_validation_type").val('');
    			$("#step_3_field4_validation_type").css("display","none");
    			$(".step_3_field4_field_boxes").remove();
    		} else if ($('#formbuilder-step_3_field4_data_type').val() == 'radio') {
    			$("#formbuilder-step_3_field4_field_value").val('');
    			$(".step_3_field4_field_boxes").remove();
    			$("#step_3_field4_vlaue").css("display","none");
    		} else if ($('#formbuilder-step_3_field4_data_type').val() == 'checkbox') {
    			$("#formbuilder-step_3_field4_field_value").val('');
    			$(".step_3_field4_field_boxes").remove();
    			$("#step_3_field4_vlaue").css("display","none");
    		} else if ($('#formbuilder-step_3_field4_data_type').val() == 'selectbox') {
    			$("#formbuilder-step_3_field4_field_value").val('');
    			$(".step_3_field4_field_boxes").remove();
    			$("#step_3_field4_vlaue").css("display","none");
    		} else if ($('#formbuilder-step_3_field4_data_type').val() == 'rating') {
    			$("#formbuilder-step_3_field4_field_value").val('');
    			$(".step_3_field4_field_boxes").remove();
    			$("#step_3_field4_vlaue").css("display","none");
    		}
    		$('#formbuilder-step_3_field4_data_type').val('');
    		$("#formbuilder-step_3_field4_no_chars").val('');
    		$("#formbuilder-step_3_field4_validation_type").val('');
    		$("input:radio[name='FormBuilder[step_3_field4_require]']").parents(':eq(2)').addClass('grey-bg');
    		$('#step_3_field4').css('display','none');
    		$(".step_3_field4_field_boxes").remove();
        } 	
    });	
    	$("input:radio[name='FormBuilder[step_3_field4_mandatory]']").on('change',function() {
    		value = $(this).val();
    		if(value == 1) {
    			$("input:radio[name='FormBuilder[step_3_field4_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
    		} else if(value == 0) {
    			$("input:radio[name='FormBuilder[step_3_field4_mandatory]']").parents(':eq(2)').addClass('grey-bg');
    		} 
    	});
    $('#formbuilder-step_3_field4_data_type').on('change',function(){
    	var data_type_value = $('#formbuilder-step_3_field4_data_type').val();
    	//alert(data_type_value);
    	if(data_type_value == 'edittext') {
    	//$('#step_3_field4_validation_type select').removeAttr('disabled');;
		$('#step_3_field4_no_of_chars').show();
		$('.field-formbuilder-step_3_field4_no_chars').show();
		$("#formbuilder-step_3_field4_no_of_chars").val('');
    	$("#formbuilder-step_3_field4_validation_type").val('');
    	$('#step_3_field4_validation_type').css("display","block");
    	$('#step_3_field4_vlaue input').attr("disabled","disabled");
    	$('#step_3_field4_vlaue').css("display","none");
    	$('.field-formbuilder-step_3_field4_validation_type').css("display","block");
    	$(".step_3_field4_field_boxes").remove();
      } else if(data_type_value == 'radio' || data_type_value == 'selectbox' || data_type_value == 'checkbox') {
    	//$('#step_3_field4_validation_type select').attr("disabled","disabled");
		$('#step_3_field4_no_of_chars').hide();
		$('.field-formbuilder-step_3_field4_no_chars').hide();
		$("#formbuilder-step_3_field4_no_of_chars").val('');
    	$("#formbuilder-step_3_field4_field_value").val('');
  		$(".step_3_field4_field_boxes").remove();
    	$('#step_3_field4_validation_type').css("display","none");
    	$('#step_3_field4_vlaue input').removeAttr('disabled');;
    	$('#step_3_field4_vlaue').css("display","block");
     }  else if(data_type_value == 'textarea') {
    	$('#step_3_field4_no_of_chars').show();
 		$('.field-formbuilder-step_3_field4_no_chars').show();
 		$("#formbuilder-step_3_field4_no_of_chars").val('');
		$("#formbuilder-step_3_field4_validation_type").val('');
		$('#step_3_field4_validation_type').css("display","none");
		$("#formbuilder-step_3_field4_field_value").val('');
		$(".step_3_field4_field_boxes").remove();
		$("#step_3_field4_vlaue").css("display","none");
		$('#step_3_field4_vlaue input').attr("disabled","disabled");
	}else if(data_type_value == 'rating') {
		$('#step_3_field4_no_of_chars').hide();
		$('.field-formbuilder-step_3_field4_no_chars').hide();
		$("#formbuilder-step_3_field4_no_of_chars").val('');
		$("#formbuilder-step_3_field4_validation_type").val('');
		$('#step_3_field4_validation_type').css("display","none");
		$("#formbuilder-step_3_field4_field_value").val('');
		$(".step_3_field4_field_boxes").remove();
		$("#step_3_field4_vlaue").css("display","none");
		$('#step_3_field4_vlaue input').attr("disabled","disabled");
	}  else {
				//$('#step_2_field1_validation_type select').attr("disabled","disabled");
		$('#step_3_field4_no_of_chars').hide();
		$('.field-formbuilder-step_3_field4_no_chars').hide();
		$("#formbuilder-step_3_field4_no_chars").val('');
		$("#formbuilder-step_3_field4_validation_type").val('');
		$('#step_3_field4_validation_type').css("display","none");
		$("#formbuilder-step_3_field4_field_value").val('');
		$(".step_3_field4_field_boxes").remove();
		$("#step_3_field4_vlaue").css("display","none");
	}
    })		
    //add more
    $("#step_3_field4_vlaue .form-group .add-txt").click(function(){
               var no = $(".form-group").length + 1;
                var more_textbox = $('<div class="form-group step_3_field4_field_boxes">' +
                '</span></label>' +
                '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_3_field4_field_boxes[]" id="txtbox' + no + '" />' +
                '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
                '</div></div><div class="help-block"></div></div></div>');
                more_textbox.hide();
                $("#step_3_field4_vlaue .form-group:last").after(more_textbox);
                more_textbox.fadeIn("slow");
                $(".field-formbuilder-step_3_field4_field_value").find('.help-error').html('');
                return false;
            });

    // Remove 

    	//Remove
            $('#step_3_field4_vlaue').on('click', '.remove-txt', function(){
                $(this).parents(':eq(4)').fadeOut("slow", function() {
                    $(this).remove();
                  /*   $('.label-numbers').each(function( index ){
                        $(this).text( index + 1 );
                    }); */
                });
                return false;
            });    	
        
            //step 3
        	//field 5
        	$("input:radio[name='FormBuilder[step_3_field5_require]']").on('change',function() {
            var value = $(this).val();
        	if(value == 1) {
        	$('#step_3_field5').css('display','block');
        //	$('#step_3_field5 input').removeAttr('disabled');
        //	$('#step_3_field5 select').removeAttr('disabled');
        	//$("input:radio[name='FormBuilder[step_3_field5_mandatory]'][value='1']").prop("checked",true);
    		$("input[name='FormBuilder[step_3_field5_mandatory]'][value='0']").next('span').removeClass('input-checked');
    		$("input[name='FormBuilder[step_3_field5_mandatory]'][value='1']").next('span').addClass('input-checked');
    		$("input:radio[name='FormBuilder[step_3_field5_require]']").parents(':eq(2)').removeClass('grey-bg');
    		$("input:radio[name='FormBuilder[step_3_field5_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
    		$("input[name='FormBuilder[step_3_field5_mandatory]'][value='1']").click();
    		$("input[name='FormBuilder[step_3_field5_mandatory]'][value='1']").prop("checked",true);
    		$("input:radio[name='FormBuilder[step_3_field5_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
        	} else if(value == 0) {
        	//$('#step_3_field5 input').attr('disabled','disabled');
        //	$('#step_3_field5 select').attr('disabled','disabled');
        		//$("input:radio[name='FormBuilder[step_3_field5_mandatory]'][value='0']").prop("checked",true);
        		$("input[name='FormBuilder[step_3_field5_mandatory]'][value='0']").click();
        		$("input[name='FormBuilder[step_3_field5_mandatory]'][value='0']").prop("checked",true);
        		$("input:radio[name='FormBuilder[step_3_field5_mandatory]']").parents(':eq(2)').addClass('grey-bg');
        		$("#formbuilder-step_3_field5_label").val('');
        		if ($('#formbuilder-step_3_field5_data_type').val() == 'edittext') {
        			$('#step_3_field5_no_of_chars').hide();
        			$('.field-formbuilder-step_3_field5_no_chars').hide();
        			$("#formbuilder-step_3_field5_no_chars").val('');
        			$("#formbuilder-step_3_field5_validation_type").val('');
        			$("#step_3_field5_validation_type").css("display","none");
        			$(".step_3_field5_field_boxes").remove();
        		} else if ($('#formbuilder-step_3_field5_data_type').val() == 'radio') {
        			$("#formbuilder-step_3_field5_field_value").val('');
        			$(".step_3_field5_field_boxes").remove();
        			$("#step_3_field5_vlaue").css("display","none");
        		} else if ($('#formbuilder-step_3_field5_data_type').val() == 'checkbox') {
        			$("#formbuilder-step_3_field5_field_value").val('');
        			$(".step_3_field5_field_boxes").remove();
        			$("#step_3_field5_vlaue").css("display","none");
        		} else if ($('#formbuilder-step_3_field5_data_type').val() == 'selectbox') {
        			$("#formbuilder-step_3_field5_field_value").val('');
        			$(".step_3_field5_field_boxes").remove();
        			$("#step_3_field5_vlaue").css("display","none");
        		} else if ($('#formbuilder-step_3_field5_data_type').val() == 'rating') {
        			$("#formbuilder-step_3_field5_field_value").val('');
        			$(".step_3_field5_field_boxes").remove();
        			$("#step_3_field5_vlaue").css("display","none");
        		}
        		$('#formbuilder-step_3_field5_data_type').val('');
        		$("#formbuilder-step_3_field5_no_chars").val('');
        		$("#formbuilder-step_3_field5_validation_type").val('');
        		$("input:radio[name='FormBuilder[step_3_field5_require]']").parents(':eq(2)').addClass('grey-bg');
        		$('#step_3_field5').css('display','none');
        		$(".step_3_field5_field_boxes").remove();
            }         	
        });	
        	$("input:radio[name='FormBuilder[step_3_field5_mandatory]']").on('change',function() {
        		value = $(this).val();
        		if(value == 1) {
        			$("input:radio[name='FormBuilder[step_3_field5_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
        		} else if(value == 0) {
        			$("input:radio[name='FormBuilder[step_3_field5_mandatory]']").parents(':eq(2)').addClass('grey-bg');
        		} 
        	});
        $('#formbuilder-step_3_field5_data_type').on('change',function(){
        	var data_type_value = $('#formbuilder-step_3_field5_data_type').val();
        	if(data_type_value == 'edittext') {
    	//$('#step_3_field5_validation_type select').removeAttr('disabled');;
    		$('#step_3_field5_no_of_chars').show();
    		$('.field-formbuilder-step_3_field5_no_chars').show();
    		$("#formbuilder-step_3_field5_no_of_chars").val('');
        	$("#formbuilder-step_3_field5_validation_type").val('');
        	$('#step_3_field5_validation_type').css("display","block");
        	$('#step_3_field5_vlaue input').attr("disabled","disabled");
        	$('#step_3_field5_vlaue').css("display","none");
        	$('.field-formbuilder-step_3_field5_validation_type').css("display","block");
        	$(".step_3_field5_field_boxes").remove();
          } else if(data_type_value == 'radio' || data_type_value == 'selectbox' || data_type_value == 'checkbox') {
        	//$('#step_3_field5_validation_type select').attr("disabled","disabled");
        	$("#formbuilder-step_3_field5_field_value").val('');
        	$(".step_3_field5_field_boxes").remove();
        	$('#step_3_field5_no_of_chars').hide();
    		$('.field-formbuilder-step_3_field5_no_chars').hide();
    		$("#formbuilder-step_3_field5_no_of_chars").val('');
        	$('#step_3_field5_validation_type').css("display","none");
        	$('#step_3_field5_vlaue input').removeAttr('disabled');;
        	$('#step_3_field5_vlaue').css("display","block");
         }  else if(data_type_value == 'textarea') {
        	$('#step_3_field5_no_of_chars').show();
     		$('.field-formbuilder-step_3_field5_no_chars').show();
     		$("#formbuilder-step_3_field5_no_of_chars").val(''); 
     		$("#formbuilder-step_3_field5_validation_type").val('');
    		$('#step_3_field5_validation_type').css("display","none");
    		$("#formbuilder-step_3_field5_field_value").val('');
    		$(".step_3_field5_field_boxes").remove();
    		$("#step_3_field5_vlaue").css("display","none");
    		$('#step_3_field5_vlaue input').attr("disabled","disabled");
    	} else if(data_type_value == 'rating') {
    		$('#step_3_field5_no_of_chars').hide();
    		$('.field-formbuilder-step_3_field5_no_chars').hide();
    		$("#formbuilder-step_3_field5_no_of_chars").val('');
     		$("#formbuilder-step_3_field5_validation_type").val('');
    		$('#step_3_field5_validation_type').css("display","none");
    		$("#formbuilder-step_3_field5_field_value").val('');
    		$(".step_3_field5_field_boxes").remove();
    		$("#step_3_field5_vlaue").css("display","none");
    		$('#step_3_field5_vlaue input').attr("disabled","disabled");
    	}  else {
    				//$('#step_2_field1_validation_type select').attr("disabled","disabled");
    		$('#step_3_field5_no_of_chars').hide();
    		$('.field-formbuilder-step_3_field5_no_chars').hide();
    		$("#formbuilder-step_3_field5_no_of_chars").val('');
    		$("#formbuilder-step_3_field5_validation_type").val('');
    		$('#step_3_field5_validation_type').css("display","none");
    		$("#formbuilder-step_3_field5_field_value").val('');
    		$(".step_3_field5_field_boxes").remove();
    		$("#step_3_field5_vlaue").css("display","none");
    	}
        });		
        //add more
        $("#step_3_field5_vlaue .form-group .add-txt").click(function(){
                   var no = $(".form-group").length + 1;
                    var more_textbox = $('<div class="form-group step_3_field5_field_boxes">' +
                    '</span></label>' +
                    '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_3_field5_field_boxes[]" id="txtbox' + no + '" />' +
                    '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
                    '</div></div><div class="help-block"></div></div></div>');
                    more_textbox.hide();
                    $("#step_3_field5_vlaue .form-group:last").after(more_textbox);
                    more_textbox.fadeIn("slow");
                    $(".field-formbuilder-step_3_field5_field_value").find('.help-error').html('');
                    return false;
                });

        // Remove 

        	//Remove
                $('#step_3_field5_vlaue').on('click', '.remove-txt', function(){
                    $(this).parents(':eq(4)').fadeOut("slow", function() {
                        $(this).remove();
                      /*   $('.label-numbers').each(function( index ){
                            $(this).text( index + 1 );
                        }); */
                    });
                    return false;
                });    	

	
//step 4 
	//field 1 
	$("input:radio[name='FormBuilder[step_4_field1_require]']").on('change',function() {
    var value = $(this).val();
	if(value == 1) {
		$('#step_4_field1').css('display','block');
		$('#step_4_field1 input').removeAttr('disabled');
		$('#step_4_field1 select').removeAttr('disabled');
		$("input:radio[name='FormBuilder[step_4_field1_require]'][value='1']").prop("checked",true);
		$("input[name='FormBuilder[step_4_field1_require]'][value='0']").next('span').removeClass('input-checked');
		$("input[name='FormBuilder[step_4_field1_require]'][value='1']").next('span').addClass('input-checked');
		//$("input:radio[name='FormBuilder[step_4_field1_mandatory]'][value='1']").prop("checked",true);
		$("input[name='FormBuilder[step_4_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
		$("input[name='FormBuilder[step_4_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
		$("input:radio[name='FormBuilder[step_4_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
		//$("input:radio[name='FormBuilder[step_4_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
		$("input[name='FormBuilder[step_4_field1_mandatory]'][value='1']").click();
		$("input[name='FormBuilder[step_4_field1_mandatory]'][value='1']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_4_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
	} else if(value == 0) {
		$('#step_4_field1 input').attr('disabled','disabled');
		$('#step_4_field1 select').attr('disabled','disabled');
		//$("input:radio[name='FormBuilder[step_4_field1_mandatory]'][value='0']").prop("checked",true);
		$("input[name='FormBuilder[step_4_field1_mandatory]'][value='0']").click();
		$("input[name='FormBuilder[step_4_field1_mandatory]'][value='0']").prop("checked",true);
		$("input:radio[name='FormBuilder[step_4_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
		$('#formbuilder-step_4_field1_label').val('');
		if ($('#formbuilder-step_4_field1_data_type').val() == 'edittext') {
			$('#step_4_field1_no_of_chars').hide();
    		$('.field-formbuilder-step_4_field1_no_chars').hide();
    		$("#formbuilder-step_4_field1_no_of_chars").val('');
			$("#formbuilder-step_4_field1_validation_type").val('');
			$("#step_4_field1_validation_type").css("display","none");
			$(".step_4_field1_field_boxes").remove();
		} else if ($('#formbuilder-step_4_field1_data_type').val() == 'radio') {
			$("#formbuilder-step_4_field1_field_value").val('');
			$(".step_4_field1_field_boxes").remove();
			$("#step_4_field1_vlaue").css("display","block");
		} else if ($('#formbuilder-step_4_field1_data_type').val() == 'checkbox') {
			$("#formbuilder-step_4_field1_field_value").val('');
			$(".step_4_field1_field_boxes").remove();
			$("#step_4_field1_vlaue").css("display","block");
		} else if ($('#formbuilder-step_4_field1_data_type').val() == 'selectbox') {
			$("#formbuilder-step_4_field1_field_value").val('');
			$(".step_4_field1_field_boxes").remove();
			$("#step_4_field1_vlaue").css("display","block");
		} else if ($('#formbuilder-step_4_field1_data_type').val() == 'rating') {
			$("#formbuilder-step_4_field1_field_value").val('');
			$(".step_4_field1_field_boxes").remove();
			$("#step_4_field1_vlaue").css("display","none");
		}
		$('#formbuilder-step_4_field1_data_type').val('');
		$("input:radio[name='FormBuilder[step_4_field1_require]']").parents(':eq(2)').addClass('grey-bg');
		$('#step_4_field1').css('display','none');
		$(".step_4_field1_field_boxes").remove();
    } 
});	
	$("input:radio[name='FormBuilder[step_4_field1_mandatory]']").on('change',function() {
		value = $(this).val();
		if(value == 1) {
			$("input:radio[name='FormBuilder[step_4_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
		} else if(value == 0) {
			$("input:radio[name='FormBuilder[step_4_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
		} 
	});
$('#formbuilder-step_4_field1_data_type').on('change',function(){
	var data_type_value = $('#formbuilder-step_4_field1_data_type').val();
	//alert(data_type_value);
	if(data_type_value == 'edittext') {
	//$('#step_4_field1_validation_type select').removeAttr('disabled');;
	$('#step_4_field1_no_of_chars').show();
	$('.field-formbuilder-step_4_field1_no_chars').show();
	$("#formbuilder-step_4_field1_no_of_chars").val('');
	$("#formbuilder-step_4_field1_validation_type").val('');
	$('#step_4_field1_validation_type').css("display","block");
	$('.field-formbuilder-step_4_field1_validation_type').css("display","block");
	$('#step_4_field1_vlaue input').attr("disabled","disabled");
	$('#step_4_field1_vlaue').css("display","none");
	$(".step_4_field1_field_boxes").remove();
  } else if(data_type_value == 'radio' || data_type_value == 'selectbox' || data_type_value == 'checkbox') {
	//$('#step_4_field1_validation_type select').attr("disabled","disabled");
	$("#formbuilder-step_4_field1_field_value").val('');
	$(".step_4_field1_field_boxes").remove();
	$('#step_4_field1_no_of_chars').hide();
	$('.field-formbuilder-step_4_field1_no_chars').hide();
	$("#formbuilder-step_4_field1_no_of_chars").val('');
	$('#step_4_field1_validation_type').css("display","none");
	$('#step_4_field1_vlaue input').removeAttr('disabled');;
	$('#step_4_field1_vlaue').css("display","block");
 }  else if(data_type_value == 'textarea') {
	$('#step_4_field1_no_of_chars').show();
	$('.field-formbuilder-step_4_field1_no_chars').show();
	$("#formbuilder-step_4_field1_no_of_chars").val('');
	$("#formbuilder-step_4_field1_validation_type").val('');
	$('#step_4_field1_validation_type').css("display","none");
	$("#formbuilder-step_4_field1_field_value").val('');
	$(".step_4_field1_field_boxes").remove();
	$("#step_4_field1_vlaue").css("display","none");
	$('#step_4_field1_vlaue input').attr("disabled","disabled");
} else if(data_type_value == 'rating') {
	$('#step_4_field1_no_of_chars').hide();
	$('.field-formbuilder-step_4_field1_no_chars').hide();
	$("#formbuilder-step_4_field1_no_of_chars").val('');
	$("#formbuilder-step_4_field1_validation_type").val('');
	$('#step_4_field1_validation_type').css("display","none");
	$("#formbuilder-step_4_field1_field_value").val('');
	$(".step_4_field1_field_boxes").remove();
	$("#step_4_field1_vlaue").css("display","none");
	$('#step_4_field1_vlaue input').attr("disabled","disabled");
} else {
				//$('#step_2_field1_validation_type select').attr("disabled","disabled");
	$('#step_4_field1_no_of_chars').hide();
	$('.field-formbuilder-step_4_field1_no_chars').hide();
	$("#formbuilder-step_4_field1_no_of_chars").val('');
	$("#formbuilder-step_4_field1_validation_type").val('');
	$('#step_4_field1_validation_type').css("display","none");
	//$('#step_4_validation_type').css("display","none");
	$("#formbuilder-step_4_field1_field_value").val('');
	$(".step_4_field1_field_boxes").remove();
	$("#step_4_field1_vlaue").css("display","none");
}
})		
//add more
$("#step_4_field1_vlaue .form-group .add-txt").click(function(){
           var no = $(".form-group").length + 1;
            var more_textbox = $('<div class="form-group step_4_field1_field_boxes">' +
            '</span></label>' +
            '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_4_field1_field_boxes[]" id="txtbox' + no + '" />' +
            '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
            '</div></div><div class="help-block"></div></div></div>');
            more_textbox.hide();
            $("#step_4_field1_vlaue .form-group:last").after(more_textbox);
            more_textbox.fadeIn("slow");
            $(".field-formbuilder-step_4_field1_field_value").find('.help-error').html('');
            return false;
        });

	//Remove
        $('#step_4_field1_vlaue').on('click', '.remove-txt', function(){
            $(this).parents(':eq(4)').fadeOut("slow", function() {
                $(this).remove();
              /*   $('.label-numbers').each(function( index ){
                    $(this).text( index + 1 );
                }); */
            });
            return false;
        }); 
      //step 5 
    	//field 1 
    	$("input:radio[name='FormBuilder[step_5_field1_require]']").on('change',function() {
        var value = $(this).val();
    	if(value == 1) {
    		$('#step_5_field1').css('display','block');
    		$('#step_5_field1 input').removeAttr('disabled');
    		$('#step_5_field1 select').removeAttr('disabled');
    		$("input:radio[name='FormBuilder[step_5_field1_require]'][value='1']").prop("checked",true);
    		$("input[name='FormBuilder[step_5_field1_require]'][value='0']").next('span').removeClass('input-checked');
			$("input[name='FormBuilder[step_5_field1_require]'][value='1']").next('span').addClass('input-checked');
			$("input:radio[name='FormBuilder[step_5_field1_mandatory]'][value='1']").prop("checked",true);
			$("input[name='FormBuilder[step_5_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
			$("input[name='FormBuilder[step_5_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
			$("input:radio[name='FormBuilder[step_5_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
			$("input:radio[name='FormBuilder[step_5_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
    	} else if(value == 0) {
    		$('#step_5_field1 input').attr('disabled','disabled');
    		$('#step_5_field1 select').attr('disabled','disabled');
    		$("input:radio[name='FormBuilder[step_5_field1_mandatory]'][value='0']").prop("checked",true);
    		$('#formbuilder-step_5_field1_label').val('');
    		$("input:radio[name='FormBuilder[step_5_field1_require]']").parents(':eq(2)').addClass('grey-bg');
    		$('#step_5_field1').css('display','none');
        } 	
    });	
    	$("input:radio[name='FormBuilder[step_5_field1_mandatory]']").on('change',function() {
    		value = $(this).val();
    		if(value == 1) {
    			$("input:radio[name='FormBuilder[step_5_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
    		} else if(value == 0) {
    			$("input:radio[name='FormBuilder[step_5_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
    		} 
    	});
    $('#formbuilder-step_5_field1_data_type').on('change',function(){
    	var data_type_value = $('#formbuilder-step_5_field1_data_type').val();
    	//alert(data_type_value);
    	if(data_type_value == 'edittext') {
    	//$('#step_5_field1_validation_type select').removeAttr('disabled');;
    	$('#step_5_field1_validation_type').css("display","block");
    	$('#step_5_field1_vlaue input').attr("disabled","disabled");
    	$('#step_5_field1_vlaue').css("display","none");
      } else if(data_type_value == 'radio' || data_type_value == 'selectbox' || data_type_value == 'checkbox') {
    	//$('#step_5_field1_validation_type select').attr("disabled","disabled");
    	$('#step_5_field1_validation_type').css("display","none");
    	$('#step_5_field1_vlaue input').removeAttr('disabled');;
    	$('#step_5_field1_vlaue').css("display","block");
     }  else {
    	//$('#step_5_validation_type select').attr("disabled","disabled");
    	$('#step_5_validation_type').css("display","none");
    }
    })		
    //add more
    $("#step_5_field1_vlaue .form-group .add-txt").click(function(){
               var no = $(".form-group").length + 1;
                var more_textbox = $('<div class="form-group">' +
                '</span></label>' +
                '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_5_field1_field_boxes[]" id="txtbox' + no + '" />' +
                '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
                '</div></div><div class="help-block"></div></div></div>');
                more_textbox.hide();
                $("#step_5_field1_vlaue .form-group:last").after(more_textbox);
                more_textbox.fadeIn("slow");
                return false;
            });

    	//Remove
            $('#step_5_field1_vlaue').on('click', '.remove-txt', function(){
                $(this).parents(':eq(4)').fadeOut("slow", function() {
                    $(this).remove();
                  /*   $('.label-numbers').each(function( index ){
                        $(this).text( index + 1 );
                    }); */
                });
                return false;
            }); 
var actvity_value = $('#formbuilder-activity').val();
if(actvity_value == '1') {
	$('#fgm').css('display','none');
	$('#fgm input').attr('disabled','disabled');
	$('#fgm select').attr('disabled','disabled');
	$('#demo').css('display','none');
	$('#demo input').attr('disabled','disabled');
	$('#demo select').attr('disabled','disabled');
	$('#mc').css('display','none');
	$('#mc input').attr('disabled','disabled');
	$('#mc select').attr('disabled','disabled');
	/*$("input:radio[name='FormBuilder[step_3_field3_require]']").filter('[value="0"]').attr('checked', true);
	$("input:radio[name='FormBuilder[step_3_field4_require]']").filter('[value="0"]').attr('checked', true);
	$("input:radio[name='FormBuilder[step_3_field5_require]']").filter('[value="0"]').attr('checked', true);*/
	}       
$("#formbuilder-activity").on('change',function(){
	var actvity_value = $(this).val();
	if(actvity_value == '1') {
		$('#fgm').css('display','none');
		$('#fgm input').attr('disabled','disabled');
		$('#fgm select').attr('disabled','disabled');
		$('#mc input').attr('disabled','disabled');
		$('#mc select').attr('disabled','disabled');
		$('#mc').css('display','none');
		$('#demo').css('display','none');
		$('#demo input').attr('disabled','disabled');
		$('#demo select').attr('disabled','disabled');
		$('#formbuilder-step_1_field1_label').val('Sub Activity');
		$('#formbuilder-step_2_field1_label').val('Purpose');
		$('#formbuilder-step_3_field1_label').val('Farmer Name');
		$('#formbuilder-step_3_field2_label').val('Mobile Number');
		
	} else if(actvity_value == '2') {
		$('#fgm').css('display','block');
		$('#fgm input').removeAttr('disabled');
		$('#fgm select').removeAttr('disabled');
		$('#demo input').attr('disabled','disabled');
		$('#demo select').attr('disabled','disabled');
		$('#demo').css('display','none');
		$('#mc input').attr('disabled','disabled');
		$('#mc select').attr('disabled','disabled');
		$('#mc').css('display','none');
		$('#formbuilder-step_1_field1_label').val('Sub Activity');
		$('#formbuilder-step_2_field1_label').val('Purpose');
		$('#formbuilder-step_3_field1_label').val('Farmers');
		$('#formbuilder-step_3_field2_label').val('Female Farmers');
		$('#formbuilder-step_3_field3_label').val('Partners');
		$('#formbuilder-step_4_field1_label').val('Remarks');
		$('#formbuilder-step_5_field1_label').val('Images');
	} else if(actvity_value == '3') {
		$('#fgm').css('display','block');
		$('#fgm input').removeAttr('disabled');
		$('#fgm select').removeAttr('disabled');
		$('#mc').css('display','block');
		$('#mc input').removeAttr('disabled');
		$('#mc select').removeAttr('disabled');
		$('#demo').css('display','none');
		$('#demo input').attr('disabled','disabled');
		$('#demo select').attr('disabled','disabled');
		$('#formbuilder-step_2_field1_label').val('Sub Activity');
		$('#formbuilder-step_2_field1_label').val('Purpose');
		$('#formbuilder-step_3_field1_label').val('Farmers');
		$('#formbuilder-step_3_field2_label').val('Female Farmers');
		$('#formbuilder-step_3_field3_label').val('Partners');
		$('#formbuilder-step_3_field4_label').val('Villages');
		$('#formbuilder-step_4_field1_label').val('Remarks');
		$('#formbuilder-step_5_field1_label').val('Images');
	} else if(actvity_value == '4') {
		$('#fgm').css('display','block');
		$('#fgm input').removeAttr('disabled');
		$('#fgm select').removeAttr('disabled');
		$('#mc').css('display','block');
		$('#mc input').removeAttr('disabled');
		$('#mc select').removeAttr('disabled');
		$('#demo').css('display','block');
		$('#demo input').removeAttr('disabled');
		$('#demo select').removeAttr('disabled');
		$('#formbuilder-step_2_field1_label').val('Sub Activity');
		$('#formbuilder-step_2_field1_label').val('Observation');
		$('#formbuilder-step_3_field1_label').val('Farmer Name');
		$('#formbuilder-step_3_field2_label').val('Mobile Number');
		$('#formbuilder-step_3_field3_label').val('Farmers');
		$('#formbuilder-step_3_field4_label').val('Female Farmers');
		$('#formbuilder-step_3_field5_label').val('Partners');
		$('#formbuilder-step_4_field1_label').val('Remarks');
		$('#formbuilder-step_5_field1_label').val('Images');
	}
	
});	 

/* dynamic form ajax request start */		
$('#formbuilder-company_id, #formbuilder-activity').bind('change',function(){
	ajaxRequest();
	stepsDisabledLabels();
});	
	function ajaxRequest() {
		var company_id = $('#formbuilder-company_id').val();
		var activity_val = $('#formbuilder-activity').val();
		stepsDisabled();//for fgm fhv mc demo changes
		if (company_id != '' && activity_val != '') {
			$.ajax({
  				method: "GET",
  				url: "dynamicdata",
  				data: { company_id: company_id,activity_id: activity_val,type:'campaign'},
				success: function(data) {
					res = $.parseJSON(data);
					//console.log('res');
					//console.log(data);
					if(res != ''){
					if(res['step1']) {
						$("input[name='FormBuilder[step_1_field1_require]'][value='1']").click();						
						//alert(res['step1'][0]['require']);
						if(res['step1'][0]['require'] == 1) {
							$("input[name='FormBuilder[step_1_field1_require]'][value='1']").prop("checked",true);
						} else {
							$("input[name='FormBuilder[step_1_field1_require]'][value='0']").prop("checked",true);
							$("input[name='FormBuilder[step_1_field1_mandatory]'][value='0']").prop("checked",true);
							$('#formbuilder-step_1_field1_label').val('');
							$('#step_1_subb').css('display','none');
						}
						if(res['step1'][0]['mandatory'] == 1) {
							$("input[name='FormBuilder[step_1_field1_mandatory]'][value='1']").prop("checked",true);
							$("input:radio[name='FormBuilder[step_1_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
							$("input[name='FormBuilder[step_1_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
							$("input[name='FormBuilder[step_1_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
							//$("input[name='FormBuilder[step_1_field1_mandatory]'][value='0']").next('span').addClass('input-checked');
						} else {
							$("input[name='FormBuilder[step_1_field1_mandatory]'][value='0']").prop("checked",true);
							$("input[name='FormBuilder[step_1_field1_mandatory]'][value='1']").next('span').removeClass('input-checked');
							$("input:radio[name='FormBuilder[step_1_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
							$("input[name='FormBuilder[step_1_field1_mandatory]'][value='0']").next('span').addClass('input-checked');
							
						}
						if(res['step1'][0]['label'] != '') {
						 $('#formbuilder-step_1_field1_label').val(res['step1'][0]['label']);
						} else {
							 //$('#formbuilder-step_1_field1_label').val('');
							stepsDisabled();
						}
					} 
					if(!res['step1']) {
						$('#step_1_subb input').attr('disabled','disabled');
						$('#step_1_subb select').attr('disabled','disabled');
						$("input[name='FormBuilder[step_1_field1_require]'][value='0']").prop("checked",true);
						$("input:radio[name='FormBuilder[step_1_field1_mandatory]'][value='0']").prop("checked",true);
						$("input[name='FormBuilder[step_1_field1_require]'][value='0']").next('span').addClass('input-checked');
						$("input[name='FormBuilder[step_1_field1_require]'][value='1']").next('span').removeClass('input-checked');
						$('#step_1_subb').css('display','none');
						$("input:radio[name='FormBuilder[step_1_field1_require]']").parents(':eq(2)').addClass('grey-bg');
					}
					// step 1 filed 1
					if(res['step2']) {
						$("input[name='FormBuilder[step_2_field1_require]'][value='1']").click();						
						if(res['step2'][0]['require'] == 1) {
							
							$("input[name='FormBuilder[step_2_field1_require]'][value='1']").prop("checked",true);
						} else {
							$("input[name='FormBuilder[step_2_field1_require]'][value='0']").prop("checked",true);
							$("input[name='FormBuilder[step_2_field1_mandatory]'][value='0']").prop("checked",true);
							$('#formbuilder-step_2_field1_label').val('');
							$('#step_2_sub').css('display','none');
						} 
						if(res['step2'][0]['mandatory'] == 1) {
							$("input[name='FormBuilder[step_2_field1_mandatory]'][value='1']").prop("checked",true);
							$("input:radio[name='FormBuilder[step_2_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
							$("input[name='FormBuilder[step_2_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
							$("input[name='FormBuilder[step_2_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
						} else {
							$("input[name='FormBuilder[step_2_field1_mandatory]'][value='0']").prop("checked",true);
							$("input:radio[name='FormBuilder[step_2_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
							$("input[name='FormBuilder[step_2_field1_mandatory]'][value='0']").next('span').addClass('input-checked');
							$("input[name='FormBuilder[step_2_field1_mandatory]'][value='1']").next('span').removeClass('input-checked');
						}
						if(res['step2'][0]['label'] != '') {
							 $('#formbuilder-step_2_field1_label').val(res['step2'][0]['label']);
						} else {
							 //$('#formbuilder-step_2_field1_label').val('');
							stepsDisabled();
						}
						if(res['step2'][0]['data_type'] != '') {
							 $('#formbuilder-step_2_field1_data_type').val(res['step2'][0]['data_type']);
							 if(res['step2'][0]['data_type'] == 'radio' || res['step2'][0]['data_type'] == 'checkbox' || res['step2'][0]['data_type'] == 'selectbox') {
								 $('.field-formbuilder-step_2_field1_validation_type').hide();
								 $('#step_2_field1_feild_vlaue input').removeAttr('disabled');
								 $('#step_2_field1_feild_vlaue').css("display","block");
								//alert(res['step2'][0]['values']);
								 if (res['step2'][0]['values'] instanceof Array) {
									//console.log(res['step2'][0]['values']);
									$('#formbuilder-step_2_field1_field_value').val(res['step2'][0]['values'][0]);
									$('.step_2_field1_field_boxes').remove();
									 var field_value = res['step2'][0]['values'];
									 //console.log(field_value);
									 var values_length = res['step2'][0]['values'].length;
									 var j = 0;
									 for(var i= 1; i <= values_length-1;i++) {
										 var no = $(".form-group").length + 1;
							             var more_textbox = $('<div class="form-group step_2_field1_field_boxes">' +
							                '</span></label>' +
							                '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_2_field1_field_boxes[]" value = "'+ field_value[i] +'" id="txtbox' + no + '" />' +
							                '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
							                '</div></div><div class="help-block"></div></div></div>');
							             $("#step_2_field1_feild_vlaue .form-group:last").after(more_textbox);
							             more_textbox.fadeIn("slow");
							             j++;
									 }
								 }
							 } else {
								 //console.log(res['step2']);
								 if(res['step2'][0]['data_type'] == 'edittext') {
									 $('#step_2_field1_no_of_chars').show();
									 $('.field-formbuilder-step_2_field1_no_of_chars').show();
									 $('#formbuilder-step_2_field1_no_chars').val(res['step2'][0]['no_of_chars']);
									 $('#step_2_field1_validation_type').css('display','block');
									 $('.field-formbuilder-step_2_field1_validation_type').css('display','block');
									 $('#formbuilder-step_2_field1_validation_type').val(res['step2'][0]['validation_type']);
									 $('#step_2_field1_feild_vlaue').css("display","none");
									 $('#step_2_field1_vlaue input').attr("disabled","disabled");
									 $('#step_2_field1_feild_vlaue input').attr("disabled","disabled");
								 }
								 if(res['step2'][0]['data_type'] == 'rating') {
									 $('#step_2_field1_no_of_chars').hide();
									 $('.field-formbuilder-step_2_field1_validation_type').hide();
									 $('#step_2_field1_feild_vlaue').css("display","none");
									 $('#step_2_field1_vlaue input').attr("disabled","disabled");
									 $('#step_2_field1_feild_vlaue input').attr("disabled","disabled");
								 }
								 if(res['step2'][0]['data_type'] == 'textarea') {
									 $('#step_2_field1_no_of_chars').show();
									 $('#formbuilder-step_2_field1_no_chars').val(res['step2'][0]['no_of_chars']);
									 $('.field-formbuilder-step_2_field1_validation_type').hide();
									 $('#step_2_field1_feild_vlaue').css("display","none");
									 $('#step_2_field1_vlaue input').attr("disabled","disabled");
									 $('#step_2_field1_feild_vlaue input').attr("disabled","disabled");
								 }
							 }
						} else {
							 //$('#formbuilder-step_2_field1_label').val('');
							stepsDisabled();
						}
					} if(!res['step2']) {
						$('#step_2_sub input').attr('disabled','disabled');
						$('#step_2_sub select').attr('disabled','disabled');
						$("input[name='FormBuilder[step_2_field1_require]'][value='0']").prop("checked",true);
						$("input:radio[name='FormBuilder[step_2_field1_mandatory]'][value='0']").prop("checked",true);
						$("input[name='FormBuilder[step_2_field1_require]'][value='0']").next('span').addClass('input-checked');
						$("input[name='FormBuilder[step_2_field1_require]'][value='1']").next('span').removeClass('input-checked');
						$('#step_2_sub').css('display','none');
						$("input:radio[name='FormBuilder[step_2_field1_require]']").parents(':eq(2)').addClass('grey-bg');
						if ($("#formbuilder-step_2_field1_data_type").val() == 'edittext') {
							$("#formbuilder-step_2_field1_validation_type").val('');
							$("#step_2_field1_no_of_chars").hide();
							$("#step_2_field1_validation_type").css("display","none");
							$(".step_2_field1_field_boxes").remove();
						} else if ($("#formbuilder-step_2_field1_data_type").val() == 'radio') {
							$("#formbuilder-step_2_field1_field_value").val('');
							$(".step_2_field1_field_boxes").remove();
							$("#step_2_field1_feild_vlaue").css("display","none");
						} else if ($("#formbuilder-step_2_field1_data_type").val() == 'checkbox') {
							$("#formbuilder-step_2_field1_field_value").val('');
							$(".step_2_field1_field_boxes").remove();
							$("#step_2_field1_feild_vlaue").css("display","none");
						} else if ($("#formbuilder-step_2_field1_data_type").val() == 'selectbox') {
							$("#formbuilder-step_2_field1_field_value").val('');
							$(".step_2_field1_field_boxes").remove();
							$("#step_2_field1_feild_vlaue").css("display","none");
						} else if ($("#formbuilder-step_2_field1_data_type").val() == 'rating') {
							$("#formbuilder-step_2_field1_field_value").val('');
							$(".step_2_field1_field_boxes").remove();
							$("#step_2_field1_feild_vlaue").css("display","none");
						}
						$("#formbuilder-step_2_field1_data_type").val('');
						$("#formbuilder-step_2_field1_no_chars").val('');
						$("#formbuilder-step_2_field1_validation_type").val('');
						$("input:radio[name='FormBuilder[step_2_field1_require]']").parents(':eq(2)').addClass('grey-bg');
						$('#step_2_sub').css('display','none');
						$(".step_2_field1_field_boxes").remove();
						
					}

					if(res['step3']) {
						$("input[name='FormBuilder[step_3_require]'][value='1']").click();
						$("input[name='FormBuilder[step_3_require]'][value='1']").prop("checked",true);
						//console.log('step3');console.log(res['step3']);
						var step3_array = res['step3'];
						var step3_length = res['step3'].length;
						//console.log(step3_array[0]);
						stepsDisabled();
						var h = 1;
						for(var k = 0;k<= step3_length -1 ;k++) {
							if(res['step3'][k]['require'] == 1) {
								//console.log('#step_3_field'+ h +'_feild_vlaue input')
								$("input[name='FormBuilder[step_3_field"+ h +"_require]'][value='1']").click();
								$("input[name='FormBuilder[step_3_field"+ h +"_require]'][value='1']").prop("checked",true);
								$("input[name='FormBuilder[step_3_field"+ h +"_require]'][value='1']").next('span').addClass('input-checked');
								$("input[name='FormBuilder[step_3_field"+ h +"_require]'][value='0']").next('span').removeClass('input-checked');
								$("input:radio[name='FormBuilder[step_3_field"+ h +"_require]']").parents(':eq(2)').removeClass('grey-bg');
								
							} else {
								$("input[name='FormBuilder[step_3_field"+ h +"_require]'][value='0']").prop("checked",true);
								$("input[name='FormBuilder[step_3_field"+ h +"_mandatory]'][value='0']").prop("checked",true);
								$('#formbuilder-step_3_field'+ h +'_label').val('');
								if ($('#formbuilder-step_3_field'+ h +'_data_type').val() == 'edittext') {
				        			$('#step_3_field'+ h +'_no_of_chars').hide();
				        			$('.field-formbuilder-step_3_field'+ h +'_no_chars').hide();
				        			$('#formbuilder-step_3_field'+ h +'_no_chars').val('');
				        			$('#formbuilder-step_3_field'+ h +'_validation_type').val('');
				        			$('#step_3_field'+ h +'_validation_type').css("display","none");
				        			$('.step_3_field'+ h +'_field_boxes').remove();
				        		} else if ($('#formbuilder-step_3_field'+ h +'_data_type').val() == 'radio') {
				        			$('#formbuilder-step_3_field'+ h +'_field_value').val('');
				        			$('.step_3_field'+ h +'_field_boxes').remove();
				        			$('#step_3_field'+ h +'_vlaue').css("display","none");
				        		} else if ($('#formbuilder-step_3_field'+ h +'_data_type').val() == 'checkbox') {
				        			$('formbuilder-step_3_field'+ h +'_field_value').val('');
				        			$('.step_3_field'+ h +'_field_boxes').remove();
				        			$('#step_3_field'+ h +'_vlaue').css("display","none");
				        		} else if ($('#formbuilder-step_3_field'+ h +'_data_type').val() == 'selectbox') {
				        			$('#formbuilder-step_3_field'+ h +'_field_value').val('');
				        			$('.step_3_field'+ h +'_field_boxes').remove();
				        			$('#step_3_field'+ h +'_vlaue').css("display","none");
				        		} else if ($('#formbuilder-step_3_field'+ h +'_data_type').val() == 'rating') {
				        			$('#formbuilder-step_3_field'+ h +'_field_value').val('');
				        			$('.step_3_field'+ h +'_field_boxes').remove();
				        			$('#step_3_field'+ h +'_vlaue').css("display","none");
				        		}
				        		$('#formbuilder-step_3_field'+ h +'_data_type').val('');
				        		$('#formbuilder-step_3_field'+ h +'_no_chars').val('');
				        		$('#formbuilder-step_3_field'+ h +'_validation_type').val('');
				        		//$("input:radio[name='FormBuilder[step_3_field5_require]']").parents(':eq(2)').addClass('grey-bg');
				        		$('#step_3_field'+ h).css('display','none');
				        		$('.step_3_field'+ h +'_field_boxes').remove();
								$('#step_3_field'+ h).css('display','none');
								$("#step_3_field"+ h +"_vlaue input").attr('disabled', 'disabled');//if no record found
								$("input[name='FormBuilder[step_3_field"+ h +"_require]'][value='0']").next('span').addClass('input-checked');
								$("input[name='FormBuilder[step_3_field"+ h +"_require]'][value='1']").next('span').removeClass('input-checked');
								$("input:radio[name='FormBuilder[step_3_field"+ h +"_require]']").parents(':eq(2)').addClass('grey-bg');
							} 
							if(res['step3'][k]['mandatory'] == 1) {
								$("input[name='FormBuilder[step_3_field"+ h +"_mandatory]'][value='1']").prop("checked",true);
								$("input:radio[name='FormBuilder[step_3_field"+ h +"_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
								$("input[name='FormBuilder[step_3_field"+ h +"_mandatory]'][value='1']").next('span').addClass('input-checked');
								$("input[name='FormBuilder[step_3_field"+ h +"_mandatory]'][value='0']").next('span').removeClass('input-checked');
							} else {
								$("input[name='FormBuilder[step_3_field"+ h +"_mandatory]'][value='0']").prop("checked",true);
								$("input[name='FormBuilder[step_3_field"+ h +"_mandatory]'][value='1']").next('span').removeClass('input-checked');
								$("input:radio[name='FormBuilder[step_3_field"+ h +"_mandatory]']").parents(':eq(2)').addClass('grey-bg');
								$("input[name='FormBuilder[step_3_field"+ h +"_mandatory]'][value='0']").next('span').addClass('input-checked');
								
							}
							if(res['step3'][k]['label'] != '') {
								 $('#formbuilder-step_3_field'+ h +'_label').val(res['step3'][k]['label']);
							} else {
								 //$('#formbuilder-step_3_field'+ h +'_label').val('');
								stepsDisabled();
							}
							if(res['step3'][k]['data_type'] != '') {
								 $('#formbuilder-step_3_field'+ h +'_data_type').val(res['step3'][k]['data_type']);
								 if(res['step3'][k]['data_type'] == 'radio' || res['step3'][k]['data_type'] == 'checkbox' || res['step3'][k]['data_type'] == 'selectbox') {
									 $('.field-formbuilder-step_3_field'+ h +'_validation_type').hide();
									 $('.formbuilder-step_3_field'+ h +'_no_chars').hide();
									 $('#step_3_field'+ h +'_vlaue input').removeAttr('disabled');
									 $('#step_3_field'+ h +'_vlaue').css("display","block");
									 $('.step_3_field'+ h +'_field_boxes').remove();
									//alert(res['step2'][0]['values']);
									 if (res['step3'][k]['values'] instanceof Array) {
										$('#formbuilder-step_3_field'+ h +'_field_value').val(res['step3'][k]['values'][0]);
										 var field_value = res['step3'][k]['values'];
										 var values_length = res['step3'][k]['values'].length;
										 //console.log(res['step3'][k]['values']);
										 var j = 0;
										 for(var i= 1; i <= values_length-1;i++) {
											 $("#step_3_field"+ h +"_vlaue .form-group:last").after('<div></div>');
											 var no = $(".form-group").length + 1;
								             var more_textbox = $('<div class="form-group step_3_field'+ h +'_field_boxes">' +
								                '</span></label>' +
								                '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_3_field'+ h +'_field_boxes[]" value = "'+ field_value[i] +'" id="txtbox' + no + '" />' +
								                '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
								                '</div></div><div class="help-block"></div></div></div>');
								        	 $("#step_3_field"+ h +"_vlaue .form-group:last").after(more_textbox);
								             more_textbox.fadeIn("slow");
								             j++;
										 }
									 }
								 } else {
									 if(res['step3'][k]['data_type'] == 'edittext') {
										 $('#step_3_field'+ h +'_no_of_chars').show();
										 $('.field-formbuilder-step_3_field'+ h +'_no_of_chars').show();
										 $('#formbuilder-step_3_field'+ h +'_no_chars').val(res['step3'][k]['no_of_chars']);
										 $('#step_3_field'+ h +'_validation_type').css('display','block');
										 $('#formbuilder-step_3_field'+ h +'_validation_type').val(res['step3'][k]['validation_type']);
										 $('.field-formbuilder-step_3_field' +h +'_validation_type').css('display','block');
										 $('#step_3_field'+ h +'_vlaue').css("display","none");
										 $('#step_3_field'+ h +'_vlaue input').attr("disabled","disabled");
									 }
									 if(res['step3'][k]['data_type'] == 'rating') {
										 $('#step_3_field'+ h +'_no_of_chars').hide();
										 $('.field-formbuilder-step_3_field'+ h +'_no_of_chars').hide();
										 $('.field-formbuilder-step_3_field'+ h +'_validation_type').hide();
										 $('#step_3_field'+ h +'_vlaue').css("display","none");
										 $('#step_3_field'+ h +'_vlaue input').attr("disabled","disabled");
									 } 
									 if(res['step3'][k]['data_type'] == 'textarea') {
										 $('#step_3_field'+ h +'_no_of_chars').show();
										 $('.field-formbuilder-step_3_field'+ h +'_no_of_chars').show();
										 $('#formbuilder-step_3_field'+ h +'_no_chars').val(res['step3'][k]['no_of_chars']);
										 $('.field-formbuilder-step_3_field'+ h +'_validation_type').hide();
										 $('#step_3_field'+ h +'_vlaue').css("display","none");
										 $('#step_3_field'+ h +'_vlaue input').attr("disabled","disabled");
									 }
								 }
						}
							h++;
					}
					} 
					if(!res['step3']) {
						/*$('#step_3_fields input').val('');
						$('#step_3_fields select').val('');*/
						$('#step_3_fields').hide();
						$("input[name='FormBuilder[step_3_require]'][value='0']").click();
						$("input[name='FormBuilder[step_3_require]'][value='0']").prop("checked",true);
						$("input[name='FormBuilder[step_3_require]'][value='0']").next('span').addClass('input-checked');
						$("input[name='FormBuilder[step_3_require]'][value='1']").next('span').removeClass('input-checked');
						$('#step_3_field1_validation_type').css("display","none");
						$('#step_3_field2_validation_type').css("display","none");
						$('#step_3_field3_validation_type').css("display","none");
						$('#step_3_field4_validation_type').css("display","none");
						$('#step_3_field5_validation_type').css("display","none");
						$("input:radio[name='FormBuilder[step_3_require]']").parents(':eq(2)').addClass('grey-bg');	
						var l = 0;
						for(l; l < 5; l++) {
						$('#formbuilder-step_3_field'+ l +'_data_type').val('');
						$('#formbuilder-step_3_field'+ l +'_no_chars').val('');
						$('#step_3_field'+ l +'_no_of_chars').hide();
						$('.field-formbuilder-step_4_field'+ l +'_no_of_chars').hide();
						$('#step_3_field'+ l +'_vlaue').css("display","none");
						$('formbuilder-step_3_field'+ l +'_field_value').val('');
						$('.step_3_field'+ l +'_field_boxes').remove();
						}
					
					}
					if(res['step4']) {
						$("input[name='FormBuilder[step_4_field1_require]'][value='1']").click();						
						if(res['step4'][0]['require'] == 1) {
							$("input[name='FormBuilder[step_4_field1_require]'][value='1']").prop("checked",true);
						} else {
							$("input[name='FormBuilder[step_4_field1_require]'][value='0']").prop("checked",true);
							$("input[name='FormBuilder[step_4_field1_mandatory]'][value='0']").prop("checked",true);
							$('#formbuilder-step_4_field1_label').val('');
							$('#step_4_field1').css('display','none');
						} 
						if(res['step4'][0]['mandatory'] == 1) {
							$("input[name='FormBuilder[step_4_field1_mandatory]'][value='1']").prop("checked",true);
							$("input:radio[name='FormBuilder[step_4_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
							$("input[name='FormBuilder[step_4_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
							$("input[name='FormBuilder[step_4_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
						} else {
							$("input[name='FormBuilder[step_4_field1_mandatory]'][value='0']").prop("checked",true);
							$("input[name='FormBuilder[step_4_field1_mandatory]'][value='1']").next('span').removeClass('input-checked');
							$("input:radio[name='FormBuilder[step_4_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
							$("input[name='FormBuilder[step_4_field1_mandatory]'][value='0']").next('span').addClass('input-checked');
							
						}
						if(res['step4'][0]['label'] != '') {
							 $('#formbuilder-step_4_field1_label').val(res['step4'][0]['label']);
						} else {
							 //$('#formbuilder-step_4_field1_label').val('');
							stepsDisabled();
						}
						if(res['step4'][0]['data_type'] != '') {
							 $('#formbuilder-step_4_field1_data_type').val(res['step4'][0]['data_type']);
							 if(res['step4'][0]['data_type'] == 'radio' || res['step4'][0]['data_type'] == 'checkbox' || res['step4'][0]['data_type'] == 'selectbox') {
								 $('.field-formbuilder-step_4_field1_validation_type').hide();
								 $('#step_4_field1_vlaue').css('display','block');
								 $('#step_4_field1_vlaue input').removeAttr('disabled');
								 //$('#step_4_field1_feild_vlaue').css("display","block");
								 $('.step_4_field1_field_boxes').remove();
								//alert(res['step4'][0]['values']);
								 if (res['step4'][0]['values'] instanceof Array) {
									 //console.log('step4');
									//console.log(res['step4'][0]['values']);
									$('#formbuilder-step_4_field1_field_value').val(res['step4'][0]['values'][0]);
									 var field_value = res['step4'][0]['values'];
									 var values_length = res['step4'][0]['values'].length;
									 var j = 0;
									 for(var i= 1; i <= values_length-1;i++) {
										 var no = $(".form-group").length + 1;
							             var more_textbox = $('<div class="form-group step_4_field1_field_boxes">' +
							                '</span></label>' +
							                '<div class="row"><div class="col-sm-offset-6 col-sm-6 col-md-offset-5  col-md-7 col-lg-offset-4 col-lg-4"><div class="dd-check"><span class="pull-left"><input class="form-control" type="text" name="step_4_field1_field_boxes[]" value = "'+ field_value[i] +'" id="txtbox' + no + '" />' +
							                '</span><span class="cb"><a href="#" class="btn btn-danger btn-xs remove-txt"><i class="fa fa-minus"></i></a></span>' +
							                '</div></div><div class="help-block"></div></div></div>');
							             $("#step_4_field1_vlaue .form-group:last").after(more_textbox);
							             more_textbox.fadeIn("slow");
							             j++;
									 }
								 }
							 } else {
								 if(res['step4'][0]['data_type'] == 'edittext') {
									 $('#step_4_field1_no_of_chars').show();
									 $('.field-formbuilder-step_4_field1_no_of_chars').show();
									 $('#formbuilder-step_4_field1_no_chars').val(res['step4'][0]['no_of_chars']);
									 $('#step_4_field1_validation_type').css('display','block');
									 $('#formbuilder-step_4_field1_validation_type').val(res['step4'][0]['validation_type']);
									 $('#step_4_field1_feild_vlaue').css("display","none");
									 $('#step_4_field1_vlaue input').attr("disabled","disabled");
								 }
								 if(res['step4'][0]['data_type'] == 'rating') {
									 $('#step_4_field1_no_of_chars').hide();
									 $('.field-formbuilder-step_4_field1_no_of_chars').hide();
									 $('.field-formbuilder-step_4_field1_validation_type').hide();
									 $('#step_4_field1_vlaue').css("display","none");
									 $('#step_4_field1_vlaue input').attr("disabled","disabled");
								 }
								 if(res['step4'][0]['data_type'] == 'textarea') {
									 $('#step_4_field1_no_of_chars').show();
									 $('.field-formbuilder-step_4_field1_no_of_chars').show();
									 $('#formbuilder-step_4_field1_no_chars').val(res['step4'][0]['no_of_chars']);
									 $('.field-formbuilder-step_4_field1_validation_type').hide();
									 $('#step_4_field1_vlaue').css("display","none");
									 $('#step_4_field1_vlaue input').attr("disabled","disabled");
								 }
							 }
						} else {
							 //$('#formbuilder-step_4_field1_label').val('');
							stepsDisabled();
						}
					}
					if(!res['step4']) {
						$('#step_4_field1 input').attr('disabled','disabled');
						$('#step_4_field1 select').attr('disabled','disabled');
						$("input[name='FormBuilder[step_4_field1_require]'][value='0']").prop("checked",true);
						$("input:radio[name='FormBuilder[step_4_field1_mandatory]'][value='0']").prop("checked",true);
						$("input[name='FormBuilder[step_4_field1_require]'][value='0']").next('span').addClass('input-checked');
						$("input[name='FormBuilder[step_4_field1_require]'][value='1']").next('span').removeClass('input-checked');
						$('#step_4_field1').css('display','none');
						$("input:radio[name='FormBuilder[step_4_field1_require]']").parents(':eq(2)').addClass('grey-bg');
						if ($('#formbuilder-step_4_field1_data_type').val() == 'edittext') {
							$('#step_4_field1_no_of_chars').hide();
				    		$('.field-formbuilder-step_4_field1_no_chars').hide();
				    		$("#formbuilder-step_4_field1_no_chars").val('');
							$("#formbuilder-step_4_field1_validation_type").val('');
							$("#step_4_field1_validation_type").css("display","none");
							$(".step_4_field1_field_boxes").remove();
						} else if ($('#formbuilder-step_4_field1_data_type').val() == 'radio') {
							$("#formbuilder-step_4_field1_field_value").val('');
							$(".step_4_field1_field_boxes").remove();
							$("#step_4_field1_vlaue").css("display","block");
						} else if ($('#formbuilder-step_4_field1_data_type').val() == 'checkbox') {
							$("#formbuilder-step_4_field1_field_value").val('');
							$(".step_4_field1_field_boxes").remove();
							$("#step_4_field1_vlaue").css("display","block");
						} else if ($('#formbuilder-step_4_field1_data_type').val() == 'selectbox') {
							$("#formbuilder-step_4_field1_field_value").val('');
							$(".step_4_field1_field_boxes").remove();
							$("#step_4_field1_vlaue").css("display","block");
						} else if ($('#formbuilder-step_4_field1_data_type').val() == 'rating') {
							$("#formbuilder-step_4_field1_field_value").val('');
							$(".step_4_field1_field_boxes").remove();
							$("#step_4_field1_vlaue").css("display","none");
						}
						$('#formbuilder-step_4_field1_data_type').val('');
						$("#formbuilder-step_4_field1_no_chars").val('');
						$('#step_4_field1').css('display','none');
						$(".step_4_field1_field_boxes").remove();
					}
					if(res['step5']) {
						$("input[name='FormBuilder[step_5_field1_require]'][value='1']").click();						

						//alert(res['step1'][0]['require']);
						if(res['step5'][0]['require'] == 1) {
							$("input[name='FormBuilder[step_5_field1_require]'][value='1']").prop("checked",true);
						} else {
							$("input[name='FormBuilder[step_5_field1_require]'][value='0']").prop("checked",true);
							$("input[name='FormBuilder[step_5_field1_mandatory]'][value='0']").prop("checked",true);
							$('#formbuilder-step_5_field1_label').val('');
							$('#step_5_field1').css('display','none');
						}
						if(res['step5'][0]['mandatory'] == 1) {
							$("input[name='FormBuilder[step_5_field1_mandatory]'][value='1']").prop("checked",true);
							$("input:radio[name='FormBuilder[step_5_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
							$("input[name='FormBuilder[step_5_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
							$("input[name='FormBuilder[step_5_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
						} else {
							$("input[name='FormBuilder[step_5_field1_mandatory]'][value='0']").prop("checked",true);
							$("input[name='FormBuilder[step_5_field1_mandatory]'][value='1']").next('span').removeClass('input-checked');
							$("input:radio[name='FormBuilder[step_5_field1_mandatory]']").parents(':eq(2)').addClass('grey-bg');
							$("input[name='FormBuilder[step_5_field1_mandatory]'][value='0']").next('span').addClass('input-checked');
							
						}
						if(res['step5'][0]['label'] != '') {
							$('#formbuilder-step_5_field1_label').val(res['step5'][0]['label']);
						} else {
							//$('#formbuilder-step_5_field1_label').val('');
							stepsDisabled();
						}
						if(res['step5'][0]['no_of_images'] != '' && res['step5'][0]['no_of_images'] != 0) {
							$('#formbuilder-step_5_field1_no_of_images').val(res['step5'][0]['no_of_images']);
						} else {
							$('#formbuilder-step_5_field1_no_of_images').val('1');
						}
					} 
					if(!res['step5']) {
						$('#step_5_field1 input').attr('disabled','disabled');
						$("input[name='FormBuilder[step_5_field1_require]'][value='0']").prop("checked",true);
						$("input:radio[name='FormBuilder[step_5_field1_mandatory]'][value='0']").prop("checked",true);
						$("input[name='FormBuilder[step_5_field1_require]'][value='0']").next('span').addClass('input-checked');
						$("input[name='FormBuilder[step_5_field1_require]'][value='1']").next('span').removeClass('input-checked');
						$('#step_5_field1').css('display','none');
						$("input:radio[name='FormBuilder[step_5_field1_require]']").parents(':eq(2)').addClass('grey-bg');
					}
				} else {
					//$('#accordion input').val('');
					//$('#accordion select').val('');
					//$('#step_2_field1_feild_vlaue').hide();
					//alert('empty');
					$('#step_3_field1_vlaue').hide();
					$('#step_3_field2_vlaue').hide();
					$('#step_3_field3_vlaue').hide();
					$('#step_3_field4_vlaue').hide();
					$('#step_3_field5_vlaue').hide();
					$('#step_4_field1_vlaue').hide();
					
					$('#step_2_field1_no_of_chars').hide();
					$('#step_3_field1_no_of_chars').hide();
					$('#step_3_field2_no_of_chars').hide();
					$('#step_3_field3_no_chars').hide();
					$('#step_3_field4_no_of_chars').hide();
					$('#step_3_field5_no_of_chars').hide();
					$('#step_4_field1_no_of_chars').hide();
					
					$('#step_2_field1_validation_type').hide();
					$('#step_3_field1_validation_type').hide();
					$('#step_3_field2_validation_type').hide();
					$('#step_3_field3_validation_type').hide();
					$('#step_3_field4_validation_type').hide();
					$('#step_3_field5_validation_type').hide();
					$('#step_4_field1_validation_type').hide();
					$('#step_5_field1_validation_type').hide();
					
					$("input[name='FormBuilder[step_1_field1_require]'][value='1']").prop("checked",true);
					$("input:radio[name='FormBuilder[step_1_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
					$("input:radio[name='FormBuilder[step_1_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
					$("input[name='FormBuilder[step_1_field1_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_1_field1_require]'][value='1']").next('span').addClass('input-checked');
					$("input[name='FormBuilder[step_1_field1_mandatory]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_1_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_1_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
					$("#step_1_subb input").removeAttr("disabled");
					$("#step_1_subb").css("display", "block");
					//$('#formbuilder-step_1_field1_label').val('');
					
					
					$("input[name='FormBuilder[step_2_field1_require]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_2_field1_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_2_field1_require]'][value='1']").next('span').addClass('input-checked');
					$("input:radio[name='FormBuilder[step_2_field1_mandatory]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_2_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_2_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
					$("#step_2_sub input").removeAttr("disabled");
					$("#step_2_sub select").removeAttr("disabled");
					$("#step_2_sub").css("display", "block");
					//$('#formbuilder-step_2_field1_label').val('');
					$('#formbuilder-step_2_field1_data_type').val('');
					$('#formbuilder-step_2_field1_validation_type').val('');
					$('#formbuilder-step_2_field1_field_value').val('');
					$('#step_2_field1_feild_vlaue input').attr("disabled","disabled");
					$('#step_2_field1_feild_vlaue').css("display","none");
					$('.step_2_field1_field_boxes').remove();
					$("input:radio[name='FormBuilder[step_2_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
					$("input:radio[name='FormBuilder[step_2_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');
					
					$("#step_3_fields").css("display", "block");
					$("input:radio[name='FormBuilder[step_3_require]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_require]'][value='1']").next('span').addClass('input-checked');
					$("#step_3_fields input").removeAttr("disabled");
					$("#step_3_fields select").removeAttr("disabled");
					$("input:radio[name='FormBuilder[step_3_require]']").parents(':eq(2)').removeClass('grey-bg');
						stepsDisabled();
					
					$("input:radio[name='FormBuilder[step_3_field1_require]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field1_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field1_require]'][value='1']").next('span').addClass('input-checked');
					$("input:radio[name='FormBuilder[step_3_field1_mandatory]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
					$("#step_3_field1 input").removeAttr("disabled");
					$("#step_3_field1 select").removeAttr("disabled");
					$("#step_3_field1").css("display", "block");
					//$('#formbuilder-step_3_field1_label').val('');
					$('#formbuilder-step_3_field1_data_type').val('');
					$('#formbuilder-step_3_field1_validation_type').val('');
					$('#formbuilder-step_3_field1_field_value').val('');
					$('#step_3_field1_vlaue input').attr("disabled","disabled");
					$('#step_3_field1_vlaue').css("display","none");
					$('.step_3_field1_field_boxes').remove();
					$("input:radio[name='FormBuilder[step_3_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
					$("input:radio[name='FormBuilder[step_3_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');

					
					$("input:radio[name='FormBuilder[step_3_field2_require]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field2_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field2_require]'][value='1']").next('span').addClass('input-checked');
					$("input:radio[name='FormBuilder[step_3_field2_mandatory]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field2_mandatory]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field2_mandatory]'][value='1']").next('span').addClass('input-checked');
					$("#step_3_field2").css("display", "block");
					//$('#formbuilder-step_3_field2_label').val('');
					$('#formbuilder-step_3_field2_data_type').val('');
					$('#formbuilder-step_3_field2_validation_type').val('');
					$('#formbuilder-step_3_field2_field_value').val('');
					$('#step_3_field2_vlaue input').attr("disabled","disabled");
					$('#step_3_field2_vlaue').css("display","none");
					$('.step_3_field2_field_boxes').remove();
					$("input:radio[name='FormBuilder[step_3_field2_require]']").parents(':eq(2)').removeClass('grey-bg');
					$("input:radio[name='FormBuilder[step_3_field2_mandatory]']").parents(':eq(2)').removeClass('grey-bg');

					
					$("input[name='FormBuilder[step_3_field3_require]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field3_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field3_require]'][value='1']").next('span').addClass('input-checked');
					$("input:radio[name='FormBuilder[step_3_field3_mandatory]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field3_mandatory]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field3_mandatory]'][value='1']").next('span').addClass('input-checked');
					$("#step_3_field3").css("display", "block");
					//$('#formbuilder-step_3_field3_label').val('');
					$('#formbuilder-step_3_field3_data_type').val('');
					$('#formbuilder-step_3_field3_validation_type').val('');
					$('#formbuilder-step_3_field3_field_value').val('');
					$('#step_3_field3_vlaue input').attr("disabled","disabled");
					$('#step_3_field3_vlaue').css("display","none");
					$('.step_3_field3_field_boxes').remove();
					$("input:radio[name='FormBuilder[step_3_field3_require]']").parents(':eq(2)').removeClass('grey-bg');
					$("input:radio[name='FormBuilder[step_3_field3_mandatory]']").parents(':eq(2)').removeClass('grey-bg');

					$("input[name='FormBuilder[step_3_field4_require]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field4_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field4_require]'][value='1']").next('span').addClass('input-checked');
					$("input:radio[name='FormBuilder[step_3_field4_mandatory]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field4_mandatory]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field4_mandatory]'][value='1']").next('span').addClass('input-checked');
					$("#step_3_field4").css("display", "block");
					//$('#formbuilder-step_3_field4_label').val('');
					$('#formbuilder-step_3_field4_data_type').val('');
					$('#formbuilder-step_3_field4_validation_type').val('');
					$('#formbuilder-step_3_field4_field_value').val('');
					$('#step_3_field4_vlaue input').attr("disabled","disabled");
					$('#step_3_field4_vlaue').css("display","none");
					$('.step_3_field4_field_boxes').remove();
					$("input:radio[name='FormBuilder[step_3_field4_require]']").parents(':eq(2)').removeClass('grey-bg');
					$("input:radio[name='FormBuilder[step_3_field4_mandatory]']").parents(':eq(2)').removeClass('grey-bg');

					$("input[name='FormBuilder[step_3_field5_require]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field5_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field5_require]'][value='1']").next('span').addClass('input-checked');
					$("input:radio[name='FormBuilder[step_3_field5_mandatory]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_3_field5_mandatory]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_3_field5_mandatory]'][value='1']").next('span').addClass('input-checked');
					$("#step_3_field5").css("display", "block");
					//$('#formbuilder-step_3_field5_label').val('');
					$('#formbuilder-step_3_field5_data_type').val('');
					$('#formbuilder-step_3_field5_validation_type').val('');
					$('#formbuilder-step_3_field5_field_value').val('');
					$('#step_3_field5_vlaue input').attr("disabled","disabled");
					$('#step_3_field5_vlaue').css("display","none");
					$('.step_3_field5_field_boxes').remove();
					$("input:radio[name='FormBuilder[step_3_field5_require]']").parents(':eq(2)').removeClass('grey-bg');
					$("input:radio[name='FormBuilder[step_3_field5_mandatory]']").parents(':eq(2)').removeClass('grey-bg');

					$("input[name='FormBuilder[step_4_field1_require]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_4_field1_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_4_field1_require]'][value='1']").next('span').addClass('input-checked');
					$("input:radio[name='FormBuilder[step_4_field1_mandatory]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_4_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_4_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
					$("#step_4_field1").css("display", "block");
					//$('#formbuilder-step_4_field1_label').val('');
					$('#formbuilder-step_4_field1_data_type').val('');
					$('#formbuilder-step_4_field1_validation_type').val('');
					$('#formbuilder-step_4_field1_field_value').val('');
					//$('#step_4_field1_vlaue input').attr("disabled","disabled");
					$('#step_4_field1 input').removeAttr("disabled");
					$('#step_4_field1 select').removeAttr("disabled");
					$('#step_4_field1_vlaue').css("display","none");
					$('.step_4_field1_field_boxes').remove();
					$("input:radio[name='FormBuilder[step_4_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
					$("input:radio[name='FormBuilder[step_4_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');

					$('#step_5_field1 input').removeAttr('disabled');
					$("input:radio[name='FormBuilder[step_5_field1_require]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_5_field1_require]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_5_field1_require]'][value='1']").next('span').addClass('input-checked');
					$("input:radio[name='FormBuilder[step_5_field1_mandatory]'][value='1']").prop("checked",true);
					$("input[name='FormBuilder[step_5_field1_mandatory]'][value='0']").next('span').removeClass('input-checked');
					$("input[name='FormBuilder[step_5_field1_mandatory]'][value='1']").next('span').addClass('input-checked');
					$("#step_5_field1").css("display", "block");
					//$('#formbuilder-step_5_field1_label').val('');
					$('#formbuilder-step_5_field1_label').removeAttr("disabled");
					$("input:radio[name='FormBuilder[step_5_field1_require]']").parents(':eq(2)').removeClass('grey-bg');
					$("input:radio[name='FormBuilder[step_5_field1_mandatory]']").parents(':eq(2)').removeClass('grey-bg');

					$('#refresh_form').trigger('reset');
				}
				}
			});
		}
}
		
// activity wise disable fields
		function stepsDisabled() {
			if ($('#formbuilder-activity').val() == 1) {
				$('#fgm').css('display','none');
				$('#fgm input').attr('disabled','disabled');
				$('#fgm select').attr('disabled','disabled');
				$('#demo').css('display','none');
				$('#demo input').attr('disabled','disabled');
				$('#demo select').attr('disabled','disabled');
				$('#mc input').attr('disabled','disabled');
				$('#mc select').attr('disabled','disabled');
				$('#mc').css('display','none');
			/*	$('#formbuilder-step_1_field1_label').val('Sub Activity');
				$('#formbuilder-step_2_field1_label').val('Purpose');
				$('#formbuilder-step_3_field1_label').val('Farmer Name');
				$('#formbuilder-step_3_field2_label').val('Mobile Number');*/
			}
			if ($('#formbuilder-activity').val() == 2) {
				$('#demo input').attr('disabled','disabled');
				$('#demo select').attr('disabled','disabled');
				$('#demo').css('display','none');
				$('#mc input').attr('disabled','disabled');
				$('#mc select').attr('disabled','disabled');
				$('#mc').css('display','none');
			/*	$('#formbuilder-step_1_field1_label').val('Sub Activity');
				$('#formbuilder-step_2_field1_label').val('Purpose');
				$('#formbuilder-step_3_field1_label').val('Farmers');
				$('#formbuilder-step_3_field2_label').val('Female Farmers');
				$('#formbuilder-step_3_field3_label').val('Partners');
				$('#formbuilder-step_4_field1_label').val('Remarks');
				$('#formbuilder-step_5_field1_label').val('Images');*/
			}
			if ($('#formbuilder-activity').val() == 3) {
				$('#demo input').attr('disabled','disabled');
				$('#demo select').attr('disabled','disabled');
				$('#demo').css('display','none');
	/*			$('#formbuilder-step_2_field1_label').val('Sub Activity');
				$('#formbuilder-step_2_field1_label').val('Purpose');
				$('#formbuilder-step_3_field1_label').val('Farmers');
				$('#formbuilder-step_3_field2_label').val('Female Farmers');
				$('#formbuilder-step_3_field3_label').val('Partners');
				$('#formbuilder-step_3_field4_label').val('Villages');
				$('#formbuilder-step_4_field1_label').val('Remarks');
				$('#formbuilder-step_5_field1_label').val('Images');*/
			}
			if($('#formbuilder-activity').val() == '4') {
				$('#fgm').css('display','block');
				$('#fgm input').removeAttr('disabled');
				$('#fgm select').removeAttr('disabled');
				$('#mc').css('display','block');
				$('#mc input').removeAttr('disabled');
				$('#mc select').removeAttr('disabled');
				$('#demo').css('display','block');
				$('#demo input').removeAttr('disabled');
				$('#demo select').removeAttr('disabled');
/*				$('#formbuilder-step_2_field1_label').val('Sub Activity');
				$('#formbuilder-step_2_field1_label').val('Observation');
				$('#formbuilder-step_3_field1_label').val('Farmer Name');
				$('#formbuilder-step_3_field2_label').val('Mobile Number');
				$('#formbuilder-step_3_field3_label').val('Farmers');
				$('#formbuilder-step_3_field4_label').val('Female Farmers');
				$('#formbuilder-step_3_field5_label').val('Partners');
				$('#formbuilder-step_4_field1_label').val('Remarks');
				$('#formbuilder-step_5_field1_label').val('Images');*/
			}
		}
/* dynamic form ajax request end */	
		function stepsDisabledLabels() {
			if ($('#formbuilder-activity').val() == 1) {
				$('#formbuilder-step_1_field1_label').val('Sub Activity');
				$('#formbuilder-step_2_field1_label').val('Purpose');
				$('#formbuilder-step_3_field1_label').val('Farmer Name');
				$('#formbuilder-step_3_field2_label').val('Mobile Number');
			}
			if ($('#formbuilder-activity').val() == 2) {
				$('#formbuilder-step_1_field1_label').val('Sub Activity');
				$('#formbuilder-step_2_field1_label').val('Purpose');
				$('#formbuilder-step_3_field1_label').val('Farmers');
				$('#formbuilder-step_3_field2_label').val('Female Farmers');
				$('#formbuilder-step_3_field3_label').val('Partners');
				$('#formbuilder-step_4_field1_label').val('Remarks');
				$('#formbuilder-step_5_field1_label').val('Images');
			}
			if ($('#formbuilder-activity').val() == 3) {
				$('#formbuilder-step_2_field1_label').val('Sub Activity');
				$('#formbuilder-step_2_field1_label').val('Purpose');
				$('#formbuilder-step_3_field1_label').val('Farmers');
				$('#formbuilder-step_3_field2_label').val('Female Farmers');
				$('#formbuilder-step_3_field3_label').val('Partners');
				$('#formbuilder-step_3_field4_label').val('Villages');
				$('#formbuilder-step_4_field1_label').val('Remarks');
				$('#formbuilder-step_5_field1_label').val('Images');
			}
			if($('#formbuilder-activity').val() == '4') {
				$('#formbuilder-step_2_field1_label').val('Sub Activity');
				$('#formbuilder-step_2_field1_label').val('Observation');
				$('#formbuilder-step_3_field1_label').val('Farmer Name');
				$('#formbuilder-step_3_field2_label').val('Mobile Number');
				$('#formbuilder-step_3_field3_label').val('Farmers');
				$('#formbuilder-step_3_field4_label').val('Female Farmers');
				$('#formbuilder-step_3_field5_label').val('Partners');
				$('#formbuilder-step_4_field1_label').val('Remarks');
				$('#formbuilder-step_5_field1_label').val('Images');
			}
		}
        
 /* dynamic form switch button start*/
 $('.modal-radio').wrap('<div class="toggle"></div>');
 $('.modal-radio').click(function(){
	    $(this).children('span').addClass('input-checked');
	    $(this).parent('.toggle').siblings('.toggle').children('label').children('span').removeClass('input-checked');
	});
 /* dynamic form switch button end*/

/* dynamic form save button click start*/
 /*$('#dynamic-form-save').on('submit', function(){
	var fields_count = '';//count of fields when radio, checkbox or selectbox selected
	var step_2_field1_lenth = $("input[name='step_2_field1_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
	var step_2_field1_field_boxes_values = [];
	if (step_2_field1_lenth > 0) {
		var skipNo = 0;
		$("input[name='step_2_field1_field_boxes[]']").each(function() {
			if (skipNo == 0) {
				skipNo = 1;
				return;
			}
		    var step_2_field1_value = $(this).val();
		    if (step_2_field1_value) {
		    	$(this).parents(':eq(2)').next().html('');
		    	step_2_field1_field_boxes_values.push(step_2_field1_value);
		    } else {
		    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
		    }
		});
	}
	var step_3_field1_lenth = $("input[name='step_3_field1_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
	var step_3_field1_field_boxes_values = [];
	if (step_3_field1_lenth > 0) {
		var skipNo = 0;
		$("input[name='step_3_field1_field_boxes[]']").each(function() {
			if (skipNo == 0) {
				skipNo = 1;
				return;
			}
		    var step_3_field1_value = $(this).val();
		    if (step_3_field1_value) {
		    	$(this).parents(':eq(2)').next().html('');
		    	step_3_field1_field_boxes_values.push(step_3_field1_value);
		    } else {
		    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
		    }
		});
	}
	var step_3_field2_lenth = $("input[name='step_3_field2_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
	var step_3_field2_field_boxes_values = [];
	if (step_3_field2_lenth > 0) {
		var skipNo = 0;
		$("input[name='step_3_field2_field_boxes[]']").each(function() {
			if (skipNo == 0) {
				skipNo = 1;
				return;
			}
		    var step_3_field2_value = $(this).val();
		    if (step_3_field2_value) {
		    	$(this).parents(':eq(2)').next().html('');
		    	step_3_field2_field_boxes_values.push(step_3_field2_value);
		    } else {
		    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
		    }
		});
	}
	var step_3_field3_lenth = $("input[name='step_3_field3_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
	var step_3_field3_field_boxes_values = [];
	if (step_3_field3_lenth > 0) {
		var skipNo = 0;
		$("input[name='step_3_field3_field_boxes[]']").each(function() {
			if (skipNo == 0) {
				skipNo = 1;
				return;
			}
		    var step_3_field3_value = $(this).val();
		    if (step_3_field3_value) {
		    	$(this).parents(':eq(2)').next().html('');
		    	step_3_field3_field_boxes_values.push(step_3_field3_value);
		    } else {
		    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
		    }
		});
	}
	var step_3_field4_lenth = $("input[name='step_3_field4_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
	var step_3_field4_field_boxes_values = [];
	if (step_3_field4_lenth > 0) {
		var skipNo = 0;
		$("input[name='step_3_field4_field_boxes[]']").each(function() {
			if (skipNo == 0) {
				skipNo = 1;
				return;
			}
		    var step_3_field4_value = $(this).val();
		    if (step_3_field4_value) {
		    	$(this).parents(':eq(2)').next().html('');
		    	step_3_field4_field_boxes_values.push(step_3_field4_value);
		    } else {
		    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
		    }
		});
	}
	var step_3_field5_lenth = $("input[name='step_3_field5_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
	var step_3_field5_field_boxes_values = [];
	if (step_3_field5_lenth > 0) {
		var skipNo = 0;
		$("input[name='step_3_field5_field_boxes[]']").each(function() {
			if (skipNo == 0) {
				skipNo = 1;
				return;
			}
		    var step_3_field5_value = $(this).val();
		    if (step_3_field5_value) {
		    	$(this).parents(':eq(2)').next().html('');
		    	step_3_field5_field_boxes_values.push(step_3_field5_value);
		    } else {
		    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
		    }
		});
	}
	var step_4_field1_lenth = $("input[name='step_4_field1_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
	var step_4_field1_field_boxes_values = [];
	if (step_4_field1_lenth > 0) {
		var skipNo = 0;
		$("input[name='step_4_field1_field_boxes[]']").each(function() {
			if (skipNo == 0) {
				skipNo = 1;
				return;
			}
		    var step_4_field1_value = $(this).val();
		    if (step_4_field1_value) {
		    	$(this).parents(':eq(2)').next().html('');
		    	step_4_field1_field_boxes_values.push(step_4_field1_value);
		    } else {
		    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
		    }
		});
	}
	//if any errors are there collapse menu has to open start
	setTimeout(function(){ accordionOpen(); }, 500);
	//if any errors are there collapse menu has to open end
	
	if (step_2_field1_field_boxes_values.length != step_2_field1_lenth || step_3_field1_field_boxes_values.length != step_3_field1_lenth || step_3_field2_field_boxes_values.length != step_3_field2_lenth || step_3_field3_field_boxes_values.length != step_3_field3_lenth || step_3_field4_field_boxes_values.length != step_3_field4_lenth || step_3_field5_field_boxes_values.length != step_3_field5_lenth || step_4_field1_field_boxes_values.length != step_4_field1_lenth) {
		return false;
 }
	return true;
});	*/
 /* dynamic form save button click end*/
//if any errors are there collapse menu has to open start
/*function accordionOpen() {
   $('.accordion').each(function(){
		if ($(this).find('.help-block').text() != '') {
			if ($(this).hasClass('in')) {
				return false;
			} else {
				$(this).prev().find('a').click();
				return false;
			}
		} else {
			return true;
		}
   });
}*/
//if any errors are there collapse menu has to open end
/* dynamic form save button click start*/

 $('#dynamic-form-save').on('beforeSubmit', function(){
	minimumSteps_count = 0;
	minimum_steps = true;
	var fields_count = 0;//count of fields when radio, checkbox or selectbox selected
	var step_2_field1_lenth = 0;
	var step_2_field1_field_boxes_values = [];
	var step_3_field1_lenth = 0;
	var step_3_field1_field_boxes_values = [];
	var step_3_field2_lenth = 0;
	var step_3_field2_field_boxes_values = [];
	var step_3_field3_lenth = 0;
	var step_3_field3_field_boxes_values = [];
	var step_3_field4_lenth = 0;
	var step_3_field4_field_boxes_values = [];
	var step_3_field5_lenth = 0;
	var step_3_field5_field_boxes_values = [];
	var step_4_field1_lenth = 0;
	var step_4_field1_field_boxes_values = [];
	var step2_field1_unique_values_arr = [];
	var step2_field1_unique_values_arr_results = [];
	var step3_field1_unique_values_arr = [];
	var step3_field1_unique_values_arr_results = [];
	var step3_field2_unique_values_arr = [];
	var step3_field2_unique_values_arr_results = [];
	var step3_field3_unique_values_arr = [];
	var step3_field3_unique_values_arr_results = [];
	var step3_field4_unique_values_arr = [];
	var step3_field4_unique_values_arr_results = [];
	var step3_field5_unique_values_arr = [];
	var step3_field5_unique_values_arr_results = [];
	var step4_field1_unique_values_arr = [];
	var step4_field1_unique_values_arr_results = [];
	var unique_fields_count = 0;//count of unique fields values when radio, checkbox or selectbox selected
	var min = 0; //differentiate with add more and duplicate values
	if ($("input[name='FormBuilder[step_2_field1_require]']:checked").val() == 1) {
		step_2_field1_lenth = $("input[name='step_2_field1_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
		var step2_field1_value_unique = $('#formbuilder-step_2_field1_field_value').val();
		step2_field1_unique_values_arr.push(step2_field1_value_unique);
		if (step_2_field1_lenth > 0) {
			var skipNo = 0;
			$("input[name='step_2_field1_field_boxes[]']").each(function() {
				if (skipNo == 0) {
					skipNo = 1;
					return;
				}
			    var step_2_field1_value = $(this).val();
			    if (step_2_field1_value) {
			    	$(this).parents(':eq(2)').next().html('');
			    	step_2_field1_field_boxes_values.push(step_2_field1_value);
			    	step2_field1_unique_values_arr.push(step_2_field1_value);
			    } else {
			    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
			    }
			});
		} else {
			if ($('#formbuilder-step_2_field1_data_type').val() == 'radio' || $('#formbuilder-step_2_field1_data_type').val() == 'checkbox' || $('#formbuilder-step_2_field1_data_type').val() == 'selectbox') {
				if (step_2_field1_lenth == 0) {
					min = 1;
					fields_count = 1;
					$("input[name='step_2_field1_field_boxes[]']").each(function() {
						$(".field-formbuilder-step_2_field1_field_value").find('.help-error').html('Please add one more field.');
						//setTimeout(function(){ $(".field-formbuilder-step_2_field1_field_value").find('.help-error').html(''); }, 3000);
					});
					
				}
			} else {
				$(".field-formbuilder-step_2_field1_field_value").find('.help-error').html('');
			}
		}
		/*Duplicate field values start*/
		var step2_field1_sorted_arr = step2_field1_unique_values_arr.slice().sort(); // You can define the comparing function here. 
		//console.log(sorted_arr);           // JS by default uses a crappy string compare.
		                                     // (we use slice to clone the array so the
		                                     // original array won't be modified)
		for (var i = 0; i < step2_field1_unique_values_arr.length - 1; i++) {
		    if (step2_field1_sorted_arr[i + 1] == step2_field1_sorted_arr[i]) {
		    	step2_field1_unique_values_arr_results.push(step2_field1_sorted_arr[i]);
		    }
		}
		if (step2_field1_unique_values_arr_results.length > 0) {
			unique_fields_count = 1;
			$(".field-formbuilder-step_2_field1_field_value").find('.help-error').html('Duplicate values are entered.');
		} else if (step2_field1_unique_values_arr_results.length == 0 && min == 0 ) {
			$(".field-formbuilder-step_2_field1_field_value").find('.help-error').html('');
		}
		/*Duplicate field values stop*/
	}
	if ($("input[name='FormBuilder[step_3_require]']:checked").val() == 1) {
		if ($("input[name='FormBuilder[step_3_field1_require]']:checked").val() == 1) {
			minimumSteps_count = minimumSteps_count + 1;
			step_3_field1_lenth = $("input[name='step_3_field1_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
			var step3_field1_value_unique = $('#formbuilder-step_3_field1_field_value').val();
			step3_field1_unique_values_arr.push(step3_field1_value_unique);
			if (step_3_field1_lenth > 0) {
				var skipNo = 0;
				$("input[name='step_3_field1_field_boxes[]']").each(function() {
					if (skipNo == 0) {
						skipNo = 1;
						return;
					}
				    var step_3_field1_value = $(this).val();
				    if (step_3_field1_value) {
				    	$(this).parents(':eq(2)').next().html('');
				    	step_3_field1_field_boxes_values.push(step_3_field1_value);
				    	step3_field1_unique_values_arr.push(step_3_field1_value);
				    } else {
				    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
				    }
				});
			} else {
				if ($('#formbuilder-step_3_field1_data_type').val() == 'radio' || $('#formbuilder-step_3_field1_data_type').val() == 'checkbox' || $('#formbuilder-step_3_field1_data_type').val() == 'selectbox') {
					if (step_3_field1_lenth == 0) {
						fields_count = 1;
						min = 1;
						$("input[name='step_3_field1_field_boxes[]']").each(function() {
							$(".field-formbuilder-step_3_field1_field_value").find('.help-error').html('Please add one more field.');
							//setTimeout(function(){ $(".field-formbuilder-step_3_field1_field_value").find('.help-error').html(''); }, 3000);
						});
					}
				} else {
					$(".field-formbuilder-step_3_field1_field_value").find('.help-error').html('');
				}
			}
			/*Duplicate field values start*/
			var step3_field1_sorted_arr = step3_field1_unique_values_arr.slice().sort(); // You can define the comparing function here. 
			//console.log(sorted_arr);           // JS by default uses a crappy string compare.
			                                     // (we use slice to clone the array so the
			                                     // original array won't be modified)
			for (var i = 0; i < step3_field1_unique_values_arr.length - 1; i++) {
			    if (step3_field1_sorted_arr[i + 1] == step3_field1_sorted_arr[i]) {
			    	step3_field1_unique_values_arr_results.push(step3_field1_sorted_arr[i]);
			    }
			}
			if (step3_field1_unique_values_arr_results.length > 0) {
				unique_fields_count = 1;
				$(".field-formbuilder-step_3_field1_field_value").find('.help-error').html('Duplicate values are entered.');
			} else if (step3_field1_unique_values_arr_results.length == 0 && min == 0) {
				$(".field-formbuilder-step_3_field1_field_value").find('.help-error').html('');
			}
			/*Duplicate field values stop*/
		}
		if ($("input[name='FormBuilder[step_3_field2_require]']:checked").val() == 1) {
			minimumSteps_count = minimumSteps_count + 1;
			step_3_field2_lenth = $("input[name='step_3_field2_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
			var step3_field2_value_unique = $('#formbuilder-step_3_field2_field_value').val();
			step3_field2_unique_values_arr.push(step3_field2_value_unique);
			if (step_3_field2_lenth > 0) {
				var skipNo = 0;
				$("input[name='step_3_field2_field_boxes[]']").each(function() {
					if (skipNo == 0) {
						skipNo = 1;
						return;
					}
				    var step_3_field2_value = $(this).val();
				    if (step_3_field2_value) {
				    	$(this).parents(':eq(2)').next().html('');
				    	step_3_field2_field_boxes_values.push(step_3_field2_value);
				    	step3_field2_unique_values_arr.push(step_3_field2_value);
				    } else {
				    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
				    }
				});
			} else {
				if ($('#formbuilder-step_3_field2_data_type').val() == 'radio' || $('#formbuilder-step_3_field2_data_type').val() == 'checkbox' || $('#formbuilder-step_3_field2_data_type').val() == 'selectbox') {
					if (step_3_field2_lenth == 0) {
						min = 1;
						fields_count = 1;
						$("input[name='step_3_field2_field_boxes[]']").each(function() {
							$(".field-formbuilder-step_3_field2_field_value").find('.help-error').html('Please add one more field.');
							//setTimeout(function(){ $(".field-formbuilder-step_3_field2_field_value").find('.help-error').html(''); }, 3000);
						});
					}
				} else {
					$(".field-formbuilder-step_3_field2_field_value").find('.help-error').html('');
				}
			}
			/*Duplicate field values start*/
			var step3_field2_sorted_arr = step3_field2_unique_values_arr.slice().sort(); // You can define the comparing function here. 
			//console.log(sorted_arr);           // JS by default uses a crappy string compare.
			                                     // (we use slice to clone the array so the
			                                     // original array won't be modified)
			for (var i = 0; i < step3_field2_unique_values_arr.length - 1; i++) {
			    if (step3_field2_sorted_arr[i + 1] == step3_field2_sorted_arr[i]) {
			    	step3_field2_unique_values_arr_results.push(step3_field2_sorted_arr[i]);
			    }
			}
			if (step3_field2_unique_values_arr_results.length > 0) {
				unique_fields_count = 1;
				$(".field-formbuilder-step_3_field2_field_value").find('.help-error').html('Duplicate values are entered.');
			} else if (step3_field2_unique_values_arr_results.length == 0 && min == 0) {
				$(".field-formbuilder-step_3_field2_field_value").find('.help-error').html('');
			}
			/*Duplicate field values stop*/
		}
		if ($("input[name='FormBuilder[step_3_field3_require]']:checked").val() == 1 && $('#formbuilder-activity').val() != 1) {
			minimumSteps_count = minimumSteps_count + 1;
			step_3_field3_lenth = $("input[name='step_3_field3_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
			var step3_field3_value_unique = $('#formbuilder-step_3_field3_field_value').val();
			step3_field3_unique_values_arr.push(step3_field3_value_unique);
			if (step_3_field3_lenth > 0) {
				var skipNo = 0;
				$("input[name='step_3_field3_field_boxes[]']").each(function() {
					if (skipNo == 0) {
						skipNo = 1;
						return;
					}
				    var step_3_field3_value = $(this).val();
				    if (step_3_field3_value) {
				    	$(this).parents(':eq(2)').next().html('');
				    	step_3_field3_field_boxes_values.push(step_3_field3_value);
				    	step3_field3_unique_values_arr.push(step_3_field3_value);
				    } else {
				    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
				    }
				});
			} else {
				if ($('#formbuilder-step_3_field3_data_type').val() == 'radio' || $('#formbuilder-step_3_field3_data_type').val() == 'checkbox' || $('#formbuilder-step_3_field3_data_type').val() == 'selectbox') {
					if (step_3_field3_lenth == 0) {
						fields_count = 1;
						min = 1;
						$("input[name='step_3_field3_field_boxes[]']").each(function() {
							$(".field-formbuilder-step_3_field3_field_value").find('.help-error').html('Please add one more field.');
							//setTimeout(function(){ $(".field-formbuilder-step_3_field3_field_value").find('.help-error').html(''); }, 3000);
						});
					}
				} else {
					$(".field-formbuilder-step_3_field3_field_value").find('.help-error').html('');
				}
			}
			/*Duplicate field values start*/
			var step3_field3_sorted_arr = step3_field3_unique_values_arr.slice().sort(); // You can define the comparing function here. 
			//console.log(sorted_arr);           // JS by default uses a crappy string compare.
			                                     // (we use slice to clone the array so the
			                                     // original array won't be modified)
			for (var i = 0; i < step3_field3_unique_values_arr.length - 1; i++) {
			    if (step3_field3_sorted_arr[i + 1] == step3_field3_sorted_arr[i]) {
			    	step3_field3_unique_values_arr_results.push(step3_field3_sorted_arr[i]);
			    }
			}
			if (step3_field3_unique_values_arr_results.length > 0) {
				unique_fields_count = 1;
				$(".field-formbuilder-step_3_field3_field_value").find('.help-error').html('Duplicate values are entered.');
			} else if (step3_field3_unique_values_arr_results.length == 0 && min == 0) {
				$(".field-formbuilder-step_3_field3_field_value").find('.help-error').html('');
			}
			/*Duplicate field values stop*/
		}
		if ($("input[name='FormBuilder[step_3_field4_require]']:checked").val() == 1 && ($('#formbuilder-activity').val() == 3 || $('#formbuilder-activity').val() == 4)) {
			minimumSteps_count = minimumSteps_count + 1;
			step_3_field4_lenth = $("input[name='step_3_field4_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
			var step3_field4_value_unique = $('#formbuilder-step_3_field4_field_value').val();
			step3_field4_unique_values_arr.push(step3_field4_value_unique);
			if (step_3_field4_lenth > 0) {
				var skipNo = 0;
				$("input[name='step_3_field4_field_boxes[]']").each(function() {
					if (skipNo == 0) {
						skipNo = 1;
						return;
					}
				    var step_3_field4_value = $(this).val();
				    if (step_3_field4_value) {
				    	$(this).parents(':eq(2)').next().html('');
				    	step_3_field4_field_boxes_values.push(step_3_field4_value);
				    	step3_field4_unique_values_arr.push(step_3_field4_value);
				    } else {
				    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
				    }
				});
			} else {
				if ($('#formbuilder-step_3_field4_data_type').val() == 'radio' || $('#formbuilder-step_3_field4_data_type').val() == 'checkbox' || $('#formbuilder-step_3_field4_data_type').val() == 'selectbox') {
					if (step_3_field4_lenth == 0) {
						fields_count = 1;
						min = 1;
						$("input[name='step_3_field4_field_boxes[]']").each(function() {
							$(".field-formbuilder-step_3_field4_field_value").find('.help-error').html('Please add one more field.');
							//setTimeout(function(){ $(".field-formbuilder-step_3_field4_field_value").find('.help-error').html(''); }, 3000);
						});
					}
				} else {
					$(".field-formbuilder-step_3_field4_field_value").find('.help-error').html('');
				}
			}
			/*Duplicate field values start*/
			var step3_field4_sorted_arr = step3_field4_unique_values_arr.slice().sort(); // You can define the comparing function here. 
			//console.log(sorted_arr);           // JS by default uses a crappy string compare.
			                                     // (we use slice to clone the array so the
			                                     // original array won't be modified)
			for (var i = 0; i < step3_field4_unique_values_arr.length - 1; i++) {
			    if (step3_field4_sorted_arr[i + 1] == step3_field4_sorted_arr[i]) {
			    	step3_field4_unique_values_arr_results.push(step3_field4_sorted_arr[i]);
			    }
			}
			if (step3_field4_unique_values_arr_results.length > 0) {
				unique_fields_count = 1;
				$(".field-formbuilder-step_3_field4_field_value").find('.help-error').html('Duplicate values are entered.');
			} else if (step3_field4_unique_values_arr_results.length == 0 && min == 0) {
				$(".field-formbuilder-step_3_field4_field_value").find('.help-error').html('');
			}
			/*Duplicate field values stop*/
		}
		if ($("input[name='FormBuilder[step_3_field5_require]']:checked").val() == 1 && $('#formbuilder-activity').val() == 4) {
			minimumSteps_count = minimumSteps_count + 1;
			step_3_field5_lenth = $("input[name='step_3_field5_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
			var step3_field5_value_unique = $('#formbuilder-step_3_field5_field_value').val();
			step3_field5_unique_values_arr.push(step3_field5_value_unique);
			if (step_3_field5_lenth > 0) {
				var skipNo = 0;
				$("input[name='step_3_field5_field_boxes[]']").each(function() {
					if (skipNo == 0) {
						skipNo = 1;
						return;
					}
				    var step_3_field5_value = $(this).val();
				    if (step_3_field5_value) {
				    	$(this).parents(':eq(2)').next().html('');
				    	step_3_field5_field_boxes_values.push(step_3_field5_value);
				    	step3_field5_unique_values_arr.push(step_3_field5_value);
				    } else {
				    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
				    }
				});
			} else {
				if ($('#formbuilder-step_3_field5_data_type').val() == 'radio' || $('#formbuilder-step_3_field5_data_type').val() == 'checkbox' || $('#formbuilder-step_3_field5_data_type').val() == 'selectbox') {
					if (step_3_field5_lenth == 0) {
						fields_count = 1;
						min = 1;
						$("input[name='step_3_field5_field_boxes[]']").each(function() {
							$(".field-formbuilder-step_3_field5_field_value").find('.help-error').html('Please add one more field.');
							//setTimeout(function(){ $(".field-formbuilder-step_3_field5_field_value").find('.help-error').html(''); }, 3000);
						});
					}
				} else {
					$(".field-formbuilder-step_3_field5_field_value").find('.help-error').html('');
				}
			}
			/*Duplicate field values start*/
			var step3_field5_sorted_arr = step3_field5_unique_values_arr.slice().sort(); // You can define the comparing function here. 
			//console.log(sorted_arr);           // JS by default uses a crappy string compare.
			                                     // (we use slice to clone the array so the
			                                     // original array won't be modified)
			for (var i = 0; i < step3_field5_unique_values_arr.length - 1; i++) {
			    if (step3_field5_sorted_arr[i + 1] == step3_field5_sorted_arr[i]) {
			    	step3_field5_unique_values_arr_results.push(step3_field5_sorted_arr[i]);
			    }
			}
			if (step3_field5_unique_values_arr_results.length > 0) {
				unique_fields_count = 1;
				$(".field-formbuilder-step_3_field5_field_value").find('.help-error').html('Duplicate values are entered.');
			} else if (step3_field5_unique_values_arr_results.length == 0 && min == 0){
				$(".field-formbuilder-step_3_field5_field_value").find('.help-error').html('');
			}
			/*Duplicate field values stop*/
		}
		/* minimum fields required for step 3 */
		minimum_steps = minimumSteps();
		/* minimum fields required for step 3 */
	}
	if ($("input[name='FormBuilder[step_4_field1_require]']:checked").val() == 1) {
		step_4_field1_lenth = $("input[name='step_4_field1_field_boxes[]']").length - 1;//-1 is used to remove addmore button field count from list
		var step4_field1_value_unique = $('#formbuilder-step_4_field1_field_value').val();
		step4_field1_unique_values_arr.push(step4_field1_value_unique);
		if (step_4_field1_lenth > 0) {
			var skipNo = 0;
			$("input[name='step_4_field1_field_boxes[]']").each(function() {
				if (skipNo == 0) {
					skipNo = 1;
					return;
				}
			    var step_4_field1_value = $(this).val();
			    if (step_4_field1_value) {
			    	$(this).parents(':eq(2)').next().html('');
			    	step_4_field1_field_boxes_values.push(step_4_field1_value);
			    	step4_field1_unique_values_arr.push(step_4_field1_value);
			    } else {
			    	$(this).parents(':eq(2)').next().html('Cannot be blank.');
			    }
			});
		} else {
			if ($('#formbuilder-step_4_field1_data_type').val() == 'radio' || $('#formbuilder-step_4_field1_data_type').val() == 'checkbox' || $('#formbuilder-step_4_field1_data_type').val() == 'selectbox') {
				if (step_4_field1_lenth == 0) {
					fields_count = 1;
					min = 1;
					$("input[name='step_4_field1_field_boxes[]']").each(function() {
						$(".field-formbuilder-step_4_field1_field_value").find('.help-error').html('Please add one more field.');
						//setTimeout(function(){ $(".field-formbuilder-step_4_field1_field_value").find('.help-error').html(''); }, 3000);
					});
				}
			} else {
				$(".field-formbuilder-step_4_field1_field_value").find('.help-error').html('');
			}
		}
		/*Duplicate field values start*/
		var step4_field1_sorted_arr = step4_field1_unique_values_arr.slice().sort(); // You can define the comparing function here. 
		//console.log(sorted_arr);           // JS by default uses a crappy string compare.
		                                     // (we use slice to clone the array so the
		                                     // original array won't be modified)
		for (var i = 0; i < step4_field1_unique_values_arr.length - 1; i++) {
		    if (step4_field1_sorted_arr[i + 1] == step4_field1_sorted_arr[i]) {
		    	step4_field1_unique_values_arr_results.push(step4_field1_sorted_arr[i]);
		    }
		}
		if (step4_field1_unique_values_arr_results.length > 0) {
			unique_fields_count = 1;
			$(".field-formbuilder-step_4_field1_field_value").find('.help-error').html('Duplicate values are entered.');
		} else if (step4_field1_unique_values_arr_results.length == 0 && min == 0) {
			$(".field-formbuilder-step_4_field1_field_value").find('.help-error').html('');
		}
		/*Duplicate field values stop*/
	}
	//if any errors are there collapse menu has to open start
	accordionOpen();
	//if any errors are there collapse menu has to open end
	
	if (step_2_field1_field_boxes_values.length != step_2_field1_lenth || step_3_field1_field_boxes_values.length != step_3_field1_lenth || step_3_field2_field_boxes_values.length != step_3_field2_lenth || step_3_field3_field_boxes_values.length != step_3_field3_lenth || step_3_field4_field_boxes_values.length != step_3_field4_lenth || step_3_field5_field_boxes_values.length != step_3_field5_lenth || step_4_field1_field_boxes_values.length != step_4_field1_lenth || fields_count == 1 || minimum_steps === false || unique_fields_count == 1) {
		return false;
    } else {
    	return true;
    }
	
});	

// dynamic form save button click end
//if any errors are there collapse menu has to open start
function accordionOpen() {
    $('.accordion').each(function(){
		if ($(this).find('.help-block').text() != '' || $(this).find('.help-error').text() != '') {
			if ($(this).hasClass('in')) {
				return false;
			} else {
				$(this).prev().find('a').click();
				return false;
			}
		} else {
			return true;
		}
    });
}
//if any errors are there collapse menu has to open end
//minimum fields required for step 3 start
$('#dynamic-form-save').on('submit', function(){
	setTimeout(function(){ accordionOpen(); }, 500);
});
function minimumSteps() {
	var result = true;
    if ($('#formbuilder-activity').val() == 1) {
    	if (minimumSteps_count <= 1 ) {
    		$('#formbuilder-step_3_require').parent('div').next('div .help-block').html('Two fields are mandatory for step 3');
    		result = false;
    	}
    }
    else if ($('#formbuilder-activity').val() == 2) {
    	if (minimumSteps_count <= 1) {
    		$('#formbuilder-step_3_require').parent('div').next('div .help-block').html('Two fields are mandatory for step 3');
    		result = false;
    	}
    }
    else if ($('#formbuilder-activity').val() == 3) {
    	if (minimumSteps_count < 2) {
    		$('#formbuilder-step_3_require').parent('div').next('div .help-block').html('Two fields are mandatory for step 3');
    		result = false;
    	}
    }
    else if ($('#formbuilder-activity').val() == 4) {
    	if (minimumSteps_count < 2) {
    		$('#formbuilder-step_3_require').parent('div').next('div .help-block').html('Two fields are mandatory for step 3');
    		result = false;
    	}
    } else {
    	$('#formbuilder-step_3_require').parent('div').next('div .help-block').html('');
    	result = true;
    }
    return result;
}
//minimum fields required for step 3 end
});
