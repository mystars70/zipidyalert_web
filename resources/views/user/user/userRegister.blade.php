@extends('user.layouts.register')
@section('content')
<section id="register-wrap" class="register-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="register-intro">
                        <h2 class="section-heading">Be safe where you “LIVE, WORK & PLAY”</h2>
                        <ul>
                            <li>
                                <p class="p-icon location-icon">Get alert messages from registered businesses within miles 
of your GPS location.</p>
                            </li>
                            <li>
                                <p class="p-icon alert-icon">Avoid unforeseen hazardous places by getting the right messages at the right time.</p>
                            </li>
                        </ul>
                    </div>
                    
                </div>
                <div class="col-md-5">
                    <div class="form-register-wrap">
                        {{ Form::open(['url' => '#', 'method' => 'post', 'id' => 'registerFreeForm', 'class'=> 'form-register']) }}
                            <h3>Free User Registration</h3>
                            <div class="form-input-list">
                                <?php if (isset($invitation) && $invitation) :?>
                                    <input name="biz_id" type="hidden" id="biz_id" value="{{$biz_id}}">
                                    <input name="type" type="hidden" id="type" value="{{$type}}">
                                <?php endif;?>
                                <div class="form-group">
                                    <input name="firstName" type="text" label="First Name" class="validate[required] placeholder-input" id="firstName">
                                </div>
                                <div class="form-group">
                                    <input name="lastName" type="text" label="Last Name" class="validate[required] placeholder-input" id="lastName">
                                </div>
                                <div class="form-group">
                                <?php if (isset($invitation) && $invitation) :?>
                                    <input name="email" type="text" label="Email" class="validate[funcCall[validateUser]] placeholder-input " value="{{$email}}" readonly="true">
                                <?php else:?>
                                    <input name="email" type="text" label="Email" class="validate[funcCall[validateUser]] placeholder-input ">
                                <?php endif;?>
                                </div>
                                <div class="form-group">
                                    {{ Form::select('country', 
                                                    $dataCountry, 
                                                    230, 
                                                    [
                                                        'placeholder' => '', 
                                                        'class' => 'validate[required] form-control select-box select-box-country'
                                                    ]) 
                                    }}
                                </div>
                                
                                <div class="form-group mutiple">
                                    <div class="col-md-7 state-box">
                                        
                                        <?php if (!empty($dataState)): ?>
                                            {{ Form::select(
                                                'state', 
                                                $dataState, 
                                                '', 
                                                ['placeholder' => '', 
                                                'class' => 'form-control select-box select-box-state']) 
                                            }}
                                         <?php endif; ?>
                                    </div>
                                    <div class="col-md-5 zip-code-box">
                                        <input name="zipCode" type="text" label="Zip code" class="validate[required] placeholder-input ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input name="city" type="text" label="City" class="validate[required] placeholder-input" id="city">
                                </div>
                                <div class="form-group clear">
                                    <input name="password" type="password" label="Password" class="validate[required,minSize[6]] placeholder-input" id="password">
                                </div>
                                <div class="form-group clear">
                                    <input name="confirm_password" type="password" label="Re-type password" class="validate[required,equals[password]] placeholder-input ">
                                </div>
                                <div class="input-group-controll">
                                    <div class="col-md-6 no-all center">
                                        <a href="{!!url('user/')!!}" class="free-user-link">Business Registration</a>
                                    </div>
                                    <div class="col-md-6 no-all right btn-submit">
                                        <button id="registerFree" type="submit" class="btn btn-default submit">Submit <i class="icon-arrow"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-register">
                                <div class="col-md-6 no-all center">
                                    <a href="#">
                                        <img src="{!!url('public/user/')!!}/images/icon_appstore.png">
                                    </a>
                                </div>
                                <div class="col-md-6 no-all center">
                                    <a href="#">
                                        <img src="{!!url('public/user/')!!}/images/icon_playstore.png">
                                    </a>
                                </div>
                            </div>
                            <input type="hidden" name="lat" />
                            <input type="hidden" name="lon" />   
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection