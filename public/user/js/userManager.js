var search = '';
var type = [2, 3, 4];
(function($) {
    $.fn.extend({
        iosCheckboxCustom: function() {
            this.destroy = function(){
                $(this).each(function() {
                    $(this).next('.ios-ui-select').remove();
                });
            };
            if ($(this).attr('data-ios-checkbox') === 'true') {
                return;
            }
            $(this).attr('data-ios-checkbox', 'true');
            // console.log($(this).attr('data-notification'));
            $(this).each(function() {
                /**
                 * Original checkbox element
                 */
                var org_checkbox = $(this);
                /**
                 * iOS checkbox div
                 */
                
                
                // If the original checkbox is checked, add checked class to the ios checkbox.
                if ($(this).attr('data-notification') === 'true') {
                    var ios_checkbox = jQuery("<div>", {
                        class: 'ios-ui-select ios-ui-select-notification'
                    }).append(jQuery("<div>", {
                        class: 'inner'
                    }));
                    var labelCheckbox = 'Not yet approve';
                } else {
                    var ios_checkbox = jQuery("<div>", {
                        class: 'ios-ui-select checkbox-owner'
                    }).append(jQuery("<div>", {
                        class: 'inner'
                    }));
                    var labelCheckbox = 'Active';
                }
                
                var labelClass = '';
                if (org_checkbox.is(":checked")) {
                    ios_checkbox.addClass("checked");
                    labelCheckbox = 'Active'
                    labelClass = 'active';
                }
                // Hide the original checkbox and print the new one.
                org_checkbox.hide().after(ios_checkbox);
                
                var wrapHtml = $(ios_checkbox).wrap('<div class="ios-wrap"></div>');
                
                var labelHtml = $(wrapHtml).parent().prepend(jQuery("<span>", {
                    class: 'checkbox-label ' + labelClass,
                    html: labelCheckbox
                }))
            });
            //$('.ios-ui-select').wrap("<div class='ios-wrap'><span class='checkbox-label'></span></div>")
            return this;
        }
    });
})(jQuery);
$(window).resize(function(){
    $(".popupAddUser").colorbox.resize(); 
});
$(document).ready(function(){
    $('.wrap-user-manager').hide();
    $('.wrap-content').show();
    $('.list-user-popup').on('click', '.ios-ui-select', function(){
        if (!$(this).hasClass("ios-ui-select-notification") && !$(this).hasClass("checkbox-owner")) {
            var user_id = $(this).parent().prev().attr('data-user-id');
            if ($(this).hasClass("checked")) {
                updateUserStatus(user_id, 1);
            } else {
                updateUserStatus(user_id, 0);
            }
        }
    });

    $( "#tabs" ).tabs({

        activate: function( event, ui ) {
            var id = ui.newPanel[0].id;
            var tabs = id.split("-")[1];
            clearSearch();
            switch (tabs) {
                case '1' :
                    getListUser(type[0], search, tabs);
                    break;
                case '2' :
                    getListUser(type[1], search, tabs);
                    break;
                case '3' :
                    getListUser(type[2], search, tabs);
                    break;
            }
            // $.colorbox.resize();                                       
        }
    });

    $('.searchUserPlace').keypress(function(e){
        var code = e.keyCode || e.which;
        if (code == 13) {
            search = $(this).val();
            var tabs = $( "#tabs" ).tabs( "option", "active" );
            switch (tabs) {
                case 0 :
                    getListUser(type[0], search, tabs + 1);
                    break;
                case 1 :
                    getListUser(type[1], search, tabs + 1);
                    break;
                case 2 :
                    getListUser(type[2], search, tabs + 1);
                    break;
            }
        }
    });

    $('#user_invitation .input-user').keypress(function(e){
        var code = e.keyCode || e.which;
        if (code == 13) {
            $('#btn-invite').click();
        }
    });

    $('#user_invitation').on('shown.bs.modal', function (e) {
        $('body').removeClass('modal-open');
        var tabs = $( "#tabs" ).tabs( "option", "active" );
        var name = $('#ui-id-' + (tabs + 1)).text();
        $('#user_invitation input[name="type"]').val(type[tabs]);
        $('#user_invitation input[name="biz_id"]').val(idBusiness);
        $('#myModalLabel').text('Add ' + name);
        $('#user_invitation .block-search').css('max-height', '60vh');
        $('#user_invitation .block-search').niceScroll();
        $('#user_invitation .block-search').getNiceScroll().resize();
    });

    $('#user_invitation').on('hidden.bs.modal', function (e) {
        var tabs = $( "#tabs" ).tabs( "option", "active" );
        $('.email-input-list').empty();
        loadPopupUser(tabs);
    });

    $('#share_place').on('shown.bs.modal', function (e) {
        $('body').removeClass('modal-open');
        var tabs = $( "#tabs" ).tabs( "option", "active" );
        var name = $('#ui-id-' + (tabs + 1)).text();
        $('#share_place input[name="type"]').val(type[tabs]);
        $('#share_place input[name="biz_id"]').val(idBusiness);
        $('#share_place .block-search').css('max-height', '60vh');
        $('#share_place .block-search').niceScroll();
        $('#share_place .block-search').getNiceScroll().resize();
    });

    $('#share_place').on('hidden.bs.modal', function (e) {
        var tabs = $( "#tabs" ).tabs( "option", "active" );
        $('.email-input-list').empty();
    });

    $('#invite_place').on('shown.bs.modal', function (e) {
        $('body').removeClass('modal-open');
        // var tabs = $( "#tabs" ).tabs( "option", "active" );
        // var name = $('#ui-id-' + (tabs + 1)).text();
        // $('#share_place input[name="type"]').val(type[tabs]);
        // $('#share_place input[name="biz_id"]').val(idBusiness);
        $('#invite_place .block-search').css('max-height', '60vh');
        $('#invite_place .block-search').niceScroll();
        $('#invite_place .block-search').getNiceScroll().resize();
    });

    $('#invite_place').on('hidden.bs.modal', function (e) {
        var tabs = $( "#tabs" ).tabs( "option", "active" );
        $('.email-input-list').empty();
    });

    $('#add-user').click(function() {
        $.colorbox.close();
        setTimeout(function(){
            $('#myModalLabel').show();
            $('#sharePlace').hide();
            $('#user_invitation').modal('show');
        },200);
    });

    $('#add-user').click(function() {
        $.colorbox.close();
        setTimeout(function(){
            $('#myModalLabel').show();
            $('#sharePlace').hide();
            $('#user_invitation').modal('show');
        },200);
    });

    $('#btn-invite').click(function(){
        if ($("#addForm").validationEngine('validate')) {
            addUser();
        }
    });
    $('#btn-share').click(function(){
        if ($("#shareForm").validationEngine('validate')) {
            sharePlaces();
        }
    });

    $('#btn-invite_place').click(function(){
        if ($("#inviteForm").validationEngine('validate')) {
            invitePlaces();
        }
    });

    $("#addForm").submit(function(e){
        e.preventDefault();
    });

    $('#addForm').validationEngine({
        autoHidePrompt:false,
        promptPosition: 'bottomRight',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: false,
        focusInvalid: false
    });

    $("#shareForm").submit(function(e){
        e.preventDefault();
    });

    $('#shareForm').validationEngine({
        autoHidePrompt:false,
        promptPosition: 'bottomRight',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: false,
        focusInvalid: false
    });

    $("#inviteForm").submit(function(e){
        e.preventDefault();
    });

    $('#inviteForm').validationEngine({
        autoHidePrompt:false,
        promptPosition: 'bottomRight',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: false,
        focusInvalid: false
    });

    $('.add-email-input').click(function(){
        var html = '<div class="form-group"><input name="email[]" type="text" label="Email" class="input-user validate[custom[email]] placeholder-input "></div>';
        $('#user_invitation .email-input-list').append(html);
        $("#user_invitation .email-input-list input").last().polymerForm();
        $('#share_place .email-input-list').append(html);
        $("#share_place .email-input-list input").last().polymerForm();
        $('#user_invitation .block-search').getNiceScroll().resize();
        $('#share_place .block-search').getNiceScroll().resize();
        $('#invite_place .email-input-list').append(html);
        $("#invite_place .email-input-list input").last().polymerForm();
        $('#invite_place .block-search').getNiceScroll().resize();
    });

    $('.cboxClose').click(function(){
        $('.wrap-user-manager').hide();
        $('.wrap-content').show();
    });

    $('.deactive-status').click(function(){
        if ($('.ckb-select:checked').length > 0) {
            changeStatus(0);
        }
    });

    $('.active-status').click(function(){
        if ($('.ckb-select:checked').length > 0) {
            changeStatus(1);
        }
    });
});

