@extends('user.layouts.register')
@section('content')
<div id="contaniner-login">
   <div class="zerogrid">
      <div class="contaniner-login row block01">
         <div class="col-1-6 titlelogin">
            <div class="wrap-col">Let the world know that everyone’s  safety is the number one priority to your  business.<br />
            </div>
         </div>
         <div class="col-1-7">
            <h2>Add card</h2>
            {{ Form::open(['url' => 'user/register/success-business', 'method' => 'put', 'id' => 'register']) }}
               <fieldset>
                  <div>
                     <input name="cardNumber" type="text" class="texfsignup lightcolor validate[required, custom[onlyLetterNumber]]" id="Card" placeholder="Card number"/>
                     <div class="row block01">
                        <div class="col-1-8">
                           <input name="cardDate" type="text" class="texfsignup lightcolor validate[required]" id="cardDate" placeholder="MM/YY" />
                        </div>
                        <div class="col-1-8">
                           <div class="margin-left">
                              <input   name="cardCvv" type="text" class="texfsignup lightcolor validate[required, custom[onlyLetterNumber]]" id="cardCvv" placeholder="CVV"/>
                           </div>
                        </div>
                     </div>
                     {{ Form::select('cardCountry', $dataCountry, null, ['placeholder' => 'Country', 'class' => 'select validate[required]']) }}
                     <input name="carZip" type="text" class="texfsignup lightcolor validate[required, custom[onlyLetterNumber]]" id="carZip" placeholder="Zip Code"/>
                     <br />
                     <div>
                        <div align="center">
                           <input class="submit botton2" type="button" value="Back" onclick="backHistory();">
                           <input name="input" class="botton3" type="submit" value="Complete" />
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
               {{ Form::hidden('password', $request['password']) }}
               {{ Form::hidden('firstName', $request['firstName']) }}
               {{ Form::hidden('lastName', $request['lastName']) }}
               {{ Form::hidden('emailCreator', $request['emailCreator']) }}
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>
@endsection