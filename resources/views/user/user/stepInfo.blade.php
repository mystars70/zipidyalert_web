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

    {{ Form::open(['url' => 'user/free/register/address', 'method' => 'put', 'id' => 'register']) }}
		<fieldset>
          <div>
            <input name="firstName" type="text"  class="texfsignup lightcolor validate[required]" placeholder="First Name" id="firstName">
            <input name="lastName" type="text"  class="texfsignup lightcolor validate[required]" placeholder="Last Name" id="lastName">
            <input name="email" type="text"  class="texfsignup lightcolor validate[funcCall[validateUser]]" placeholder="Email" id="emailCreator">
           {{ Form::select('country', $dataCountry, 230, ['placeholder' => 'Select Country', 'class' => 'country validate[required]']) }}
           
            By clicking Create an account, you agree to our <a href="#">Terms</a> and confirm that you have read our <a href="#">Data Policy</a>, including our <a href="#">Cookie Use Policy</a>. You may receive SMS message notifications from <a href="#">Zipidy Alert</a> and can opt out at any time.
  <div>
    <div align="center">
 
      <input class="submit botton2" type="submit" value="Create an account">
    </div>
    <span class="blue">$99 a year </span></div>
          </div>
        </fieldset>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection