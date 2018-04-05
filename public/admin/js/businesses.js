var flag = true;
$(document).ready(function(){
    $('#business-update').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'topRight',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: true,
        focusInvalid: false,
        validateNonVisibleFields: true,
    });
    $('.btn-update').click(function(){
        if ($("#business-update").validationEngine('validate')) {
            // $('#businessId').val(idBusiness);
            var registerForm = $(".form-register");
            var formData = registerForm.serialize();
            $.ajax({
                url: baseUrl + '/admin/businesses/change-info-business',
                type: 'POST',
                data: new FormData($(".form-register")[0]),
                contentType:false,
                cache: false,
                processData:false,
                success: function(data) {
                    if (data.code == 200) {
                        // $('.message-notice').show();
                        // setTimeout(function(){
                        //     $('.message-notice').hide();
                        // }, 2000);
                        // if (data.img) {
                        //     $('.biz_'+data.biz_id+' img').attr('src', data.img);
                        // }
                        console.log(data.status);
                        $.confirm({
                            title: 'Update success!',
                            content: 'Business has been updated.',
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                close: function () {
                                    if (data.status == '0') {
                                        html = 'New';
                                    } else if (data.status == '1') {
                                        html = 'Active';
                                    } else if (data.status == '2') {
                                        html = 'Pending';
                                    } else if (data.status == '3') {
                                        html = 'Suspend';
                                    } else if (data.status == '-1') {
                                        html = 'Deactivate';
                                    }
                                    $('.status-label').html(html);
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
        }
    });
    $(".select-box-country")
        .select2({
            placeholder: "Country"
        })
        .on("change", function(e) {
            var countryId = $(this).val();
            var selectOption = this;
            $.ajax({
                url: baseUrl + '/user/get-state',
                type: 'POST',
                data: {
                    cid: countryId,
                },
                success: function(data) {
                    var htmlState = '<option></option>';
                    if (data.code == 200) {
                        if (!jQuery.isEmptyObject(data.dataState)) {
                            $.each(data.dataState, function(i, name) {
                                htmlState += '<option value="'+ i +'">' + name + '</option>'
                            })
                            $('.select-box-state').html(htmlState);
                            displayState(true);
                        } else {
                            displayState(false);
                        }
                    }

                    //$('body').removeClass("loading");
                },
                error: function(data) {
                    //$('body').removeClass("loading");
                },
                headers: {
                    'X-CSRF-TOKEN': $('[name="_token"]').attr('value')
                }
            });
        });
    $(".select-box-state").select2({
        placeholder: "State"
    });
    // autocomplete
    $( "#city" ).autocomplete({
          source: function( request, response ) {
            $.ajax( {
              url: baseUrl + "/user/search-city",
              dataType: "jsonp",
              data: {
                term: request.term,
                state_code: $('.select-box-state').val(),
                cid: $('.select-box-country').val()
              },
              success: function( data ) {
                response( data );
              }
            } );
          },
          minLength: 2,
          select: function( event, ui ) {
            //log( "Selected: " + ui.item.value + " aka " + ui.item.id );
          }
    });
});
function displayState(display) {
    $('.select-box-state').val('');
    if (display) {
        $('.state-box').show();
        $('.zip-code-box').addClass('col-md-5');
        $('.state-box').find('.select2-container').css('width', $('.state-box').width());
    } else {
        $('.state-box').hide();
        $('.zip-code-box').removeClass('col-md-5');
    }
}
function getListBusinesse() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-business-list";
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
            '<label for="search-input">Status</label>'+
            '<select class="form-control input-sm" id="search-select">'+
            '<option value="">None</option>'+
            '<option value="0">New</option>'+
            '<option value="1">Active</option>'+
            '<option value="2">Pending</option>'+
            '<option value="3">Suspend</option>'+
            '<option value="-1">Deactivate</option>'+
            '</select>'+
            '<label for="search-input"><i class="fa fa-search" aria-hidden="true"></i><span class="sr-only">Search icons</span></label>'+
            '</div>';
            $('#businessList_filter').hide();
            $('#businessList_filter').parent().append(html);
            $('.custom-search label').click(function(){
                $('#businessList_filter input').val($('#search-input').val()+'|'+$('#search-select').val());
                $('#businessList_filter input').trigger('keyup');
            });
            $('#search-input').keydown(function(e){
                if (e.keyCode == 13) {
                    console.log(1);
                    $('#businessList_filter input').val($('#search-input').val()+'|'+$('#search-select').val());
                    $('#businessList_filter input').trigger('keyup');
                }
            });
        },
        columns: [
            { 
                data: 'no',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/messages/messages-by-businesses/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + fullData.no + '</a>';
                }
            },
            { 
                data: 'name',
                name: 'Businesses Name',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    var alert = '';
                    console.log(fullData.city_id);
                    if (fullData.city_id == null || fullData.city_id == 0) {
                        alert = '<i class="fa fa-exclamation tooltip-alert" aria-hidden="true"><span class="tooltiptext">Please check and update city name.</span></i>';
                    }
                    return '<a href="' + urlNewLink + '">' + fullData.name + '</a>' + alert;
                }
            },
            {
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
                }
            },
            {
                data: 'country_name',
                name: 'Country',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + fullData.country_name + '</a>';
                }
            },
            {
                data: 'address',
                name: 'Address',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + fullData.address + '</a>';
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
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + location + '</a>';
                }
            },
            {
                data: 'created_at',
                name: 'Created On',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + fullData.created_at + '</a>';
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
                    // return '<a  href="#" onclick="changeStatusB(this, '+fullData.business_id+', '+fullData.status+')">' + html + '</a>';
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

function changeStatusB(obj, id, status) {
    var url = baseUrl + "/admin/businesses/ajax-change-status";
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            id: id,
            status: status
        },
        success: function(result){
            if (result.code == 200) {
                $(obj).closest('td').html(result.html);
                //$(obj).html(result.html);
            }
        },
        headers: { 'X-CSRF-TOKEN': csrf_token }
    });
    return false;
}


