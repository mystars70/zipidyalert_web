@extends('user.layouts.register')
@section('content')
<div id="contaniner-login">
   <div class="zerogrid">
      <div class="contaniner-login row block01">
         <div class="col-1-6 titlelogin">
            <div class="wrap-col">Streamline communication, reduce  your liability and unforeseen cost by getting the right message to the right people at the right time.<br />
            </div>
         </div>
         <div class="col-1-7">
            <h2>Create a new account</h2>
            {{ Form::open(['url' => 'user/register/creator', 'method' => 'put', 'id' => 'register']) }}
               <fieldset>
                  <div>
                     <input name="address" type="text" class="texfsignup lightcolor validate[required]" id="address" placeholder="Address"/>
                     
                     <?php if (!empty($dataState)): ?>
                        {{ Form::select('state', $dataState, '', ['placeholder' => 'Select State', 'class' => 'state select validate[required]']) }}
                     <?php endif; ?>

                     
                     <input name="city" type="text"  class="texfsignup lightcolor validate[required]" id="city" placeholder="City"/>
                     <input name="zipCode" type="text" class="texfsignup lightcolor validate[required]" id="zipCode" placeholder="Zip Code">
                     <div class="map-wrap">
                        <p class="confirm-address">Please, confirm address on map. <a href="#" class="confirmAddress">Click here</a></p>
                        <div id="addressMap"></div>
                     </div>
                     <br />
                  </div>
                     By clicking Create an account, you agree to our <a href="#">Terms</a> and confirm that you have read our <a href="#">Data Policy</a>, including our <a href="#">Cookie Use Policy</a>. You may receive SMS message notifications from <a href="#">Zipidy Alert</a> and can opt out at any time.
                  <div>
                        <div align="center">
                           <input class="submit botton3" type="submit" value="Next">
                        </div>
                  </div>
               </fieldset>
               {{ Form::hidden('email', $request['email']) }}
               {{ Form::hidden('businessName', $request['businessName']) }}
               {{ Form::hidden('country', $request['country']) }}
               {{ Form::hidden('lat', '') }}
               {{ Form::hidden('lon', '') }}
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
<script>
var map;
var marker;
function initMap() {
    var latLon = {lat: -25.363, lng: 131.044};
    map = new google.maps.Map(document.getElementById('addressMap'), {
      zoom: 16,
      center: latLon
    });
    marker = new google.maps.Marker({
      position: latLon,
      map: map,
      draggable:true
    });
    marker.addListener('drag', handleEventMarker);
    marker.addListener('dragend', handleEventMarker);
    
    // set latlon
    $('input[name="lat"]').val(latLon.lat);
    $('input[name="lon"]').val(latLon.lng);
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgxkEK3tumfrZe2-7mMABZY6_Q04QtOCI&callback=initMap&libraries=places"></script>
@endsection