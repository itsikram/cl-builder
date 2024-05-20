<?php
// template name: Account

if (!is_user_logged_in()) {
    wp_redirect(site_url());
}

if(isset($_REQUEST['logout'])){
    wp_logout();
    wp_redirect(site_url());

}
get_header();?>


<div class="container py-5">
    <div class="row">
        <div class="col">
            <h1>Cooming Soon</h1>
            <a href="<?php echo get_permalink().'?logout=true'?>" class="btn btn-danger">Log Out</a>
        </div>
    </div>
</div>



<?php get_footer();