$(document).ready(function(){
    $('.location-tab-business').click(function(){
        $('.tab-business').click();
        $('.location-tabs a').removeClass('active');
        $('.location-tab-business').toggleClass('active');
    });
    $('.location-tab-indirect').click(function(){
        $('.tab-indirect').click();
        $('.location-tabs a').removeClass('active');
        $('.location-tab-indirect').toggleClass('active');
    });
});
function getListUserBusinesse() {
    // get message user business list
    var url = baseUrl + "/admin/location/ajax-location-list";
    var numId = 0;
    //console.log(url);
    var tTable = $('#businessList').DataTable({
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
            $('#businessList_filter').hide();
            $('#businessList_filter').parent().append(html);
            $('.custom-search label').click(function(){
                $('#businessList_filter input').val($('#search-input').val());
                $('#businessList_filter input').trigger('keyup');
            });
            $('#search-input').keydown(function(e){
                if (e.keyCode == 13) {
                    $('#businessList_filter input').val($('#search-input').val());
                    $('#businessList_filter input').trigger('keyup');
                }
            });
        },
        columns: [
            { 
                data: 'no',
                render: function ( data, type, fullData, meta ) {
                    return fullData.no;
                }
            },
            { 
                data: 'city_name',
                name: 'Location',
                render: function ( data, type, fullData, meta ) {
                    var location = '';
                    if (fullData.city_name) {
                        location += fullData.city_name;
                    }
                    if (fullData.state_name) {
                        location += ' ' + fullData.state_name;
                    }
                    if (fullData.zipcode) {
                        location += ' ' + fullData.zipcode;
                    }
                    if (fullData.country_name) {
                        location += ' ' + fullData.country_name;
                    }
                    urlNewLink = baseUrl + '/admin/location/detail/' + fullData.city_name + '/' + fullData.state_id + '/' + fullData.zipcode + '/' + fullData.country_id;
                    return '<a href="' + urlNewLink + '">' + location + '</a>';
                }
            },
            {
                data: 'created_at',
                name: 'Created On',
                render: function ( data, type, fullData, meta ) {
                    return fullData.created_at;
                }
            },
            {
                data: 'total_business',
                name: 'Business',
                render: function ( data, type, fullData, meta ) {
                    return fullData.total_business;
                }
            },
            {
                data: 'total_indirect',
                name: 'Indirect User',
                render: function ( data, type, fullData, meta ) {
                    return fullData.total_indirect;
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

function getListBusiness() {
    // get message user business list
    var url = baseUrl + "/admin/location/ajax-list-business";
    var numId = 0;
    //console.log(url);
    var tTable = $('#listBusiness').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "city_name": city_name,
                    "state_id": state_id,
                    "zipcode": zipcode,
                    "country_id": country_id
                } );
            }
        },
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
                data: 'name',
                name: 'Name',
                render: function ( data, type, fullData, meta ) {
                    return fullData.name;
                }
            },
            { 
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    return fullData.email;
                }
            },
            { 
                data: 'city_name',
                name: 'Location',
                render: function ( data, type, fullData, meta ) {
                    var location = '';
                    if (fullData.city_name) {
                        location += fullData.city_name;
                    }
                    if (fullData.state_name) {
                        location += ' ' + fullData.state_name;
                    }
                    if (fullData.zipcode) {
                        location += ' ' + fullData.zipcode;
                    }
                    if (fullData.country_name) {
                        location += ' ' + fullData.country_name;
                    }
                    return location;
                }
            },
            { 
                data: 'created_at',
                name: 'created_at',
                render: function ( data, type, fullData, meta ) {
                    return fullData.created_at;
                }
            },
            { 
                data: 'status',
                name: 'Status',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.status == 0) {
                        html = 'New';
                    } else if (fullData.status == 1) {
                        html = 'Active';
                    } else if (fullData.status == 2) {
                        html = 'Pending';
                    } else if (fullData.status == 3) {
                        html = 'Suspend';
                    } else if (fullData.status == -1) {
                        html = 'Deactive';
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

function getListIndrect() {
    // get message user business list
    var url = baseUrl + "/admin/location/ajax-list-indirect";
    var numId = 0;
    //console.log(url);
    var tTable = $('#listIndirect').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "city_name": city_name,
                    "state_id": state_id,
                    "zipcode": zipcode,
                    "country_id": country_id
                } );
            }
        },
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
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    return fullData.email;
                }
            },
            { 
                data: 'firstname',
                name: 'Name',
                render: function ( data, type, fullData, meta ) {
                    return fullData.firstname + ' ' + fullData.lastname;
                }
            },
            { 
                data: 'city_name',
                name: 'Location',
                render: function ( data, type, fullData, meta ) {
                    var location = '';
                    if (fullData.city_name) {
                        location += fullData.city_name;
                    }
                    if (fullData.state_name) {
                        location += ' ' + fullData.state_name;
                    }
                    if (fullData.zipcode) {
                        location += ' ' + fullData.zipcode;
                    }
                    if (fullData.country_name) {
                        location += ' ' + fullData.country_name;
                    }
                    return location;
                }
            },
            { 
                data: 'created_at',
                name: 'created_at',
                render: function ( data, type, fullData, meta ) {
                    return fullData.created_at;
                }
            },
            { 
                data: 'status',
                name: 'Status',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.status == 1) {
                        html = 'Active';
                    } else if (fullData.status == 0) {
                        html = 'Deactive';
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