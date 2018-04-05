@extends('admin.layouts.master')
@section('content')
<div class="main-content">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
            <h2>Location Manager</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="list-user">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="row">
                          <div class="col-md-12">
                            <?php echo $detail->city_name.' '.$detail->state_name.' '.$detail->zipcode.' '.$detail->country_name; ?>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <?php echo  'Created On: '.date('h:i m-d-Y', strtotime($detail->created_at));?>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-8 text-right location-tabs">
                        <a class="location-tab-business active" href="javascript:void(0)">Business</a>&nbsp;/
                        <a class="location-tab-indirect" href="javascript:void(0)">Indirect</a>
                      </div>
                      </div>
                    
                        <ul id="myTab" class="nav nav-tabs bar_tabs hidden" role="tablist">
                          <li role="presentation" class="active"><a href="#tab_content0" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" class="tab-business">Business</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" class="tab-indirect">Indirect</a>
                          </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                          <div role="tabpanel" class="tab-pane fade active in" id="tab_content0" aria-labelledby="home-tab">
                                <table id="listBusiness" class="table table-striped table-bordered">
                                  <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Company Name</th>
                                        <th>Email</th>
                                        <th>Location</th>
                                        <th>Created On</th>
                                        <th>Status</th>
                                    </tr>
                                  </thead>
                                </table>
                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content1" aria-labelledby="home-tab">
                                <table id="listIndirect" class="table table-striped table-bordered">
                                  <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Created</th>
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
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/location.js"></script>
<script type="text/javascript">
    var city_name = <?php echo "'".$city_name."'"?>;
    var state_id = <?php echo "'".$state_id."'"?>;
    var zipcode = <?php echo "'".$zipcode."'"?>;
    var country_id = <?php echo "'".$country_id."'"?>;
    $(document).ready(function() {
      $('#sidebar-menu').find('li.li-location').toggleClass('current-page');
      getListBusiness();
      getListIndrect();
    });
</script>
@endsection