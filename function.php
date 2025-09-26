add_action( 'wp_enqueue_scripts', function () {
    if ( ! class_exists( 'WooCommerce' ) ) return;

    // Swiper assets
    wp_register_style( 'swiper', 'https://unpkg.com/swiper@9/swiper-bundle.min.css', [], '9.0.0' );
    wp_register_script( 'swiper', 'https://unpkg.com/swiper@9/swiper-bundle.min.js', [], '9.0.0', true );

    // Custom styling
    wp_add_inline_style( 'swiper', "
        .wc-cat-slider-container {
            position: relative;
            width: 100%;
        }
        
        .wc-cat-slider { 
            width:100%; 
            position:relative; 
            padding-bottom:50px; 
        } /* space for pagination */
        
        .wc-cat-slide { background:#fff; border:1px solid #eee; border-radius:12px; overflow:hidden; display:flex; flex-direction:column; height:100%; text-align:center; }
        .wc-cat-slide .thumb { position:relative; width:100%; padding-bottom:100%; overflow:hidden; }
        .wc-cat-slide .thumb img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
        .wc-cat-slide .content { padding:15px; display:flex; flex-direction:column; flex:1; justify-content:flex-start; align-items:center; }
        .wc-cat-slide .title { font-size:15px; font-weight:500; margin:8px 0; line-height:1.4; text-decoration:none; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; min-height:4.2em; text-align:center; }
        .wc-cat-slide .title a { color:#222; text-decoration:none !important; }
        .wc-cat-slide .price { margin:8px 0; font-weight:600; color:#111; text-align:center; }

        /* Pagination padding */
        .wc-cat-slider .swiper-pagination { margin-top:20px; }

        /* Custom Arrows - Positioned outside the slider */
        .wc-cat-slider-container .arrow-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            font-weight: bold;
            font-size: 16px;
            color: #333;
            z-index: 30;
        }
        
        .wc-cat-slider-container .arrow-btn:hover {
            background: #f8f8f8;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .wc-cat-slider-container .arrow-btn.left {
            left: -45px;
        }
        
        .wc-cat-slider-container .arrow-btn.right {
            right: -45px;
        }

        @media(max-width:767px){
            .wc-cat-slider-container .arrow-btn {
                display: none !important;
            }
        }
    " );
} );

add_shortcode( 'wc_category_slider', function ( $atts ) {
    if ( ! class_exists( 'WooCommerce' ) ) return '';

    $atts = shortcode_atts( [
        'category'      => '',
        'limit'         => 8,
        'autoplay'      => 'true',
        'speed'         => 4000,
        'mobile_cols'   => 1.5,
        'tablet_cols'   => 2.5,
        'desktop_cols'  => 4,
    ], $atts );

    $limit       = max( 1, intval( $atts['limit'] ) );
    $category    = sanitize_text_field( $atts['category'] );
    $autoplay    = filter_var( $atts['autoplay'], FILTER_VALIDATE_BOOLEAN );
    $speed       = intval( $atts['speed'] );
    $mobileCols  = floatval( $atts['mobile_cols'] );
    $tabletCols  = floatval( $atts['tablet_cols'] );
    $desktopCols = floatval( $atts['desktop_cols'] );

    // Product query
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
    ];
    if ( $category ) {
        $args['tax_query'] = [[
            'taxonomy' => 'product_cat',
            'field'    => is_numeric( $category ) ? 'term_id' : 'slug',
            'terms'    => $category,
        ]];
    }

    $loop = new WP_Query( $args );
    if ( ! $loop->have_posts() ) return '<p>No products found.</p>';

    wp_enqueue_style( 'swiper' );
    wp_enqueue_script( 'swiper' );

    $uid = 'wc-slider-' . wp_generate_uuid4();
    ob_start(); ?>
    
    <div class="wc-cat-slider-container">
        <div id="<?php echo esc_attr( $uid ); ?>" class="wc-cat-slider swiper">
            <div class="swiper-wrapper">
                <?php while ( $loop->have_posts() ) : $loop->the_post();
                    $product   = wc_get_product( get_the_ID() );
                    $title     = get_the_title();
                    $permalink = get_permalink();
                    $price     = $product->get_price_html();
                    $image     = wp_get_attachment_image_src( $product->get_image_id(), 'medium_large' );
                    $thumbnail = $image ? $image[0] : wc_placeholder_img_src();
                ?>
                <div class="swiper-slide">
                    <article class="wc-cat-slide">
                        <a class="thumb" href="<?php echo esc_url( $permalink ); ?>">
                            <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                        </a>
                        <div class="content">
                            <h3 class="title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h3>
                            <div class="price"><?php echo wp_kses_post( $price ); ?></div>
                        </div>
                    </article>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        
        <!-- Custom Arrows - Positioned completely outside the container -->
        <button class="arrow-btn left" aria-label="left">
            &lt;
        </button>
        <button class="arrow-btn right" aria-label="right">
            &gt;
        </button>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded",function(){
        new Swiper("#<?php echo esc_js( $uid ); ?>",{
            spaceBetween: 20,
            loop:true,
            navigation:{ 
                nextEl: ".wc-cat-slider-container .arrow-btn.right", 
                prevEl: ".wc-cat-slider-container .arrow-btn.left" 
            },
            pagination:{ el:"#<?php echo esc_js( $uid ); ?> .swiper-pagination", clickable:true },
            autoplay: <?php echo $autoplay ? '{delay:'.$speed.',disableOnInteraction:false}' : 'false'; ?>,
            breakpoints:{
                0:{ slidesPerView: <?php echo $mobileCols; ?> },
                768:{ slidesPerView: <?php echo $tabletCols; ?> },
                1024:{ slidesPerView: <?php echo $desktopCols; ?> }
            }
        });
    });
    </script>
    <?php
    return ob_get_clean();
} );
