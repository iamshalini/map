<?php
require_once('wp_bootstrap_navwalker.php');
add_action( 'after_setup_theme', 'blankslate_setup' );

function blankslate_setup()
{
load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
add_theme_support( 'automatic-feed-links' );
//add_theme_support( 'post-thumbnails' );
global $content_width;
if ( ! isset( $content_width ) ) $content_width = 640;
register_nav_menus(
array( 'main-menu' => __( 'Main Menu', 'blankslate' ) )
);
}
add_action( 'wp_enqueue_scripts', 'blankslate_load_scripts' );

function blankslate_load_scripts()
{
wp_enqueue_script( 'jquery' );
}
add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );



function blankslate_enqueue_comment_reply_script()
{
if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_filter( 'the_title', 'blankslate_title' );

function blankslate_title( $title ) {
if ( $title == '' ) {
return '&rarr;';
} else {
return $title;
}
}
add_filter( 'wp_title', 'blankslate_filter_wp_title' );

function blankslate_filter_wp_title( $title )
{
return $title . esc_attr( get_bloginfo( 'name' ) );
}
add_action( 'widgets_init', 'blankslate_widgets_init' );

function blankslate_widgets_init()
{
register_sidebar( array (
'name' => 'Sidebar Widget Area', 'blankslate',
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => "</li>",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );

if (function_exists('register_sidebar')) {
register_sidebar(array(
'name' => 'News Widget Area', 'blankslate',
'id'   => 'recentpost-widgets',
'description'   => 'Widget Area',
'before_widget' => '<div>',
'after_widget' => '</div>',
'before_title' => '<h4>',
'after_title'   => '</h4>'
));
}
}

function blankslate_custom_pings( $comment )
{
$GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php 
}
add_filter( 'get_comments_number', 'blankslate_comments_number' );

function blankslate_comments_number( $count )
{
if ( !is_admin() ) {
global $id;
$comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}

if (function_exists('register_sidebar')) {
register_sidebar(array(
'name' => 'Contact Widgets',
'id'   => 'contact-widgets',
'description'   => 'Widget Area',
'before_widget' => '<div class="list-group">',
'after_widget' => '</div>',
'before_title' => '<h4>',
'after_title'   => '</h4>'
));
}

function custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 1500 );

if ( function_exists( 'add_theme_support' ) ) {
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 1970, 752, true );
}
function wds_pagination($pages = '', $range = 2) {
global $paged;
$showitems = ($range * 1)+1; // links to show
// init paged
if(empty($paged))
$paged = 1;
// init pages
if($pages == '') {
global $wp_query;
$pages = $wp_query->max_num_pages;
if(!$pages)
$pages = 1;
}
// if $pages more then one post
if(1 != $pages) {
echo '<div class="wds-pagination">';
// First link
if($paged > 1 && $paged > $range+1 && $showitems < $pages)
echo '<a href="' . get_pagenum_link(1) . '"><< First</a>';
// Previous link
if($paged > 1 && $showitems < $pages)
echo '<a href="'.get_pagenum_link($paged - 1).'">< Previous</a>';
// Links of pages
for ($i=1; $i <= $pages; $i++)
if (1 != $pages && ( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
echo ($paged == $i) ? '<span class="current">' . $i . '</span>' : '<a href="' . get_pagenum_link($i) . '">' . $i . 
'</a>';
// Next link
if ($paged < $pages && $showitems < $pages)
echo '<a href="' . get_pagenum_link($paged + 1) . '">Next ></a>';
// Last link
if ($paged < $pages-1 && $paged+$range-1 < $pages && $showitems < $pages)
echo '<a href="' . get_pagenum_link($pages) . '">Last >></a>';
echo '</div>';
}
}
if ( ! function_exists( 'my_pagination' ) ) :
    function my_pagination() {
        global $wp_query;

        $big = 999999999; // need an unlikely integer
        
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $wp_query->max_num_pages
        ) );
    }
endif;

my_pagination();
 

function my_custom_post_podcast() {
  $labels = array(
    'name'               => _x( 'Podcast', 'post type general name' ),
    'singular_name'      => _x( 'Podcast', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'podcast' ),
    'add_new_item'       => __( 'Add New podcast' ),
    'edit_item'          => __( 'Edit Podcast' ),
    'new_item'           => __( 'New Podcast' ),
    'all_items'          => __( 'All Podcast' ),
    'view_item'          => __( 'View Podcast' ),
    'search_items'       => __( 'Search Podcast' ),
    'not_found'          => __( 'No podcasts found' ),
    'not_found_in_trash' => __( 'No podcast found in the Trash' ), 
    'parent_item_colon'  => ’,
    'menu_name'          => 'Podcast'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our podcast and podcast specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,
  );
  register_post_type( 'podcast', $args ); 
}
add_action( 'init', 'my_custom_post_podcast' );

/** 18 May 2021 **/

if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page();
    
}


