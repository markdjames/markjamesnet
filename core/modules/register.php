
<?php
if (empty($_SESSION['userid']) || $is_admin) {
    ?>
    <form id='register' action="" method="post" onsubmit='return false;'>
        
        <label class='label_left'>Firstname<br />
        <input name="firstname" type="text" class='required' value=""  /></label>
        
        <label class='label_right'>Surname<br />
        <input name="surname" type="text" class='required' value=""  /></label>
        <div style='clear:both'></div>
        
        <label>Email<br />
        <input name="register_email" type="text" class='required' value="<?=(isset($_SESSION['register_email']))?$_SESSION['register_email']:"";?>"  /></label>
        <label>Choose Password<br />
        <input name="register_password" type="password" autocomplete="off" /></label>
        <label>Confirm Password<br />
        <input name="register_password_confirm" type="password" autocomplete="off" /></label>
                
        <div style='clear:both; padding:20px 0'>
            <p><strong>Data Protection</strong> <a style='font-size:13px;' rel='data_protection' class='help'>info</a></p>
            <p>Please let us know if you are happy for us to contact you in the following ways:</p>
            <label><input name="mailinglist" type="checkbox" value='1' /> by Email</label>
            <label><input name="post_mailinglist" type="checkbox" value='1' /> by Post</label>
            <label><input name="phone_mailinglist" type="checkbox" value='1' /> by Phone/SMS</label>
            <label style='margin-top:20px;'><input name="share_data" type="checkbox" value='1' /> Are you happy for us to share your data with other like-minded organisations?</label>
        </div>
        
        <!--div style='clear:both; padding:10px; background-color:#ccc;'>
            <img src='<?=BASE?>/images/logos/gift_aid_logo.png' style='float:right' />
            <label><input name="gift_aid" type="checkbox" value='1' /> GIFT AID I am a UK taxpayer</label>
            <p style='font-size:13px;'>Please treat all donations I make from the date of this Declaration until I notify you otherwise as Gift Aid donations.</p>
            <p style='font-size:13px;'>I confirm I have paid or will pay an amount of Income Tax and/or Capital Gains Tax for each tax year (6 April to 5 April) that is at least equal to the amount of tax that all the charities or Community Amateur Sports Clubs (CASCs) that I donate to will reclaim on my gifts for that tax year.</p>
            <p style='font-size:13px;'>I understand that other taxes such as VAT and Council Tax do not qualify. I understand XXX will reclaim 25p of tax for every £1 that I give.</p>
            <p style='font-size:13px;'><em>If your circumstances change and we can no longer claim Gift Aid on your donations, or you change your name or address, please contact us as soon as possible so we can update our records.</em>
            <div style='clear:both'></div>
        </div-->
               
        <p>Before registering please make sure you are aware of our <a href="<?=DIR?>/privacy">privacy policy</a> and <a href="<?=DIR?>/terms_and_conditions">terms and conditions</a>.</p>
                    
        <input type="hidden" name="function" id="function" value="" data-val="user_register" />
        <input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>" />
        <input type="button" value="Register" alt="Register" onclick="$('#function').val($('#function').data('val')); document.getElementById('register').onsubmit=''; checkForm('register');" />
    </form>
    <?php
} else {
    ?>
    <script>
    document.location.href='/';
    </script>
    <?php
}
?>