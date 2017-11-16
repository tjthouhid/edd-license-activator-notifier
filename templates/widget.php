<div class="notifier-widget">
	<h2>Notifications</h2>
	<div class="success-result">Successfully Removed Notification.</div>
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th>Sl</th>
				<th>Type</th>
				<th>Email</th>
				<th>Url</th>
				<th>Action</th>
			</tr>
			
		</thead>
		<tbody>
			<?php if($total_data>0):$i=1;foreach($query as $row) : 
			$type=($row->type=='1')?"Site Added":"Site Removed";
			?>
				<tr>
					<td><?php echo $i;?></td>
					<td><?php echo $type;?></td>
					<td><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=download&page=edd-customers&view=overview&id=<?php echo $row->customer_id;?>"><?php echo $row->customer_email;?></a></td>
					<td><?php echo $row->site_url;?></td>
					<td><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=download&page=edd-licenses&view=overview&license=<?php echo $row->license_id;?>#edd-item-tables-wrapper" class="view-licence" title="View Licence Page"><span class="dashicons dashicons-visibility"></span></a><input type="checkbox" class="delete-notification" checked="" data-id="<?php echo $row->n_id;?>"></td>
				</tr>
			<?php $i++;endforeach;else:?>
			<tr>
				<td colspan="4">No New Notification.</td>
			</tr>
		<?php endif; ?>
		</tbody>	
	</table>
</div>
<style type="text/css">
	.notifier-widget h2{text-align: center;font-weight: 700 !important;margin-bottom: 10px !important;}
	.notifier-widget .success-result{color: #ffffff;background-color: #5cb85c;margin: 0px auto;max-width: 260px;display: none;padding: 5px 10px;border-radius: 5px;text-align: center;}
	.notifier-widget table{width: 100%;}
	.notifier-widget table thead tr th{border-bottom: 2px solid #ececec;padding: 8px 0;text-align: center;}
	.notifier-widget table tbody tr td{border-bottom: 1px solid #ececec;padding: 8px 0;text-align: center;}
	.notifier-widget .view-licence{margin-right: 10px;}
	.notifier-widget .delete-notification{border-radius: 5px;}
</style>