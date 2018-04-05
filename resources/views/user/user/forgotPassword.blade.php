@extends('user.layouts.register')
@section('content')
<section id="register-wrap" class="register-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12 background-warp">
                    <div class="form-forgot-password-wrap">
                        {{ Form::open(['url' => '#', 'method' => 'post', 'id' => 'forgotPasswordForm', 'class'=> 'form-register']) }}
                            <h3>Forgot Password</h3>
                            <div class="form-input-list">
                                <div class="form-group">
                                    <input name="email" type="text" label="Email" class="validate[funcCall[validateUser]] placeholder-input">
                                </div>
                                <div class="input-group-controll right">
                                    <div class="col-md-12 no-all  btn-submit">
                                        <button id="registerFree" type="submit" class="btn btn-default submit">Send</button>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-register">
                                <div class="col-md-6 no-all center">
                                    <a href="#"><img src="{!!url('public/user/')!!}/images/icon_appstore.png"></a>
                                    </div>
                                    <div class="col-md-6 no-all center">
                                    <a href="#"><img src="{!!url('public/user/')!!}/images/icon_playstore.png"></a>
                                    </div>
                                </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection