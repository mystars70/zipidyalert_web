@extends('user.layouts.profile')
@section('content')
<div class="col-lg-8 list-data-wrap">
    <div class="title-block title-block-wrap">
        <h2 class="title-block">Business Profile</h2>
    </div>
    <div class="update-form-wrap update-form">
    {{ Form::open(['url' => 'user/business/add-user', 'method' => 'post', 'id' => 'business-update', 'class' => 'form-register']) }}
        <div class="col-lg-4">
            <div class="logo-wrap">
                <div class="logo-box">
                    <img src="<?php echo url(env('DIR_UPLOAD_BUSINESS').$dataBusiness->avatar)?>" />
                </div>
                <div class="upload-file-box">
                    <img src="{{url('public/user')}}/images/changeimg.png" />
                    <input name="fileName" id="fileName" type="text" class="validate[funcCall[checkImage]]" style="display: none">
                </div>
            </div>
        </div>
        <div class="col-lg-8">
                <input type="hidden" name="businessId" value="{{$dataBusiness->business_id}}">
                <!-- <div class="form-input-list"> -->
                    <input type="file" name="avatar" class="business-avatar validate[funcCall[checkImage]]">
                    <div class="form-group">
                        <input name="businessName" type="text" label="Business Name" class="placeholder-input" value="{{$dataBusiness->name}}">
                    </div>
                    <div class="form-group">
                        <input name="email" type="text" label="Business Email" class="validate[required, custom[email]] placeholder-input" value="{{$dataBusiness->email}}">
                    </div>
                    <div class="form-group">
                        {{ Form::select('country', 
                                        $dataCountry, 
                                        $dataBusiness->country_id, 
                                        [
                                            'placeholder' => '', 
                                            'class' => 'validate[required] form-control select-box select-box-country'
                                        ]) 
                        }}
                    </div>
                    <div class="form-group">
                        <input name="address" type="text" label="Address" class="placeholder-input" value="{{$dataBusiness->address}}">
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
                                    $dataBusiness->state_id, 
                                    ['placeholder' => '', 
                                    'class' => 'form-control select-box select-box-state']) 
                                }}
                        </div>
                        <div class="<?php echo $zipCode?> zip-code-box">
                            <input name="zipCode" type="text" label="Zip code" class="placeholder-input " value="{{$dataBusiness->zipcode}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <input name="city" type="text" label="City" class="validate[required] placeholder-input" id="city" value="<?php
                        if ($dataCity) {
                            echo $dataCity->city_name;
                        } elseif ($dataBusiness->city_name) {
                            echo $dataBusiness->city_name;
                        } else {
                            echo '';
                        }
                        ?>">
                    </div>
                    <div class="form-group">
                        <input name="radius" type="text" label="Radius" class="validate[funcCall[checkNumber]] placeholder-input" value="{{$dataBusiness->radius}}">
                    </div>
                <!-- </div> -->
            <div class="message-notice" style="display: none">
                <div>Your Business Has Been Updated Successfully</div>
            </div>
        </div>
        <div class="col-md-12 no-all right btn-submit">
            <button type="button" class="btn btn-white-p btn-form-cancel">Cancel</button>
            <button type="button" class="btn btn-blue-p" id="btn-update-business">Save</button>
        </div>
    </div>
            {!! Form::close() !!}
</div>
@endsection
@section('updateBusiness')
<link rel="stylesheet" href="{!!url('public/user/')!!}/css/jquery-confirm.css" type="text/css"/>
<link href="{!!url('public/user/')!!}/css/updateBusiness.css" rel="stylesheet">
<link href="{!!url('public/user/')!!}/css/validationEngine.jquery.css" rel="stylesheet">
@endsection