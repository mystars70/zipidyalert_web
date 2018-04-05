var actionUpload = '';
$(document).ready(function() {
    $('#register').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'topLeft',
        showOneMessage:true,
        validationEventTrigger: 'submit'
    });
    $('#loginForm').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'bottomLeft',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        scroll: false,
        focusInvalid: false
    });
    
    $('#sendMesssage').validationEngine({
        autoHidePrompt:true,
        promptPosition: 'topLeft',
        showOneMessage:true,
        validationEventTrigger: 'submit',
        focusInvalid: true
    });
    
    $('#invoiceCompany').click(function(){
        $('#saveInfo').val('true');
        $('#register').attr('action', baseUrl + '/user/register/success-business');
        $('#register').submit()
    })
    
    $('#creditCard').click(function(){
        $('#saveInfo').val('false');
    })
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
    
    // send message ajax
    $('#sendMesssage').submit(function(e) {
        
        if ($("#sendMesssage").validationEngine('validate')) {
            $('body').addClass("loading")
            e.preventDefault();
            //var formData = $("#sendMesssage").serialize();
            var formData = new FormData(this);
            formData.append('idBusiness', idBusiness);
            $.ajax({
                url: baseUrl + '/user/ajax-send-message',
                type: 'POST',
                //data: formData + '&idBusiness=' + idBusiness,
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(data) {
                    if (data.code == 200) {
                        $.confirm({
                            title: 'Notication!',
                            content: data.message,
                            type: 'blue',
                            typeAnimated: true,
                            buttons: {
                                close: function () {
                                    $("#sendMesssage")[0].reset();
                                }
                            }
                        });
                        $('body').removeClass("loading");
                    }
                },
                error: function(data) {
                    $(this).removeAttr('disabled');
                    $('body').removeClass("loading");
                }
            });
        }
        
        return false;
    });
    
    // replay message ajax
    $('#replayMesssage').submit(function(e) {
        
        $('body').addClass("loading")
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
                    $.confirm({
                        title: 'Notication!',
                        content: data.message,
                        type: 'blue',
                        typeAnimated: true,
                        buttons: {
                            close: function () {
                                $("#replayMesssage")[0].reset();
                            }
                        }
                    });
                    $('body').removeClass("loading");
                }
                return false;
            },
            error: function(data) {
                $(this).removeAttr('disabled');
                $('body').removeClass("loading");
            }
        });
        
        
        return false;
    });
    // address lost focus
    $("#register #address").focusout(function() {
        /*
        var addressText = $(this).val();
        if (addressText != '') {
            addressToLocation(addressText);
        }
        */
    })
    
    $(".confirmAddress").click(function() {
        var addressText = $('#address').val();
        var address = [];
        if ($('#address').val() != '') {
            address.push($('#address').val());
        }
        if ($('#city').val() != '') {
            address.push($('#city').val());
        }
        if (addressText != '') {
            addressToLocation(address.toString());
        }
        return false;
    })
    
    $('#registerFree').click(function(){
        var url = baseUrl + '/user/free/register';
        window.location.href = url;
    })
    // load map user profile
    if (typeof userInfo != "undefined") {
        loadmapProfile(userInfo.address);
    }
    
    // save follow
    $('#followSave').click(function(){
        $('body').addClass("loading");
        $.ajaxSetup({
            
        });
        $.ajax({
            url: baseUrl + '/user/ajax-follow',
            type: 'POST',
            data: {
                follow: $('#follow').is(':checked'),
                'idBusiness': idBusiness
            },
            success: function(data) {
                if (data.code == 200) {
                    
                }
                $('body').removeClass("loading");
            },
            error: function(data) {
                $('body').removeClass("loading");
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    })
    
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
    
})

function validateUser(field, rules, i, options) {
    var userName  = $(field).val();
    if (userName == '') {
        rules.push('required'); 
    }else {
        var isEmail = validateEmail(userName);
        var isPhone = validatePhone(userName);
        if (!isEmail) {
            if (!isPhone) {
                return "* Please input email address or phone number";
            }
            
        } else {
            var dataAjax = [];
            $.ajax({
                url: baseUrl + '/user/check-business-acc',
                type: 'POST',
                async: false,
                data: {email: userName, _method: 'POST', _token: $('#register [name="_token"]').val()},
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

function validatePhone(phone) {
    var phoneRe = /^([\+][0-9]{1,3}([ \.\-])?)?([\(][0-9]{1,6}[\)])?([0-9 \.\-]{1,32})(([A-Za-z \:]{1,11})?[0-9]{1,4}?)$/;
    return phoneRe.test(phone);
}

function backHistory() {
    window.history.back();
}

function handleEventMarker(event) {
    console.log(event);
    $('input[name="lat"]').val(event.latLng.lat());
    $('input[name="lon"]').val(event.latLng.lng());
}
function addressToLocation(address) {
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

function loadmapProfile(address) {
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
                    var latLon = {lat: resultLocations[0].location.lat(), lng: resultLocations[0].location.lng()};
                    map = new google.maps.Map(document.getElementById('map'), {
                      zoom: 16,
                      center: latLon
                    });
                    marker = new google.maps.Marker({
                      position: latLon,
                      map: map,
                      draggable:false
                    });
				
			}
		}
	);
}

function validateMIME(field, rules, i, options) {
    var fileInput = field[0].files[0];
    var MimeFilter = new RegExp(rules[3],'i');
    
    if (fileInput) {
        var isValid = /image\/png|image\/jpeg|image\/gif$/i.test(fileInput.type); 
        if (!isValid) {
            return "* Wrong Mime Type.";
        }
        
        //return isValid;
    } else { return true;}
      
}
