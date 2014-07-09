<?php
if (!empty($_GET['paginate'])) {
	require '../../lib/bootstrap.php';
	?>
    <h1>Users  <span id="sub_title"></span></h1>
    <?php
}
if ($_SESSION['is_admin']) {
	?>
    <p style='position:absolute; top:30px; right:20px;'><a title='edit' onclick="modal(0, 'admin/users/users', 'medium')" rel='modal' href='javascript:void(0)'><img style='display:inline' alt='edit' src='<?=BASE?>/images/icons/edit16.png'> Add new user</a></p>
    <div style='clear:both'></div>
    <?php
	$p = (isset($_GET['p']) && !empty($_GET['p'])) ? strtoupper($_GET['p']) : 'A';
	$u->order = "surname, firstname, email ASC";
	$allusers = $u->getUsers("WHERE surname LIKE '".$p."%'");
	
	$users = array();
	foreach ($allusers as $k=>$user) {
		$address = $user['address'];
		$address .= (!empty($address))?"<br />":"";
		$address .= (!empty($user['city']))?$user['city']:"";
		$address .= (!empty($address))?"<br />":"";
		$address .= (!empty($user['postcode']))?$user['postcode']:"";
		$address .= (!empty($address))?"<br />":"";
		$address .= (!empty($user['country']))?$user['country']:"";

		$users[$k] = array(
			'id' => $user['id'],
			'surname' => $user['surname'],
			'firstname' => $user['firstname'],
			'email' => $user['email'],
			'address' => $address,
		);
	}
	
	$cells = array();
	foreach ($users as $record) {
		$k = $record['id'];
		
		if ($db->checkPermissions('edit_users', $_SESSION['userid'])) {	
			$cells[$k][] = "<a title='edit' onclick=\"modal(".$record['id'].", 'admin/users/users', 'medium')\" rel='modal' href='javascript:void(0)'><img alt='edit' src='".BASE."/images/icons/edit16.png'></a>";
		}
		if ($db->checkPermissions('delete_users', $_SESSION['userid'])) {
			$cells[$k][] = "<a onclick=\"deleteRecord(".$record['id'].", 'users', {refresh:false})\" style='position:relative; top:3px;' title='delete'><img src='".BASE."/images/icons/redcross.png' alt='delete'></a>";
		}
	}

	$table->orderby = 'surname';
	$table->type = 'alpha';
	echo $table->createDataTable($users, array('id','address'), $cells, array('id','surname','firstname','email','address'), 'admin/users');
	
} else {
	?>
	<p style='color:red'><em>Access denied</em></p>
    <?php
}