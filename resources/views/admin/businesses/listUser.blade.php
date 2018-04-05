@extends('admin.layouts.master')
@section('content')
<div class="main-content">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
            <h2>Business Manager > User</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="list-user">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <div class="form-group col-sm-1">
                      <label>User type:</label>
                      </div>
                      <div class="col-sm-8">
                        <div class="form-group col-sm-2">
                          <select class="form-control tab-selection">
                            <option value="1">Manager</option>
                            <option value="2">Direct</option>
                            <option value="3">Indirect</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-3">
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
                            <?php echo $detailBusiness->country_name;?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <?php echo 'Direct '.$detailBusiness->total_direct.'/Indirect '.$detailBusiness->total_indirect;?>
                          </div>
                        </div>
                      </div>
                        <ul id="myTab" class="nav nav-tabs bar_tabs hidden" role="tablist">
                          <li role="presentation" class="active"><a href="#tab_content0" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" class="tab-manager">Manager</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" class="tab-direct">Direct</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false" class="tab-indirect">Indirect</a>
                          </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                          <div role="tabpanel" class="tab-pane fade active in" id="tab_content0" aria-labelledby="home-tab">
                                <table id="userManagerList" class="table table-striped table-bordered">
                                  <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Created</th>
                                        <th>User Type</th>
                                        <th>Status</th>
                                    </tr>
                                  </thead>
                                </table>
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content1" aria-labelledby="home-tab">
                                <table id="userDirectList" class="table table-striped table-bordered">
                                  <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Created</th>
                                        <th>User Type</th>
                                        <th>Status</th>
                                    </tr>
                                  </thead>
                                </table>
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                <table id="userIndirectList" class="table table-striped table-bordered">
                                  <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Created</th>
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
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/businesses.js"></script>
<script type="text/javascript">
    var business_id = {!!$business_id!!};
    $(document).ready(function() {
        getListUserDirectBusiness();
        getListUserIndirectBusiness();
        getListUserManagerBusiness();
    });
</script>
@endsection
@section('nav-business')
<ul class="nav nav-business">
  <li class=""><a href="{{url('/admin/businesses/detail').'/'.$business_id}}">Detail</a></li>
  <li class="disable"><a href="javascript:void(0)">Billing</a></li>
  <li><a href="{!!url('/admin/businesses/user-business').'/'.$business_id!!}">User</a></li>
  <li><a href="{!!url('/admin/businesses/messages').'/'.$business_id!!}">Business's Messages</a></li>
</ul>
@endsection