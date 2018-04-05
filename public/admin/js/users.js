var flag = true;
$(document).ready(function(){
    // $('.btn-update').click(function(){
    //     var status = $('.select-status option:selected').val();
    //     if (flag) {
    //         flag = false;
    //         $.ajax({
    //             url: baseUrl + '/admin/users/change-info-user',
    //             type: 'POST',
    //             data: {
    //                 status: status,
    //                 user_id: user_id,
    //                 _token: $('meta[name="csrf-token"]').attr('content')
    //             },
    //             success: function(data) {
    //                 flag = true;
    //                 if (data.code == 200) {
    //                     $.confirm({
    //                         title: 'Update success!',
    //                         content: 'User has been updated.',
    //                         type: 'green',
    //                         typeAnimated: true,
    //                         buttons: {
    //                             close: function () {
    //                                 // if (data.status == '1') {
    //                                 //     html = 'Active';
    //                                 // } else if (data.status == '0') {
    //                                 //     html = 'Deactivate';
    //                                 // }
    //                                 // $('.status-label').html(html);
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

    $('#user-update').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'topRight',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: true,
        focusInvalid: false,
        validateNonVisibleFields: true,
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
    $('.upload-file-box').click(function(){
        console.log(1);
        $('.user-avatar').trigger('click');
    });
    $('.user-avatar').change(function (){
        var file = $(this).val();
        $('#fileName').val(file);
        readURL(this);
    });
    $('.btn-update').click(function(){
        if ($("#user-update").validationEngine('validate')) {
            // $('#businessId').val(idBusiness);
            var registerForm = $(".form-register");
            var formData = registerForm.serialize();
            $.ajax({
                url: baseUrl + '/admin/users/change-info-user',
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
                            content: 'User has been updated.',
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
        }
    });
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.avatar-box .user-avatar').attr('src', e.target.result);
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
function getListUser() {
    // get message user business list
    var url = baseUrl + "/admin/users/ajax-get-list-user";
    var numId = 0;
    //console.log(url);
    var tTable = $('#userList').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        deferLoading: 60,
        // searching: false,
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
            '<option value="0">Deactivate</option>'+
            '<option value="1">Active</option>'+
            '</select>'+
            '<label for="search-input"><i class="fa fa-search" aria-hidden="true"></i><span class="sr-only">Search icons</span></label>'+
            '</div>';
            $('#userList_filter').hide();
            $('#userList_filter').parent().append(html);
            $('.custom-search label').click(function(){
                $('#userList_filter input').val($('#search-input').val()+'|'+$('#search-select').val());
                $('#userList_filter input').trigger('keyup');
            });
            $('#search-input').keydown(function(e){
                if (e.keyCode == 13) {
                    $('#userList_filter input').val($('#search-input').val()+'|'+$('#search-select').val());
                    $('#userList_filter input').trigger('keyup');
                }
            });
        },
        columns: [
            {
                data: 'no',
                render: function ( data, type, fullData, meta ) {
                    return fullData.no ;
                }
            },
            {
                data: 'email',
                name: 'Email',
                render: function ( data, type, fullData, meta ) {
                    urlNewLink = baseUrl + '/admin/users/detail/' + fullData.user_id;
                    return '<a href="' + urlNewLink + '">' + fullData.email + '</a>';
                }
            },
            {
                data: 'username',
                name: 'User Name',
                render: function ( data, type, fullData, meta ) {
                    return fullData.firstname + ' ' + fullData.lastname;
                }
            },
            // {
            //     data: 'phone',
            //     name: 'Phone',
            // },
            // {
            //     data: 'address',
            //     name: 'Address',
            //     render: function ( data, type, fullData, meta ) {
            //         var address = '';
            //         if (fullData.address) {
            //             address += fullData.address + ', ';
            //         }
            //         if (fullData.city_name) {
            //             address += fullData.city_name + ',';
            //         }
            //         if (fullData.state_name) {
            //             address += ' ' + fullData.state_name;
            //         }
            //         if (fullData.zipcode) {
            //             address += ' ' + fullData.zipcode;
            //         }
            //         if (fullData.country_name) {
            //             address += ' ' + fullData.country_name;
            //         }
            //         // address += fullData.zipcode + ' ' + fullData.country_name;
            //         return address;
            //     }
            // },
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
                    return location;
                }
            },
            {
                data: 'created_at',
                name: 'Created_at',
            },
            // {
            //     data: 'deny',
            //     name: 'Deny Status',
            //     render: function ( data, type, fullData, meta ) {
            //         if (fullData.deny == null) {
            //             html = '<i class="fa fa-check status-enable"></i>';
            //         } else {
            //             html = '<i class="fa fa-close status-disable"></i>';
            //         }
            //         return '<a  href="#" onclick="changeStatusDeny(this, '+fullData.user_id+')">' + html + '</a>';
            //     }
            // },
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
            // {
            //     name: 'Action',
            //     render: function ( data, type, fullData, meta ) {
            //         var html = '<a href="' + baseUrl + '/admin/users/detail/' + fullData.user_id +'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>';
            //         html += '<a href="' + baseUrl + '/admin/users/update/' + fullData.user_id +'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>'
            //         return html;
            //     }
            // }

        ],
        // rowId: 'staffId',
        columnDefs: [
            { orderable: false, targets: [0]},
        ],
        order: [],

	});
    tTable.on( 'order.dt search.dt', function () {
        // tTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //     numId = i+1;
        //     $(cell).find('a').text(numId);
        // } );
    } ).draw();
}

function changeStatusUser(obj, id, status) {
    var url = baseUrl + "/admin/users/ajax-change-status-user";
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id: id,
            status: status
        },
        success: function(result){
            if (result.code == 200) {
                $(obj).closest('td').html(result.html);
            }
        },
        headers: { 'X-CSRF-TOKEN': csrf_token }
    });
    return false;
}

function changeStatusDeny(obj, id, status) {
    var url = baseUrl + "/admin/users/ajax-change-status-deny";
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id: id,
        },
        success: function(result){
            if (result.code == 200) {
                $(obj).closest('td').html(result.html);
            }
        },
        headers: { 'X-CSRF-TOKEN': csrf_token }
    });
    return false;
}

function detailUser(id) {
    $('#detailPopup').modal('show');
}

function getListUserBusiness() {
    // get message user business list
    var url = baseUrl + "/admin/users/ajax-list-user-business";
    var numId = 0;
    //console.log(url);
    var tTable = $('#userList').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function ( d ) {
                return $.extend( {}, d, {
                    "user_id": user_id,
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
                data: 'name',
                name: 'Businesses Name',
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
                    return  location;
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
                data: 'user_type',
                name: 'User Type',
                render: function ( data, type, fullData, meta ) {
                    if (fullData.user_type == 1) {
                        html = 'Owner';
                    } else if (fullData.user_type == 0) {
                        html = 'Free';
                    } else if (fullData.user_type == 2) {
                        html = 'Alert Manager';
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