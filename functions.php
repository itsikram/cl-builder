<?php

/**
 * litsign functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package litsign
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}


if (file_exists(dirname(__FILE__) . '/cmb2/init.php')) {
	require_once(dirname(__FILE__) . '/cmb2/init.php');
}
if (file_exists(dirname(__FILE__) . '/template/display_admin_orders.php')) {
	require_once(dirname(__FILE__) . '/template/display_admin_orders.php');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wholesale_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on litsign, use a find and replace
	 * to change 'litsign' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('litsign', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'header-menu' => esc_html__('Primary', 'litsign'),
		),

	);
	register_nav_menus(
		array(
			'header-bottom-menu' => esc_html__('Header Bottom', 'litsign'),
		)

	);
	register_nav_menus(
		array(
			'category-filter-menu' => esc_html__('Category Filter Menu', 'litsign'),
		)

	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'litsign_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);

	register_post_type(
		'product',
		array(
			'label' => 'Product',
			'supports' => array('title', 'editor', 'thumbnail'),
			'labels' => array(
				'name' => 'Products',
				'singular_name' => 'Product'

			),
			'public' => true,
		)
	);
	register_post_type('cnn', array(
		'label' => 'CNN',
		'supports' => array('title', 'editor', 'thumbnail'),
		'labels' => array(
			'name' => 'CNNS',
			'singular_name' => 'CNN'
		),
		'public' => true,

	));

	register_post_type('order', array(
		'label' => 'Order',
		'public' => true,
		'supports' => array('title', 'editor', 'thumbnail'),
		'has_archive' => true, // Enable archive for the custom post type
		'rewrite' => array('slug' => 'order'), // Custom slug for your post type
		'show_in_rest' => true, // Enable block editor support
	));



	$category_labels = array(
		'name' => _x('Product Categories', 'taxonomy general name'),
		'singular_name' => _x('Product Category', 'taxonomy singular name'),
		'search_items' => __('Category Subjects'),
		'all_items' => __('All Categories'),
		'parent_item' => __('Parent Category'),
		'parent_item_colon' => __('Parent Category:'),
		'edit_item' => __('Edit Category'),
		'update_item' => __('Update Category'),
		'add_new_item' => __('Add New Category'),
		'new_item_name' => __('New Category Name'),
		'menu_name' => __('Category'),
	);

	// Now register the taxonomy
	register_taxonomy(
		'product_category',
		array('product'),
		array(
			'hierarchical' => true,
			'labels' => $category_labels,
			'show_ui' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array('slug' => 'cpecial-category')
		)
	);

	$subscriber_role = get_role( 'subscriber' );

    if ( $subscriber_role ) {
        $subscriber_role->add_cap( 'upload_files' ); // Allow file uploads
    }

}
add_action('after_setup_theme', 'wholesale_setup');

function register_order_post_statuses()
{
	$statuses = array(
		'on_hold'    => 'On Hold',
		'processing' => 'Processing',
		'completed'  => 'Completed',
		'failed'     => 'Failed',
	);

	foreach ($statuses as $status => $label) {
		register_post_status($status, array(
			'label'                     => _x($label, 'post'),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop("$label <span class='count'>(%s)</span>", "$label <span class='count'>(%s)</span>"),
		));
	}
}
add_action('init', 'register_order_post_statuses');


function add_custom_status_to_dropdown()
{
	global $post;

	if ($post->post_type === 'order') {
?>
		<script>
			jQuery(document).ready(function($) {
				var selectedStatus = $('#hidden_post_status').val();

				$('#post_status').change(e => {

					$('#hidden_post_status').val(e.target.value);

				})

				$('#post_status').append('<option value="on_hold" ' + (selectedStatus === 'on_hold' ? 'selected="selected"' : '') + '>On Hold</option>');
				$('#post_status').append('<option value="processing" ' + (selectedStatus === 'processing' ? 'selected="selected"' : '') + '>Processing</option>');
				$('#post_status').append('<option value="completed" ' + (selectedStatus === 'completed' ? 'selected="selected"' : '') + '>Completed</option>');
				$('#post_status').append('<option value="failed" ' + (selectedStatus === 'failed' ? 'selected="selected"' : '') + '>Failed</option>');
			});
		</script>
	<?php
	}
}
add_action('post_submitbox_misc_actions', 'add_custom_status_to_dropdown');


function save_custom_post_status($post_id, $post)
{
	if ($post->post_type === 'order' && isset($_POST['post_status'])) {
		$new_status = sanitize_text_field($_POST['hidden_post_status']);
		$valid_statuses = array('on_hold', 'processing', 'completed', 'failed');

		if (in_array($new_status, $valid_statuses)) {
			remove_action('save_post', 'save_custom_post_status');
			return wp_update_post(array(
				'ID'          => $post_id,
				'post_status' => $new_status
			));
			add_action('save_post', 'save_custom_post_status');
		}
	}
}
add_action('save_post', 'save_custom_post_status', 10, 2);


function register_custom_bulk_action($bulk_actions)
{
	global $post_type;

	if ($post_type == 'order') { // Replace 'order' with your custom post type ID
		$bulk_actions['failed'] = __('Mark as Failed');
		$bulk_actions['on_hold'] = __('Mark as On Hold');
		$bulk_actions['processing'] = __('Mark as Processing');
		$bulk_actions['completed'] = __('Mark as Completed');
	}

	return $bulk_actions;
}
add_filter('bulk_actions-edit-order', 'register_custom_bulk_action'); // Replace 'order' with your custom post type ID

function handle_custom_bulk_action($redirect_to, $doaction, $post_ids)
{

	echo $doaction;

	if ($doaction) {
		foreach ($post_ids as $post_id) {
			// Perform your custom bulk action on each post here
			$new_status = 'processing'; // Define your new status
			wp_update_post(array(
				'ID' => $post_id,
				'post_status' => $doaction,
			));
		}

		// Add a query variable to the redirect URL to display a message after the action
		$redirect_to = add_query_arg('bulk_processed_posts', count($post_ids), $redirect_to);
	}

	return $redirect_to;
}
add_filter('handle_bulk_actions-edit-order', 'handle_custom_bulk_action', 10, 3);



/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function litsign_content_width()
{
	$GLOBALS['content_width'] = apply_filters('litsign_content_width', 640);
}
add_action('after_setup_theme', 'litsign_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function litsign_widgets_init()
{
	register_sidebar(
		array(
			'name' => esc_html__('Sidebar', 'litsign'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Add widgets here.', 'litsign'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);
}
add_action('widgets_init', 'litsign_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function litsign_scripts()
{
	wp_enqueue_style('bootsrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), _S_VERSION);
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/all.min.css', array(), _S_VERSION);
	wp_enqueue_style('litsign-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/style.css', array(), _S_VERSION);
	//wp_enqueue_style('zebra_dialog', get_template_directory_uri() . '/css/zebra_dialog.css', array(), _S_VERSION);

	wp_style_add_data('litsign-style', 'rtl', 'replace');

	wp_enqueue_script('bootsrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery'), _S_VERSION, true);

	wp_enqueue_script('eModal', get_template_directory_uri() . '/js/jquery.eModal.js', array(), _S_VERSION, true);
	//wp_enqueue_script('stripe', 'https://js.stripe.com/v3/', array(), _S_VERSION, false);
	wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '8.5.3', true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}


	if (is_page('channel-letter-builder')) {
		wp_enqueue_script('cl', get_template_directory_uri() . '/js/cl.js', array('jquery', 'redux', 'konva'), '8.5.8', true);
		wp_enqueue_script('konva', 'https://cdn.jsdelivr.net/npm/konva@8.3.5/konva.min.js', array(), _S_VERSION, true);
		wp_enqueue_script('redux', get_template_directory_uri() . '/js/redux.min.js', array(), _S_VERSION, true);
		wp_enqueue_style('cl', get_template_directory_uri() . '/css/cl.css', array(), _S_VERSION);

	}
}
add_action('wp_enqueue_scripts', 'litsign_scripts');


function my_enqueue($hook)
{


	wp_enqueue_style('bootsrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), _S_VERSION);
	wp_enqueue_style('admin-style', get_template_directory_uri() . '/css/admin.css', array(), _S_VERSION);


	wp_enqueue_script('admin-script', get_template_directory_uri() . '/js/admin-script.js');
}

add_action('admin_enqueue_scripts', 'my_enqueue');



add_action('cmb2_admin_init', function () {



	// $cnn = new_cmb2_box(
	// 	array(
	// 		'id' => 'product_details',
	// 		'title' => __('Product Details', 'tm'),
	// 		'object_types' => array('cnn'),
	// 		'show_names' => true,
	// 	)
	// );

	// $cnn->add_field(
	// 	array(
	// 		'name' => __('Cnn Type', 'tm'),
	// 		'type' => 'text',
	// 		'id' => '_cnn_type',
	// 	)
	// );

	// $cnn->add_field(
	// 	array(
	// 		'name' => __('Cnn Number', 'tm'),
	// 		'type' => 'text',
	// 		'id' => '_cnn_number',
	// 	)
	// );

	// $cnn->add_field(
	// 	array(
	// 		'name' => __('Cnn Type', 'tm'),
	// 		'type' => 'text',
	// 		'id' => '_cnn_exp',
	// 	)
	// );

	// $cnn->add_field(
	// 	array(
	// 		'name' => __('Cnn Type', 'tm'),
	// 		'type' => 'text',
	// 		'id' => '_cnn_cvv',
	// 	)
	// );



	// adding custom meta fields for products
	$product = new_cmb2_box(
		array(
			'id' => 'product_details',
			'title' => __('Product Details', 'tm'),
			'object_types' => array('product'),
			'show_names' => true,
		)
	);

	$product->add_field(
		array(
			'name' => __('Product Order Index', 'tm'),
			'type' => 'text',
			'id' => '_order_by_index',
			'default' => '999999'
		)
	);

	$product->add_field(
		array(
			'name' => __('Min Height', 'tm'),
			'type' => 'text',
			'id' => '_min_height',
			'default' => 1,
		)
	);

	$product->add_field(
		array(
			'name' => __('Max Height', 'tm'),
			'type' => 'text',
			'id' => '_max_height',
			'default' => 100,
		)
	);

	$product->add_field(
		array(
			'name' => __('Min Width', 'tm'),
			'type' => 'text',
			'id' => '_min_width',
			'default' => 1,
		)
	);
	$product->add_field(
		array(
			'name' => __('Max Width', 'tm'),
			'type' => 'text',
			'id' => '_max_width',
			'default' => 100,
		)
	);

	$product->add_field(
		array(
			'name' => __('Product Min Sqft', 'tm'),
			'type' => 'text',
			'id' => '_min_sqft',
			'default' => '1'
		)
	);

	$product->add_field(
		array(
			'name' => __('Product Price Per Sqft', 'tm'),
			'type' => 'text',
			'id' => '_price_per_sqft',
		)
	);
	$product->add_field(
		array(
			'name' => __('Discount Percent', 'tm'),
			'type' => 'text',
			'id' => '_discount_percent',
		)
	);


	$product->add_field(
		array(
			'name' => __('Product Starting At Text', 'tm'),
			'type' => 'wysiwyg',
			'id' => '_starting_at_text',
		)
	);

	$product->add_field(
		array(
			'name' => __('Product Starting At Atribute Name', 'tm'),
			'type' => 'text',
			'id' => '_starting_at_options',
		)
	);

	$product->add_field(
		array(
			'name' => __('Prouduct List Description', 'tm'),
			'id' => '_product_list_desc',
			'type' => 'wysiwyg',
		)
	);

	$product->add_field(
		array(
			'name' => __('Prouduct Short Description', 'tm'),
			'id' => '_product_short_desc',
			'type' => 'wysiwyg',
		)
	);



	$product->add_field(
		array(
			'name' => __('Prouduct Gallery', 'tm'),
			'id' => '_product_gallery',
			'type' => 'file_list',
		)
	);
	$product->add_field(
		array(
			'name' => __('Prouduct Group Data', 'tm'),
			'id' => '_product_group_data',
			'type' => 'textarea',
			'default' => '[
				{
					"slug": "null",
					"title": "null"
				}
			]',
		)
	);
	$product->add_field(
		array(
			'name' => __('Hide Calculator', 'tm'),
			'id' => '_hide_calculator',
			'type' => 'checkbox',
			'default' => 'on'
		)
	);

	$product->add_field(
		array(
			'name' => __('Show In List', 'tm'),
			'id' => '_show_in_list',
			'type' => 'checkbox',
			'default' => 'on',
		)
	);


	$product->add_field(
		array(
			'name' => __('Has Upload Artwork Option', 'tm'),
			'id' => '_has_upload_artwork',
			'type' => 'checkbox',
			'default' => false,
		)
	);
	$product->add_field(
		array(
			'name' => __('Prouduct Turnaround', 'tm'),
			'id' => '_product_turnaround',
			'type' => 'text',
			'default' => '1'
		)
	);

	$product->add_field(
		array(
			'name' => __('Trimcap Color', 'tm'),
			'id' => '_trimcap_color',
			'type' => 'text',
			'desc' => 'If you added trimcap multiple colors you don\'t need to add color here'
		)
	);

	$product->add_field(
		array(
			'name' => __('Return Color Same as face color', 'tm'),
			'id' => '_return_color',
			'type' => 'checkbox',
		)
	);



	$product->add_field(
		array(
			'name' => __('Prouduct Info Content return text', 'tm'),
			'id' => '_info_content_return_text',
			'type' => 'text',
		)
	);

	$product->add_field(
		array(
			'name' => __('Prouduct Info Content return image', 'tm'),
			'id' => '_info_content_return_image',
			'type' => 'file',
		)
	);

	$product->add_field(
		array(
			'name' => __('Prouduct Info Content trimcap text', 'tm'),
			'id' => '_info_content_trimcap_text',
			'type' => 'text',
		)
	);

	$product->add_field(
		array(
			'name' => __('Prouduct Info Content trimcap image', 'tm'),
			'id' => '_info_content_trimcap_image',
			'type' => 'file',
		)
	);

	$product->add_field(
		array(
			'name' => __('Prouduct Info Content face text', 'tm'),
			'id' => '_info_content_face_text',
			'type' => 'text',
		)
	);


	$product->add_field(
		array(
			'name' => __('Prouduct Info Content face image', 'tm'),
			'id' => '_info_content_face_image',
			'type' => 'file',
		)
	);

	// product addtional info box

	$pai = new_cmb2_box(
		array(
			'id' => 'product_additional_info',
			'title' => __('Product Additional Info', 'tm'),
			'object_types' => array('product'),
			'show_names' => true,
		)
	);

	$pai->add_field(
		array(
			'name' => __('Prouduct Description', 'tm'),
			'id' => '_product_description',
			'type' => 'wysiwyg',

		)
	);

	$pai->add_field(
		array(
			'name' => __('Prouduct Component', 'tm'),
			'id' => '_product_component',
			'type' => 'wysiwyg',

		)
	);

	$pai->add_field(
		array(
			'name' => __('Prouduct Warrenty', 'tm'),
			'id' => '_product_warrenty',
			'type' => 'wysiwyg',

		)
	);

	$pai->add_field(
		array(
			'name' => __('Prouduct FAQ', 'tm'),
			'id' => '_product_faq',
			'type' => 'wysiwyg',

		)
	);

	$pai->add_field(
		array(
			'name' => __('Prouduct Manual', 'tm'),
			'id' => '_product_manual',
			'type' => 'wysiwyg',

		)
	);



	$peo = new_cmb2_box(
		array(
			'id' => 'product_extra_options',
			'title' => __('Product Extra Options', 'tm'),
			'object_types' => array('product'),
			'show_names' => true,
		)
	);

	$peo->add_field(
		array(
			'name' => __('Show Power Supply Option', 'tm'),
			'id' => '_is_ps_option',
			'type' => 'checkbox',
			'default' => true,

		)
	);
	$peo->add_field(
		array(
			'name' => __('Show Lit Option', 'tm'),
			'id' => '_is_lit_option',
			'type' => 'checkbox',
			'default' => true,

		)
	);
	$peo->add_field(
		array(
			'name' => __('Show Cable Option', 'tm'),
			'id' => '_is_cable_option',
			'type' => 'checkbox',
			'default' => true,

		)
	);

	$peo->add_field(
		array(
			'name' => __('Standard Power Supply Cost', 'tm'),
			'id' => '_standard_ps_cost',
			'type' => 'text',
			'default' => '90',

		)
	);
	$peo->add_field(
		array(
			'name' => __('Back Lit Cost', 'tm'),
			'id' => '_backlit_cost',
			'type' => 'text',
			'default' => '237.24',

		)
	);
	$peo->add_field(
		array(
			'name' => __('Cable Cost (8ft)', 'tm'),
			'id' => '_eight_ft_cable_cost',
			'type' => 'text',
			'default' => '70',

		)
	);

	$peo->add_field(
		array(
			'name' => __('Has Trimcap', 'tm'),
			'id' => '_has_trimcap',
			'type' => 'checkbox',

		)
	);
	$peo->add_field(
		array(
			'name' => __('Has Return', 'tm'),
			'id' => '_has_return',
			'type' => 'checkbox',

		)
	);
	$peo->add_field(
		array(
			'name' => __('Has Face', 'tm'),
			'id' => '_has_face',
			'type' => 'checkbox',

		)
	);
	$peo->add_field(
		array(
			'name' => __('Default Face', 'tm'),
			'id' => '_default_face',
			'type' => 'text',

		)
	);
	$peo->add_field(
		array(
			'name' => __('Default Color Cost', 'tm'),
			'id' => '_default_color_cost',
			'type' => 'text',
			'default' => 0

		)
	);
	$peo->add_field(
		array(
			'name' => __('Default Return', 'tm'),
			'id' => '_default_return',
			'type' => 'text',

		)
	);
	$peo->add_field(
		array(
			'name' => __('Default Trimcap', 'tm'),
			'id' => '_default_trimcap',
			'type' => 'text',

		)
	);









	// adding custom meta fields for products
	$cart = new_cmb2_box(
		array(
			'id' => 'cart_details',
			'title' => __('Cart Details', 'tm'),
			'object_types' => array('cart'),
			'show_names' => true,
		),
	);


	$cart->add_field(array(
		'name' => 'User Id',
		'id' => 'user_id',
		'type' => 'text',
	));

	$cart->add_field(array(
		'name' => 'Cart Items',
		'id' => 'cart_items',
		'type' => 'textarea',
	));
	$cart->add_field(array(
		'name' => 'Total Cart Price',
		'id' => 'total_cart_price',
		'type' => 'text',
	));
});


// Add the Product Turnaround field to Quick Edit
function cmb2_quick_edit_custom_box_product($column_name, $post_type)
{
	if ($column_name == '_product_turnaround' && $post_type == 'product') {
	?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<label>
					<span class="title"><?php _e('Product Turnaround', 'cmb2'); ?></span>
					<span class="input-text-wrap">
						<input type="text" name="_product_turnaround" value="">
					</span>
				</label>
			</div>
		</fieldset>
	<?php
	}
}
add_action('quick_edit_custom_box', 'cmb2_quick_edit_custom_box_product', 10, 2);

// Add Quick Edit Column for Product Turnaround
function cmb2_add_quick_edit_column_product($columns)
{
	$columns['_product_turnaround'] = __('Product Turnaround', 'cmb2');
	return $columns;
}
add_filter('manage_product_posts_columns', 'cmb2_add_quick_edit_column_product');

// Save Quick Edit Data for Product Turnaround
function cmb2_save_quick_edit_data_product($post_id)
{
	if (isset($_POST['_product_turnaround'])) {
		update_post_meta($post_id, '_product_turnaround', sanitize_text_field($_POST['_product_turnaround']));
	}
}
add_action('save_post', 'cmb2_save_quick_edit_data_product');


add_action('init', 'start_session', 1);

function start_session()
{
	if (!session_id()) {
		session_start();
	}
}

// Load Quick Edit Data for Product Turnaround


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}




// Custom product attribute meta box
function custom_attribute_product_meta_box()
{
	add_meta_box(
		'product_attr',
		'Product Attributes',
		'render_product_attr_meta_box',
		'product',
		'normal',
		'default'
	);
}
add_action('add_meta_boxes', 'custom_attribute_product_meta_box');

function render_product_attr_meta_box($post)
{
	// Retrieve existing values from the database
	$product_attr = get_post_meta($post->ID, 'product_attr', true);
	$has_upload_artwork = get_post_meta($post->ID, '_has_upload_artwork', true);
	$hide_calculator = get_post_meta($post->ID, '_hide_calculator', true);
	$show_in_list = get_post_meta($post->ID, '_show_in_list', true);
	?>
	<input type="hidden" name="has_custom_artwork" value="<?php echo $has_upload_artwork; ?>" id="hasCustomArtwork">
	<input type="hidden" name="hide_calculator" value="<?php echo $hide_calculator; ?>" id="hideCalculator">
	<input type="hidden" name="show_in_list" value="<?php echo $show_in_list; ?>" id="showInList">

	<?php

	$product_attr_array = json_decode($product_attr);

	if ($product_attr == '[{') {
	?>
		<input type="hidden" name="product_attr" id="productAttrJson" value="[]">


	<?php
	} else {
	?>
		<input type="hidden" name="product_attr" id="productAttrJson" value='<?php echo $product_attr; ?>'>

	<?php
	}
	?>





	<div class="product-attr-container default">
		<?php
		if ($product_attr_array == true) {
			for ($i = 0; $i < count($product_attr_array); $i++) {

				$allOptions = $product_attr_array[$i]->options;
		?>
				<div class="product-attr mt-2 attr-<?php echo $product_attr_array[$i]->name; ?>" data-opname="<?php echo $product_attr_array[$i]->name; ?>">
					<div class="attr-name">
						<div class="row">
							<div class="col">
								<label for="">Attribute Name</label>
								<input type="text" name="attr-title" disabled="" value="<?php echo $product_attr_array[$i]->name; ?>" placeholder="Display Option" class="form-control">
							</div>
							<div class="col"><label> Attribute Type </label><select type="text" value="<?php echo $product_attr_array[$i]->type; ?>" name="attr-type" class="form-select">
									<option value="normal"> Normal </option>
									<option value="flat"> Flat </option>
									<option value="percent"> Percent </option>
									<option value="lft"> Linear Ft. </option>
									<option value="sqft"> Squre Ft. </option>
								</select></div>
							<div class="col"><label for="">Css Class</label> <input type="text" name="css-class" value="<?php echo $product_attr_array[$i]->cssClass; ?>" class="form-control"></div>
						</div>



					</div>
					<div class="attibute-options">
						<?php
						foreach ($allOptions as $option) {

							foreach ($option as $name => $price) ?>
							<div class="row opt-row">
								<div class="col">
									<div class="form-group">
										<label for="" class="form-label">Variant Title</label>
										<input type="text" value="<?php echo $name; ?>" data-opname="<?php echo $product_attr_array[$i]->name?  trim($product_attr_array[$i]->name) : ''; ?>" placeholder="Single Sided" name="attr-name" class="variable-title form-control">
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label class="form-label">Variant Price</label>
										<input data-opname="<?php echo $product_attr_array[$i]->name; ?>" type="text" placeholder="10" value="<?php echo $price ? trim($price) : 0; ?>" name="attr-price" class="variable-price form-control">
									</div>
								</div>
							</div>

						<?php }; ?>
					</div>
					<a class="btn btn-primary button-large mt-2 addOptBtn" data-opname="<?php echo $product_attr_array[$i]->name; ?>">Add Option</a>
					<a class="btn btn-danger button-large mt-2 removeAttr" data-opname="<?php echo $product_attr_array[$i]->name; ?>">Remove Attribute</a>
				</div>

		<?php
			}
		};

		?>

	</div>
	<input type="text" class="attr-title mt-2 mr-2 " placeholder="New Attribute title"><a class="button button-success button-large mt-2" id="addAttrBtn">Add attribute</a> <a class="button button-primary button-large mt-2 saveOptBtn">Save Options</a>

	<?php
}

// Save meta box data
function save_product_attr_meta($post_id)
{

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	if (!current_user_can('edit_post', $post_id))
		return;
	if (isset($_POST['product_attr'])) {

		if ($_POST['product_attr'] == '[{') {
			return;
		} else {
			update_post_meta($post_id, 'product_attr', sanitize_text_field($_POST['product_attr']));
		}
	}
}
add_action('save_post', 'save_product_attr_meta');




// Custom product cl meta box
function custom_cl_product_meta_box()
{
	add_meta_box(
		'product_cl_data',
		'Channel Letter Product Data',
		'render_product_cl_meta_box',
		'product',
		'normal',
		'default'
	);
}
add_action('add_meta_boxes', 'custom_cl_product_meta_box');

function render_product_cl_meta_box($post)
{
	// Retrieve existing values from the database
	$product_cl_data = get_post_meta($post->ID, 'product_cl_data', true);
	//update_post_meta($post->ID, 'product_attr', '[]');

	$product_cl_array = json_decode($product_cl_data);

	if ($product_cl_data == '[{') {
	?>
		<input type="hidden" name="product_cl_data" id="productClJson" value="[]">


	<?php
	} else {
	?>
		<input type="hidden" name="product_cl_data" id="productClJson" value="<?php echo $product_cl_data; ?>">

	<?php
	}
	?>





	<div class="product-cl-container default">
		<?php
		if ($product_cl_array == true) {
			for ($i = 0; $i < count($product_cl_array); $i++) {

				$allOptions = $product_cl_array[$i]->options;
		?>
				<div class="product-attr mt-2 attr-<?php echo $product_cl_array[$i]->id; ?>" data-opname="<?php echo $product_cl_array[$i]->id; ?>">
					<div class="attr-name">
						<div class="row">
							<div class="col">
								<label for="">Id</label>
								<input type="text" name="attr-id" disabled="" value="<?php echo $product_cl_array[$i]->id; ?>" class="form-control">
							</div>
							<div class="col"><label> Heading </label><input type="text" value="<?php echo $product_cl_array[$i]->heading; ?>" name="attr-heading" class="form-control" />
							</div>
							<div class="col"><label for="">Cost</label> <input type="text" name="css-class" value="<?php echo $product_cl_array[$i]->cost; ?>" class="form-control"></div>

						</div>



					</div>
					<div class="attibute-options">
						<?php
						foreach ($allOptions as $option) {

							foreach ($option as $name => $price) ?>
							<div class="row opt-row">
								<div class="col">
									<div class="form-group">
										<label for="" class="form-label">Variant Title</label>
										<input type="text" value="<?php echo $name; ?>" data-opname="<?php echo $product_cl_array[$i]->id; ?>" placeholder="Single Sided" name="attr-name" class="variable-title form-control">
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label class="form-label">Variant Value</label>
										<input data-opname="<?php echo $product_cl_array[$i]->id; ?>" type="text" placeholder="10" value="<?php echo $price ? $price : 0; ?>" name="attr-price" class="variable-price form-control">
									</div>
								</div>
							</div>

						<?php }; ?>
					</div>
					<a class="btn btn-primary button-large mt-2 addOptBtn" data-opname="<?php echo $product_cl_array[$i]->id; ?>">Add Option</a>
					<a class="btn btn-danger button-large mt-2 removeAttr" data-opname="<?php echo $product_cl_array[$i]->id; ?>">Remove Attribute</a>
				</div>

		<?php
			}
		};

		?>

	</div>
	<input type="text" class="attr-title mt-2 mr-2 " placeholder="New Attribute title">
	<a class="button button-success button-large mt-2" id="addClAttrBtn">Add attribute</a> <a class="button button-primary button-large mt-2 saveClBtn">Save Options</a>

<?php
}

// Save meta box data
function save_product_cl_meta($post_id)
{

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	if (!current_user_can('edit_post', $post_id))
		return;
	if (isset($_POST['product_cl_data'])) {

		if ($_POST['product_cl_data'] == '[{') {
			return;
		} else {
			update_post_meta($post_id, 'product_cl_data', sanitize_text_field($_POST['product_cl_data']));
		}
	}
}
add_action('save_post', 'save_product_cl_meta');

function allow_unauthenticated_media_route($result)
{
	// Check if we are in the media POST route
	$route = $_SERVER['REQUEST_URI'];
	if (strpos($route, '/wp-json/wp/v2/media') !== false && $_SERVER['REQUEST_METHOD'] === 'POST') {
		return true; // Bypass authentication
	}

	if (! empty($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/media/') !== false) {
		// Bypass authentication for this specific route
		return true;
	}

	return $result;
}
add_filter('rest_authentication_errors', 'allow_unauthenticated_media_route');


function custom_cron_intervals($schedules)
{
	$schedules['every_five_minutes'] = array(
		'interval' => 300, // 300 seconds = 5 minutes
		'display'  => __('Every 5 Minutes'),
	);
	return $schedules;
}
add_filter('cron_schedules', 'custom_cron_intervals');



function schedule_image_deletion()
{
	if (!wp_next_scheduled('delete_scheduled_images')) {
		wp_schedule_event(time(), 'yearly', 'delete_scheduled_images'); // Use 'every_five_minutes' for custom intervals
	}
}
add_action('wp', 'schedule_image_deletion');

function delete_scheduled_images()
{
	// Get the current time
	function delete_old_uploaded_images($title)
	{
		// Query to get attachment by its title

		$current_time = current_time('timestamp');

		// Set the time threshold (e.g., 30 days ago)
		$time_threshold = strtotime('-30 Days', $current_time);

		$args = array(
			'post_type'   => 'attachment',  // Specify that we are looking for attachments
			'post_status' => 'inherit',     // Attachments have an 'inherit' status
			's'       => $title,        // Title of the attachment to search for
			'date_query'     => array(
				array(
					'column' => 'post_date',
					'before' => date('Y-m-d H:i:s', $time_threshold),
				),
			),
		);

		// Get the attachment(s)
		$attachments = get_posts($args);

		// Check if attachment is found
		if (!empty($attachments)) {
			// Return the attachment (or you can loop if you allow multiple results)
			if ($attachments) {
				foreach ($attachments as $attachment) {
					$attachment_id = $attachment->ID;
					wp_delete_attachment($attachment_id, true);
				}
			}
		}
	}

	delete_old_uploaded_images('custom-artwork');
	delete_old_uploaded_images('clDesign');
}

add_action('delete_scheduled_images', 'delete_scheduled_images');


// Add an image field to add new taxonomy term
function add_taxonomy_image_field()
{ ?>

	<div class="form-field">
		<label for="taxonomy-ref"><?php _e('Reference Category Slug', 'wholesale'); ?></label>
		<input type="text" id="taxonomy-ref" name="taxonomy-ref" value="" />
		<p class="description"><?php _e('', 'wholesale'); ?></p>
	</div>

	<div class="form-field">
		<label for="taxonomy-image"><?php _e('Category Image', 'wholesale'); ?></label>
		<input type="text" id="taxonomy-image" name="taxonomy-image" value="" />
		<p class="description"><?php _e('Upload an image for this term.', 'wholesale'); ?></p>
		<button class="button button-secondary upload_image_button">Upload Image</button>
	</div>
	<script>
		jQuery(document).ready(function($) {
			var mediaUploader;
			$('.upload_image_button').click(function(e) {
				e.preventDefault();
				if (mediaUploader) {
					mediaUploader.open();
					return;
				}
				mediaUploader = wp.media.frames.file_frame = wp.media({
					title: 'Choose Image',
					button: {
						text: 'Choose Image'
					},
					multiple: false
				});
				mediaUploader.on('select', function() {
					var attachment = mediaUploader.state().get('selection').first().toJSON();
					$('#taxonomy-image').val(attachment.url);
				});
				mediaUploader.open();
			});
		});
	</script>
<?php }
add_action('category_add_form_fields', 'add_taxonomy_image_field', 10, 2);
add_action('product_category_add_form_fields', 'add_taxonomy_image_field', 10, 2);

// Add an image field to edit taxonomy term
function edit_taxonomy_image_field($term)
{
	$term_id = $term->term_id;
	$image_url = get_term_meta($term_id, 'taxonomy-image', true);
	$taxonomy_ref = get_term_meta($term_id, 'taxonomy-ref', true);

?>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="taxonomy-ref"><?php _e('Reference Category', 'wholesale'); ?></label>
		</th>
		<td>
			<input type="text" id="taxonomy-ref" name="taxonomy-ref" value="<?php echo esc_html($taxonomy_ref); ?>" />
			<p class="description"><?php _e('', 'wholesale'); ?></p>

		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="taxonomy-image"><?php _e('Image', 'wholesale'); ?></label>
		</th>
		<td>
			<input type="text" id="taxonomy-image" name="taxonomy-image" value="<?php echo esc_attr($image_url); ?>" />
			<p class="description"><?php _e('Upload an image for this term.', 'wholesale'); ?></p>
			<button class="button button-secondary upload_image_button">Upload Image</button>
			<?php if ($image_url): ?>
				<br><img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width:150px;margin-top:10px;" />
			<?php endif; ?>
		</td>
	</tr>
	<script>
		jQuery(document).ready(function($) {
			var mediaUploader;
			$('.upload_image_button').click(function(e) {
				e.preventDefault();
				if (mediaUploader) {
					mediaUploader.open();
					return;
				}
				mediaUploader = wp.media.frames.file_frame = wp.media({
					title: 'Choose Image',
					button: {
						text: 'Choose Image'
					},
					multiple: false
				});
				mediaUploader.on('select', function() {
					var attachment = mediaUploader.state().get('selection').first().toJSON();
					$('#taxonomy-image').val(attachment.url);
				});
				mediaUploader.open();
			});
		});
	</script>
<?php }
add_action('category_edit_form_fields', 'edit_taxonomy_image_field', 10, 2);
add_action('product_category_edit_form_fields', 'edit_taxonomy_image_field', 10, 2);


// Save the image field
function save_taxonomy_image_field($term_id)
{
	if (isset($_POST['taxonomy-image'])) {
		update_term_meta($term_id, 'taxonomy-image', esc_url_raw($_POST['taxonomy-image']));
	}
	if (isset($_POST['taxonomy-ref'])) {
		update_term_meta($term_id, 'taxonomy-ref', esc_html($_POST['taxonomy-ref']));
	}
}
add_action('edited_category', 'save_taxonomy_image_field', 10, 2);
add_action('create_category', 'save_taxonomy_image_field', 10, 2);
add_action('edited_product_category', 'save_taxonomy_image_field', 10, 2);
add_action('create_product_category', 'save_taxonomy_image_field', 10, 2);


