<div class="notifier-widget">
	<div class="notifier-widget-header">
		<h2>Notifications</h2>
		<a href="javascript:void(0);" class="full-screen-icon" title="Click to toggle Fullscreen.">
			
			<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
				<circle style="fill:#324A5E;" cx="256" cy="256" r="256"/>
				<path style="fill:#2B3B4E;" d="M162.109,293.481l-21.035,53.886l-9.997,33.549l131.448,131.001
				c135.83-3.4,245.376-112.599,249.351-248.284L380.923,131.084l-47.17,6.144l-40.272,24.885l27.432,28.229l-21.604,21.752
				l-80.789-81.015l-73.537,22.523l-13.9,64.917l81.31,81.087l-0.064,0.066l0.45,0.45l-22.025,21.783L162.109,293.481z"/>
				<polygon class="cross-svg" points="380.923,218.521 380.923,131.084 293.488,131.082 293.481,162.113 327.945,162.114 
				256.002,234.058 184.051,162.109 218.521,162.109 218.521,131.079 131.084,131.077 131.084,218.519 162.114,218.514 
				162.114,184.056 234.058,256 162.109,327.949 162.109,293.481 131.079,293.481 131.079,380.916 218.514,380.916 218.514,349.886 
				184.056,349.886 256,277.942 327.951,349.893 293.481,349.893 293.481,380.923 380.918,380.923 380.918,293.486 349.887,293.486 
				349.887,327.945 277.942,256 349.893,184.051 349.893,218.521 "/>
				<polygon class="cross-svg" points="256,277.942 327.951,349.893 293.481,349.893 293.481,380.923 380.918,380.923 
				380.918,293.486 349.887,293.486 349.887,327.945 277.942,256 349.893,184.051 349.893,218.521 380.923,218.521 380.923,131.084 
				293.488,131.082 293.481,162.113 327.945,162.114 256.002,234.058 "/>

			</svg>
		</a>
	</div>
	
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
	.notifier-widget{background-color: #ffffff;}
	.notifier-widget h2{text-align: center;font-weight: 700 !important;margin-bottom: 10px !important;}
	.notifier-widget .success-result{color: #ffffff;background-color: #5cb85c;margin: 0px auto;max-width: 260px;display: none;padding: 5px 10px;border-radius: 5px;text-align: center;}
	.notifier-widget table{width: 100%;}
	.notifier-widget table thead tr th{border-bottom: 2px solid #ececec;padding: 8px 0;text-align: center;}
	.notifier-widget table tbody tr td{border-bottom: 1px solid #ececec;padding: 8px 0;text-align: center;}
	.notifier-widget .view-licence{margin-right: 10px;}
	.notifier-widget .delete-notification{border-radius: 5px;}
	.full-screen{position: fixed;top: 30px;right: 0px;bottom: 0px;left: 0px;z-index: 99999;}
	.full-screen-icon{width: 30px;height: 30px;position: absolute;right: 20px;top: 10px;}
	.full-screen-icon:focus{-webkit-box-shadow: inherit;box-shadow: inherit;}
	.full-screen-icon svg .cross-svg{fill: #ffffff;}
	.full-screen-icon:hover svg .cross-svg{fill: #969191;}
</style>