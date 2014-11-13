<?php
/*
 * Template for the output of the Title Page Widget
 * Override by placing a file called tpw_template.php in your active theme
 */
 
/* Output
	background image 
	background video
	title text
	title image
*/
	$upload_dir = wp_upload_dir(); 
 
	echo '<!-- full-screen title page -->
	<header>';
		
	// background video 
		
	echo '<!-- background video -->
		<div class="bgvideo-wrapper">';
			
		if ( isset( $instance['background_video'] ) ) {
		
			$video_dir = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $instance['background_video'] );
		
			echo '<video class="bgvideo" preload="auto" loop autoplay poster="' . $instance['background_image'] . '">';

			$ext = '.' . wp_check_filetype( $video_dir )['ext'];
		
			// check for mp4 in same folder and output if found
			$new_video_dir = str_replace( $ext, '.mp4', $video_dir );
			if ( file_exists( $new_video_dir ) ) echo '<source src="' . str_replace( $ext, '.mp4', $instance['background_video'] ) . '" type="video/mp4">';

			// check for webm in same folder and output if found
			$new_video_dir = str_replace( $ext, '.webm', $video_dir );		
			if ( file_exists( $new_video_dir ) ) echo '<source src="' . str_replace( $ext, '.webm', $instance['background_video'] ) . '" type="video/webm">';

			// check for ogg in same folder and output if found
			$new_video_dir = str_replace( $ext, '.ogg', $video_dir );		
			if ( file_exists( $new_video_dir ) ) echo '<source src="' . str_replace( $ext, '.ogg', $instance['background_video'] ) . '" type="video/ogg">';

			echo '</video>';
		}
		
	echo '</div>
			
		<h1 class="maintitle">';

		if ( isset( $instance['title_image'] ) ) {
			echo '<img src="' . $instance['title_image'] . '">';
		} elseif ( isset( $instance['title_text'] ) ) {
			echo $instance['title_text'];
		}

	echo '</h1>';
		'</header>';
?>