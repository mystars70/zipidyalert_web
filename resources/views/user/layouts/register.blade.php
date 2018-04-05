
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
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
    <title>{{$title}}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{!!url('public/user/')!!}/fonts/fonts.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/jquery-ui.min.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="{!!url('public/user/')!!}/css/jquery-confirm.css" type="text/css"/>
    <link href="{!!url('public/user/')!!}/plugin/select2/css/select2.css" rel="stylesheet" />
    <link href="{!!url('public/user/')!!}/plugin/colorbox/colorbox.css" rel="stylesheet" />
    <link href="{!!url('public/user/')!!}/css/validationEngine.jquery.css" rel="stylesheet" />
    <!-- Theme CSS -->
    <link href="{!!url('public/user/')!!}/css/register.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/reponsive.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/common.css" rel="stylesheet">
    <link href="{!!url('public/user/')!!}/css/loading.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        var baseUrl = '{!!url("/")!!}';
    </script>
</head>

<body class="register">
    
    <nav id="mainNav" class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            
            
            <div class="row">
                <div class="col-lg-3">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-header">
                            <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                        </button>
                        <a class="navbar-brand" href="index.html">
                            <img src="{!!url('public/user/')!!}/images/logo.png" alt="logo" data-pin-nopin="true"/>
                            <span class="logo-name">ipidy Alert</span>    
                        </a>
                    </div>
                </div>
                <div class="col-lg-9">
                    
                    
                    
                    
                    <div class="collapse navbar-collapse" id="menu-header">

                        {{ Form::open(['url' => 'user/login', 'method' => 'post', 'id' => 'loginForm']) }}
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <div class="form-login">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="sizing-addon2">
                                            <img src="{!!url('public/user/')!!}/images/icon_loginemail.png">
                                        </span>
                                        <input name="email" type="text" class="form-control validate[required, custom[email]]" placeholder="Email" aria-describedby="sizing-addon2">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon" id="sizing-addon2">
                                        <img src="{!!url('public/user/')!!}/images/icon_loginpass.png">
                                        </span>
                                        <input name="password" type="password" class="form-control validate[required]" placeholder="Password" aria-describedby="sizing-addon2">
                                    </div>
                                    <div class="login-submit"><button type="submit" class="btn btn-login">Sign in</button></div>
                                    
                                </div>
                                <p class="forget-div"><a class="forget-user-link" href="{{url('user/forgot-password')}}">
                                        <span>Forgot Password?</span>
                                    </a>
                                </p>
                            </li>
                            
                        </ul>
                        {!! Form::close() !!}
                    </div>
                    
                    
                    
                    
                    
                </div>
            </div>
            
        </div>
        <!-- /.container-fluid -->
    </nav>
    @yield('content')
    <div style='display: none;'>
        <a class='mapLink' href="#map_content" style="display: none;"></a>
        <a class='thankLink' href="#thank_content" style="display: none;"></a>
        <a class='user_thankLink' href="#user_thank_content" style="display: none;"></a>
        <a class='termLink' href="#term_content" style="display: none;"></a>
        
            <div id='map_content' style='padding:10px; background:#fff;'>
                <div class="map-wrap terms-wrap">
                    <h2>Confirm Location</h2>
                    <span class="addressLabel"></span>
                    <div id="mapSite" style="width: 600px; height: 250px;"></div>
                    <div class="group-button-terms">
                        <button id="mapConfirm" class="btn-accept">Confirm</button>
                    </div>
                 </div>
            </div>
            <div id='thank_content' style='padding:10px; background:#fff;'>
                <div class="map-wrap terms-wrap">
                    <h2 class="title-thank">Thank you!</h2>
                    
                    <p class="icon-thank"><img src="{!!url('public/user/')!!}/images/icon-thanks.png" /></p>
                    <p class="thank-msg"><span>Thank you! Please check your email to complete the steps to registration.</span></p>
                    <div class="group-button-terms">
                        <button class="btn-accept" class="start_start">Check email</button>
                    </div>
                 </div>
            </div>
            <div id='user_thank_content' style='padding:10px; background:#fff;'>
                <div class="map-wrap terms-wrap">
                    <h2 class="title-thank">Thank you!</h2>
                    
                    <p class="icon-thank"><img src="{!!url('public/user/')!!}/images/icon-thanks.png" /></p>
                    <p class="thank-msg"><span>Thank you for being a part of safety network and keep each other safe where you Live, Work & Play!</span></p>

                    <div class="group-button-terms">
                        <button class="btn-accept" class="start_start">Start Using</button>
                    </div>
                 </div>
            </div>
            <div id='term_content' style='padding:10px; background:#fff;'>
                <div class="map-wrap">
                    <h2 class="title-term">User License Agreement</h2>
                    
                    <p class="sub-title-term">Everything you need to know, all in one place.</p>
                    <p class="term-msg">
                        <span>
                        In proprietary software, an end-user license agreement (EULA) or software license agreement is the contract between the licensor and purchaser, establishing the purchaser's right to use the software. The license may define ways under which the copy can be used, in addition to the automatic rights of the buyer including the first sale doctrine and 17 U.S.C. § 117 (freedom to use, archive, re-sale, and backup).
                        <br/><br/>
                        Many form contracts are only contained in digital form, and only presented to a user as a click-through where the user must "accept". As the user may not see the agreement until after he or she has already purchased the software, these documents may be contracts of adhesion.
                        <br/><br/>
                        Software companies often make special agreements with large businesses and government entities that include support contracts and specially drafted warranties.
                        <br/><br/>
                        Some end-user license agreements accompany shrink-wrapped software that is presented to a user sometimes on paper or more usually electronically, during the installation procedure. The user has the choice of accepting or rejecting the agreement. The installation of the software is conditional to the user clicking a button labelled "accept". See below.
                        <br/><br/>
                        Many EULAs assert extensive liability limitations. Most commonly, an EULA will attempt to hold harmless the software licensor in the event that the software causes damage to the user's computer or data, but some software also proposes limitations on whether the licensor can be held liable for damage that arises through improper use of the software (for example, incorrectly using tax preparation software and incurring penalties as a result). One case upholding such limitations on consequential damages is M.A. Mortenson Co. v. Timberline Software Corp., et al. Some EULAs also claim restrictions on venue and applicable law in the event that a legal dispute arises.
                        <br/><br/>
                        In disputes of this nature in the United States, cases are often appealed and different circuit courts of appeal sometimes disagree about these clauses. This provides an opportunity for the U.S. Supreme Court to intervene, which it has usually done in a scope-limited and cautious manner, providing little in the way of precedent or settled law
                        </span>
                    </p>
                    <div class="group-button-terms">
                        <div class="btn-decline" id="btn-term-decline">Decline</div>
                        <button class="btn-accept" data-action="<?php echo isset($action) ? $action: ''?>" id="btn-term-accept">Accept</button>
                    </div>
                 </div>
            </div>
    </div>
    <footer>
        <div class="container footer-wrap">
                <div class="menu-footer"><a href="#">Help</a> <a href="#">Policy</a> </div>
                <span class="copy-right">
                -   © 2017 Zipidy All rights reserved.</span>
        </div>
    </footer>
    <div class="modal"></div>
    <script src="{!!url('public/user/')!!}/js/jquery-3.2.1.min.js"></script>
    <script src="{!!url('public/user/')!!}/js/bootstrap.js"></script>
    <script src="{!!url('public/user/')!!}/js/jquery-ui.min.js"></script>
    <script src="{!!url('public/user/')!!}/js/jquery.polymer-form.min.js"></script>
	<script src="{!!url('public/user/')!!}/js/jstz.min.js"></script>
	<script src="{!!url('public/user/')!!}/js/jquery.cookie.js"></script>
    <script src="{!!url('public/user/')!!}/plugin/select2/js/select2.min.js"></script>
    <script src="{!!url('public/user/')!!}/plugin/colorbox/jquery.colorbox.js"></script>
    <script src="{!!url('public/user/')!!}/js/jquery-confirm.js" type="text/javascript" charset="utf-8"></script>
    <script src="{!!url('public/user/')!!}/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
    <script src="{!!url('public/user/')!!}/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <script src="{!!url('public/user/')!!}/js/main.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgxkEK3tumfrZe2-7mMABZY6_Q04QtOCI&callback=initMap&libraries=places"></script>
    @yield('script')
</body>
</html>