/** Store Location ***/

function queensvaad_location_class_cpt() {
  $labels = array(
    'name'               => _x( 'Location', 'post type general name' ),
    'singular_name'      => _x( 'Location', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'book' ),
    'add_new_item'       => __( 'Add New Location' ),
    'edit_item'          => __( 'Edit Location' ),
    'new_item'           => __( 'New Location' ),
    'all_items'          => __( 'All Location' ),
    'view_item'          => __( 'View Location' ),
    'search_items'       => __( 'Search Location' ),
    'not_found'          => __( 'No products found' ),
    'not_found_in_trash' => __( 'No products found in the Trash' ), 
    'menu_name'          => 'Store Location'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our products and product specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments','custom-fields' ),
    'has_archive'   => true,
    'rewrite' => array('slug' => 'location'),
    
  );
  register_post_type( 'location_cpt', $args ); 
}
add_action( 'init', 'queensvaad_location_class_cpt' );

/***  ***/


add_action( 'init', 'queensvaad_location_custom_taxonomy', 0 );
 
function queensvaad_location_custom_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Category', 'taxonomy general name' ),
    'singular_name' => _x( 'Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Category' ),
    'all_items' => __( 'All Categories' ),
    'parent_item' => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Category' ), 
    'update_item' => __( 'Update Category' ),
    'add_new_item' => __( 'Add New Category' ),
    'new_item_name' => __( 'New Category Name' ),
    'menu_name' => __( 'Category' ),
  );    
 
  register_taxonomy('location_cat',array('location_cpt'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'location-category' ),
  ));
}

/*** Establishment Type ***/
add_action( 'init', 'queensvaad_location_establishment_type_custom_taxonomy', 0 );
 
function queensvaad_location_establishment_type_custom_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Type', 'taxonomy general name' ),
    'singular_name' => _x( 'Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Type' ),
    'all_items' => __( 'All Types' ),
    'parent_item' => __( 'Parent Type' ),
    'parent_item_colon' => __( 'Parent Type:' ),
    'edit_item' => __( 'Edit Type' ), 
    'update_item' => __( 'Update Type' ),
    'add_new_item' => __( 'Add New Type' ),
    'new_item_name' => __( 'New Type Name' ),
    'menu_name' => __( 'Establishment Type' ),
  );    
 
  register_taxonomy('location_type',array('location_cpt'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'location-type' ),
  ));
}

/*** update Lat Long ***/
/** Save Lat Long  **/
function searsol_update_latlong_field( $post_id, $post, $update ) {
    $address = get_field('address',$post_id);
    
     $map_address = $address['address_line_1']." ".$address['address_line_2']." ".$address['city']." ".$address['state']." ".$address['country']." ".$address['zip_code'];
    
  
  
    if(!empty($map_address)){
    //$apiKey = get_field('google_api_key','options'); // Google maps now requires an API key.
    $apiKey = "AIzaSyADlk166150RMLLGby78Ayq9kUKyAdHtp0";
    $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($map_address).'&sensor=false&key='.$apiKey);
    $geo = json_decode($geo, true);
    if (isset($geo['status']) && ($geo['status'] == 'OK')) {
    $latitude = $geo['results'][0]['geometry']['location']['lat'];
    $longitude = $geo['results'][0]['geometry']['location']['lng'];
    update_field( 'lat', $latitude, $post_id );
    update_field( 'long', $longitude, $post_id );
    }
}

}
add_action( 'save_post', 'searsol_update_latlong_field', 20, 3 );


