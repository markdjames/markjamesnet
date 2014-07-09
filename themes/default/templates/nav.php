	<div id='nav_toggle'>
    	<a class='nav_icon' href="javascript:void" onclick="$('nav').toggle()"><img src="<?=BASE?>/core/images/icons/mobile_nav.png" /></a>
        <div style="clear:both"></div>	  
    </div>
    <nav role='navigation'>
    	<a class='nav_icon' href="javascript:void" onclick="$('nav').toggle()"><img src="<?=BASE?>/core/images/icons/nav_cross.png" /></a>
        <div style="clear:right"></div>	  
        
		<?php
		if (isset($_SESSION['userid'])) { ?>
            <ul style='float:right'>
            	<li><a onclick="$('#logout').submit();">Logout</a></li>
           	</ul>
            <form id='logout' method='POST' action=''>
                <input type='hidden' name='token' value='<?=$_SESSION['token']?>'>
                <input type='hidden' name='function' value='logout'>
            </form>
            <?php
		} else {
			?>
            <ul style='float:right'>
            	<li><a href="<?=DIR?>/register">Register</a></li>
                <li><a href="<?=DIR?>/login">Login</a></li>
           	</ul>
            <?php
		}
		?>
        
        <ul>
            <li<?=($path_array[0]=='home')?" class='current'":"";?>><a href="<?=DIR?>/home">Home</a></li>
            <li<?=($path_array[0]=='search')?" class='current'":"";?>><a href="<?=DIR?>/search">Search</a></li>
        </ul>
        
        <div style="clear:both"></div>	  
    </nav>
    
            
    