jQuery(function($){var $alldivs=$('div.tabcontent');var $outerdivs=$('div.tabouter');$outerdivs.each(function(){var $alldivs=$(this).find('div.tabcontent');var count=0;var countankers=0;$alldivs.each(function(){var $el=$(this);count++;$el.attr('role','tabpanel');$el.attr('aria-hidden','false');$el.attr('aria-expanded','true');elid=$el.attr('id');elid=elid.split('_');elid='link_'+elid[1];$el.attr('aria-labelledby',elid);if(count!==1){$el.addClass('tabclosed').removeClass('tabopen');$el.attr('aria-hidden','true');$el.attr('aria-expanded','false');}});$allankers=$(this).find('ul.tabs').first().find('a');$allankers.each(function(){countankers++;var $el=$(this);$el.attr('aria-selected','true');$el.attr('role','tab');linkid=$el.attr('id');moduleid=linkid.split('_');moduleid='module_'+moduleid[1];$el.attr('aria-controls',moduleid);if(countankers!=1){$el.addClass('linkclosed').removeClass('linkopen');$el.attr('aria-selected','false');}});});});function tabshow(elid){var $=jQuery.noConflict();var $el=$('#'+elid);var $outerdiv=$el.parent();var $alldivs=$outerdiv.find('div.tabcontent');var $liste=$outerdiv.find('ul.tabs').first();$liste.find('a').attr('aria-selected','false');$alldivs.each(function(){var $element=$(this);$element.addClass('tabclosed').removeClass('tabopen');$element.attr('aria-hidden','true');$element.attr('aria-expanded','false');});$el.addClass('tabopen').removeClass('tabclosed');$el.attr('aria-hidden','false');$el.attr('aria-expanded','true');$el.focus();var getid=elid.split('_');var activelink='#link_'+getid[1];$(activelink).attr('aria-selected','true');$liste.find('a').addClass('linkclosed').removeClass('linkopen');$(activelink).addClass('linkopen').removeClass('linkclosed');}function nexttab(el){var $=jQuery.noConflict();var $outerdiv=$('#'+el).parent();var $liste=$outerdiv.find('ul.tabs').first();var getid=el.split('_');var activelink='#link_'+getid[1];var aktiverlink=$(activelink).attr('aria-selected');var $tablinks=$liste.find('a');for(var i=0;i<$tablinks.length;i++){if($($tablinks[i]).attr('id')===activelink){if($($tablinks[i+1]).length){$($tablinks[i+1]).click();break;}}}}