/** Category Fillter **/
function cat_fillter(){
        
        $apiKey = "AIzaSyADlk166150RMLLGby78Ayq9kUKyAdHtp0";
        $array = array();
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $array = array(
            'post_type'  => 'location_cpt',
            'posts_per_page' => 10,
            'post_status' => 'publish',
            'order'   => 'ASC',
            'paged'  => $paged,
        );
        if(!empty($_POST['location'])){
          $postal_code = $_POST['location'];
          
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($postal_code).",ireland&sensor=false&key=".$apiKey."";
        $result_string = file_get_contents($url);
        $result = json_decode($result_string, true);

        if(!empty($result['results'])){
        $zipLat = $result['results'][0]['geometry']['location']['lat'];

        $ziplng = $result['results'][0]['geometry']['location']['lng'];
        }

        
        $array['geo_query'] = array(
            'lat_field' => 'lat',  // this is the name of the meta field storing latitude
            'lng_field' => 'long', // this is the name of the meta field storing longitude 
            'latitude'  => $zipLat,    // this is the latitude of the point we are getting distance from
            'longitude' => $ziplng,   // this is the longitude of the point we are getting distance from
            'distance'  => 50,           // this is the maximum distance to search
            'units'     => 'kilometers'       // this supports options: miles, mi, kilometers, km
        );
    
    }

     
    $tax_query = array();
    if(!empty($_POST['category'])){
        $tax_query1 = array(
                'taxonomy' => 'location_cat',
                'field'    => 'term_id',
                'terms'    => $_POST['category'], 
            );
     $tax_query =   array_merge($tax_query,$tax_query1); 
    }
    if(!empty($_POST['type'])){
      $tax_query2 = array(
            'taxonomy' => 'location_type',
            'field'    => 'term_id',
            'terms'    => $_POST['type'], 
        );
       $tax_query =   array_merge($tax_query,$tax_query2); 

    }
    if(!empty($_POST['category']) || !empty($_POST['type'])){
     $array['tax_query'] = array(
     'relation' => 'AND',
      $tax_query

     );

    }
   $arr[0] = $array['geo_query']['longitude'];//log
   $arr[1] = $array['geo_query']['latitude'];//lat
   echo json_encode($arr);
   exit();
//     echo "<pre>";
//     print_r($array); 
//     echo "</pre>";
    // $args = array_merge($args,$array);
    // echo "<pre>";
    // print_r($args); 
    // echo "</pre>";
    exit;
    
}
add_action( 'wp_ajax_nopriv_cat_fillter', 'cat_fillter' );
add_action( 'wp_ajax_cat_fillter', 'cat_fillter' );


