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

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function litsign_setup()
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
			'menu-1' => esc_html__('Primary', 'litsign'),
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

	$category_labels = array(
		'name' => _x( 'Product Categories', 'taxonomy general name' ),
		'singular_name' => _x( 'Product Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Category Subjects' ),
		'all_items' => __( 'All Categories' ),
		'parent_item' => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item' => __( 'Edit Category' ), 
		'update_item' => __( 'Update Category' ),
		'add_new_item' => __( 'Add New Category' ),
		'new_item_name' => __( 'New Category Name' ),
		'menu_name' => __( 'Category' ),
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
				'rewrite' => array('slug' => 'cpecial-category' )
			)
		);
}
add_action('after_setup_theme', 'litsign_setup');

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
	wp_enqueue_style('bootsrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), _S_VERSION);
	wp_enqueue_style('litsign-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/style.css', array(), _S_VERSION);

	wp_style_add_data('litsign-style', 'rtl', 'replace');

	wp_enqueue_script('bootsrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), _S_VERSION, true);
	wp_enqueue_script('stripe', 'https://js.stripe.com/v3/', array(), _S_VERSION, false);
	wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/main.js', array('jquery'), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
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
			'name' => __('Min Height', 'tm'),
			'type' => 'text',
			'id' => '_min_height',
			'default' => 1,
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
			'name' => __('Product Price Per Sqft', 'tm'),
			'type' => 'text',
			'id' => '_price_per_sqft',
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
			'name' => __('Prouduct Metarial', 'tm'),
			'id' => '_product_metarial',
			'type' => 'text',
		)
	);

	$product->add_field(
		array(
			'name' => __('Prouduct Print', 'tm'),
			'id' => '_product_print',
			'type' => 'text',
		)
	);

	$product->add_field(
		array(
			'name' => __('Prouduct Lamination', 'tm'),
			'id' => '_product_lamination',
			'type' => 'text',
		)
	);
	$product->add_field(
		array(
			'name' => __('Prouduct Gallery', 'tm'),
			'id' => '_product_gallery',
			'type' => 'file_list',
		)
	);
});

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



function add_custom_meta_box()
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
add_action('add_meta_boxes', 'add_custom_meta_box');

function render_product_attr_meta_box($post)
{
	// Retrieve existing values from the database
	$product_attr = get_post_meta($post->ID, 'product_attr', true);
	//update_post_meta($post->ID, 'product_attr', '[]');

	$product_attr_array = json_decode($product_attr);

	if ($product_attr == '[{') {
?>
		<input type="hidden" name="product_attr" id="productAttrJson" value="[]">


	<?php
	} else {
	?>
		<input type="hidden" name="product_attr" id="productAttrJson" value="<?php echo $product_attr; ?>">

	<?php
	}
	?>





	<div class="product-attr-container default">
		<?php
		if ($product_attr_array == true) {
			for ($i = 0; $i < count($product_attr_array); $i++) {

				$allOptions = $product_attr_array[$i]->options;
		?>
				<div class="product-attr mt-2 attr-<?php echo $product_attr_array[$i]->name;  ?>" data-opname="<?php echo $product_attr_array[$i]->name;  ?>">
					<div class="attr-name">
						<div class="row">
							<div class="col">
								<label for="">Attribute Name</label>
								<input type="text" name="attr-title" disabled="" value="<?php echo $product_attr_array[$i]->name;  ?>" placeholder="Display Option" class="form-control">
							</div>
							<div class="col"><label> Attribute Type </label><select type="text" value="<?php echo $product_attr_array[$i]->type;  ?>" name="attr-type" class="form-select">
									<option value="normal"> Normal </option>
									<option value="flat"> Flat </option>
									<option value="percent"> Percent </option>
									<option value="lft"> Linear Ft. </option>
									<option value="sqft"> Squre Ft. </option>
								</select></div>
							<div class="col"><label for="">Css Class</label> <input type="text" name="css-class" value="<?php echo $product_attr_array[$i]->cssClass;  ?>" class="form-control"></div>
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
										<input type="text" value="<?php echo $name; ?> " data-opname="<?php echo $product_attr_array[$i]->name;  ?>" placeholder="Single Sided" name="attr-name" class="variable-title form-control">
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<label class="form-label">Variant Price</label>
										<input data-opname="<?php echo $product_attr_array[$i]->name;  ?>" type="text" placeholder="10" value="<?php echo $price ? $price : 0; ?>" name="attr-price" class="variable-price form-control">
									</div>
								</div>
							</div>

						<?php }; ?>
					</div>
					<a class="btn btn-primary button-large mt-2 addOptBtn" data-opname="<?php echo $product_attr_array[$i]->name;  ?>">Add Option</a>
					<a class="btn btn-danger button-large mt-2 removeAttr" data-opname="<?php echo $product_attr_array[$i]->name;  ?>">Remove Attribute</a>
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
