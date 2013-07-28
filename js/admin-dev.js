/**
 * jQuery to power image uploads, modifications and removals.
 *
 * The object passed to this script file via wp_localize_script is
 * soliloquy.
 *
 * @package   TGM-Soliloquy
 * @version   1.0.0
 * @author    Thomas Griffin <thomas@thomasgriffinmedia.com>
 * @copyright Copyright (c) 2012, Thomas Griffin
 */
jQuery(document).ready(function($) {

	/** Prepare formfield variable */
	var formfield;

	/** Hide elements on page load */
	$('.soliloquy-image-meta').hide();

	/** Set default post meta fields */
	if ( $('#soliloquy-width').length > 0 && 0 == $('#soliloquy-width').val().length ) {
		$('#soliloquy-width').val(soliloquy.width);
	}

	if ( $('#soliloquy-height').length > 0 && 0 == $('#soliloquy-height').val().length ) {
		$('#soliloquy-height').val(soliloquy.height);
	}

	if ( $('#soliloquy-speed').length > 0 && 0 == $('#soliloquy-speed').val().length ) {
		$('#soliloquy-speed').val(soliloquy.speed);
	}

	if ( $('#soliloquy-duration').length > 0 && 0 == $('#soliloquy-duration').val().length ) {
		$('#soliloquy-duration').val(soliloquy.duration);
	}

	/** Process fadeToggle for slider size explanation */
	$('.soliloquy-size-more').on('click.soliloquySizeExplain', function(e) {
		e.preventDefault();
		$('#soliloquy-explain-size').fadeToggle();
	});

	/** Process image removals */
	$('#soliloquy-area').on('click.soliloquyRemove', '.remove-image', function(e) {
		e.preventDefault();
		formfield = $(this).parent().attr('id');

		/** Output loading icon and message */
		$('#soliloquy-upload').after('<span class="soliloquy-waiting"><img class="spinner" src="' + soliloquy.spinner + '" width="16px" height="16px" style="margin: 0 5px; vertical-align: bottom;" />' + soliloquy.removing + '</span>');

		/** Prepare our data to be sent via Ajax */
		var remove = {
			action: 		'soliloquy_remove_images',
			attachment_id: 	formfield,
			nonce: 			soliloquy.removenonce
		};

		/** Process the Ajax response and output all the necessary data */
		$.post(
			soliloquy.ajaxurl,
			remove,
			function(response) {
				$('#' + formfield).fadeOut('normal', function() {
					$(this).remove();

					/** Remove the spinner and loading message */
					$('.soliloquy-waiting').fadeOut('normal', function() {
						$(this).remove();
					});
				});
			},
			'json'
		);
	});

	/** Use thickbox to handle image meta fields */
	$('#soliloquy-area').on('click.soliloquyModify', '.modify-image', function(e) {
		e.preventDefault();
		$('html').addClass('soliloquy-editing');
		formfield = $(this).next().attr('id');
		tb_show( soliloquy.modifytb, 'TB_inline?width=640&height=500&inlineId=' + formfield );

		/** Close thickbox if they click the actual close button */
		$(document).contents().find('#TB_closeWindowButton').on('click.soliloquyIframe', function() {
			if( $('html').hasClass('soliloquy-editing') ) {
				$('html').removeClass('soliloquy-editing');
				tb_remove();
			}
		});

		/** Close thickbox if they click the overlay */
		$(document).contents().find('#TB_overlay').on('click.soliloquyIframe', function() {
			if( $('html').hasClass('soliloquy-editing') ) {
				$('html').removeClass('soliloquy-editing');
				tb_remove();
			}
		});

		return false;
	});

	/** Save image meta via Ajax */
	$(document).on('click.soliloquyMeta', '.soliloquy-meta-submit', function(e) {
		e.preventDefault();

		/** Set default meta values that any addon would need */
		var table 		= $(this).parent().find('.soliloquy-meta-table').attr('id');
		var attach 		= table.split('-');
		var attach_id 	= attach[3];

		/** Prepare our data to be sent via Ajax */
		var meta = {
			action: 	'soliloquy_update_meta',
			attach: 	attach_id,
			id: 		soliloquy.id,
			nonce: 		soliloquy.metanonce
		};

		/** Loop through each table item and send data for every item that has a usable class */
		$('#' + table + ' td').each(function() {
			/** Grab all the items within each td element */
			var children = $(this).find('*');

			/** Loop through each child element */
			$.each(children, function() {
				var field_class = $(this).attr('class');
				var field_val 	= $(this).val();

				if ( 'checkbox' == $(this).attr('type') )
					var field_val = $(this).is(':checked') ? 'true' : 'false';

				/** Store all data in the meta object */
				meta[field_class] = field_val;
			});
		});

		/** Output loading icon and message */
		$(this).after('<span class="soliloquy-waiting"><img class="spinner" src="' + soliloquy.spinner + '" width="16px" height="16px" style="margin: 0 5px; vertical-align: middle;" />' + soliloquy.saving + '</span>');

		/** Process the Ajax response and output all the necessary data */
		$.post(
			soliloquy.ajaxurl,
			meta,
			function(response) {
				/** Remove the spinner and loading message */
				$('.soliloquy-waiting').fadeOut('normal', function() {
					$(this).remove();
				});

				/** Remove thickbox with a slight delay */
				var metaTimeout = setTimeout(function() {
					$('html').removeClass('soliloquy-editing');
					tb_remove();
				}, 1000);
			},
			'json'
		);
	});

	/** Use thickbox to handle image uploads */
	$('#soliloquy-area').on('click.soliloquyUpload', '#soliloquy-upload', function(e) {
		e.preventDefault();
		$('html').addClass('soliloquy-uploading');
		formfield = $(this).parent().prev().attr('name');
 		tb_show( soliloquy.upload, 'media-upload.php?post_id=' + soliloquy.id + '&type=image&context=soliloquy-image-uploads&TB_iframe=true&width=640&height=500' );

 		/** Refresh image list and meta if a user selects to save changes instead of insert into the slider gallery */
		$(document).contents().find('#TB_closeWindowButton').on('click.soliloquyIframe', function() {
			/** Refresh if they click the actual close button */
			if( $('html').hasClass('soliloquy-uploading') ) {
				$('html').removeClass('soliloquy-uploading');
				tb_remove();
				soliloquyRefresh();
			}
		});

		/** Refresh if they click the overlay */
		$(document).contents().find('#TB_overlay').on('click.soliloquyIframe', function() {
			if( $('html').hasClass('soliloquy-uploading') ) {
				$('html').removeClass('soliloquy-uploading');
				tb_remove();
				soliloquyRefresh();
			}
		});

 		return false;
	});

	window.original_send_to_editor = window.send_to_editor;

	/** Send out an ajax call to refresh the image attachment list */
	window.send_to_editor = function(html) {
		if (formfield) {
			/** Remove thickbox and extra html class */
			tb_remove();
			$('html').removeClass('soliloquy-uploading');

			/** Delay the processing of the refresh until thickbox has closed */
			var timeout = setTimeout(function() {
				soliloquyRefresh();
			}, 1500); // End timeout function
		}
		else {
 			window.original_send_to_editor(html);
 		}
	};

	/** Reset variables */
	var formfield 	= '';
	var remove 		= '';
	var table 		= '';
	var attach 		= '';
	var attach_id 	= '';
	var meta 		= '';
	var metaTimeout = '';
	var timeout 	= '';
	var refresh 	= '';

	/** Make image uploads sortable */
	var items = $('#soliloquy-images');

	/** Use Ajax to update the item order */
	if ( 0 !== items.length ) {
		items.sortable({
			containment: '#soliloquy-area',
			update: function(event, ui) {
				/** Show the loading text and icon */
				$('.soliloquy-waiting').show();

				/** Prepare our data to be sent via Ajax */
				var opts = {
					url: 		soliloquy.ajaxurl,
                	type: 		'post',
                	async: 		true,
                	cache: 		false,
                	dataType: 	'json',
                	data:{
                    	action: 	'soliloquy_sort_images',
						order: 		items.sortable('toArray').toString(),
						post_id: 	soliloquy.id,
						nonce: 		soliloquy.sortnonce
                	},
                	success: function(response) {
                    	$('.soliloquy-waiting').hide();
                    	return;
                	},
                	error: function(xhr, textStatus ,e) {
                    	$('.soliloquy-waiting').hide();
                    	return;
                	}
            	};
            	$.ajax(opts);
			}
		});
	}

	/** jQuery function for loading the image uploads */
	function soliloquyRefresh() {
		/** Prepare our data to be sent via Ajax */
		var refresh = {
			action: 'soliloquy_refresh_images',
			id: 	soliloquy.id,
			nonce: 	soliloquy.nonce
		};
		var output = '';

		/** Output loading icon and message */
		$('#soliloquy-upload').after('<span class="soliloquy-waiting"><img class="spinner" src="' + soliloquy.spinner + '" width="16px" height="16px" style="margin: 0 5px; vertical-align: bottom;" />' + soliloquy.loading + '</span>');

		/** Process the Ajax response and output all the necessary data */
		$.post(
			soliloquy.ajaxurl,
			refresh,
			function(json) {
				/** Loop through the object */
				$.each(json.images, function(i, object) {
					/** Store each image and its data into the image variable */
					var image = json.images[i];

					/** Store the output into a variable */
					output +=
						'<li id="' + image.id + '" class="soliloquy-image attachment-' + image.id + '">' +
							'<img src="' + image.src + '" width="' + image.width + '" height="' + image.height + '" />' +
							'<a href="#" class="remove-image" title="' + soliloquy.remove + '"></a>' +
							'<a href="#" class="modify-image" title="' + soliloquy.modify + '"></a>' +
							'<div id="meta-' + image.id + '" class="soliloquy-image-meta" style="display: none;">' +
								'<div class="soliloquy-meta-wrap">' +
									'<h2>' + soliloquy.metatitle + '</h2>' +
									'<p>' + soliloquy.metadesc + '</p>';
									if ( image.before_image_meta_table ) {
										$.each(image.before_image_meta_table, function(i, array) {
											output += image.before_image_meta_table[i];
										});
									}
									output += '<table id="soliloquy-meta-table-' + image.id + '" class="form-table soliloquy-meta-table">' +
										'<tbody>';
											if ( image.before_image_title ) {
												$.each(image.before_image_title, function(i, array) {
													output += image.before_image_title[i];
												});
											}
											output += '<tr id="soliloquy-title-box-' + image.id + '" valign="middle">' +
												'<th scope="row">' + soliloquy.title + '</th>' +
												'<td>' +
													'<input id="soliloquy-title-' + image.id + '" class="soliloquy-title" type="text" size="75" name="_soliloquy_uploads[title]" value="' + image.title + '" />' +
												'</td>' +
											'</tr>';
											if ( image.before_image_alt ) {
												$.each(image.before_image_alt, function(i, array) {
													output += image.before_image_alt[i];
												});
											}
											output += '<tr id="soliloquy-alt-box-' + image.id + '" valign="middle">' +
												'<th scope="row">' + soliloquy.alt + '</th>' +
												'<td>' +
													'<input id="soliloquy-alt-' + image.id + '" class="soliloquy-alt" type="text" size="75" name="_soliloquy_uploads[alt]" value="' + image.alt + '" />' +
												'</td>' +
											'</tr>';
											if ( image.before_image_link ) {
												$.each(image.before_image_link, function(i, array) {
													output += image.before_image_link[i];
												});
											}
											output += '<tr id="soliloquy-link-box-' + image.id + '" valign="middle">' +
												'<th scope="row">' + soliloquy.link + '</th>' +
												'<td>' +
													'<label class="soliloquy-link-url">' + soliloquy.url + '</label>' +
													'<input id="soliloquy-link-' + image.id + '" class="soliloquy-link" type="text" size="70" name="_soliloquy_uploads[link]" value="' + image.link + '" />' +
													'<label class="soliloquy-link-title-label">' + soliloquy.linktitle + '</label>' +
													'<input id="soliloquy-link-title-' + image.id + '" class="soliloquy-link-title" type="text" size="40" name="_soliloquy_uploads[link_title]" value="' + image.linktitle + '" />' +
													'<input id="soliloquy-link-tab-' + image.id + '" class="soliloquy-link-check" type="checkbox" name="_soliloquy_uploads[link_tab]" value="' + image.linktab + '"' + image.linkcheck + ' />' +
													'<span class="description">' + soliloquy.tab + '</span>' +
												'</td>' +
											'</tr>';
											if ( image.before_image_caption ) {
												$.each(image.before_image_caption, function(i, array) {
													output += image.before_image_caption[i];
												});
											}
											output += '<tr id="soliloquy-caption-box-' + image.id + '" valign="middle">' +
												'<th scope="row">' + soliloquy.caption + '</th>' +
												'<td>' +
													'<textarea id="soliloquy-caption-' + image.id + '" class="soliloquy-caption" rows="3" cols="75" name="_soliloquy_uploads[caption]">' + image.caption + '</textarea>' +
												'</td>' +
											'</tr>';
											if ( image.after_meta_defaults ) {
												$.each(image.after_meta_defaults, function(i, array) {
													output += image.after_meta_defaults[i];
												});
											}
										output += '</tbody>' +
									'</table>';
									if ( image.after_image_meta_table ) {
										$.each(image.after_image_meta_table, function(i, array) {
											output += image.after_image_meta_table[i];
										});
									}
									output += '<a href="#" class="soliloquy-meta-submit button-secondary" title="' + soliloquy.savemeta + '">' + soliloquy.savemeta + '</a>' +
								'</div>' +
							'</div>' +
						'</li>';
				});

				/** Load the new HTML with the newly uploaded images */
				$('#soliloquy-images').html(output);

				/** Hide the image meta when refreshing the list */
				$('.soliloquy-image-meta').hide();
			},
			'json'
		);

		/** Remove the spinner and loading message */
		$('.soliloquy-waiting').fadeOut('normal', function() {
			$(this).remove();
		});
	}

	/** Handle dismissing of upgrade notice */
	$('#soliloquy-dismiss-notice').on('click.soliloquyDismiss', function(e){
		/** Prevent the default action from occurring */
		e.preventDefault();

		/** Prepare our data to be sent via Ajax */
		var opts = {
			url: 		soliloquy.ajaxurl,
            type: 		'post',
            async: 		true,
            cache: 		false,
            dataType: 	'json',
            data:{
                action: 	'soliloquy_dismiss_notice',
				nonce: 		soliloquy.dismissnonce
            },
            success: function(response) {
                $('#setting-error-tgmsp-upgrade-soliloquy').fadeOut();
                return;
            },
            error: function(xhr, textStatus ,e) {
                return;
            }
        };
        $.ajax(opts);
	});

	// Handle opening the Soliloquy Addons image in a lightbox.
	$('.soliloquy-show-addons').magnificPopup({
		items: {
			type: 'inline',
			src: '#addons-image'
		},
		closeBtnInside: true,
		closeOnBgClick: true
	});
	$('.soliloquy-comparison-show').magnificPopup({
		items: {
			type: 'inline',
			src: '#soliloquy-comparison'
		},
		closeBtnInside: true,
		closeOnBgClick: true
	});

	// Handle Stripe checkout goodness.
	$('.soliloquy-do-upgrade').on('click.soliloquyUpgrade', function(e){
		// Prevent the default action from occurring.
		e.preventDefault();

		var $this = $(this);
		$this.parent().slideUp(300, function(){
			$this.parent().next().slideDown(300, function(){
				$('input[type="email"]').focus();
				$.getScript('https://checkout.stripe.com/v2/checkout.js').done();
			});
		});
	});

	var upgrade_email = false,
		external_res  = false;
	$('.soliloquy-start-upgrade').on('click', function(e){
		e.preventDefault();
		$('.text-danger').remove();
		var $this = $(this),
			email_addy = $this.parent().parent().find('input[type="text"]').val(),
			token_id;

		if ( ! soliloquyValidEmailAddress(email_addy) ) {
			$('<p style="margin-top:15px;" class="text-danger">' + soliloquy.danger + '</p>').appendTo($this.parent().parent());
			return;
		}

		// If we have reached this point, victory - let's do it.
		upgrade_email = email_addy;

		var token = function(res){
	        token_id = res.id;
	        $('.tgm-plugin-overlay').appendTo('body').show();
			$('.tgm-plugin-processing').css({ top: ($(window).height() - $('.tgm-plugin-processing').outerHeight()) / 2, left: ($(window).width() - $('.tgm-plugin-processing').outerWidth()) / 2 });
			$(window).resize(function(){$('.tgm-plugin-processing').css({ top: ($(window).height() - jQuery('.tgm-plugin-processing').outerHeight()) / 2, left: ($(window).width() - $('.tgm-plugin-processing').outerWidth()) / 2 });});

		    // Send the JSONP request to create the account and process the transaction.
		    $.getJSON('http://soliloquywp.com/?soliloquy-stripe-processing=true&stripe-token=' + token_id + '&plugin=' + $this.data('plugin') + '&slug=' + $this.data('slug') + '&amount=' + $this.data('amount') + '&user-email=' + upgrade_email + '&callback=?', function(res){
				// If for some reason we do not receive a response or the response is empty, silently fail.
				if ( ! res || $.isEmptyObject(res) ) {
					$('.tgm-plugin-processing h3').text(soliloquy.stripe_err);
				};

				// If there is an error, output the error and hide the updating screen.
				if ( res && res.error ) {
					$this.after('<p style="margin:15px 0 0;" class="text-danger">' + res.error + '</p>');
					$('.tgm-plugin-overlay').fadeOut(300);
					return;
				}

				// If we have reached this point, success! Let's process the upgrade.
				$('.tgm-plugin-processing h3').text(soliloquy.upgrade);
				external_res = res;

				// Process the ajax request to upgrade the plugin.
				$.post(soliloquy.ajaxurl, { action: 'soliloquy_do_plugin_upgrade', download: external_res.download_url, key: external_res.license, single: external_res.single }, function(res){
					/** If there is a WP Error instance, output it here and quit the script */
	                if ( res.error ) {
	                	$('.tgm-plugin-processing h3').text('There was an error installing the upgrade.');
	                	return;
	                }

	                /** If we need more credentials, output the form sent back to us */
	                if ( res.form ) {
	                	$('.tgm-plugin-processing h3').addClass('text-left').text('Oops - we need some more information before the upgrade can be installed!');
	                	$('.tgm-plugin-processing').append(res.form);
	                	$('.tgm-plugin-processing').css({ top: ($(window).height() - $('.tgm-plugin-processing').outerHeight()) / 2, left: ($(window).width() - $('.tgm-plugin-processing').outerWidth()) / 2 });

	                	$(document).on('click.installCredsAddon', '#upgrade', function(e){
	                		/** Prevent the default action, let the user know we are attempting to install again and go with it */
	                		e.preventDefault();
	                		var button_text = $('.tgm-plugin-processing h3').text()
	                		$('.tgm-plugin-processing h3').text('Working...');
	                		$('.soliloquy-inline-error').remove();

	                		/** Now let's make another Ajax request once the user has submitted their credentials */
	                		var hostname 	= $(this).parent().parent().find('#hostname').val();
	                		var username	= $(this).parent().parent().find('#username').val();
	                		var password	= $(this).parent().parent().find('#password').val();
	                		var proceed		= $(this);
	                		var connect		= $(this).parent().parent().parent().parent();
	                		var cred_opts 	= {
	                			url: 		ajaxurl,
	            				type: 		'post',
	            				async: 		true,
	            				cache: 		false,
	            				dataType: 	'json',
	            				data: {
	                				action: 	'soliloquy_do_plugin_upgrade',
									download:   external_res.download_url,
									key:		external_res.license,
									single:     external_res.single,
									hostname:	hostname,
									username:	username,
									password:	password
	            				},
	            				success: function(res) {
	            					/** If there is a WP Error instance, output it here and quit the script */
	                				if ( res.error ) {
	                					$('.soliloquy-inline-error').remove();
	                					$('.tgm-plugin-processing h3').text('There was an error installing the upgrade.');
	                					return;
	                				}

	                				if ( res.form ) {
	                					$(proceed).after('<span class="soliloquy-inline-error" style="font-weight:bold;padding-left:5px;">The information provided was incorrect. Please try again to continue with the upgrade.</span>');
	                					$('.tgm-plugin-processing h3').text(button_text);
	                					return;
	                				}

	                				/** The Ajax request was successful, so let's update the output */
									window.location.href = res.page;
	            				},
	            				error: function(xhr, textStatus ,e) {
	                				return;
	            				}
	                		}
	                		$.ajax(cred_opts);
	                	});

	                	/** No need to move further if we need to enter our creds */
	                	return;
	                }

	                /** The Ajax request was successful, so let's update the output */
	                window.location.href = res.page;
				}, 'json');
			});
	      };

	      StripeCheckout.open({
	        key:         'pk_live_oFViDOfW8oyvaTHp9JrFVH3q',
	        address:     false,
	        amount:      $this.data('amount'),
	        currency:    'usd',
	        name:        $this.data('name'),
	        description: $this.data('desc'),
	        panelLabel:  $this.data('panel'),
	        image:		 soliloquy.stripe,
	        token:       token
	      });
	});

	function soliloquyValidEmailAddress(emailAddress) {
	    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
	    return pattern.test(emailAddress);
	}

});