function shortcode_pagegrid() {
   $page_id= get_the_ID();
	
   $args = array(
    'post_type'      => 'page', //write slug of post type
    'posts_per_page' => -1,
    'post_parent'    => $page_id,
    'order'          => 'ASC',
    'orderby'        => 'menu_order'
 );
 
 
$childrens = new WP_Query( $args );
 
if ( $childrens->have_posts() ) : ?>
     <div class="row justify-content-center pagegrid_row">
    <?php while ( $childrens->have_posts() ) : $childrens->the_post();
     $img = get_the_post_thumbnail_url(get_the_ID(),'medium'); ?>
      <div class="col-md-6 col-lg-4 pagegrid_coulam" id="hidden-<?php the_ID(); ?>">
		  <div class="children" id="child-<?php the_ID(); ?>">
        <a href="<?php the_permalink(); ?>">
                        <div class="page_grid">
                            <div class="page_grid_img">
							 <img src=" <?php if( get_field('thumbnaill_image_') ): the_field('thumbnaill_image_');  else: echo $img; endif; ?> " alt="">
                            </div>
							
                           <h4 class="page_grid_title"><?php 
	                        
									the_title();			
							   
							   ?>
                        	 </h4>
                        </div></a>
                    </div>   
			    </div>
 
    <?php    endwhile; 
		 if($page_id == '110') : ?>
 		  <div class="col-md-6 col-lg-4 pagegrid_coulam">
		  <div class="children" id="child-additional">
        <a target="_blank" href="https://www.nasck.org/kaddish/"> <div class="page_grid">
                            <div class="page_grid_img">
                                <img src="<?php echo site_url();?>/wp-content/uploads/2021/09/chevra-kadisha-kadish.jpg" alt="">
                            </div>
                           <h4 class="page_grid_title"> Kaddish Services </h4>
                        </div></a>
                    </div>   
			    </div>
		  <div class="col-md-6 col-lg-4 pagegrid_coulam">
		  <div class="children" id="child-additional">
          <a target="_blank" href="http://www.nasck.org"> 
			  <div class="page_grid">
                            <div class="page_grid_img">
                                <img src="<?php echo site_url();?>/wp-content/uploads/2021/09/chevra-kadisha-nasck.jpg" alt="">
                            </div>
                           <h4 class="page_grid_title">Visit NASCK </h4>
                        </div></a>
                    </div>   
			    </div>
		  <?php        endif; ?>
	  	</div>
    <?php        endif; 
        wp_reset_query(); 
}
add_shortcode('pagegrid', 'shortcode_pagegrid');



//location category filter
   
/*add_action('wp_ajax_get_loc_cat', 'get_loc_cat');
add_action('wp_ajax_nopriv_get_loc_cat', 'get_loc_cat');
	 
function get_loc_cat(){ 
	  $c_id = $_POST['cat_id'];
	$args = array(
 'post_type' => 'location_cpt',
 'post_status' => 'publish',
'tax_query' => array(
    array(
    'taxonomy' => 'location_cat',
    'field' => 'term_id',
    'terms' => $c_id
     )
  )
);
	$my_query = null;
$my_query = new WP_Query( $args );
	 if( $my_query->have_posts() ) { 
         $arrayData1 = array();   
		 $listing = '';
		  $i=1;
			while ( $my_query->have_posts()) {
			$my_query->the_post();
			$title= get_the_title(); 
			 $img = get_the_post_thumbnail_url(get_the_ID(),'full');
			 $type_name = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
                 if( have_rows('address') ):
                            while( have_rows('address') ): the_row();
                             $cp_city = get_sub_field('city');?>
					<?php	endwhile; 
					 	endif; 
			$listing.= '<div class="listing"><h3><a href= "'.get_permalink() .'">'.$title .'</a></h3>';
			$listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-cutlery"></i>'.$type_name[0].'</div></div>'; 
		 $listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-street-view"></i>'.$cp_city.'</div></div>';
	 $listing.='</div>';
	 $arrayData1[] = 
                   array('lat'=>get_field('lat',get_the_ID()),'long'=>get_field('long',get_the_ID()),'label'=>get_the_title()); 
		 $i++;
			}
		   } 
	   echo json_encode(array('list'=>$listing,'location'=>$arrayData1));   
	exit();
}



// location cat listview
   
add_action('wp_ajax_get_loc_cat_list', 'get_loc_cat_list');
add_action('wp_ajax_nopriv_get_loc_cat_list', 'get_loc_cat_list');
	 
function get_loc_cat_list(){ 
	  $c_id = $_POST['cat_id'];
	$args = array(
 'post_type' => 'location_cpt',
 'post_status' => 'publish',
'tax_query' => array(
    array(
    'taxonomy' => 'location_cat',
    'field' => 'term_id',
    'terms' => $c_id
     )
  )
);
	$my_query = null;
$my_query = new WP_Query( $args );
	  if( $my_query->have_posts() ) {?>

          	<?php while ($my_query->have_posts()) : $my_query->the_post();  
			$cat = wp_get_object_terms( get_the_ID(), 'location_cat', array( 'fields' => 'names' ) ) ;
			$establishment_type = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
		   if( have_rows('address') ):
                            while( have_rows('address') ): the_row();
                             $cp_city = get_sub_field('city'); 
							 $address1 = get_sub_field('address_line_1'); 
							 $address2 = get_sub_field('address_line_2'); 
									   
								   endwhile; 
									endif; ?>	  
               <tr>
        <td><a href="<?php echo get_permalink();?>"> <?php the_title(); ?> </a></td>
        <td><?php echo $cat[0];?></td>
        <td><?php echo $address1;?></td>
        <td><?php echo $cp_city;?></td>
		 <td><?php echo $establishment_type[0];?></td>		   
        
      </tr>
		  <?php  endwhile;
   } 
  
	exit();
 
}
*/

