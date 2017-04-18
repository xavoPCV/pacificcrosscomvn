(function($) {
    $.fn.mmPaginator = function(more, cstr, options, paginationId, callback) {
        var return_false = function() {
            return false;
        };
        var $this = this;
        var settings = $.extend({
            'max_pages': 12,
            'ajax_url': '',
            'data': {}
        }, options);
        var PG = {
            current_page: 1,
            previous_page: 1,
            pages: [],
            pagination_base: null,
            pagination_html_base: null,
            prev_but: null,
            next_but: null,
            addButtons: function() {
                PG.pagination_base.empty();
                PG.pagination_base.append(PG.prev_but);
                var first_page = PG.current_page;
                var last_page = PG.current_page - 1 + settings.max_pages;
                if (last_page > PG.pages.length) {
                    first_page = PG.pages.length - settings.max_pages;
                    last_page = PG.pages.length;
                }
                if (first_page == PG.current_page && PG.current_page != 1) {
                    first_page--;
                    last_page--;
                }
                if (PG.current_page == 1) {
                    PG.prev_but.addClass('disabled');
                    PG.prev_but.click(return_false);
                } else {
                    PG.prev_but.removeClass('disabled');
                    PG.prev_but.click(function() {
                        PG.selectPage(PG.current_page - 1);
                        return false;
                    });
                }
                for (var p in PG.pages) {
                    var pg_num = (parseInt(p, 10) + 1);
                    if ((first_page <= pg_num && last_page >= pg_num) || pg_num == 1) {
                        var page_butt = $('<li>');
                        var link = $('<a href="#">');
                        link.text(pg_num);
                        link.attr('data-page', pg_num);
                        if (PG.current_page == pg_num) {
                            page_butt.addClass('active');
                            var old_html = PG.pagination_html_base.html();
                            if (typeof(PG.pages[PG.previous_page - 1]) != 'undefined' && typeof(PG.pages[PG.previous_page - 1]['html']) != 'undefined') {
                                PG.pages[PG.previous_page - 1]['html'] = old_html;
                            }
                            PG.pagination_html_base.empty();
                            PG.pagination_html_base.html(PG.pages[p]['html']);
                            if ($().tooltip != undefined) {
                                $('.tooltiper').tooltip();
                            }
                            link.click(return_false);
                        } else {
                            link.bind('click', function() {
                                PG.selectPage($(this).attr('data-page'));
                                return false;
                            });
                        }
                        PG.pagination_base.append(page_butt.append(link));
                        if (pg_num == 1 && first_page > 2) {
                            var spcr = $('<li class="disabled"><a href="#">&hellip;</a></li>');
                            spcr.click(return_false);
                            PG.pagination_base.append(spcr);
                        }
                    }
                }
                if (last_page + 1 != PG.pages.length && last_page != PG.pages.length && ((PG.pages[PG.pages.length - 1]['has_more'] && last_page < PG.pages.length + 1) || (!PG.pages[PG.pages.length - 1]['has_more'] && last_page < PG.pages.length))) {
                    var spcr = $('<li class="disabled"><a href="#">&hellip;</a></li>');
                    spcr.click(return_false);
                    PG.pagination_base.append(spcr);
                }
                if (last_page != PG.pages.length && last_page < PG.pages.length && !PG.pages[PG.pages.length - 1]['has_more']) {
                    var page_butt = $('<li>');
                    var link = $('<a href="#">');
                    link.text(PG.pages.length);
                    link.attr('data-page', pg_num);
                    link.bind('click', function() {
                        PG.selectPage($(this).attr('data-page'));
                        return false;
                    });
                    PG.pagination_base.append(page_butt.append(link));
                }
                if (PG.pages[PG.pages.length - 1]['has_more']) {
                    var page_butt = $('<li>');
                    var link = $('<a href="#">');
                    var pg_num = (PG.pages.length + 1);
                    link.text(pg_num);
                    link.attr('data-page', pg_num);
                    PG.pagination_base.append(page_butt.append(link));
                    link.click(function() {
                        PG.selectPage($(this).attr('data-page'));
                        return false;
                    });
                }
                if (PG.current_page < PG.pages.length || (PG.pages[PG.pages.length - 1]['has_more'])) {
                    PG.next_but.removeClass('disabled');
                    PG.next_but.click(function() {
                        PG.selectPage(PG.current_page + 1);
                        return false;
                    });
                } else {
                    PG.next_but.addClass('disabled');
                    PG.next_but.click(return_false);
                }
                PG.pagination_base.append(PG.next_but);
                if (typeof(PaginatorAPICallback) != 'undefined') {
                    PaginatorAPICallback(PG.current_page);
                }
            },
            selectNextPage: function() {
                if (PG.current_page < PG.pages.length || (PG.pages[PG.pages.length - 1]['has_more'])) {
                    PG.selectPage(PG.current_page + 1);
                    return true;
                }
                return false;
            },
            selectPrevPage: function() {
                if (PG.current_page > 1) {
                    PG.selectPage(PG.current_page - 1);
                    return true;
                }
                return false;
            },
            selectPage: function(num) {
                if (PG.pages[num - 1]) {
                    PG.previous_page = PG.current_page;
                    PG.current_page = parseInt(num, 10);
                    PG.addButtons();
                    if ($this.is('div.tabbed-paginator')) {
                        var $els = $($.trim(PG.pages[num - 1].html));
                        $(paginationId).children().remove();
                        if ($($els[0]).is('ul')) {
                            $(paginationId).append($els);
                        } else {
                            $(paginationId).append($("<ul/>").addClass("item-list").append($els));
                        }
                    }
                    if (callback)
                        callback(PG.pages[num - 1]);
                } else {
                    data = settings.data;
                    data['cstr'] = PG.pages[num - 2]['cstr'];
                    MAPI.ajaxcall({
                        data: data,
                        type: 'POST',
                        url: settings.ajax_url,
                        async: true,
                        success: function(data) {
                            if ($this.is('table')) {
                                data.body['html'] = $($.trim(data.body['html'])).find('tbody').html();
                            } else if ($this.is('div.paginator-ul')) {
                                if ($this.is('div.tabbed-paginator')) {
                                    $(paginationId).children().remove();
                                    $(paginationId).append($($.trim(data.body.html)));
                                }
                                data.body['html'] = $($.trim(data.body['html']));
                            }
                            PG.pages[num - 1] = $.extend(false, {}, data.body);
                            PG.previous_page = PG.current_page;
                            PG.current_page = parseInt(num, 10);
                            PG.addButtons();
                            if (callback)
                                callback(data.body);


                        }
                    });
                }
                window.exitSelection();
            }
        };
        if ($this.is('table'))
            PG.pagination_html_base = $this.find('tbody');
        else if ($this.is('div.paginator-ul')) {
            PG.pagination_html_base = $this.find('ul');
        }
        var html_base = (typeof PG.pagination_html_base !== "undefined" && PG.pagination_html_base !== null) ? PG.pagination_html_base.html() : '';
        PG.pages[0] = {
            'has_more': more,
            'cstr': cstr,
            'html': html_base
        };
        if (more) {
            PG.pagination_base = $('<ul>');
            this.after($('<div class="pagination">').append(PG.pagination_base));
            PG.prev_but = $('<li><a href="#"><span>&laquo;</span> Previous</a></li>');
            PG.next_but = $('<li><a href="#">Next <span>&raquo;</span></a></li>');
            PG.addButtons();
        } else {
            if (typeof(PaginatorAPICallback) !== "undefined") {
                PaginatorAPICallback(PG.current_page);
            }
        }
        return {
            selectNextPage: PG.selectNextPage,
            selectPrevPage: PG.selectPrevPage
        }
    };
    var paginate_xhr = null;
    $.fn.paginate = function(pagination_url, data, max_pages, callback) {
        var PG;
        if (!data) {
            data = {};
        }
        var data_for_paginator = {
            'ajax_url': pagination_url,
            'data': data
        };
        if (max_pages && parseInt(max_pages, 10)) {
            data_for_paginator['max_pages'] = parseInt(max_pages, 10);
        }
        if (paginate_xhr) {
            paginate_xhr.abort();
        }
        var pagination_id = '#' + $(this).attr('id');
        var $this = $(this);
        $this.removeClass('loaded');
        $this.html('<p class="loading"></p>');
        paginate_xhr = MAPI.ajaxcall({
            url: pagination_url,
            data: data,
            type: 'POST',
            async: true,
            success: function(data) {
                $this.addClass('loaded');
                $(pagination_id).html(data.body.html);
                var paginator = $('#page-table').mmPaginator(data.body.has_more, data.body.cstr, data_for_paginator, pagination_id, callback);
                if (callback)
                    callback(data.body, paginator);
            },
            complete: function() {
                paginate_xhr = null;
            }
        });
    };
})(jQuery);