<?php
// === Optimized <picture> tag output with WebP fallback ===
function theme_optimized_image( $image_id, $size = 'large', $class = '', $loading = 'lazy' ) {
  if ( ! $image_id ) return;

  // Get original and WebP versions
  $image_src  = wp_get_attachment_image_src( $image_id, $size );
  $image_webp = wp_get_attachment_image_url( $image_id, $size, false, [ 'type' => 'image/webp' ] );
  $alt        = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
  $width      = $image_src[1];
  $height     = $image_src[2];

  echo '<picture>';
  echo '<source srcset="' . esc_url( $image_webp ) . '" type="image/webp">';
  echo '<img src="' . esc_url( $image_src[0] ) . '" ';
  echo 'width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" ';
  echo 'alt="' . esc_attr( $alt ) . '" loading="' . esc_attr( $loading ) . '" ';
  echo 'class="' . esc_attr( $class ) . '" />';
  echo '</picture>';
}

// === Featured Image Output Helper ===
function theme_post_thumbnail( $size = 'large', $class = '' ) {
  if ( has_post_thumbnail() ) {
    $image_id = get_post_thumbnail_id();
    theme_optimized_image( $image_id, $size, $class );
  }
}

//Use the following snippet to use the theme optimised image in templates

// $image = get_field('custom_image');
// if ( $image ) {
//   theme_optimized_image( $image['ID'], 'medium_large', 'my-img-class' );
// }

