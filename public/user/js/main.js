var map;
var marker;
var nice = '';
var returnPopup = '';
var clickPopup = '';
$(document).ready(function(){
    $('.social-share a').click(function(e) {
       e.preventDefault();
       window.open($(this).attr('href') + window.location.href, 'share', 'width=600,height=400');
       // window.open(window.location.href, 'share','width=600,height=400');
   });
    $('.counter-header-share').click(function(){
        if ($('.social-share:visible').length > 0) {
            $('.social-share').hide();
        } else {
            $('.social-share').show();
        }
    });
    $(document).click(function(e){
        if (!$('.counter-header-share').is(e.target) && $('.counter-header-share').has(e.target).length === 0) {
            $('.social-share').hide();
        }
    });
    //forgot password
    $('#changePasswordForm').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'topLeft',
        showOneMessage:true,
        onValidationComplete: function(form, status){
            if (status) {
                $('body').addClass("loading");
                var formData = $('#changePasswordForm').serialize()
                $.ajax({
                    url: baseUrl + '/user/update-password',
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        $('body').removeClass("loading");
                        if (data.code == 200) {
                            window.location.href = baseUrl + '/user';
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
                        $('body').removeClass("loading");
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
                });
            }
            return false;
        }
        //validationEventTrigger: 'submit'
    });
    $('#forgotPasswordForm').submit(function(e){
        e.preventDefault();
        $('body').addClass("loading");
        $.ajax({
            url: baseUrl + '/user/send-forgot-password',
            type: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                $('body').removeClass("loading");
                if (data.code == 200) {
                    $.confirm({
                        title: 'Thank You!',
                        content: data.message,
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
                $('body').removeClass("loading");
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
        });
    });
    //paging
    $('.cotent-main').on('click', '.paging_content a', function(e){
        e.preventDefault();
        paging($(this).attr('href'), {search: $('.search-input').val()});
    });
    $('.data_right_content').on('click', '.paging_partial a', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var page = url.split('?');
        $.ajax({
            url: baseUrl + '/user/right-content?'+page.pop(),
            type: 'GET',
            data: {
            },
            success: function(data) {
                $('.data_right_content').html(data);
            },
            error: function(data) {
            }
        });
    });
    //
    $(".placeholder-input").polymerForm();
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
    // $(".select-box-country")
    //     .select2({
    //         placeholder: "Country"
    //     })
    //     .on("change", function(e) {
    //         var countryId = $(this).val();
    //         var selectOption = this;
    //         $.ajax({
    //             url: baseUrl + '/user/get-state',
    //             type: 'POST',
    //             data: {
    //                 cid: countryId,
    //             },
    //             success: function(data) {
    //                 console.log(data);
    //                 var htmlState = '<option></option>';
    //                 if (data.code == 200) {
    //                     $.each(data.dataState, function(i, name) {
    //                         htmlState += '<option value="'+ i +'">' + name + '</option>'
    //                     })
    //                 }
    //                 $('.select-box-state').html(htmlState);
    //                 //$('body').removeClass("loading");
    //             },
    //             error: function(data) {
    //                 //$('body').removeClass("loading");
    //             },
    //             headers: {
    //                 'X-CSRF-TOKEN': $('[name="_token"]').attr('value')
    //             }
    //         });
    //     });

    $(".select-box-state").select2({
        placeholder: "State"
    });

    var wWidth = $(window).width();
    if (wWidth < 980) {
        wWidth = '98%';
    } else {
        wWidth = "50%";
    }

    $(".terms-fancy").colorbox({
        inline:true,
        width: wWidth,
        height: '100%',
        opacity: 0.7,
        open: true,
        onLoad: function() {
            $('#cboxClose').remove();
        }
    });

    $(".inline").colorbox({
        inline:true,
        opacity: 0.7,
        open: true,
        onLoad: function() {
            $('#cboxClose').remove();
        }
    });


    $('.btn-deaccept').click(function() {
        $.colorbox.close();
        return false;
    })
    /*---------process register--------*/
    $('#register').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'topLeft',
        showOneMessage:true,
        onValidationComplete: function(form, status){
            if (status) {
                var address = $('[name="address"]').val();
                $('.addressLabel').html(address);
                $(".termLink").colorbox({
                    inline:true,
                    width: 800,
                    opacity: 0.7,
                    open: true,
                    onLoad: function() {
                        $('#cboxClose').remove();
                    },
                    onClosed: function() {

                    }
                });

            }
            return false;
        }
        //validationEventTrigger: 'submit'
    });

    $('#btn-term-decline').click(function(){
        $.colorbox.close();
    });

    $('#btn-term-accept').click(function(){
        // $.colorbox.close();
        var action = $(this).attr('data-action');
        if (action == 'business') {
            var address = $('[name="address"]').val() + ', ' +$('[name="city"]').val() + ', ' + $(".select-box-state").select2('data')[0].text + ' ' +$('[name="zipCode"]').val() + ', ' + $(".select-box-country").select2('data')[0].text;
            $('.addressLabel').html(address);
            $(".mapLink").colorbox({
                inline:true,
                opacity: 0.7,
                open: true,
                onLoad: function() {
                    $('#cboxClose').remove();
                },
                onComplete: function() {
                    google.maps.event.trigger(map, 'resize');
                    addressToLocation(address);
                }
            });
        } else if (action == 'user') {
            var registerForm = $(".form-register");
            var formData = registerForm.serialize();
            $.ajax({
                url: baseUrl + '/user/ajax-free-register',
                type: 'POST',
                data: formData,
                success: function(data) {
                    console.log(data);
                    if (data.code == 200) {
                        $(".user_thankLink").colorbox({
                            inline:true,
                            opacity: 0.7,
                            open: true,
                            onLoad: function() {
                                $('#cboxClose').remove();
                            },
                            onClosed: function() {
                                var email  = $('#registerFreeForm [name="email"]').val();
                                var password = $('#registerFreeForm [name="password"]').val();
                                $('#loginForm [name="email"]').val(email);
                                $('#loginForm [name="password"]').val(password);
                                $(".form-register").trigger('reset');
                                $('#loginForm').submit();
                            }
                        });
                    }
                },
                error: function(data) {

                }
            });
        }
    });

    $('#registerFreeForm').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'topLeft',
        showOneMessage:true,
        onValidationComplete: function(form, status){
            if (status) {
                var registerForm = $(".form-register");
                var formData = registerForm.serialize();
                $(".termLink").colorbox({
                    inline:true,
                    width: 800,
                    opacity: 0.7,
                    open: true,
                    onLoad: function() {
                        $('#cboxClose').remove();
                    },
                    onClosed: function() {

                    }
                });

            }
            return false;
        }
        //validationEventTrigger: 'submit'
    });

    $('#mapConfirm').click(function(){
        console.log('registerBusiness');
        var registerForm = $(".form-register");
        var formData = registerForm.serialize();
        $.ajax({
            url: baseUrl + '/user/business-register',
            type: 'POST',
            data: formData,
            success: function(data) {
                // console.log(data);
                $(".form-register").trigger('reset');
                if (data.code == 200) {
                    $(".thankLink").colorbox({
                        inline:true,
                        opacity: 0.7,
                        open: true,
                        overlayClose: false,
                        onLoad: function() {
                            $('#cboxClose').remove();
                        },
                        onClosed: function() {
                            window.location.href = baseUrl + '/user';
                            // var email  = $('#register [name="email"]').val();
                            // var password = $('#register [name="password"]').val();
                            // $('#loginForm [name="email"]').val(email);
                            // $('#loginForm [name="password"]').val(password);
                            // $('#loginForm').submit();
                        }
                    });
                }
            },
            error: function(data) {

            }
        });
    })

    $('.start_start').click(function(){
        $.colorbox.close();
        //update for user invitation
        // location.href = baseUrl + '/user';
    })

    // login form
    $('#loginForm').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'bottomLeft',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: false,
        focusInvalid: false
    });
    // login ajax
    var loginForm = $("#loginForm");
    loginForm.submit(function(e) {
        if ($("#loginForm").validationEngine('validate')) {
            $(this).attr('disabled');
            e.preventDefault();
            var formData = loginForm.serialize();
            $.ajax({
                url: baseUrl + '/user/login',
                type: 'POST',
                data: formData,
                success: function(data) {
                    if (data.code == 200) {
                        window.location.href = baseUrl + '/user';
                    } else {
                        $.confirm({
                            title: 'Error!',
                            content: data.message,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                close: function () {
                                }
                            }
                        });
                    }
                    $(this).removeAttr('disabled');
                },
                error: function(data) {
                    $(this).removeAttr('disabled');
                }
            });
        }

        return false;
    });

    // Upload image
    $("#uploadFile").click(function() {
        actionUpload = 'avatar';
        console.log(actionUpload);
        $('.qq-upload-button-selector').find('input[type=file]').click();
    })
    $("#coverImg").click(function() {
        actionUpload = 'cover';
        console.log(actionUpload);
        $('.qq-upload-button-selector').find('input[type=file]').click();
    })

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
    // load detail message
    $('.cotent-main').on('click', '.iframe', function(){
        clickPopup = $(this);
    })
    returnPopup = $(".iframe").colorbox({
        iframe:true,
        width:"80%",
        height:"80%",
        opacity: 0.7,
        maxWidth: 700,
        maxHeight: '95%',
        onLoad: function() {
            console.log('onLoad');
            // $('#cboxClose').remove();
            $('#cboxWrapper').addClass('replayMessageWrap');
        },
        onOpen: function() {
            console.log('onOpen');
            setTimeout(function(){
                //$("#cboxLoadedContent iframe").attr('scrolling', 'no');
                //$("#cboxLoadedContent iframe").niceScroll();
                //nice = $("#cboxLoadedContent iframe").getNiceScroll();
            }, 500)
        },
        onComplete : function() {
           console.log('onComplete', this);
           returnPopup = $(this);
           var poupReturn = $(this);
           setTimeout(function(poupReturn){
                console.log('onComplete', poupReturn);
            }, 500)
        }
    });



    $('.replay-send-message-icon').click(function() {
        if ($('#image-replay-file').val() != '' || $('.message-replay-text').val() != '') {
            $( "#replayMesssage" ).submit();
        }

    })
    // replay message ajax
    $('#replayMesssage').submit(function(e) {

        //$('body').addClass("loading")
        e.preventDefault();
        //var formData = $("#sendMesssage").serialize();
        var formData = new FormData(this);
        formData.append('messageId', messageId);

        $.ajax({
            url: baseUrl + '/user/ajax-replay-message',
            type: 'POST',
            //data: formData + '&idBusiness=' + idBusiness,
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data) {
                if (data.code == 200) {
                    $('.message-replay-text').val('');
                    var htmlAvarta = '';
                    if (data.dataMessage.avatar != '') {
                        htmlAvarta = '<img src="'+ data.dataMessage.avatar +'">';
                    } else {
                        htmlAvarta = data.dataMessage.no_image;
                    }
                    var html = '';
                    html = '<li>'
                            + '<div class="avatar">'
                                + htmlAvarta
                            + '</div>'
                            + '<div class="replay-detail">'

                                + '<div class="block-replay-1">'
                                    + '<span class="user-name">' + data.dataMessage.userName +'</span>'
                                    + '<span class="date">' + data.dataMessage.date +'</span>'
                                + '</div>'
                                + '<div class="image-replay-view"><img src="' + data.dataMessage.image + '" /></div>'
                                + '<div class="replay-detail-text">'
                                    + data.dataMessage.message
                                + '</div>'
                            + '</div>'
                        + '</li>';

                    $('.replay-list ul').append(html);
                    //window.parent.nice.resize();
                    removeImageReplay();
                    /*
                    if (nice) {
                        console.log('nice true');
                        nice.resize();
                    } else {
                        console.log('nice false');
                    }
                    */



                }
                return false;
            },
            error: function(data) {
            }
        });


        return false;
    });

    // save loaltion
    navigator.geolocation.getCurrentPosition(function(location) {
      $.ajax({
            url: baseUrl + '/user/ajax-add-local',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {lat: location.coords.latitude, lon: location.coords.longitude},
            success: function(data) {
            }
        });
		// distance
        $('.distance-api').each(function(i,v) {
            console.log($.attr('data-lat'));
            var bLat = $(v).attr('data-lat');
            var bLon = $(v).attr('data-lon');

            var distanceTmp = getDistanceFromLatLonInKm(bLat, bLon, location.coords.latitude, location.coords.longitude);
            $(this).find('.count-miles').html(Math.round(distanceTmp * 100) / 100 + ' miles');
            console.log($(this).find('.count-miles').length );
        })
    });
    // create mesage
    $('.create-mesage').click(function() {
        loadSendMessage();
    })


    var type = '';
    $('#createMesssage').on('submit',(function(e) {
        e.preventDefault();
        if (type == '') {
            console.log('type empty');
            return false;
        }
        $('body').addClass("loading");
        var formData = new FormData(this);
        formData.append('idBusiness', curentBusinessId);
        formData.append('option-message', type);
        type = '';
        $.ajax({
            type:'POST',
            url: baseUrl + '/user/ajax-send-message',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                $('body').removeClass("loading");
                $('#createMesssage')[0].reset();
                paging(window.location.href + '?page=1');
                $.colorbox.close();
            },
            error: function(data){
				$('body').removeClass("loading");
                $.colorbox.close();
                $( "#dialog-message-err" ).dialog({
                    modal: true,
                    buttons: [
                        {
                            text: "Ok",
                            icon: "ui-icon-heart",
                            id: 'btn-oke',
                            click: function() {
                                $( this ).dialog( "close" );
                                location.reload();
                            }
                        }
                    ]
                });
            }
        });
        console.log(formData);
    }));

    $('.send-message-btn').click(function() {
        type = $(this).attr('data-type');
        $("#createMesssage").submit();
    })

    $('.group-upload img').click(function() {
        $('#image-create').click();
    })
    $('.replay-messgess-send img.replay-select-img').click(function() {
        $('#image-replay-file').click();
    })

    /*
    $('.send-message-btn').click(function() {
        $('body').addClass("loading")
        var type = $(this).attr('data-type');
        var message = $('#messge-content-send').val();
        if (message != '') {
            $.ajax({
                url: baseUrl + '/user/ajax-send-message',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'option-message': type,
                    message: message,
                    idBusiness: curentBusinessId
                },
                success: function(data) {
                    $('body').removeClass("loading");
                    $('#messge-content-send').val('');
                }
            });
        }
    })
    */

    // logout
    $('.logout').click(function() {
        console.log('logout');
        deleteToken();
        return true;
    })

    $('.image-image-create .remove-image').click(function() {
        $(this).parent().find('img').remove();
        $(this).addClass('hide');
        $('#image-create').val('');
        $.colorbox.resize();
    })
    $('.replay-img .remove-image').click(function() {
        removeImageReplay();
    })

    // event upload image message
    if ($('#image-create').length > 0) {
        document.getElementById("image-create").addEventListener("change", readFile);
    }

    if ($('#image-replay-file').length > 0) {
        document.getElementById("image-replay-file").addEventListener("change", readFileReplay);
    }

    // set timezon
    var timezone = jstz.determine();
    $.cookie('time_zone_offset', timezone.name());
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

