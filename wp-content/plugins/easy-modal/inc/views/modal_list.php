<div class="wrap">
	<?php if(!empty($this->messages)){?>
		<?php foreach($this->messages as $message){?>
		<div class="<?php _e($message['type'],'easy-modal')?>"><strong><?php _e($message['message'],'easy-modal')?>.</strong></div>
		<?php }?>
	<?php }?>
	<?php screen_icon()?>
	<h2>Easy Modal Modals<a class="add-new-h2" href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=new">Add New</a></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<?php if( $modals && count($modals) > 0 ){?>
				<table class="wp-list-table widefat fixed posts" style="width:100%">
					<thead>
						<th width="60">ID</th>
						<th width="120">Class</th>
						<th>Name</th>
						<th>Display Mode</th>
						<th width="100" style="text-align:center">Actions</th>
					</thead>
					<tbody>
					<?php foreach($modals as $id => $name){ $modal = $this->getModalSettings($id);?>
						<tr>
							<td><a href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php esc_attr_e($id)?>"><?php esc_attr_e($id)?></a></td>
							<td><a href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php esc_attr_e($id)?>">eModal-<?php esc_html_e($id)?></a></td>
							<td><a href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php esc_attr_e($id)?>"><?php echo ucfirst(esc_html($name))?></a></td>
							<td><?php if($modal['sitewide']) echo 'Sitewide'; else 'Page / Post';?></td>
							<td align="center">
								<?php if(!in_array($id, array('Login','Register','Forgot'))){?>
								<a style="margin-right:10px;" href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php esc_attr_e($id)?>&action=clone&safe_csrf_nonce_easy_modal=<?php echo wp_create_nonce("safe_csrf_nonce_easy_modal")?>">Clone</a>
								<a style="color:red" href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php esc_attr_e($id)?>&action=delete&safe_csrf_nonce_easy_modal=<?php echo wp_create_nonce("safe_csrf_nonce_easy_modal")?>">Delete</a>
								<?php }?>
							</td>
						</tr>
					<?php }?>
					</tbody>
				</table>
				<?php }else{?>
				<p>To get started click the "add new" button above</p>
				<?php }?>
			</div>
			<div id="postbox-container-1" class="postbox-container">
				<?php require(EASYMODAL_DIR.'/inc/views/sidebar.php')?>
			</div>
		</div>
		<br class="clear"/>
	</div>
</div>