function changeStatus(status) {
    $('.form-status input[name="status"]').val(status);
    $.ajax({
        url: baseUrl + '/user/business/status-user',
        type: 'POST',
        data: new FormData($(".form-status")[0]),
        contentType:false,
        cache: false,
        processData:false,
        success: function(data) {
            var tabs = $( "#tabs" ).tabs( "option", "active" );
            switch (tabs) {
                case 0 :
                    $('#tabs').tabs({ active: 0 });
                    getListUser(type[0], search, tabs + 1);
                    break;
                case 1 :
                    $('#tabs').tabs({ active: 1 });
                    getListUser(type[1], search, tabs + 1);
                    break;
                case 2 :
                    $('#tabs').tabs({ active: 2 });
                    getListUser(type[2], search, tabs + 1);
                    break;
            }
        },
        error: function(data) {
        }
    });
}
function loadPopupUser(tabs) {
            $(".ios-checkbox").iosCheckbox();
            $(".ios-checkbox-notification").iosCheckboxCustom();
            $('.wrap-user-manager').show();
            $('.wrap-content').hide();
            switch (tabs) {
                case 0 :
                    $('#tabs').tabs({ active: 0 });
                    getListUser(type[0], search, tabs + 1);
                    break;
                case 1 :
                    $('#tabs').tabs({ active: 1 });
                    getListUser(type[1], search, tabs + 1);
                    break;
                case 2 :
                    $('#tabs').tabs({ active: 2 });
                    getListUser(type[2], search, tabs + 1);
                    break;
            }
            // $(".popupAddUser").colorbox({
            //     inline:true,
            //     opacity: 0.7,
            //     open: true,
            //     maxWidth: 700,
            //     maxHeight: '95%',
            //     overlayClose: false,
            //     onLoad: function() {
            //         $('#cboxWrapper').addClass('userManagerWrap');
            //     },
            //     onOpen: function() {
            //         // $('#tabs').tabs({ active: 0 });
            //         // getListUser(2, search, tabs + 1);
            //         switch (tabs) {
            //             case 0 :
            //                 $('#tabs').tabs({ active: 0 });
            //                 getListUser(type[0], search, tabs + 1);
            //                 break;
            //             case 1 :
            //                 $('#tabs').tabs({ active: 1 });
            //                 getListUser(type[1], search, tabs + 1);
            //                 break;
            //             case 2 :
            //                 $('#tabs').tabs({ active: 2 });
            //                 getListUser(type[2], search, tabs + 1);
            //                 break;
            //         }
            //     },
            //     onComplete : function() { 
            //         // $(this).colorbox.resize(); 
            //     }  
            // });
        }