function getListUserBusiness() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-list-user";
    var numId = 0;
    //console.log(url);
    var tTable = $('#userList').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "business_id": business_id
                } );
            }
        },
        deferLoading: 60,
        columns: [
            { 
                data: 'no',
                render: function ( data, type, fullData, meta ) {
                    return fullData.no;
                }
            },
            { 
                data: 'username',
                name: 'Username',
                render: function ( data, type, fullData, meta ) {
                    return fullData.username;
                }
            },
            { 
                data: 'phone',
                name: 'Phone',
                render: function ( data, type, fullData, meta ) {
                    return fullData.phone;
                }
            },
            { 
                data: 'zipcode',
                name: 'Zip code',
                render: function ( data, type, fullData, meta ) {
                    return fullData.zipcode;
                }
            },
            { 
                data: 'address',
                name: 'Address',
                render: function ( data, type, fullData, meta ) {
                    return fullData.address;
                }
            },
            { 
                data: 'status',
                name: 'Status',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.status == 1) {
                        html = '<i class="fa fa-check status-enable"></i>';
                    } else {
                        html = '<i class="fa fa-close status-disable"></i>';
                    }
                    return '<a  href="#">' + html + '</a>';
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

function getListUserDirectBusiness() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-list-user";
    var numId = 0;
    //console.log(url);
    var tTable = $('#userDirectList').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "business_id": business_id,
                    "type": 3
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
                    urlNewLink = baseUrl + '/admin/businesses/detail-user/'+ fullData.business_id + '/' + fullData.user_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
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
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + location + '</a>';
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
                data: 'user_type',
                name: 'user_type',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.user_type == 1) {
                        html = 'Owner';
                    } else if (fullData.user_type == 2) {
                        html = 'Manager';
                    } else if (fullData.user_type == 3) {
                        html = 'Direct';
                    } else if (fullData.user_type == 4) {
                        html = 'Indirect';
                    }
                    return html;
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

function getListUserIndirectBusiness() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-list-user";
    var numId = 0;
    //console.log(url);
    var tTable = $('#userIndirectList').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "business_id": business_id,
                    "type": 4
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
                    urlNewLink = baseUrl + '/admin/businesses/detail-user/'+ fullData.business_id + '/' + fullData.user_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
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
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + location + '</a>';
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
                data: 'user_type',
                name: 'user_type',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.user_type == 1) {
                        html = 'Owner';
                    } else if (fullData.user_type == 2) {
                        html = 'Manager';
                    } else if (fullData.user_type == 3) {
                        html = 'Direct';
                    } else if (fullData.user_type == 4) {
                        html = 'Indirect';
                    }
                    return html;
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
                     else if (fullData.status == 2) {
                        html = 'Not yet approve';
                    }
                    return html;
                }
            },
        ],
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

