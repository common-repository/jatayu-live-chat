<?php
/*
Plugin Name: Live Chat
Plugin URI:http://webkul.com/
Description:Live chat wordpress plugin
Version:1.0
Author:webkul
Author URI:http://www.webkul.com
License:GPL
*/


register_activation_hook(__FILE__,'webkul_livechat_install');
register_deactivation_hook(__FILE__,'webkul_livechat_uninstall');

function webkul_livechat_install()
{
	add_option('webkul_livechat_chatid','','','yes');
}


function webkul_livechat_uninstall()
{
	delete_option('webkul_livechat_chatid');
}

if(is_admin())
{	
	add_action('admin_menu','webkul_livechat_admin_menu');	   
	
	function webkul_livechat_admin_menu()
	{
		add_options_page('Live Chat','Live Chat','administrator','livechat-setting','livechat_setting_page');
	}	
	
}
function livechat_setting_page()
{	
?>
<link rel="stylesheet" href="<?php echo plugins_url('livechat/css/admin-page.css');?>"/>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function(){
	jQuery('#publish').click(function(){
		jQuery('.chatform').css({'display':'block'});
		jQuery('.chatiframe').css({'display':'none'});
		jQuery('.header_row2').addClass(' activerow');
		jQuery('.header_row1').removeClass('activerow');
	});
	jQuery('#instance').click(function(){
		jQuery('.chatform').css({'display':'none'});
		jQuery('.chatiframe').css({'display':'block'});
		jQuery('.header_row1').addClass(' activerow');
		jQuery('.header_row2').removeClass('activerow');
	});
});
</script>
<div class="container">
			<div class="containe_within">
				<div class="header">
					<div class="header_content">
						<div class="header_row1 activerow">
								<a style="font-weight:bold;" href="#" title="Create Instance" id="instance">Create Instance</a>
						</div>
						<div class="header_row2">
								<a style="font-weight:bold;" href="#" title="Publish" id="publish">Publish</a>
						</div>						
					</div>
				</div>
				<div class="chatiframe" style="display:block;">
					<?php  $chatid = get_option('webkul_livechat_chatid'); 
					if($chatid){
					 ?>
						<iframe src="http://jatayu.webkul.com?platform=wordpress&pcid=<?php echo $chatid; ?>" height="770" width="100%" frameborder="0"></iframe>
					<?php } else {  ?>
						<iframe src="http://jatayu.webkul.com?platform=wordpress" height="770" width="100%" frameborder="0"></iframe>
					<?php } ?>
				</div>
				<div class="chatform" style="display:none;">
					<form method="post" action="options.php" id="livechat-id">
					<?php wp_nonce_field('update-options'); ?>					
					<span class="label">Chat Instance Id :</span><input name="webkul_livechat_chatid" type="text" id="webkul_livechat_chatid" value="<?php echo get_option('webkul_livechat_chatid'); ?>" />
					<input type="hidden" name="action" value="update" />
					<input type="hidden" name="page_options" value="webkul_livechat_chatid" />					
					<p>
					<input type="submit" value="<?php _e('Save Changes') ?>" />
					</p>					
					</form>
				</div>
			</div>
</div>

<?php
}

add_action('wp_head', 'livechat');
add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts' );
	function wpb_adding_scripts() {		
	wp_register_script('livechat_script', false, array('jquery'),false, false);		
	wp_enqueue_script('livechat_script');	
	}
	function livechat(){
	$chatid = get_option('webkul_livechat_chatid');
	?>	
<script type="text/javascript" src="http://jatayu.webkul.com:3000/socket.io/socket.io.js"></script>
<script type="text/javascript">var uvOptions = {};(function() {
var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'jatayu.webkul.com/chat-io.php?id=<?php echo $chatid; ?>';var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv, s);})();</script>
	
	<?php
	}

?>