<?php


// Template Name: home
get_header();

?>


<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1 col-sm-12">

            <div class="category-selecteor-container container mt-3">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="category-selector active" data-cat="all">All</div>
                        <div class="category-selector" data-cat="channel-letters">Channel Letters</div>
                        <div class="category-selector" data-cat="adhesive-products">Adhesive Products</div>
                    </div>
                </div>
            </div>

            <!-- <h1 class="text-center fs-2 mt-3">Adhesives Products</h1> -->
            <!-- <p class="text-center fs-5">Outdoor signage designed to be long-lasting and reusable</p> -->

            <div class="product-box-container d-flex flex-wrap">


                <?php

                $product_query = new WP_Query(array(
                    "post_type" => "product",
                    "post_per_page" => 99,
                    'order' => 'ASC',
                    'nopaging' => true,
                ));

                while ($product_query->have_posts()) {
                    $product_query->the_post();

                    $short_desc = get_post_meta(get_the_ID(), "_product_short_desc", true);
                    $price_per_sqft = get_post_meta(get_the_ID(), "_price_per_sqft", true);
                    $terms = get_the_terms(get_the_ID(), 'product_category');

                    $product_category_slug = isset($terms[0]) ? $terms[0]->slug : '';
                    $product_thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
                ?>
                    <div class="product-box" data-product-category="<?php echo $product_category_slug; ?>">
                        <a href="<?php echo get_permalink(); ?>">
                            <div class="pb-image-top">
                                <img src="<?php echo $product_thumbnail; ?>" alt="">
                            </div>
                            <div class="pb-details">
                                <h4 class="pb-title fs-5">
                                    <?php echo get_the_title(); ?>
                                </h4>
                                <div class="pb-description-list">
                                    <?php echo $short_desc; ?>
                                </div>
                                <hr>
                                <p class="pb-pricing d-flex justify-content-between">
                                    <span class="pb-title-short fs-6">
                                        Starting at
                                    </span>
                                    <span class="pb-price">
                                        $<?php echo $price_per_sqft; ?>
                                    </span>
                                </p>
                            </div>
                        </a>

                    </div>


                <?php
                } ?>

            </div>
        </div>
    </div>
</div>


<?php

get_footer();
