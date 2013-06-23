(function() {
	tinymce.create( "tinymce.plugins.WPC_Client_Shortcodes",
		{
			init: function( d,e ) {},
			createControl: function( d,e )
			{
				if ( 'wpc_client_button_shortcodes' == d ) {
					d = e.createMenuButton( 'wpc_client_button_shortcodes',{
						title: 'Insert Placeholders & Shortcode',
						icons: false
						});

						var a=this;
                        d.onRenderMenu.add( function( c, b ) {
                            c = b.addMenu( {title: 'Placeholders: General'} );
                                a.addImmediate( c, '{site_title}', '{site_title}' );
                                a.addImmediate( c, '{contact_name}', '{contact_name}' );
                                a.addImmediate( c, '{client_name}', '{client_name}' );
                                a.addImmediate( c, '{user_name}', '{user_name}' );
                                a.addImmediate( c, '{login_url}', '{login_url}' );
                                a.addImmediate( c, '{logout_url}', '{logout_url}' );

                            b.addSeparator();

                            c = b.addMenu( {title: 'Placeholders: Business'} );
                                a.addImmediate( c, '{business_logo_url}', '{business_logo_url}' );
                                a.addImmediate( c, '{business_name}', '{business_name}' );
                                a.addImmediate( c, '{business_address}', '{business_address}' );
                                a.addImmediate( c, '{business_mailing_address}', '{business_mailing_address}' );
                                a.addImmediate( c, '{business_website}', '{business_website}' );
                                a.addImmediate( c, '{business_email}', '{business_email}' );
                                a.addImmediate( c, '{business_phone}', '{business_phone}' );
                                a.addImmediate( c, '{business_fax}', '{business_fax}' );

                            b.addSeparator();

                                c = b.addMenu( {title: 'Placeholders: Specific'} );
                                a.addImmediate( c, '{admin_url}', '{admin_url}' );
                                a.addImmediate( c, '{approve_url}', '{approve_url}' );
                                a.addImmediate( c, '{password}', '{password}' );
                                a.addImmediate( c, '{page_title}', '{page_title}' );
                                a.addImmediate( c, '{admin_file_url}', '{admin_file_url}' );
                                a.addImmediate( c, '{message}', '{message}' );
                                a.addImmediate( c, '{file_name}', '{file_name}' );
                                a.addImmediate( c, '{file_category}', '{file_category}' );

                            b.addSeparator();

							c = b.addMenu( {title: 'Shortcodes: Pages'} );
                                a.addImmediate( c, 'Redirect on Login or HUB', '[wpc_redirect_on_login_hub]' );
                                a.addImmediate( c, 'Login From', '[wpc_client_loginf]' );
                                a.addImmediate( c, 'HUB Page', '[wpc_client_hub_page]' );
                                a.addImmediate( c, 'Portal Page', '[wpc_client_portal_page]' );
                                a.addImmediate( c, 'Edit Portal Page', '[wpc_client_edit_portal_page]' );
                                a.addImmediate( c, 'Staff Directory', '[wpc_client_staff_directory]' );
                                a.addImmediate( c, 'Add Staff', '[wpc_client_add_staff_form]' );
                                a.addImmediate( c, 'Edit Staff', '[wpc_client_edit_staff_form]' );
                                a.addImmediate( c, 'Client Registration', '[wpc_client_registration_form]' );
                                a.addImmediate( c, 'Successful Client Registration', '[wpc_client_registration_successful]' );
                                a.addImmediate( c, 'Feedback Wizard', '[wpc_client_feedback_wizard]' );
                                a.addImmediate( c, 'Feedback Wizard List', '[wpc_client_feedback_wizards_list]' );
                                a.addImmediate( c, 'Invoicing', '[wpc_client_invoicing]' );
                                a.addImmediate( c, 'Invoicing List', '[wpc_client_invoicing_list type="invoice|estimate" status="new|inprocess|paid" ]' );

                            b.addSeparator();

                            c = b.addMenu( {title: 'Shortcodes: Others'} );
                                a.addImmediate( c, 'Page Url', '[wpc_client_get_page_link page="hub|login|client_registration|feedback_wizard_list|invoicing_list|staff_directory|add_staff" text="Some Link" id="" class="" style="" ]' );
                                a.addImmediate( c, 'wpc_client', '[wpc_client]' );
                                a.addImmediate( c, 'Logout Link', '[wpc_client_logoutb]' );
                                a.addImmediate( c, 'Private Content', '[wpc_client_private for="" for_circle="" ]' );
                                a.addImmediate( c, 'Images URL', '[wpc_client_theme]' );
                                a.addImmediate( c, 'Files Client Have Access To', '[wpc_client_filesla show_sort="yes|no" show_date="yes|no" show_size="yes|no" category="" exclude_author="false|true" ]' );
                                a.addImmediate( c, 'Files Client Have Uploaded', '[wpc_client_fileslu show_sort="yes|no" show_date="yes|no" show_size="yes|no" category="" ]' );
                                a.addImmediate( c, 'File Upload Form', '[wpc_client_uploadf category="ID|name"]' );
                                a.addImmediate( c, 'List of Client Portals', '[wpc_client_pagel show_current_page="no|yes" sort_type="date|title" sort="asc|desc" ]' );
                                a.addImmediate( c, 'Private Messages', '[wpc_client_com]' );
                                a.addImmediate( c, 'Graphic', '[wpc_client_graphic]' );
						});
					return d
				}
				return null
			},
			addImmediate: function( d, e, a ){ d.add({ title: e, onclick: function(){ tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, a )} }) }
		}
	);
	tinymce.PluginManager.add( 'WPC_Client_Shortcodes', tinymce.plugins.WPC_Client_Shortcodes );
}
)();