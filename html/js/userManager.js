var search = '';
var type = [2, 3, 4];
$(document).ready(function(){
    $('.list-user-popup').on('click', '.ios-ui-select', function(){
        var user_id = $(this).parent().prev().attr('data-user-id');
        if ($(this).hasClass("checked")) {
            updateUserStatus(user_id, 1);
        } else {
            updateUserStatus(user_id, 0);
        }
    });

    $( "#tabs" ).tabs({
        activate: function( event, ui ) {
            var id = ui.newPanel[0].id;
            var tabs  = id.split("-")[1]
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

    $('#add-user').click(function() {
        $.colorbox.close();
        setTimeout(function(){
            $(".popupInvitationUser").colorbox({
                inline:true,
                opacity: 0.7,
                open: true,
                maxWidth: 400,
                height: 200,
                overlayClose: false,
                onLoad: function() {
                    $('#cboxWrapper').addClass('userManagerWrap');
                },
                onOpen: function() {
                },
                onComplete : function() { 
                    // $(this).colorbox.resize(); 
                }  
            });
        }, 400)
    });

    $('#btn-invite').click(function(){
        var tabs = $( "#tabs" ).tabs( "option", "active" );
        var email = $('#user_invitation .input-user').val();
        console.log(tabs);
        $.colorbox.close();
        switch (tabs) {
            case 0 :
                addUser(email, type[0]);
                break;
            case 1 :
                addUser(email, type[1]);
                break;
            case 2 :
                addUser(email, type[2]);
                break;
        }
    });
});

function loadPopupUser() {
            $(".ios-checkbox").iosCheckbox();         
            $(".popupAddUser").colorbox({
                inline:true,
                opacity: 0.7,
                open: true,
                maxWidth: 700,
                maxHeight: '95%',
                overlayClose: false,
                onLoad: function() {
                    $('#cboxWrapper').addClass('userManagerWrap');
                },
                onOpen: function() {
                    $('#tabs').tabs({ active: 0 });
                    getListUser(2, search, 1);
                    // setTimeout(function(){
                    //     var wrapHeight = $('#add_user_content .popup-content').height();
                    //     var tabActive =  $("#tabs").tabs( "option", "active" );
                    //     $('#add_user_content .ui-tabs .ui-tabs-panel').css('max-height', wrapHeight);
                    //     $("#tabs-" + (tabActive + 1)).niceScroll();
                    //     $("#tabs-" + (tabActive + 2)).niceScroll();
                    //     $("#tabs-" + (tabActive + 3)).niceScroll();                                                
                    // }, 500)
                    
                },
                onComplete : function() { 
                    // $(this).colorbox.resize(); 
                }  
            });
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
            $("#tabs-" + tabs).css('max-height', '60vh');
            $("#tabs-" + tabs).niceScroll();
            $(".popupAddUser").colorbox.resize(); 
            $("#tabs-" + tabs).getNiceScroll().resize()
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

function addUser(email, type) {
    $('body').addClass("loading");
    $.ajax({
        url: baseUrl + '/user/business/add-user',
        type: 'POST',
        data: {
            biz_id: idBusiness,
            email: email,
            type: type,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            $('#user_invitation .input-user').val('');
            $('body').removeClass("loading");
        },
        error: function(data) {
            $('#user_invitation .input-user').val('');
            $('body').removeClass("loading");
        }
    });
}
