@extends('user.layouts.register')
@section('content')
<div id="contaniner-login">
   <div class="zerogrid">
      <div class="contaniner-login row block01">
         <div class="col-1-6 titlelogin">
            <div class="wrap-col">
               <p>Let the world know that everyone’s  safety is the number one priority to your  business.<br />
               </p>
            </div>
         </div>
         <div class="col-1-7">
            <h2>Creator</h2>
            {{ Form::open(['url' => 'user/register/confirm-password', 'method' => 'put', 'id' => 'register']) }}
               <fieldset>
                  <div>
                     <input name="firstName" type="text"  class="texfsignup lightcolor validate[required]" placeholder="First Name" id="firstName">
                     <input name="lastName" type="text"  class="texfsignup lightcolor validate[required]" placeholder="Last Name" id="lastName">
                     <input name="emailCreator" type="text"  class="texfsignup lightcolor validate[required]" placeholder="Email" id="emailCreator">
                     <br />
                     By clicking Create an account, you agree to our <a href="#">Terms</a> and confirm that you have read our <a href="#">Data Policy</a>, including our <a href="#">Cookie Use Policy</a>. You may receive SMS message notifications from <a href="#">Zipidy Alert</a> and can opt out at any time.
                     <div>
                        <div align="center">
                           <input name="input" class="botton3" type="submit" value="Next" id="nextbtn" />
                        </div>
                     </div>
                  </div>
               </fieldset>
               {{ Form::hidden('email', $request['email']) }}
               {{ Form::hidden('businessName', $request['businessName']) }}
               {{ Form::hidden('country', $request['country']) }}
               {{ Form::hidden('address', $request['address']) }}
               {{ Form::hidden('city', $request['city']) }}
               {{ Form::hidden('state', $request['state']) }}
               {{ Form::hidden('zipCode', $request['zipCode']) }}
               {{ Form::hidden('saveInfo', 'false', ['id' => 'saveInfo']) }}
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>
@endsection