function initMap() {
    var latLon = {lat: -25.363, lng: 131.044};
    map = new google.maps.Map(document.getElementById('mapSite'), {
      zoom: 16,
      center: latLon
    });
    marker = new google.maps.Marker({
      position: latLon,
      map: map,
      draggable:true,
      icon: '../images/marker.png'
    });
    map.panTo(latLon);

    //marker.addListener('drag', handleEventMarker);
    ///marker.addListener('dragend', handleEventMarker);

    // set latlon
    $('input[name="lat"]').val(latLon.lat);
    $('input[name="lon"]').val(latLon.lng);
}

function validateUser(field, rules, i, options) {
    var userName  = $(field).val();
    var token = $('#register [name="_token"]').val();
    if (token == undefined) {
        token = $('#registerFreeForm [name="_token"]').val();
    }
    if (userName == '') {
        rules.push('required');
    }else {
        var isEmail = validateEmail(userName);
        if (!isEmail) {
            return "* Please input email address";
        } else {
            var dataAjax = [];
            $.ajax({
                url: baseUrl + '/user/check-business-acc',
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

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function addressToLocation(address) {
    google.maps.event.trigger(map, 'resize');
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode(
		{
			address: address
		},
		function(results, status) {

			var resultLocations = [];

			if(status == google.maps.GeocoderStatus.OK) {
				if(results) {
					var numOfResults = results.length;
					for(var i=0; i<numOfResults; i++) {
						var result = results[i];
						resultLocations.push(
							{
								text:result.formatted_address,
								addressStr:result.formatted_address,
								location:result.geometry.location
							}
						);
					};
				}
			} else if(status == google.maps.GeocoderStatus.ZERO_RESULTS) {
				// address not found
			}

			if(resultLocations.length > 0) {
			     console.log(resultLocations)
				//marker.setMap(null);
                var center = new google.maps.LatLng(resultLocations[0].location.lat(), resultLocations[0].location.lng());
                var latLon  = {lat: resultLocations[0].location.lat(), lng: resultLocations[0].location.lng()};
                // set latlon
                $('input[name="lat"]').val(resultLocations[0].location.lat());
                $('input[name="lon"]').val(resultLocations[0].location.lng());
                marker = new google.maps.Marker({
                    position: latLon,
                    map: map,
                    draggable:true
                });
                marker.addListener('drag', handleEventMarker);
                marker.addListener('dragend', handleEventMarker);
                map.panTo(center);
			}
		}
	);
}
function handleEventMarker(event) {
    console.log(event);
    $('input[name="lat"]').val(event.latLng.lat());
    $('input[name="lon"]').val(event.latLng.lng());
}
function loadDetailMessage() {
    $(".popupMessageDetail").colorbox({
        inline:true,
        opacity: 0.7,
        open: true,
        maxWidth: 700,
        maxHeight: '95%',
        onLoad: function() {
            // $('#cboxClose').remove();
            $('#cboxWrapper').addClass('replayMessageWrap');
        },
        onOpen: function() {

            setTimeout(function(){
                //$(".replayMessageWrap #cboxLoadedContent").niceScroll();
            }, 500)
        }
    });
}

function loadSendMessage() {
    $(".popupSendMessage").colorbox({
        inline:true,
        opacity: 0.7,
        open: true,
        width: 600,
        maxWidth: '100%',
        maxHeight: '95%',
        onLoad: function() {
            //$('#cboxWrapper').addClass('sendMessageWrap removeBorder');
        },
        onOpen: function() {

            var urlImg = $('.block-logo img').attr('src');
            $('#send_message_content .img-rounded.img-circle').attr('src', urlImg);
        }
    });
}

function notification(biz_id, flag) {
    $('body').addClass("loading");
    $.ajax({
        url: baseUrl + '/user/update-notification',
        type: 'POST',
        data: {biz_id: biz_id, flag: flag, _token: $('meta[name="csrf-token"]').attr('content')},
        success: function(data) {
            $('body').removeClass("loading");
            if (data.code == 200) {
                $.confirm({
                    title: 'Confirm Success!',
                    content: data.msg,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        close: function () {
                            $('.cotent-main').find('.paging_content li:first-child a').trigger('click');
                            $('.counter-notice').html(data.count);
                            $('.item-' + biz_id).remove();
                        }
                    }
                });
            } else {
                $.confirm({
                    title: 'Encountered an error!',
                    content: data.msg,
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
            $('body').removeClass("loading");
        }
    });
}

function paging(url, param) {
    $.ajax({
            url: url,
            type: 'GET',
            data: param,
            success: function(data) {
                $('.content-data-list .list-data-wrap .list-data').html(data);
                returnPopup = $(".iframe").colorbox({
                    iframe:true,
                    width:"80%",
                    height:"80%",
                    opacity: 0.7,
                    maxWidth: 700,
                    maxHeight: '95%',
                    onLoad: function() {
                        console.log('onLoad');
                        // $('#cboxClose').remove();
                        $('#cboxWrapper').addClass('replayMessageWrap');
                    },
                    onOpen: function() {
                        console.log('onOpen');
                        setTimeout(function(){
                            //$("#cboxLoadedContent iframe").attr('scrolling', 'no');
                            //$("#cboxLoadedContent iframe").niceScroll();
                            //nice = $("#cboxLoadedContent iframe").getNiceScroll();
                        }, 500)
                    },
                    onComplete : function() {
                       console.log('onComplete', this);
                       returnPopup = $(this);
                       var poupReturn = $(this);
                       setTimeout(function(poupReturn){
                            console.log('onComplete', poupReturn);
                        }, 500)
                    }
                });
            },
            error: function(data) {
            }
        });
}

function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1);
  var a =
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ;
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  var d = R * c; // Distance in km
  return d;
}

function deg2rad(deg) {
  return deg * (Math.PI/180)
}

function addressMap(address) {
    if (address != '') {
        google.maps.event.trigger(map, 'resize');
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode(
            {
                address: address
            },
            function(results, status) {

                var resultLocations = [];

                if(status == google.maps.GeocoderStatus.OK) {
                    if(results) {
                        var numOfResults = results.length;
                        for(var i=0; i<numOfResults; i++) {
                            var result = results[i];
                            resultLocations.push(
                                {
                                    text:result.formatted_address,
                                    addressStr:result.formatted_address,
                                    location:result.geometry.location
                                }
                            );
                        };
                    }
                } else if(status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                    // address not found
                }
                if(resultLocations.length > 0) {
                    var center = new google.maps.LatLng(resultLocations[0].location.lat(), resultLocations[0].location.lng());
                    var latLon  = {lat: resultLocations[0].location.lat(), lng: resultLocations[0].location.lng()};
                    // set latlon
                    map = new google.maps.Map(document.getElementById('mapSite'), {
                      zoom: 16,
                      center: latLon
                    });
                    marker = new google.maps.Marker({
                        position: latLon,
                        map: map,
                        draggable: false
                    });
                    marker.addListener('drag', handleEventMarker);
                    marker.addListener('dragend', handleEventMarker);
                    initInfo(address);
                    map.panTo(center);
                }
            }
        );
    }
}

function initInfo(address) {
    if (typeof address === 'undefined') {
        address = '';
    }
    var content = '<div>';
    if (address != '') {
        content += address;
    } else {
        content += $('.info-addr').text()
    }
    content += '</div>';
    var infowindow = new google.maps.InfoWindow({
        content: '<div>' + content + '</div>',
        close: false
    });
    google.maps.event.addListener(infowindow, 'domready', function(){
        $(".gm-style-iw").next("div").hide();
        $(".gm-style-iw").css('text-align', 'center');
    });
    infowindow.open(map,marker);
}

function readFile() {

  if (this.files && this.files[0]) {

    var FR= new FileReader();

    FR.addEventListener("load", function(e) {
        $('.image-image-create').find('img').remove();
        $('.image-image-create .remove-image').removeClass('hide');
        $('.image-image-create').prepend('<img src="' + e.target.result + '" />');
        $.colorbox.resize();
    });

    FR.readAsDataURL( this.files[0] );
  }

}

function readFileReplay() {

  if (this.files && this.files[0]) {

    var FR= new FileReader();

    FR.addEventListener("load", function(e) {
        //$('.image-image-create').find('img').remove();
        //$('.image-image-create .remove-image').removeClass('hide');
        $('.replay-img').css('background-image', 'url(' + e.target.result + ')').addClass('is_image_replay').find('.remove-image').removeClass('hide');
        $('.replay-messgess-send').addClass('is-image');
        $.colorbox.resize();
    });

    FR.readAsDataURL( this.files[0] );
  }


  $('#wrapScroll').height($(parent.document).find('#cboxLoadedContent iframe').height() - 200);
  $('#wrapScroll').getNiceScroll().remove();
  $('#wrapScroll').niceScroll();
  console.log($(".replayMessageWrap #cboxLoadedContent").length);
}


function removeImageReplay() {
    $('.replay-img .remove-image').addClass('hide').parent().css('background-image', '').removeClass('is_image_replay');
    $('#wrapScroll').height($(parent.document).find('#cboxLoadedContent iframe').height() - 80);
    $('#wrapScroll').getNiceScroll().remove();
    $('#wrapScroll').niceScroll();

    var $el = $('#image-replay-file');
    $el.wrap('<form>').closest('form').get(0).reset();
    $el.unwrap();
}

function makeid() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}

function joinPlaces(idBusiness) {
    console.log(idBusiness);
    $.confirm({
        title: 'Register Indirect!!',
        content: 'Do you want register for receive all broadcast message of this business ?',
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
                    console.log('place');
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
                                            window.location.href = baseUrl + '/user/business/page/' + idBusiness;
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

function initPopupDetail() {
    console.log('call initPopupDetail')
    $(".iframe").colorbox({
        iframe:true,
        width:"80%",
        height:"80%",
        opacity: 0.7,
        maxWidth: 700,
        maxHeight: '95%',
        onLoad: function() {
            console.log('onLoad');
            // $('#cboxClose').remove();
            $('#cboxWrapper').addClass('replayMessageWrap');
        },
        onOpen: function() {
            console.log('onOpen');
            setTimeout(function(){
                //$("#cboxLoadedContent iframe").attr('scrolling', 'no');
                //$("#cboxLoadedContent iframe").niceScroll();
                //nice = $("#cboxLoadedContent iframe").getNiceScroll();
            }, 500)
        },
        onComplete : function() {
           console.log('onComplete', this);
           returnPopup = $(this);
           var poupReturn = $(this);
           setTimeout(function(poupReturn){
                console.log('onComplete', poupReturn);
            }, 500)
        }
    });
}