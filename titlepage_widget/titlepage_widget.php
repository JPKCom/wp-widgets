<?php
/*
Plugin Name: Title Page Widget (PB)
Plugin URI: http://mydomain.com
Description: Creates a full-screen title page for a post (designed to used with Site Origin Page Builder)
Author: Me
Version: 1.0
Author URI: http://mydomain.com
*/

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');
	
add_action( 'widgets_init', function(){
	register_widget( 'Title_Page_Widget' );
});	


/**
 * Adds Title_Page_Widget widget.
 */
class Title_Page_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Title_Page_Widget', // Base ID
			__('Title Page Widget (PB)', 'text_domain'), // Name
			array('description' => __( 'Creates a full-screen title page - designed for use with Site Origin\'s Page Builder plugin', 'text_domain' ),) // Args
		);
		
		add_action( 'sidebar_admin_setup', array( $this, 'admin_setup' ) );

	}
	
	function admin_setup(){

		wp_enqueue_media();
		wp_register_script('tpw-admin-js', plugins_url('tpw_admin.js', __FILE__), array( 'jquery', 'media-upload', 'media-views' ) );
		wp_enqueue_script('tpw-admin-js');
		wp_enqueue_style('tpw-admin', plugins_url('tpw_admin.css', __FILE__) );

	}		
			
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	
		// use a template for the output so that it can easily be overridden by theme
		
		// check for template in active theme
		$template = locate_template(array('tpw_template.php'));
		
		// if none found use the default template
		if ( $template == '' ) $template = 'tpw_template.php';
				
		include ( $template );
			
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
		
		$bg_video = ( isset( $instance['background_video'] ) ) ? $instance['background_video'] : '';
		$bg_image = ( isset( $instance['background_image'] ) ) ? $instance['background_image'] : '';
		$title_text = ( isset( $instance['title_text'] ) ) ? $instance['title_text'] : '';
		$title_image = ( isset( $instance['title_image'] ) ) ? $instance['title_image'] : '';

	
	?>	
		
		<div class="titlepage_widget">
		
			<h3>Title</h3>
			<p>
				<div class="widget_input">
					<label for="<?php echo $this->get_field_id( 'title_text' ); ?>"><?php _e( 'Text :' ); ?></label> 	
					<input class="title_text" id="<?php echo $this->get_field_id( 'title_text' ); ?>" name="<?php echo $this->get_field_name( 'title_text' ); ?>" value="<?php echo $title_text ?>" type="text"><br/>
				</div>
				<div class="widget_input">
					<label for="<?php echo $this->get_field_id( 'title_image' ); ?>"><?php _e( 'Image :' ); ?></label> 	
					<input class="title_image" id="<?php echo $this->get_field_id( 'title_image' ); ?>" name="<?php echo $this->get_field_name( 'title_image' ); ?>" value="<?php echo $title_image ?>" type="text"><button id="title_image_button" class="button" onclick="image_button_click('Choose Title Image','Select Image','image','title_image_preview','<?php echo $this->get_field_id( 'title_image' );  ?>');">Select Image</button>			
				</div>
				<div id="title_image_preview" class="preview_placholder">
				<?php 
					if ($title_image!='') echo '<img src="' . $title_image . '">';
				?>
				</div>
			</p>	
			
			<h3>Background</h3>
			<p id="title_background_inputs">
				<label for="<?php echo $this->get_field_id( 'background_video' ); ?>"><?php _e( 'Video :' ); ?></label> 	
				<input class="background_video" id="<?php echo $this->get_field_id( 'background_video' ); ?>" name="<?php echo $this->get_field_name( 'background_video' ); ?>" value="<?php echo $bg_video ?>" type="text"><button id="background_video_button" class="button" onclick="image_button_click('Choose Background Video','Select Video','video','background_video_preview','<?php echo $this->get_field_id( 'background_video' );  ?>');">Select Video</button>			
				<div id="background_video_preview" class="preview_placholder">
				<?php 
            		if ($bg_video!='') echo '<video autoplay loop><source src="' . $bg_video . '" type="video/' . substr( $bg_video, strrpos( $bg_video, '.') + 1 ) . '" /></video>';
				?>				
				</div>
				<label for="<?php echo $this->get_field_id( 'background_image' ); ?>"><?php _e( 'Image :' ); ?></label> 	
				<input class="background_image" id="<?php echo $this->get_field_id( 'background_image' ); ?>" name="<?php echo $this->get_field_name( 'background_image' ); ?>" value="<?php echo $bg_image ?>" type="text"><button id="background_image_button" class="button" onclick="image_button_click('Choose Background Image','Select Image','image','background_image_preview','<?php echo $this->get_field_id( 'background_image' );  ?>');">Select Image</button>	
				<div id="background_image_preview" class="preview_placholder">
				<?php 
					if ($bg_image!='') echo '<img src="' . $bg_image . '">';
				?>				
				</div>
			</p>
			
		</div>
	
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		$instance['background_video'] = ( ! empty( $new_instance['background_video'] ) ) ? strip_tags( $new_instance['background_video'] ) : '';
		$instance['background_image'] = ( ! empty( $new_instance['background_image'] ) ) ? strip_tags( $new_instance['background_image'] ) : '';
		$instance['title_text'] = ( ! empty( $new_instance['title_text'] ) ) ? strip_tags( $new_instance['title_text'] ) : '';
		$instance['title_image'] = ( ! empty( $new_instance['title_image'] ) ) ? strip_tags( $new_instance['title_image'] ) : '';
		return $instance;
	}

} // class My_Widget