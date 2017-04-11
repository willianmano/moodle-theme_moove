/*! Moove app.js
 * ================
 * Main JS application file for Moove v2. This file
 * should be included in all pages. It controls some layout
 * options and implements exclusive Moove plugins.
 *
 * @Author  Willian Mano
 */

/* jshint ignore:start */
define(['jquery', 'theme_moove/resize_sensor', 'core/log'], function($, ResizeSensor, log) {

    "use strict"; // jshint ;_;

    log.debug('Theme Moove AMD initialised');

    /* Moove
     *
     * @type Object
     * @description $.Moove is the main object for the template's app.
     *              It's used for implementing functions and options related
     *              to the template. Keeping everything wrapped in an object
     *              prevents conflict with other plugins and is a better
     *              way to organize our code.
     */
    $.Moove = {};

    /* --------------------
     * - Moove Options -
     * --------------------
     * Modify these options to suit your implementation
     */
    $.Moove.options = {
        //Bootstrap.js tooltip
        enableBSToppltip: true,
        BSTooltipSelector: "[data-admtoggle='tooltip']",

        //Control Sidebar Options
        enableControlSidebar: true,
        controlSidebarOptions: {
            //Which button should trigger the open/close event
            toggleBtnSelector: "[data-toggle='control-sidebar']",
            //The sidebar selector
            selector: ".control-sidebar",
            //Enable slide over content
            slide: true,
            // update slide var based on theme settings
            slide: parseInt($(".rightsidebar-toggle").attr('data-slide'))
        },

        //The standard screen sizes that bootstrap uses.
        //If you change these in the variables.less file, change
        //them here too.
        screenSizes: {
            xs: 480,
            sm: 768,
            md: 992,
            lg: 1200
        }
    };

    /* ------------------
     * - Implementation -
     * ------------------
     * The next block of code implements Moove's
     * functions and plugins as specified by the
     * options above.
     */
    $(function() {
        "use strict";

        //Fix for IE page transitions
        $("body").removeClass("hold-transition");

        //Extend options if external options exist
        if (typeof MooveOptions !== "undefined") {
            $.extend(true,
                $.Moove.options,
                MooveOptions);
        }

        //Easy access to options
        var o = $.Moove.options;

        //Set up the object
        _init();

        //Enable control sidebar
        if (o.enableControlSidebar) {
            $.Moove.controlSidebar.activate();
        }

        //Activate Bootstrap tooltip
        if (o.enableBSToppltip) {
            $('body').tooltip({
                selector: o.BSTooltipSelector,
                container: 'body'
            });
        }

        /*
         * INITIALIZE BUTTON TOGGLE
         * ------------------------
         */
        $('.btn-group[data-toggle="btn-toggle"]').each(function() {
            var group = $(this);
            $(this).find(".btn").on('click', function(e) {
                group.find(".btn.active").removeClass("active");
                $(this).addClass("active");
                e.preventDefault();
            });

        });
    });

    /* ----------------------------------
     * - Initialize the Moove Object -
     * ----------------------------------
     * All Moove functions are implemented below.
     */
    function _init() {

        /* ControlSidebar
         * ==============
         * Adds functionality to the right sidebar
         *
         * @type Object
         * @usage $.Moove.controlSidebar.activate(options)
         */
        $.Moove.controlSidebar = {
            //instantiate the object
            activate: function() {
                //Get the object
                var _this = this;
                //Update options
                var o = $.Moove.options.controlSidebarOptions;
                //Get the sidebar
                var sidebar = $(o.selector);
                //The toggle button
                var btn = $(o.toggleBtnSelector);
                //Get the screen sizes
                var screenSizes = $.Moove.options.screenSizes;

                /* on small screens close the sidebar on click outside */
                $(".content-wrapper").click(function() {
                    // //Enable hide menu when clicking on the content-wrapper on small screens
                    if ($(window).width() <= (screenSizes.sm - 1)) {
                        _this.close(sidebar, o.slide);
                    }
                });

                //Listen to the click event
                btn.on('click', function(e) {
                    e.preventDefault();
                    //If the sidebar is not open
                    if (!sidebar.hasClass('sidebar-blocks-open') && !$('body').hasClass('sidebar-blocks-open')) {
                        //Open the sidebar
                        _this.open(sidebar, o.slide);
                    } else {
                        _this.close(sidebar, o.slide);
                    }
                });

                //If the body has a boxed layout, fix the sidebar bg position
                var bg = $(".control-sidebar-bg");
                _this._fix(bg);

                //If the body has a fixed layout, make the control sidebar fixed
                if ($('body').hasClass('fixed')) {
                    _this._fixForFixed(sidebar);
                } else {
                    //If the content height is less than the sidebar's height, force max height
                    if ($('.content-wrapper, .right-side').height() < sidebar.height()) {
                        _this._fixForContent(sidebar);
                    }
                }
            },
            //Open the control sidebar
            open: function(sidebar, slide) {

                $(".rightsidebar-toggle").find('.fa').removeClass('fa-plus');
                $(".rightsidebar-toggle").find('.fa').addClass('fa-minus');

                //Slide over content
                if (slide) {
                    sidebar.addClass('sidebar-blocks-open');
                    sidebar.removeClass('display-none');
                } else {
                    //Push the content by adding the open class to the body instead
                    //of the sidebar itself
                    $('body').addClass('sidebar-blocks-open');
                }
            },
            //Close the control sidebar
            close: function(sidebar, slide) {
                $(".rightsidebar-toggle").find('.fa').addClass('fa-plus');
                $(".rightsidebar-toggle").find('.fa').removeClass('fa-minus');

                if (slide) {
                    sidebar.removeClass('sidebar-blocks-open');
                    sidebar.addClass('display-none');
                } else {
                    $('body').removeClass('sidebar-blocks-open');
                    $('.sidebar-blocks-open').attr('display', 'none');
                }
            },
            _fix: function(sidebar) {
                var _this = this;
                if ($("body").hasClass('layout-boxed')) {
                    sidebar.css('position', 'absolute');
                    sidebar.height($(".wrapper").height());
                    $(window).resize(function() {
                        _this._fix(sidebar);
                    });
                } else {
                    sidebar.css({
                        'position': 'fixed',
                        'height': 'auto'
                    });
                }
            },
            _fixForFixed: function(sidebar) {
                sidebar.css({
                    'position': 'fixed',
                    'max-height': '100%',
                    'padding-bottom': '50px'
                });
            },
            _fixForContent: function(sidebar) {
                $(".content-wrapper, .right-side").css('min-height', sidebar.height());
            },
            _fixForHeightChange: function(sidebar) {

                var sidebar_height = $(".sidebar").height();
                var csidebar_height = $(sidebar).height();

                // check which sidebar height is more and adjust content wrapper height accordingly
                var min_height = (sidebar_height > csidebar_height) ? sidebar_height : csidebar_height;
                $(".content-wrapper, .right-side").css('min-height', min_height);
            }
        };

    return $.Moove;
  }
});
/* jshint ignore:end */
