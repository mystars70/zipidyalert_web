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
            <h2>Verify then create password</h2>
            <div class="contentpss">
                First Name: {{$request['firstName']}}<br />
                Lat Name: {{$request['lastName']}}<br />
                Email: {{$request['email']}}<br />
                Address: {{$request['city']}} {{ isset($request['state']) ? ','  . $dataState[$request['state']]: ''}}, {{$dataCountry[$request['country']]}}<br />
                Zipcode: {{$request['zipCode']}}
            </div>
            {{ Form::open(['url' => 'user/free/register/save', 'method' => 'put', 'id' => 'register']) }}
               <fieldset>
                  <div>
                     <input name="password" type="password"  class="texfsignup lightcolor validate[required]" placeholder="Password" id="password">
                     <input name="confirm_password" type="password" class="texfsignup lightcolor validate[required,equals[password]]" placeholder="Confirm Password" id="confirm_password">
                     <br />
                     By clicking Create an account, you agree to our <a href="#">Terms</a> and confirm that you have read our <a href="#">Data Policy</a>, including our <a href="#">Cookie Use Policy</a>. You may receive SMS message notifications from <a href="#">Zipidy Alert</a> and can opt out at any time.
                     <div>
                        <div align="center">
                           <input name="input" class="botton3" type="submit" value="Next"/>
                        </div>
                     </div>
                  </div>
               </fieldset>
               {{ Form::hidden('firstName', $request['firstName']) }}
               {{ Form::hidden('lastName', $request['lastName']) }}
               {{ Form::hidden('email', $request['email']) }}
               {{ Form::hidden('country', $request['country']) }}
               {{ Form::hidden('city', $request['city']) }}
               <?php if (isset($request['state'])): ?>
                {{ Form::hidden('state', $request['state']) }}
               <?php endif; ?>
               {{ Form::hidden('zipCode', $request['zipCode']) }}
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>
@endsection