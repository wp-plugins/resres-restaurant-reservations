<?php

$xyz = '';

echo "<div id='resres_menu'>";

$terms = get_terms( "menu_sections", array(
	'hide_empty' => 1,
	'orderby'       => 'id',
    'order'         => 'ASC',
) );


$section_options = get_option('resres_sections');


if(!$section_options) { echo __('Nothing here right now...'); } else {

	foreach ($section_options as $section) {

		foreach($terms as $term) {

			if( isset ($sections_array) ) {
				if(in_array($term->term_id, $sections_array) || in_array($term->slug, $sections_array) ) {

				} else {
					continue;
				}
			}

			if( isset ($exclude_array)  ){
				if(in_array($term->term_id, $exclude_array) || in_array($term->slug, $exclude_array) ) {
					continue;
				} else {
				}
			}

			if($section == $term->term_id) {
				$dishes_query = new WP_Query(
					array(
						'post_type' => 'dish',
						'posts_per_page' => 1000,
						'orderby'	=> 'menu_order',
						'order'		=> 'ASC',
						'tax_query' => array(
							array(
							'taxonomy' => 'menu_sections',
							'field' => 'slug',
							'terms' => $term->slug //was just $term, changed due to php notice.
							)
						)
					)
				);

$counter = 0;

				// Display the custom loop
				if ( $dishes_query->have_posts() ) { ?>

					<div class="resres_section_wrapper">
						<h2 class="resres_section_title" style="<?php echo $resres_colour; echo $resres_font_colour; ?>"><?php echo $term->name; ?></h2>

							<?php while ( $dishes_query->have_posts() ) : $dishes_query->the_post(); 
							?>
								<?php if ($counter === 0) { echo '<ul class="resres_ul">';} ?>

								<?php $ind_post_meta = get_post_custom( get_the_id() ); ?>
								<li class="resres_dish_li resres_dish_li_<?php //echo ($xyz++%2); ?>">
									<div class="resres_dish_wrapper">

										<h3 class="resres_dish_title">
											<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
											<span class="resres_template_price">
											<?php if($ind_post_meta['resres_dish_price'][0]) { echo $csymbol; echo $ind_post_meta['resres_dish_price'][0]; } ?>
											</span>
										</h3>


										<div class="resres_dish_content">
											<span class="resres_dish_thumbnail">
											<?php if(!$options['resres_disable_featured_images']) { echo the_post_thumbnail('thumbnail'); } ?>
											</span>
											<?php
											the_excerpt();
											?>

										</div>

										<div class="resres_dish_meta">

											<?php 
											do_action( 'resres_display_meta', $ind_post_meta, $resres_font_colour, $resres_colour ); 
											do_action( 'resres_display_chili', $ind_post_meta, $resres_font_colour, $resres_colour );								
											do_action( 'resres_display_wine', $ind_post_meta, $resres_font_colour, $resres_colour ); 
											//do_action( 'resres_display_misc', $ind_post_meta, $resres_font_colour, $resres_colour );
											?>

										</div>
									</div>
								</li>

								<?php $counter++; if ($counter == 2) { echo '</ul>';} if($counter === 2) { $counter = 0; }  ?>

							<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>

						</div>

						<?php }// end custom loop
			}
			else {
			}
		}

	}

echo "</div>";

}