// establishment type

add_action('wp_ajax_get_loc_type', 'get_loc_type');
add_action('wp_ajax_nopriv_get_loc_type', 'get_loc_type');
	 
function get_loc_type(){  
	
		$type_id = $_POST['type_id'];

	$selected_cp_city = $_POST['city_id'];
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array(
 'post_type' => 'location_cpt',
		'posts_per_page' => 10,
		'order'   => 'ASC',
		'orderby' => 'title', 
			'paged'          => $paged,
 'post_status' => 'publish',
'tax_query' => array(
    array(
    'taxonomy' => 'location_type',
    'field' => 'term_id',
    'terms' => $type_id
     )
  )
);

 	$query = null;
$query = new WP_Query( $args );
	 if($query->have_posts() ) { 
         $arrayData1 = array();   
		 $listing = '';
		  $i=1;
			while ( $query->have_posts()) {
			$query->the_post();
			$title= get_the_title(); 
			 $img = get_the_post_thumbnail_url(get_the_ID(),'full');
			 $type_name = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
                 if( have_rows('address') ):
                            while( have_rows('address') ): the_row();
                             $cp_city = get_sub_field('city');?>
					<?php	endwhile; 
					 	endif; 
			 $optionv = "View All";
				$optiont = "View Allc";
			
// echo "<pre>";
// print_r((".loc-type").val('-1')); 
if(($selected_cp_city == $optionv) || ($type_id == $type_name[0]))
			{
				$listing.= '<div class="listing"><h3><a href= "'.get_permalink() .'">'.$title .'</a></h3>';
			$listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-cutlery"></i>'.$type_name[0].'</div></div>'; 
		 $listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-street-view"></i>'.$cp_city.'</div></div>';
	 $listing.='</div>';
			}
	
 elseif (($selected_cp_city == $cp_city) || ($type_id == $type_name[0])){
								$listing.= '<div class="listing"><h3><a href= "'.get_permalink() .'">'.$title .'</a></h3>';
				$listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-cutlery"></i>'.$type_name[0].'</div></div>'; 
		 $listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-street-view"></i>'.$cp_city.'</div></div>';
	 $listing.='</div>';
  }
			if(($selected_cp_city == $optionv) || ($type_id == $type_name[0])){
				 $arrayData1[] = 
                   array('lat'=>get_field('lat',get_the_ID()),'long'=>get_field('long',get_the_ID()),'label'=>get_the_title()); 	
			}	
		 elseif (($selected_cp_city == $cp_city) || ($type_id == $type_name[0])){
				 $arrayData1[] = 
                   array('lat'=>get_field('lat',get_the_ID()),'long'=>get_field('long',get_the_ID()),'label'=>get_the_title());  
		 }		
			}
		
		   } 
	

	   echo json_encode(array('list'=>$listing,'location'=>$arrayData1));   
	 
     
	exit();
}

//City
add_action('wp_ajax_get_loc_city', 'get_loc_city');
add_action('wp_ajax_nopriv_get_loc_city', 'get_loc_city');

