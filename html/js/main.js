var map;
var marker;
var nice = '';
var returnPopup = '';
var clickPopup = '';
$(document).ready(function(){
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
                    console.log(data);
                    var htmlState = '<option></option>';
                    if (data.code == 200) {
                        $.each(data.dataState, function(i, name) {
                            htmlState += '<option value="'+ i +'">' + name + '</option>'
                        })
                    }
                    $('.select-box-state').html(htmlState);
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
            }
            return false;
        }  
        //validationEventTrigger: 'submit'
    });
    
    $('#registerFreeForm').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'topLeft',
        showOneMessage:true,
        onValidationComplete: function(form, status){
            if (status) {
                var registerForm = $(".form-register");
                var formData = registerForm.serialize();
                $.ajax({
                    url: baseUrl + '/user/ajax-free-register',
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        console.log(data);
                        if (data.code == 200) {
                            $(".thankLink").colorbox({
                                inline:true,
                                opacity: 0.7,
                                open: true,
                                onLoad: function() {
                                    $('#cboxClose').remove();
                                }
                            });
                        }
                    },
                    error: function(data) {
                        
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
                console.log(data);
                if (data.code == 200) {
                    $(".thankLink").colorbox({
                        inline:true,
                        opacity: 0.7,
                        open: true,
                        onLoad: function() {
                            $('#cboxClose').remove();
                        }
                    });
                }
            },
            error: function(data) {
                
            }
        });
    })
    
    $('#start_start').click(function(){
        $.colorbox.close();
        //update for user invitation
        location.href = baseUrl + '/user';
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
                        window.location.href = baseUrl + '/user/profile';
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
        		term: request.term
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
    $('.iframe').click(function(){
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
            $('#cboxClose').remove();
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
    
    $('.message-replay-text').keypress(function(e) {
        if(e.which == 13 && $(this).val() != '') {
            $( "#replayMesssage" ).submit()
        }
    });
    
    $('.replay-send-message-icon').click(function() {
        $( "#replayMesssage" ).submit()
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
                    var html = '';
                    html = '<li>'
                            + '<div class="avatar">'
                                + '<img src="'+ baseUrl +'/public/user/images/logo.png">'
                            + '</div>'
                            + '<div class="replay-detail">'
                            
                                + '<div class="block-replay-1">'
                                    + '<span class="user-name">' + data.dataMessage.userName +'</span>'
                                    + '<span class="date">' + data.dataMessage.date +'</span>'
                                + '</div>'
                                + '<div class="replay-detail-text">'
                                    + data.dataMessage.message
                                + '</div>'
                            + '</div>'
                        + '</li>';
                    
                    $('.replay-list ul').append(html);
                    //window.parent.nice.resize();
                }
                return false;
            },
            error: function(data) {
            }
        });
        
        
        return false;
    });
    
});


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
            return "* Please input email address or phone number";
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
				marker.setMap(null);
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
            $('#cboxClose').remove();
            $('#cboxWrapper').addClass('replayMessageWrap');
        },
        onOpen: function() {
            
            setTimeout(function(){
                $(".replayMessageWrap #cboxLoadedContent").niceScroll();
            }, 500)
        }
    });
}