@extends('user.layouts.register')
@section('content')
<section id="register-wrap" class="register-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12 background-warp">
                    <div class="verify-msg">
                        <?php if ($flag) :?>
                            Verification successful, <a href="javascript:void(0)">click here</a> to sign in.
                        <?php else:?>
                            <?php echo $msg?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
<script type="text/javascript">
    var email  = '<?php echo $user->email?>';
    var password = '<?php echo $user->password?>';
  $(document).ready(function(){
    $('.verify-msg a').click(function(){
        $('#loginForm [name="email"]').val(email);
        $('#loginForm [name="password"]').val(password);
        var html = '<input name="verify_user" type="hidden" value="true">';
        $('#loginForm').append(html);
        $('#loginForm').submit();
    });
  });
</script>
@endsection