function get_loc_city(){ 
	  $selected_cp_city = $_POST['city_id'];
	  $type_id = $_POST['type_id'];
	 
	    	$args = array(
			'post_type'  => 'location_cpt',
			'posts_per_page' => 10,
				'order'   => 'ASC',
		'orderby' => 'title', 
			'post_status' => 'publish',
				'relation' => 'AND',
				'tax_query' => array(
    array(
    'taxonomy' => 'location_type',
    'field' => 'term_id',
    'terms' => $type_id
     )
  )
);
	 	$args1 = array(
			'post_type'  => 'location_cpt',
			'posts_per_page' => -1,
			'order'   => 'ASC',
		'orderby' => 'title', 
			'post_status' => 'publish'
);

	$query = null;
$query = new WP_Query( $args );
	$query1 = null;
$query1 = new WP_Query( $args1 );
	
	 if($query->have_posts() ) { 
         $arrayData2 = array();   
		 $listing = '';
		  $i=1;
	     	 while ( $query->have_posts()) {
			$query->the_post();
			  if( have_rows('address') ):
				     while( have_rows('address') ): the_row();
                    $cp_city = get_sub_field('city'); 
			         $title= get_the_title(); 
			        $img = get_the_post_thumbnail_url(get_the_ID(),'full');
				    $type_name = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
			     	endwhile; 
				 	endif; 
				  $optionv = "View All";
				$optiont = "View All";

 
		 if (($selected_cp_city == $cp_city) || ($type_id == $type_name[0])) {
		 	$listing.= '<div class="listing"><h3><a href= "'.get_permalink() .'">'.$title .'</a></h3>';
		$listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-cutlery"></i>'.$type_name[0].'</div></div>'; 
		 $listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-street-view"></i>'.$cp_city.'</div></div>';
	 $listing.='</div>';
				  }


				  if (($selected_cp_city == $cp_city) || ($type_id == $type_name[0])) {
				 	 $arrayData2[] = 
                   array('lat'=>get_field('lat',get_the_ID()),'long'=>get_field('long',get_the_ID()),'label'=>get_the_title()); 
				  } 
				 

	 	 }
	 }
 elseif($query1->have_posts() ) { 
         $arrayData2 = array();   
		 $listing = '';
		  $i=1;
	     	 while ( $query1->have_posts()) {
			$query1->the_post();
			  if( have_rows('address') ):
				     while( have_rows('address') ): the_row();
                    $cp_city = get_sub_field('city'); 
			         $title= get_the_title(); 
			        $img = get_the_post_thumbnail_url(get_the_ID(),'full');
				    $type_name = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
				 
			     	endwhile; 
				 	endif; 
			  $optionv = "View All";
				$optiont = "View All";
 
				 
				 
		 if ($selected_cp_city == $cp_city) {
					  	$listing.= '<div class="listing"><h3><a href= "'.get_permalink() .'">'.$title .'</a></h3>';
			$listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-cutlery"></i>'.$type_name[0].'</div></div>'; 
		 $listing.='<div class="row"><div class="col col-lg-6"><i class="fa fa-street-view"></i>'.$cp_city.'</div></div>';
	 $listing.='</div>';
				 
				 }

	 if ($selected_cp_city == $cp_city) {
		 $arrayData2[] = 
                   array('lat'=>get_field('lat',get_the_ID()),'long'=>get_field('long',get_the_ID()),'label'=>get_the_title()); 
		 $i++; 
		  }
			
				 
	 	 }
	 }
	 echo json_encode(array('list'=>$listing,'location'=>$arrayData2));   
		
	exit();
}



// city list view


add_action('wp_ajax_get_loc_city_list', 'get_loc_city_list');
add_action('wp_ajax_nopriv_get_loc_city_list', 'get_loc_city_list');
	 
