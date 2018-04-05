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
                Business Email: {{$request['email']}}<br />
                Business Name: {{$request['businessName']}}<br />
                Address: {{$request['address']}}, {{$request['city']}} {{ isset($request['state']) ? ','  . $dataState[$request['state']]: ''}}, {{$dataCountry[$request['country']]}} <br />
                ZipCode: {{$request['zipCode']}}<br />
                Creator: {{$request['firstName']}} {{$request['lastName']}}<br />
                Creator email: {{$request['emailCreator']}}<br />

            </div>
            {{ Form::open(['url' => 'user/register/add-card', 'method' => 'put', 'id' => 'register']) }}
               <fieldset>
                  <div>
                     <input name="password" type="password"  class="texfsignup lightcolor validate[required]" placeholder="Password" id="password">
                     <input name="confirm_password" type="password" class="texfsignup lightcolor validate[required,equals[password]]" placeholder="Confirm Password" id="confirm_password">
                     <br />
                     By clicking Create an account, you agree to our <a href="#">Terms</a> and confirm that you have read our <a href="#">Data Policy</a>, including our <a href="#">Cookie Use Policy</a>. You may receive SMS message notifications from <a href="#">Zipidy Alert</a> and can opt out at any time.
                     <div>
                        <div align="center">
                           <input id="creditCard" class="submit botton2" type="submit" value="Pay with credit card">
                           <input name="input" class="botton3" type="button" value="Invoice company" id="invoiceCompany" />
                        </div>
                     </div>
                  </div>
               </fieldset>
               {{ Form::hidden('email', $request['email']) }}
               {{ Form::hidden('businessName', $request['businessName']) }}
               {{ Form::hidden('country', $request['country']) }}
               {{ Form::hidden('address', $request['address']) }}
               {{ Form::hidden('lat', $request['lat']) }}
               {{ Form::hidden('lon', $request['lon']) }}
               {{ Form::hidden('city', $request['city']) }}
               <?php if (isset($request['state'])): ?>
                {{ Form::hidden('state', $request['state']) }}
               <?php endif; ?>
               {{ Form::hidden('zipCode', $request['zipCode']) }}
               {{ Form::hidden('firstName', $request['firstName']) }}
               {{ Form::hidden('lastName', $request['lastName']) }}
               {{ Form::hidden('emailCreator', $request['emailCreator']) }}
               {{ Form::hidden('saveInfo', 'false', ['id' => 'saveInfo']) }}
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>
@endsection