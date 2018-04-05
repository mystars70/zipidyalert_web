<?php
use App\HelperDB;
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
    <title>{{$title}}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{!!url('public/user/')!!}/css/jquery-ui.min.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/bootstrap.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/plugin/select2/css/select2.css" rel="stylesheet" />
    <link href="{!!url('public/plugins/fine-uploader/')!!}/fine-uploader-gallery.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/plugin/colorbox/colorbox.css" rel="stylesheet" />
    <link href="{!!url('public/user/')!!}/plugin/ios-checkbox/iosCheckbox.css" rel="stylesheet" />
    <link href="{!!url('public/user/')!!}/css/loading.css" rel="stylesheet" />
    <!-- Theme CSS -->
    <link href="{!!url('public/user/')!!}/css/register.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/reponsive.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/userManager.css" rel="stylesheet">
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
        ]
        ?>
        var userInfo = <?php echo json_encode($userInfo); ?>;
        var apiKey = "<?php echo env('APIKEY', ''); ?>",
        authDomain = "<?php echo env('AUTHDOMAIN', ''); ?>",
        databaseURL = "<?php echo env('DATABASEURL', ''); ?>",
        projectId = "<?php echo env('PROJECTID', ''); ?>",
        storageBucket = "<?php echo env('STORAGEBUCKET', ''); ?>",
        messagingSenderId = "<?php echo env('MESSAGINGSENDERID', ''); ?>";
    </script>
</head>

<body>

    

    
    
    
    
    @yield('content')
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="modal"></div>
    <a class='popupMessageDetail' href="#detail_message_wrap" style="display: none;"></a>
    <div style="display: none;">
        <div id="fine-uploader-gallery"></div>
        <!---popup detail message----->
        <div id='detail_message_wrap'>
            <div class="popup-content">
                <div class="rows">
                    <div class="col-lg-6">
                        <div class="user-wrap">
                            <img src="<?php echo url('public/user/'); ?>/images/logo.png" />
                            <div class="user-info">
                                <span>Katherine Bryant</span>
                                <span class="date">June 06, 2017 at 4:10 PM</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="address-info">
                            <span>Happy Valley, OR 97086</span>
                            <span class="mile">3 miles</span>
                        </div>
                    </div>
                </div>
                <div class="content-message">
                    <p class="image"><img src="<?php echo url('public/user/'); ?>/images/img.png" /></p>
                    <div class="message">
                        Being the savage's bowsman, that is, the person who pulled the bow-oar in his boat (the second one from forward), it was my cheerful duty to attend upon him while taking that hard-scrabble scramble upon the dead whale's back.
                    </div>
                </div>
                <div class="replay-list">
                    <ul>
                        <li>
                            <div class="avatar">
                                <img src="<?php echo url('public/user/'); ?>/images/logo.png" />
                            </div>
                            <div class="replay-detail">
                            
                                <div class="block-replay-1">
                                    <span class="user-name">Carl Lawson</span>
                                    <span class="date">5:22 PM  June 06 </span>
                                </div>
                                <div class="replay-detail-text">
                                    Vestibulum rutrum quam vitae fringilla tincidunt. Suspendisse nec torto.
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="avatar">
                                <img src="<?php echo url('public/user/'); ?>/images/logo.png" />
                            </div>
                            <div class="replay-detail">
                            
                                <div class="block-replay-1">
                                    <span class="user-name">Carl Lawson</span>
                                    <span class="date">5:22 PM  June 06 </span>
                                </div>
                                <div class="replay-detail-text">
                                    Vestibulum rutrum quam vitae fringilla tincidunt. Suspendisse nec torto.
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="avatar">
                                <img src="<?php echo url('public/user/'); ?>/images/logo.png" />
                            </div>
                            <div class="replay-detail">
                            
                                <div class="block-replay-1">
                                    <span class="user-name">Carl Lawson</span>
                                    <span class="date">5:22 PM  June 06 </span>
                                </div>
                                <div class="replay-detail-text">
                                    Vestibulum rutrum quam vitae fringilla tincidunt. Suspendisse nec torto.
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="replay-messgess-send">
                    <img class="replay-select-img" src="<?php echo url('public/user/'); ?>/images/icon-select-img.png" />
                    <img class="replay-send-message-icon" src="<?php echo url('public/user/'); ?>/images/icon-send-message.png" />
                    <input type="text" placeholder="Reply here" class="message-replay-text" />
                    
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
    @yield('script')
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
    @yield('scriptRun')
    
    
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
                        console.log('111111', actionUpload);
                        if (actionUpload == 'avatar') {
                            $('#avatarFile img').attr('src', baseUrl + '/public/upload/' + responseJSON.image);
                            console.log(actionUpload);
                        } else if (actionUpload == 'cover') {
                            $('#map img').attr('src', baseUrl + '/public/upload/' + responseJSON.image);
                            console.log(actionUpload);
                        }
                    } console.log(baseUrl + '/public/upload/' + responseJSON.image);
                    
                }
            }
        });
    </script>
</body>
</html>