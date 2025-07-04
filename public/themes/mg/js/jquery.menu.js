/**
 * jquery.slimmenu.js
 * http://adnantopal.github.io/slimmenu/
 * Author: @adnantopal
 * Copyright 2013-2015, Adnan Topal (adnan.co)
 * Licensed under the MIT license.
 */
(function (jQuery, window, document, undefined) {
    "use strict";

    var pluginName = 'slimmenu',
        oldWindowWidth = 0,
        defaults = {
            resizeWidth: '767',
            initiallyVisible: false,
            collapserTitle: 'Main Menu',
            animSpeed: 'medium',
            easingEffect: null,
            indentChildren: false,
            childrenIndenter: '&nbsp;&nbsp;',
            expandIcon: '<span class="icon"><i class="fa"></i></span>',
            collapseIcon: '<span class="icon"><i class="fa"></i></span>'
        };

    function Plugin(element, options) {
        this.element = element;
        this.jQueryelem = jQuery(this.element);
        this.options = jQuery.extend(defaults, options);
        this.init();
    }

    Plugin.prototype = {

        init: function () {
            var jQuerywindow = jQuery(window),
                options = this.options,
                jQuerymenu = this.jQueryelem,
                jQuerycollapser = '<div class="menu-collapser">' + options.collapserTitle + '<div class="collapse-button"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></div></div>',
                jQuerymenuCollapser;

            jQuerymenu.before(jQuerycollapser);
            jQuerymenuCollapser = jQuerymenu.prev('.menu-collapser');

            jQuerymenu.on('click', '.sub-toggle', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var jQueryparentLi = jQuery(this).closest('li');

                if (jQuery(this).hasClass('expanded')) {
                    jQuery(this).removeClass('expanded').html(options.expandIcon);
                    jQueryparentLi.find('>ul').slideUp(options.animSpeed, options.easingEffect);
                } else {
                    jQuery(this).addClass('expanded').html(options.collapseIcon);
                    jQueryparentLi.find('>ul').slideDown(options.animSpeed, options.easingEffect);
                }
            });

            jQuerymenuCollapser.on('click', '.collapse-button', function (e) {
                e.preventDefault();
                jQuerymenu.slideToggle(options.animSpeed, options.easingEffect);
            });

            this.resizeMenu();
            jQuerywindow.on('resize', this.resizeMenu.bind(this));
            jQuerywindow.trigger('resize');
        },

        resizeMenu: function () {
            var self = this,
                jQuerywindow = jQuery(window),
                windowWidth = jQuerywindow.width(),
                jQueryoptions = this.options,
                jQuerymenu = jQuery(this.element),
                jQuerymenuCollapser = jQuery('body').find('.menu-collapser');

            if (window['innerWidth'] !== undefined) {
                if (window['innerWidth'] > windowWidth) {
                    windowWidth = window['innerWidth'];
                }
            }

            if (windowWidth != oldWindowWidth) {
                oldWindowWidth = windowWidth;

                jQuerymenu.find('li').each(function () {
                    if (jQuery(this).has('ul').length) {
                        if (jQuery(this).addClass('has-submenu').has('.sub-toggle').length) {
                            jQuery(this).children('.sub-toggle').html(jQueryoptions.expandIcon);
                        } else {
                            jQuery(this).addClass('has-submenu').append('<span class="sub-toggle">' + jQueryoptions.expandIcon + '</span>');
                        }
                    }

                    jQuery(this).children('ul').hide().end().find('.sub-toggle').removeClass('expanded').html(jQueryoptions.expandIcon);
                });

                if (jQueryoptions.resizeWidth >= windowWidth) {
                    if (jQueryoptions.indentChildren) {
                        jQuerymenu.find('ul').each(function () {
                            var jQuerydepth = jQuery(this).parents('ul').length;
                            if (!jQuery(this).children('li').children('a').has('i').length) {
                                jQuery(this).children('li').children('a').prepend(self.indent(jQuerydepth, jQueryoptions));
                            }
                        });
                    }

                    jQuerymenu.addClass('collapsed').find('li').has('ul').off('mouseenter mouseleave');
                    jQuerymenuCollapser.show();

                    if (!jQueryoptions.initiallyVisible) {
                        jQuerymenu.hide();
                    } 
                } else {
                    jQuerymenu.find('li').has('ul')
                        .on('mouseenter', function () {
                            jQuery(this).find('>ul').stop().slideDown(jQueryoptions.easingEffect);
                        })
                        .on('mouseleave', function () {
                            jQuery(this).find('>ul').stop().slideUp(jQueryoptions.easingEffect);
                        });

                    jQuerymenu.find('li > a > i').remove();
                    jQuerymenu.removeClass('collapsed').show();
                    jQuerymenuCollapser.hide();
                }
            }
        },

        indent: function (num, options) {
            var i = 0,
                jQueryindent = '';
            for (; i < num; i++) {
                jQueryindent += options.childrenIndenter;
            }
            return '<i>' + jQueryindent + '</i> ';
        }
    };

    jQuery.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!jQuery.data(this, 'plugin_' + pluginName)) {
                jQuery.data(this, 'plugin_' + pluginName,
                    new Plugin(this, options));
            }
        });
    };

}(jQuery, window, document));