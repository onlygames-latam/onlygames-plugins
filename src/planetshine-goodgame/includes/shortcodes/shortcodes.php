<?php

add_shortcode( 'rating', 'goodgame_review_summary_rating' );
add_shortcode( 'weather', 'goodgame_weather_widget' );

if(!function_exists('sc_review_summary_rating'))
{
	function goodgame_review_summary_rating($atts, $content) {
		ob_start();
		global $post;

		extract( shortcode_atts( array(
			'title' => '',
			'value' => '1',
		), $atts ) );

		$range = 10;

		$value = str_replace(',', '.', $value);
		
		if(is_numeric($value) && $value <= $range && $value >= 0)
		{
			$percent = $value * 10;
			$value_out = number_format($value, 1);
			$value_out = str_replace('.5', '<small>.5</small>', $value_out);
			$value_out = str_replace('.0', '<small>.0</small>', $value_out);
			if( $percent > 0 && $percent <= 100)
			{
				?>
					<div class="row">
						<div class="rating-title"><?php echo esc_html($title); ?></div>
						<div class="rating-value">
							<b><?php echo $value_out;?></b>
						</div>
						<div class="bar-wrapper">
							<span class="bar"><s data-value="<?php echo esc_attr($percent); ?>"></s></span>
						</div>
					</div>
				<?php
			}
		}
		$return = ob_get_contents();
		ob_end_clean();
		wp_reset_postdata();
		return $return;
	}
}

function goodgame_weather_widget($atts, $content) {
	ob_start();
    
    GoodGameInstance()->weather_widget_placeholder();
    
    $return = ob_get_contents();
    ob_end_clean();
    return $return;
}
?>