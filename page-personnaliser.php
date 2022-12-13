<?php get_header(); ?>


<?php 
    $id = "13";
    
    $products = wc_get_product($id); 
    echo $products;
    $size = $products->default_attributes->pa_tailles;
    $support = $products->default_attributes->pa_support;

?>

<?php  


    /**
     * 
     */

    // $args = [
    //     'post_type' => 'product',
    //     'post__in' => array($id)
    // ];

    // $wp = new WP_Query($args);
    // if ($wp->have_posts()){
    //     while ($wp->have_posts()) {
    //         $wp->the_post(); 
    // ?>


<!-- page-tableau-personnalise -->
<div class="container">
    <div class="tab-form-wrapper">
        <div class="tab-form-col">
            <div class="form-col-1">
                <?php the_post_thumbnail(); ?>
            </div>
            <div class="form-col-2">
                <h1><?php echo $products->name; ?></h1>
                <p><?php the_excerpt(); ?></p>
                
                <div class="tabloide-upload-inner">
                    <label class="tabloide-upload-file cp" for="tabloide-upload">
                        <div class="tabloide-upload-icon"><img src="<?= get_theme_file_uri().'/upload-solid.svg' ?>" alt="Image"></div>
                        <div class="tabloide-upload-text">Cliquez ici pour charger votre image <span class="red-color">*</span></div>
                    </label>
                    <!-- <div class="tabloide-upload-validator">
                        <img src="<?= get_theme_file_uri().'/check-solid.svg'; ?>" alt="Image">
                    </div> -->
                </div>
                <input type="file" name="" id="tabloide-upload" class="d-none">

                <p class="tabloide-text-confirm">
                    Votre image est correcte
                </p>

                <div class="tabloid-price">
                <?php echo $products->price; ?> €
                </div>


                <div class="tabloide-size">
                    <h2 class="tabloide-sizez-title size18">Tailles</h2>
                    
                    <div class="tabloide-size-display tabloide-bull-display">
                        <div class="tabloide-bull tabloide-bull-size default-attributes">XS</div>
                        <div class="tabloide-bull tabloide-bull-size">S</div>
                        <div class="tabloide-bull tabloide-bull-size">M</div>
                        <div class="tabloide-bull tabloide-bull-size">L</div>
                        <div class="tabloide-bull tabloide-bull-size">XL</div>
                        
                    </div>
                </div>
                <p class="tabloide-size-guid">
                    Guide des tailles
                </p>

                <div class="tabloide-support">
                    <h2 class="tabloide-support-title size18">Support</h2>
                    <div class="tabloide-support-display tabloide-bull-display">
                        <div class="tabloide-bull tabloide-bull-support"></div>
                        <div class="tabloide-bull tabloide-bull-support"></div>
                    </div>
                </div>

                <div class="tabloide-filters">
                    <h2 class="tabloide-filter-title size18">Filtres</h2>
                    <div class="tabloide-filter-display tabloide-bull-display">
                        <div class="tabloide-bull tabloide-bull-filter">X</div>
                        <div class="tabloide-bull tabloide-bull-filter bg-bw"></div>
                    </div>
                </div>

                <button class="click click-dark">
                    Ajouter au panier
                </button>

            </div>
        </div>
    </div>
</div>



    <?= get_footer(); ?>
    