function getListUserDenyBusiness() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-list-deny-user";
    var numId = 0;
    //console.log(url);
    var tTable = $('#userDenyList').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "business_id": business_id,
                    "type": 3
                } );
            }
        },
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
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail-user/'+ fullData.business_id + '/' + fullData.user_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
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
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + location + '</a>';
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
                data: 'user_type',
                name: 'user_type',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.user_type == 1) {
                        html = 'Owner';
                    } else if (fullData.user_type == 2) {
                        html = 'Manager';
                    } else if (fullData.user_type == 3) {
                        html = 'Direct';
                    } else if (fullData.user_type == 4) {
                        html = 'Indirect';
                    }
                    return html;
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
function getListUserManagerBusiness() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-list-user";
    var numId = 0;
    //console.log(url);
    var tTable = $('#userManagerList').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "business_id": business_id,
                    "type": 2
                } );
            }
        },
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
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    // return fullData.email;
                    urlNewLink = baseUrl + '/admin/businesses/detail-user/'+ fullData.business_id + '/' + fullData.user_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
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
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + location + '</a>';
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
                data: 'user_type',
                name: 'user_type',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.user_type == 1) {
                        html = 'Owner';
                    } else if (fullData.user_type == 2) {
                        html = 'Manager';
                    } else if (fullData.user_type == 3) {
                        html = 'Direct';
                    } else if (fullData.user_type == 4) {
                        html = 'Indirect';
                    }
                    return html;
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

