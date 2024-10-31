<?php

if (!defined('ABSPATH'))
    exit;

if (!class_exists('POP_front')) {

    class POP_front {

        protected static $instance;

        
        function cp_filter_wp_link_pages( $output, $args ) { 
           
            global $post;
            $id = get_the_ID();
            if( $post->post_type == "post") {
                if(is_singular()){

                }else{
                    return '<div class="cp_btn_div"><button class="cp_btn" data-id="'.$id.'" style="background-color:'.get_option('cp_btn_bg_clr').';font-size:'.get_option('cp_btn_ft_size').'px;color:'.get_option('cp_btn_ft_clr').';">'.get_option('cp_btn_txt').'</button></div>'.$output; 
                }
            }
        }


        function cp_pop_popup($atts, $content = null) {
            ob_start();  
            extract(shortcode_atts(array(
                'id' => '',
            ), $atts));
            // print_r($id);
            ?>
            <div class="cp_btn_div">
                <button class="cp_btn" data-id="<?php echo $id; ?>" style="background-color:<?php echo get_option('cp_btn_bg_clr') ?>;font-size:<?php echo get_option('cp_btn_ft_size')."px" ?>;color:<?php echo get_option('cp_btn_ft_clr') ?>;"><?php echo get_option('cp_btn_txt'); ?></button>
            </div>
            <?php
            return $var = ob_get_clean();
        }

      
        function cp_popup_div_footer(){
            ?>
            <div id="cp_popup_id" class="cp_popup_class">
                <div class="mailppss">
                </div>
            </div>
            <?php
        }


        function cp_popup_open() {
            $post_id = sanitize_text_field( $_REQUEST['popup_id'] );
            $post_info = get_post( $post_id );
            
			$author = $post_info->post_author;
			$author_obj = get_user_by('id', $author);

            $featured_img_url = get_the_post_thumbnail_url($post_id, 'full'); 
            if(empty($featured_img_url)){
                $cp_image_url = POP_PLUGIN_DIR."/includes/images/background-image.jpg";
            }else{
                $cp_image_url = $featured_img_url;
            }
            $post_categories = get_the_category($post_id);
           	$fix_cat = array();

            foreach ($post_categories as $key => $value) {
            	
            	$category_link = get_category_link( $value->term_id );
            	$terms_name = '<a href="'.$category_link.'" target="_blank" style="color:'.get_option('cp_header_clr').';">'.$value->name.'</a>';
            	array_push($fix_cat,$terms_name);
            }

            
                echo '<div class="cp_popup_content" style="background-color: '.get_option('cp_popup_clr') .'">';
    	            echo '<div class="cp_popup_header" style="background-image: url('.$cp_image_url.')">';
    	            	echo '<span class="cp_close">&times;</span>';

                        if(get_option('cp_dis_cat') == "yes"){
                            echo '<div class="cp_category_div" style="color:'.get_option('cp_header_clr').';">';
                                echo  implode(",",$fix_cat);
                            echo '</div>';
                        }
    	            	
    	            	echo '<div class="cp_title_div">';
    	            		echo '<h3 style="color:'.get_option('cp_header_clr').';">'.$post_info->post_title.'</h3>';
    	            	echo '</div>';


    	            	echo '<div class="cp_ath_time_div" style="color:'.get_option('cp_header_clr').';">';
                            if(get_option('cp_dis_autor') == "yes"){
    	            		    echo "by <strong><a href='".get_author_posts_url(  $author  )."' target='_blank' style='color: ".get_option('cp_header_clr')."'>".$author_obj->display_name."</a></strong>";
                            }
                            if(get_option('cp_dis_date') == "yes"){
                                echo " on <strong>".get_the_date( 'F j, Y', $post_id)."</strong>";
                            }
    	            	echo '</div>';
    	            echo '</div>';
    	        	echo '<div class="cp_popup_body" style="color:'.get_option('cp_discrption_clr').';font-size:'.get_option('cp_discrption_ft_size').'px;background-color: '.get_option('cp_popup_clr') .'">';
    	        		echo $post_info->post_content;
    	            echo '</div>';
                    echo '<a href="#" id="back-to-top" title="Back to top"><img src="'.POP_PLUGIN_DIR.'/includes/images/arrow-up.png"></a>';
                echo '</div>';    
               
            exit();
        }


        function init() {
            add_filter( 'wp_link_pages', array($this,'cp_filter_wp_link_pages'), 10, 2 ); 
            add_shortcode( 'post_popup', array($this,'cp_pop_popup'));
            add_action( 'wp_footer', array( $this, 'cp_popup_div_footer' ));
            add_action( 'wp_ajax_post_popup', array( $this, 'cp_popup_open' ));
            add_action( 'wp_ajax_nopriv_post_popup', array( $this, 'cp_popup_open' ));
        }


        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }

            return self::$instance;
        }

    }

    POP_front::instance();
}




function oc_popup_post_button(){
   $id = get_the_ID();
   echo  '<div class="cp_btn_div custom"><button class="cp_btn" data-id="'.$id.'" style="background-color:'.get_option('cp_btn_bg_clr').';font-size:'.get_option('cp_btn_ft_size').'px;color:'.get_option('cp_btn_ft_clr').';">'.get_option('cp_btn_txt').'</button></div>'.$output; 
}
