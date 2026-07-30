<?php
/********************************************************************
+--------------------------------------------------------------------------
|   MKPortal SimplePie Feed Reader Block 1.1 17.06.2008
|   ========================================
|   by Meo aka Luponero <Amedeo de longis>
|      Don K. Colburn <visiblesoul.net>
|
|   Copyright (c) 2003-2008 mkportal.it
|   http://www.mkportal.it
|   Email: luponero@mclink.it
|
+---------------------------------------------------------------------------
|
|   > MKPortal
|   > Written By Amedeo de longis
|   > Date started: 9.2.2004
+--------------------------------------------------------------------------
|   SIMPLEPIE
|   http://simplepie.org
|   Copyright (c) 2004-2007, Ryan Parman and Geoffrey Sneddon.
|   All rights reserved.
|   
|   BSD-LICENSED: /mkportal/modules/rss/LICENSE.txt
+--------------------------------------------------------------------------
*/

if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}

global $MK_PATH, $mklib;

############################
# More config options
############################

$mklib->config['rss_add_links'] = '0'; //Include icons to recommend feed to various services? Yes=1; No=0
$mklib->config['rss_subscribe_links'] = '0'; //Include subscribe links? Yes=1; No=0

############################

// Include the SimplePie library, and the one that handles internationalized domain names.
require_once($MK_PATH . 'mkportal/modules/rss/simplepie.inc');
require_once($MK_PATH . 'mkportal/modules/rss/idn/idna_convert.class.php');

// Query mkp_rss for feed URLs
$DB->query("SELECT * FROM mkp_rss WHERE active='1' ORDER BY position ASC");
if ($DB->get_num_rows()) {
    while ($r = $DB->fetch_row()) {
	    //$rss_source[$r['name']] = $r['url']; //[google] => http://news.google.co.uk/nwshp?hl=en&tab=wn&q=&output=rss
	    $rss_source[] = $r['url']; // [0] => http://news.google.co.uk/nwshp?hl=en&tab=wn&q=&output=rss
    }
}

//Marquee
if ($mklib->config['rss_marquee']) {
        $marquee_begin = "\n<marquee direction=\"up\" scrolldelay=\"0\" scrollamount=\"1\" height=\"{$mklib->config['rss_marquee_height']}\">\n";
        $marquee_end = "\n</marquee>\n";
} else {
	$marquee_begin = '';
	$marquee_end = '';
}

//Open block table row
$content = '
<tr>
  <td>
    <div id="site">
      '.$marquee_begin;

ob_start();

############################
# Begin merged feeds
############################
if ($mklib->config['rss_merge'] == '1') { 

	$feed = new SimplePie();
	$feed->set_feed_url($rss_source);

	$feed->set_output_encoding($mklib->charset);
	//$feed->set_timeout(60);
	//$feed->force_fsockopen(true);
	$feed->enable_order_by_date(true);
	$feed->set_cache_location($MK_PATH . 'mkportal/cache');
	$feed->set_cache_duration($mklib->config['rss_cache_time']);
	$feed->set_item_limit($mklib->config['rss_max_items']);
	$feed->set_javascript('media');

	// When we set these, we need to make sure that the handler_image.php file
	// is also trying to read from the same cache directory that we are.
	$feed->set_favicon_handler($MK_PATH . 'mkportal/modules/rss/handler_image.php');
	$feed->set_image_handler($MK_PATH . 'mkportal/modules/rss/handler_image.php');

	// Initialize the feed.
	$feed->init();

	//Handle feed errors
	if ($feed->error) {
		$content .= '<p>' . htmlspecialchars($feed->error()) . '</p>';
	}

	// Let's loop through each item in the feed.
	foreach($feed->get_items() as $item) {
		$content .= mk_get_feeditems($feed, $item);
	} //end items loop	

} //end merged feeds

