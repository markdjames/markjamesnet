<?php
$aggregationblock = new AggregationBlock();
$aggregationblock->block = $block;

switch ($block[0]['type']) {
	case 'blog': // blog
		$aggregationblock->display_date = true;
		break;
	default:
		break;
}
if ($is_admin && $block[0]['orderby']=='manual') {
	if (isset($_GET['admin_sort']) && $_GET['admin_sort']==1) {
		echo "<button style='float:right' onclick=\"window.location.href='".BASE."/".$_GET['id']."'\">save order</button>";
	} else {
		echo "<button style='float:right' onclick=\"window.location.href='".BASE."/".$_GET['id']."?admin_sort=1'\">sort order</button>";
	}
}

echo $aggregationblock->display();

// add javascript to sort aggregation if in correct mode
if (isset($_GET['admin_sort']) && $_GET['admin_sort']==1 && $is_admin) {
	?>
	<script>
	$(function() {
		$( "#sortable" ).sortable({
			stop:function(event, ui) {
				$.ajax({
					type:'POST',
					url:BASE+'/processors/admin/blocks/aggregation_sort.php',
					data:{
						order:$( "#sortable").sortable( "serialize" ),
						function:'sort_aggregation',
						id:<?php echo $block[0]['id']; ?>
					},
					success:function(data) {
						//alert(data);
					}
				});
			}
		});
		$( "#sortable" ).disableSelection();
	});
	</script>
   	<?php
}