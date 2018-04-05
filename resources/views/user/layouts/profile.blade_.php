<?php
use App\HelperDB;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{$title}}</title>
 <!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- CSS
  ================================================== -->
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/jquery-confirm.css" type="text/css"/>
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/validationEngine.jquery.css" type="text/css"/>
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/template.css" type="text/css"/>
	<link rel="stylesheet" href="{!!url('public/user/')!!}/css/style.css">
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/zerogrid.css">
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/responsive.css">
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/loading.css">
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/iziToast.css">
    <!-- Fine Uploader Gallery CSS file
    ====================================================================== -->
    <link href="{!!url('public/plugins/fine-uploader/')!!}/fine-uploader-gallery.css" rel="stylesheet">
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
<header>
   <div class="zerogrid">
      <div id="logo" class="col-1-1">
         <img src="{!!url('public/user/')!!}/images/logoweb.png" width="214" height="85">
      </div>
      <form method="GET" action="<?php echo url('user') . '/search' ?>" accept-charset="UTF-8" id="searchForm">
         <fieldset>
            <nav >
               <div class="col-2-3">
                  <a class="togglelogin" href="#">Login</a>
                  <ul class="nav">
                     <li class="col-2-5">
                      
                         <input name="s" type="text"  class="search" placeholder="Search..."/>
                       
                     </li>
                     <li class="col-2-2">
                       <div class="profile"> <i class=" fa fa-user " aria-hidden="true"></i>
                         <div>Welcome, <?php echo Auth::user()->firstname; ?></div>
                       </div>
     
                    </li>
                  </ul>
               </div>
            </nav>
         </fieldset>
      </form>
   </div>
</header>
<div class="container">
  <div class="zerogrid">
    <div class=" row block01 wrap-col2">
      <div class="col-2-2"> <label class="custom-file-upload" id="avatarFile">
      <?php
      $imgAvatar = url('public/user/') . '/images/iconcamera.png';
      if (isset($page)) {
        if ($page == 'profile' && Auth::user()['avatar']) {
            $imgAvatar = url('public/upload') . '/' . Auth::user()['avatar'];
        }
        if ($page == 'business') {
            $imgAvatar = url('public/upload') . '/' . $businessDetail->avatar;
        }
      }
      ?>
        <img src="{{$imgAvatar}}" /></label>
        <?php if (isset($permis) && $permis && isset($page) && $page == 'business'): ?>
            <div class="upload" id="uploadFile"><img src="{!!url('public/user/')!!}/images/iconupload2.gif" /> Upload your logo</div>Company Name
        <?php endif; ?>
        <?php if (isset($page) && $page == 'profile'): ?>
            <div class="upload" id="uploadFile"><img src="{!!url('public/user/')!!}/images/iconupload2.gif" /> Upload your logo</div>Company Name
        <?php endif; ?>
        
    
    
    @include('user/tpl/sidebar')
    
    </div>
      <div class="col-2-3">
    <div id="map" class="mapProfile" style="height: 250px;">
    <?php
      $imgCovert = url('public/user/') . '/images/map.jpg';
      if (isset($page)) {
        if ($page == 'profile' && Auth::user()['cover'] != '') {
            $imgCovert = url('public/upload') . '/' . Auth::user()['cover'];
        }
        if ($page == 'business' && isset($permis) && $permis) {
            $imgCovert = url('public/upload') . '/' . $businessDetail->cover;
        }
        
      }
      ?>
        <img src="{{$imgCovert}}" />
    </div>
    <div id="item" class=" row block01">
      <div class="col-1-6">
        
          <div>
            <i class="fa fa-map-marker" aria-hidden="true"></i>
            {{ Session::get('addressFull') }}
            <?php if (isset($permis) && $permis && isset($page) && $page == 'business'): ?>
                <i id="coverImg" class="fa fa-camera" aria-hidden="true"></i>Upload  cover
            <?php endif; ?>
            <?php if (isset($page) && $page == 'profile'): ?>
                <i id="coverImg" class="fa fa-camera" aria-hidden="true"></i>Upload  cover
            <?php endif; ?>
            
        </div> 
      </div>
      <div class="col-1-7">
        <div class=" right"><i class="fa fa-user" aria-hidden="true"></i>Users {{ Session::get('totalUser') }} - Business {{ Session::get('totalBusiness') }}<br />
        </div>
      </div>
    </div>
    <div class="row block01">
      <div class="col-1-9">
        <div id="content">
            @yield('content')
        </div>
      </div>
      <div class="col-2-4"><img src="{!!url('public/user/')!!}/images/shareFP.jpg" />
        <div id="sidebar2">
          <?php
           // get list business with country
          $helpDB = new HelperDB();
          $listBusiness = $helpDB->getBusinessAccWithCity(Auth::user()->city_id);
          if (!empty($listBusiness)) { 
          ?>
          <h4>
           Nearby Places 
          </h4>
          <ul>
          <?php
            foreach($listBusiness as $item) { 
                ?>
                <li><a href="<?php echo url('user') . '/business/page/' . generateId(($item->business_id + 100)  . '-' . $item->business_id) ?>"><?php echo $item->name; ?></a></li>
                <?php
            }
          ?>
          </ul>
          <?php } ?>
<br clear="all" />
        </div>
      </div>
    </div>
      </div>
    </div>
  </div>
</div>
<div class="modal"></div>
<div style="display: none;">
    <div id="fine-uploader-gallery"></div>
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

<script type="text/javascript" src="{!!url('public/user/')!!}/js/jquery-3.2.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{!!url('public/user/')!!}/js/jquery-confirm.js" type="text/javascript" charset="utf-8"></script>
<script src="{!!url('public/user/')!!}/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="{!!url('public/user/')!!}/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="{!!url('public/user/')!!}/js/script.js"></script>
<script src="{!!url('public/user/')!!}/js/custom-file-input.js"></script>
<script src="{!!url('public/plugins/fine-uploader/')!!}/fine-uploader.js"></script>
<script src="https://www.gstatic.com/firebasejs/4.1.2/firebase.js"></script>
<script src="{!!url('public/user/')!!}/js/firebaseClient.js"></script>
<script src="{!!url('public/user/')!!}/js/iziToast.js"></script>
<script type="text/javascript" src="{!!url('public/user/')!!}/js/main.js"></script>



<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgxkEK3tumfrZe2-7mMABZY6_Q04QtOCI&callback=initMap&libraries=places"></script>
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
