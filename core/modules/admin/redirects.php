<?php
if ($is_admin) {
	$db->type='site';
	$redirects = $db->select("SELECT * FROM redirects ORDER BY path ASC");
	if (count($redirects)) {
		?>
        <style>
		th, td {
			padding:10px
		}
		tr th {
			text-align:left;
			background-color:#999999;
		}
		td p {
			font-size:13px;
			margin:0;
		}
		tr:hover {
			background-color:#FFC;
		}
		</style>
        <form method="post" action="">
        <table style="width:100%; table-layout:fixed">
        	<tr>
                <td style="width:40%"><input type="text" value="" name="origin" /></td>
                <td style="width:40%"><input type="text" value="" name="destination" /></td>
                <td style="width:20%"><input type="submit" style='margin-top:0' value="Add New Redirect" /></td>
            </tr>
        	<tr>
            	<th style="width:40%"><p>Origin</p></th>
                <th style="width:40%"><p>Destination</p></th>
                <th style="width:20%"></th>
           	</tr>
			<?php
            foreach ($redirects as $redirect) {
                ?>
                <tr>
                    <td style="width:40%"><p><?=$redirect['path']?></p></td>
                    <td style="width:40%"><p><?=$redirect['target']?></p></td>
                    <td style="width:20%"><p><a href="javascript:void(0)" onclick="deleteRecord(<?=$redirect['id']?>, 'redirects')">delete</a></p></td>
                </tr>
                <?php
            }
            ?>
            
        </table>
        <input type='hidden' name='token' value='<?=$_SESSION['token']?>' />
        <input type='hidden' name='function' value='add_new_redirect' />
        </form>
        <?php
	}
} else {
	?>
	<p style='color:red'><em>Access denied</em></p>
    <?php
}