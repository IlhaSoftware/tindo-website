(function ($) {
    'use strict';

    $(document).on('click', '.rock-convert-exclude-pages-add', function () {
        $('.rock-convert-exclude-pages-link').first().clone()
            .appendTo('.rock-convert-exclude-pages')
            .children('input[type=text]')
            .val('')
            .focus();
    });

    // Exclude link
    $(document).on('click', '.rock-convert-exclude-pages-remove', function () {
        $(this).parent().remove();
    });

    $(document).on('change', 'input[name="rock_convert_visibility"]', function () {

        var selected = $('input[name="rock_convert_visibility"]:checked').val();

        if (selected == "exclude") {
            $(".rock-convert-exclude-control").show();
        } else {
            $(".rock-convert-exclude-control").hide();
        }
    });

    function initColorPicker(widget) {
        widget.find('.color-picker').wpColorPicker({
            change: function (e, ui) {
                $(e.target).val(ui.color.toString());
                $(e.target).trigger('change');
            },
            clear: function (e, ui) {
                $(e.target).trigger('change');
            },
        });
    }

    function onFormUpdate(event, widget) {
        initColorPicker(widget);
    }

    $(document).on('widget-added widget-updated', onFormUpdate);

    $(document).ready(function () {
        $('#widgets-right .widget:has(.color-picker)').each(function () {
            initColorPicker($(this));
        });

        jQuery('.rconvert_announcement_bar_page .color-picker').each(function () {
            jQuery(this).wpColorPicker(
                {
                    change: function (event, ui) {
                        var target = event.target.id;
                        var c = ui.color.toString();
                        var property =
                            (target === "rconvert_announcement_text_color" ||
                                target === "rconvert_announcement_btn_text_color"
                            ) ? {"color": c} : {"backgroundColor": c};

                        jQuery('.' + target).css(property);
                    },
                }
            );
        })
    });

})(jQuery);
