<?php
use App\HelperDB;
use Illuminate\Http\Request;

$helpDb = new HelperDB();

$rootPage = url('user') . '/business/page/';
$businessR = $helpDb->getBusiness(Auth::user()->user_id);
?>


<!-- with manager account -->
<?php if (isset($page) && $page == 'business'): ?>
    @section('script')
    var idBusiness = '{{$idBusiness}}';
    @endsection
    <div class="block-logo" id="avatarFile">
        <?php
          $imgAvatar = url('public/user/') . '/images/logo.png';
          $accName = '';
          if (isset($page)) {
            if ($page == 'profile') {
                if (Auth::user()['avatar']) {
                    $imgAvatar = url('public/upload') . '/' . Auth::user()['avatar'];
                }
                $accName = Auth::user()->firstname;
            }
            $nameBusiness = '';
            if ($page == 'business') {
                if ($businessDetail->avatar != '') {
                    $imgAvatar = url(env('DIR_UPLOAD_BUSINESS').$businessDetail->avatar);
                }
                $nameBusiness = $businessDetail->name;
            }
          }
          ?>
        <a href="javascript:void(0)" class="logo-img">
            <?php if ($businessDetail->avatar && file_exists(env('DIR_UPLOAD_BUSINESS').$businessDetail->avatar)) :?>
                <img src="<?php echo $imgAvatar?>">
            <?php else :?>
                <?php $helpDb->noImage($nameBusiness) ?>
            <?php endif;?>
        </a>
        <div class="business-name"><?php echo $nameBusiness; ?></div>
        
        <?php if (isset($permis) && $permis && isset($page) && $page == 'business'): ?>
            <div class="upload-button">
                <a id="uploadFile" href="#"><i class="glyphicon glyphicon-cloud"></i>Upload your logo</a>
            </div>
        <?php endif; ?>
        <?php if (isset($page) && $page == 'profile'): ?>
            <div class="upload-button">
                <a id="uploadFile" href="#"><i class="glyphicon glyphicon-cloud"></i>Upload your logo</a>
            </div>
        <?php endif; ?>
        
    </div>
    <div class="bussiness-counter-wrap">
        <?php
        // get business detail
        $businessDetail = $helpDb->getBusinessById($urlData[1]);
        if (empty($businessDetail)) {
            abort(404);
        }
        // get type of user with business
        $userType = $helpDb->checkPermissWithBusiness(Auth::user()->user_id, $businessDetail->business_id);

        ?>
        <?php 
        if (empty($userType)) {
            $userType= new stdClass();
            $userType->user_type = 5;
        } 
        ?>
        <div class="block-title-p">Users
            <?php if ($userType->user_type == 1): ?>
                <a href="#user_invitation" class="popupInvitationUser" style="display: none"></a>
                <div class="block-title-p-bottom">
                    <a href="javascript:void(0)" class="popupAddUser" onclick="loadPopupUser(0)">Manage</a>
                </div>
            <?php endif; ?>
        </div>
            <div class="block-content">
        <ul>
            <?php
                $managerLink = '#';
                $directLink = '#';
                $indirectLink = '#';
                $codeId = generateId(($businessDetail->business_id + 100)  . '-' . $businessDetail->business_id);
                if ($userType->user_type == 1) {
                    $managerLink = $rootPage . 'alert-manager/' . $codeId;
                    $directLink = $rootPage . 'direct-users/' . $codeId;
                    $indirectLink = $rootPage . 'indirect-users/' . $codeId;
                } else if ($userType->user_type == 2) {
                    $directLink = $rootPage . 'direct-users/' . $codeId;
                    $indirectLink = $rootPage . 'indirect-users/' . $codeId;
                }
            ?>
                <li>
                    <span class="label-n"><i class="glyphicon glyphicon-alert-managers-active glyphicon-p"></i>Alert Managers</span>
                    <span class="nummber-n"><?php echo $helpDb->countUserOfBusinessID($urlData[1], 2); ?></span>
                </li>
                <li>
                    <span class="label-n"><i class="glyphicon glyphicon-direct-users-active glyphicon-p"></i>Direct Users</span>
                    <span class="nummber-n"><?php echo $helpDb->countUserOfBusinessID($urlData[1], 3); ?></span>
                </li>
                <li>
                    <span class="label-n"><i class="glyphicon glyphicon-usert glyphicon-p"></i>Indirect Users</span>
                    <span class="nummber-n"><?php echo $helpDb->countUserOfBusinessID($urlData[1], 4); ?></span>
                </li>
        </ul>
    </div>
