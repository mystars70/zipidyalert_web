var flag = true;
$(document).ready(function(){
    $('.btn-send').click(function(){
        if (flag) {
                flag = false;
                var registerForm = $("#support-send");
                var formData = registerForm.serialize();
                $.ajax({
                    url: baseUrl + '/admin/support/send',
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
                                        $('.back-link').click();
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
});
function getListSupport() {
    // get message user business list
    var url = baseUrl + "/admin/support/ajax-list-support";
    var numId = 0;
    //console.log(url);
    tTable = $('#supportList').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        deferLoading: 60,
        searching: false,
        columns: [
            { 
                data: 'no',
                render: function ( data, type, fullData, meta ) {
                    return fullData.no;
                }
            },
            {
                data: 'firstname',
                name: 'Name',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/support/detail/' + fullData.id;
                    return '<a href="' + urlNewLink + '">' + fullData.firstname + ' ' + fullData.lastname + '</a>';
                }
            },
            {
                data: 'email',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/support/detail/' + fullData.id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
                }
            },
            {
                data: 'message',
                render: function ( data, type, fullData, meta ) {
                    return fullData.message;
                }
            },
            {
                data: 'created_at',
                render: function ( data, type, fullData, meta ) {
                    return fullData.created_at;
                }
            },
            { 
                data: 'status',
                name: 'Status',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.status == 1) {
                        html = 'Replied';
                    } else if (fullData.status == 0) {
                        html = 'New';
                    }
                    return html;
                }
            },
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