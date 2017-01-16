<?php

// Change post permalink to the URL saved in metadata
add_filter('the_permalink', 'my_filter_change_permalink', 10, 3);
/**
 * Change Post URl when it' a post from my_custom_post
 *
 * @param $url
 * @return mixed New URL
 */
function my_filter_change_permalink($url)
{
    if ('my_custom_post' === get_post_type()) {
        $post_url = get_post_meta(get_the_ID(), 'my_url_post_from_another_site', true);
        return $post_url;
    }
    return $url;
}

#### Change Post Thumbnail #####
# Trick to use an img from another URL instead of post thumbnail


add_filter("get_post_metadata", 'my_filter_image_thumbnail_meta', 99, 4);
/**
 *Filter to retrieve img url as true when post is my_custom_post
 *
 * As this custom post doesnt have thumbnail, this filter returns true
 * instead of a thumbnail id.
 * This value is used in another filters to get the image
 *
 * @param $a
 * @param $object_id
 * @param $meta_key
 * @param $single
 * @return bool
 */
function my_filter_image_thumbnail_meta($a, $object_id, $meta_key, $single)
{
    if ('my_custom_post' === get_post_type($object_id) && $meta_key === '_thumbnail_id') {
        return true;
    }
}

global $my_post_id;
$my_post_id = false;

add_filter("wp_get_attachment_image_src", 'my_filter_img_src', 1, 4);
/**
 * Filter to change IMG src url when casa_materias_marcas post_type
 *
 * @param $attr
 * @param $attachment
 * @param $size
 * @return mixed
 */
function my_filter_img_src($image, $attachment_id, $size, $icon)
{
    //verify whether post type is my_custom_post and attachment_id === true (passed in filter my_filter_image_thumbnail_meta)
    if ((get_post_type() === 'my_custom_post' || get_post_type() === 'post') && !$image && $attachment_id === true) {

        global $my_post_id;
        $p_id = $my_post_id ? $my_post_id : get_the_ID();

        if ($p_id) {
            $src = get_post_meta($p_id, 'my_featured_image_url', true);
            if ($src) {
                $image = array($src, '100%', 'auto', true);
            }
        }
    }
    //(url, width, height, is_intermediate)
    return $image;
}

add_filter("post_thumbnail_html", 'my_filter_img_html', 1, 5);
/**
 * Filter to get img html
 *
 * @param $html
 * @param $post_id
 * @param $post_thumbnail_id
 * @param $size
 * @param $attr
 * @return string
 */
function my_filter_img_html($html, $post_id, $post_thumbnail_id, $size, $attr)
{
    //$post_thumbnail_id === true only when custom post type my_custom_post due the filter my_filter_image_thumbnail_meta
    if (!$html && (get_post_type() === 'my_custom_post' || get_post_type() === 'post') && $post_thumbnail_id === true) {
        $src = get_post_meta($post_id, 'my_featured_image_url', true);
        if ($src) {
            $html = '<img src="' . esc_url($src) . '" class="attachment-' . esc_attr($size) . ' size-' . esc_attr($size) . ' wp-post-image" />';
        }
    }

    return $html;
}

/**
 * Seta o $my_post_id para funcionar corretamente em my_filter_img_materias_marcas_src
 *
 * @param $post_ID
 * @param $post_thumbnail_id
 * @param $size
 */
function my_init_filtro_fetch_thumb($post_ID, $post_thumbnail_id, $size)
{
    global $my_post_id;
    $my_post_id = $post_ID;
}
add_action('begin_fetch_post_thumbnail_html', 'my_init_filtro_fetch_thumb', 10, 3);

/**
 * Reseta o $my_post_id
 *
 * @param $post_ID
 * @param $post_thumbnail_id
 * @param $size
 */
function my_end_filtro_fetch_thumb($post_ID, $post_thumbnail_id, $size)
{
    global $my_post_id;
    $my_post_id = false;
}
add_action('end_fetch_post_thumbnail_html', 'my_end_filtro_fetch_thumb', 10, 3);