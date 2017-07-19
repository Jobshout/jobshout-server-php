/* [ ---- Gebo Admin Panel - validation ---- ] */

	$(document).ready(function() {
		//* validation with tooltips
		$.validator.setDefaults({
        ignore: ""
    });
		var val_flag=0;
		//* regular validation
		gebo_validation.reg();
	});
	
	//* validation
	gebo_validation = {
		
        reg: function() {
            reg_validator = $('.form_validation_reg').validate({
			
				onkeyup: false,
				errorClass: 'error',
				validClass: 'valid',
				highlight: function(element) {
					var err_div_id=$(element).closest('div.tab-pane').attr('id');
								if($("#"+err_div_id).hasClass("active")){
								$(element).closest('div').addClass("f_error");
								val_flag=1;
								}
								
					else if(!$("#"+err_div_id).hasClass("active") && val_flag==0){
					$(element).closest('div').addClass("f_error");
					for(var i=1; i<=4; i++) {
						if(err_div_id=="tab"+i){
							$("#tab"+i).addClass("active");
							$("#li"+i).addClass("active");

						}
						else {
							$("#tab"+i).removeClass("active");
							$("#li"+i).removeClass("active");
						}
					}
					}
						


				},
				unhighlight: function(element) {
					$(element).closest('div').removeClass("f_error");
					val_flag=0;
				},
                errorPlacement: function(error, element) {
                    $(element).closest('div').append(error);
                },
                rules: {
					site_id: { required: true },
					PageTitle: { required: true },
					Body: { required: true, minlength: 10 },
					UserID: { required: true, minlength: 1 },
					Published_timestamp: { required: true },
					Code: { required: true},
									
					Type: { required: true }
					
				},
                invalidHandler: function(form, validator) {
					$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
				}
            })
        }
	};