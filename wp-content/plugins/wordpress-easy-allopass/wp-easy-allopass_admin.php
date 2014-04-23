<?php
global $wpdb;
$wpdb->flush();
$btn_retour="";
if (isset($_GET['edit']) and $_GET['edit'] != "")
	{
	$re_ = $wpdb->get_row("SELECT * FROM ".TBL_PROD." WHERE id='".$_GET['edit']."';");
	$id_allopass = $re_->id_prod;
	$txt_allopass = stripslashes($re_->prod_descr);
	$btn_action = __( 'Save Changes', 'wp-easy-allopass' );
	$titre_box = __( 'Edit product', 'wp-easy-allopass' );
		if (http_build_query($_GET) != "")
		{
		$url_action = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&act=save';
		$g__ = $_GET;
		unset($g__["edit"]);
		$url_cancel= WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($g__);
		$btn_cancel= "<input class='button-secondary' type='button' name='cancel' value='".__( 'Cancel', 'wp-easy-allopass' )."' onClick='window.location=\"".$url_cancel."\";' />";
		$btn_retour= "<input class='button-primary' type='button' name='retour' value='".__( 'Return to setup', 'wp-easy-allopass' )."' onClick='window.location=\"".$url_cancel."\";' />";
		}
		else
		{
		$url_action = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?act=save';
		$url_cancel= WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$btn_cancel= "<input class='button-secondary' type='button' name='cancel' value='".__( 'Cancel', 'wp-easy-allopass' )."' onClick='window.location=\"".$url_cancel."\";' />";
		}
	}
if (isset($_GET['stats']))
	{
		$g__ = $_GET;
		unset($g__["stats"]);
		$url_retour= WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($g__);
		$btn_retour= "<input class='button-primary' type='button' name='retour' value='".__( 'Return to setup', 'wp-easy-allopass' )."' onClick='window.location=\"".$url_retour."\";' />";
	}	
if (isset($_GET['del']))
	{
	$re_ = $wpdb->get_row("SELECT * FROM ".TBL_PROD." WHERE id='".$_GET['del']."';");
	$id_allopass = $re_->id_prod;
	$txt_allopass = stripslashes($re_->prod_descr);
	$btn_action = __( 'Delete product', 'wp-easy-allopass' );
	$titre_box = __( 'Delete product', 'wp-easy-allopass' );
	$disabled='readonly=readonly';
		if (http_build_query($_GET) != "")
		{
		$g__ = $_GET;
		unset($g__["del"]);
		$url_action = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($g__);
		$url_cancel= WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($g__);
		$btn_cancel= "<input class='button-secondary' type='button' name='cancel' value='".__( 'Cancel', 'wp-easy-allopass' )."' onClick='window.location=\"".$url_cancel."\";' />";
		$btn_retour= "<input class='button-primary' type='button' name='retour' value='".__( 'Return to setup', 'wp-easy-allopass' )."' onClick='window.location=\"".$url_cancel."\";' />";
		$hidden= "<input type=hidden name=del value=".$_GET['del'].">";
		}
		else
		{
		$url_action = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$url_cancel= WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$btn_cancel= "<input class='button-secondary' type='button' name='cancel' value='".__( 'Cancel', 'wp-easy-allopass' )."' onClick='window.location=\"".$url_cancel."\";' />";
		}	
	}
		
function CreerLiens($lien, $debut, $nbr_par_page, $id_count)
{
    echo "<center><div class='tablenav-pages'>";
    if ($id_count >= ($nbr_par_page + 1))
	{
        // liens vers pages precedentes/suivantes
        if ($debut - $nbr_par_page >= 0) {
            echo " <a href='$lien" . 0 . "'>&lt;&lt;</a>\n ";
            echo " <a href='$lien" . ($debut - $nbr_par_page) . "'>&lt;</a> ";
        } 


        $p = 1;
        for ($i = 0 ;
            $i < $id_count ;
            $i += $nbr_par_page) {
            if (($debut / $nbr_par_page) == ($p-1)) echo " <b>$p</b> ";
            else echo " <a href='$lien$i'>$p</a>\n ";
            $p++;
        } 


        if ($debut + $nbr_par_page < $id_count) {
            echo " <a href='$lien" . ($debut + $nbr_par_page) . "'>&gt;</a> ";
            $pos = ($id_count - ($id_count % $nbr_par_page));
            if (($id_count % $nbr_par_page) == 0) $pos = $pos - $nbr_par_page;
            echo " <a HREF='$lien$pos'>&gt;&gt;</a>\n ";
        } 
        echo "\n<br>";
    } 
    echo "</div></center>";
}
if (isset($_GET['debut'])){$debut = $_GET['debut'];}
if (isset($_GET['debus'])){$debus = $_GET['debus'];}
if (empty($debut)) $debut = 0;
if (empty($debus)) $debus = 0;

