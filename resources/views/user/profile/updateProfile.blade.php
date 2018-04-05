@extends('user.layouts.profile')
@section('content')
<div class="col-lg-8 list-data-wrap">
    <div class="title-block title-block-wrap">
        <h2 class="title-block">Personal Profile</h2>
    </div>
    <div class="update-form-wrap update-form">
        {{ Form::open(['url' => 'user/profile/update', 'method' => 'post', 'id' => 'profile-update', 'class' => 'form-register']) }}
        <div class="col-lg-4">
            <div class="logo-wrap">
                <div class="logo-box">
                    <img src="<?php echo url(env('DIR_UPLOAD_USER').$dataProfile->avatar)?>" />
                </div>
                <div class="upload-file-box">
                    <img src="{{url('public/user')}}/images/changeimg.png" />
                    <input name="fileName" id="fileName" type="text" class="validate[funcCall[checkImage]]" style="display: none">
                </div>
            </div>
        </div>
        <div class="col-lg-8">
                <input type="hidden" name="userId" value="{{$dataProfile->user_id}}">
                <!-- <div class="form-input-list"> -->
                    <input style="display: none" type="file" name="avatar" class="profile-avatar validate[funcCall[checkImage]]">
                    <div class="form-group clear">
                        <input name="firstName" type="text" label="First Name" class="validate[required] placeholder-input" id="firstName" value="{{$dataProfile->firstname}}">
                    </div>
                    <div class="form-group clear">
                        <input name="lastName" type="text" label="Last Name" class="validate[required] placeholder-input" id="lastName" value="{{$dataProfile->lastname}}">
                    </div>
                    <div class="form-group">
                        <input name="email" type="text" label="Email" class="validate[funcCall[checkUser]] placeholder-input " value="{{$dataProfile->email}}">
                    </div>
                    <div class="form-group clear">
                        <input name="password" type="password" label="Password" class="placeholder-input" id="password">
                    </div>
                    <div class="form-group clear">
                        <input name="confirm_password" type="password" label="Re-type password" class="validate[equals[password]] placeholder-input ">
                    </div>
                    
                    <div class="form-group">
                        {{ Form::select('country', 
                                        $dataCountry, 
                                        $dataProfile->country_id, 
                                        [
                                            'placeholder' => '', 
                                            'class' => 'validate[required] form-control select-box select-box-country'
                                        ]) 
                        }}
                    </div>
                    
                    <div class="form-group mutiple">
                    <?php
                        $zipCode = '';
                        if ($dataState) {
                            $zipCode = 'col-md-5';
                            $state = '';
                        } else {
                            $state = 'style="display:none;"';
                        }
                    ?>
                        <div class="col-md-7 state-box" <?php echo $state?> >
                                {{ Form::select(
                                    'state', 
                                    $dataState, 
                                    $dataProfile->state_id, 
                                    ['placeholder' => '', 
                                    'class' => 'form-control select-box select-box-state']) 
                                }}
                        </div>
                        <div class="<?php echo $zipCode?> zip-code-box">
                            <input name="zipCode" type="text" label="Zip code" class="placeholder-input " value="{{$dataProfile->zipcode}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <input name="city" type="text" label="City" class="validate[required] placeholder-input" id="city" value="<?php
                        if ($dataCity) {
                            echo $dataCity->city_name;
                        } elseif ($dataProfile->city_name) {
                            echo $dataProfile->city_name;
                        } else {
                            echo '';
                        }
                        ?>">
                    </div>
                <!-- </div> -->
            <div class="message-notice" style="display: none">
                <div>Your Profile Has Been Updated Successfully</div>
            </div>
        </div>
        <div class="col-md-12 no-all right btn-submit">
            <button type="button" class="btn btn-white-p btn-form-cancel">Cancel</button>
            <button type="button" class="btn btn-blue-p" id="btn-update-profile">Save</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection
@section('updateProfile')
<link rel="stylesheet" href="{!!url('public/user/')!!}/css/jquery-confirm.css" type="text/css"/>
<link href="{!!url('public/user/')!!}/css/updateBusiness.css" rel="stylesheet">
<link href="{!!url('public/user/')!!}/css/validationEngine.jquery.css" rel="stylesheet">
@endsection