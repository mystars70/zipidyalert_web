<?php
use App\HelperDB;
use Illuminate\Pagination\LengthAwarePaginator;
$helpDb = new HelperDB();

$rootPage = url('user') . '/business/page/';
$businessR = $helpDb->getBusiness(Auth::user()->user_id);
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Face book -->
    <meta property="og:url" content="http://zipidyalert.com/git/zipidy-web/user" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Zipidy Alert" />
    <meta property="og:description" content="Be safe where you “LIVE, WORK & PLAY”" />
    <meta property="og:image" content="http://zipidyalert.com/git/zipidy-web/public/user/images/logo.png" />
    <!-- Twitter Card -->   
    <meta name="twitter:card" value="summary">
    <meta name="twitter:url" content="http://zipidyalert.com/git/zipidy-web/user">
    <meta name="twitter:title" content="Be safe where you “LIVE, WORK & PLAY”">
    <meta name="twitter:description" content="zipidyalert.com">
    <meta name="twitter:image" content="http://zipidyalert.com/git/zipidy-web/public/user/images/logo.png"/>
    <!-- <meta name="twitter:site" content="@VnEnews"> -->
    <!-- <meta name="twitter:creator" content="@VnEnews"> -->
    <title>{{$title}}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{!!url('public/user/')!!}/css/jquery-ui.min.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/fonts/fonts.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/bootstrap.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/plugin/select2/css/select2.css" rel="stylesheet" />
    <link href="{!!url('public/plugins/fine-uploader/')!!}/fine-uploader-gallery.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/plugin/colorbox/colorbox.css" rel="stylesheet" />
    <link href="{!!url('public/user/')!!}/plugin/ios-checkbox/iosCheckbox.css" rel="stylesheet" />
    <link href="{!!url('public/user/')!!}/css/loading.css" rel="stylesheet" />
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/iziToast.css">
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/jquery-confirm.css" type="text/css"/>
    <link href="{!!url('public/user/')!!}/css/font-awesome.min.css" rel="stylesheet">
    <!-- Theme CSS -->
    <link href="{!!url('public/user/')!!}/css/register.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/reponsive.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/common.css" rel="stylesheet">
    @yield('userManager')
    @yield('updateBusiness')
    @yield('updateProfile')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        var baseUrl = '{!!url("/")!!}';
        <?php
        $userInfo = [
            'address' => Session::get('addressFull')
        ];
        ?>
        var userInfo = <?php echo json_encode($userInfo); ?>;
        var apiKey = "<?php echo env('APIKEY', ''); ?>",
        authDomain = "<?php echo env('AUTHDOMAIN', ''); ?>",
        databaseURL = "<?php echo env('DATABASEURL', ''); ?>",
        projectId = "<?php echo env('PROJECTID', ''); ?>",
        storageBucket = "<?php echo env('STORAGEBUCKET', ''); ?>",
        messagingSenderId = "<?php echo env('MESSAGINGSENDERID', ''); ?>",
        pathUser = "<?php echo env('DIR_UPLOAD_USER', ''); ?>",
        pathBusiness = "<?php echo env('DIR_UPLOAD_BUSINESS', ''); ?>",
        userId = "<?php echo Auth::user()['user_id']; ?>",
        userType = "<?php echo Session::get('userType'); ?>",
        pathMessage = "<?php echo env('DIR_UPLOAD_MESSAGE', ''); ?>";
    </script>
</head>