$(document).ready(function(){
    $('#avatar-update').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'bottomLeft',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: true,
        focusInvalid: false,
        validateNonVisibleFields: true,
    });
    $('#avatar-update').submit(function(e){
        e.preventDefault();
    });

    $('.upload-file-box').click(function(){
        $('.business-avatar').trigger('click');
    });

    $('.business-avatar').change(function (){
        var file = $(this).val();
        $('#fileName').val(file);
        readURL(this);
        // if (!$("#avatar-update").validationEngine('validate')) {
        //     return false;
        // }
        // var registerForm = $(".form-register");
        // var formData = registerForm.serialize();
        // $.ajax({
        //     url: baseUrl + '/admin/businesses/change-info-business',
        //     type: 'POST',
        //     data: new FormData($("#avatar-update")[0]),
        //     contentType:false,
        //     cache: false,
        //     processData:false,
        //     success: function(data) {
        //         console.log(data);
        //         if (data.code == 200) {
        //             if (data.img) {
        //                 $('.avatar-box img').attr('src', data.img);
        //             }
        //         } else {
        //             $.confirm({
        //                 title: 'Encountered an error!',
        //                 content: data.message,
        //                 type: 'red',
        //                 typeAnimated: true,
        //                 buttons: {
        //                     close: function () {
        //                     }
        //                 }
        //             });
        //         }
        //     },
        //     error: function(data) {
                
        //     }
        // });
    });
    $('.tab-selection').change(function(){
        var val = $(this).val();
        if (val == 1) {
            $('.tab-manager').click();
            $('.tab-public').click();
        } else if (val == 2) {
            $('.tab-direct').click();
            $('.tab-broadcast').click();
        } else if (val == 3) {
            $('.tab-indirect').click();
        }
    });

    $('.change-email').click(function(e){
        e.preventDefault();
        $.confirm({
            title: 'Change Email Address',
            content: '' +
            '<form action="" class="form-change-email">' +
            '<input name="_token" type="hidden" value="'+$('meta[name="csrf-token"]').attr('content')+'">' +
            '<input name="owner_id" type="hidden" value="'+owner_id+'">' +
            '<div class="form-group">' +
            '<label>New Email Address</label>' +
            '<input name="email" type="text" placeholder="Email..." class="validate[required, custom[email]] form-control" required />' +
            '</div>'+
            '</form>',
            buttons: {
                formSubmit: {
                    text: 'Submit',
                    btnClass: 'btn-blue',
                    action: function (e) {
                        this.$content.find('input').blur();
                        if ($(".form-change-email").validationEngine('validate')) {
                            var registerForm = $(".form-register");
                            var formData = registerForm.serialize();
                            $.ajax({
                                url: baseUrl + '/admin/businesses/change-info',
                                type: 'POST',
                                data: new FormData($(".form-change-email")[0]),
                                contentType:false,
                                cache: false,
                                processData:false,
                                success: function(data) {
                                    if (data.code == 200) {
                                        $.confirm({
                                            title: 'Update success!',
                                            content: 'Email has been updated.',
                                            type: 'green',
                                            typeAnimated: true,
                                            buttons: {
                                                close: function () {
                                                    $('.business-email').html(data.email);
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
                        } else {
                            return false;
                        }
                    }
                },
                cancel: function () {
                    //close
                },
            },
            onContentReady: function () {
                $('.form-change-email').validationEngine({
                    autoHidePrompt:true,
                    promptPosition: 'bottomLeft',
                    showOneMessage:true,
                    validationEventTrigger: 'blur',
                    scroll: true,
                    focusInvalid: false,
                    validateNonVisibleFields: true,
                });
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    });
    $('.change-password').click(function(e){
        e.preventDefault();
        $.confirm({
            title: 'Change Password',
            content: '' +
            '<form action="" class="form-change-password">' +
            '<input name="_token" type="hidden" value="'+$('meta[name="csrf-token"]').attr('content')+'">' +
            '<input name="owner_id" type="hidden" value="'+owner_id+'">' +
            '<div class="form-group">' +
            '<label>New Password</label>' +
            '<input name="password" type="password" placeholder="Password" class="validate[required,minSize[6]] form-control" required />' +
            '</div>'+
            '</form>',
            buttons: {
                formSubmit: {
                    text: 'Submit',
                    btnClass: 'btn-blue',
                    action: function (e) {
                        this.$content.find('input').blur();
                        if ($(".form-change-password").validationEngine('validate')) {
                            var registerForm = $(".form-register");
                            var formData = registerForm.serialize();
                            $.ajax({
                                url: baseUrl + '/admin/businesses/change-info',
                                type: 'POST',
                                data: new FormData($(".form-change-password")[0]),
                                contentType:false,
                                cache: false,
                                processData:false,
                                success: function(data) {
                                    if (data.code == 200) {
                                        $.confirm({
                                            title: 'Update success!',
                                            content: 'Password has been updated.',
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
                        } else {
                            return false;
                        }
                    }
                },
                cancel: function () {
                    //close
                },
            },
            onContentReady: function () {
                $('.form-change-password').validationEngine({
                    autoHidePrompt:true,
                    promptPosition: 'bottomLeft',
                    showOneMessage:true,
                    validationEventTrigger: 'blur',
                    scroll: true,
                    focusInvalid: false,
                    validateNonVisibleFields: true,
                });
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    });

    // $('.select-industry').change(function(){
    //     var val = $('.select-industry option:selected').val();
    //     if (flag) {
    //         flag = false;
    //         $.ajax({
    //             url: baseUrl + '/admin/businesses/change-info-business',
    //             type: 'POST',
    //             data: {
    //                 industry: val,
    //                 businessId: business_id,
    //                 _token: $('meta[name="csrf-token"]').attr('content')
    //             },
    //             success: function(data) {
    //                 flag = true;
    //                 if (data.code == 200) {
    //                     $.confirm({
    //                         title: 'Update industry success!',
    //                         content: 'Industry has been updated.',
    //                         type: 'green',
    //                         typeAnimated: true,
    //                         buttons: {
    //                             close: function () {
    //                             }
    //                         }
    //                     });
    //                 } else {
    //                     $.confirm({
    //                         title: 'Encountered an error!',
    //                         content: data.message,
    //                         type: 'red',
    //                         typeAnimated: true,
    //                         buttons: {
    //                             close: function () {
    //                             }
    //                         }
    //                     });
    //                 }
    //             },
    //             error: function(data) {
    //                 flag = true;
    //             }
    //         });
    //     }
    // });

    // $('.btn-update').click(function(){
    //     var val = $('.select-industry option:selected').val();
    //     var status = $('.select-status option:selected').val();
    //     if (flag) {
    //         flag = false;
    //         $.ajax({
    //             url: baseUrl + '/admin/businesses/change-info-business',
    //             type: 'POST',
    //             data: {
    //                 industry: val,
    //                 status: status,
    //                 businessId: business_id,
    //                 _token: $('meta[name="csrf-token"]').attr('content')
    //             },
    //             success: function(data) {
    //                 flag = true;
    //                 if (data.code == 200) {
    //                     $.confirm({
    //                         title: 'Update success!',
    //                         content: 'Business has been updated.',
    //                         type: 'green',
    //                         typeAnimated: true,
    //                         buttons: {
    //                             close: function () {
    //                                 if (data.status == '0') {
    //                                     html = 'New';
    //                                 } else if (data.status == '1') {
    //                                     html = 'Active';
    //                                 } else if (data.status == '2') {
    //                                     html = 'Pending';
    //                                 } else if (data.status == '3') {
    //                                     html = 'Suspend';
    //                                 } else if (data.status == '-1') {
    //                                     html = 'Deactivate';
    //                                 }
    //                                 $('.status-label').html(html);
    //                             }
    //                         }
    //                     });
    //                 } else {
    //                     $.confirm({
    //                         title: 'Encountered an error!',
    //                         content: data.message,
    //                         type: 'red',
    //                         typeAnimated: true,
    //                         buttons: {
    //                             close: function () {
    //                             }
    //                         }
    //                     });
    //                 }
    //             },
    //             error: function(data) {
    //                 flag = true;
    //             }
    //         });
    //     }
    // });
    $('.message-tab-reply').click(function(){
        $('.tab-reply').click();
        $('.location-tabs a').removeClass('active');
        $('.message-tab-reply').toggleClass('active');
    });
    $('.message-tab-receive').click(function(){
        $('.tab-receive').click();
        $('.location-tabs a').removeClass('active');
        $('.message-tab-receive').toggleClass('active');
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

function updateStatus(status) {
    if (flag) {
        flag = false;
        $.ajax({
            url: baseUrl + '/admin/businesses/change-info-business',
            type: 'POST',
            data: {
                status: status,
                businessId: business_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                flag = true;
                if (data.code == 200) {
                    $.confirm({
                        title: 'Update status success!',
                        content: 'Status has been updated.',
                        type: 'green',
                        typeAnimated: true,
                        buttons: {
                            close: function () {
                                if (data.status == '0') {
                                    html = 'New';
                                    html2 = '<li onclick="updateStatus(1)">Active</li>'+
                                            '<li onclick="updateStatus(2)">Pending</li>'+
                                            '<li onclick="updateStatus(3)">Suspend</li>'+
                                            '<i onclick="updateStatus(-1)">Deactivate</li>';
                                }
                                if (data.status == '1') {
                                    html = 'Active';
                                    html2 = '<li onclick="updateStatus(0)">New</li>'+
                                            '<li onclick="updateStatus(2)">Pending</li>'+
                                            '<li onclick="updateStatus(3)">Suspend</li>'+
                                            '<i onclick="updateStatus(-1)">Deactivate</li>';
                                }
                                if (data.status == '2') {
                                    html = 'Pending';
                                    html2 = '<li onclick="updateStatus(0)">New</li>'+
                                            '<li onclick="updateStatus(1)">Active</li>'+
                                            '<li onclick="updateStatus(3)">Suspend</li>'+
                                            '<i onclick="updateStatus(-1)">Deactivate</li>';
                                }
                                if (data.status == '3') {
                                    html = 'Suspend';
                                    html2 = '<li onclick="updateStatus(0)">New</li>'+
                                            '<li onclick="updateStatus(1)">Active</li>'+
                                            '<li onclick="updateStatus(2)">Pending</li>'+
                                            '<i onclick="updateStatus(-1)">Deactivate</li>';
                                }
                                if (data.status == '-1') {
                                    html = 'Deactivate';
                                    html2 = '<li onclick="updateStatus(0)">New</li>'+
                                            '<li onclick="updateStatus(1)">Active</li>'+
                                            '<li onclick="updateStatus(2)">Pending</li>'+
                                            '<li onclick="updateStatus(3)">Suspend</li>';
                                }
                                $('.status-label').html(html);
                                $('.status-group').html(html2);
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
}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.avatar-box img').attr('src', e.target.result);
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

function getListMessages() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-user-messages";
    var numId = 0;
    var tTable = $('#messagesList').DataTable({
        processing: true,
        serverSide: true,
        ajax:  {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "business_id": business_id,
                    "user_id": user_id
                } );
            }
        },
        deferLoading: 60,
        searching: false,
        // initComplete: function () {
        //     var api = this.api();
        //     api.$('td').click( function () {
        //         api.search( this.innerHTML ).draw();
        //     } );
        //     var html = '<div class="dataTables_filter custom-search">'+
        //     '<input type="search" class="form-control input-sm" id="search-input" placeholder="">'+
        //     '<label for="search-input"><i class="fa fa-search" aria-hidden="true"></i><span class="sr-only">Search icons</span></label>'+
        //     '</div>';
        //     $('#messagesList_filter').hide();
        //     $('#messagesList_filter').parent().append(html);
        //     $('.custom-search label').click(function(){
        //         $('#messagesList_filter input').val($('#search-input').val());
        //         $('#messagesList_filter input').trigger('keyup');
        //     });
        // },
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
                    return fullData.firstname + ' ' + fullData.lastname;
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
                data: 'created_at',
                render: function ( data, type, fullData, meta ) {
                    return fullData.created_at;
                }
            },
            { 
                data: 'message_type',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.message_type == 2) {
                        html = 'Public';
                    } else {
                        html = 'Broadcast';
                    }
                    return html;
                }
            },
            { 
                data: 'receive',
                render: function ( data, type, fullData, meta ) {
                    return fullData.receive;
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
function getListBusinessMessagesPublic() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-business-messages";
    var numId = 0;
    var tTable = $('#messagesListPublic').DataTable({
        processing: true,
        serverSide: true,
        ajax:  {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "business_id": business_id,
                    'type' : 2,
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
                data: 'firstname',
                name: 'Name',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail-message/'+ business_id + '/' + fullData.message_id;
                    return '<a href="' + urlNewLink + '">' + fullData.firstname + ' ' + fullData.lastname + '</a>';
                }
            },
            {
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail-message/'+ business_id + '/' + fullData.message_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
                }
            },
            { 
                data: 'created_at',
                render: function ( data, type, fullData, meta ) {
                    return fullData.created_at;
                }
            },
            { 
                data: 'message_type',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.message_type == 2) {
                        html = 'Public';
                    } else {
                        html = 'Broadcast';
                    }
                    return html;
                }
            },
            { 
                data: 'receive',
                render: function ( data, type, fullData, meta ) {
                    return fullData.receive;
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
function getListBusinessMessagesBroadcast() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-business-messages";
    var numId = 0;
    var tTable = $('#messagesListBroadcast').DataTable({
        processing: true,
        serverSide: true,
        ajax:  {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "business_id": business_id,
                    'type' : 1,
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
                data: 'firstname',
                name: 'Name',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail-message/'+ business_id + '/' + fullData.message_id;
                    return '<a href="' + urlNewLink + '">' + fullData.firstname + ' ' + fullData.lastname + '</a>';
                }
            },
            {
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/businesses/detail-message/'+ business_id + '/' + fullData.message_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
                }
            },
            { 
                data: 'created_at',
                render: function ( data, type, fullData, meta ) {
                    return fullData.created_at;
                }
            },
            { 
                data: 'message_type',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.message_type == 2) {
                        html = 'Public';
                    } else {
                        html = 'Broadcast';
                    }
                    return html;
                }
            },
            { 
                data: 'receive',
                render: function ( data, type, fullData, meta ) {
                    return fullData.receive;
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
function getListUserReceive() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-user-receive";
    var numId = 0;
    //console.log(url);
    var tTable = $('#receiveList').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "message_id": message_id,
                } );
            }
        },
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
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    // return fullData.email;
                    urlNewLink = baseUrl + '/admin/businesses/detail-user/'+ fullData.business_id + '/' + fullData.user_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
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
                    urlNewLink = baseUrl + '/admin/businesses/detail/' + fullData.business_id;
                    return '<a href="' + urlNewLink + '">' + location + '</a>';
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

function getListUserReply() {
    // get message user business list
    var url = baseUrl + "/admin/businesses/ajax-user-reply";
    var numId = 0;
    //console.log(url);
    var tTable = $('#replyList').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "message_id": message_id,
                } );
            }
        },
        searching: false,
        deferLoading: 60,
        columns: [
            { 
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    // return fullData.email;
                    var html =  '<div class="col-lg-12">'+
                                    '<div class="user-wrap">'+
                                        '<img class="avatar-box" src="'+ fullData.avatar + '">'+
                                        '<div class="user-info">'+
                                            '<span>' + fullData.email + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + fullData.created_at +'</span>'+
                                            '<span>' + fullData.firstname + ' ' + fullData.lastname + '</span>'+
                                            '<span>' + fullData.detail + '</span>'+
                                            '<div class="image-replay-view">'+
                                                '<img src="'+ fullData.image + '">'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                    return html;
                }
            },
            // { 
            //     data: 'created_at',
            //     name: 'created_at',
            //     render: function ( data, type, fullData, meta ) {
            //         return fullData.created_at;
            //     }
            // },
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