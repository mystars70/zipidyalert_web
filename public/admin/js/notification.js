var flag = true;
var tTable;
$(document).ready(function(){
    $('.action-edit').click(function(){
        if (checkselect() == 1) {
            window.location.href = baseUrl + "/admin/notification/edit/" + $('.ckb-select:checked').attr('data-id');
        }
    });
    $('.action-delete').click(function(){
        if (checkselect() > 0) {
            if (flag) {
                flag = false;
                var registerForm = $("#notification-delete");
                var formData = registerForm.serialize();
                $.ajax({
                    url: baseUrl + '/admin/notification/delete',
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        flag = true;
                        if (data.code == 200) {
                            tTable.ajax.reload();
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
                        flag = true;
                    }
                });
            }
        }
    });
    $('.btn-update').click(function(){
        if (flag) {
            flag = false;
            var registerForm = $("#notification-update");
            var formData = registerForm.serialize();
            $.ajax({
                url: baseUrl + '/admin/notification/update',
                type: 'POST',
                data: formData,
                success: function(data) {
                    flag = true;
                    if (data.code == 200) {
                        $.confirm({
                            title: 'Update success!',
                            content: '',
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                close: function () {
                                }
                            }
                        });
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
                    flag = true;
                }
            });
        }
    });
    $('.btn-create').click(function(){
        if (flag) {
            flag = false;
            var registerForm = $("#notification-update");
            var formData = registerForm.serialize();
            $.ajax({
                url: baseUrl + '/admin/notification/create',
                type: 'POST',
                data: formData,
                success: function(data) {
                    flag = true;
                    if (data.code == 200) {
                        $('.back-link').click();
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
                    flag = true;
                }
            });
        }
    });
});
function checkselect() {
    return $('.ckb-select:checked').length;
}
function getListNotification() {
    // get message user business list
    var url = baseUrl + "/admin/notification/ajax-list-notification";
    var numId = 0;
    //console.log(url);
    tTable = $('#notificationList').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        deferLoading: 60,
        searching: false,
        columns: [
            { 
                data: 'category',
                render: function ( data, type, fullData, meta ) {
                    var html = '<div class="ckb-input"><input name="select[' + fullData.id + ']" type="checkbox" class="ckb-select" data-id="' + fullData.id + '"/></div>'
                    return html;
                }
            },
            { 
                data: 'name',
                render: function ( data, type, fullData, meta ) {
                    return fullData.name;
                }
            },
            {
                data: 'description',
                render: function ( data, type, fullData, meta ) {
                    return fullData.description;
                }
            },
            {
                data: 'created_at',
                render: function ( data, type, fullData, meta ) {
                    return fullData.created_at;
                }
            }
        ],
        rowId: 'staffId',
        columnDefs: [ 
            { orderable: false, targets: [0]},
        ],
        order: [],

    });
    tTable.on( 'order.dt search.dt', function () {
        tTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            numId = i+1;
            $(cell).find('a').text(numId);
        } );
    } ).draw();
}