<body class="business-wrap">

    <nav id="mainNav" class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            
            
            <div class="row">
                <div class="col-lg-3">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-header">
                            <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                        </button>
                        <a class="navbar-brand" href="{!!url('user/home')!!}">
                            <img src="{!!url('public/user/')!!}/images/logo.png" alt="logo" data-pin-nopin="true"/>
                            <span class="logo-name">ipidy Alert</span>    
                        </a>
                    </div>
                </div>
                <div class="col-lg-9 search-block">
                    
                    <div class="collapse navbar-collapse" id="menu-header">


                        <ul class="nav navbar-nav">
                            <li>
                                <div class="input-group" id="adv-search">
                                    <input type="text" class="form-control search-input" placeholder="Search for places or business">
                                    <div class="input-group-btn">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-primary btn-search">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="menu-item-right">
                                <a href="#" class="dropdown-toggle header-avatar-wrap" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <?php
                                $avatar = url('public/user/') . '/images/user-64.png';
                                if (Auth::user()->avatar != '') {
                                    $avatar = url(env('DIR_UPLOAD_USER')).'/'.Auth::user()->avatar . '?tp=' . time();
                                }
                                ?>
                                    <img src="<?php echo $avatar ?>" alt="" class="img-rounded img-circle avatar">
                                    <!-- <i class="fa fa-user"></i>  -->
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu profile-menu">
                                    <li>
                                        <a href="<?php echo url('user') . '/notifications' ?>">
                                            <i class="glyphicon glyphicon-notifications glyphicon-p"></i> Notifications 
                                            <span class="counter-notice">{{$helpDb->getCountNotification(Auth::user()->user_id)}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo url('user') . '/profile/update' ?>">
                                            <i class="glyphicon glyphicon-single-user glyphicon-p"></i> Personal Profile
                                        </a>
                                    </li>
                                    <?php if (isset($idBusiness) && isset($permis) && $permis == 1) :?>
                                        <li>
                                            <a href="<?php echo url('user') . '/business/update/' . $idBusiness?>">
                                                <i class="glyphicon glyphicon-cap glyphicon-p"></i> Business Profile
                                                
                                            </a>
                                        </li>
                                    <?php endif;?>
                                    <li>
                                        <a href="<?php echo url('user') . '/logout' ?>" class="logout">
                                            <i class="glyphicon glyphicon-logout glyphicon-p"></i> Logout   
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-item-right">
                                <a href="#" class="menu-profile">
                                    <span class="welcome">Welcome, <strong><?php echo Auth::user()->firstname; ?></strong></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- /.container-fluid -->
    </nav>
    
    
    <div class="content-wrap">
        <div class="row">
            <div class="col-lg-3">
                @include('user/tpl/sidebar')
            </div>
            <div class="col-lg-9">
                <div class="wrap-content">
            <?php if (isset($page) && $page != 'business/update' && $page != 'profile/update' && $page != 'notification'): ?>
                <div class="row header" id="map">
                    <?php
                      // $imgCovert = url('public/user/') . '/images/map.jpg';
                      if (isset($page)) {
                        if ($page == 'profile' && Auth::user()['cover'] != '') {
                            $imgCovert = url(env('DIR_UPLOAD_USER')) . '/' . Auth::user()['cover'];
                        }
                        if ($page == 'business' && isset($permis) && $permis) {
                            if ($businessDetail->cover != '') {
                                $imgCovert = url(env('DIR_UPLOAD_BUSINESS')) . '/' . $businessDetail->cover;
                            }
                            
                        }
                        
                      }
                      ?>
                    <?php if(isset($imgCovert)) :?>
                        <img src="{{$imgCovert}}" />
                        <div class="upload-cover">
                            <a id="coverImg" href="#"><i class="glyphicon glyphicon-cloud"></i>Upload cover</a>
                        </div>
                    <?php else:?>
                        <div id="mapSite" class="top-map"></div>
                    <?php endif;?>
                </div>
                <div class="row bottom-header">
                    <div class="col-lg-8 no-padding-right">
                        <span class="info-addr"><i class="glyphicon glyphicon-place glyphicon-p"></i>
                        <?php if (isset($page) && $page == 'business'):?>
                            <?php echo (($businessDetail->address) ? $businessDetail->address.', ' : '').(($businessDetail->city_name) ? $businessDetail->city_name.', ' : '').$businessDetail->state_code.' '.$businessDetail->zipcode.' '.$businessDetail->country_name?>
                        <?php else:?>
                            {{ Session::get('addressFull') }}
                        <?php endif;?>
                        </a></span>
                    </div>
                    <div class="col-lg-4">
                        <div class="counter-header">
                            <div class="social-share">
                                <a class="fb" href="https://www.facebook.com/sharer/sharer.php?u="><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                                <a class="twitter" href="https://twitter.com/home?status="><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                                <a class="google_plus" href="https://plus.google.com/share?url="><i class="fa fa-google-plus-square" aria-hidden="true"></i></a>
                                <a class="email" href="https://www.facebook.com/sharer/sharer.php?u=http%3A//zipidyalert.com/git/zipidy-web/user"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i></a>
                            </div>
                                <span class="counter-header-share" >Share</span>
                            <?php if (isset($page) && $page == 'business'):?>
                                <span class="counter-header-add-indirect">
                                <?php if (isset($user_exists) && !$user_exists):?>
                                    <i class="glyphicon glyphicon-place glyphicon-p" onclick="registerIndirect()"></i>
                                <?php else:?>
                                    &nbsp;
                                <?php endif;?>
                                </span>
                                <span><i class="glyphicon glyphicon-usert glyphicon-p"></i><?php echo $helpDb->countUserOfBusinessID($urlData[1], 4); ?></span>
                                <span><i class="glyphicon glyphicon-direct-users-active glyphicon-p"></i><?php echo $helpDb->countUserOfBusinessID($urlData[1], 3); ?></span>
                            <?php else:?>
                               <span><i class="glyphicon glyphicon-usert glyphicon-p"></i><?php echo $helpDb->countUserWithCity(Auth::user()->city_id); ?></span>
                                <span><i class="glyphicon glyphicon-cap glyphicon-p"></i><?php echo $helpDb->countBusinessWithCity(Auth::user()->city_id); ?></span>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            <?php endif;?>
                <div class="row cotent-main">
                    <div class="content-data-list">
                        @yield('content')
                    </div>
                    <?php if (isset($page) && $page != 'business/update' && $page != 'profile/update'):?>
                    <div class="col-lg-4 sidebar-right-wrap">
                        <div class="sidebar-right">
                            <div class="title-block title-block-wrap title-block-message">
                            <h2 class="title-block">Places</h2>
                            <span class="counter-header-share invite_place" onclick="addInvite()">Invite</span>
                            </div>
                            <div class="list-data data_right_content">
                              
                              <?php
                               // get list business with country
                              $helpDB = new HelperDB();
                              $listBusiness = $helpDB->getBusinessAccWithCity(Auth::user()->city_id, true);?>

                             @include('user.tpl.rightContent', array('listBusiness' => $listBusiness))
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
                </div>
                <!-- user manager -->
                <?php if (isset($page) && $page == 'business'):?>

                <div class="wrap-user-manager">
                    <div id='add_user_content' style='padding:10px; background:#fff;'>
                    <div class="map-wrap">
                        <div class="popup-content">
                            <div id="tabs" class="ui-tabs ui-corner-all ui-widget ui-widget-content">
                                <div class="header-tab">
                                    <h2 class="title-add_user">Users Management</h2>
                                    <div class="cboxClose">close</div>
                                    <div class="header-tab-wrap">
                                        <ul role="tablist" class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
                                            <li id="alert-m" role="tab" tabindex="0" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab ui-tabs-active ui-state-active" aria-controls="tabs-1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="#tabs-1" role="presentation" tabindex="-1" class="ui-tabs-anchor" id="ui-id-1">Alert Managers</a></li>
                                            <li id="direct-m" role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="tabs-2" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="#tabs-2" role="presentation" tabindex="-1" class="ui-tabs-anchor" id="ui-id-2">Direct Users</a></li>
                                            <li id="indirect-m" role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="tabs-3" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="#tabs-3" role="presentation" tabindex="-1" class="ui-tabs-anchor" id="ui-id-3">Indirect Users</a></li>
                                        </ul>
                                    </div>
                                    <div class="block-search">
                                        <div class="col-lg-12 input-search-user-wrap">
                                            <input type="text" class="input-search-user searchUserPlace" placeholder="Search for user" />
                                            <div id="add-user" >Add User</div>
                                        </div>
                                    </div>
                                    <div class="block-search">
                                        <div class="col-lg-12 input-search-user-wrap text-right">
                                            <div class="deactive-status" >Deactive</div>
                                            <div class="active-status" >Active</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-tab">
                                    {{ Form::open(['url' => '#', 'method' => 'post', 'class' => 'form-status']) }}
                                    <input type="hidden" name="business_id" value="{{$businessDetail->business_id}}">
                                    <input type="hidden" name="status" value="">
                                    <div id="tabs-1" aria-labelledby="ui-id-1" role="tabpanel" class="ui-tabs-panel ui-corner-bottom ui-widget-content" aria-hidden="false" tabindex="1" style="max-height: 60vh; overflow: hidden; outline: none;">
                                        <table class="list-user-popup">
                                        </table>
                                    </div>
                                    <div id="tabs-2" aria-labelledby="ui-id-2" role="tabpanel" class="ui-tabs-panel ui-corner-bottom ui-widget-content" aria-hidden="true" style="display: none;">
                                        <table class="list-user-popup">
                                        </table>
                                    </div>
                                    <div id="tabs-3" aria-labelledby="ui-id-3" role="tabpanel" class="ui-tabs-panel ui-corner-bottom ui-widget-content" aria-hidden="true" style="display: none;">
                                        <table class="list-user-popup">
                                        </table>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p></p>
        </div>
    </footer>
    <div class="modal"></div>
    <div style="display: none;">
        <div id="fine-uploader-gallery"></div>
    </div>
    
    <script type="text/javascript">
    <?php 
    if (!isset($page)) {
        $page = '';
    }
    if (!isset($idBusiness)) {
        $idBusiness = '';
    }
    ?>
        var page = '{{$page}}';
        var idBusiness = '{{$idBusiness}}';
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgxkEK3tumfrZe2-7mMABZY6_Q04QtOCI&callback=initMap&libraries=places"></script>
    <script src="{!!url('public/user/')!!}/js/jquery-3.2.1.min.js"></script>
    <script src="{!!url('public/user/')!!}/js/jquery-ui.min.js"></script>
    <script src="{!!url('public/user/')!!}/js/bootstrap.js"></script>
    <script src="{!!url('public/user/')!!}/plugin/colorbox/jquery.colorbox.js"></script>
    <script src="{!!url('public/user/')!!}/plugin/scroll/jquery.jscroll.js"></script>
    <script src="{!!url('public/user/')!!}/js/jquery-confirm.js" type="text/javascript" charset="utf-8"></script>
    <script src="{!!url('public/user/')!!}/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
    <script src="{!!url('public/user/')!!}/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <script src="{!!url('public/user/')!!}/js/jquery.polymer-form.min.js"></script>
	<script src="{!!url('public/user/')!!}/js/jstz.min.js"></script>
	<script src="{!!url('public/user/')!!}/js/jquery.cookie.js"></script>
    <script src="{!!url('public/user/')!!}/plugin/select2/js/select2.min.js"></script>
    <script src="{!!url('public/user/')!!}/plugin/ios-checkbox/iosCheckbox.js"></script>
    <script src="{!!url('public/user/')!!}/plugin/nicescroll/jquery.nicescroll.js"></script>
    <script src="{!!url('public/plugins/fine-uploader/')!!}/fine-uploader.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.1.2/firebase.js"></script>
    <script src="{!!url('public/user/')!!}/js/firebaseClient.js"></script>
    <script src="{!!url('public/user/')!!}/js/iziToast.js"></script>
    <script src="{!!url('public/user/')!!}/js/main.js"></script>
    <script src="{!!url('public/user/')!!}/js/userManager.js"></script>
    <script src="{!!url('public/user/')!!}/js/updateBusiness.js"></script>
    <script src="{!!url('public/user/')!!}/js/updateProfile.js"></script>
    <script src="{!!url('public/user/')!!}/js/search.js"></script>
    <script src="{!!url('public/user/')!!}/js/backend.js"></script>
    @yield('script')
    
    
    <script type="text/template" id="qq-template-gallery">
        <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div id="fileSelect">Upload a file</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Processing dropped files...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                    <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <div class="qq-thumbnail-wrapper">
                        <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                    </div>
                    <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                    <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                        <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                        Retry
                    </button>
    
                    <div class="qq-file-info">
                        <div class="qq-file-name">
                            <span class="qq-upload-file-selector qq-upload-file"></span>
                            <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                        </div>
                        <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                        <span class="qq-upload-size-selector qq-upload-size"></span>
                        <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                            <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                            <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                            <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                        </button>
                    </div>
                </li>
            </ul>
    
            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Close</button>
                </div>
            </dialog>
    
            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">No</button>
                    <button type="button" class="qq-ok-button-selector">Yes</button>
                </div>
            </dialog>
    
            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cancel</button>
                    <button type="button" class="qq-ok-button-selector">Ok</button>
                </div>
            </dialog>
        </div>
    </script>
    <script>
        var galleryUploader = new qq.FineUploader({
            element: document.getElementById("fine-uploader-gallery"),
            template: 'qq-template-gallery',
            request: {
                endpoint: baseUrl + '/user/upload',
                customHeaders: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                params: {
                    action: function() {
                        return actionUpload;
                    },
                    page: function() {
                        return page;
                    },
                    idBusiness: function() {
                        return idBusiness;
                    },
                    
                }
            },
            thumbnails: {
                placeholders: {
                    waitingPath: baseUrl + '/public/plugins/fine-uploader/placeholders/waiting-generic.png',
                    notAvailablePath: baseUrl + '/public/plugins/fine-uploader/placeholders/not_available-generic.png'
                }
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
            },
            callbacks: {
                onSubmit: function(id, name) {
                    
                    console.log('onSubmit', actionUpload);
                },
                onSubmitted: function(id, name) {
                    console.log('onSubmitted');
                },
                onComplete: function(id, name, responseJSON, maybeXhr) {
                    
                    
                    if (responseJSON.code == 200) {
                        // console.log('111111', actionUpload);

                        if (actionUpload == 'avatar') {
                            $('.logo-img').find('span').remove();
                            if ($(".logo-img").find('img').length == 0) {
                                $(".logo-img").append("<img />")
                            }
                            $('#avatarFile img').attr('src', responseJSON.image + '?tp=' + makeid());
                            location.reload();
                            // console.log(actionUpload);
                        } else if (actionUpload == 'cover') {
                            $('#map img').attr('src', responseJSON.image);
                            // console.log(actionUpload);
                        }
                    }
                    // console.log(responseJSON.image);
                    
                }
            }
        });
    </script>

	<div class="hide">
        <div id="dialog-message-err" title="Error">
    	  <p>
    	    <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
    		Error from system, Please reload page.
    	  </p>
    	</div>
    </div>
</body>
</html>