<?php
use Collective\Html\FormFacade;
?>
@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Business Manager > Detail</h2>
            <div class="clearfix"></div>
          </div>
          {{ Form::open(['url' => 'admin/businesses/change-info-business', 'method' => 'post', 'id' => 'business-update', 'class' => 'form-register']) }}
          <div class="x_content">
            <div class="row businesses-detail">
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Company Name:
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <input name="businessName" type="text" label="Business Name" class="placeholder-input form-control" value="{{$detailBusiness->name}}">
                    </div>
                      <!-- <?php echo $detailBusiness->name; ?> -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Country:
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        {{ Form::select('country', 
                                        $dataCountry, 
                                        $detailBusiness->country_id, 
                                        [
                                            'placeholder' => '', 
                                            'class' => 'validate[required] form-control select-box select-box-country'
                                        ]) 
                        }}
                    </div>
                      <!-- <?php echo $detailBusiness->country_name; ?> -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Business Email:
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <input name="email" type="text" label="Business Email" class="validate[required, custom[email]] placeholder-input form-control" value="{{$detailBusiness->email}}">
                      </div>
                      <!-- <?php echo $detailBusiness->email; ?> -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Address:
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <input name="address" type="text" label="Address" class="placeholder-input form-control" value="{{$detailBusiness->address}}">
                    </div>
                      <!-- <?php echo $detailBusiness->address; ?> -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      State Zip Code:
                    </div>
                    <div class="col-md-5">
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
                                    $detailBusiness->state_id, 
                                    ['placeholder' => '', 
                                    'class' => 'form-control select-box select-box-state']) 
                                }}
                        </div>
                        <div class="<?php echo $zipCode?> zip-code-box">
                            <input name="zipCode" type="text" label="Zip code" class="placeholder-input form-control" value="{{$detailBusiness->zipcode}}">
                        </div>
                    </div>
                      <!-- <?php echo $detailBusiness->city_name.' '.$detailBusiness->state_name.' '.$detailBusiness->zipcode; ?> -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      City:
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <input name="city" type="text" label="City" class="validate[required] placeholder-input form-control" id="city" value="<?php
                        if (isset($dataCity) && $dataCity) {
                            echo $dataCity->city_name;
                        } elseif ($detailBusiness->city_name) {
                            echo $detailBusiness->city_name;
                        } else {
                            echo '';
                        }
                        ?>">
                    </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Industry:
                    </div>
                    <div class="col-md-5">
                      <?php echo Form::select('size', $listIndustries, $detailBusiness->industry_id, ['class' => 'select-industry form-control' , 'name' => 'industry_id']);?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Contact Name:
                    </div>
                    <div class="col-md-8 text-label">
                      <?php echo $detailBusiness->firstname.' '.$detailBusiness->lastname;?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Contact Email:
                    </div>
                    <div class="col-md-8 text-label">
                      <span class="business-email"><?php echo $detailBusiness->email_user; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a class="change-email" href="javascript:void()">Change Email Address</a>
                    </div>
                  </div>
                  <!-- <div class="row">
                    <div class="col-md-4 detail-label">
                      Phone Number:
                    </div>
                    <div class="col-md-8">
                      <?php echo $detailBusiness->phone;?>
                    </div>
                  </div> -->
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Password:
                    </div>
                    <div class="col-md-8 text-label">
                      xxxxxx&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a class="change-password" href="javascript:void()">Change Password</a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Lat/Lon:
                    </div>
                    <div class="col-md-8 text-label">
                      <?php echo $detailBusiness->lat.'-'.$detailBusiness->lon;?>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class='avatar-box'>
                  <?php if ($detailBusiness->avatar && file_exists(env('DIR_UPLOAD_BUSINESS').$detailBusiness->avatar)) :?>
                    <img src="<?php echo url(env('DIR_UPLOAD_BUSINESS').$detailBusiness->avatar)?>" />
                  <?php else:?>
                    <img src="{!!url('public/admin/')!!}/images/logo.png" />
                  <?php endif;?>
                  </div>
                  <!-- {{ Form::open(['url' => 'admin/businesses/change-info-business', 'method' => 'post', 'id' => 'avatar-update', 'class' => 'form-register']) }} -->
                  <div class="upload-file-box">
                    <img src="{{url('public/user')}}/images/changeimg.png" />
                  </div>
                    <input name="fileName" id="fileName" type="text" class="validate[funcCall[checkImage]]" style="display: none">
                    <input type="file" name="avatar" class="business-avatar hidden validate[funcCall[checkImage]]">
                    <input type="hidden" name="user_id" value="{{$owner_id}}">
                    <input type="hidden" name="businessId" value="{{$business_id}}">
                    <!-- {!! Form::close() !!} -->
                </div>
          </div>
        </div>
        <div class="x_title">&nbsp;</div>
        <div class="row businesses-detail">
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-4 detail-label">
                Status:
              </div>
              <div class="col-md-8 status-label">
                <?php 
                  if ($detailBusiness->status == 0) {
                    echo 'New';
                  } elseif ($detailBusiness->status == 1) {
                    echo 'Active';
                  } elseif ($detailBusiness->status == 2) {
                    echo 'Pending';
                  } elseif ($detailBusiness->status == 3) {
                    echo 'Suspend';
                  } elseif ($detailBusiness->status == -1) {
                    echo 'Deactive';
                  }
                ?>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-4 detail-label">
                Change Status:
              </div>
              <div class="col-md-8">
                <?php echo Form::select('size', [0 => 'New', 1 => 'Active', 2 => 'Pending', 3 => 'Suspend', -1 => 'Deactivate'], $detailBusiness->status, ['class' => 'select-status form-control' ,'name' => 'status']);?>
                <!-- <ul class="status-group">
                <?php if ($detailBusiness->status != 0) :?>
                  <li onclick="updateStatus(0)">New</li>
                <?php endif;?>
                <?php if ($detailBusiness->status != 1) :?>
                  <li onclick="updateStatus(1)">Active</li>
                <?php endif;?>
                <?php if ($detailBusiness->status != 2) :?>
                  <li onclick="updateStatus(2)">Pending</li>
                <?php endif;?>
                <?php if ($detailBusiness->status != 3) :?>
                  <li onclick="updateStatus(3)">Suspend</li>
                <?php endif;?>
                <?php if ($detailBusiness->status != -1) :?>
                  <li onclick="updateStatus(-1)">Deactivate</li>
                <?php endif;?>
                </ul> -->
              </div>
            </div>
          </div>
          
        </div>
        {!! Form::close() !!}
        <div class="row">
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <button class="btn btn-primary back-link" type="button">Cancel</button>
              <button type="button" class="btn btn-success btn-update">Save</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/businesses.js"></script>
<script type="text/javascript">
    var business_id = {!!$business_id!!};
    var owner_id = {!!$owner_id!!};
    $(document).ready(function() {
        getListUserDirectBusiness();
        getListUserIndirectBusiness();
        getListUserManagerBusiness();
    });
</script>
@endsection
@section('nav-business')
<ul class="nav nav-business">
  <li><a href="{{url('/admin/businesses/detail').'/'.$business_id}}">Detail</a></li>
  <li class="disable"><a href="javascript:void(0)">Billing</a></li>
  <li><a href="{!!url('/admin/businesses/user-business').'/'.$business_id!!}">User</a></li>
  <li><a href="{!!url('/admin/businesses/messages').'/'.$business_id!!}">Business's Messages</a></li>
</ul>
@endsection