</div>
<?php endif; ?>

<!--- with free user -->
<div class="sidebar-left">
    <ul class="nav nav-list">      
        <li class="active menu_home"><a href="<?php echo url('user/home') ?>"><i class="glyphicon glyphicon-homet glyphicon-p"></i> Home</a></li>
        <?php if (!empty($businessR)): ?>
        <li><a href="<?php echo $rootPage . generateId(($businessR->business_id + 100)  . '-' . $businessR->business_id) ?>"><i class="glyphicon glyphicon-place glyphicon-p"></i> My Businesses</a></li>
        <?php endif; ?>
        <li><a href="<?php echo url('user') . '/work-place' ?>"><i class="glyphicon glyphicon-cap-z glyphicon-p"></i> My Places</a></li>
        <li><a href="#"><i class="glyphicon glyphicon-comments glyphicon-p"></i> FAQ's</a></li>
        <li><a href="#"><i class="glyphicon glyphicon-copys glyphicon-p"></i> Legal</a></li>
	</ul>
</div>

<!-- popup user manager -->
<!-- <div style='display:none'>
    <div id='add_user_content' style='padding:10px; background:#fff;'>
        <div class="map-wrap">
            <div class="popup-content">
                <div id="tabs">
                    <div class="header-tab">
                        <h2 class="title-add_user">Users Management</h2>
                        <div class="header-tab-wrap">
                            <ul>
                                <li id="alert-m"><a href="#tabs-1">Alert Managers</a></li>
                                <li id="direct-m"><a href="#tabs-2">Direct Users</a></li>
                                <li id="indirect-m"><a href="#tabs-3">Indirect Users</a></li>
                            </ul>
                        </div>
                        <div class="block-search">
                            <div class="col-lg-12 input-search-user-wrap">
                                <input type="text" class="input-search-user searchUserPlace" placeholder="Search for user" />
                                <div id="add-user" >Add User</div>
                            </div>
                        </div>
                    </div>
                    <div id="tabs-1">
                        <table class="list-user-popup">
                        </table>
                    </div>
                    <div id="tabs-2">
                        <table class="list-user-popup">
                        </table>
                    </div>
                    <div id="tabs-3">
                        <table class="list-user-popup">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
            <!-- popup user invitation -->
            <div class="modal fade" id="user_invitation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel" >Add Users</h4>
                            <h4 class="modal-title" id="sharePlace" style="display: none">Share Place</h4>
                        </div>
                        <div class="modal-body">
                            <div class="block-search">
                            {{ Form::open(['url' => 'user/business/add-user', 'method' => 'post', 'id' => 'addForm']) }}
                            <input type="hidden" name="biz_id" value="">
                            <input type="hidden" name="type" value="">
                                <div class="form-input-list">
                                    <div class="form-group">
                                        <input name="email[]" type="text" label="Email" class="input-user validate[custom[email]] placeholder-input ">
                                    </div>
                                    <div class="email-input-list">
                                    </div>
                                    <div class="add-email-input"><div><span>+</span></div>&nbsp;&nbsp;<span>Add more</span></div>
                                </div>
                            {!! Form::close() !!}
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-default btn-modal-close" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn-invite">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- popup share -->
            <div class="modal fade" id="share_place" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="sharePlace">Share Place</h4>
                        </div>
                        <div class="modal-body">
                            <div class="block-search">
                            {{ Form::open(['url' => 'user/business/add-user', 'method' => 'post', 'id' => 'shareForm']) }}
                            <input type="hidden" name="biz_id" value="">
                            <input type="hidden" name="type" value="">
                            <input type="hidden" name="action" value="share">
                                <div class="form-input-list">
                                    <div class="form-group">
                                        <input name="email[]" type="text" label="Email" class="input-user validate[custom[email]] placeholder-input ">
                                    </div>
                                    <div class="email-input-list">
                                    </div>
                                    <div class="add-email-input"><div><span>+</span></div>&nbsp;&nbsp;<span>Add more</span></div>
                                </div>
                            {!! Form::close() !!}
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-default btn-modal-close" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn-share">Share</button>
                        </div>
                    </div>
                </div>
            </div>
