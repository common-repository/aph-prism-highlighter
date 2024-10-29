(function($) {
	$(document).ready(function()
	{
		var delAllAction = false,
			lang_tobe_checked = [];
		
		$('#aphph-langused-container').delegate('a.aphph-del-lang', 'click', function(e, type)
		{
			e.preventDefault(e);
			var lang = $(this).parent().children('input').val();
			$('#aphph-langlist-' + lang).trigger('click', type);
		});
		
		$('#aphph-langlist-container').delegate('input', 'click', function(e, type)
		{
			var $this = $(this),
				lang = $this.val(),
				lang_name = $.trim($this.parent().text());
			
			// Change state from unchecked to checked
			if ($this.is(':checked'))
			{
				var prefix = lang.substr(0,3);
				// Check dependency
				
				if (prefix == 'add') {
					var lang_component = {};
					lang_component.require = undefined;
				} else {
					var lang_component = components.languages[lang];
				}
				
				var lang_required = [],
					curr_lang = {'lang':lang, 'lang_name': lang_name};
				
				// Save dependency fo build later				
				lang_tobe_checked.push(curr_lang);
				build_lang_tobe_checked = true;
				if ( lang_component.require != undefined)
				{
					lang_required = lang_component.require;
					if (typeof lang_required == 'string') {
						lang_required = [lang_required];
					}

					for (k in lang_required)
					{
						var $checkbox = $('#aphph-langlist-' + lang_required[k]);
						
						// Click the dependency
						if (!$checkbox.is(':checked')) {
							$checkbox.click();
							// alert('aphph-langlist-' + lang_required[k]);
						} else {
							// Build the lang_tobe_checked
							build_lang_tobe_checked = true;
						}
					}
				}
				
				// Show the delete all button
				$('#aphph-delall-langused').fadeIn('fast');
				
				// Build
				if (build_lang_tobe_checked)
				{
					// Reverse the order of dependency
					reverse = [];
					for (k = lang_tobe_checked.length - 1; k >= 0 ;k--)
					{
						reverse.push(lang_tobe_checked[k]);
					}
					
					// Build html button
					for (index in reverse) {
						var html = '<div class="aphph-langused-item" id="aphph-langused-' + reverse[index].lang + '">' +
										'<input type="hidden" name="aphph_options[lang-used][]" value="' + reverse[index].lang + '">' + reverse[index].lang_name +
										'<a href="#" class="aphph-del-lang"><i class="aphph-icon-cross"></i></a>' +
									'</div>';
						$(html).appendTo('#aphph-langused-container').hide().fadeIn('fast');
					}
					lang_tobe_checked = [];
				}
			}
			
			// Change state from checked to unchecked
			else 
			{
				$('#aphph-langused-' + lang).fadeOut('fast', function()
				{
					$(this).remove();
					if ($('#aphph-langused-container').find('a').length == 0)
						$('#aphph-delall-langused').fadeOut('fast');
				});
				
				if (delAllAction) {
					return;
				}

				// Remove all language that depend on this lang
				dependency = [];
				for (prism_lang in components.languages)
				{
					required = components.languages[prism_lang].require;
					if (required != undefined) 
					{
						if (typeof required == 'string') {
							if (required == lang) {
								dependency.push(prism_lang);
							}
						} else {
							for (k in required) {
								if (required[k] == lang) {
									dependency.push(prism_lang);
								}
							}
						}
					}
				}
				
				for (k in dependency) {
					var $checkbox = $('#aphph-langlist-' + dependency[k]);
					if ($checkbox.is(':checked'))
						$checkbox.click();
				}
				
			}
		});
		
		// Add Language
		$('#aphph-show-lang').click(function(e)
		{
			e.preventDefault();
			$('#aphph-langlist-container').fadeToggle();
		});
		
		// Delete all lang used
		$('#aphph-delall-langused').click(function(e)
		{
			delAllAction = true;
			e.preventDefault();
			$('#aphph-langused-container').find('a').trigger('click', 'delall');
			$(this).fadeOut('fast', function(){
				delAllAction = false;
			});
		});
		// Restore to defaults settings
		$('#aphph-defaults').click(function()
		{
			var popup_confirm = confirm('Are you sure want to restore to the default settings?');
			if (popup_confirm == false)
				return false;
		});
		
		$('#aphph-add-css-option').change(function()
		{ 
			if ($(this).val() == 1)
			{
				$('#aphph-add-css-container').fadeIn('fast');
			} else {
				$('#aphph-add-css-container').fadeOut('fast');
			}
		});
		
		$('#aphph-css-example-btn').click(function()
		{
			$('#aphph-css-example').fadeToggle('fast');
			return false;
		});
		var textarea = document.getElementById('aphph-add-css-textarea');
		tabOverride.set(textarea);
	});
})(jQuery);
