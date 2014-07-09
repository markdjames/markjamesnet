<div id='admin_page_controls'>
	<img onclick="$('#admin_page_controls').hide()" style='cursor:pointer; float:right' src='<?=BASE?>/core/images/icons/cross.png' />
	<p><strong>Admin Controls</strong></p>
    <?php 
	// make sure page is unlocked if lock date over an hour ago
	$p->unlock();
	
	if ($page) { //show edit page if module or dynamic page 
		
		if (isset($page['locked_by']) && $page['locked_by']!=0 && $page['locked_by']!=$_SESSION['userid']) {
			$locked = true;
			$editor = $u->getUser($page['locked_by']);
		} else {
			$locked = false;
		}
			
		if ($db->checkPermissions('edit_pages', $_SESSION['userid']) && !$locked) {
			?>
            <p>
			<a onclick="modal(<?=$page['pid']?>, 'admin/page/settings')">edit page</a> | 
            <a onclick="modal(<?=$page['pid']?>, 'admin/page/content', 'large')">edit content</a> |
			<?php 		
			if (is_numeric($page['pid'])) {
				?>
				<a onclick="modal(<?=$page['pid']?>, 'admin/page/blocks', 'medium', {type:'<?=(!empty($blocktype))?$blocktype:""; ?>'})">edit block(s)</a> | 
				<?php
			}
			?>
			<a onclick="modal('<?=(!empty($page['pid']))?$page['pid']:$url_vars[0];?>', 'admin/page/related', 'auto', {type:'<?=(!empty($module))?$module:'page';?>'})">related content</a>
            </p>
			<?php
		}

	} else { //otherwise prompt to make page 
		$locked = false;
		
   		if ($is_module && (end($url_vars)==$module||empty($url_vars[0])||is_numeric($url_vars[0]))) {
			?>
            <p><strong>Module</strong>: <?=ucwords(str_replace("_", " ", $module))?></p>
            <p>
            <?php
            $mod = $m->getModuleByPath($modpath);

			if ($mod) {
				if (is_file($_SERVER['DOCUMENT_ROOT'].BASE."/lib/forms/admin/modules/".$module.".php") && is_numeric($url_vars[0])) {
					if ($db->checkPermissions('edit_modules', $_SESSION['userid'])) {
						?>
						<a onclick="modal('<?=$url_vars[0]?>', 'admin/modules/<?=$module?>', 'medium')">edit <?=($module!='series')?rtrim($module,"s"):"Series";?></a> |
						<a onclick="modal(<?=$mod['id']?>, 'admin/module/blocks', 'medium', {content_id:<?=$url_vars[0]?>})">edit block(s)</a> |
						<?php
					} 					
				} else {

                    if ($db->checkPermissions('edit_module_pages', $_SESSION['userid'])) {
						?>
                        <a onclick="modal(<?=$mod['id']?>, 'admin/module/settings')">edit module</a> |
                        <a onclick="modal(<?=$mod['id']?>, 'admin/module/blocks', 'medium')">edit block(s)</a> |
                        <a onclick="modal(<?=$mod['id']?>, 'admin/module/content', 'large')">edit content</a> | 
                        <a onclick="modal(<?=$mod['id']?>, 'admin/module/related')">related content</a> | 
					<?php
					} elseif ($db->checkPermissions('edit_modules', $_SESSION['userid'])) {
						?>
                        <a onclick="modal(<?=$mod['id']?>, 'admin/module/content', 'large')">edit content</a>
                        <?php
					}
				} 
			} else {
				if ($db->checkPermissions('create_pages', $_SESSION['userid'])) {
					?>
					<a onclick="modules.install('<?=$modpath?>')">install module</a>
					<?php 
				}
			}
			?>
            </p>
            <?php

		} else {
			if ($db->checkPermissions('create_pages', $_SESSION['userid'])) {
				?>
				<p><a onclick="modal('<?=$_GET['id']?>', 'admin/page/create')">make page</a></p>
				<?php 
			}
		}
	} ?>

    <?php
    if (is_numeric($page['pid']) && $locked!=true) { //only show edit content if dynamic page ?>
        <p>
        <?php if ($db->checkPermissions('restore_pages', $_SESSION['userid'])) { ?>
        <a onclick="modal(<?=$page['pid']?>, 'admin/page/snapshot', 'medium')">restore</a> | 
        <?php } ?>
        <?php if ($db->checkPermissions('create_pages', $_SESSION['userid'])) { ?>
        <a onclick="modal(<?=$page['pid']?>, 'admin/page/duplicate')">duplicate page</a> | 
        <?php } ?>
        <?php if ($db->checkPermissions('move_pages', $_SESSION['userid'])) { ?>
        <a onclick="modal(<?=$page['pid']?>, 'admin/page/move')">move page</a> | 
        <?php } ?>
        <?php if ($db->checkPermissions('delete_pages', $_SESSION['userid'])) { ?>
        <a onclick="modal(<?=$page['pid']?>, 'admin/page/delete')">delete page</a>
        <?php } ?>
        </p>
        <?php
    } elseif ($locked==true) {
		?>
        <p><em>Page currently locked by <?=$editor['firstname']." ".$editor['surname']?></em></p>
        <?php
	}
	
	$_page = (!empty($mod)) ? $mod : $page;

	if ((!empty($_page['modified_by']) && !is_numeric($url_vars[0])) || ($_page['path']=='/concerts' && is_numeric($url_vars[0]))) {
		$editor = $u->getUser($_page['modified_by']);

		?>
        <p><strong>Last editor</strong>:<br /><?=$editor['firstname']." ".$editor['surname']?><br /><?=date('j M Y, H:i', strtotime($_page['last_modified']))?></p>
        <?php
	}
	
	if (!$is_module && 								// if not a module
		count($pages) && 							// if is a page
		($page['published']==0 || 					// not published
		 strtotime($page['publish_date'])>time() || 
		 strtotime($page['expiry_date'])<time())
		 ) { // not published show warning ?>
		<p class='warning'>This page is not currently published</p>
		<?php 
	} 
	?>
    <div style='clear:both; height:10px;'></div>
    <p style='float:right; margin-bottom:0;'><a onclick="$('#logout').submit();">logout</a></p>
    <form id='logout' method='POST' action=''>
        <input type='hidden' name='token' value='<?=$_SESSION['token']?>'>
        <input type='hidden' name='function' value='logout'>
    </form>
    <script>
		$(function() {
			$( "#admin_page_controls" ).draggable({
				start:function() {
					$(this).css('right', 'auto');
				}
			});
		});
	</script>
</div>
