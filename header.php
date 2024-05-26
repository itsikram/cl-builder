<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package litsign
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">


	<style>
		.footer {}

		.footer-bg-image-1 {
			background-image: url(<?php echo get_template_directory_uri() . '/img/footer-bg-1.jpg'; ?>);

		}

		.footer-bg-image-2 {
			background-image: url(<?php echo get_template_directory_uri() . '/img/footer-bg-2.jpg'; ?>);
		}
	</style>

	<style>
		@font-face {
			font-family: 'nimbus-sans';
			/*a name to be used later*/
			src: url('<?php echo get_template_directory_uri().'/fonts/NimbusSanL-Bol.otf'; ?>');
			font-weight: bold;
			/*URL to font*/
		}

		@font-face {
			font-family: 'type-writer';
			src: url('<?php echo get_template_directory_uri().'/fonts/TYPEWR_B.TTF'; ?>');
			font-weight: bold;
		}
		@font-face {
			font-family: 'alegreya';
			src: url('<?php echo get_template_directory_uri().'/fonts/Alegreya-VariableFont_wght.ttf'; ?>');
		}
		@font-face {
			font-family: 'anton';
			src: url('<?php echo get_template_directory_uri().'/fonts/Anton-Regular.ttf'; ?>');
		}
	</style>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'litsign'); ?></a>


		<header class="main-header">
			<div class="container">
				<div class="row d-flex align-items-center justify-content-center">
					<div class="col-md-2">
						<div class="logo-container">
							<a href="<?php echo home_url().'/home'; ?>">
								<img src="<?php echo get_template_directory_uri() . '/img/logo.png'; ?>" alt="<?php echo bloginfo('title'); ?>" class="header-logo">
							</a>
						</div>
					</div>
					<div class="col-md-8">

						<?php
						wp_nav_menu( array(
							'theme_location' => 'header-menu',
							'container_class' => 'header-menu-container',
							'menu_class' => 'header-menu text-center'
						) );
						?>
					</div>
					<div class="col-md-2">

						<?php if (!is_user_logged_in()) { ?>
							<form class="header-account-form" method="POST" action="<?php echo site_url() . '/login'; ?>">
								<div class="row align-items-center d-flex">
									<!-- <div class="col-md-3 col-sm-6">
										<input type="text" name="email" class="form-control" id="inlineFormInput" placeholder="Email">
									</div>
									<div class="col-md-3 my-1 col-sm-6">
										<input name="password" type="text" class="form-control" id="inlineFormInputGroup" placeholder="Password">
									</div> -->
									<div class="col-md-12 mb-sm-1 col-sm-12 text-center">
										<button type="submit" class="btn btn-primary account-action-button p-0">login</button>
										<a href="<?php echo site_url() . '/signup'; ?>" class="btn btn-danger account-action-button p-0">Register</a>
									</div>

								</div>
							</form>
						<?php } else {
						?>
							<div class="header-menu-container account-menu">
								<ul class="header-menu text-end">
									<li class="menu-item"><a href="#">Orders</a></li>
									<li class="menu-item"><a href="<?php echo site_url() . '/account'; ?>">My Account</a></li>
								</ul>
							</div>

						<?php
						} ?>
					</div>


				</div>
			</div>
		</header>

		<?php if (isset($_GET['type'])) {
			$type = $_GET['type'];

			$message = isset($_GET['message']) ? $_GET['message'] : '';
		?>
			<div class="container mt-3">
				<div class="row">
					<div class="col-md-6 offset-md-3 col-6 text-center">
						<div class="alert alert-<?php echo $type; ?>">
							<?php echo $message; ?>
						</div>
					</div>
				</div>
			</div>


		<?php
		}