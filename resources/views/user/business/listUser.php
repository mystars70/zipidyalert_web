<?php
use App\HelperDB;
$helpDb = new HelperDB();
?>

<?php if ($users) :?>
	<?php foreach ($users as $user) :?>
		<tr>
			<td>
				<?php if ($user->status == 2 || $user->user_type == 1) :?>
		        	<input class="" type="checkbox" disabled="" />
				<?php else :?>
					<input class="ckb-select" type="checkbox" name="user[<?php echo $user->user_id?>]"/>
				<?php endif;?>
			</td>
	        <td class="user-name">
	        <?php if ($user->avatar && file_exists(env('DIR_UPLOAD_USER').$user->avatar)) :?>
                        <img src="<?php echo url(env('DIR_UPLOAD_USER').$user->avatar)?>">
                    <?php else :?>
                        <?php $helpDb->noImage($user->user_name) ?>
                    <?php endif;?>
	        
	        <?php echo $user->user_name?>
	        </td>
	        <td class="user-date"><?php echo date(config('settings.date_format'), strtotime( $helpDb->printDate($user->created_at) ))?></td>
	        <td class="user-status">
        	<?php if ($user->user_type == 1) :?>
	        	<input type="checkbox" data-user-id="<?php echo $user->user_id?>" class="ios-checkbox-notification" checked disabled=""/>
	        <?php elseif ($user->status == 1) :?>
	        	<input type="checkbox" data-user-id="<?php echo $user->user_id?>" class="ios-checkbox" checked/>
			<?php elseif ($user->status == 0) :?>
				<input type="checkbox" data-user-id="<?php echo $user->user_id?>" class="ios-checkbox" />
			<?php else :?>
				<input type="checkbox" data-user-id="<?php echo $user->user_id?>" data-notification="true" class="ios-checkbox-notification" />
			<?php endif;?>
	        </td>
	    </tr>
	<?php endforeach;?>
<?php endif;?>