############################
# Begin feeds by channel
############################
else {
	foreach ($rss_source AS $feedname => $feedurl) {

		// Create a new instance of the SimplePie object
		$feed = new SimplePie();
		$feed->set_feed_url($feedurl);

		$feed->set_output_encoding($mklib->charset);
		//$feed->set_timeout(60);
		//$feed->force_fsockopen(true);
		//$feed->enable_order_by_date(false);
		$feed->set_cache_location($MK_PATH . 'mkportal/cache');
		$feed->set_cache_duration($mklib->config['rss_cache_time']);
		$feed->set_item_limit($mklib->config['rss_max_items']);
		$feed->set_javascript('media');

		// When we set these, we need to make sure that the handler_image.php file
		// is also trying to read from the same cache directory that we are.
		$feed->set_favicon_handler($MK_PATH . 'mkportal/modules/rss/handler_image.php');
		$feed->set_image_handler($MK_PATH . 'mkportal/modules/rss/handler_image.php');

		// Initialize the feed.
		$feed->init();

		//Handle feed errors
		if ($feed->error) {
			$content .= '<p>' . htmlspecialchars($feed->error()) . '</p>';
		} ?>

      <div class="tdblock channeltitle" align="center">
        <!-- Channel Title & Link -->
        <h3 class="header"><?php if ($feed->get_link()) echo '<a href="' . $feed->get_link() . '">'; echo $feed->get_title(); if ($feed->get_link()) echo '</a>'; ?></h3>

        <!-- Channel description -->
        <p><?php echo $feed->get_description(); ?></p>
      </div>

		<?php
		//Add subscribe links for several different aggregation services
		if ($mklib->config['rss_subscribe_links'] == '1') { ?>

	<!-- Add subscribe links for several different aggregation services -->
      <div class="subscribe"><strong>Subscribe:</strong> <a href="<?php echo $feed->subscribe_bloglines(); ?>">Bloglines</a>, <a href="<?php echo $feed->subscribe_google(); ?>">Google Reader</a>, <a href="<?php echo $feed->subscribe_msn(); ?>">My MSN</a>, <a href="<?php echo $feed->subscribe_netvibes(); ?>">Netvibes</a>, <a href="<?php echo $feed->subscribe_newsburst(); ?>">Newsburst</a><br /><a href="<?php echo $feed->subscribe_newsgator(); ?>">Newsgator</a>, <a href="<?php echo $feed->subscribe_odeo(); ?>">Odeo</a>, <a href="<?php echo $feed->subscribe_podnova(); ?>">Podnova</a>, <a href="<?php echo $feed->subscribe_rojo(); ?>">Rojo</a>, <a href="<?php echo $feed->subscribe_yahoo(); ?>">My Yahoo!</a>, <a href="<?php echo $feed->subscribe_feed(); ?>">Desktop Reader</a></div>

		<?php
		} //end subscribe links

		// Let's loop through each item in the feed.
		$max = $feed->get_item_quantity($mklib->config['rss_max_items']);
		for ($x = 0; $x < $max; $x++) { //Begin items loop
			$item = $feed->get_item($x);

			$content .= mk_get_feeditems($feed,$item);
 		} //end items loop
	} //end feeds by channel loop 

	unset($feedurl);

} // feeds by channel

$feedblock = ob_get_contents();
ob_end_clean();

$content .= $feedblock;

//Close block table row
$content .= ' 
      <p class="footnote">MKPFeedReader powered by <a href="' . SIMPLEPIE_URL . '">' . SIMPLEPIE_NAME . '</a></p>
      '.$marquee_end.'
    </div>      
  </td>
</tr>';

//Error if block not selected in RSS Admin
if ($mklib->config['rss_parser'] != 'simplepie') {
	$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$mklib->lang['rss_nodouble']}
				  </td>
				</tr>
	";
}

//Error if no active feeds
if (!$rss_source) {
	$content = "
				<tr>
				  <td class=\"tdblock\" align=\"center\">
				  {$mklib->lang['rss_noactive']}
				  </td>
				</tr>
	";
}

if ($feed) {
	$feed->__destruct(); // Do what PHP should be doing on it's own.
}
unset($r);
unset($rss_source);
unset($marquee_begin);
unset($marquee_end);
unset($feedname);
unset($feedurl);
unset($feed);
unset($max);
unset($x);
unset($item);
unset($feedblock);


