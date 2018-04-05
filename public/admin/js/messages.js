function getListMessages() {
    // get message user business list
    var url = baseUrl + "/admin/messages/ajax-messages-by-businesses";
    var numId = 0;
    var tTable = $('#messagesList').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        deferLoading: 60,
        initComplete: function () {
            var api = this.api();
            api.$('td').click( function () {
                api.search( this.innerHTML ).draw();
            } );
            var html = '<div class="dataTables_filter custom-search">'+
            '<label for="search-input">Search</label>'+
            '<input type="search" class="form-control input-sm" id="search-input" placeholder="">'+
            '<label for="search-input"><i class="fa fa-search" aria-hidden="true"></i><span class="sr-only">Search icons</span></label>'+
            '</div>';
            $('#messagesList_filter').hide();
            $('#messagesList_filter').parent().append(html);
            $('.custom-search label').click(function(){
                $('#messagesList_filter input').val($('#search-input').val());
                $('#messagesList_filter input').trigger('keyup');
            });
            $('#search-input').keydown(function(e){
                if (e.keyCode == 13) {
                    $('#messagesList_filter input').val($('#search-input').val());
                    $('#messagesList_filter input').trigger('keyup');
                }
            });
        },
        columns: [
            { 
                data: 'created_at',
                render: function ( data, type, fullData, meta ) {
                    return fullData.created_at;
                }
            },
            { 
                data: 'detail',
                render: function ( data, type, fullData, meta ) {
                    return fullData.detail;
                }
            },
            {
                data: 'firstname',
                name: 'Name',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/user-business/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + fullData.firstname + ' ' + fullData.lastname + '</a>';
                }
            },
            {
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail-user/'+ fullData.business_id + '/' + fullData.user_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
                }
            },
            {
                data: 'name',
                name: 'Business Name',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + fullData.name + '</a>';
                }
            },
            {
                data: 'direct_indirect',
                name: 'Direct/Indirect',
                render: function ( data, type, fullData, meta ) {
                    return fullData.direct_indirect;
                }
            },
            {
                data: 'receive',
                render: function ( data, type, fullData, meta ) {
                    return fullData.receive;
                }
            },
                        {
                data: 'reply',
                render: function ( data, type, fullData, meta ) {
                    return fullData.reply;
                }
            },
        ],
        rowId: 'staffId',
        order: [],

    });
    tTable.on( 'order.dt search.dt', function () {
        tTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            numId = i+1;
            $(cell).find('a').text(numId);
        } );
    } ).draw();
}