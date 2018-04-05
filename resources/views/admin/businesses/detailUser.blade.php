@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Business Manager > User Detail</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row businesses-detail">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Email:
                    </div>
                    <div class="col-md-8">
                      <?php echo $detailUser->email; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Name:
                    </div>
                    <div class="col-md-8">
                      <?php echo $detailUser->firstname.' '.$detailUser->lastname; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Location:
                    </div>
                    <div class="col-md-8">
                      <?php echo $detailUser->city_name.' '.$detailUser->state_name.' '.$detailUser->zipcode.' '.$detailUser->country_name; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Created On:
                    </div>
                    <div class="col-md-8">
                      <?php echo date('h:i m-d-Y', strtotime($detailUser->created_on)); ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      User Type:
                    </div>
                    <div class="col-md-8">
                      <?php if ($detailUser->user_type == 2) :?>
                        Alert Manager
                      <?php elseif ($detailUser->user_type == 3) :?>
                        Direct
                      <?php elseif ($detailUser->user_type == 4) :?>
                        Indirect
                      <?php endif;?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Status:
                    </div>
                    <div class="col-md-8">
                      <?php if ($detailUser->status == 0) :?>
                        Deactive
                      <?php elseif ($detailUser->status == 1) :?>
                        Active
                      <?php endif;?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Total Message:
                    </div>
                    <div class="col-md-8">
                      <?php echo $detailUser->total_msg;?>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class='avatar-box'>
                  <?php if ($detailUser->avatar && file_exists(env('DIR_UPLOAD_USER').$detailUser->avatar)) :?>
                    <img src="<?php echo url(env('DIR_UPLOAD_USER').$detailUser->avatar)?>" />
                  <?php else:?>
                    <img src="{!!url('public/admin/')!!}/images/user-64.png" />
                  <?php endif;?>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="row">
                    <div class="col-md-12">
                      <?php echo $detailBusiness->name;?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <?php echo $detailBusiness->address;?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <?php echo $detailBusiness->city_name.' '.$detailBusiness->state_name.' '.$detailBusiness->zipcode; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      Direct/Indirect
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <?php echo $detailBusiness->direct_indirect; ?>
                    </div>
                  </div>
                </div>
          </div>
        </div>

          <div class="x_content">
            <p class="font-13 m-b-30 text-right">
              Created:<?php echo $detailUser->total_msg;?>; Received: <?php echo $detailUser->receive;?> messages
            </p>
            <table id="messagesList" class="table table-striped table-bordered">
              <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created On</th>
                    <th>Message Type</th>
                    <th>Received</th>
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
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/businesses.js"></script>
<script type="text/javascript">
    var business_id = {!!$business_id!!};
    var user_id = {!!$detailUser->user_id!!};
    $(document).ready(function() {
      getListMessages() 
        // getListUserDirectBusiness();
        // getListUserIndirectBusiness();
        // getListUserManagerBusiness();
    });
</script>
@endsection
@section('nav-business')
<ul class="nav nav-business">
  <li><a href="{{url('/admin/businesses/detail').'/'.$business_id}}">Detail</a></li>
  <li class="disable"><a href="javascript:void(0)">Billing</a></li>
  <li class="current-page"><a href="{!!url('/admin/businesses/user-business').'/'.$business_id!!}">User</a></li>
  <li><a href="{!!url('/admin/businesses/messages').'/'.$business_id!!}">Business's Messages</a></li>
</ul>
@endsection