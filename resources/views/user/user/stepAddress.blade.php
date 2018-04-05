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
            <h2>Create a new free account</h2>
            {{ Form::open(['url' => 'user/free/register/password', 'method' => 'put', 'id' => 'register']) }}
               <fieldset>
                  <div>
                     <?php if (!empty($dataState)): ?>
                        {{ Form::select('state', $dataState, '', ['placeholder' => 'Select State', 'class' => 'state select validate[required]']) }}
                     <?php endif; ?>
                     <input name="city" type="text"  class="texfsignup lightcolor validate[required]" id="city" placeholder="City"/>
                     <input name="zipCode" type="text" class="texfsignup lightcolor validate[required]" id="zipCode" placeholder="Zip Code">
                     <br />
                  </div>
                     By clicking Create an account, you agree to our <a href="#">Terms</a> and confirm that you have read our <a href="#">Data Policy</a>, including our <a href="#">Cookie Use Policy</a>. You may receive SMS message notifications from <a href="#">Zipidy Alert</a> and can opt out at any time.
                  <div>
                        <div align="center">
                           <input class="submit botton3" type="submit" value="Next">
                        </div>
                  </div>
               </fieldset>
               {{ Form::hidden('firstName', $request['firstName']) }}
               {{ Form::hidden('lastName', $request['lastName']) }}
               {{ Form::hidden('email', $request['email']) }}
               {{ Form::hidden('country', $request['country']) }}
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
@endsection