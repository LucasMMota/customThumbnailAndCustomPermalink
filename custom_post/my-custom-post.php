<?php
/*
Description: Simple Custom post-type that will receive pulled posts
*/

add_action('init', 'init_my_post_type');
function init_my_post_type()
{
    $args = array(
        'public' => false,
        'publicly_queryable' => true,
        'show_ui' => true,  // you should be able to edit it in wp-admin
        'has_archive' => false,  // it shouldn't have archive page
        'rewrite' => false,  // it shouldn't have rewrite rules
        'supports' => array('title', 'editor', 'excerpt', 'tags'),
        'taxonomies' => array('category', 'post_tag')
    );
    register_post_type('my_custom_post', $args);
}

//add custom fields URL to post
add_action('custom_metadata_manager_init_metadata', 'x_my_cpt_init_custom_fields');
function x_my_cpt_init_custom_fields()
{

    x_add_metadata_field('my_featured_image_url', 'my_custom_post', array(
        'label' => 'Url from img in another site'
    ));

    x_add_metadata_field('my_url_post_from_another_site', 'my_custom_post', array(
        'label' => 'Url from post in another site'
    ));
}
