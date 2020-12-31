<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Gallery
 * @subpackage Wprem_Gallery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wprem_Gallery
 * @subpackage Wprem_Gallery/admin
 * @author     Sergio Cutone <sergio.cutone@yp.ca>
 */
class Wprem_Gallery_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wprem_Gallery_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wprem_Gallery_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wprem-gallery-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wprem_Gallery_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wprem_Gallery_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wprem-gallery-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name, 'jquery-ui-core');
        wp_enqueue_media();
    }

    public function add_button()
    {
        echo '<a href="#TB_inline?width=480&height=500&inlineId=wp_gallery_shortcode" class="button thickbox wp_doin_media_link" id="add_div_shortcode">GA</a>';
    }

    public function wp_gallery_sc_popup()
    {
        ?>
        <div id="wp_gallery_shortcode" style="display:none;">
            <div class="wrap wp_doin_shortcode">
                <div>
                    <div class="wprem-gallery-shortcode-admin" style="padding:10px">
                        <h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;">Gallery Shortcode</h3>
                        <hr />
                        <div class="field-container">
                            <div class="label-desc">
                                <?php
$args = array(
            'post_type' => WPREM_GALLERY_CUSTOM_POST_TYPE,
        );
        echo '<strong>Select Gallery</strong>: <select id="wp_gallery_id"><option value="">- Select Gallery -</option>';
        $galleries = get_posts($args);
        foreach ($galleries as $gallery):
            setup_postdata($gallery);
            echo "<option value=" . $gallery->ID . ">" . $gallery->post_title . "</option>";
        endforeach;
        wp_reset_postdata();
        echo "</select>";
        echo '<div class="wprem-gallery-error">Please select a gallery</div>';
        ?>
                            </div>
                        </div>
                        <div class="field-container">
                            <div class="label-desc">
                                <label for="wp_slider_sync"><strong>Type of Gallery: </strong></label>
                                <select name="slider_sync" id="wp_slider_sync">
                                    <option value="single">Single Slider</option>
                                    <option value="dual">Dual Sync Slider</option>
                                    <option value="cols">Columns</option>
                                </select>
                            </div>
                        </div>
                        <h3>Slider Options</h3>
                        <hr />
                        <div class="field-container">
                            <div class="label-desc">
                                <label for="wp_autoplay"><strong>Autoplay: </strong></label>
                                <select name="autoplay" id="wp_autoplay">
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-container">
                            <div class="label-desc">
                                <label for="wp_slider_arrows"><strong>Arrows: </strong></label>
                                <select name="wp_slider_arrows" id="wp_slider_arrows">
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-container">
                            <div class="label-desc">
                                <label for="wp_slider_dots"><strong>Dots: </strong></label>
                                <select name="wp_slider_dots" id="wp_slider_dots">
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-container">
                            <div class="label-desc">
                                <label for="wp_slides_to_show"><strong>Sync Photos to Show (Dual Sync Slider): </strong></label>
                                <select name="slides_to_show" id="wp_slides_to_show">
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-container">
                            <div class="label-desc">
                                <label for="wp_slides_maxwidth"><strong>Max Width (px): </strong></label>
                                <input type="text" name="wp_slides_maxwidth" id="wp_slides_maxwidth" />
                            </div>
                        </div>
                        <h3>Columns Options</h3>
                        <hr />
                        <div class="field-container">
                            <div class="label-desc">
                                <label for="wp_slider_sync"><strong>Number of Columns: </strong></label>
                                <select name="slider_sync" id="wp_slider_cols">
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-container">
                            <div class="label-desc">
                                <label for="wp_ratio"><strong>Image Size: </strong></label>
                                <select name="wp_ratio" id="wp_ratio">
                                    <option value="4:3">4:3</option>
                                    <option value="16:9">16:9</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div style="padding:15px;">
                        <input type="button" class="button-primary" value="Insert Gallery" id="gallery-insert" />
                        &nbsp;&nbsp;&nbsp;<a class="button" href="#" onclick="tb_remove(); return false;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
}

    public function menu_settings()
    {
        add_submenu_page(
            'edit.php?post_type=' . WPREM_GALLERY_CUSTOM_POST_TYPE,
            'Settings', // The title to be displayed in the browser window for this page.
            'Settings', // The text to be displayed for this menu item
            'manage_options', // Which type of users can see this menu item
            $this->plugin_name, // The unique ID - that is, the slug - for this menu item
            array($this, 'settings_page') // The name of the function to call when rendering this menu's page
        );
    }

    public function settings_page()
    {
        include_once 'partials/wprem-gallery-admin-display.php';
    }

    public function remove_yoast_metabox()
    {
        remove_meta_box('wpseo_meta', WPREM_GALLERY_CUSTOM_POST_TYPE, 'normal');
    }

    public function content_types()
    {

        $labels = array(
            'name' => _x('Galleries', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Gallery', 'Post type singular name', 'textdomain'),
            'menu_name' => _x('Galleries', 'Admin Menu text', 'textdomain'),
            'name_admin_bar' => _x('Gallery', 'Add New on Toolbar', 'textdomain'),
            'add_new' => __('Add New', 'textdomain'),
            'add_new_item' => __('Add New Gallery', 'textdomain'),
            'new_item' => __('New Gallery', 'textdomain'),
            'edit_item' => __('Edit Gallery', 'textdomain'),
            'view_item' => __('View Gallery', 'textdomain'),
            'all_items' => __('All Galleries', 'textdomain'),
            'search_items' => __('Search Galleries', 'textdomain'),
            'parent_item_colon' => __('Parent Galleries:', 'textdomain'),
            'not_found' => __('No Galleries found.', 'textdomain'),
            'not_found_in_trash' => __('No Galleries found in Trash.', 'textdomain'),
            'featured_image' => _x('Gallery Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
            'set_featured_image' => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'use_featured_image' => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
            'archives' => _x('Gallery archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
            'insert_into_item' => _x('Insert into Gallery', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
            'uploaded_to_this_item' => _x('Uploaded to this Gallery', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
            'filter_items_list' => _x('Filter Galleries list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain'),
            'items_list_navigation' => _x('Galleries list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain'),
            'items_list' => _x('Galleries list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain'),
        );
        $exludefromsearch = (esc_attr(get_option('wprem_searchable_wprem-gallery')) === "1") ? false : true;
        $args = array('exclude_from_search' => $exludefromsearch, 'register_meta_box_cb' => 'wprem_gallery_metabox', 'show_in_rest' => true, 'rewrite' => array("slug" => "gallery", 'with_front' => false), "menu_icon" => "dashicons-location-alt", 'labels' => $labels, "has_archive" => false, 'supports' => array('title'));
        $galleries = register_cuztom_post_type(WPREM_GALLERY_CUSTOM_POST_TYPE, $args);

        function wprem_gallery_metabox($post_type)
        {
            add_meta_box(
                'wprem-gallery-metabox',
                __('Add Images or Videos to Gallery'),
                'wprem_render_gallery_metabox',
                WPREM_GALLERY_CUSTOM_POST_TYPE,
                'normal',
                'default'
            );
        }

        function wprem_render_gallery_metabox($post)
        {

            wp_nonce_field('wprem_imgid_nonce', 'wprem_imgid_nonce');
            $value = get_post_meta($post->ID, '_wprem_imgid', true);
            echo '<input type="hidden" id="wprem_imgid" class="wprem_imgid" name="wprem_imgid" value="' . esc_attr($value) . '">';
            echo '<input type="hidden" name="wprem_gallery_ids" />';
            $imagee = get_post_meta($post->ID, '_meta-image', true);
            echo esc_attr($imagee);
            ?>
            <div class="wprem_gallery_element">
                <div data-type="img" class="button button-primary button-large">Add Image</div>
                <div class="button button-primary button-large" data-type="vid">Add Video</div>
            </div>
            <ul id="sortable" class="wprem_gallery_sortable">
            <?php
$obj = json_decode($value);
            if (count($obj) > 0) {
                foreach ($obj->media as $mydata) {
                    echo '<li class="ui-state-default">';
                    if (is_numeric($mydata->id)) {
                        echo '<img class="meta-image-preview" src="' . wp_get_attachment_url($mydata->id) . '" /><input type="hidden" name="meta-image" id="meta-image" class="meta_image meta_media" value="' . $mydata->id . '" data-raw="' . $mydata->raw . '" />';
                    } else {
                        echo '<img class="meta-image-preview" src="' . $mydata->raw . '" /><div style="padding:5px"><input type="text" class="wprem-vid meta_media" value="' . $mydata->id . '" data-raw="' . $mydata->raw . '"/> <input type="button" class="wprem-gal-vid button button-primary button-large" value="Add Video"><br/>From youtube.com or vimeo.com</div>';
                    }
                    echo '<input type="button" class="button button-large meta-image-remove" value="X"/></li>';
                }
            }
            echo '</ul>';
        }
    }

    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id
     */
    public function save_wprem_imgid_meta_box_data($post_id)
    {

        // Check if our nonce is set.
        if (!isset($_POST['wprem_imgid_nonce'])) {
            return;
        }

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($_POST['wprem_imgid_nonce'], 'wprem_imgid_nonce')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id)) {
                return;
            }

        } else {

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Make sure that it is set.
        if (!isset($_POST['wprem_imgid'])) {
            return;
        }

        // Sanitize user input.
        $my_data = sanitize_text_field($_POST['wprem_imgid']);

        // Update the meta field in the database.
        update_post_meta($post_id, '_wprem_imgid', $my_data);
    }

}
