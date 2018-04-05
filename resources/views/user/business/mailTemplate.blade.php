<div>
	{{$biz_id}}
	<?php echo $biz_id;?>
	{{$email}}
	{{$type}}
	<a href="<?php echo url('user/invitation',['biz_id' => base64_encode($biz_id), 'email' => base64_encode($email), 'type' => base64_encode($type)]);?>">Acept</a>
</div>