function get_loc_city_list(){ 
	   $selected_cp_city = $_POST['city_id'];
	   $type_id = $_POST['type_id'];
	 $args = array(
			'post_type'  => 'location_cpt',
			'posts_per_page' => -1,
			'post_status' => 'publish',
				'tax_query' => array(
    array(
    'taxonomy' => 'location_type',
    'field' => 'term_id',
    'terms' => $type_id
     )
  )
);
	 	$args1 = array(
			'post_type'  => 'location_cpt',
			'posts_per_page' => -1,
			'post_status' => 'publish'
);

	$my_query = null;
    $my_query = new WP_Query( $args );
	
	$my_query1 = null;
$my_query1 = new WP_Query( $args1 );
	
	
	    if( $my_query->have_posts() ) {?>

          	<?php while ($my_query->have_posts()) : $my_query->the_post();  
			$cat = wp_get_object_terms( get_the_ID(), 'location_cat', array( 'fields' => 'names' ) ) ;
			$establishment_type = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
		   if( have_rows('address') ):
                            while( have_rows('address') ): the_row();
                             $cp_city = get_sub_field('city'); 
							 $address1 = get_sub_field('address_line_1'); 
							 $address2 = get_sub_field('address_line_2'); 
									   
								   endwhile; 
									endif; 
      if (($selected_cp_city == $cp_city) || ($type_id == $type_name[0])) {?>
               <tr>
        <td><a href="<?php echo get_permalink();?>"><?php the_title(); ?> </a></td>
        <td><?php echo $cat[0];?></td>
        <td><?php echo $address1;?></td>
        <td><?php echo $cp_city;?></td>
		 <td><?php echo $establishment_type[0];?></td>		   
        
      </tr> <?php }	 elseif ($_POST['cp_city'] == "View All") {?>
   <tr>
        <td><a href="<?php echo get_permalink();?>"><?php the_title(); ?> </a></td>
        <td><?php echo $cat[0];?></td>
        <td><?php echo $address1;?></td>
        <td><?php echo $cp_city;?></td>
		 <td><?php echo $establishment_type[0];?></td>		   
        
      </tr> 

			<?php	}

?>


		

	

		  

		  <?php   endwhile;
   } 
	   elseif( $my_query1->have_posts() ) {?>

          	<?php while ($my_query1->have_posts()) : $my_query1->the_post();  
			$cat = wp_get_object_terms( get_the_ID(), 'location_cat', array( 'fields' => 'names' ) ) ;
			$establishment_type = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
		   if( have_rows('address') ):
                            while( have_rows('address') ): the_row();
                             $cp_city = get_sub_field('city'); 
							 $address1 = get_sub_field('address_line_1'); 
							 $address2 = get_sub_field('address_line_2'); 
									   
								   endwhile; 
									endif; 
      if (($selected_cp_city == $cp_city) || ($type_id == $type_name[0])) {?>
               <tr>
        <td><a href="<?php echo get_permalink();?>"><?php the_title(); ?> </a></td>
        <td><?php echo $cat[0];?></td>
        <td><?php echo $address1;?></td>
        <td><?php echo $cp_city;?></td>
		 <td><?php echo $establishment_type[0];?></td>		   
        
      </tr> <?php }	 elseif ($_POST['cp_city'] == "View All") {?>
   <tr>
        <td><a href="<?php echo get_permalink();?>"><?php the_title(); ?> </a></td>
        <td><?php echo $cat[0];?></td>
        <td><?php echo $address1;?></td>
        <td><?php echo $cp_city;?></td>
		 <td><?php echo $establishment_type[0];?></td>		   
        
      </tr> 

			<?php	} 

?>
		  <?php  endwhile;
   }
	   exit();
 }






// establishment type in list view


add_action('wp_ajax_get_loc_type_list', 'get_loc_type_list');
add_action('wp_ajax_nopriv_get_loc_type_list', 'get_loc_type_list');
	 
