jQuery(document).ready(function() {
  jQuery("input[type=checkbox].switch").each(function() {
    jQuery(this).before(
      '<span class="switch">' +
      '<span class="background" /><span class="mask" />' +
      '</span>'
    );
    jQuery(this).hide();
 	if (!jQuery(this)[0].checked) {
      jQuery(this).prev().find(".background").css({left: "-56px"});
    }
  });
  jQuery("span.switch").click(function() {
    if (jQuery(this).next()[0].checked) {
      jQuery(this).find(".background").animate({left: "-56px"}, 200);
    } else {
      jQuery(this).find(".background").animate({left: "0px"}, 200);
    }
    jQuery(this).next()[0].checked = !jQuery(this).next()[0].checked;
  });
});