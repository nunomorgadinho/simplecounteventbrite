<?php
/**
 * @package simplecountdown-eventbrite
 * @author Nuno Morgadinho (based on work by Michael Henke)
 * @version 0.1
 */
/*
Plugin Name: Simple Count Down - Eventbrite Attendees
Plugin URI: http://wordpress.org/extend/plugins/simple-count-down-eventbrite/
Description: Simple configurable widgetized attendee count plugin for an Eventbrite event. Use the <a href="widgets.php">widgets options</a> to integrate the plugin and see the <a href="plugins.php?page=simple_count_down_eventbrite-config">administration panel</a> for further configuration.
Version: 0.1
Author: Nuno Morgadinho (based on work by Michael Henke)
Author URI: http://www.widgilabs.com
*/

function simplecountdowneventbrite_myFeature() {
    echo $_SESSION['simple-count-down-eventbrite-text-string'];
}

function simplecountdowneventbrite_control() {

    $options = get_option("simplecountdowneventbrite");

    if (!is_array( $options )) {
        $options = array(
            'title' => 'Count'
        );
    }

    if ($_POST['simplecountdowneventbrite-submit']) {
        $options['title'] = htmlspecialchars($_POST['simplecountdowneventbrite-title']);

        update_option("simplecountdowneventbrite", $options);
    }
    
?>
<p>
<label for="simplecountdowneventbrite-title">Title: </label><br />
<input class="widefat" type="text" id="simplecountdowneventbrite-title" name="simplecountdowneventbrite-title" value="<?php echo $options['title'];?>" />
<input type="hidden" id="simplecountdowneventbrite-submit" name="simplecountdowneventbrite-submit" value="1" />
<?php

    echo "<br />Please use the administration panel to further <a href=\"plugins.php?page=simple_count_down_eventbrite-config\">configure the plugin</a>.";
}

register_sidebar_widget ( "Count Eventbrite Attendees", simplecountdowneventbrite_myFeature );
register_widget_control ( "Count Eventbrite Attendees", simplecountdowneventbrite_control );

add_action('admin_menu', 'simplecountdowneventbrite_config_page');

function simplecountdowneventbrite_config_page() {

    add_submenu_page('plugins.php', __('Count Attendees'), __('Count Attendees'), 'manage_options', 'simple_count_down_eventbrite-config', 'simplecountdowneventbrite_options');
    
}

