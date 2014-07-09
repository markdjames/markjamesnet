<?php
ini_set('display_errors', 1);
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<style>
body {
	font-size:16px;
	font-family:Verdana, Geneva, sans-serif;
}
#container {
	width:600px;
	min-height:100px;
	padding:30px;
	border:2px solid #666;
	box-shadow:0 0 10px #999;
	margin:auto;
	margin-top:100px;
	position:relative;
	padding-bottom:70px;
}
li {
	display:none;
}
input[type=submit] {
	display:none;
	position:absolute;
	bottom:20px;
	right:20px;
	-moz-box-shadow: 0px 1px 0px 0px #f29c93;
	-webkit-box-shadow: 0px 1px 0px 0px #f29c93;
	box-shadow: 0px 1px 0px 0px #f29c93;
	background-color:#fe1a00;
	-webkit-border-top-left-radius:37px;
	-moz-border-radius-topleft:37px;
	border-top-left-radius:37px;
	-webkit-border-top-right-radius:0px;
	-moz-border-radius-topright:0px;
	border-top-right-radius:0px;
	-webkit-border-bottom-right-radius:37px;
	-moz-border-radius-bottomright:37px;
	border-bottom-right-radius:37px;
	-webkit-border-bottom-left-radius:0px;
	-moz-border-radius-bottomleft:0px;
	border-bottom-left-radius:0px;
	text-indent:0;
	border:1px solid #d83526;
	color:#ffffff;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	font-style:normal;
	height:41px;
	line-height:41px;
	padding:0 20px;
	text-decoration:none;
	text-align:center;
	text-shadow:1px 1px 0px #b23e35;
	cursor:pointer;
}
input[type=submit]:hover {
	background-color:#ce0100;
}
label {
	display:block;
	width:100%;
	clear:both;
	margin-bottom:20px;
}
input[type=text], input[type=password], select {
	width:100%;
	padding:8px;
	border:2px solid #CCC;
	color:#666;
}
h1, h2 {
	margin-top:0;
}
h2 {
	font-size:22px;
}
</style>
<div id='container'>
	<?php
    /** 
     * Stage 0
     * Check setup
     */
    if (!isset($_POST['function'])) {

		?>
		<h1>CMS Installation</h1>
		<p><strong>What you'll need:</strong></p>
		<ul>
			<li>Your FTP/SFTP details</li>
			<li>An empty database (and the relevent login credentials)</li>
			<li>Just a couple of minutes!</li>
		</ul>
		<form method='post' action=''>
        	<label>How would you like to connect to your server?<br />
            <select name='connection_type'>
            	<option value='FTP'>FTP</option>
                <option value='SFTP'>SFTP (requires OpenSSQL & libssh2 PHP libraries)</option>
          	</select></label>
            
			<input type='hidden' value='get_ftp_details' name='function' />
			<input type='submit' value='Begin Installation' />
		</form>
		<?php
    }
	
	/** 
     * Stage 1
     * Check setup
     */
    if (isset($_POST['function']) && $_POST['function']=='get_ftp_details') {
		
		$root = explode("/", $_SERVER['DOCUMENT_ROOT']);
		$base = end($root);

		if ($_POST['connection_type']=='FTP') {
			?>
			<h2>FTP Details</h2>
			<p>Firstly we need your FTP login details so we can create the nessecary files on your server.</p>
			<p><em>These details will only be used for the duration of the installation process, they will not be stored anywhere on your server.</em></p>
			<form method='post' action=''>
				<label>FTP Host<br />
				<input type='text' value='<?=(isset($_SERVER['SERVER_ADDR']))?$_SERVER['SERVER_ADDR']:"";?>' name='ftp_host' /></label>
				
				<label>Username<br />
				<input type='text' value='' name='ftp_user' /></label>
				
				<label>Password<br />
				<input type='password' value='' name='ftp_pass' /></label>
				
				<label>Root Directory (e.g. 'public_html' or 'httpdocs' etc)<br />
				<input type='text' value='<?=$base.str_replace($_SERVER['DOCUMENT_ROOT'], "", getcwd());?>' name='root_dir' /></label>
				
                <input type='hidden' value='<?=$_POST['connection_type']?>' name='connection_type' />
				<input type='hidden' value='create_directories' name='function' />
				<input type='submit' value='Create Directory Structure' />
			</form>
			<?php  
		} else {
			?>
			<h2>SFTP Details</h2>
			<p>Firstly we need your SFTP login details so we can create the nessecary files on your server.</p>
			<p><em>These details will only be used for the duration of the installation process, they will not be stored anywhere on your server.</em></p>
			<form method='post' action=''>
				<label>SFTP Host<br />
				<input type='text' value='<?=(isset($_SERVER['SERVER_ADDR']))?$_SERVER['SERVER_ADDR']:"";?>' name='sftp_host' /></label>
				
				<label>Username<br />
				<input type='text' value='' name='sftp_user' /></label>
				
				<label>Password<br />
				<input type='password' value='' name='sftp_pass' /></label>
				
				<label>Root Directory (e.g. 'public_html' or 'httpdocs' etc)<br />
				<input type='text' value='<?=getcwd()?>' name='root_dir' /></label>
				
				<input type='hidden' value='<?=$_POST['connection_type']?>' name='connection_type' />
                <input type='hidden' value='create_directories' name='function' />
				<input type='submit' value='Create Directory Structure' />
			</form>
			<?php  
		}
    }

    /** 
     * Stage 2
     * Create directory structure
     */
    if (isset($_POST['function']) && $_POST['function']=='create_directories') {
        $server = (isset($_POST['ftp_host'])) ? $_POST['ftp_host'] : $_POST['sftp_host'] ;
		$user 	= (isset($_POST['ftp_user'])) ? $_POST['ftp_user'] : $_POST['sftp_user'] ;
		$pass 	= (isset($_POST['ftp_pass'])) ? $_POST['ftp_pass'] : $_POST['sftp_pass'] ;

		$dirs = array(	'assets'		=> '777', 
						'assets/images'	=> '777', 
						'assets/files'	=> '777',
						'assets/audio'	=> '777',
						'assets/films'	=> '777',
						'blocks'		=> '654',
						'classes'		=> '654',
						'images'		=> '654',
						'js'			=> '654',
						'lib'			=> '777',
						'modules'		=> '654',
						'processors'	=> '654',
						'sites'			=> '654',
						'widgets'		=> '654'						
					);
		
		
		// try to login
		if ($_POST['connection_type']=='FTP') {
			// set up a connection or die
			$conn_id = ftp_connect($server) or die("Couldn't connect"); 
		
			if (@ftp_login($conn_id, $user, $pass)) {
			if (ftp_chdir($conn_id, $_POST['root_dir'])) {
					$mask = umask(0);
					foreach ($dirs as $dir=>$perm) {
						if (!is_dir($dir)) {
							ftp_mkdir($conn_id, $dir);
							ftp_chmod($conn_id, octdec(str_pad($perm,4,'0',STR_PAD_LEFT)), $dir);
						}
					}			
					umask($mask);
				
                } else { 
					?>
					<p>Couldn't change to root directory</p>
					<?php
				}
			} else {
				?>
				<p>Couldn't connect</p>
				<?php
			}
			ftp_close($conn_id);  
		
		} else {
			$connection = ssh2_connect($server, 22);
			
			ssh2_auth_password($connection, $user, $pass);
			$sftp = ssh2_sftp($connection);

			
			foreach ($dirs as $dir=>$perm) {			
				ssh2_sftp_mkdir($sftp, "/".trim($_POST['root_dir'], "/")."/".$dir);
			}	
			
			$mask = umask(0);
			$permissions_changed = true;
			if (function_exists('ssh2_sftp_chmod')) { 
				foreach ($dirs as $dir=>$perm) {			
					if (!@ssh2_sftp_chmod($sftp, "/".trim($_POST['root_dir'], "/").$dir, octdec(str_pad($perm,4,'0',STR_PAD_LEFT)))) {
						$permissions_changed = false;
						break;
					}
				}	
			} else {
				$permissions_changed = false;
			}
			umask($mask);	
			
			unset($connection);
		}
		?>
               
        <h2>Directories</h2>
        <?php
		if (!$permissions_changed && substr(sprintf('%o', fileperms($_POST['root_dir'].'/lib')), -4) != "0775") {
			?>
            <div style='padding:3px 10px; border:1px solid #f00;'>
	            <p><span style='color:red'>Warning</span>: Unable to change the permissions on your server.</p>
                <p>Please run the following command on your server's command line (sudo if required):</p>
                <code>chmod -R 0775 <?=$_POST['root_dir']?>/*</code>
                <p>Once you have done that click the 'Check Permissions' button.</p>
                <p>
           	</div>
            <form method='post' action=''>
				<input type='hidden' value='<?=$server?>' name='host' />
				<input type='hidden' value='<?=$user?>' name='user' />
				<input type='hidden' value='<?=$pass?>' name='pass' />
				<input type='hidden' value='<?=$_POST['connection_type']?>' name='connection_type' />
				<input type='hidden' value='<?=$_POST['root_dir']?>' name='root_dir' />
				<input type='hidden' value='check_directories' name='function' />
				<input type='submit' value='Check Permissions' />
			</form> 
            <?php			
		} else {
			?>
			<p>Creating directory structure...</p>
			<ul>
				<li>/assets</li>
				<li>/assets/images</li>
				<li>/assets/files</li>
				<li>/assets/audio</li>
				<li>/assets/video</li>
				<li>/blocks</li>
				<li>/classes</li>
				<li>/images</li>
				<li>/js</li>
				<li>/lib</li>
				<li>/modules</li>
				<li>/processors</li>
				<li>/sites</li>
				<li>/widgets</li>
			</ul> 
		   
			<form method='post' action=''>
				<input type='hidden' value='<?=$server?>' name='host' />
				<input type='hidden' value='<?=$user?>' name='user' />
				<input type='hidden' value='<?=$pass?>' name='pass' />
				<input type='hidden' value='<?=$_POST['connection_type']?>' name='connection_type' />
				<input type='hidden' value='<?=$_POST['root_dir']?>' name='root_dir' />
				<input type='hidden' value='enter_database_details' name='function' />
				<input type='submit' value='Next' />
			</form> 
			<?php 
			
			/**
			 * As we have the root directory we can now set the crontab for the search script
			 */
			touch('lib/cron'); 
			chmod('lib/cron', 0777); 
			file_put_contents('lib/cron', '0 * * * * php '.$_POST['root_dir'].'/core/scripts/search.php'); 
			exec('crontab lib/cron');
			unlink('lib/cron');
		}
    }
	
	/** 
     * Stage 2b
     * Create directory structure
     */
    if (isset($_POST['function']) && $_POST['function']=='check_directories') {
		?>               
        <h2>Checking Directories</h2>
        <?php
		if (substr(sprintf('%o', fileperms($_POST['root_dir'].'/lib')), -4) == "0775") {
			?>
            <p>Great, looking good now.</p>
            <form method='post' action=''>
				<input type='hidden' value='<?=$_POST['host']?>' name='host' />
				<input type='hidden' value='<?=$_POST['user']?>' name='user' />
				<input type='hidden' value='<?=$_POST['pass']?>' name='pass' />
				<input type='hidden' value='<?=$_POST['connection_type']?>' name='connection_type' />
				<input type='hidden' value='<?=$_POST['root_dir']?>' name='root_dir' />
				<input type='hidden' value='enter_database_details' name='function' />
				<input type='submit' value='Continue Installation' />
			</form> 
            <?php			
		} else {
			?>
            <p>Sorry, it looks like your file permissions are still incorrectly set.</p>
            
            <form method='post' action=''>
				<input type='hidden' value='<?=$server?>' name='host' />
				<input type='hidden' value='<?=$user?>' name='user' />
				<input type='hidden' value='<?=$pass?>' name='pass' />
				<input type='hidden' value='<?=$_POST['connection_type']?>' name='connection_type' />
				<input type='hidden' value='<?=$_POST['root_dir']?>' name='root_dir' />
				<input type='hidden' value='check_directories' name='function' />
				<input type='submit' value='Try again' />
			</form> 
			<?php 
		}
    }
    
    /** 
     * Stage 3
     * Create database
     */
    if (isset($_POST['function']) && $_POST['function']=='enter_database_details') {
		?>
        <h2>Database Details</h2>
        <p>Now we need to install the database - please make sure you have an empty database before proceeding.</p>
        <form method='post' action=''>
        	<label>Database Name<br />
            <input type='text' value='' name='dbname' /></label>
            
            <label>Database Username<br />
            <input type='text' value='' name='dbuser' /></label>
            
            <label>Database Password<br />
            <input type='password' value='' name='dbpass' /></label>
            
            <label>Database Host<br />
            <input type='text' value='localhost' name='dbhost' /></label>
            
            <input type='hidden' value='<?=$_POST['host']?>' name='host' />
            <input type='hidden' value='<?=$_POST['user']?>' name='user' />
            <input type='hidden' value='<?=$_POST['pass']?>' name='pass' />
            <input type='hidden' value='<?=$_POST['connection_type']?>' name='connection_type' />
            <input type='hidden' value='<?=$_POST['root_dir']?>' name='root_dir' />
            <input type='hidden' value='create_database' name='function' />
            <input type='submit' value='Create Database Tables' />
            <p><em>Please note: Database creation could take a couple of minutes. Please be patient.</em></p>
        </form>
        <?php
    }
	
	/** 
     * Stage 4
     * Create database
     */
    if (isset($_POST['function']) && $_POST['function']=='create_database') {
       	$command = "mysql -u".$_POST['dbuser']." -p".$_POST['dbpass']." -h ".$_POST['dbhost']." -D ".$_POST['dbname']." < db.sql";
      	$output = shell_exec($command);
		
		if (empty($output)) {
			?>
            <h2>Site Settings</h2>
            <p>Now some configuration options - default settings should be auto-filled.</p>
            <form method='post' action=''>
                <label>Website Name<br />
                <input type='text' value='My New Website' name='sitename' /></label>
                
                <label>Website Domain<br />
                <input type='text' value='<?=$_SERVER['HTTP_HOST']?>' name='domain' onchange="this.value=this.value.replace('http://', '').('https://', '');" /></label>
                
                <label>Installation Directory <span style='font-size:12px'>(i.e. path from root directory to installation location)</span><br />
                <input type='text' value='<?=str_replace($_SERVER['DOCUMENT_ROOT'], "", getcwd())?>' name='base' /></label>
                
                <label>Sub-Directory <span style='font-size:12px'>(i.e. if you'd like your site to publicly appear in a sub-directory)</span><br />
                <input type='text' value='' name='dir' /></label>
                
                <input type='hidden' value='<?=$_POST['dbname']?>' name='dbname' />
                <input type='hidden' value='<?=$_POST['dbuser']?>' name='dbuser' />
                <input type='hidden' value='<?=$_POST['dbpass']?>' name='dbpass' />
                <input type='hidden' value='<?=$_POST['dbhost']?>' name='dbhost' />
                <input type='hidden' value='<?=$_POST['host']?>' name='host' />
                <input type='hidden' value='<?=$_POST['user']?>' name='user' />
                <input type='hidden' value='<?=$_POST['pass']?>' name='pass' />
                <input type='hidden' value='<?=$_POST['root_dir']?>' name='root_dir' />
                <input type='hidden' value='<?=$_POST['connection_type']?>' name='connection_type' />
                
                <input type='hidden' value='create_config' name='function' />
                <input type='submit' value='Create Config File' />
            </form>
            <?php
		} else {
			echo "<pre>";
			print_r($output);
			echo "</pre>";
		}
    }
    
    /** 
     * Stage 5
     * Create config file
     */
    if (isset($_POST['function']) && $_POST['function']=='create_config') {

        $_POST['domain'] = str_replace("http://", "", str_replace("https://", "", $_POST['domain']));
		$_POST['base'] = (!empty($_POST['base'])) ? "/".trim($_POST['base'], "/") : "";
		$_POST['dir'] = (!empty($_POST['dir'])) ? "/".trim($_POST['dir'], "/") : "";
        
        $config = '<?php
define("URL", "http://'.$_POST['domain'].'");		// url
define("DOMAIN", "'.str_replace("www.", "", $_POST['domain']).'");			// domain name
define("BASE", "'.$_POST['base'].'");				// sub folder containing site - for assets etc
define("DIR", "'.$_POST['dir'].'");					// sub folder containing site - for links

$dbref1  = "site"; 					// Reference for use in database class - DONT TOUCH
$dbname1 = "'.$_POST['dbname'].'";	// Datebase name
$dbuser1 = "'.$_POST['dbuser'].'";	// Database username
$dbpass1 = "'.$_POST['dbpass'].'";	// Database password

$dbref2  = ""; 						// Reference for use in database class
$dbname2 = "";						// Datebase name
$dbuser2 = "";						// Database username
$dbpass2 = "";						// Database password

$dbhost = "'.$_POST['dbhost'].'";	// Database host';

		$htaccess = 'Options +FollowSymlinks
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} .*jpg$|.*png$|.*jpeg$ [NC]
RewriteRule ^(.*)?(.*)$ '.$_POST['base'].'/core/images/image.php?img=$1&$2 [QSA,L]

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^.]+)?$ '.$_POST['base'].'/index.php?id=$1 [L]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)\.php$ '.$_POST['base'].'/core/$1.php [L]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)\.js$ '.$_POST['base'].'/core/$1.js [L]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)\.css$ '.$_POST['base'].'/core/$1.css [L]';
		
		/**
		 * Convoluted way to get files on to server with correct owner / permissions
		 */
		if ($_POST['connection_type'] == "FTP") {
			$conn_id = ftp_connect($_POST['host']) or die("Couldn't connect to $ftp_server"); 
			ftp_login($conn_id, $_POST['user'], $_POST['pass']);
			ftp_chdir($conn_id, $_POST['root_dir']);
			
			file_put_contents('lib/_config.php', $config);
			chmod('lib/_config.php', 0777);
			ftp_put($conn_id, 'lib/config.php', 'lib/_config.php', FTP_BINARY); 
			ftp_chmod($conn_id, octdec(str_pad('755',4,'0',STR_PAD_LEFT)), 'lib/config.php');
			unlink('lib/_config.php');
			
			file_put_contents('lib/_htaccess', $htaccess);
			chmod('lib/_htaccess', 0777);
			ftp_put($conn_id, '.htaccess', 'lib/_htaccess', FTP_BINARY); 
			ftp_chmod($conn_id, octdec(str_pad('755',4,'0',STR_PAD_LEFT)), '.htaccess');
			unlink('lib/_htaccess');
			
			ftp_close($conn_id);
		} else {
			$connection = ssh2_connect($_POST['host'], 22);
			
			ssh2_auth_password($connection, $_POST['user'], $_POST['pass']);
			$sftp = ssh2_sftp($connection);

			file_put_contents('lib/_config.php', $config);
			chmod('lib/_config.php', 0777);		
			ssh2_scp_send($connection, 'lib/_config.php', "/".trim($_POST['root_dir'], "/")."/lib/config.php", 0755);
			unlink('lib/_config.php');
			
			file_put_contents('lib/_htaccess', $config);
			chmod('lib/_htaccess', 0777);		
			ssh2_scp_send($connection, 'lib/_htaccess', "/".trim($_POST['root_dir'], "/")."/.htaccess", 0755);
			unlink('lib/_htaccess');
			
			unset($connection);			
		}
		
		require 'core/lib/bootstrap.php';
		$db->update('settings', array('name'=>'site-name'), array('value'=>$_POST['sitename']));
		$db->doCommit();
		
		?>
        <h2>Configuration Successful</h2>
        <p>Your config file has been created (find it at 'lib/config.php')</p>
        <form method='post' action=''>
            <input type='hidden' value='install_modules' name='function' />
            <input type='submit' value='Install Core Modules' />
        </form>
        <?php
    }
    
    /** 
     * Stage 6
     * Install modules
     */
    if (isset($_POST['function']) && $_POST['function']=='install_modules') {
        require 'core/lib/bootstrap.php';
       
        $modules = array(	'admin', 
                            'admin/modules', 
                            'admin/redirects',
                            'admin/settings',
                            'admin/users',
                            'forgotten_password',
                            'home',
                            'login',
                            'profile',
                            'register',
                            'reset_password',
                            'search',
                            'sitemap'
                        );
        ?>
        <h2>Module Installation</h2>
        <p>Installing core CMS modules...</p>
        <ul>
			<?php
            foreach ($modules as $module) {
                $parts = explode("/", "/".$module);
                $name = end($parts);
				
				$db->vars['path'] = "/".$module;
				$check = $db->select("SELECT * FROM modules WHERE path=:path");
            	
				if (!count($check)) {
					$values['path'] 	= "/".$module;
					$values['title'] 	= ucwords(str_replace("_", " ", $name));
					$db->insert("modules", $values);
				}
                ?>
                <li><?=$module?></li>
                <?php
            }
			$db->doCommit();
            ?>
        </ul>
        <form method='post' action=''>
            <input type='hidden' value='enter_admin_details' name='function' />
            <input type='submit' value='Next' />
        </form>
        <?php	
		
		/**
		 * Now lets fill in the search table
		 */
		$db->custom("DELETE FROM search");
		$db->custom("ALTER TABLE search AUTO_INCREMENT=0");
		$records = $db->select("SELECT * FROM pages WHERE archived=0");
		$modules = $db->select("SELECT * FROM modules");
		$records = (count($records)) ? array_merge($records, $modules) : $modules;
		
		foreach ($records as $record) {
			unset($v);
			$content = (strlen($record['content'])>250) ? rtrim(substr($record['content'], 0, 250))."..." : $record['content'] ;
			$id = (isset($record['pid'])) ? $record['pid'] : $record['id'] ;
			$v['path'] 			= $db->sqlify(ltrim($record['path'],"/"));
			$v['title'] 		= $db->sqlify($record['title']);
			$v['description'] 	= $db->sqlify(trim(strip_tags($content)));
			$v['data'] 			= $db->sqlify(replace_latin(strip_tags($record['title']." / ".$record['content']." / ".$record['description']." / ".$record['keywords'])));
			$v['type'] 			= $db->sqlify('pages');
			$v['rid'] 			= $db->sqlify($id);
			$db->insert('search', $v);
		}
		$db->doCommit();
    }
    
	/** 
     * Stage 7
     * Create admin user
     */
    if (isset($_POST['function']) && $_POST['function']=='enter_admin_details') {
		?>
        <h2>Create Login</h2>
        <p>Now we need to create the super-user account.</p>
        <form method='post' action=''>
        	<label>Firstname<br />
            <input type='text' value='' name='firstname' /></label>
            
            <label>Surname<br />
            <input type='text' value='' name='surname' /></label>
            
            <label>Email<br />
            <input type='text' value='' name='email' /></label>
            
            <label>Password<br />
            <input type='password' value='' name='password' /></label>
            
            <label>Confirm Password<br />
            <input type='password' value='' name='password_confirm' /></label>
            
            <input type='hidden' value='create_admin' name='function' />
            <input type='submit' value='Create Admin User' />
        </form>
        <?php
    }
	
    /** 
     * Stage 8
     * Create admin user and set permissions
     */
    if (isset($_POST['function']) && $_POST['function']=='create_admin') {
        require 'core/lib/bootstrap.php';
        
		if ($_POST['password']==$_POST['password_confirm']) {
			
			$db->vars['email'] = $_POST['email'];
			$check = $db->select("SELECT * FROM users WHERE email=:email");
			
			if (count($check)) {
				$db->delete('users', 'id', $check[0]['id']);
				$db->doCommit();
			}
			$salt = uniqid(mt_rand(), true);
			
			$user_array['firstname'] 		= $_POST['firstname'];
			$user_array['surname'] 			= $_POST['surname'];
			$user_array['email'] 			= $_POST['email'];
			$user_array['password']			= crypt($_POST['password'], $salt);
			$user_array['salt']				= $salt;
			
			$user_array['last_login'] 		= date('Y-m-d H:i:s');
			$user_array['date_created'] 	= date('Y-m-d H:i:s');
			
			$user_array['permissions'] 		= "5";
			
			$db->insert("users", $user_array);
			$db->doCommit();
			$uid = $db->lastId;
			
			$permissions = $db->select("SELECT * FROM permissions");
			foreach ($permissions as $permission) {
				$values['permission_id'] = $permission['id'];
				$values['user_id'] = $uid;
				$db->insert("permissions_bridge", $values);
			}
			$db->doCommit();
			
			$_SESSION['userid'] = $uid;
			?>
            <h1>Congratulations!</h1>
            <p>Your installation is now complete.</p>
            <p><strong>Please now delete your installation file (install.php)</strong></p>
            <?php
			if (stripos($_SERVER["SERVER_SOFTWARE"], 'nginx')!==false) {
				?>
                <div style='border:1px solid #f00; padding:10px;'>
                	<p>It looks like you might be using Nginx as your web server (good choice!). If so there are a couple more changes you will need to make manually in order to get the most out of your new CMS.</p>
                    <p>Please modify your server config file (usually located in /etc/nginx/sites-available) so it looks something like this (the bits in red are the really important bits! The rest is up to you):</p>
                    <code style='font-size:12px'><pre>
server {
	listen   80;
	server_name  <?=$_SERVER['HTTP_HOST']?> www.<?=$_SERVER['HTTP_HOST']?>;

	access_log /var/log/nginx/<?=$_SERVER['HTTP_HOST']?>_access.log;
	error_log /var/log/nginx/<?=$_SERVER['HTTP_HOST']?>_error.log;

	root   /var/www/<?=$_SERVER['HTTP_HOST']?>;
	index  index.html index.htm index.php;

	location ~ \.php$ {
    	try_files $uri <span style='color:red'>/core$uri</span> =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_read_timeout 900s;
        break;
    }
    <span style='color:red'>
    location / {
    	try_files $uri $uri/ /core$uri /index.php?id=$uri&$args;
    }
    
    location ~* ^(.*\.(png|jpe?g))$ {
        if ($args) {
        	rewrite ^(.+)$ /core/images/image.php?img=$1;
        }
    }
    </span>    
    location ~ /\. {
    	deny all;
    }
}
                    </pre></code>
                </div>
                <?php
			}
			?>
            <form method='post' action='<?=DIR?>/'>
                <input type='hidden' value='enter_admin_details' name='function' />
                <input type='submit' value='Go to your new website' />
            </form>
            <?php
		} else {
			?>
            <p style='color:red'>Your passwords did not match, please go back and try again. (Do not use your browser's back button.)</p>
			<form method='post' action=''>
                <input type='hidden' value='enter_admin_details' name='function' />
                <input type='submit' value='Back' />
            </form>
            <?php
		}
    }
	?>
</div>
<script>
$(document).ready(function() {
	console.log($('li').length);
	if ($('li').length) {
		var i=0;
		$('li').each(function() {
			setTimeout(function(that) { that.fadeIn() }, 300*i, $(this));
			i++;
		});
		setTimeout(function(that) { that.fadeIn() }, 300*i, $('input[type=submit]'));
	} else {
		setTimeout(function(that) { that.fadeIn() }, 1000, $('input[type=submit]'));
	}
});
</script>

