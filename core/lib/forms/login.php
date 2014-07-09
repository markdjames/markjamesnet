<?php
if (!isset($db)) {	
	require_once '../../../core/lib/bootstrap.php';

	if ($_SESSION['mobile']!=1) {
		?>
		<h3>Login</h3>
		<?php 
	}
}
if (empty($_SESSION['userid'])) { 
	?>
    <div id="login_form">
       
       	<div class="login_section" id="login">
        	<?php if ($_SESSION['mobile']!=1) { ?>
        	<!--div style='width:60%; float:left;'-->
            <?php } ?>
			<form action="" method="post">
                <label>Email<br />
                <input name="username" type="text" id="username" tabindex='1' value="<?=(isset($_SESSION['username']))?$_SESSION['username']:"";?>" /></label>
                <span style='float:right; font-size:11px; margin-top:5px'><a style='margin:0' href="<?=DIR?>/forgotten_password">Forgotten Password</a></span>
                <label>Password<br />
                <input id="password" name="password" type="password" tabindex='2' /></label>
                <input type="hidden" name="function" id="function" value="login" />
                <input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>" />
                
                <input type="submit" value="Login" alt="login" tabindex='3' />
                <p style='padding-top:25px;'><a href="<?=BASE?>/register">Create new account</a></p>
            </form>
            <?php if ($_SESSION['mobile']!=1) { ?>
            <!--/div>
            <!--div style='width:10%; float:left; text-align:center'>
                <div style='width:1px; height:60px; background-color:#999; margin:auto'></div>
                <p style='color:#999; margin:0;'>or</p>
                <div style='width:1px; height:60px; background-color:#999; margin:auto'></div>
            </div>
            <div style='width:30%; float:left; padding-top:33px'>
                <p><a href="<?=DIR?>/core/tools/twitteroauth/redirect.php"><img src="<?=BASE?>/images/icons/twitter_login.png" alt="Sign in with Twitter"/></a></p>
                
                <p><a href="<?=$_SESSION['facebook_login_url'];?>"><img src="<?=BASE?>/images/icons/facebook_login.png" alt="Sign in with Twitter"/></a></p>
            </div-->
            <?php } ?>
            <script>
           	$("#username").focus();
			</script>
        </div>
      
        <div style="clear:both"></div>
    </div>
	<?php 
} else { 
	?>
 	<p><em>You are already logged in</em></p>
 	<?php 
}