function get_loc_type_list(){ 
	    $type_id = $_POST['type_id'];
	 
	 $selected_cp_city = $_POST['city_id'];
	$args = array(
 'post_type' => 'location_cpt',
		'posts_per_page' => -1,
 'post_status' => 'publish',
'tax_query' => array(
    array(
    'taxonomy' => 'location_type',
    'field' => 'term_id',
    'terms' => $type_id
     )
  )
);
	$my_query = null;
    $my_query = new WP_Query( $args );
	    if( $my_query->have_posts() ) {?>

          	<?php while ($my_query->have_posts()) : $my_query->the_post();  
			$cat = wp_get_object_terms( get_the_ID(), 'location_cat', array( 'fields' => 'names' ) ) ;
			$establishment_type = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
		   if( have_rows('address') ):
                            while( have_rows('address') ): the_row();
                             $cp_city = get_sub_field('city'); 
							 $address1 = get_sub_field('address_line_1'); 
							 $address2 = get_sub_field('address_line_2'); 
									   
								   endwhile; 
									endif; 	
$optionv = "View All";?>
             <?php if (($selected_cp_city == $cp_city) || ($type_id == $type_name[0])) { ?>
<tr>
        <td><a href="<?php echo get_permalink();?>"><?php the_title(); ?> </a></td>
        <td><?php echo $cat[0];?></td>
        <td><?php echo $address1;?></td>
        <td><?php echo $cp_city;?></td>
		 <td><?php echo $establishment_type[0];?></td>		   
        
      </tr>
<?php } 
											 elseif(($selected_cp_city == $optionv) || ($type_id == $type_name[0])){?>
 
								<tr>
        <td><a href="<?php echo get_permalink();?>"><?php the_title(); ?> </a></td>
        <td><?php echo $cat[0];?></td>
        <td><?php echo $address1;?></td>
        <td><?php echo $cp_city;?></td>
		 <td><?php echo $establishment_type[0];?></td>		   
        
      </tr>
<?php 
					 
												 
											 } 
		?>			


		  <?php  endwhile;
   } 
	   exit();
 }


add_action('wp_ajax_get_list_view', 'get_list_view');
add_action('wp_ajax_nopriv_get_list_view', 'get_list_view');
	 
 function get_list_view(){ 
       $args=array(
	'post_type' => 'location_cpt',// You can set custom post type here
	'post_status' => 'publish',
	'posts_per_page' => -1, 
	'orderby' => 'title', 
	'order' => 'ASC', 
	);
   
	$my_query = null;
	$my_query = new WP_Query($args);
	if( $my_query->have_posts() ) {?>
      <div class="list_view" >
		 <table class="table table-striped table-bordered table-hover" id="myTable">
    <thead>
      <tr>
        <th width="25%">Name</th>
        <th width="20%">Establishment Type</th>  
        <th width="20%">Address</th>
        <th width="15%">City</th>
		
       </tr>
    </thead>
    <tbody class="data_list">
		<?php while ($my_query->have_posts()) : $my_query->the_post();  
			$cat = wp_get_object_terms( get_the_ID(), 'location_cat', array( 'fields' => 'names' ) ) ;
			$cat_ids = wp_get_object_terms( get_the_ID(), 'location_cat',array( ) )    ;
			    $loccategory = $cat_ids[0];
             	$cat_id  = $loccategory->term_id;
								   
								   
								   
		$establishment_type = wp_get_object_terms( get_the_ID(), 'location_type', array( 'fields' => 'names' ) ) ;
		     $establishment_ids = wp_get_object_terms( get_the_ID(), 'location_type',array( ) )    ;
								 
		      $category = $establishment_ids[0];
             $establishment_id = $category->term_id;
								    
		   if( have_rows('address') ):
                            while( have_rows('address') ): the_row();
                             $cp_city = get_sub_field('city'); 
							 $address1 = get_sub_field('address_line_1'); 
							 $address2 = get_sub_field('address_line_2'); 
									   
								   endwhile; 
									endif; ?>	  
               <tr>
        <td><a href="<?php echo get_permalink();?>"><?php the_title(); ?> </a></td>
     <td data-id="<?php echo $establishment_id;?>"><?php echo $establishment_type[0];?></td>		   
        <td ><?php echo $address1;?></td>
        <td ><?php echo $cp_city;?></td>
		
        
      </tr>
		  <?php  endwhile; ?>
	     
	  </tbody>
		</table>
	 </div>
<?php  }
	 ?>
	
 <?php  exit();
 }