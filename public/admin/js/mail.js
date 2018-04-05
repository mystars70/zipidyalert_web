$(document).ready(function(){
    $('#mail-content').summernote();
    $('#saveForm').submit(function(e){
        e.preventDefault();
    });

    $('.mail-save').click(function(){
        if ($('.btn-codeview').hasClass('active')) {
            $('.btn-codeview').click();
        }
        $.ajax({
            url: baseUrl + '/admin/mail/save',
            type: 'POST',
            data: {
                id: $('#saveForm input[name="id"]').val(),
                subject: $('#saveForm input[name="subject"]').val(),
                content: $('#saveForm textarea').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.code == 200) {
                        $.confirm({
                            title: 'Update success!',
                            content: 'Mail has been updated.',
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
            }
        });
    });
});

function view(file) {
    console.log(file);
    $.ajax({
        url: baseUrl + '/admin/mail/view',
        type: 'POST',
        data: {
            file: file,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            $('#view_mail .modal-body').html(data);
            $('#view_mail').modal('show');
        },
        error: function(data) {
        }
    });
}

function getListEmail() {
    // get message user business list
    var url = baseUrl + "/admin/mail/ajax-list-mail";
    var numId = 0;
    //console.log(url);
    var tTable = $('#mailList').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        searching: false,
        deferLoading: 60,
        columns: [
            { 
                data: 'no',
                render: function ( data, type, fullData, meta ) {
                    return fullData.no;
                }
            },
            { 
                data: 'name',
                name: 'Email Name',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/mail/detail/' + fullData.id;
                    return '<a href="' + urlNewLink + '">' + fullData.name + '</a>';
                }
            },
            {
                data: 'rule',
                name: 'Email rule',
                render: function ( data, type, fullData, meta ) {
                    return fullData.rule;
                }
            },
            {
                data: 'total_sent',
                name: 'Sent',
                render: function ( data, type, fullData, meta ) {
                    return fullData.total_sent;
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