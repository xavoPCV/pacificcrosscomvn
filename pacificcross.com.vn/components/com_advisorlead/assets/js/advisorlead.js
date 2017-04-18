$(document).ready(function() {

    var sub_nav = $('#sub_nav');

    if (typeof sub_nav != 'undefined') {
        var items = sub_nav.find('li');
        sub_nav.addClass('active').appendTo('ul#left_menu > li.active');

    }
});