function getListUser(type, search, tabs) {
    $('.list-user-popup').empty();
    $.ajax({
        url: baseUrl + '/user/business/get-list-user',
        type: 'POST',
        data: {
            biz_id: idBusiness,
            type: type,
            search: search,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            $('#tabs-' + tabs + ' .list-user-popup').html(data);
            $(".ios-checkbox").iosCheckbox();
            $(".ios-checkbox-notification").iosCheckboxCustom();   
            $("#tabs-" + tabs).css('max-height', '80vh');
            $("#tabs-" + tabs).niceScroll();
            $(".popupAddUser").colorbox.resize(); 
            $("#tabs-" + tabs).getNiceScroll().resize();
        },
        error: function(data) {
        }
    });
}

function updateUserStatus(user_id, status) {
	$.ajax({
        url: baseUrl + '/user/business/update-user',
        type: 'POST',
        data: {
        	user_id: user_id,
        	biz_id: idBusiness,
        	status: status,
        	_token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
        },
        error: function(data) {
        }
    });
}

function clearSearch() {
    $('.searchUserPlace').val('');
    search = '';
}

function addUser() {
    $('body').addClass("loading");
    var registerForm = $("#addForm");
    var formData = registerForm.serialize();
    $.ajax({
        url: baseUrl + '/user/business/add-user',
        type: 'POST',
        data: formData,
        success: function(data) {
            if (data.code == 400) {
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
            } else {
                $('#user_invitation').modal('hide');
                $('#user_invitation .input-user').val('');
            }
            $('body').removeClass("loading");
        },
        error: function(data) {
            $('body').removeClass("loading");
        }
    });
}