function mk_get_feeditems($feed, $item) {

	global $MK_PATH, $mklib;

	// Reference to the parent $feed object for this particular item.
	$feed = $item->get_feed();

	$favicon = ($feed->get_favicon() != '') ? $feed->get_favicon() : $MK_PATH . 'mkportal/modules/rss/files/favicons/alternate.png';  

	$itemcss = ($mklib->config['rss_desc']) ? "chunk" : "itemlist" ;

	echo '<div class="'.$itemcss.'">';

	?>

      <h4><img src="<?php echo $favicon; ?>" alt="Favicon" class="favicon" /><?php if ($item->get_permalink()) echo '<a href="' . $item->get_permalink() . '">'; echo html_entity_decode($item->get_title(), ENT_QUOTES); if ($item->get_permalink()) echo '</a>'; if (!$mklib->config['rss_desc']) echo ' - <span class="footnote"><a href="' . $feed->get_permalink() . '"> ' . $feed->get_title() . '</a></span>'; ?></h4>

	<?php
	if ($mklib->config['rss_desc']) {
	?>
      <p class="footnote">Source: <a href="<?php echo $feed->get_permalink(); ?>"><?php echo $feed->get_title(); ?></a> | <?php echo $item->get_date('j M Y | g:i a'); ?></p>
		<?php

		if ($mklib->config['rss_desc_length'] > 0 ) {
			echo shorten($item->get_content(), $mklib->config['rss_desc_length']);
		} else {
			echo $item->get_content();
		}

		// Check for enclosures.
		// If an item has any, set the first one to the $enclosure variable.
		if (($enclosure = $item->get_enclosure(0)) && ($mklib->config['rss_media'] == 1)) {
			// Embed enclosures
			echo '<div align="center">';
			echo '<p>' . $enclosure->embed(array(
				'audio' => $MK_PATH . 'mkportal/modules/rss/files/place_audio.png',
				'video' => $MK_PATH . 'mkportal/modules/rss/files/place_video.png',
				'mediaplayer' => $MK_PATH . 'mkportal/modules/rss/files/mediaplayer.swf',
				'alt' => '<img src="'. $MK_PATH . 'mkportal/modules/rss/files/mini_podcast.png" class="download" border="0" title="Download the Podcast (' . $enclosure->get_extension() . '; ' . $enclosure->get_size() . ' MB)" />',
				'altclass' => 'download'
				)) . '</p>';
			echo '<p class="footnote" align="center">(' . $enclosure->get_type();
			if ($enclosure->get_size()) {
				echo '; ' . $enclosure->get_size() . ' MB';					
			}
			echo ')</p>';
			echo '</div>';
		}

		//Add links to add this post to one of a handful of services
		if ($mklib->config['rss_add_links'] == '1') {
		?>
						<p class="footnote favicons" style="margin-top: 4px" align="center">
						<a href="<?php echo $item->add_to_blinklist(); ?>" title="Add post to Blinklist"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/blinklist.png" alt="Blinklist" /></a>
							<a href="<?php echo $item->add_to_blogmarks(); ?>" title="Add post to Blogmarks"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/blogmarks.png" alt="Blogmarks" /></a>
							<a href="<?php echo $item->add_to_delicious(); ?>" title="Add post to del.icio.us"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/delicious.png" alt="del.icio.us" /></a>
							<a href="<?php echo $item->add_to_digg(); ?>" title="Digg this!"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/digg.png" alt="Digg" /></a>
							<a href="<?php echo $item->add_to_magnolia(); ?>" title="Add post to Ma.gnolia"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/magnolia.png" alt="Ma.gnolia" /></a>
							<a href="<?php echo $item->add_to_myweb20(); ?>" title="Add post to My Web 2.0"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/myweb2.png" alt="My Web 2.0" /></a>
							<a href="<?php echo $item->add_to_newsvine(); ?>" title="Add post to Newsvine"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/newsvine.png" alt="Newsvine" /></a>
							<a href="<?php echo $item->add_to_reddit(); ?>" title="Add post to Reddit"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/reddit.png" alt="Reddit" /></a>
							<a href="<?php echo $item->add_to_segnalo(); ?>" title="Add post to Segnalo"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/segnalo.png" alt="Segnalo" /></a>
							<a href="<?php echo $item->add_to_simpy(); ?>" title="Add post to Simpy"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/simpy.png" alt="Simpy" /></a>
							<a href="<?php echo $item->add_to_spurl(); ?>" title="Add post to Spurl"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/spurl.png" alt="Spurl" /></a>
							<a href="<?php echo $item->add_to_wists(); ?>" title="Add post to Wists"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/wists.png" alt="Wists" /></a>
							<a href="<?php echo $item->search_technorati(); ?>" title="Who's linking to this post?"><img src="<?php echo $MK_PATH ?>mkportal/modules/rss/files/favicons/technorati.png" alt="Technorati" /></a>
						</p>

		<?php
		} 

	}
?>
		</div> <?php

unset($feed);
unset($favicon);
unset($itemcss);
unset($enclosure);

}

?>
