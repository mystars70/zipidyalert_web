@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>User Manager > User Detail</h2>
            <div class="clearfix"></div>
          </div>
          {{ Form::open(['url' => 'admin/businesses/change-info-business', 'method' => 'post', 'id' => 'user-update', 'class' => 'form-register']) }}
          <div class="x_content">
            <div class="row businesses-detail">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Email:
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <input name="email" type="text" label=" Email" class="validate[required, custom[email]] placeholder-input form-control" value="{{$data->email}}">
                      </div>
                      <!-- <?php echo $data->email; ?> -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      First Name:
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <input name="firstName" type="text" label=" Name" class="placeholder-input form-control" value="<?php echo $data->firstname ?>">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Last Name:
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <input name="lastName" type="text" label=" Name" class="placeholder-input form-control" value="<?php echo $data->lastname; ?>">
                      </div>
                    </div>
                  </div>
                  <!-- <div class="row">
                    <div class="col-md-4 detail-label">
                      Location:
                    </div>
                    <div class="col-md-8">
                      <?php echo $data->city_name.' '.$data->state_name.' '.$data->zipcode.' '.$data->country_name; ?>
                    </div>
                  </div> -->
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Country:
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        {{ Form::select('country', 
                                        $dataCountry, 
                                        $data->country_id, 
                                        [
                                            'placeholder' => '', 
                                            'class' => 'validate[required] form-control select-box select-box-country'
                                        ]) 
                        }}
                    </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Address:
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <input name="address" type="text" label="Address" class="placeholder-input form-control" value="{{$data->address}}">
                    </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      State Zip Code:
                    </div>
                    <div class="col-md-8">
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
                                    $data->state_id, 
                                    ['placeholder' => '', 
                                    'class' => 'form-control select-box select-box-state']) 
                                }}
                        </div>
                        <div class="<?php echo $zipCode?> zip-code-box">
                            <input name="zipCode" type="text" label="Zip code" class="placeholder-input form-control" value="{{$data->zipcode}}">
                        </div>
                    </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      City:
                    </div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <input name="city" type="text" label="City" class="validate[required] placeholder-input form-control" id="city" value="<?php
                        if (isset($dataCity) && $dataCity) {
                            echo $dataCity->city_name;
                        } elseif ($data->city_name) {
                            echo $data->city_name;
                        } else {
                            echo '';
                        }
                        ?>">
                    </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Status:
                    </div>
                    <div class="col-md-8">
                      <?php echo Form::select('size', [0 => 'Deactivate', 1 => 'Active'], $data->status, ['class' => 'select-status form-control' ,'name' => 'status']);?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Created On:
                    </div>
                    <div class="col-md-8 text-label">
                      <?php echo date('h:i m-d-Y', strtotime($data->created_at)); ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                        <button class="btn btn-primary back-link" type="button">Cancel</button>
                        <button type="button" class="btn btn-success btn-update">Save</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 col-md-offset-1">
                  <div class='avatar-box'>
                  <?php if ($data->avatar && file_exists(env('DIR_UPLOAD_USER').$data->avatar)) :?>
                    <img class="user-avatar" src="<?php echo url(env('DIR_UPLOAD_USER').$data->avatar)?>" />
                  <?php else:?>
                    <img class="user-avatar" src="{!!url('public/admin/')!!}/images/user-64.png" />
                  <?php endif;?>
                  <div class="upload-file-box">
                    <img src="{{url('public/user')}}/images/changeimg.png" />
                  </div>
                    <input name="fileName" id="fileName" type="text" class="validate[funcCall[checkImage]]" style="display: none">
                    <input type="file" name="avatar" class="user-avatar hidden validate[funcCall[checkImage]]">
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                  </div>
                </div>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
      <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <table id="userList" class="table table-striped table-bordered">
              <thead>
                <tr>
                    <th>No</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Location</th>
                    <th>Create On</th>
                    <th>User Type</th>
                    <th>Status</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    
      
    
    
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/users.js"></script>
<script type="text/javascript">
var user_id = {!!$user_id!!};
    $(document).ready(function() {
      $('#sidebar-menu').find('li.li-users').toggleClass('current-page');
      getListUserBusiness();
    });
</script>
@endsection