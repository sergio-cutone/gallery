<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Gallery
 * @subpackage Wprem_Gallery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wprem_Gallery
 * @subpackage Wprem_Gallery/public
 * @author     Sergio Cutone <sergio.cutone@yp.ca>
 */
class Wprem_Gallery_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->development = true;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style('slick', plugin_dir_url(__FILE__) . 'js/slick/slick.css', array(), $this->version, 'all');
        wp_enqueue_style('slick-theme', plugin_dir_url(__FILE__) . 'js/slick/slick-theme.css', array(), $this->version, 'all');
        wp_enqueue_style('lightbox', plugin_dir_url(__FILE__) . 'js/simpleLightbox.min.css', array(), $this->version, 'all');
        if ($this->development) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wprem-gallery-public.css', array(), $this->version, 'all');
        } else {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wprem-gallery-public-min.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script('slick', plugin_dir_url(__FILE__) . 'js/slick/slick.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('lightbox', plugin_dir_url(__FILE__) . 'js/simpleLightbox.min.js', array('jquery'), $this->version, false);
        if ($this->development) {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wprem-gallery-public.js', array('jquery'), $this->version, false);
        } else {
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wprem-gallery-public-min.js', array('jquery'), $this->version, false);
        }
    }

    private function imgtags($id)
    {
        return 'alt="' . get_post_meta($id, '_wp_attachment_image_alt', true) . '" title="' . get_the_title($id) . '"';
    }

    private function get_image_id($image_url)
    {
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url));
        return $attachment[0];
    }

    public function gallery_shortcode($atts)
    {
        global $post;
        /*$a = shortcode_atts(array(
        'id' => 0,
        'type' => 'single', // single || dual || cols
        'cols' => 5, // number of columns if type=cols
        'slidestoshow' => 5,
        'autoplay' => 'false',
        'autoplayspeed' => 2000,
        'dots' => 'true',
        'arrows' => 'true',
        'fade' => 'true',
        'maxwidth' => '100%',
        'thumbheight' => '100px',
        ), $atts);*/

        extract(shortcode_atts(array(
            'id' => 0,
            'type' => 'single', // single || dual || cols
            'cols' => 5, // number of columns if type=cols (3 || 4 || 5 || 6)
            'slidestoshow' => 5,
            'autoplay' => 'false',
            'autoplayspeed' => 2000,
            'dots' => 'true',
            'arrows' => 'true',
            'fade' => 'true',
            'maxwidth' => '100%',
            'thumbheight' => '100px',
            'margin' => 20,
            'size' => '16:9',
        ), $atts));

        ob_start();
        $gall = get_post_meta($id, '_wprem_imgid', true);
        $obj = json_decode($gall);
        $modalImgs = $modalDots = "";
        $c = 0;

        $ratio = ($size === '16:9') ? 'ratio169' : 'ratio43';

        if ($slidestoshow > 1) {
            $fade = "false";
        }

        switch (intval($cols)) {
            case 2:
                $li_col = 'wprem-gal-c2';
                break;
            case 3:
                $li_col = 'wprem-gal-c3';
                break;
            case 4:
                $li_col = 'wprem-gal-c4';
                break;
            case 5:
                $li_col = 'wprem-gal-c5';
                break;
            case 6:
                $li_col = 'wprem-gal-c6';
                break;
        }

        // 2 ROW LAYOUT WITH THUMBNAILS AT THE BOTTOM
        if ($type === 'dual') {

            echo '<div style="max-width:' . $maxwidth . '; margin:0 auto"><div class="wprem-gallery-container wprem-gallery-dual" style="margin-left:10px; margin-right:10px;" data-slick=\'{"lazyLoad": "ondemand", "slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".wprem-gallery", "arrows": false, "fade": true, "adaptiveHeight": true}\'>';

            foreach ($obj->media as $mydata) {
                if (strpos($mydata->id, 'youtube') !== false || strpos($mydata->id, 'vimeo') !== false) {
                    if (strpos($mydata->id, 'vimeo')) {
                        echo '
                        <div class="wprem-gallery-video-play">
                            <a href="http://player.vimeo.com/video/' . (int) substr(parse_url($mydata->id, PHP_URL_PATH), 1) . '"><img src="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '"/></a>
                        </div>
                        ';
                    } else {
                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $mydata->id, $match)) {
                            $video_id = $match[1];
                        }
                        echo '
                        <div class="wprem-gallery-video-play">
                            <a href="https://www.youtube.com/embed/' . $match[1] . '?autoplay=1"><img src="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '"/></a>
                        </div>
                        ';
                    }
                } else {
                    echo '<div><a href="' . $mydata->raw . '"><img data-lazy="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '"/></a></div>';
                    //echo '<div><a href="' . $mydata->raw . '"><img src="' . $mydata->raw . '"/></a></div>';
                    $modalImgs .= '<div class="item"><img src="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '" /><div class="carousel-caption"></div></div>';
                }
            }

            echo '</div><div class="wprem-gallery" style="margin-left:10px; margin-right:10px" data-slick=\'{"variableWidth": true, "lazyLoad": "ondemand", "autoplay": ' . $autoplay . ', "autoplaySpeed":' . $autoplayspeed . ',"slidesToShow": ' . $slidestoshow . ', "nextArrow": "<div class=&apos;slick-next&apos;>d</div>", "prevArrow": "<div class=&apos;slick-prev&apos;>d</div>", "focusOnSelect": true, "centerMode": true, "dots": ' . $dots . ', "slidesToScroll": 1, "asNavFor": ".wprem-gallery-dual"}\'>';

            foreach ($obj->media as $mydata) {
                echo '<div class="wprem-gal-container"><img data-lazy="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '"/></div>';
            }

            echo '</div></div>';
            $jScript = '$(".wprem-gallery-dual").slick(); $(".wprem-gallery").slick();';

            // SINGLE SLIDE SHOW
        } elseif ($type === 'single') {

            echo '<div style="max-width:' . $maxwidth . '; margin:0 auto"><div class="wprem-gallery-container wprem-gallery-single" data-slick=\'{"infinite": true,"dots": ' . $dots . ',"slidesToShow": ' . $slidestoshow . ', "slidesToScroll": 1, "autoplay": ' . $autoplay . ', "autoplaySpeed":' . $autoplayspeed . ', "arrows": ' . $arrows . ', "fade": ' . $fade . ', "adaptiveHeight": true, "nextArrow": "<div class=&apos;slick-next&apos;>d</div>", "prevArrow": "<div class=&apos;slick-prev&apos;>d</div>"}\'>';

            foreach ($obj->media as $mydata) {
                if (strpos($mydata->id, 'youtube') !== false || strpos($mydata->id, 'vimeo') !== false) {
                    if (strpos($mydata->id, 'vimeo')) {
                        echo '<div class="wprem-gallery-video-play"><a href="http://player.vimeo.com/video/' . (int) substr(parse_url($mydata->id, PHP_URL_PATH), 1) . '"><img src="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '"/></a></div>';
                    } else {
                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $mydata->id, $match)) {
                            $video_id = $match[1];
                        }
                        echo '<div class="wprem-gallery-video-play"><a href="https://www.youtube.com/embed/' . $match[1] . '?autoplay=1"><img src="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '"/></a></div>';
                    }
                } else {
                    echo '<div><a href="' . $mydata->raw . '"><img src="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '"/></a></div>';
                    $modalImgs .= '<div class="item"><img src="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '" /><div class="carousel-caption"></div></div>';
                }
            }

            echo '</div></div>';
            $jScript = '$(".wprem-gallery-single").slick();';

            // COLUMNS
        } elseif ($type === 'cols') {
            echo '<div class="wprem-gallery-container">';
            $this->columns($obj->media, $margin, $li_col, $ratio);
            echo '</div>';
        }

        if (isset($jScript)) {
            echo '<script>jQuery(document).ready(function($) {' . $jScript . '});</script>';
        }

        return ob_get_clean();
    }

    private function columns($media, $margin, $li_col, $ratio)
    {
        echo '<ul class="flex-gallery ' . $li_col . '">';
        foreach ($media as $mydata) {
            echo '<li style="padding:' . ($margin / 2) . 'px"><div class="' . $ratio . '">';
            if (strpos($mydata->id, 'youtube') !== false || strpos($mydata->id, 'vimeo') !== false) {
                if (strpos($mydata->raw, 'youtube') !== false || strpos($mydata->raw, 'vimeo') !== false) {
                    $vid_image = '<img src="' . $mydata->raw . '" ' . $this->imgtags($mydata->id) . '" />';
                } else {
                    $img_id = $this->get_image_id($mydata->raw);
                    $vid_image = wp_get_attachment_image($img_id, 'full', array("class" => "img-responsive"));
                }

                if (strpos($mydata->id, 'vimeo')) {
                    echo '
                <div class="wprem-gallery-video-play">
                    <a href="http://player.vimeo.com/video/' . (int) substr(parse_url($mydata->id, PHP_URL_PATH), 1) . '">' . $vid_image . '</a>
                </div>';
                } else {
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $mydata->id, $match)) {
                        $video_id = $match[1];
                    }
                    echo '
                <div class="wprem-gallery-video-play">
                    <a href="https://www.youtube.com/embed/' . $match[1] . '?autoplay=1">' . $vid_image . '</a>
                </div>
                ';
                }
            } else {
                echo '<a href="' . $mydata->raw . '">' . wp_get_attachment_image($mydata->id, 'full', array("class" => "img-responsive")) . '</a>';
            }
            echo '</div></li>';
        }
        echo '</ul>';
    }

}
