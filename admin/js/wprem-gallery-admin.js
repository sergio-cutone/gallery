(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).ready(function(){
        if ($("#wprem-gallery-metabox").length){
            $("#sortable").sortable();
        }
    });

    $(document).on("click", "#gallery-insert", function() {
        gallery_container();
    });

    function gallery_container() {
        // let's obtain the values of the fields

        $(".wprem-gallery-error").hide();
        if (!$("#wp_gallery_id").val().length) {
            $(".wprem-gallery-error").show();
            return false;
        }

        var out, maxwidth = '';
        if ($("#wp_gallery_id").val()) {
            if (!$("#wp_slides_maxwidth").val()) {
                maxwidth = "100%";
            } else {
                maxwidth = $("#wp_slides_maxwidth").val() + "px";
            }
            out = ' id="' + $("#wp_gallery_id").val() + '"';
            out = out + ' type="' + $("#wp_slider_sync").val() + '"';
            out = out + ' cols="' + $("#wp_slider_cols").val() + '"';
            out = out + ' size="' + $("#wp_ratio").val() + '"';
            out = out + ' slidestoshow="' + $("#wp_slides_to_show").val() + '"';
            out = out + ' autoplay="' + $("#wp_autoplay").val() + '"';
            out = out + ' dots="' + $("#wp_slider_dots").val() + '"';
            out = out + ' arrows="' + $("#wp_slider_arrows").val() + '"';
            out = out + ' maxwidth="' + maxwidth + '"';
        }
        window.send_to_editor("[wp_gallery" + out + "]");
    }

    // Figure out if it is a valid YouTube or Video URL
    function parseURL(url) {
        // - Supported YouTube URL formats:
        //   - http://www.youtube.com/watch?v=My2FRPA3Gf8
        //   - http://youtu.be/My2FRPA3Gf8
        //   - https://youtube.googleapis.com/v/My2FRPA3Gf8
        //   - https://m.youtube.com/watch?v=My2FRPA3Gf8
        // - Supported Vimeo URL formats:
        //   - http://vimeo.com/25451551
        //   - http://player.vimeo.com/video/25451551
        // - Also supports relative URLs:
        //   - //player.vimeo.com/video/25451551

        url.match(/(http:|https:|)\/\/(player.|www.|m.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);

        if (RegExp.$3.indexOf('youtu') > -1) {
            var type = 'youtube';
        } else if (RegExp.$3.indexOf('vimeo') > -1) {
            var type = 'vimeo';
        }

        return {
            type: type,
            id: RegExp.$6
        };
    }

    $(function() {

        $("#sortable").on("sortupdate", function(event, ui) {
            set_json();
        });

        $(".wprem_gallery_element div").on("click", function(e) {
            var action;
            if ($(this).attr("data-type") === 'img') {
                //action = '<input type="button" class="button button-primary button-large meta-image-button" value="Choose or Upload an Image" />';
                // Create the media frame.

                e.preventDefault();
                var $button = $(this);
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select or upload image',
                    library: { // remove these to show all
                        type: 'image' // specific mime
                    },
                    button: {
                        text: 'Select'
                    },
                    multiple: 'add' // Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function() {
                    // We set multiple to false so only get one image from the uploader

                    var selections = file_frame.state().get('selection').toJSON()
                    //console.log(selections);

                    for(var a=0; a < selections.length; a++){
                        //console.log(selections[a].id+" : "+selections[a].url);
                        var newimg = '<img class="meta-image-preview" src="' + selections[a].url + '" /> <br/><input type="hidden" name="meta-image" id="meta-image" class="meta_image meta_media" value="' + selections[a].id + '" data-raw="' + selections[a].url + '" />'
                        $("#sortable").prepend('<li class="ui-state-default" style="background:#EFEFEF; border:1px solid #CCC; position:relative">' + newimg + '<input type="button" class="button button-large meta-image-remove" value="X"/></li>');
                    }
                    set_json();
                    //var attachment = file_frame.state().get('selection').first().toJSON();
                    //$button.siblings('input').val(attachment.url).change();
                });

                // Finally, open the modal
                file_frame.open();
            } else if ($(this).attr("data-type") === 'vid') {
                action = '<input type="text" class="wprem-vid meta_media" value="" /> <input type="button" class="wprem-gal-vid button button-primary button-large" value="Add Video"><br/>From youtube.com or vimeo.com';
                $("#sortable").prepend('<li class="ui-state-default"><img src="" class="meta-image-preview" /><div style="padding:5px"><input type="hidden" name="meta-image" id="meta-image" class="meta_image meta_media" value="" />' + action + '<input type="button" class="button button-large meta-image-remove" value="X"/></div></li>');
            }
        });

        $(".meta-image-preview").on("click", function(e) {
            var action;
            var ths = $(this);
            var ths_input = ths.next().next();

            e.preventDefault();
            var $button = $(this);
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select or upload image',
                library: { // remove these to show all
                    type: 'image' // specific mime
                },
                button: {
                    text: 'Select'
                },
                multiple: 'false' // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function() {
                // We set multiple to false so only get one image from the uploader
                var selections = file_frame.state().get('selection').toJSON()
                for(var a=0; a < selections.length; a++){
                    ths.attr("src",selections[a].url);
                    if (ths_input.hasClass("wprem-vid")){
                        ths.next().next().attr("data-raw",selections[a].url).attr("data-id",selections[a].id);
                    } else {
                        ths.next().next().val(selections[a].id).attr("data-raw",selections[a].url).attr("data-id",selections[a].id);
                    }
                }
                set_json();
            });
            // Finally, open the modal
            file_frame.open();
        });

        $(document).on("click", ".wprem-gal-vid", function() {

            var parent = $(this).closest(".ui-state-default");
            var vidin = $(this).prev(".wprem-vid").val();
            var thisvid = $(this).prev(".wprem-vid");

            var videoDetails = parseURL(vidin);
            var videoType = videoDetails.type;
            var videoID = videoDetails.id;
            var thumbIMG;
            var thumbLINK;

            $(this).nextAll(".wprem-gal-err").hide();
            $('.meta-image-preview').eq($(".ui-state-default").index(parent)).attr("src", "");

            if (videoType == 'youtube') {
                $('.meta-image-preview').eq($(".ui-state-default").index(parent)).attr("src", "https://img.youtube.com/vi/" + videoID + "/hqdefault.jpg");
                $(thisvid).attr("data-raw", "https://img.youtube.com/vi/" + videoID + "/0.jpg");
                set_json();
            } else if (videoType == 'vimeo') {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "https://vimeo.com/api/v2/video/" + videoID + ".json", true);
                xhr.onload = function(e) {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            var data = xhr.responseText;
                            var parsedData = JSON.parse(data);
                            var thumbSRClarge = parsedData[0].thumbnail_large;
                            // split url of large thumbnail at 640
                            var thumbSplit = thumbSRClarge.split(/\d{3}(?=.jpg)/);
                            // add 1280x720 to parts and get bigger thumbnail
                            var thumbSRC = thumbSplit[0] + '1280x720' + thumbSplit[1];
                            $('.meta-image-preview').eq($(".ui-state-default").index(parent)).attr("src", thumbSRClarge);
                            $(thisvid).attr("data-raw", thumbSRClarge);
                            set_json();
                        } else {
                            console.error(xhr.statusText);
                        }
                    }
                };
                xhr.onerror = function(e) {
                    console.error(xhr.statusText);
                };
                xhr.send(null);
            }

        })

        $(document).on("click", ".meta-image-remove", function() {
            $(this).closest(".ui-state-default").remove();
            set_json();
        });

        $(document).on("click", ".media-button", function() {
            var parent = $(this).closest(".ui-state-default");
            var send_attachment_bkp = wp.media.editor.send.attachment;
            wp.media.editor.send.attachment = function(props, attachment) {
                $('.meta_media').eq($(".ui-state-default").index(parent)).val(attachment.id);
                $('.meta_media').eq($(".ui-state-default").index(parent)).attr("data-raw", attachment.url);
                // image preview
                $('.meta-image-preview').eq($(".ui-state-default").index(parent)).attr('src', attachment.url);
                wp.media.editor.send.attachment = send_attachment_bkp;
                set_json();
            }

            wp.media.editor.open();
            return false;
        });
        function set_json() {
            $("#wprem_imgid").val('');
            var ids = [];
            $("#sortable li .meta_media").each(function() {
                if ($(this).val()) {
                    ids.push('{"id":"' + $(this).val() + '","raw":"' + $(this).attr("data-raw") + '"}');
                    //console.log($(this).val());
                }
            });
            $("#wprem_imgid").val('{"media":[' + ids + ']}');
        }

    });

})(jQuery);