foreach($_GET AS $key=>$value)
{
	if ( $key == 'debut' or $key == 'debus') 
		{
			unset($_GET[$key]);
		}
}

if (http_build_query($_GET) != "")
	{
	$lien  = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&debut=';
	$lien2 = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&debus=';
	}
	else
	{
	$lien  = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?debut=';
	$lien2 = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?debus=';
	}
	
if (http_build_query($_GET) != "")
	{
	$linkfr  = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&lang=fr';
	$linken  = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($_GET).'&lang=en';
	}
	else
	{
	$linkfr  = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?lang=fr';
	$linken  = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?lang=en';
	}
		
$nbr_par_page = 10;
$id_count = 0;
?>
<div class='wrap'>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" valign="middle"><div id="icon-options-general" class="icon32"></div>
    <h2><?php _e( 'WP Easy Allopass', 'wp-easy-allopass' ) ?></h2></td>
  </tr>
<?php if ($btn_retour!="")
 {
?>
  <tr>
    <td colspan="2" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" valign="middle"><?php echo $btn_retour ?></td>
  </tr>
<?php
 }
?>  
<?php if (!isset($_GET["stats"]))
{  
?> 
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="534">
	<div align="left">
	<form method=post action="<?php echo $url_action ?>"><?php echo $hidden ?>
	<table width="534" class="widefat">
	<thead>
      <tr>
        <th><div align="left"><strong><?php echo $titre_box ?></strong></div></th>
        <th><div align="right">&nbsp;</div></th>
      </tr>
    </thead>
	<tbody>
      <tr>
        <td width="25%"><div align="left"><?php _e( 'Your product Id', 'wp-easy-allopass' ) ?>: </div></td>
        <td width="75%"><div align="left"><input type=text name=id_allopass value="<?php echo $id_allopass ?>" style='width:200px' <?php echo $disabled ?>></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"><em> <?php _e( 'Id Product available on', 'wp-easy-allopass' ) ?> <a href="http://<?php echo $lang ?>.allopass.com/advert?from=sponsorship&target=1605852" target="_blank"><?php _e( 'your allopass account', 'wp-easy-allopass' ) ?></a>.<br />
  <?php _e( 'Example ', 'wp-easy-allopass' ) ?>: </em><font color='#000080'>123456</font>/<font color='#008000'>789012</font>/<font color='#FF0000'>3456789</font> &lt;&lt; <a href="<?php echo URL_HELP.$lang."/" ?>" target="_blank"><?php _e( 'Need Help?', 'wp-easy-allopass' ) ?></a></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"><?php _e( 'No allopass account?', 'wp-easy-allopass' ) ?> <a href="http://<?php echo $lang ?>.allopass.com/advert?from=sponsorship&target=1605852" target="_blank"><?php _e( 'Click here', 'wp-easy-allopass' ) ?></a>.</div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div align="left"><?php _e( 'Description', 'wp-easy-allopass' ) ?>: </div></td>
        <td><textarea name=txt_allopass style='width:95%;height:60px;text-weight:bolder' <?php echo $disabled ?>><?php echo $txt_allopass?></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"><em><?php _e( 'Default text that will be displayed at the left of the allopass button describing your protected content.', 'wp-easy-allopass' ) ?></em></div></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td><div align="left"><input class=button-primary type=submit value="<?php echo $btn_action ?>"><?php echo $btn_cancel ?></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><em><?php _e( '<b>Note</b>: When you add new product on your allopass account, always set as return URL', 'wp-easy-allopass' ) ?>: <font color="#FF0000"><strong>&quot;<?php echo get_bloginfo('siteurl'); ?>&quot;</strong></font>. <?php _e( 'After purchase, the WEA plugin will automatically redirect the user to the page that he was visiting before.', 'wp-easy-allopass' ) ?></em></td>
        </tr>
    </tbody>  
    </table>
	</form>
	</div></td>
    <td><div align="left"><img src="<?php echo URL_WEA ?>image/allo<?php echo $lang ?>.gif" width="534" height="289" /></div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<?php
} 
else
{
$re_ = $wpdb->get_row("SELECT * FROM ".TBL_PROD." WHERE id='".$_GET['stats']."';");
?>
  
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
	<div align="left">
	<table class="widefat" width="100%">
	  <thead>
	    <tr>
          <th colspan="6"><div align="left"><strong><?php _e( 'Product Sales Statistics', 'wp-easy-allopass' ) ?> </strong>(<?php _e( 'Ref ', 'wp-easy-allopass' ) ?> : <?php echo $re_->id_prod ?>)</div></th>
        </tr>
        <tr>
          <th><div align="left"><?php _e( 'N&deg;', 'wp-easy-allopass' ) ?></div></th>
          <th><div align="left"><?php _e( 'Allopass code', 'wp-easy-allopass' ) ?></div></th>
          <th><div align="left"><?php _e( 'Date', 'wp-easy-allopass' ) ?></div></th>
          <th><div align="center"><?php _e( 'Post Id', 'wp-easy-allopass' ) ?></div></th>
          <th width="180"><div align="center"><?php _e( 'Status (O=Ok)', 'wp-easy-allopass' ) ?></div></th>
          <th width="50">&nbsp;</th>
        </tr>
	  </thead>
	  <tbody>
	  <?php
		$wpdb->flush();
		$id_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM ".TBL_STAT." WHERE id = %d" , $_GET["stats"] ) );
		if ($id_count > 0)
		{
			// Manage link admin
			$get___ = $_GET;
			foreach($get___ AS $key=>$value)
			{
				if ($key == 'edit' or $key == 'stats' or $key == 'del') 
					{
						unset($get___[$key]);
					}
			}
			
			if (http_build_query($get___) != "")
				{
				$link = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($get___).'&';
				}
				else
				{
				$link = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?';
				}
		
			$row = $wpdb->get_results("SELECT * FROM ".TBL_STAT." WHERE id = '".$_GET["stats"]."' LIMIT ".$debus.",".$nbr_par_page."");
			foreach ($row as $row) 
			{
			// Date
			$Y__ = substr($row->date, 0, 4);
			$m__ = substr($row->date, 4, 2);
			$d__ = substr($row->date, 6, 2);
			$date__ = $d__."/".$m__."/".$Y__;
		    ?>
			<tr>
			  <td><div align="left"><?php echo $row->id_pmt ?></div>
			    <div align="center"></div></td>
			  <td><div align="left"><?php echo $row->codes ?></div></td>
			  <td><div align="left"><?php echo $date__ ?></div></td>
			  <td><div align="center"><a href="<?php echo get_permalink($row->post) ?>" target="_blank" title="<?php echo get_the_title($row->post) ?>"><?php echo $row->post  ?></a></div></td>
			  <td width="180"><div align="center"><?php echo $row->stats ?></div>
			    <div align="center"></div></td>
			  <td width="50"></td>
			</tr>
		    <?php 
			}
		}
		else
		{
		    ?>
			<tr>
			  <td colspan="6"><div align="center"><br>
			  <?php _e( 'There are currently no sales for this product', 'wp-easy-allopass' ) ?><br>
			  &nbsp;</div></td>
			</tr>
		    <?php 
	    }
	  ?>		
	  		<tr>
			  <td colspan="6">
			  <?php CreerLiens($lien2, $debus, $nbr_par_page, $id_count); ?>			  </td>
			</tr>
	  </tbody>
      </table>
	</div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<?php
}
?>    
  <tr>
    <td colspan="2">
	<div align="left">
	<form method=post>
	  <table class="widefat" width="100%">
	  <thead>
	    <tr>
          <th colspan="7"><div align="left"><strong><?php _e( 'Your products list', 'wp-easy-allopass' ) ?></strong></div></th>
        </tr>
        <tr>
          <th><?php _e( 'id', 'wp-easy-allopass' ) ?></th>
          <th width="160"><?php _e( 'Product Id', 'wp-easy-allopass' ) ?></th>
          <th><?php _e( 'Description', 'wp-easy-allopass' ) ?></th>
          <th><?php _e( 'Last update', 'wp-easy-allopass' ) ?></th>
          <th><div align="center"><?php _e( 'Sales', 'wp-easy-allopass' ) ?></div></th>
          <th width="250"><?php _e( 'Shortcode', 'wp-easy-allopass' ) ?></th>
          <th width="50">&nbsp;</th>
        </tr>
	  </thead>
	  <tbody>
	  <?php
		$wpdb->flush();
		$id_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM ".TBL_PROD." WHERE id_prod <> %s" , 'DontDelete' ) );
		if ($id_count > 0)
		{
			// Manage link admin
			$get___ = $_GET;
			foreach($get___ AS $key=>$value)
			{
				if ($key == 'edit' or $key == 'stats' or $key == 'del') 
					{
						unset($get___[$key]);
					}
			}
			
			if (http_build_query($get___) != "")
				{
				$link = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . '?' . http_build_query($get___).'&';
				}
				else
				{
				$link = WEA_PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. '?';
				}
		
			$row = $wpdb->get_results("SELECT * FROM ".TBL_PROD." WHERE id_prod <> 'DontDelete' LIMIT ".$debut.",".$nbr_par_page."");
			foreach ($row as $row) 
			{
			// Stats
			$stat = 0;
			$stat = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM ".TBL_STAT." WHERE id = %d" , $row->id ) );
			
			// Date
			$Y__ = substr($row->last_update, 0, 4);
			$m__ = substr($row->last_update, 4, 2);
			$d__ = substr($row->last_update, 6, 2);
			$date__ = $d__."/".$m__."/".$Y__;
			
			// Description
			$descr___ = stripslashes($row->prod_descr);
			if(strlen($descr___)>=200){$descr___=substr($descr___,0,200) . "..." ;} 
			$descr___ = htmlspecialchars($descr___);
		    ?>
			<tr>
			  <td><div align="left"><?php echo $row->id ?></div></td>
			  <td width="160"><div align="left"><?php echo str_replace("/", "<strong><font color='#FF0000'>/</font></strong>", $row->id_prod) ?></div></td>
			  <td><div align="left"><?php echo $descr___ ?></div></td>
			  <td><div align="left"><?php echo $date__ ?></div></td>
			  <td><div align="center"><?php echo $stat ?></div></td>
			  <td width="250"><div align="left"><code><input type='text' value='<?php echo '[allopass id="'.$row->id.'"] ... [/allopass]' ?>' readonly='readonly' style='width:240px'/></code></div></td>
			  <td width="50">
				  <a href="<?php echo $link."edit=".$row->id ?>" title="<?php _e( 'Edit', 'wp-easy-allopass' ) ?>"><img src="<?php echo URL_WEA ?>image/edit.png" width="12" height="13" border="0" align="absmiddle"/></a>
				  <a href="<?php echo $link."stats=".$row->id ?>" title="<?php _e( 'Statistics', 'wp-easy-allopass' ) ?>"><img src="<?php echo URL_WEA ?>image/fiche.png" width="12" height="13" border="0" align="absmiddle"/></a>
				  <a href="<?php echo $link."del=".$row->id ?>" title="<?php _e( 'Delete', 'wp-easy-allopass' ) ?>"><img src="<?php echo URL_WEA ?>image/suppr.png" width="12" height="13" border="0" align="absmiddle"/></a>			  </td>
			</tr>
		    <?php 
			}
		}
		else
		{
		    ?>
			<tr>
			  <td colspan="7"><div align="center"><br><?php _e( 'You have not yet added product', 'wp-easy-allopass' ) ?><br>&nbsp;</div></td>
			</tr>
		    <?php 
	    }
	  ?>		
	  		<tr>
			  <td colspan="7">
			  <?php CreerLiens($lien, $debut, $nbr_par_page, $id_count); ?>			  </td>
			</tr>
	  </tbody>
      </table>
	</form>
	</div>	</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div align="left">
      <table class="widefat">
	  <thead>
        <tr>
          <th><div align="left"><strong><?php _e( 'Shortcode', 'wp-easy-allopass' ) ?></strong></div></th>
        </tr>
	  </thead>
	  <tbody>
        <tr>
          <td><div align="left"><?php _e( 'To protect paid content on one of your posts/pages, insert the following code:', 'wp-easy-allopass' ) ?></div></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div align="left"><?php _e( 'Free content', 'wp-easy-allopass' ) ?> ... <font color="#FF0000"><strong><input type='text' value='[allopass id=&quot;X&quot;] ...<?php _e( 'Protected content', 'wp-easy-allopass' ) ?>... [/allopass]' readonly='readonly' style='width:300px;text-align:center' /></strong></font> ... <?php _e( 'Free content', 'wp-easy-allopass' ) ?>...</div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div align="left"><strong><?php _e( 'Notes:', 'wp-easy-allopass' ) ?></strong></div></td>
        </tr>
        <tr>
          <td>
		  	<div align="left">
			<strong>&raquo; </strong><?php _e( 'The parameter <strong>Id</strong> (<strong>X</strong> = numeric value) represents the id of your product. (See column &quot;shortcode&quot; in the above table).<br>This parameter is required.', 'wp-easy-allopass' ) ?><br>
            <strong>&raquo; </strong><?php _e( 'You can insert <strong>several shortcodes</strong> in a single post or page.', 'wp-easy-allopass' ) ?>			</div>		  </td>
        </tr>
        <tr>
          <td><div align="left"><?php _e( 'If the parameter <strong>id</strong> is not defined or no product at', 'wp-easy-allopass' ) ?> <a href="http://<?php echo $lang ?>.allopass.com/advert?from=sponsorship&amp;target=1605852" target="_blank">Allopass</a> <?php _e( 'corresponds to your <strong>&quot;Product Id&quot;,</strong> WordPress will display an error code...', 'wp-easy-allopass' ) ?></div></td>
        </tr>
	  </tbody>
      </table>
    </div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
	<?php _e( 'This plugin is free for use. But you can also download the ', 'wp-easy-allopass' ) ?>
	<a href="<?php echo URL_WES ?>wea-wp-easy-allopass-download/" target="_blank">Pro Version</a><br>
	<?php _e( '(The PRO Version allows you to increase your profits and enjoy other benefits and FREE support.)', 'wp-easy-allopass' )?>
	</td>
  </tr>
</table>
</div>
