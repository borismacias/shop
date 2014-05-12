<div class="wrap">
	<h2>Image Mapper
			<a href="<?php echo admin_url( "admin.php?page=imagemapper_edit" ); ?>" class="add-new-h2">Add New</a>
	</h2>
<?php

?>


<table class="wp-list-table widefat fixed">
	<thead>
		<tr>
			<th width="5%">ID</th>
			<th width="30%">Name</th>
			<th width="60%">Shortcode</th>
			<th width="20%">Actions</th>					
		</tr>
	</thead>
	
	<tfoot>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Shortcode</th>
			<th>Actions</th>					
		</tr>
	</tfoot>
	
	<tbody>
		<?php 
			global $wpdb;
			$prefix = $wpdb->base_prefix;

			if(array_key_exists('action', $_GET) && $_GET['action'] == 'delete') {
				$wpdb->query('DELETE FROM '. $prefix . 'image_mapper WHERE id = '.$_GET['id']);
			}
			$mappers = $wpdb->get_results("SELECT * FROM " . $prefix . "image_mapper ORDER BY id");
			if (count($mappers) == 0) {
				echo '<tr>'.
						 '<td colspan="100%">No instances of Image Mapper found.</td>'.
					 '</tr>';
			} else {
				$tname;
				foreach ($mappers as $mapper) {
					$mname = $mapper->name;
					if(!$mname) {
						$mname = 'Image Mapper #' . $mapper->id . ' (untitled)';
					}
					echo '<tr>'.
							'<td>' . $mapper->id . '</td>'.						
							'<td>' . '<a href="' . admin_url('admin.php?page=imagemapper_edit&id=' . $mapper->id) . '" title="Edit">'.$mname.'</a>' . '</td>'.
							'<td> [image_mapper id="' . $mapper->id . '"]</td>' .		
							'<td>' . '<a href="' . admin_url('admin.php?page=imagemapper_edit&id=' . $mapper->id) . '" title="Edit this item">Edit</a> | '.									  
								  '<a href="' . admin_url('admin.php?page=imagemapper&action=delete&id='  . $mapper->id) . '" title="Delete this item" >Delete</a>'.
							'</td>'.														
						'</tr>';
				}
			}
		?>
		
	</tbody>		 
</table>
<div style="margin-top:20px;">

<h2>Step by step:</h2>
<ul>
	<li><h3>1. Click on "Add New button"</h3></li>
	<li><h3>2. Setup your Image Mapper - click Change to set up map image, then click anywhere on it to add pins - and click Save</h3></li>
	<li><h3>3. Copy "shortcode" from table and use it in your post or page. (for adding Image Mapper into .php parts of template use it like this "&lt;?php echo do_shortcode('[image_mapper id="X"]'); ?&gt;" where X is id of your mapper)</h3></li>

</ul>
</div>
</div>
<?php

?>