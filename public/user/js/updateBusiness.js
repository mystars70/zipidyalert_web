$(document).ready(function(){
    $('#btn-update-business').click(function(){
        if ($("#business-update").validationEngine('validate')) {
            $('#businessId').val(idBusiness);
            var registerForm = $(".form-register");
            var formData = registerForm.serialize();
            $.ajax({
                url: baseUrl + '/user/business-update',
                type: 'POST',
                data: new FormData($(".form-register")[0]),
                contentType:false,
                cache: false,
                processData:false,
                success: function(data) {
                    console.log(data);
                    if (data.code == 200) {
                        $('.message-notice').show();
                        setTimeout(function(){
                            $('.message-notice').hide();
                        }, 2000);
                        if (data.img) {
                            $('.biz_'+data.biz_id+' img').attr('src', data.img);
                        }
                    } else {
                        $.confirm({
                            title: 'Encountered an error!',
                            content: data.message,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                close: function () {
                                }
                            }
                        });
                    }
                },
                error: function(data) {
                    
                }
            });
        }
    });

    $('#business-update').submit(function(e){
        e.preventDefault();
    });

    $('.upload-file-box').click(function(){
        $('.business-avatar').trigger('click');
    });

    $('.business-avatar').change(function (){
        var file = $(this).val();
        $('#fileName').val(file);
        readURL(this);
    });

    $('#business-update').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'topRight',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: true,
        focusInvalid: false,
        validateNonVisibleFields: true,
    });

    $('#business-update .btn-form-cancel').click(function(){
        location.href = baseUrl + '/user/business/page/' + idBusiness;
    });

});

function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.logo-box img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

function checkImage(field, rules, i, options) {
    var file  = $(field).val();
    var validExtensions = ['jpeg', 'jpg', 'png', 'bmp']; //array of valid extensions
    var fileNameExt = file.substr(file.lastIndexOf('.') + 1);
    if (file != '') {
        if ($.inArray(fileNameExt.toLowerCase(), validExtensions) == -1){
           return "* Please select image";
        }
    }
}

function checkNumber(field, rules, i, options) {
    var radius  = $(field).val();

    if (radius == '') {
        rules.push('required');
    }else {
        if (!$.isNumeric(radius)) {
            return 'Input must be number';
        }
        if (radius < 0) {
            return 'Input must be more than or equal 0';
        }
    }
}