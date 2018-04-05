$(document).ready(function(){
    $('#btn-update-profile').click(function(){
        if ($("#profile-update").validationEngine('validate')) {
            var registerForm = $(".form-register");
            var formData = registerForm.serialize();
            $.ajax({
                url: baseUrl + '/user/profile-update',
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
                            $('.header-avatar-wrap img').attr('src', data.img);
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

    $('.upload-file-box').click(function(){
        $('.profile-avatar').trigger('click');
    });

    $('.profile-avatar').change(function (){
        var file = $(this).val();
        $('#fileName').val(file);
        readURL(this);
    });

    $('#profile-update').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'bottomLeft',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: true,
        focusInvalid: false,
        validateNonVisibleFields: true,
    });

    $('#profile-update .btn-form-cancel').click(function(){
        location.href = baseUrl + '/user';
    });

});

function checkUser(field, rules, i, options) {
    var userName  = $(field).val();
    var token = $('#profile-update [name="_token"]').val();
    if (userName == '') {
        rules.push('required'); 
    }else {
        var isEmail = validateEmail(userName);
        if (!isEmail) {
            return "* Please input email address or phone number";
        } else {
            var dataAjax = [];
            $.ajax({
                url: baseUrl + '/user/check-user-acc',
                type: 'POST',
                async: false,
                data: {email: userName, _method: 'POST', _token: token},
                success: function(data) {
                    dataAjax = data;
                }
            });
            
            if (dataAjax.code == 300) {
                return dataAjax.message;
            }
        }
    }
}