function simplecountdowneventbrite_options() {

?><div class="wrap simple-count-down-wrap">
    <div class="simple-count-down-left" style="float: left;width:70%;">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>Count Eventbrite Attendees Settings</h2>
    <form method="post" action="options.php"><?php wp_nonce_field('update-options'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Eventbrite App Key</th>
                <td>
	<input type="text" name="simple-count-down-eventbrite-appkey" value="<?php echo get_option('simple-count-down-eventbrite-appkey'); ?>" />
                    <br />
                    Instructions on how to find your Eventbrite App Key will be here.
                </td>
            </tr>
	            <tr valign="top">
	                <th scope="row">Eventbrite Event ID</th>
	                <td>
		<input type="text" name="simple-count-down-eventbrite-eventid" value="<?php echo get_option('simple-count-down-eventbrite-eventid'); ?>" />
	                    <br />
	                    Instructions on how to find your Eventbrite Event ID will be here.
	                </td>
	            </tr>
			<tr>
                <th scope="row">Text String</th>
                <td>
                    <input type="text" name="simple-count-down-eventbrite-text-string" value="<?php echo get_option('simple-count-down-eventbrite-text-string'); ?>" />
                    <br />
                    %N or %n will be replaced with the resulting string. The result will become negative if the date is in the past.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Example</th><td><em>Our event will have %N attendees.</em> or
                    <br /><em>%n guys and gals have already registered.</em></td>
            </tr>
        </table>


    <p class="submit">
    <input type="submit" class="button-primary" value="Save Changes" />
    </p>

    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="simple-count-down-eventbrite-appkey,simple-count-down-eventbrite-eventid,simple-count-down-eventbrite-text-string" />
    </form>
    </div>
    
    <div class="simple-count-down-bottom" style="clear: both;" width="100%">
        Version&nbsp;0.1
    </div>
    </div>
<?php
}

function simplecountdowneventbrite_my_scripts() {
    wp_enqueue_script( 'jquery' );
}    
 
add_action('wp_enqueue_scripts', 'simplecountdowneventbrite_my_scripts');

function simplecountdowneventbrite_addHeaderCode() {

    echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/simplecountdowneventbrite/css/custom.css" />' . "\n";

	?>

	<script type="text/javascript">
	jQuery( function($) {
		
		var numnums = $('#numnumnum'),
	    numnumpos = $('#numnumnum').position(),
	    dataLen = $('#numnumnum').text().length;
	    
	    recenter = function( data ) {
			var visible = numnums.filter( ':visible' );

			visible.fadeOut( 500 ).queue( function() {
				visible.css( {
					position: 'static',
					display: 'inline',
					visibility: 'hidden',
					top: '',
					left: '',
				} ).html( data );

				numnumpos = visible.position();

				
				numnums.css( {
					display: 'none',
					visibility: 'visible'
				} );

				visible.dequeue();
			} ).fadeIn( 350 );
	    };

	

	setInterval(function() {
		var data = {action: 'simplecountdowneventbrite'};
		$.post( '<?php echo bloginfo('siteurl')."/wp-admin/admin-ajax.php"; ?>', data, function( response ) {
			console.log("response = "+response);
			$('#numnumnum').html( response );
			return;

		});
	}, 4000);
	} );
	</script>

	<?php
}

function  simplecountdowneventbrite_addAdminHeaderCode() {

    echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/simplecountdowneventbrite/css/admin-custom.css" />' . "\n";


}

add_action( 'wp_head', 'simplecountdowneventbrite_addHeaderCode' );
add_action( 'admin_head', 'simplecountdowneventbrite_addAdminHeaderCode' );

function simplecountdowneventbrite_precalculate() {

    if(get_option('simple-count-down-eventbrite-appkey') || ($_SESSION['simple-count-down-eventbrite-text-string'] == "")) {
        
			$attendees = simplecountdowneventbrite_get_num();
			
			if ($attendees > 0)
			{
				$string = preg_replace('/%[n|N]/', '<span id="numnumnum">'.$attendees.'</span>', htmlentities(get_option('simple-count-down-eventbrite-text-string'), ENT_QUOTES, get_bloginfo('charset')));
				
            	$options = get_option("simplecountdowneventbrite");
			
            	$_SESSION['simple-count-down-eventbrite-text-string'] = $before_widget .
                                                    $before_title .
                                                    $options['title'] .
                                                    $after_title . 
                                                    '<ul><li id="simple-count-down-li">'. $string .'</li></ul>' . 
                                                    $after_widget;
			}
			else
			{
				$_SESSION['simple-count-down-eventbrite-text-string'] = "";
			}
    }
}

add_action( 'get_header', 'simplecountdowneventbrite_precalculate' );


add_action('wp_ajax_simplecountdowneventbrite', 'simplecountdowneventbrite_callback');
add_action('wp_ajax_nopriv_simplecountdowneventbrite', 'simplecountdowneventbrite_callback');

function simplecountdowneventbrite_callback()
{
	$num = simplecountdowneventbrite_get_num();
	
	echo $num;
	
	die();
}

function simplecountdowneventbrite_get_num() {
	$app_key = get_option("simple-count-down-eventbrite-appkey");
	$eid = get_option("simple-count-down-eventbrite-eventid");
	
	if (empty($app_key) || empty($eid))
		return("-1");
	
	$xml = file_get_contents("https://www.eventbrite.com/xml/event_list_attendees?app_key=".$app_key."&id=".$eid);
	if ($xml) {
		$elem = new SimpleXMLElement($xml);
		return count($elem->attendee);
	} else {
		return("-1");
	}
}

//Default Options
add_option('simple-count-down-eventbrite-appkey', '');
add_option('simple-count-down-eventbrite-eventid', '');
add_option('simple-count-down-eventbrite-text-string', 'Our event will have %n attendees.');


?>