function sharePlaces() {
    $('body').addClass("loading");
    var registerForm = $("#shareForm");
    var formData = registerForm.serialize();
    $.ajax({
        url: baseUrl + '/user/business/add-user',
        type: 'POST',
        data: formData,
        success: function(data) {
            if (data.code == 400) {
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
            } else {
                $('#share_place').modal('hide');
                $('#share_place .input-user').val('');
            }
            $('body').removeClass("loading");
        },
        error: function(data) {
            $('body').removeClass("loading");
        }
    });
}

function invitePlaces() {
    $('body').addClass("loading");
    var registerForm = $("#inviteForm");
    var formData = registerForm.serialize();
    console.log(formData);
    $.ajax({
        url: baseUrl + '/user/business/add-invite',
        type: 'POST',
        data: formData,
        success: function(data) {
            if (data.code == 400) {
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
            } else {
                $('#invite_place').modal('hide');
                $('#invite_place .input-user').val('');
                $.confirm({
                    title: 'Thank you!',
                    content: data.message,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        close: function () {
                        }
                    }
                });
            }
            $('body').removeClass("loading");
        },
        error: function(data) {
            $('body').removeClass("loading");
        }
    });
}

function addUserIndirect() {
    $('#tabs').tabs({ active: 2 });
    // $('#myModalLabel').hide();
    // $('#sharePlace').show();
    $('#share_place').modal('show');
}

function registerIndirect() {
    $.confirm({
        title: 'Register Indirect!!',
        content: 'Are you sure?',
        type: 'blue',
        typeAnimated: true,
        buttons: {
            heyThere: {
                text: 'YES, SURE!', // text for button
                btnClass: 'btn-orange', // class for the button
                keys: ['enter', 'a'], // keyboard event for button
                isHidden: false, // initially not hidden
                isDisabled: false, // initially not disabled
                action: function(heyThereButton){
                    $.ajax({
                        url: baseUrl + '/user/business/add-indirect',
                        type: 'POST',
                        data: {
                            biz_id: idBusiness,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data.code == 200) {
                                $.confirm({
                                    icon: 'fa fa-success',
                                    title: 'Congratulations!',
                                    content: 'Registration Success!',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        'Lest\'s start': function () {
                                            location.reload();
                                        }
                                    }
                                });
                            }
                        },
                        error: function(data) {
                        }
                    });
                }
            },
            'NO, THANKS!': function () {
            }
        }
    });
}

function addInvite() {
    $('#invite_place').modal('show');
}
