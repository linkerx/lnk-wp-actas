<?php

/**
Plugin Name: LNK Actas de Congresos y Jornadas
Plugin URI: http://curza.net/
Description: Actas de Congresos y Jornadas
Version: 0.1
Author: Diego
Author URI: http://curza.net/
License: GPL2
*/

/**
 * Creacion de tipo de dato especial para acta
 */

function lnk_actas_create_type(){
    register_post_type(
        'acta',
        array(
            'labels' => array(
                'name' => __('Actas','actas_name'),
                'singular_name' => __('Acta','actas_singular_name'),
                'menu_name' => __('Actas','actas_menu_name'),
                'all_items' => __('Lista de Actas','actas_all_items'),
            ),
            'description' => 'Actas de Jornadas y Congresos',
            'public' => true,
            /*'exclude_from_search' => true,*/
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 21,
            'support' => array(
                'title',
                'excerpt',
                'editor',
                'thumbnail',
                'revisions'
            ),
            "capability_type" => 'actas',
            "map_meta_cap" => true
        )
    );
}
add_action('init', 'lnk_actas_create_type');
add_post_type_support('acta', array('thumbnail','excerpt'));

/**
 * Input de subtitulo
 */
function lnk_actas_subtitulo(){
	global $post;
        if($post->post_type == 'acta'){
            $id = $post->ID;

            $subtitulo = get_post_meta($id,'acta_subtitulo',true);

            print "<div id='lnk_acta_subtitulo_container' class='postbox-container'>";
            print "<label class='inline-label' for='lnk_acta_subtitulo_input'>Volanta:</label>";
            print "<input class='title-like' type='text' name='lnk_acta_subtitulo_input' id='lnk_acta_subtitulo_input' size='80' ";
            print "value='".$subtitulo."' />";
            print "</div>";
            print "<div style='clear:both;'></div>";

        }
}
add_action('edit_form_after_title','lnk_actas_subtitulo');

// Agrega Meta Boxes
function add_actas_metaboxes() {
    add_meta_box('acta_issn', 'ISSN', 'acta_issn_metabox', 'acta', 'side', 'default');
    add_meta_box('acta_colores', 'Colores', 'acta_colores_metabox', 'acta', 'side', 'default');
    add_meta_box('acta_logo_hue', 'Rotacion de Color', 'acta_logo_hue_rotation_metabox', 'acta', 'side', 'default');
}
add_action( 'add_meta_boxes', 'add_actas_metaboxes' );

/**
 * Metabox ISSN
 * @global type $post
 */
function acta_issn_metabox() {
	global $post;
	$issn = get_post_meta($post->ID, 'acta_issn', true);
	echo '<input type="text" name="acta_issn" value="'.$issn.'" class="widefat" />';
}

/**
 * Colores Actas
 * @global type $post
 */
function acta_colores_metabox() {
	global $post;
	$color_primario = get_post_meta($post->ID, 'acta_color_primario', true);
	$color_color_secundario = get_post_meta($post->ID, 'acta_color_secundario', true);
	
        echo '#<input type="text" name="acta_color_primario" value="'.$color_primario.'" class="widefat" style="width:80px" /><br><br>';
        echo '#<input type="text" name="acta_color_secundario" value="'.$color_secundario.'" class="widefat" style="width:80px" />';
}

/**
 * Colores Actas
 * @global type $post
 */
function acta_logo_hue_rotation_metabox() {
	global $post;
	$rotation = get_post_meta($post->ID, 'acta_logo_hue_rotation', true);
	
        echo '<input type="text" name="acta_logo_hue_rotation" value="'.$rotation.'" class="widefat" style="width:80px" /> deg.';
}


/**
 * Save Metas
 * @global type $wpdb
 * @global type $post_type
 * @param type $id
 * @return type
 */
function acta_save_metas($id) {
	global $wpdb,$post_type;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $id;
	if (defined('DOING_AJAX') && DOING_AJAX)
		return $id;

	if($post_type == 'acta'){
		update_post_meta($id,'acta_issn', $_POST['acta_issn']);
		update_post_meta($id,'acta_color_primario', $_POST['acta_color_primario']);
		update_post_meta($id,'acta_color_secundario', $_POST['acta_color_secundario']);
		update_post_meta($id,'acta_logo_hue_rotation', $_POST['acta_logo_hue_rotation']);
	}
}
add_action('save_post', 'acta_save_metas');