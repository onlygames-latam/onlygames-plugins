/* 
    scripts.js
    v.1.0.3
*/

(function(jQuery){
    jQuery(document).ready(function(){		//when DOM is ready
        constellation.init();
    });
})(jQuery);

var constellation = {
    init: function() {
           
        constellation.initMenuConstellationActiveCheckbox();
         
        //In case the menu is already saved
        var items = jQuery('.menu-item');
        items.each(function(){
            var item = jQuery(this).find('.menu-item-settings');
            var id = jQuery(this).attr('id').replace('menu-item-', '');
            
            constellation.getItemFormFields(item, id);
        });

    },
    getItemFormFields: function(item, id) {
        var data = { action: 'get_cm_fields', cm_item: id };
        jQuery.post(cm_data.ajax_url, data, function(response) {
            item.find('p').last().after(response);
            constellation.initExtraSettingsVisibility();
        }, 'html');
    },
    initExtraSettingsVisibility: function() {
        jQuery('.cm-settings-wrapper input[type=checkbox]').change(function(){
            var item = jQuery(this).parents('.cm-settings-wrapper').find('.cm-extra-settings');
            
            if(jQuery(this).is(':checked'))
            {
                item.slideDown();
                item.removeClass('active');
            }
            else
            {
                item.slideUp();
                item.addClass('active');
            }
        });

        //hide ALL settings if constellation is not active for this menu
        if(jQuery('#constellation-status').is(':checked'))
        {
            jQuery('.cm-settings-wrapper').show();
        }
        else
        {
            jQuery('.cm-settings-wrapper').hide();
        }
                
    },
    initMenuConstellationActiveCheckbox: function() {

        jQuery('.menu-settings fieldset').last().after(cm_data.cm_status_form);
        var menu_name = jQuery('#menu-name').val();
		
		/* v.1.0.3  - added menu name decode and stripslashes to work with special shars in menu name*/
		var decoded = new Array();

		for(i in cm_data.menu_data)
		{
			var text = cm_data.menu_data[i];
			decoded.push(stripslashes(jQuery('<div/>').html(text).text()));
		}		
		
		if(decoded.indexOf(menu_name) > -1)   //if menu is found in the active list, check it
        {
            jQuery('#constellation-status').prop('checked', true);
        }
        
        jQuery('#constellation-status').change(function(){
            
            if(jQuery(this).is(':checked'))
            {
                jQuery('.cm-settings-wrapper').show();
            }
            else
            {
                jQuery('.cm-settings-wrapper').hide();
            }  
        });
    }
}

function HTMLEncode(str) {
  var i = str.length,
      aRet = [];

  while (i--) {
    var iC = str[i].charCodeAt();
    if (iC < 65 || iC > 127 || (iC>90 && iC<97) || iC !== 32) {
      aRet[i] = '&#'+iC+';';
    } else {
      aRet[i] = str[i];
    }
   }
  return aRet.join('');    
}

function stripslashes(str) {

  return (str + '')
    .replace(/\\(.?)/g, function(s, n1) {
      switch (n1) {
        case '\\':
          return '\\';
        case '0':
          return '\u0000';
        case '':
          return '';
        default:
          return n1;
      }
    });
}