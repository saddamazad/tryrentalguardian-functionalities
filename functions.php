<?php
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'load-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
	wp_enqueue_style( 'signature-style', get_stylesheet_directory_uri() . '/css/jquery.signature.css' );
	//wp_enqueue_style( 'jquery-ui', get_stylesheet_directory_uri() . '/inc/jquery-ui.css' );
    wp_enqueue_script( 'jquery-ui', get_stylesheet_directory_uri() .  '/inc/jquery-ui.js', array(), '1.12.1', true );
	wp_enqueue_script( 'jquery-ui-touch-punch', get_stylesheet_directory_uri() .  '/js/jquery.ui.touch-punch.min.js', array(), '0.2.3', true );
	wp_enqueue_script( 'jquery-form', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js', array(), '3.51.0', true );
	wp_enqueue_script( 'jquery-signature', get_stylesheet_directory_uri() .  '/js/jquery.signature.min.js', array(), '1.2.0', true );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

add_action('wp_head', 'add_company_info_tab_script');
function add_company_info_tab_script(){ ?>
	<?php $user_id = get_current_user_id(); ?>
    <style type="text/css">
        .tml_container p.error { color: red; font-size: 16px; text-align: center; }
		/*.tab-icon { color: #ffffff; }*/
		.already-signed { font-size: 16px; padding: 50px 0 !important; font-weight: 700; color: #19BC9D; }
		.form_cont { border: 1px solid #dddddd; margin-bottom: 10px; }
		.payment-errors.alert-success, .payment-errors.alert-error { padding-bottom: 8px; }
        .tml.tml-resetpass { padding: 20px 0; }
        .tml.tml-resetpass input[type="text"], .tml.tml-resetpass input[type="password"] { padding: 5px 8px 5px 8px; }
        div#pass-strength-result { font-weight: 700; color: cornflowerblue; }
        <?php if( isset($_GET['checkemail']) && ($_GET['checkemail'] == "confirm") ) { ?>
        .tml.tml-login p.message { display: block; text-align: center; padding: 12px 0 0; color: red; }
        <?php } ?>
        #payment-form input[type="password"] { background: #F3F4F6; border-radius: 4px; border: none; height: 36px; }
    </style>
    <script type="text/javascript">
        jQuery( document ).ready(function() {
            var $tabs = jQuery( "#company_info" ).tabs();
            
            jQuery( "#schedule_tab .add_attendee" ).on( "click", function() {
                var attendee_num = jQuery( "#schedule_tab #num_of_attendee" ).val();
                attendee_num++;
                jQuery('<div class="three_col"><input type="text" name="attend_'+attendee_num+'_name" id="attend_'+attendee_num+'_name" placeholder="Full Name"  /></div><div class="three_col"><input type="text" name="attend_'+attendee_num+'_email" id="attend_'+attendee_num+'_email"  placeholder="Email Address" /></div><div class="three_col last"></div><div class="clearfix"></div>').insertBefore( jQuery( "#schedule_tab .add_attendee" ) );
                jQuery( "#schedule_tab #num_of_attendee" ).val(attendee_num);
            });
            
            if( jQuery(".company_info_steps").length ) {
                var total_tabs = jQuery(".company_info_steps[data-tab]").length;
                if( jQuery("#licensing_tab1").length ) {
                    total_tabs--;
                }
                //alert(total_tabs);
                var tab_length = 0;
                jQuery('.company_info_steps[data-tab]').each(function (index, value){
                    tab_length++;
                    if( !jQuery(this).attr('data-tab-index') ) {
                        jQuery(this).attr('data-tab', tab_length);
                    } else {
                        tab_length--;
                        jQuery(this).attr('data-tab', tab_length);
                    }
                });
            }
            
            jQuery( ".btn_next[data-action=next-step]" ).on( "click", function() {
                var form_id = jQuery(this).closest("form").attr("id");
                var step_id_base = jQuery(this).parents(".company_info_steps").attr("data-id-base");
                var curr_step = parseInt(jQuery(this).parents(".company_info_steps").attr("data-step"));
                var next_step = curr_step+1;
                if( document.getElementById(form_id).checkValidity() ) {
					var curr_step_id = jQuery( ".company_info_steps[data-step="+curr_step+"]" ).attr("id");
					if(step_id_base == "company_tab") {
						jQuery( "#"+step_id_base+curr_step ).find(".hidden-progress").val(100);
					} else if(step_id_base == "licensing_tab") {
						/*if( (curr_step == 3) || (curr_step == 4) ) {
							if( jQuery("#"+curr_step_id).find(".docusign-form-done").val() == "Yes" ) {
								jQuery( "#"+curr_step_id ).find(".hidden-progress").val(100);
							} else {
								jQuery( "#"+curr_step_id ).find(".hidden-progress").val(0);
							}
						} else {*/
							jQuery( "#"+step_id_base+curr_step ).find(".hidden-progress").val(100);
						/*}*/
					}

                    jQuery("#"+form_id).ajaxForm( {
                        dataType: 'json',
                        data: {ued: 'MQ=='},
                        beforeSubmit: function() {
                            //console.log('uploading image to server...');
                        },
                        success: function(data) {
                            if(data.Success == true) {
                                jQuery( ".company_info_steps" ).hide();
                                jQuery( "#"+step_id_base+next_step ).show();
                                
                                if( (step_id_base == "licensing_tab") && ((next_step == 3) || (next_step == 4)) ) {
                                    /*if((next_step == 3) && (jQuery("#"+step_id_base+next_step).find("iframe").length == 0)) {
										<?php //if(get_user_meta($user_id, 'sign_las3_docusign_form', true) != "Yes") { ?>
                                        jQuery('<div class="form_cont"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=79f03c34-9124-411d-b3f2-e6cbe2572240"/></div>').insertAfter(jQuery( "#"+step_id_base+next_step+" .line" ));
										<?php //} else { ?>
                                        jQuery('<div class="form_cont" style="display:none;"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=79f03c34-9124-411d-b3f2-e6cbe2572240"/></div>').insertAfter(jQuery( "#"+step_id_base+next_step+" .line" ));
										<?php //} ?>
                                    }
                                    if((next_step == 4) && (jQuery("#"+step_id_base+next_step).find("iframe").length == 0)) {
										<?php //if(get_user_meta($user_id, 'sign_las4_docusign_form', true) != "Yes") { ?>
                                        jQuery('<div class="form_cont"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=118625e6-cd32-46c6-aaa7-9268466c9293"/></div>').insertAfter(jQuery( "#"+step_id_base+next_step+" .line" ));
										<?php //} else { ?>
                                        jQuery('<div class="form_cont" style="display:none;"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=118625e6-cd32-46c6-aaa7-9268466c9293"/></div>').insertAfter(jQuery( "#"+step_id_base+next_step+" .line" ));
										<?php //} ?>
                                    }*/
                                }

								var loc = document.location.toString().split('#')[0];
								document.location = loc + '#'+step_id_base+next_step;
                            }
                        }
                    }).submit();
                } else {
                    alert("You can't leave required fields empty.");
                }
            });
            
            jQuery( ".btn_back_link[data-action=prev-step]" ).on( "click", function() {
                var step_id_base = jQuery(this).parents(".company_info_steps").attr("data-id-base");
                var curr_step = parseInt(jQuery(this).parents(".company_info_steps").attr("data-step"));
                var prev_step = curr_step-1;
                
                jQuery( ".company_info_steps" ).hide();
                jQuery( "#"+step_id_base+prev_step ).show();
				
				var loc = document.location.toString().split('#')[0];
				document.location = loc + '#'+step_id_base+prev_step;
            });

            jQuery( ".btn_next[data-action=next-tab]" ).on( "click", function() {
                var form_id = jQuery(this).closest("form").attr("id");
                var curr_tab = parseInt(jQuery(this).parents(".company_info_steps").attr("data-tab"));
                var next_tab = curr_tab+1;
                if( document.getElementById(form_id).checkValidity() ) {
					var curr_tab_id = jQuery( ".company_info_steps[data-tab="+curr_tab+"]" ).attr("id");
					if( jQuery(this).parents(".company_info_steps").attr("data-id-base") ) {
						var tab_id_base = jQuery(this).parents(".company_info_steps").attr("data-id-base");
						if(tab_id_base == "company_tab") {
							jQuery( "#"+tab_id_base+"4" ).find(".hidden-progress").val(100);
						} else if(tab_id_base == "licensing_tab") {
							jQuery( "#"+tab_id_base+"5" ).find(".hidden-progress").val(100);
						}
					} else {
						if( (jQuery(this).parents(".company_info_steps").attr("id") == "w9_form_tab") || (jQuery(this).parents(".company_info_steps").attr("id") == "travel_tab") || (jQuery(this).parents(".company_info_steps").attr("id") == "property_tab") || (jQuery(this).parents(".company_info_steps").attr("id") == "liability_tab") ) {
							/*if( jQuery( ".company_info_steps[data-tab="+curr_tab+"]" ).find(".docusign-form-done").val() == "Yes" ) {
								jQuery( ".company_info_steps[data-tab="+curr_tab+"]" ).find(".hidden-progress").val(100);
							} else {
								jQuery( ".company_info_steps[data-tab="+curr_tab+"]" ).find(".hidden-progress").val(0);
							}*/
							
							/*if( jQuery( ".company_info_steps[data-tab="+curr_tab+"]" ).find(".following_box").is(':checked') ) {
								jQuery( ".company_info_steps[data-tab="+curr_tab+"]" ).find(".hidden-progress").val(100);
							} else {*/
								jQuery( ".company_info_steps[data-tab="+curr_tab+"]" ).find(".hidden-progress").val(100);
							/*}*/
						} else {
							jQuery( ".company_info_steps[data-tab="+curr_tab+"]" ).find(".hidden-progress").val(100);
						}
					}
					
                    jQuery("#"+form_id).ajaxForm( {
                        dataType: 'json',
                        data: {ued: 'MQ=='},
                        beforeSubmit: function() {
                            //console.log('uploading image to server...');
                        },
                        success: function(data) {
                            if(data.Success == true) {
                                jQuery( ".company_info_steps" ).hide();
                                var ui_tab_id = jQuery( ".company_info_steps[data-tab="+next_tab+"]" ).attr("id");
                                jQuery( 'a[href="#'+ui_tab_id+'"]' ).click();
								//jQuery( ".company_info_steps[data-tab="+next_tab+"]" ).append('<img src="'+data.signature+'" /> <img src="'+data.signature2+'" />');
                            }
                        }
                    }).submit();
					
					if( jQuery(this).parents(".company_info_steps").attr("data-id-base") ) {
						var tab_id_base = jQuery(this).parents(".company_info_steps").attr("data-id-base");
						jQuery( 'a[href="#'+tab_id_base+'1"]' ).siblings(".item_complete").remove();
						jQuery( 'a[href="#'+tab_id_base+'1"]' ).parent("li").append('<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>');
						jQuery( 'a[href="#'+tab_id_base+'1"]' ).siblings(".tab-icon").hide();
					} else {
						if( (jQuery(this).parents(".company_info_steps").attr("id") == "w9_form_tab") || (jQuery(this).parents(".company_info_steps").attr("id") == "travel_tab") || (jQuery(this).parents(".company_info_steps").attr("id") == "property_tab") || (jQuery(this).parents(".company_info_steps").attr("id") == "liability_tab") ) {
							//if( jQuery(this).parents(".company_info_steps").find(".docusign-form-done").val() == "Yes" ) {
								jQuery( 'a[href="#'+curr_tab_id+'"]' ).siblings(".item_complete").remove();
								jQuery( 'a[href="#'+curr_tab_id+'"]' ).parent("li").append('<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>');
								jQuery( 'a[href="#'+curr_tab_id+'"]' ).siblings(".tab-icon").hide();
							//}
						} else {
							jQuery( 'a[href="#'+curr_tab_id+'"]' ).siblings(".item_complete").remove();
							jQuery( 'a[href="#'+curr_tab_id+'"]' ).parent("li").append('<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>');
							jQuery( 'a[href="#'+curr_tab_id+'"]' ).siblings(".tab-icon").hide();
						}
					}

					var tabs_length = jQuery("#company_info > ul li").length;
					var visible_tabs = tabs_length - 1; // -1 for payment tab
					var tab_count = 0;
					var completed_tabs = 0;
					jQuery( "#company_info > ul li" ).each(function() {
						if(tab_count < visible_tabs) {
							var tab_anchor_name = jQuery(this).find("a").attr("href");
							var tab_id = tab_anchor_name.substring(1);
							if(tab_id == "company_tab1") {
								var section_base = jQuery("#company_tab1").attr("data-id-base");
								var step_done = 0;
								for(var i=1; i<=4; i++) {
									if( jQuery("#"+section_base+i).find(".hidden-progress").val() == 100 ) {
										step_done++;
									}
								}
								if(step_done == 4) {
									completed_tabs++;
								}
							} else if(tab_id == "licensing_tab1") {
								var section_base = jQuery("#licensing_tab1").attr("data-id-base");
								var step_done = 0;
								for(var i=1; i<=5; i++) {
									if( jQuery("#"+section_base+i).find(".hidden-progress").val() == 100 ) {
										step_done++;
									}
								}
								if(step_done == 5) {
									completed_tabs++;
								}
							} else {
								if( jQuery("#"+tab_id).find(".hidden-progress").val() == 100 ) {
									completed_tabs++;
								}
							}
						}
						tab_count++;
					});
					
					var parcentage_count = 0;
					parcentage_count = (100 / visible_tabs) * completed_tabs;
					if((parcentage_count != 100) && (parcentage_count > 0)) {
						parcentage_count = parcentage_count.toFixed(1);
					}

					jQuery(".form_header .complete_parcentage").text(parcentage_count+"%");
					/*var form_header_width = jQuery("#signature_tab .form_header").outerWidth();
					var progress_bar_width = form_header_width - 100.6;*/
					var bar_width_percentage = (parcentage_count*progress_bar_width) / 100;
					if(parcentage_count == 0) {
						bar_width_percentage = 50;
					}
					jQuery(".form_header .complete_parcentage").css("max-width", bar_width_percentage+"px");
                } else {
                    alert("You can't leave required fields empty.");
                }
            });

            jQuery( ".btn_back_link[data-action=prev-tab]" ).on( "click", function() {
                var curr_tab = parseInt(jQuery(this).parents(".company_info_steps").attr("data-tab"));
                var prev_tab = curr_tab-1;
                jQuery( ".company_info_steps" ).hide();
                var ui_tab_id = jQuery( ".company_info_steps[data-tab="+prev_tab+"]" ).first().attr("id");
                if(ui_tab_id == "company_tab4") {
                    jQuery( 'a[href="#company_tab1"]' ).click();
                } else {
                    jQuery( 'a[href="#'+ui_tab_id+'"]' ).click();
                }
            });

            jQuery("#company_info ul li a").click(function() {
                var tab_name = jQuery(this).attr("href");
                var tab_name_id = tab_name.substring(1);
                if((tab_name_id != "company_tab1") && (tab_name_id != "licensing_tab1")) {
                    jQuery( ".company_info_steps[data-step]" ).hide();
					jQuery( "#app_submitted_tab" ).hide();
                } else {
					if(tab_name_id == "company_tab1") {
						jQuery( ".company_info_steps[data-id-base=licensing_tab]" ).hide();
					}
					if(tab_name_id == "licensing_tab1") {
						jQuery( ".company_info_steps[data-id-base=company_tab]" ).hide();
					}
				}
				
                /*if(tab_name_id == "payment_tab") {
                    jQuery(".form_header .complete_parcentage").css("opacity", 0);
                    jQuery(".form_header .progress").css("opacity", 0);
                } else {
                    jQuery(".form_header .complete_parcentage").css("opacity", 1);
                    jQuery(".form_header .progress").css("opacity", 1);
                }*/

                /*if((tab_name_id == "w9_form_tab") && (jQuery("#w9_form_tab").find("iframe").length == 0)) {
					<?php //if(get_user_meta($user_id, 'sign_w9_form_docusign_form', true) != "Yes") { ?>
                    jQuery('<div class="form_cont"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=816aa6a0-7089-48de-b0c7-e658cf8d0db0"/></div>').insertAfter(jQuery( "#w9_form_tab .line" ));
					<?php //} else { ?>
                    jQuery('<div class="form_cont" style="display:none;"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=816aa6a0-7089-48de-b0c7-e658cf8d0db0"/></div>').insertAfter(jQuery( "#w9_form_tab .line" ));
					<?php //} ?>
                }
                if((tab_name_id == "travel_tab") && (jQuery("#travel_tab").find("iframe").length == 0)) {
					<?php //if(get_user_meta($user_id, 'sign_travel_docusign_form', true) != "Yes") { ?>
                    jQuery('<div class="form_cont"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=3d775bb4-a62c-42b7-b3f8-eb88d3a3cf0b"/></div>').insertAfter(jQuery( "#travel_tab .line" ));
					<?php //} else { ?>
                    jQuery('<div class="form_cont" style="display:none;"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=3d775bb4-a62c-42b7-b3f8-eb88d3a3cf0b"/></div>').insertAfter(jQuery( "#travel_tab .line" ));
					<?php //} ?>
                }
                if((tab_name_id == "property_tab") && (jQuery("#property_tab").find("iframe").length == 0)) {
					<?php //if(get_user_meta($user_id, 'sign_property_docusign_form', true) != "Yes") { ?>
                    jQuery('<div class="form_cont"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=2381398d-31dc-4d99-9a60-b552223a2deb"/></div>').insertAfter(jQuery( "#property_tab .line" ));
					<?php //} else { ?>
                    jQuery('<div class="form_cont" style="display:none;"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=2381398d-31dc-4d99-9a60-b552223a2deb"/></div>').insertAfter(jQuery( "#property_tab .line" ));
					<?php //} ?>
                }
                if((tab_name_id == "liability_tab") && (jQuery("#liability_tab").find("iframe").length == 0)) {
					<?php //if(get_user_meta($user_id, 'sign_liability_docusign_form', true) != "Yes") { ?>
                    jQuery('<div class="form_cont"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=f5847265-bf63-4b29-b84c-9784bf4befea"/></div>').insertAfter(jQuery( "#liability_tab .line" ));
					<?php //} else { ?>
                    jQuery('<div class="form_cont" style="display:none;"><iframe src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=f5847265-bf63-4b29-b84c-9784bf4befea"/></div>').insertAfter(jQuery( "#liability_tab .line" ));
					<?php //} ?>
                }*/
            });
            
            jQuery("#licensing_tab1 .add-agency-affiliations").click(function() {
                var agency_aff = jQuery( "#licensing_tab1 #num_of_agency_aff" ).val();
                agency_aff++;
                jQuery('<div class="three_col"><input type="text" name="la_1_FEIN_'+agency_aff+'" id="la_1_FEIN_'+agency_aff+'" placeholder="FEIN" /></div><div class="three_col"><input type="text" name="la_1_NPN_'+agency_aff+'" id="la_1_NPN_'+agency_aff+'" placeholder="NPN" /></div><div class="three_col last"><input type="text" name="la_1_agency_name_'+agency_aff+'" id="la_1_agency_name_'+agency_aff+'" placeholder="Name of Agency" /></div><div class="clearfix"></div>').insertBefore( jQuery( "#licensing_tab1 .add-agency-affiliations" ) );
                jQuery( "#licensing_tab1 #num_of_agency_aff" ).val(agency_aff);
            });
            
            jQuery("#licensing_tab2 .add-agency-affiliations").click(function() {
                var agency_aff = jQuery( "#licensing_tab2 #num_of_agency_aff2" ).val();
                agency_aff++;
                jQuery('<div class="three_col"><input type="text" name="la_2_FEIN_'+agency_aff+'" id="la_2_FEIN_'+agency_aff+'" placeholder="FEIN" /></div><div class="three_col"><input type="text" name="la_2_NPN_'+agency_aff+'" id="la_2_NPN_'+agency_aff+'" placeholder="NPN" /></div><div class="three_col last"><input type="text" name="la_2_agency_name_'+agency_aff+'" id="la_2_agency_name_'+agency_aff+'" placeholder="Name of Agency" /></div><div class="clearfix"></div>').insertBefore( jQuery( "#licensing_tab2 .add-agency-affiliations" ) );
                jQuery( "#licensing_tab2 #num_of_agency_aff2" ).val(agency_aff);
            });

            jQuery("#licensing_tab1 .add-employment-experience").click(function() {
                var employment = jQuery( "#licensing_tab1 #num_of_employment" ).val();
                employment++;
                jQuery('<div class="three_col"><input type="text" name="la_1_employee_'+employment+'_name" id="la_1_employee_'+employment+'_name" placeholder="Name" /></div><div class="three_col"><input type="text" name="la_1_employee_'+employment+'_city" id="la_1_employee_'+employment+'_city" placeholder="City" /></div><div class="three_col last"><input type="text" name="la_1_employee_'+employment+'_state" id="la_1_employee_'+employment+'_state" placeholder="State" /></div><div class="clearfix"></div><div class="three_col"><input type="text" name="la_1_employee_'+employment+'_from_month" id="la_1_employee_'+employment+'_from_month" placeholder="From MM-YY" /></div><div class="three_col"><input type="text" name="la_1_employee_'+employment+'_to_month" id="la_1_employee_'+employment+'_to_month" placeholder="To MM-YY" /></div><div class="three_col last"><input type="text" name="la_1_employee_'+employment+'_position_held" id="la_1_employee_'+employment+'_position_held" placeholder="Position Held" /></div><div class="clearfix"></div>').insertBefore( jQuery( "#licensing_tab1 .add-employment-experience" ) );
                jQuery( "#licensing_tab1 #num_of_employment" ).val(employment);
            });

            jQuery("#licensing_tab2 .add-employment-experience").click(function() {
                var employment = jQuery( "#licensing_tab2 #num_of_employment2" ).val();
                employment++;
                jQuery('<div class="three_col"><input type="text" name="la_2_employee_'+employment+'_name" id="la_2_employee_'+employment+'_name" placeholder="Name" /></div><div class="three_col"><input type="text" name="la_2_employee_'+employment+'_city" id="la_2_employee_'+employment+'_city" placeholder="City" /></div><div class="three_col last"><input type="text" name="la_2_employee_'+employment+'_state" id="la_2_employee_'+employment+'_state" placeholder="State" /></div><div class="clearfix"></div><div class="three_col"><input type="text" name="la_2_employee_'+employment+'_foreign_country" id="la_2_employee_'+employment+'_foreign_country" placeholder="Foreign Country" /></div><div class="three_col"><input type="text" name="la_2_employee_'+employment+'_from_month" id="la_2_employee_'+employment+'_from_month" placeholder="From MM-YY" /></div><div class="three_col last"><input type="text" name="la_2_employee_'+employment+'_to_month" id="la_2_employee_'+employment+'_to_month" placeholder="To MM-YY" /></div><div class="clearfix"></div><div class="full_col"><input type="text" name="la_2_employee_'+employment+'_position_held" id="la_2_employee_'+employment+'_position_held" placeholder="Position Held" /></div>').insertBefore( jQuery( "#licensing_tab2 .add-employment-experience" ) );
                jQuery( "#licensing_tab2 #num_of_employment2" ).val(employment);
            });

            jQuery("#licensing_tab2 .add-identify-owners").click(function() {
                var owners = jQuery( "#licensing_tab2 #num_of_owners" ).val();
                owners++;
                jQuery('<div class="three_col"><input name="la_2_entity_'+owners+'_name" id="la_2_entity_'+owners+'_name" placeholder="Name" type="text" /></div><div class="three_col"><input name="la_2_entity_'+owners+'_title" id="la_2_entity_'+owners+'_title" placeholder="Title" type="text" /></div><div class="three_col last"><input name="la_2_entity_'+owners+'_ssn" id="la_2_entity_'+owners+'_ssn" placeholder="SSN/Fein " type="text" /></div><div class="clearfix"></div><div class="three_col"><label for="la_2_entity_'+owners+'_birth_day" class="label-control">Date of Birth</label><input type="text" name="la_2_entity_'+owners+'_birth_day" id="la_2_entity_'+owners+'_birth_day" placeholder="DD-MM-YY" /></div><div class="three_col"><label for="la_2_entity_'+owners+'_interest_rate" class="label-control">% of ownership interest</label><input name="la_2_entity_'+owners+'_interest_rate" id="la_2_entity_'+owners+'_interest_rate" placeholder="% of ownership interest" type="text" /></div><div class="three_col last">&nbsp;</div><div class="clearfix"></div>').insertBefore( jQuery( "#licensing_tab2 .add-identify-owners" ) );
                jQuery( "#licensing_tab2 #num_of_owners" ).val(owners);
            });
            
            jQuery( ".docusign-form-done" ).on( "change", function() {
				if( jQuery(this).val() == "Yes" ) {
					jQuery(this).parents(".company_info_steps").find(".hidden-progress").val(100);
					if( jQuery(this).siblings(".already-signed").length == 0 ) {
						jQuery('<p class="already-signed">This PDF has been completed and signed. Please Click "Next" to Continue & Save the PDF - Thank You.</p>').insertAfter( jQuery(this).siblings(".line") );
					}
					//if( jQuery(this).siblings(".form_cont").length ) {
					jQuery(this).siblings(".form_cont").hide();
					//}
				} else if( jQuery(this).val() == "No" ) {
					jQuery(this).parents(".company_info_steps").find(".hidden-progress").val(0);
					jQuery(this).siblings(".form_cont").show();
					if( jQuery(this).siblings(".already-signed").length ) {
						jQuery(this).siblings(".already-signed").remove();
					}
				}
			});

            jQuery( ".btn_next[data-action=submitted-tab]" ).on( "click", function() {
				if( jQuery("#invisible_captcha").val() != '' ) {
					// Bot submitted this form
					return false;
				} else {
					var curr_tab = parseInt(jQuery(this).parents(".company_info_steps").attr("data-tab"));
					var next_tab = curr_tab+1;
					var tabs_length = jQuery(".company_info_steps").length;
					var visible_tabs = tabs_length - 1; //-2 was previous, but now Payment Tab is also shown in Licensing Tab, so now it is -1
					var tab_count = 0;
					var all_complete = 0;
					jQuery( ".company_info_steps" ).each(function() {
						if(tab_count < visible_tabs) {
							if( jQuery(this).find(".hidden-progress").val() == 100 ) {
								all_complete++;
							}
						}
						tab_count++;
					});
					if( all_complete == visible_tabs ) {
						//if( jQuery(".payment-errors.alert-success").length || jQuery("#bill_me_later").is(':checked') ) {
							/*var bill_me_later = '';
							if( jQuery("#bill_me_later").is(':checked') ) {
								bill_me_later = jQuery("#bill_me_later").val();
							}*/
							var e_signature = jQuery("#e_signature").val();
							var signature_progress = jQuery("#signature_progress").val();
							//var pay_signature = jQuery("#pay_signature").val();
							if( e_signature != '' ) {
								jQuery.ajax({
									type : "post",
									dataType: "json",
									url : et_pb_custom.ajaxurl,
									//data : {action : 'send_onboard_app_email', bill_me_later : bill_me_later},
									data : {action : 'send_onboard_app_email', e_signature : e_signature, signature_progress : signature_progress},
									beforeSend: function() {
										//jQuery(".clinics_container").html('Loading...').show();
									}, 
									success: function(data) {
										if(data.success == true) {
											jQuery( ".company_info_steps" ).hide();
											jQuery( ".company_info_tab_wrap" ).hide();
											jQuery( ".company_info_steps[data-tab="+next_tab+"]" ).show();
										} else {
											alert("Something went wrong.");
										}
									}
								});
							} else {
								alert("Please enter your name.");
							}
						//}
					} else {
						alert("Please complete all the steps before submitting.");
					}
				}
            });

            /*jQuery( "#signature_tab" ).on( "keyup", "input", function() {
                var form_id = jQuery(this).closest("form").attr("id");
                var tab_name = jQuery(this).closest(".company_info_steps").attr("id");
                if( document.getElementById(form_id).checkValidity() ) {
                    jQuery(".form_header .complete_parcentage").text("100%");
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete_parcentage").hide();
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete").remove();
                    jQuery('<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>').insertAfter( jQuery('#company_info ul li a[href="#'+tab_name+'"]') );
					jQuery("#signature_progress").val("100");
                } else {
                    jQuery(".form_header .complete_parcentage").text("0%");
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete_parcentage").text("0%").show();
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete").remove();
					jQuery("#signature_progress").val("0");
                }
            });

            jQuery( "#schedule_tab" ).on( "keyup", "input, textarea", function() {
                var form_id = jQuery(this).closest("form").attr("id");
                var tab_name = jQuery(this).closest(".company_info_steps").attr("id");
                if( document.getElementById(form_id).checkValidity() ) {
                    jQuery(this).closest(".form_header").find(".complete_parcentage").text("100%");
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete_parcentage").hide();
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete").remove();
                    jQuery('<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>').insertAfter( jQuery('#company_info ul li a[href="#'+tab_name+'"]') );
                } else {
                    var cntreq = schedule_req_fields.length;
                    var cntvals = 0;
                    jQuery.each(schedule_req_fields, function( index, value ) {
						if(value == "standard_weekly_training") {
							// if radio button make sure if it's checked
							if(jQuery("#schedule_tab input[name=standard_weekly_training]").is(':checked')) {
								cntvals++;
							}
						} else {
							if(jQuery("#schedule_tab input[name="+value+"]").val() != '') {
								cntvals++;
							}
						}
                    });

                    var parcentage_count = ((cntvals/cntreq)*100).toFixed(1);
					if(parcentage_count == 100.0) {
						parcentage_count = 100;
					}

                    jQuery(this).closest(".form_header").find(".complete_parcentage").text(parcentage_count+"%");
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete_parcentage").text(parcentage_count+"%").show();
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete").remove();
                }
            });
            jQuery( "#schedule_tab" ).on( "change", "input[type=radio]", function() {
                var form_id = jQuery(this).closest("form").attr("id");
                var tab_name = jQuery(this).closest(".company_info_steps").attr("id");
                if( document.getElementById(form_id).checkValidity() ) {
                    jQuery(".form_header .complete_parcentage").text("100%");
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete_parcentage").hide();
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete").remove();
                    jQuery('<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>').insertAfter( jQuery('#company_info ul li a[href="#'+tab_name+'"]') );
                } else {
                    var cntreq = schedule_req_fields.length;
                    var cntvals = 0;
                    jQuery.each(schedule_req_fields, function( index, value ) {
						if(value == "standard_weekly_training") {
							// if radio button make sure if it's checked
							if(jQuery("#schedule_tab input[name=standard_weekly_training]").is(':checked')) {
								cntvals++;
							}
						} else {
							if(jQuery("#schedule_tab input[name="+value+"]").val() != '') {
								cntvals++;
							}
						}
                    });

                    var parcentage_count = ((cntvals/cntreq)*100).toFixed(1);

                    jQuery(".form_header .complete_parcentage").text(parcentage_count+"%");
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete_parcentage").text(parcentage_count+"%").show();
                    jQuery('#company_info ul li a[href="#'+tab_name+'"]').siblings(".item_complete").remove();
                }
            });*/
			
			/*var schedule_req_fields = new Array("standard_weekly_training", "product_training_date", "product_training_time", "product_training_timezone", "attend_1_name", "attend_1_email");*/

			if( jQuery(".company_info_steps").length > 0 ) {
				var tabs_length = jQuery("#company_info > ul li").length;
				var visible_tabs = tabs_length - 1; // -1 for payment tab
				var tab_count = 0;
				var completed_tabs = 0;
				jQuery( "#company_info > ul li" ).each(function() {
					jQuery(this).find("a").prepend('<span>'+(tab_count+1)+' | </span>');
					if(tab_count < visible_tabs) {
						var tab_anchor_name = jQuery(this).find("a").attr("href");
						var tab_id = tab_anchor_name.substring(1);
						if(tab_id == "company_tab1") {
							var section_base = jQuery("#company_tab1").attr("data-id-base");
							var step_done = 0;
							for(var i=1; i<=4; i++) {
								if( jQuery("#"+section_base+i).find(".hidden-progress").val() == 100 ) {
									step_done++;
								}
							}
							if(step_done == 4) {
								completed_tabs++;
							}
						} else if(tab_id == "licensing_tab1") {
							var section_base = jQuery("#licensing_tab1").attr("data-id-base");
							var step_done = 0;
							for(var i=1; i<=5; i++) {
								if( jQuery("#"+section_base+i).find(".hidden-progress").val() == 100 ) {
									step_done++;
								}
							}
							if(step_done == 5) {
								completed_tabs++;
							}
						} else {
							if( jQuery("#"+tab_id).find(".hidden-progress").val() == 100 ) {
								completed_tabs++;
							}
						}
					}
					tab_count++;
				});
				
				var parcentage_count = 0;
				parcentage_count = (100 / visible_tabs) * completed_tabs;
				if((parcentage_count != 100) && (parcentage_count > 0)) {
					parcentage_count = parcentage_count.toFixed(1);
				}
				jQuery(".form_header .complete_parcentage").text(parcentage_count+"%");
				var form_header_width = jQuery(".form_header").outerWidth();
				var progress_bar_width = form_header_width - 100.6; //100.6 is Sign Out buttons width
				var bar_width_percentage = (parcentage_count*progress_bar_width) / 100;
				if(parcentage_count == 0) {
					bar_width_percentage = 50;
				}
				jQuery(".form_header .complete_parcentage").css("max-width", bar_width_percentage+"px");
			}
			<?php
			/*if(get_user_meta($user_id, 'num_of_attendee', true)) {
				$num_of_attendee = get_user_meta($user_id, 'num_of_attendee', true);
				for($i=1; $i<=$num_of_attendee; $i++) {
				?>
				schedule_req_fields.push("attend_"+<?php echo $i; ?>+"_name", "attend_"+<?php echo $i; ?>+"_email");
				<?php
				}
			} else {
				?>
				schedule_req_fields.push("attend_1_name", "attend_1_email");
				<?php
			}*/
			?>
			var sig = jQuery('.sig-pad').signature();
			//sig.signature({syncField: '#sig_png'});
			jQuery('.sig-clear').click(function() {
				//jQuery('#sig').signature('isEmpty')
				jQuery(this).siblings('.sig-pad').signature('clear');
				/*jQuery('#sig_png').val('');*/
				jQuery(this).siblings('textarea').val('');
				jQuery(this).siblings('.sig-done').css({"background": "#F4AB10", "color": "#333333"});
			});
			jQuery('.sig-done').click(function() {
				//sig.signature('option', 'syncFormat', 'PNG');
				//sig.signature({syncField: '#sig_png', syncFormat: 'PNG'});
				if( jQuery(this).siblings('.sig-pad').signature('isEmpty') ) {
					alert("Please draw your signature");
				} else {
					//jQuery('#sig_png').val(sig.signature('toDataURL'));
					jQuery(this).siblings('textarea').val(jQuery(this).siblings('.sig-pad').signature('toDataURL'));
					jQuery(this).css({"background": "green", "color": "white"});
				}
			});
			
			jQuery( ".following_box" ).on( "click", function() {
				if( jQuery(this).is(':checked') ) {
					jQuery(this).parents("form").find("input[required]").attr("disabled", "disabled");
					jQuery(this).parents("form").find("textarea[required]").attr("disabled", "disabled");
					jQuery(this).parents("form").find("select[required]").attr("disabled", "disabled");
					jQuery(this).parents("form").find(".btn_next").click();
				} else {
					jQuery(this).parents("form").find("input[disabled]").removeAttr("disabled");
					jQuery(this).parents("form").find("textarea[disabled]").removeAttr("disabled");
					jQuery(this).parents("form").find("select[disabled]").removeAttr("disabled");
				}
			});

            jQuery( ".btn_back_link[data-action=save-pdf]" ).on( "click", function() {
                var form_id = jQuery(this).closest("form").attr("id");
                var curr_tab = parseInt(jQuery(this).parents(".company_info_steps").attr("data-tab"));
                //var next_tab = curr_tab+1;
                if( document.getElementById(form_id).checkValidity() ) {
					jQuery("#"+form_id).append('<input type="hidden" name="generate_pdf" value="PDF" />');
					var curr_tab_id = jQuery( ".company_info_steps[data-tab="+curr_tab+"]" ).attr("id");
                    jQuery("#"+form_id).ajaxForm( {
                        dataType: 'json',
                        data: {ued: 'MQ=='},
                        beforeSubmit: function() {
                            //console.log('uploading image to server...');
                        },
                        success: function(data) {
							if(data.Success == true) {
								jQuery("#"+form_id).find('input[name=generate_pdf]').remove();
							}
                            if(data.pdf_url) {
								//window.open(data.pdf_url);
								//console.log(data.pdf_url);
								jQuery("#"+form_id).find('.pdf_gen').attr("href", data.pdf_url);
								jQuery("#"+form_id).find('.pdf_gen').show();
								jQuery("#"+form_id).find('.pdf_gen').css("display", "block");
                            }
							if(data.Success == true) {
								//jQuery("#"+form_id).find('#pdf_gen').remove();
                            }
                        }
                    }).submit();
				} else {
                    alert("You can't leave required fields empty.");
                }
        	});
			
			jQuery("#administrative_department1").click(function() {
				if(jQuery(this).prop("checked") == true ) {
					jQuery(this).parents(".one_half").append('<div class="email-name-wrap"><input name="admin_dept_name" placeholder="Name" type="text"><input name="admin_dept_email" placeholder="Email" type="text"></div>');
				} 
			});
			jQuery("#administrative_department2").click(function() {
				if(jQuery(this).prop("checked") == true ) {
					jQuery(this).parents(".one_half").find(".email-name-wrap").remove();
				} 
			});
			jQuery("#technology_department1").click(function() {
				if(jQuery(this).prop("checked") == true ) {
					jQuery(this).parents(".one_half").append('<div class="email-name-wrap"><input name="tech_dept_name" placeholder="Name" type="text"><input name="tech_dept_email" placeholder="Email" type="text"></div>');
				} 
			});
			jQuery("#technology_department2").click(function() {
				if(jQuery(this).prop("checked") == true ) {
					jQuery(this).parents(".one_half").find(".email-name-wrap").remove();
				} 
			});
			jQuery("#accounting_department1").click(function() {
				if(jQuery(this).prop("checked") == true ) {
					jQuery(this).parents(".full_col").append('<div class="email-name-wrap"><input name="accounting_dept_name" placeholder="Name" type="text"><input name="accounting_dept_email" placeholder="Email" type="text"></div>');
				} 
			});
			jQuery("#accounting_department2").click(function() {
				if(jQuery(this).prop("checked") == true ) {
					jQuery(this).parents(".full_col").find(".email-name-wrap").remove();
				} 
			});

			jQuery(".sig-save").click(function() {
				if(jQuery("#e_signature").val() != "") {
					jQuery("#signature-form").find(".hidden-progress").val(100);
					jQuery("#signature-form").ajaxForm( {
						dataType: 'json',
						data: {ued: 'MQ=='},
						beforeSubmit: function() {
							//console.log('uploading image to server...');
						},
						success: function(data) {
							if(data.Success == true) {
								jQuery(".sig-save").css({"background": "green", "color": "white"});
							}
						}
					}).submit();
				}
			});
			<?php if( isset($_GET['show']) && ($_GET['show'] == "reginfo") ) { ?>
				jQuery('<p style="text-align: center;">If you\'re returning, <a href="/login/">Log In</a> here</p>').insertBefore(jQuery("#registerform"));
			<?php } ?>
        });
    </script>
<?php }


add_shortcode('display-company-info', 'get_company_information_shortcode');
function get_company_information_shortcode(){
	ob_start();
	if( !is_user_logged_in() ) {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			window.location = "<?php echo get_site_url().'/user-register/?show=reginfo'; ?>";
		});
	</script>
	<?php
	} else {
		$user_id = get_current_user_id();
		$show_company_info = get_user_meta( $user_id, 'cmb_user_company_info', true );
		$show_product_info = get_user_meta( $user_id, 'cmb_user_product_info', true );
		$show_w9_form = get_user_meta( $user_id, 'cmb_user_w9_form', true );
		$show_travel_protection = get_user_meta( $user_id, 'cmb_user_travel_protection', true );
		$show_property_protection = get_user_meta( $user_id, 'cmb_user_property_protection', true );
		$show_liability_protection = get_user_meta( $user_id, 'cmb_user_liability_protection', true );
		$show_schedule_training = get_user_meta( $user_id, 'cmb_user_schedule_training', true );
		$show_licensing_appointments = get_user_meta( $user_id, 'cmb_user_licensing_appointments', true );
	?>
    	<div id="company_info" class="company_info">
        	<?php //if( get_user_meta($user_id, 'onboard_application_submitted', true) != "Yes" ) { ?>
        	  <ul class="company_info_tab_wrap">
              	<?php if($show_company_info != "No") { ?>
                <li><a href="#company_tab">Company</a><!--#company_tab1-->
                	<?php
					if(get_user_meta($user_id, 'company_info_step_4_progress', true) == 100) {
						echo '<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>';
					} else {
						echo '<span class="tab-icon"><i class="fa fa-building-o" aria-hidden="true"></i></span>';
					}
					?>
                </li>
                <?php } ?>
              	<?php if($show_product_info != "No") { ?>
                <li><a href="#product_tab">Product</a>
                	<?php
					if(get_user_meta($user_id, 'product_info_progress', true)) {
						if(get_user_meta($user_id, 'product_info_progress', true) == 100) {
							echo '<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>';
						}
					} else {
						echo '<span class="tab-icon"><i class="fa fa-tasks" aria-hidden="true"></i></span>';
					}
					?>
                </li>
                <?php } ?>
              	<?php if($show_w9_form != "No") { ?>
                <li><a href="#w9_form_tab">W9 Form</a>
                	<?php
					if(get_user_meta($user_id, 'w9_form_progress', true)) {
						if(get_user_meta($user_id, 'w9_form_progress', true) == 100) {
							echo '<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>';
						}
					} else {
						echo '<span class="tab-icon"><i class="fa fa-files-o" aria-hidden="true"></i></span>';
					}
					?>
                </li>
                <?php } ?>
              	<?php if($show_travel_protection != "No") { ?>
                <li><a href="#travel_tab">Travel</a>
                	<?php
					if(get_user_meta($user_id, 'travel_protection_progress', true)) {
						if(get_user_meta($user_id, 'travel_protection_progress', true) == 100) {
							echo '<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>';
						}
					} else {
						echo '<span class="tab-icon"><i class="fa fa-plane" aria-hidden="true"></i></span>';
					}
					?>
                </li>
                <?php } ?>
              	<?php if($show_property_protection != "No") { ?>
                <li><a href="#property_tab">Property</a>
                	<?php
					if(get_user_meta($user_id, 'property_protection_progress', true)) {
						if(get_user_meta($user_id, 'property_protection_progress', true) == 100) {
							echo '<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>';
						}
					} else {
						echo '<span class="tab-icon"><i class="fa fa-home" aria-hidden="true"></i></span>';
					}
					?>
                </li>
                <?php } ?>
              	<?php if($show_liability_protection != "No") { ?>
                <li><a href="#liability_tab">Liability</a>
                	<?php
					if(get_user_meta($user_id, 'liability_protection_progress', true)) {
						if(get_user_meta($user_id, 'liability_protection_progress', true) == 100) {
							echo '<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>';
						}
					} else {
						echo '<span class="tab-icon"><i class="fa fa-gavel" aria-hidden="true"></i></span>';
					}
					?>
                </li>
                <?php } ?>
              	<?php if($show_schedule_training != "No") { ?>
                <li><a href="#schedule_tab">Schedule</a>
                	<?php
					if(get_user_meta($user_id, 'schedule_training_progress', true)) {
						if(get_user_meta($user_id, 'schedule_training_progress', true) == 100) {
							echo '<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>';
						}
					} else {
						echo '<span class="tab-icon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>';
					}
					?>
                </li>
                <?php } ?>
              	<?php if($show_licensing_appointments != "No") { ?>
                <li><a href="#licensing_tab1">Licensing</a>
                	<?php
					if((get_user_meta($user_id, 'licensing_appointments_step3_progress', true) == 100) && (get_user_meta($user_id, 'licensing_appointments_step4_progress', true) == 100) && (get_user_meta($user_id, 'licensing_appointments_step5_progress', true) == 100)) {
						echo '<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>';
					} else {
						echo '<span class="tab-icon" style="position: relative; top: 1px;"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>';
					}
					?>
                </li>
                <?php } ?>
                <li><a href="#signature_tab">Signature</a>
                	<?php
					if(get_user_meta($user_id, 'signature_progress', true)) {
						if(get_user_meta($user_id, 'signature_progress', true) == 100) {
							echo '<span class="item_complete"><i class="fa fa-check" aria-hidden="true"></i></span>';
						}
					} else {
						echo '<span class="tab-icon"><i class="fa fa-pencil" aria-hidden="true"></i></span>';
					}
					?>
                </li>
                <li style="display:none;"><a href="#payment_tab">Payment</a></li>
              </ul><!--company_info_tab_wrap-->
              
              
              
              
            <div id="company_info_form" class="company_info_form">
                <?php if($show_company_info != "No") { ?>
                <div class="company_info_steps company_info_step_1" id="company_tab" data-tab="1"><!-- #company_tab1 -->
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="company-info-step-1" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Company Info<!-- <span class="step_active">1</span> of 4--></h1>
                        <p>We are very excited to work with you and your team.  Our onboarding form makes it simple to complete the onboarding process in one place.  Once you complete the steps, it will be reviewed and we can then usually have you live within 3 to 4 business days.  Let us know at <a href="mailto:onboarding@rentalguardian.com">onboarding@rentalguardian.com</a> if you have any questions along the way.  We are happy to help! </p>
                        <div class="line"></div>
                        <div class="three_col">
                            <label for="company_name" class="label-control require">Company Name/DBA</label>
                            <input type="text" name="company_name" id="company_name" placeholder="Company Name/DBA" value="<?php echo get_user_meta($user_id, 'company_name', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="appling" class="label-control require">Applying</label>
                            <select name="appling" id="appling">
                                <option value="">Please Select</option>
                                <option value="Corporation" <?php if(get_user_meta($user_id, 'appling', true) == "Corporation") echo 'selected="selected"'; ?>>Corporation</option>
                                <option value="Partnership / LLP" <?php if(get_user_meta($user_id, 'appling', true) == "Partnership / LLP") echo 'selected="selected"'; ?>>Partnership / LLP</option>
                                <option value="LLC" <?php if(get_user_meta($user_id, 'appling', true) == "LLC") echo 'selected="selected"'; ?>>LLC</option>
                                <option value="Sole Proprietor" <?php if(get_user_meta($user_id, 'appling', true) == "Sole Proprietor") echo 'selected="selected"'; ?>>Sole Proprietor</option>
                                <option value="Individual" <?php if(get_user_meta($user_id, 'appling', true) == "Individual") echo 'selected="selected"'; ?>>Individual</option>
                            </select>
    
                        </div>
                        <div class="three_col last">
                            <label for="reseller_id" class="label-control">Tax/Reseller Id</label>
                            <input type="text" name="reseller_id" id="reseller_id" placeholder="99-87674321" value="<?php echo get_user_meta($user_id, 'reseller_id', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="three_col">
                            <label for="company_type" class="label-control require">Type of Company</label>
                            <select name="company_type" id="company_type">
                                <option value="">Please Select</option>
                                <option value="Property Manager" <?php if(get_user_meta($user_id, 'company_type', true) == "Property Manager") echo 'selected="selected"'; ?>>Property Manager</option>
                                <option value="Marketing Company / RPM" <?php if(get_user_meta($user_id, 'company_type', true) == "Marketing Company / RPM") echo 'selected="selected"'; ?>>Marketing Company / RPM</option>
                                <option value="Portal / Index" <?php if(get_user_meta($user_id, 'company_type', true) == "Portal / Index") echo 'selected="selected"'; ?>>Portal / Index</option>
                                <option value="Software Provider" <?php if(get_user_meta($user_id, 'company_type', true) == "Software Provider") echo 'selected="selected"'; ?>>Software Provider</option>
                                <option value="OTHER TYPE" <?php if(get_user_meta($user_id, 'company_type', true) == "OTHER TYPE") echo 'selected="selected"'; ?>>OTHER TYPE</option>
                            </select>
                        </div>
                        <div class="three_col">
                            <label for="years_in_business" class="label-control require">Years in Business</label>
                            <input type="text" name="years_in_business" id="years_in_business" value="<?php echo get_user_meta($user_id, 'years_in_business', true); ?>" />
                        </div>
                        <div class="three_col last">
                            <label for="preferred_payment_method" class="label-control require">Preferred Method of Payment</label>
                            <select name="preferred_payment_method" id="preferred_payment_method">
                                <option value="">Please Select</option>
                                <option value="ACH" <?php if(get_user_meta($user_id, 'preferred_payment_method', true) == "ACH") echo 'selected="selected"'; ?>>ACH</option>
                                <option value="Wire, Check" <?php if(get_user_meta($user_id, 'preferred_payment_method', true) == "Wire, Check") echo 'selected="selected"'; ?>>Wire, Check</option>
                                <option value="Credit Card - Per Transaction" <?php if(get_user_meta($user_id, 'preferred_payment_method', true) == "Credit Card - Per Transaction") echo 'selected="selected"'; ?>>Credit Card - Per Transaction</option>
                                <option value="Credit Card - Per Month" <?php if(get_user_meta($user_id, 'preferred_payment_method', true) == "Credit Card - Per Month") echo 'selected="selected"'; ?>>Credit Card - Per Month</option>
                            </select>
                        </div>
                        <div class="clearfix"></div>

                        <div class="full_col">
                            <label for="street_address" class="label-control require">Street Address</label>
                            <input type="text" name="street_address" id="street_address" value="<?php echo get_user_meta($user_id, 'street_address', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="street_address_line_2" class="label-control">Street Address Line 2</label>
                            <input type="text" name="street_address_line_2" id="street_address_line_2" value="<?php echo get_user_meta($user_id, 'street_address_line_2', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="city" class="label-control require">City</label>
                            <input type="text" name="city" id="city" value="<?php echo get_user_meta($user_id, 'city', true); ?>" />
                        </div>
                        <div class="three_col last">
                            <label for="state_provinc" class="label-control require">State / Province</label>
                            <input type="text" name="state_provinc" id="state_provinc" value="<?php echo get_user_meta($user_id, 'state_province', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="three_col">
                            <label for="postal_code" class="label-control require">Postal / Zip Code</label>
                            <input type="text" name="postal_code" id="postal_code" value="<?php echo get_user_meta($user_id, 'co_postal_code', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="country" class="label-control require">Country</label>
                            <select name="country" id="country">
                                <!--<option value="">Please Select</option>
                                <option value="canada" <?php //if(get_user_meta($user_id, 'co_country', true) == "canada") echo 'selected="selected"'; ?>>Canada</option>
                                <option value="usa" <?php //if(get_user_meta($user_id, 'co_country', true) == "usa") echo 'selected="selected"'; ?>>USA</option>-->
								<?php
                                $countries = array("United States", "Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
                                foreach($countries as $country) {
                                    if(get_user_meta($user_id, 'co_country', true) == $country) { $selected = 'selected="selected"'; }
                                    else { $selected = ''; }
                                    echo '<option value="'.$country.'" '.$selected.'>'.$country.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="three_col last">
                        </div>
                        <div class="clearfix"></div>

                        <div class="two_third">
                            <label for="street_address" class="label-control require">Legal / Authorized Representatives Name</label>
                            <!--<input type="text" name="name_prefix" class="name_prefix" id="name_prefix" placeholder="Prefix" value="<?php //echo get_user_meta($user_id, 'name_prefix', true); ?>" />-->
                            <input type="text" name="first_name" class="first_name" id="first_name" placeholder="First Name" value="<?php echo get_user_meta($user_id, 'representatives_first_name', true); ?>" />
                            <input type="text" name="last_name" class="last_name" id="last_name" placeholder="Last Name" value="<?php echo get_user_meta($user_id, 'representatives_last_name', true); ?>" />
                        </div>
                        <div class="one_third et_column_last">
                            <label for="job_position" class="label-control">Job Position / Title</label>
                            <input type="text" name="job_position" id="job_position" placeholder="EX: Owner / CEO / Partner" value="<?php echo get_user_meta($user_id, 'job_position', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="three_col">
                            <label for="applicant_email" class="label-control require">Applicant E-mail</label>
                            <input type="email" name="applicant_email" id="applicant_email" placeholder="EX: myemail@myemail.com" value="<?php echo get_user_meta($user_id, 'applicant_email', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="phone_number" class="label-control require">Phone Number </label>
                            <input type="tel" name="phone_number" id="phone_number" placeholder="1-888-888-8888 EXT. 77" value="<?php echo get_user_meta($user_id, 'applicant_phone_number', true); ?>" />
                        </div>
                        <div class="three_col last">
                        </div>
                        <div class="clearfix"></div>
                        <div class="radio_group clearfix">
                            <div class="one_half">
                                <label for="administrative_department" class="label-control require">Do you have an Administrative Department</label>
                                <label for="administrative_department1" class="radio-control"><input type="radio" value="Yes" id="administrative_department1" name="administrative_department" <?php if(get_user_meta($user_id, 'administrative_department', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="administrative_department2" class="radio-control"><input type="radio" value="No" id="administrative_department2" name="administrative_department" <?php if(get_user_meta($user_id, 'administrative_department', true) == "No") echo 'checked="checked"'; ?> />No</label>
                                <?php if(get_user_meta($user_id, 'administrative_department', true) == "Yes") { ?>
                                <div class="email-name-wrap">
                                	<input name="admin_dept_name" placeholder="Name" type="text" value="<?php echo get_user_meta($user_id, 'admin_dept_name', true); ?>">
                                    <input name="admin_dept_email" placeholder="Email" type="text" value="<?php echo get_user_meta($user_id, 'admin_dept_email', true); ?>">
                                </div>
                                <?php } ?>
                            </div><!--one_half-->
                            <div class="one_half et_column_last">
                                <label for="technology_department" class="label-control require">Do you have an IT / Technology Department</label>
                                <label for="technology_department1" class="radio-control"><input type="radio" value="Yes" id="technology_department1" name="technology_department" <?php if(get_user_meta($user_id, 'technology_department', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="technology_department2" class="radio-control"><input type="radio" value="No" id="technology_department2" name="technology_department" <?php if(get_user_meta($user_id, 'technology_department', true) == "No") echo 'checked="checked"'; ?> />No</label>
                                <?php if(get_user_meta($user_id, 'technology_department', true) == "Yes") { ?>
                                <div class="email-name-wrap">
                                	<input name="admin_dept_name" placeholder="Name" type="text" value="<?php echo get_user_meta($user_id, 'tech_dept_name', true); ?>">
                                    <input name="admin_dept_email" placeholder="Email" type="text" value="<?php echo get_user_meta($user_id, 'tech_dept_email', true); ?>">
                                </div>
                                <?php } ?>
                            </div><!--one_half-->
                        </div><!--radio_group clearfix-->
                        
                        
                        <div class="full_col radio_group">
                            <label for="accounting_department" class="label-control require">Do you have an Accountant/Accounting Department</label>
                            <label for="accounting_department1" class="radio-control"><input type="radio" value="Yes" id="accounting_department1" name="accounting_department" <?php if(get_user_meta($user_id, 'accounting_department', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="accounting_department2" class="radio-control"><input type="radio" value="No" id="accounting_department2" name="accounting_department" <?php if(get_user_meta($user_id, 'accounting_department', true) == "No") echo 'checked="checked"'; ?> />No</label>
							<?php if(get_user_meta($user_id, 'accounting_department', true) == "Yes") { ?>
                            <div class="email-name-wrap">
                                <input name="admin_dept_name" placeholder="Name" type="text" value="<?php echo get_user_meta($user_id, 'accounting_dept_name', true); ?>">
                                <input name="admin_dept_email" placeholder="Email" type="text" value="<?php echo get_user_meta($user_id, 'accounting_dept_email', true); ?>">
                            </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>

                        <div class="three_col">
                            <label for="company_website_address" class="label-control require">Company Website Address/URL</label>
                            <input type="text" name="company_website_address" id="company_website_address" placeholder="EX: www.yourcompany.com" value="<?php echo get_user_meta($user_id, 'company_website_address', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="company_email" class="label-control">Company Email Address</label>
                            <input type="email" name="company_email" id="company_email"  placeholder="EX: name@yourcompany.com" value="<?php echo get_user_meta($user_id, 'company_email', true); ?>" />
                        </div>
                        <!--<div class="three_col last">
                            <label for="area_code" class="label-control require">Main Phone Number Area Code</label>
                            <input type="text" name="area_code" id="area_code" placeholder="Area Code" value="<?php //echo get_user_meta($user_id, 'main_phone_area_code', true); ?>" />
                        </div>-->
                        <div class="three_col last">
                            <label for="main_phone_number" class="label-control require">Main Phone Number</label>
                            <input type="tel" name="main_phone_number" id="main_phone_number" placeholder="Phone Number" value="<?php echo get_user_meta($user_id, 'main_phone_number', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <!--<div class="clearfix"></div>-->
                        <div class="radio_group">
                            <label for="properties_located" class="label-control require">Do you have Properties located in the U.S.?</label>
                            <label for="properties_located1" class="radio-control"><input type="radio" id="properties_located1" name="properties_located" value="Yes" <?php if(get_user_meta($user_id, 'properties_located_usa', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="properties_located2" class="radio-control"><input type="radio" id="properties_located2" name="properties_located" value="No" <?php if(get_user_meta($user_id, 'properties_located_usa', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div><!--radio_group-->
                        
                        <div class="radio_group">
                            <label for="properties_located_internationally" class="label-control require">Do you have Properties located Internationally? </label>
                            <label for="properties_located_internationally1" class="radio-control"><input type="radio" id="properties_located_internationally1" name="properties_located_internationally" value="Yes" <?php if(get_user_meta($user_id, 'properties_located_internationally', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="properties_located_internationally2" class="radio-control"><input type="radio" id="properties_located_internationally2" name="properties_located_internationally" value="No" <?php if(get_user_meta($user_id, 'properties_located_internationally', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div><!--radio_group-->
                        
                        
                        <div class="two_third">
                            <label for="management_software_system" class="label-control require">Current Management Software System</label>
                            <select name="management_software_system" id="management_software_system">
                                <option value="">Please Select</option>
                                <option value="Aaxsys" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Aaxsys") echo 'selected="selected"'; ?>>Aaxsys</option>
                                <option value="Barefoot" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Barefoot") echo 'selected="selected"'; ?>>Barefoot</option>
                                <option value="Bookerville" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Bookerville") echo 'selected="selected"'; ?>>Bookerville</option>
                                <option value="Ciirus" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Ciirus") echo 'selected="selected"'; ?>>Ciirus</option>
                                <option value="Escapia" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Escapia") echo 'selected="selected"'; ?>>Escapia</option>
                                <option value="Instant Software" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Instant Software") echo 'selected="selected"'; ?>>Instant Software</option>
                                <option value="LIS" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "LIS") echo 'selected="selected"'; ?>>LIS</option>
                                <option value="LiveRez" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "LiveRez") echo 'selected="selected"'; ?>>LiveRez</option>
                                <option value="Lodgix" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Lodgix") echo 'selected="selected"'; ?>>Lodgix</option>
                                <option value="Masston US" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Masston US") echo 'selected="selected"'; ?>>Masston US</option>
                                <option value="Software Answers" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Software Answers") echo 'selected="selected"'; ?>>Software Answers</option>
                                <option value="Perfect Places" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Perfect Places") echo 'selected="selected"'; ?>>Perfect Places</option>
                                <option value="Real Time Rentals" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Real Time Rentals") echo 'selected="selected"'; ?>>Real Time Rentals</option>
                                <option value="Kigo/Instamanager" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Kigo/Instamanager") echo 'selected="selected"'; ?>>Kigo/Instamanager</option>
                                <option value="Rental Network Software" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Rental Network Software") echo 'selected="selected"'; ?>>Rental Network Software</option>
                                <option value="Reservation Software" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Reservation Software") echo 'selected="selected"'; ?>>Reservation Software</option>
                                <option value="RMS" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "RMS") echo 'selected="selected"'; ?>>RMS</option>
                                <option value="SkyRun Vacation Rentals" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "SkyRun Vacation Rentals") echo 'selected="selected"'; ?>>SkyRun Vacation Rentals</option>
                                <option value="Streamline" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Streamline") echo 'selected="selected"'; ?>>Streamline</option>
                                <option value="Vacation Rent Pro" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Vacation Rent Pro") echo 'selected="selected"'; ?>>Vacation Rent Pro</option>
                                <option value="Vested Travel" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Vested Travel") echo 'selected="selected"'; ?>>Vested Travel</option>
                                <option value="VREasy" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "VREasy") echo 'selected="selected"'; ?>>VREasy</option>
                                <option value="VRM" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "VRM") echo 'selected="selected"'; ?>>VRM</option>
                                <option value="Web Chalet" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "Web Chalet") echo 'selected="selected"'; ?>>Web Chalet</option>
                                <option value="World Escape" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "World Escape") echo 'selected="selected"'; ?>>World Escape</option>
                                <option value="CUSTOM" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "CUSTOM") echo 'selected="selected"'; ?>>CUSTOM</option>
                                <option value="NONE" <?php if(get_user_meta($user_id, 'content_management_software_system', true) == "NONE") echo 'selected="selected"'; ?>>NONE</option>
                            </select>
                        </div>
                        <div class="one_third et_colmun_last">
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <div class="two_third" style="line-height:120%;">
                            <label for="company_information_notes" class="label-control">Company Information Notes / Comments</label>
                            <textarea style="margin-bottom:0px;" rows="2" name="company_information_notes" id="company_information_notes"><?php echo get_user_meta($user_id, 'company_information_notes', true); ?></textarea>
                            <span class="discription" style="padding-bottom:20px;">Please provide any additional information that may assist us in processing your onboarding application.</span>
                        </div>
                        <div class="one_third et_colmun_last">
                        </div>
                        <div class="clearfix"></div>
                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="no-back">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="company_info" />
                        <input type="hidden" name="company_info_progress" id="company_info_progress" value="<?php echo get_user_meta($user_id, 'company_info_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_1-->
                <?php } ?>
                
                
                <?php if($show_product_info != "No") { ?>
                <div class="company_info_steps company_info_step_5" id="product_tab" data-tab="2">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="product-info" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Requested Coverage Information</h1>
                        <p>Please select the desired type of coverage and specific coverage limits for the program you are requesting approval for.</p>
                        <div class="line"></div>
                        
                        <div class="two_third">
                            <div class="radio_group">
                                <label for="geographical_coverage" class="label-control require">Desired Geographical Coverage (U.S. Domestic/International) </label>
                                <label for="geographical_coverage1" class="radio-control"><input type="radio" id="geographical_coverage1" name="geographical_coverage" value="U.S. Domestic" <?php if(get_user_meta($user_id, 'geographical_coverage', true) == "U.S. Domestic") echo 'checked="checked"'; ?> />U.S. Domestic</label>
                                <label for="geographical_coverage2" class="radio-control"><input type="radio" id="geographical_coverage2" name="geographical_coverage" value="International" <?php if(get_user_meta($user_id, 'geographical_coverage', true) == "International") echo 'checked="checked"'; ?> />International</label>
                                <label for="geographical_coverage3" class="radio-control"><input type="radio" id="geographical_coverage3" name="geographical_coverage" value="Both" <?php if(get_user_meta($user_id, 'geographical_coverage', true) == "Both") echo 'checked="checked"'; ?> />Both</label>
                            </div><!--radio_group-->
                        </div>
                        
                        <div class="one_third et_column_last">
                            <div class="radio_group">
                                <label for="travel_protection " class="label-control require">Travel Protection</label>
                                <label for="travel_protection_yes" class="radio-control"><input type="radio" id="travel_protection_yes" name="travel_protection" value="Yes" <?php if(get_user_meta($user_id, 'travel_protection', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="travel_protection_no" class="radio-control"><input type="radio" id="travel_protection_no" name="travel_protection" value="No" <?php if(get_user_meta($user_id, 'travel_protection', true) == "No") echo 'checked="checked"'; ?> />No</label>
                            </div>
                        </div><!--one_third-->
                        <div class="clearfix"></div>
                        
                        <div class="two_third">
                            <div class="radio_group">
                                <label for="property_damage_protection" class="label-control require">Property Protection / Damage Protection</label>
                                <label for="property_damage_protection_yes" class="radio-control"><input type="radio" id="property_damage_protection_yes" name="property_damage_protection" value="Yes" <?php if(get_user_meta($user_id, 'property_damage_protection', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="property_damage_protection_no" class="radio-control"><input type="radio" id="property_damage_protection_no" name="property_damage_protection" value="No" <?php if(get_user_meta($user_id, 'property_damage_protection', true) == "No") echo 'checked="checked"'; ?> />No</label>
                            </div>
                        </div><!--two_third-->
                        
                        <div class="one_third et_column_last">
                            <div class="radio_group">
                                <label for="liability_property_protection" class="label-control require">Liability & Real Property Protection</label>
                                <label for="liability_property_protection_yes" class="radio-control"><input type="radio" id="liability_property_protection_yes" name="liability_property_protection" value="Yes" <?php if(get_user_meta($user_id, 'liability_property_protection', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="liability_property_protection_no" class="radio-control"><input type="radio" id="liability_property_protection_no" name="liability_property_protection" value="No" <?php if(get_user_meta($user_id, 'liability_property_protection', true) == "No") echo 'checked="checked"'; ?> />No</label>
                            </div>
                        </div><!--one_third-->
                        <div class="clearfix"></div>
                        
                        <div class="full_col"><label class="label-control require">Who will oversee claims within your team?</label></div>
                        <div class="three_col">
                            <input type="text" name="team_name" id="team_name" placeholder="Name" value="<?php echo get_user_meta($user_id, 'oversee_claims_team_name', true); ?>" />
                        </div>
                        <div class="three_col">
                            <input type="email" name="team_email" id="team_email" placeholder="Email" value="<?php echo get_user_meta($user_id, 'oversee_claims_team_email', true); ?>" />
                        </div>                       
                        <div class="three_col last">
                            <input type="tel" name="team_phone" id="team_phone" placeholder="Phone" value="<?php echo get_user_meta($user_id, 'oversee_claims_team_phone', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col">
                            <label for="additional_comments" class="label-control">Additional Comments</label>
                            <textarea rows="3" name="additional_comments" id="additional_comments"><?php echo get_user_meta($user_id, 'additional_comments', true); ?></textarea>
                        </div>
                        
                        
                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-tab">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="product-info" />
                        <input type="hidden" name="product_info_progress" id="product_info_progress" value="<?php echo get_user_meta($user_id, 'product_info_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_5-->
                <?php } ?>


                <?php if($show_w9_form != "No") { ?>
                <div class="company_info_steps company_info_step_9" id="w9_form_tab" data-tab="3">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="w9_form" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">W-9 Form</h1>
                        <div class="line"></div>
                         
                        <div class="radio_group">
                            <label for="w9_following_box" class="radio-control"><strong style="color:#3297DB;">If you are Non US incorporated entity, you will not be required to complete this section.  Simply check the box to the right:</strong> <input type="checkbox" class="following_box" name="w9_following_box" value="If you are Non US incorporated entity, you will not be required to complete this section.  Simply check the box to the right:" <?php if(get_user_meta($user_id, 'skip_w9_form', true) == "If you are Non US incorporated entity, you will not be required to complete this section.  Simply check the box to the right:") echo 'checked="checked"'; ?> /></label>
                        </div>
                        
                        <div class="two_third">
                        	<label for="w9_income_tax" class="label-control require">Provide your name as shown in your income tax return:</label>
                       	    <input type="text" name="w9_income_tax" id="w9_income_tax" value="<?php echo get_user_meta($user_id, 'w9_income_tax', true); ?>" />
                        </div>
                        <div class="one_third et_column_last">
                        	<label for="w9_business_name" class="label-control require">Your business name:</label>
                       	    <input type="text" name="w9_business_name" id="w9_business_name" value="<?php echo get_user_meta($user_id, 'w9_business_name', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="radio_group">
                        	<h5><strong>Check the appropriate box for federal tax classification: <!--<sup style="bottom: .2em;"><span style="color:#ED4545;">*</span></sup>--></strong></h5>
                            <?php
								$w9_federal_tax = get_user_meta($user_id, 'w9_federal_tax', true);
								if($w9_federal_tax) {
									$w9_checked_federal_taxes = explode(",", $w9_federal_tax);
								}
							?>
                            <label for="w9_federal_tax1" class="radio-control"><input type="checkbox" id="w9_federal_tax1" name="w9_federal_tax[]" value="Individual / Sole proprietor" <?php if(isset($w9_checked_federal_taxes) && in_array("Individual / Sole proprietor", $w9_checked_federal_taxes)) echo 'checked="checked"'; ?> />Individual / Sole proprietor</label>
                            
                            <label for="w9_federal_tax2" class="radio-control"><input type="checkbox" id="w9_federal_tax2" name="w9_federal_tax[]" value="C Corporation" <?php if(isset($w9_checked_federal_taxes) && in_array("C Corporation", $w9_checked_federal_taxes)) echo 'checked="checked"'; ?> />C Corporation</label>
                            
                            <label for="w9_federal_tax3" class="radio-control"><input type="checkbox" id="w9_federal_tax3" name="w9_federal_tax[]" value="S Corporation" <?php if(isset($w9_checked_federal_taxes) && in_array("S Corporation", $w9_checked_federal_taxes)) echo 'checked="checked"'; ?> />S Corporation</label>
                            
                            <label for="w9_federal_tax4" class="radio-control"><input type="checkbox" id="w9_federal_tax4" name="w9_federal_tax[]" value="Partnership" <?php if(isset($w9_checked_federal_taxes) && in_array("Partnership", $w9_checked_federal_taxes)) echo 'checked="checked"'; ?> />Partnership</label>
                            
                            <label for="w9_federal_tax5" class="radio-control"><input type="checkbox" id="w9_federal_tax5" name="w9_federal_tax[]" value="Trust / Estate" <?php if(isset($w9_checked_federal_taxes) && in_array("Trust / Estate", $w9_checked_federal_taxes)) echo 'checked="checked"'; ?> />Trust / Estate</label>
                            
                            <label for="w9_federal_tax6" class="radio-control"><input type="checkbox" id="w9_federal_tax6" name="w9_federal_tax[]" value="Limited Liability Corporation" <?php if(isset($w9_checked_federal_taxes) && in_array("Limited Liability Corporation", $w9_checked_federal_taxes)) echo 'checked="checked"'; ?> />Limited Liability Corporation</label>    
                        </div><!--radio_group-->
                        
                        <div class="full_col">
                        	<label for="w9_address_city_state" class="label-control require">Enter your address, city, state and ZIP code:</label>
                       	    <input type="text" name="w9_address_city_state" id="w9_address_city_state" value="<?php echo get_user_meta($user_id, 'w9_address_city_state', true); ?>" />

                        </div>
                        
                        <div class="full_col">
                        	<label for="w9_account_numbers" class="label-control">List account numbers (optional):</label>
                       	    <input type="text" name="w9_account_numbers" id="w9_account_numbers" value="<?php echo get_user_meta($user_id, 'w9_account_numbers', true); ?>">

                        </div>
                        
                        <div class="one_half">
                        	<label for="w9_requesters_name" class="label-control">Specify requester’s name and address (optional):</label>
                       	    <input type="text" name="w9_requesters_name" id="w9_requesters_name" value="<?php echo get_user_meta($user_id, 'w9_requesters_name', true); ?>">

                        </div>
                        
                        <div class="one_half et_column_last">
                        	<label for="w9_federal_id_number" class="label-control require">Indicate your Federal / Tax ID Number:</label>
                       	    <input type="text" name="w9_federal_id_number" id="w9_federal_id_number" value="<?php echo get_user_meta($user_id, 'w9_federal_id_number', true); ?>" />

                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <h5><strong>Put your signature and current date:</strong></h5>
                          <div class="one_half">
                             	<label class="label-control require">Authorized Signature</label>
                             	<div class="signature-wrapper">
                                    <div class="sig-pad" style="border-color:#E2E2E2; margin-bottom:5px;"></div>
                                    <br />(Use mouse or stylus pen to sign)
                                    <br />
                                    <?php
									if( get_user_meta($user_id, 'w9_signature', true) ) {
										echo '<img src="'.get_user_meta($user_id, 'w9_signature', true).'" alt="" /><br />';
									}
									?>
                                    <span class="sig-done">Done</span>
                                    <span class="sig-clear">Clear</span>
                                    <?php if( get_user_meta($user_id, 'w9_signature', true) ) { ?>
                                    <textarea id="w9_sig_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                    <?php } else { ?>
                                    <textarea id="w9_sig_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                    <?php } ?>
                                </div><!-- .signature-wrapper -->
                            </div>
                            <div class="one_half et_column_last">
                             	<label for="w9_date" class="label-control require">Date</label>
                       	  		<input type="text" name="w9_date" id="w9_date" value="<?php if( get_user_meta($user_id, 'w9_date', true) ) { echo get_user_meta($user_id, 'w9_date', true); } else { echo date("Y-m-d"); } ?>" />
                           </div>
                           <div class="clearfix"></div>

                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-tab">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="w9-form" />
                        <input type="hidden" name="w9_form_progress" id="w9_form_progress" value="<?php echo get_user_meta($user_id, 'w9_form_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_9-->
                <?php } ?>


                <?php if($show_travel_protection != "No") { ?>
                <div class="company_info_steps company_info_step_9" id="travel_tab" data-tab="4">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="travel-protection" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Travel Protection</h1>
                        <div class="line"></div>
                        
                        <div class="full_col clearfix radio_group">
                            <label for="travel_following_box" class="radio-control"><strong style="color:#3297DB;">If you will not be using our Travel Protection, check the box to skip this section:</strong> <input type="checkbox" class="following_box" name="travel_following_box" value="If you will not be using our Travel Protection, check the box to skip this section:" <?php if(get_user_meta($user_id, 'skip_travel_form', true) == "If you will not be using our Travel Protection, check the box to skip this section:") echo 'checked="checked"'; ?> /></label>
                        </div>
                        
                        <div class="full_col clearfix radio_group">
                        	<h5><strong>TYPE</strong> <small style="font-size: 12px;">(Check all that Apply): </small></h5>
                            <?php
								$property_protection_type = get_user_meta($user_id, 'property_protection_type', true);
								if($property_protection_type) {
									$property_protection_checked_types = explode(",", $property_protection_type);
								}
							?>
                            <label for="property_part_b_type1" class="radio-control"><input type="checkbox" id="property_part_b_type1" name="property_protection_type[]" value="Full-Time Property Manager" <?php if(isset($property_protection_checked_types) && in_array("Full-Time Property Manager", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Full-Time Property Manager</label>
                            
                            <label for="property_part_b_type2" class="radio-control"><input type="checkbox" id="property_part_b_type2" name="property_protection_type[]" value="Online Property Listing Service" <?php if(isset($property_protection_checked_types) && in_array("Online Property Listing Service", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Online Property Listing Service </label>
                            
                            <label for="property_part_b_type3" class="radio-control"><input type="checkbox" id="property_part_b_type3" name="property_protection_type[]" value="Homeowner/ Unit-owner" <?php if(isset($property_protection_checked_types) && in_array("Homeowner/ Unit-owner", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Homeowner/ Unit-owner</label>
                            
                            <label for="property_part_b_type4" class="radio-control"><input type="checkbox" id="property_part_b_type4" name="property_protection_type[]" value="Part-Time Property Manager" <?php if(isset($property_protection_checked_types) && in_array("Part-Time Property Manager", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Part-Time Property Manager</label>
                            
                            <label for="property_part_b_type5" class="radio-control"><input type="checkbox" id="property_part_b_type5" name="property_protection_type[]" value="Aggregator" <?php if(isset($property_protection_checked_types) && in_array("Aggregator", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Aggregator</label>
                            
                            <label for="property_part_b_type6" class="radio-control"><input type="checkbox" id="property_part_b_type6" name="property_protection_type[]" value="Booking Software Vendor" <?php if(isset($property_protection_checked_types) && in_array("Booking Software Vendor", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Booking Software Vendor</label>
                            
                            <label for="property_part_b_type7" class="radio-control"><input type="checkbox" id="property_part_b_type7" name="property_protection_type[]" value="Other" <?php if(isset($property_protection_checked_types) && in_array("Other", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Other</label>
                            
                            <label for="property_part_b_type8" class="radio-control"><input type="checkbox" id="property_part_b_type8" name="property_protection_type[]" value="Short-Term Vacation Rentals" <?php if(isset($property_protection_checked_types) && in_array("Short-Term Vacation Rentals", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Short-Term Vacation Rentals</label>
                            
                            
                            <label for="property_part_b_type9" class="radio-control"><input type="checkbox" id="property_part_b_type9" name="property_protection_type[]" value="FCorporate Housing" <?php if(isset($property_protection_checked_types) && in_array("FCorporate Housing", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Corporate Housing</label>
                            
                            <label for="property_part_b_type10" class="radio-control"><input type="checkbox" id="property_part_b_type10" name="property_protection_type[]" value="Long Term Rentals" <?php if(isset($property_protection_checked_types) && in_array("Long Term Rentals", $property_protection_checked_types)) echo 'checked="checked"'; ?> />Long Term Rentals</label>                          
                        </div><!--full_col clearfix radio_group-->
                        
                        <div class="full_col">
                       	  <label for="property_reservations" class="label-control require">Total bookings per year?</label>
                       	  <input type="text" name="property_reservations" id="property_reservations" value="<?php echo get_user_meta($user_id, 'property_reservations', true); ?>" />
                       	</div><!--full_col-->
                        
                        <div class="full_col">
                       	  <label for="property_booking_cost" class="label-control require">What is your average booking cost?</label>
                       	  <input type="text" name="property_booking_cost" id="property_booking_cost" value="<?php echo get_user_meta($user_id, 'property_booking_cost', true); ?>" />
                       	</div><!--full_col-->
                        
                        
                        <div class="full_col">
                       	  <label for="property_average_length" class="label-control require">What is the average length of stay? </label>
                       	  <input type="text" name="property_average_length" id="property_average_length" value="<?php echo get_user_meta($user_id, 'property_average_length', true); ?>" />
                       	</div><!--full_col-->
                        
                          <h5 style="margin-bottom:5px;"><strong>How many cancellation requests have you received versus total bookings each of the last two years?</strong></h5>
                         
                         
                         <div class="travel_year_loss_wrap">
                             <div class="one_third">
                                <h6><strong>YEAR</strong></h6>
                                <label class="label-control">2017</label>
                                <label class="label-control">2016</label>
                             </div>
                             <div class="one_third">
                                <h6><strong>NUMBER OF PROPERTIES BOOKED</strong></h6>
                                <input type="text" name="number_of_properties_booked1" id="number_of_properties_booked1" value="<?php echo get_user_meta($user_id, 'number_of_properties_booked1', true); ?>">
                                
                                <input type="text" name="number_of_properties_booked2" id="number_of_properties_booked2" value="<?php echo get_user_meta($user_id, 'number_of_properties_booked2', true); ?>">
                             </div>
                             <div class="one_third et_column_last">
                                <h6><strong>NUMBER OF TOTAL CANCELLATIONS</strong></h6>
                                <input type="text" name="number_of_total_cancellations1" id="number_of_total_cancellations1" value="<?php echo get_user_meta($user_id, 'number_of_total_cancellations1', true); ?>" >
                                
                                <input type="text" name="number_of_total_cancellations2" id="number_of_total_cancellations2" value="<?php echo get_user_meta($user_id, 'number_of_total_cancellations2', true); ?>" >
                             </div>
                             <div class="clearfix"></div>
                          </div><!--travel_year_loss_wrap-->

                        <div class="full_col">
                       	  <label for="why_guest_cancels_their_booking" class="label-control">Please describe typical reasons why a guest cancels their booking: </label>
                       	  <textarea name="why_guest_cancels_their_booking" id="why_guest_cancels_their_booking"><?php echo get_user_meta($user_id, 'why_guest_cancels_their_booking', true); ?></textarea>
                       	</div><!--full_col-->
                        
                        <h5 style="margin-bottom:8px;"><strong>APPLICANT/ SUBSCRIBER ACKNOWLEDGEMENTS</strong></h5>
                        
                        <p>The participating coverage-provider(s)/insurer(s) rely upon the representations, declarations,  and statements in this Application for coverage approval decisions. All such representations, declarations and statements attached to this Application are the basis for coverage offered, if any. The Applicant agrees that the representations, declarations, and statements made in this Application shall be the basis of underwriting and/or coverage approval and that should coverage/a policy be issued, the Application will be attached to and made a part the coverage/policy contract.</p>

<p>All written statements and materials furnished to agents of InsureStays and the coverage-provider(s)/insurer(s) submitted in conjunction with this Application are hereby incorporated by reference into this Application and made a part hereof. Nothing contained herein or incorporated herein by reference shall constitute notice of a claim or potential claim under any contract of coverage/insurance.</p>

<p>This Application does not bind the Applicant to buy coverage, nor does it obligate the coverage-provider(s) /insurer(s) to approve coverage.</p>

<p>The undersigned Applicant declares that the statements set forth in this Application are true. The Applicant further agrees that</p>  
						<ul>
                        	<li><strong>a)</strong> if the information provided in this Application changes between the date of this Application and the effective date of the coverage, the Applicant will immediately notify InsureStays and the provider/insurer(s) of such changes, and </li>
                            <li><strong>b)</strong> based on such changes, the provider/insurer(s) may withdraw or modify any outstanding quotations and/or authorizations or agreement to bind coverage.</li>
                        </ul>

                        <h5 style="margin-top:20px"><strong>FOR  AND ON BEHALF OF</strong></h5>
                             <div class="two_third">
                             	<label for="property_printed_name" class="label-control require">NAME & TITLE</label>
                       	  		<input type="text" name="property_printed_name" id="property_printed_name" value="<?php echo get_user_meta($user_id, 'property_printed_name', true); ?>" />
                             </div>
                             <div class="one_third et_column_last">
                             	<label for="property_date" class="label-control require">DATE</label>
                       	  		<input type="text" name="property_date" id="property_date" value="<?php if(get_user_meta($user_id, 'property_date', true)) { echo get_user_meta($user_id, 'property_date', true); } else { echo date("Y-m-d"); } ?>" />
                             </div>
                             <div class="clearfix"></div>
                            <label class="label-control require">APPLICANT/ AUTHORIZED SIGNATURE</label>
                            <div class="signature-wrapper">
                                <div class="sig-pad" style="border-color:#E2E2E2; margin-bottom:5px;"></div>
                                <br />(Use mouse or stylus pen to sign)
                                <br />
                                <?php
                                if( get_user_meta($user_id, 'property_signature', true) ) {
                                    echo '<img src="'.get_user_meta($user_id, 'property_signature', true).'" alt="" /><br />';
                                }
                                ?>
                                <span class="sig-done">Done</span>
                                <span class="sig-clear">Clear</span>
                                <?php if( get_user_meta($user_id, 'property_signature', true) ) { ?>
                                <textarea id="property_sig_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } else { ?>
                                <textarea id="property_sig_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } ?>
                            </div><!-- .signature-wrapper -->

                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-tab">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="travel-protection" />
                        <input type="hidden" name="travel_protection_progress" id="travel_protection_progress" value="<?php echo get_user_meta($user_id, 'travel_protection_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_9-->
                <?php } ?>


                <?php if($show_property_protection != "No") { ?>
                <div class="company_info_steps company_info_step_9" id="property_tab" data-tab="5">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="property-protection" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Property Protection / Damage Protection</h1>
                        <div class="line"></div>
                        
                        <div class="full_col clearfix radio_group">
                            <label for="property_following_box" class="radio-control"><strong style="color:#3297DB;">If you will not be using our Property Protection, check the box to skip this section:</strong> <input type="checkbox" class="following_box" name="property_following_box" value="If you will not be using our Travel Protection, check the box to skip this section:" <?php if(get_user_meta($user_id, 'skip_property_form', true) == "If you will not be using our Travel Protection, check the box to skip this section:") echo 'checked="checked"'; ?> /></label>
                        </div>
                        
                        <div class="full_col clearfix radio_group">
                        	<h5><strong>TYPE<!--<sup style="bottom: .2em;"><span style="color:#ED4545;">*</span></sup>--> </strong><small style="font-size: 12px;">(Check all that Apply): </small></h5>
                            <?php
								$travel_protection_type = get_user_meta($user_id, 'travel_protection_type', true);
								if($travel_protection_type) {
									$travel_protection_checked_types = explode(",", $travel_protection_type);
								}
							?>
                            <label for="travel_part_b_type1" class="radio-control"><input type="checkbox" id="travel_part_b_type1" name="travel_protection_type[]" value="Full-Time Property Manager" <?php if(isset($travel_protection_checked_types) && in_array("Full-Time Property Manager", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Full-Time Property Manager</label>
                            
                            <label for="travel_part_b_type2" class="radio-control"><input type="checkbox" id="travel_part_b_type2" name="travel_protection_type[]" value="Online Property Listing Service" <?php if(isset($travel_protection_checked_types) && in_array("Online Property Listing Service", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Online Property Listing Service </label>
                            
                            <label for="travel_part_b_type3" class="radio-control"><input type="checkbox" id="travel_part_b_type3" name="travel_protection_type[]" value="Homeowner/ Unit-owner" <?php if(isset($travel_protection_checked_types) && in_array("Homeowner/ Unit-owner", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Homeowner/ Unit-owner</label>
                            
                            <label for="travel_part_b_type4" class="radio-control"><input type="checkbox" id="travel_part_b_type4" name="travel_protection_type[]" value="Part-Time Property Manager" <?php if(isset($travel_protection_checked_types) && in_array("Part-Time Property Manager", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Part-Time Property Manager</label>
                            
                            <label for="travel_part_b_type5" class="radio-control"><input type="checkbox" id="travel_part_b_type5" name="travel_protection_type[]" value="Aggregator" <?php if(isset($travel_protection_checked_types) && in_array("Aggregator", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Aggregator</label>
                            
                            <label for="travel_part_b_type6" class="radio-control"><input type="checkbox" id="travel_part_b_type6" name="travel_protection_type[]" value="Booking Software Vendor" <?php if(isset($travel_protection_checked_types) && in_array("Booking Software Vendor", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Booking Software Vendor</label>
                            
                            <label for="travel_part_b_type7" class="radio-control"><input type="checkbox" id="travel_part_b_type7" name="travel_protection_type[]" value="Other" <?php if(isset($travel_protection_checked_types) && in_array("Other", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Other</label>
                            
                            <label for="travel_part_b_type8" class="radio-control"><input type="checkbox" id="travel_part_b_type8" name="travel_protection_type[]" value="Short-Term Vacation Rentals" <?php if(isset($travel_protection_checked_types) && in_array("Short-Term Vacation Rentals", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Short-Term Vacation Rentals</label>
                            
                            <label for="travel_part_b_type9" class="radio-control"><input type="checkbox" id="travel_part_b_type9" name="travel_protection_type[]" value="FCorporate Housing" <?php if(isset($travel_protection_checked_types) && in_array("FCorporate Housing", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Corporate Housing</label>
                            
                            <label for="travel_part_b_type10" class="radio-control"><input type="checkbox" id="travel_part_b_type10" name="travel_protection_type[]" value="Long Term Rentals" <?php if(isset($travel_protection_checked_types) && in_array("Long Term Rentals", $travel_protection_checked_types)) echo 'checked="checked"'; ?> />Long Term Rentals</label>                            
                        </div>
                        
                        <div class="full_col clearfix">
                        	<label for="professional_property_manager" class="label-control require">	Are the units you rent directly managed by a full-time professional property manager?</label>
                            <input type="text" name="professional_property_manager" id="professional_property_manager" value="<?php echo get_user_meta($user_id, 'professional_property_manager', true); ?>" />
                       </div>
                       
                       <div class="full_col clearfix">
                        	<label for="percentage_properties" class="label-control require">What percentage of your properties are directly managed by you/ your company?</label>
                            <input type="text" name="percentage_properties" id="percentage_properties" value="<?php echo get_user_meta($user_id, 'percentage_properties', true); ?>" />
                       </div>
                       <h5 style="margin-bottom:5px;"><strong>How many of each type of property/unit do you offer for rental:</strong></h5>
                       
                       <div class="three_col">
                       	<h6><strong>Type of Unit</strong></h6>
                       </div>
                       <div class="three_col last">
                       	<h6><strong>Number of Units</strong></h6>
                       </div>
                       <div class="clearfix"></div><!--clearfix-->
                       
                       <div class="three_col">
                       	<label for="travel_single_family" class="label-control">Single Family</label>
                       </div>
                       <div class="three_col last">
                       	<input type="text" name="travel_single_family" id="travel_single_family" value="<?php echo get_user_meta($user_id, 'travel_single_family', true); ?>">
                       </div>
                       <div class="clearfix"></div><!--clearfix-->
                       
                       <div class="three_col">
                       	<label for="travel_condominium" class="label-control">Condominium</label>
                       </div>
                       <div class="three_col last">
                       	<input type="text" name="travel_condominium" id="travel_condominium" value="<?php echo get_user_meta($user_id, 'travel_condominium', true); ?>">
                       </div>
                       <div class="clearfix"></div><!--clearfix-->
                       
                       <div class="three_col">
                       	<label for="travel_apartment" class="label-control">Apartment</label>
                       </div>
                       <div class="three_col last">
                       	<input type="text" name="travel_apartment" id="travel_apartment" value="<?php echo get_user_meta($user_id, 'travel_apartment', true); ?>">
                       </div>
                       <div class="clearfix"></div><!--clearfix-->
                       
                       
                       <div class="three_col">
                       	<label for="travel_time_share" class="label-control">Time Share</label>
                       </div>
                       <div class="three_col last">
                       	<input type="text" name="travel_time_share" id="travel_time_share" value="<?php echo get_user_meta($user_id, 'travel_time_share', true); ?>">
                       </div>
                       <div class="clearfix"></div><!--clearfix-->
                       
                       
                       <div class="three_col">
                       	<label for="travel_condo_tel" class="label-control">Lodge/Condo-tel</label>
                       </div>
                       <div class="three_col last">
                       	<input type="text" name="travel_condo_tel" id="travel_condo_tel" value="<?php echo get_user_meta($user_id, 'travel_condo_tel', true); ?>">
                       </div>
                       <div class="clearfix"></div><!--clearfix-->
                       
                       <div class="three_col">
                       	<label for="travel_cabin" class="label-control">Cabin</label>
                       </div>
                       <div class="three_col last">
                       	<input type="text" name="travel_cabin" id="travel_cabin" value="<?php echo get_user_meta($user_id, 'travel_cabin', true); ?>">
                       </div>
                       <div class="clearfix"></div><!--clearfix-->
                       
                       <div class="three_col">
                       	<label for="travel_other" class="label-control">Other</label>
                       </div>
                       <div class="three_col last">
                       	<input type="text" name="travel_other" id="travel_other" value="<?php echo get_user_meta($user_id, 'travel_other', true); ?>">
                       </div>
                       <div class="clearfix"></div><!--clearfix-->
                       
                       <div class="three_col" style="text-align:right;">
                       	<label for="travel_total" class="label-control">TOTAL</label>
                       </div>
                       <div class="three_col last">
                       	<input type="text" name="travel_total" id="travel_total" value="<?php echo get_user_meta($user_id, 'travel_total', true); ?>">
                       </div>
                       <div class="clearfix"></div><!--clearfix-->
                       
                       <div class="full_col">
                       	  <label for="travel_provide_list" class="label-control">For the US, provide list of states in which your properties are located.</label>
                       	  <input type="text" name="travel_provide_list" id="travel_provide_list" value="<?php echo get_user_meta($user_id, 'travel_provide_list', true); ?>">
                       </div>
                       
                       <div class="full_col">
                       	  <label for="travel_provide_list_countries" class="label-control">For international, provide list of countries in which your properties are located</label>
                       	  <input type="text" name="travel_provide_list_countries" id="travel_provide_list_countries" value="<?php echo get_user_meta($user_id, 'travel_provide_list_countries', true); ?>">
                       </div><!--full_col-->
                       
                        <div class="full_col clearfix radio_group">
                        	<label class="label-control">In the next 12 months, do you plan on increasing or decreasing the number of units for rent and/or the number of reservations you accept?</label>
                            <label for="travel_units_for_rent1" class="radio-control"><input type="radio" id="travel_units_for_rent1" name="travel_units_for_rent" value="Yes" <?php if(get_user_meta($user_id, 'travel_units_for_rent', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="travel_units_for_rent2" class="radio-control"><input type="radio" id="travel_units_for_rent2" name="travel_units_for_rent" value="No" <?php if(get_user_meta($user_id, 'travel_units_for_rent', true) == "No") echo 'checked="checked"'; ?> />No</label>
                          </div>
                          
                          <div class="one_third">
                          	<label for="travel_units_for_rent_describe" class="radio-control">If "Yes", please describe:</label>
                          </div>
                          <div class="two_third et_column_last">
                       	  <input type="text" name="travel_units_for_rent_describe" id="travel_units_for_rent_describe" value="<?php echo get_user_meta($user_id, 'travel_units_for_rent_describe', true); ?>">
                          </div>
                          <div class="clearfix"></div>
                          
                          
                          <div class="full_col">
                       	  <label for="travel_total_bookings" class="label-control">How many total bookings did you have during the past/prior 12 months?</label>
                       	  <input type="text" name="travel_total_bookings" id="travel_total_bookings" value="<?php echo get_user_meta($user_id, 'travel_total_bookings', true); ?>">
                       	 </div>
                         
                         <div class="full_col">
                       	  <label for="travel_average_length" class="label-control">What is your average length of stay?</label>
                       	  <input type="text" name="travel_average_length" id="travel_average_length" value="<?php echo get_user_meta($user_id, 'travel_average_length', true); ?>">
                       	 </div>
                         
                         
                         <div class="full_col">
                       	  <label for="travel_booking_amount" class="label-control">What is your average booking total amount?</label>
                       	  <input type="text" name="travel_booking_amount" id="travel_booking_amount" value="<?php echo get_user_meta($user_id, 'travel_booking_amount', true); ?>">
                       	 </div>
                         
                         <div class="clearfix radio_group">
                        	<label class="label-control">Do you plan on adding the Property Protection Program as a surcharge on every booking?</label>
                            <label for="travel_property_program1" class="radio-control"><input type="radio" id="travel_property_program1" name="travel_property_program" value="Yes" <?php if(get_user_meta($user_id, 'travel_property_program', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="travel_property_program2" class="radio-control"><input type="radio" id="travel_property_program2" name="travel_property_program" value="No" <?php if(get_user_meta($user_id, 'travel_property_program', true) == "No") echo 'checked="checked"'; ?> />No</label>
                          </div><!--radio_group-->
                          
                          <div class="clearfix radio_group">
                        	<label class="label-control">Does your rental/lease agreement include specific guidance and instruction for the guest(s) regarding guest responsibilities with respect to proper care for the rented unit?</label>
                            <label for="travel_agreement_guidance1" class="radio-control"><input type="radio" id="travel_agreement_guidance1" name="travel_agreement_guidance" value="Yes" <?php if(get_user_meta($user_id, 'travel_agreement_guidance', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="travel_agreement_guidance2" class="radio-control"><input type="radio" id="travel_agreement_guidance2" name="travel_agreement_guidance" value="No" <?php if(get_user_meta($user_id, 'travel_agreement_guidance', true) == "No") echo 'checked="checked"'; ?> />No</label>
                          </div><!--radio_group-->
                          
                          <div class="clearfix radio_group">
                        	<label class="label-control">Do you inspect the unit immediately upon check-out for every booking-occupancy?</label>
                            <label for="travel_booking_occupancy1" class="radio-control"><input type="radio" id="travel_booking_occupancy1" name="travel_booking_occupancy" value="Yes" <?php if(get_user_meta($user_id, 'travel_booking_occupancy', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="travel_booking_occupancy2" class="radio-control"><input type="radio" id="travel_booking_occupancy2" name="travel_booking_occupancy" value="No" <?php if(get_user_meta($user_id, 'travel_booking_occupancy', true) == "No") echo 'checked="checked"'; ?> />No</label>
                          </div><!--radio_group-->
                          
                          <div class="clearfix radio_group">
                        	<label class="label-control">Do you require guest-verification of damages?</label>
                            <label for="travel_guest_verification1" class="radio-control"><input type="radio" id="travel_guest_verification1" name="travel_guest_verification" value="Yes" <?php if(get_user_meta($user_id, 'travel_guest_verification', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="travel_guest_verification2" class="radio-control"><input type="radio" id="travel_guest_verification2" name="travel_guest_verification" value="No" <?php if(get_user_meta($user_id, 'travel_guest_verification', true) == "No") echo 'checked="checked"'; ?> />No</label>
                          </div><!--radio_group-->
                          
                          
                       	  <h5 style="margin-bottom:5px;"><strong>What is the total amount of renter-caused damage, security deposit deductions, or claimed accidental damages to your rental properties each of the last three (3) years?</strong></h5>
                         
                         
                         <div class="travel_year_loss_wrap">
                             <div class="one_fourth">
                             	<h6><strong>Year of Loss</strong></h6>
                                <label class="label-control">2017</label>
                                <label class="label-control">2016</label>
                                <label class="label-control">2015</label>
                             </div>
                             <div class="one_fourth">
                             	<h6><strong>Approximate Number of Claim Incidents</strong></h6>
                                <input type="text" name="travel_approximate_number1" id="travel_approximate_number1" value="<?php echo get_user_meta($user_id, 'travel_approximate_number1', true); ?>">
                                
                                <input type="text" name="travel_approximate_number2" id="travel_approximate_number2" value="<?php echo get_user_meta($user_id, 'travel_approximate_number2', true); ?>">
                                
                                <input type="text" name="travel_approximate_number3" id="travel_approximate_number3" value="<?php echo get_user_meta($user_id, 'travel_approximate_number3', true); ?>">
                             </div>
                             <div class="one_fourth">
                             	<h6><strong>Total Amount of Losses</strong></h6>
                                <input type="text" name="travel_amount_losses1" id="travel_amount_losses1" value="<?php echo get_user_meta($user_id, 'travel_amount_losses1', true); ?>">
                                
                                <input type="text" name="travel_amount_losses2" id="travel_amount_losses2" value="<?php echo get_user_meta($user_id, 'travel_amount_losses2', true); ?>" >
                                
                                <input type="text" name="travel_amount_losses3" id="travel_amount_losses3" value="<?php echo get_user_meta($user_id, 'travel_amount_losses3', true); ?>" >
                             </div>
                             <div class="one_fourth et_column_last">
                             	<h6><strong>Nature of/ Type of Losses</strong></h6>
                                <input type="text" name="travel_type_losses1" id="travel_type_losses1" value="<?php echo get_user_meta($user_id, 'travel_type_losses1', true); ?>" >
                                
                                <input type="text" name="travel_type_losses2" id="travel_type_losses2" value="<?php echo get_user_meta($user_id, 'travel_type_losses2', true); ?>" >
                                
                                <input type="text" name="travel_type_losses3" id="travel_type_losses3" value="<?php echo get_user_meta($user_id, 'travel_type_losses3', true); ?>" >
                             </div>
                             <div class="clearfix"></div>
                          </div><!--travel_year_loss_wrap-->
                             
                             <div class="full_col">
                             	<h5 style="margin:5px 0 10px"> <strong>APPLICANT/ SUBSCRIBER ACKNOWLEDGEMENTS</strong></h5>
                                <p>The participating coverage-provider(s)/insurer(s) rely upon the representations, declarations,  and statements in this Application for coverage approval decisions. All such representations, declarations and statements attached to this Application are the basis for coverage offered, if any. The Applicant agrees that the representations, declarations, and statements made in this Application shall be the basis of underwriting and coverage approval and that should coverage/a policy be issued, the Application may be attached to and made a part the coverage/policy contract.</p>

<p>All written statements and materials furnished to agents of InsureStays and the coverage-provider(s)/insurer(s) submitted in conjunction with this Application are hereby incorporated by reference into this Application and made a part hereof. Nothing contained herein or incorporated herein by reference shall constitute notice of a claim or potential claim under any contract of coverage/insurance.</p>

<p>This Application does not bind the Applicant to buy coverage, nor does it obligate the coverage-provider(s) /insurer(s) to approve coverage. </p>

<p>The undersigned Applicant declares that the statements set forth in this Application are true. The Applicant further agrees that</p>
							<ul>
                            	<li><strong>a)</strong> if the information provided in this Application changes between the date of this Application and the effective date of the coverage, the Applicant will immediately notify InsureStays and the provider/insurer(s) of such changes, and </li>
                                <li><strong>b)</strong> based on such changes, the provider/insurer(s) may withdraw or modify any outstanding quotations and/or authorizations or agreement to bind coverage.</li>
                            </ul>
                            <p>If and when coverage/a policy is issued, this Application is attached to and made a part of the coverage/policy; therefore, it is necessary that all questions be answered in detail. The Applicant hereby acknowledges that by signing below where indicated, that this signed statement will be attached to the coverage/policy.</p>

                             </div>
                             
                             <h5 style="margin-top:20px"><strong>FOR AND ON BEHALF OF APPLICANT</strong></h5>
                             <div class="two_third">
                             	<label for="travel_printed_name" class="label-control require">Printed Name & Title</label>
                       	  		<input type="text" name="travel_printed_name" id="travel_printed_name" value="<?php echo get_user_meta($user_id, 'travel_printed_name', true); ?>" />
                             </div>
                             <div class="one_third et_column_last">
                             	<label for="travel_date" class="label-control require">Date</label>
                       	  		<input type="text" name="travel_date" id="travel_date" value="<?php if(get_user_meta($user_id, 'travel_date', true)) { echo get_user_meta($user_id, 'travel_date', true); } else { echo date("Y-m-d"); } ?>" />
                             </div>
                             <div class="clearfix"></div>
                            <label class="label-control require">Authorized Signature</label>
                            <div class="signature-wrapper">
                                <div class="sig-pad" style="border-color:#E2E2E2; margin-bottom:5px;"></div>
                                <br />(Use mouse or stylus pen to sign)
                                <br />
                                <?php
                                if( get_user_meta($user_id, 'travel_signature', true) ) {
                                    echo '<img src="'.get_user_meta($user_id, 'travel_signature', true).'" alt="" /><br />';
                                }
                                ?>
                                <span class="sig-done">Done</span>
                                <span class="sig-clear">Clear</span>
                                <?php if( get_user_meta($user_id, 'travel_signature', true) ) { ?>
                                <textarea id="travel_sig_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } else { ?>
                                <textarea id="travel_sig_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } ?>
                            </div><!-- .signature-wrapper -->

                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-tab">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="property-protection" />
                        <input type="hidden" name="property_protection_progress" id="property_protection_progress" value="<?php echo get_user_meta($user_id, 'property_protection_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_9-->
                <?php } ?>
               

                <?php if($show_liability_protection != "No") { ?>
                <div class="company_info_steps company_info_step_9" id="liability_tab" data-tab="6">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="liability-protection" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Liability &amp; Real Property Protection</h1>
                        <div class="line"></div>
                        <?php //if(get_user_meta($user_id, 'sign_liability_docusign_form', true) == "Yes") { ?>
                        <!--<p class="already-signed">This PDF has been completed and signed. Please Click "Next" to Continue & Save the PDF - Thank You.</p>-->
                        <?php //} ?>
                        <!--<label for="sign_liability_docusign_form" class="label-control require">Have you accurately completed the above PDF and Signed it?</label>
                        <select name="sign_liability_docusign_form" id="sign_liability_docusign_form" class="docusign-form-done">
                            <option value="">Please Select</option>
                            <option value="Yes" <?php //if(get_user_meta($user_id, 'sign_liability_docusign_form', true) == "Yes") echo 'selected="selected"'; ?>>Yes</option>
                            <option value="No" <?php //if(get_user_meta($user_id, 'sign_liability_docusign_form', true) == "No") echo 'selected="selected"'; ?>>No</option>
                        </select>-->
                        
                        <div class="full_col clearfix radio_group">
                            <label for="liability_following_box" class="radio-control"><strong style="color:#3297DB;">If you will not be using our Liability Protection, check the box to skip this section:</strong> <input type="checkbox" class="following_box" name="liability_following_box" value="If you will not be using our Liability Protection, check the box to skip this section:" <?php if(get_user_meta($user_id, 'skip_liability_form', true) == "If you will not be using our Liability Protection, check the box to skip this section:") echo 'checked="checked"'; ?> /></label>
                        </div>
                        
                        <div class="full_col">
                        	<label for="liability_insurance_program" class="label-control require">Please provide details of any existing Tenant Damage or Owner Liability Insurance Program:</label>
                       	    <input type="text" name="liability_insurance_program" id="liability_insurance_program" value="<?php echo get_user_meta($user_id, 'liability_insurance_program', true); ?>" />
                        </div>
                        
                        <div class="one_third et_coumn_last">
                        	<label for="liability_expiration_date" class="label-control require">Insurer & Expiration Date:</label>
                       	    <input type="text" name="liability_expiration_date" id="liability_expiration_date" value="<?php echo get_user_meta($user_id, 'liability_expiration_date', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        
                        <div class="radio_group">
                        	<label class="label-control">Has any application for insurance on behalf your company or any of the present Directors/Partners/Principals or, to your knowledge on behalf of their predecessors in business ever been declined or has any such insurance ever been cancelled or renewal refused?</label>
                            <label for="liability_refused1" class="radio-control"><input type="radio" id="liability_refused1" name="liability_refused" value="Yes" <?php if(get_user_meta($user_id, 'liability_refused', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="liability_refused2" class="radio-control"><input type="radio" id="liability_refused2" name="liability_refused" value="No" <?php if(get_user_meta($user_id, 'liability_refused', true) == "No") echo 'checked="checked"'; ?> />No</label>
                     	</div><!--radio_group-->
                        
                        <div class="radio_group">
                        	<label class="label-control">Have any insured losses which would be subject to this program been incurred by your company over the past 5 years?</label>
                            <label for="liability_insured_losses1" class="radio-control"><input type="radio" id="liability_insured_losses1" name="liability_insured_losses" value="Yes" <?php if(get_user_meta($user_id, 'liability_insured_losses', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="liability_insured_losses2" class="radio-control"><input type="radio" id="liability_insured_losses2" name="liability_insured_losses" value="No" <?php if(get_user_meta($user_id, 'liability_insured_losses', true) == "No") echo 'checked="checked"'; ?> />No</label>
                     	</div><!--radio_group-->
                        
                        <div class="two_third et_coumn_last">
                        	<label for="liability_insured_details" class="label-control">If you answered YES, to either of the two questions above, please provide details:</label>
                       	    <input type="text" name="liability_insured_details" id="liability_insured_details" value="<?php echo get_user_meta($user_id, 'liability_insured_details', true); ?>">
                        </div>
                        <div class="clearfix"></div>
                        
                        <h5 style="margin-bottom:10px"><strong>APPLICANT/ SUBSCRIBER ACKNOWLEDGEMENTS</strong></h5>
                        <ul>
                        	<li><strong>a)</strong> The participating coverage-provider(s)/insurer(s) rely upon the representations, declarations, and statements in this Application for coverage approval decisions. All such representations, declarations and statements attached to this Application are the basis for coverage offered, if any. The Applicant agrees that the representations, declarations, and statements made in this Application shall be the basis of underwriting and/or coverage approval and that should coverage/a policy be issued, the Application will be attached to and made a part the coverage/policy contract.</li>
                            <li><strong>b)</strong> All written statements and materials furnished to agents of InsureStays and the coverage-provider(s)/insurer(s) submitted in conjunction with this Application are hereby incorporated by reference into this Application and made a part hereof. Nothing contained herein or incorporated herein by reference shall constitute notice of a claim or potential claim under any contract of coverage/insurance.</li>
                            <li><strong>c)</strong> This Application does not bind the Applicant to buy coverage, nor does it obligate the coverage-provider(s) /insurer(s) to approve coverage.</li>
                            <li><strong>d)</strong> The undersigned Applicant declares that the statements set forth in this Application are true. The Applicant further agrees that if the information provided in this Application changes between the date of this Application and the effective date of the coverage, the Applicant will immediately notify InsureStays and the provider/insurer(s) of such changes, and based on such changes, the provider/insurer(s) may withdraw or modify any outstanding quotations and/or authorizations or agreement to bind coverage.</li>
                        </ul>
                        
                        <h5 style="margin-top:10px"><strong>FOR  AND ON BEHALF OF:</strong></h5>
                             <div class="two_third">
                             	<label for="liability_printed_name" class="label-control require">NAME & TITLE</label>
                       	  		<input type="text" name="liability_printed_name" id="liability_printed_name" value="<?php echo get_user_meta($user_id, 'liability_printed_name', true); ?>" />
                             </div>
                             <div class="one_third et_column_last">
                             	<label for="liability_date" class="label-control require">DATE</label>
                       	  		<input type="text" name="liability_date" id="liability_date" value="<?php if(get_user_meta($user_id, 'liability_date', true)) { echo get_user_meta($user_id, 'liability_date', true); } else { echo date("Y-m-d"); } ?>" />
                             </div>
                             <div class="clearfix"></div>
                             	<label class="label-control require">APPLICANT/ AUTHORIZED SIGNATURE</label>
                            <div class="signature-wrapper">
                                <div class="sig-pad" style="border-color:#E2E2E2; margin-bottom:5px;"></div>
                                <br />(Use mouse or stylus pen to sign)
                                <br />
                                <?php
                                if( get_user_meta($user_id, 'liability_signature', true) ) {
                                    echo '<img src="'.get_user_meta($user_id, 'liability_signature', true).'" alt="" /><br />';
                                }
                                ?>
                                <span class="sig-done">Done</span>
                                <span class="sig-clear">Clear</span>
                                <?php if( get_user_meta($user_id, 'liability_signature', true) ) { ?>
                                <textarea id="liability_sig_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } else { ?>
                                <textarea id="liability_sig_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } ?>
                           </div><!-- .signature-wrapper -->
                        
                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-tab">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="liability-protection" />
                        <input type="hidden" name="liability_protection_progress" id="liability_protection_progress" value="<?php echo get_user_meta($user_id, 'liability_protection_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_9-->
                <?php } ?>

                
                <?php if($show_schedule_training != "No") { ?>
                <div class="company_info_steps company_info_step_6" id="schedule_tab" data-tab="7">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="schedule-training" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Schedule Training</h1>
                        <p>We want all new clients to attend a training session so you have a better understanding of the programs and how to offer them to your travelers.</p>
                        <div class="line"></div>
                        
                        <div class="full_col clearfix radio_group">
                            <label for="standard_weekly_training" class="label-control">Please invite me to the two standard weekly training sessions:</label>
                            <div class="one_half">
                                <label for="standard_weekly_training1" class="radio-control"><input type="radio" id="standard_weekly_training1" name="standard_weekly_training" value="Training - Tuesdays @ 1pm EST / 10am PST" <?php if(!get_user_meta($user_id, 'standard_weekly_training_sessions', true) || (get_user_meta($user_id, 'standard_weekly_training_sessions', true) == "Training - Tuesdays @ 1pm EST / 10am PST")) echo 'checked="checked"'; ?> />Training - Tuesdays @ 1pm EST / 10am PST</label>
                            </div><!--one_half-->
                            <div class="one_half et_column_last">
                                <label for="standard_weekly_training2" class="radio-control"><input type="radio" id="standard_weekly_training2" name="standard_weekly_training" value="Training - Tuesdays @ 2pm EST / 11am PST" <?php if(get_user_meta($user_id, 'standard_weekly_training_sessions', true) == "Training - Tuesdays @ 2pm EST / 11am PST") echo 'checked="checked"'; ?> />Training - Tuesdays @ 2pm EST / 11am PST</label>
                            </div><!--one_half-->
                        </div><!--full_col-->
                        
                        <div class="full_col">
                            <label for="standard_weekly_training" class="label-control">I would like to set up a custom date & time for a combined Platform & Products training session:</label>
                        </div>
                        <div class="three_col">
                            <input type="date" name="product_training_date" id="product_training_date" placeholder="MM/DD/YY" value="<?php echo get_user_meta($user_id, 'product_training_custom_date', true); ?>" />
                        </div>
                        <div class="three_col">
                            <input type="datetime" name="product_training_time" id="product_training_time" placeholder="Time" value="<?php echo get_user_meta($user_id, 'product_training_custom_time', true); ?>" />
                        </div>
                        <div class="three_col last">
                            <input type="text" name="product_training_timezone" id="product_training_timezone" placeholder="Timezone" value="<?php echo get_user_meta($user_id, 'product_training_custom_timezone', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="full_col">
                            <label class="label-control require">Who will attend:</label> 
                        </div>
                        <?php if(get_user_meta($user_id, 'num_of_attendee', true)) {
                        	$num_of_attendee = get_user_meta($user_id, 'num_of_attendee', true);
							for($i=1; $i<=$num_of_attendee; $i++) {
							?>
                                <div class="three_col">                    	
                                    <input type="text" name="attend_<?php echo $i; ?>_name" id="attend_<?php echo $i; ?>_name" placeholder="Full Name" value="<?php echo get_user_meta($user_id, 'attendee_'.$i.'_name', true); ?>" />
                                </div>
                                <div class="three_col">
                                    <input type="text" name="attend_<?php echo $i; ?>_email" id="attend_<?php echo $i; ?>_email"  placeholder="Email Address" value="<?php echo get_user_meta($user_id, 'attendee_'.$i.'_email', true); ?>" />
                                </div>
                                <div class="three_col last">
                                </div>
                                <div class="clearfix"></div>
							<?php
							}
                        } else { ?>
                        <div class="three_col">                    	
                            <input type="text" name="attend_1_name" id="attend_1_name" placeholder="Full Name" />
                        </div>
                        <div class="three_col">
                            <input type="text" name="attend_1_email" id="attend_1_email"  placeholder="Email Address" />
                        </div>
                        <div class="three_col last">
                        </div>
                        <div class="clearfix"></div>
                        <?php } ?>
                        <span class="add_attendee"><span style="color:red">+</span> Add Another Attendee</span>
                        <?php if(get_user_meta($user_id, 'num_of_attendee', true)) { ?>
                        <input type="hidden" name="num_of_attendee" id="num_of_attendee" value="<?php echo get_user_meta($user_id, 'num_of_attendee', true); ?>" />
                        <?php } else { ?>
                        <input type="hidden" name="num_of_attendee" id="num_of_attendee" value="1" />
                        <?php } ?>
                        
                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-tab">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="schedule-training" />
                        <input type="hidden" name="schedule_training_progress" id="schedule_training_progress" value="<?php echo get_user_meta($user_id, 'schedule_training_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_6-->
                <?php } ?>
                
                
                <?php if($show_licensing_appointments != "No") { ?>
                <div class="company_info_steps company_info_step_9" id="licensing_tab1" data-step="1" data-tab="8" data-id-base="licensing_tab">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="license-appointment1" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Licensing & Appointments <span class="step_active">1</span> of 5</h1>
                        
                        <div class="line"></div>
                        
                        <div class="radio_group">
                            <label for="licensing_following_box" class="radio-control"><strong style="color:#3297DB;">If you are 1) not using travel protection or you 2) are using travel protection but are a Non US incorporated entity, you will not be required to complete this section. Simply check the box to the right:</strong> <input type="checkbox" id="licensing_following_box" class="following_box" name="licensing_following_box" value="If you are Non US incorporated entity, you will not be required to complete this section.  Simply check the box to the right:" <?php if(get_user_meta($user_id, 'licensing_following_box', true) == "If you are Non US incorporated entity, you will not be required to complete this section.  Simply check the box to the right:") echo 'checked="checked"'; ?> /></label>
                        </div>
                         <p>If your team is located in the United States, and you will be offering travel protection and desire to earn the full commission, it will require you to obtain a license from the Department of Insurance. Don't  worry, there are no tests.  Please provide the information below and we will take care of the rest.</p>
                         <p>&nbsp;</p>
                       
                        <div class="one_third">
                            <label for="la_1_security_number" class="label-control require">Social Security Number</label>
                            <input type="text" name="la_1_security_number" id="la_1_security_number" value="<?php echo get_user_meta($user_id, 'la_1_security_number', true); ?>" />
                        </div>
                        <div class="two_third et_column_last">
                            <label for="la_1_npn" class="label-control">If assigned, National Producer Number (NPN)</label>
                            <input type="text" name="la_1_npn" id="la_1_npn" value="<?php echo get_user_meta($user_id, 'la_1_assigned_npn', true); ?>">
                        </div>
                        <div class="clearfix"></div>
                        <div class="three_col">
                            <label for="la_1_first_name" class="label-control require">First Name</label>
                            <input type="text" name="la_1_first_name" id="la_1_first_name" value="<?php echo get_user_meta($user_id, 'la_1_first_name', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="la_1_middle_name" class="label-control">Middle Name</label>
                            <input type="text" name="la_1_middle_name" id="la_1_middle_name" value="<?php echo get_user_meta($user_id, 'la_1_middle_name', true); ?>" />
                        </div>
                        <div class="three_col last">
                            <label for="la_1_last_name" class="label-control require">Last Name</label>
                            <input type="text" name="la_1_last_name" id="la_1_last_name" value="<?php echo get_user_meta($user_id, 'la_1_last_name', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="one_third">
                            <label for="la_1_birth_day" class="label-control require">Date of Birth</label>
                            <input type="text" name="la_1_birth_day" id="la_1_birth_day" placeholder="DD-MM-YY" value="<?php echo get_user_meta($user_id, 'la_1_birth_day', true); ?>" />
                        </div>
                        <div class="two_third et_column_last">
                            <label for="la_1_home_address" class="label-control require">Residence/Home Address (Physical Street)</label>
                            <input type="text" name="la_1_home_address" id="la_1_home_address" placeholder="Address" value="<?php echo get_user_meta($user_id, 'la_1_home_address', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="three_col">
                            <label for="la_1_city" class="label-control require">City</label>
                            <input type="text" name="la_1_city" id="la_1_city" value="<?php echo get_user_meta($user_id, 'la_1_city', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="la_1_state" class="label-control require">State</label>
                            <input type="text" name="la_1_state" id="la_1_state" value="<?php echo get_user_meta($user_id, 'la_1_state', true); ?>" />
                        </div>
                        <div class="three_col last">
                            <label for="la_1_zip_code" class="label-control">Zip Code</label>
                            <input type="text" name="la_1_zip_code" id="la_1_zip_code" value="<?php echo get_user_meta($user_id, 'la_1_zip_code', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <!--<div class="three_col">
                            <label for="la_1_foreign_country" class="label-control">Foreign Country</label>
                            <input type="text" name="la_1_foreign_country" id="la_1_foreign_country" value="<?php //echo get_user_meta($user_id, 'la_1_foreign_country', true); ?>" />
                        </div>-->
                        <div class="one_half">
                            <label for="la_1_home_phone" class="label-control require">Home Phone Number</label>
                            <input type="tel" name="la_1_home_phone" id="la_1_home_phone" value="<?php echo get_user_meta($user_id, 'la_1_home_phone', true); ?>" />
                        </div>
                        <div class="one_half et_column_last">
                            <label for="la_1_gender" class="label-control require">Gender</label>
                            <select name="la_1_gender" id="la_1_gender">
                                <option value="">Please Select</option>
                                <option value="male" <?php if(get_user_meta($user_id, 'la_1_gender', true) == "male") echo 'selected="selected"'; ?>>Male</option>
                                <option value="female" <?php if(get_user_meta($user_id, 'la_1_gender', true) == "female") echo 'selected="selected"'; ?>>Female</option>
                            </select>
                            
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="one_half">
                            <div class="radio_group">
                                <label class="label-control require">Are you a Citizen of the United States?</label>
                                <label for="la_1_citizen1" class="radio-control"><input type="radio" name="la_1_citizen" id="la_1_citizen1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_citizen_usa', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_citizen2" class="radio-control"><input type="radio" name="la_1_citizen" id="la_1_citizen2" value="No" <?php if(get_user_meta($user_id, 'la_1_citizen_usa', true) == "No") echo 'checked="checked"'; ?> />No</label>
                            </div>
                        </div>
                        <div class="one_half et_column_last">
                            <label for="la_1_applicant_email" class="label-control require">Individual Applicant Email Address</label>
                            <input type="email" name="la_1_applicant_email" id="la_1_applicant_email" value="<?php echo get_user_meta($user_id, 'la_1_applicant_email', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col">
                            <label for="la_1_applicant_email" class="label-control">List your Insurance Agency Affiliations: Add (if any)</label>
                        </div>
                        <?php
						$num_of_agency_aff = get_user_meta($user_id, 'la_1_num_of_agency_aff', true);
						if($num_of_agency_aff) {
							for($i=1; $i<=$num_of_agency_aff; $i++) {
							?>
                            <div class="three_col">
                                <input type="text" name="la_1_FEIN_<?php echo $i; ?>" id="la_1_FEIN_<?php echo $i; ?>" placeholder="FEIN" value="<?php echo get_user_meta($user_id, 'la_1_FEIN_'.$i, true); ?>" />
                            </div>
                            <div class="three_col">
                                <input type="text" name="la_1_NPN_<?php echo $i; ?>" id="la_1_NPN_<?php echo $i; ?>" placeholder="NPN" value="<?php echo get_user_meta($user_id, 'la_1_NPN_'.$i, true); ?>" />
                            </div>
                            <div class="three_col last">
                                <input type="text" name="la_1_agency_name_<?php echo $i; ?>" id="la_1_agency_name_<?php echo $i; ?>" placeholder="Name of Agency" value="<?php echo get_user_meta($user_id, 'la_1_agency_name_'.$i, true); ?>" />
                            </div>
                            <div class="clearfix"></div>
                            <?php
							}
						} else {
						?>
                        <div class="three_col">
                            <input type="text" name="la_1_FEIN_1" id="la_1_FEIN_1" placeholder="FEIN" />
                        </div>
                        <div class="three_col">
                            <input type="text" name="la_1_NPN_1" id="la_1_NPN_1" placeholder="NPN" />
                        </div>
                        <div class="three_col last">
                            <input type="text" name="la_1_agency_name_1" id="la_1_agency_name_1" placeholder="Name of Agency" />
                        </div>
                        <div class="clearfix"></div>
                        <?php } ?>
                        <a class="add_more add-agency-affiliations" href="javascript:void(0);">Add Another Agency Affiliations</a>
                        <?php
						if($num_of_agency_aff) {
						?>
                        <input type="hidden" name="num_of_agency_aff" id="num_of_agency_aff" value="<?php echo $num_of_agency_aff; ?>" />
						<?php
						} else {
						?>
                        <input type="hidden" name="num_of_agency_aff" id="num_of_agency_aff" value="1" />
                        <?php } ?>
                        <div class="full_col">
                            <label class="label-control">Account for all time for the past five years. Give all employment experience starting with your current employer working back five years. Include full and part-time work, self-employment, military service, unemployment and full-time education. </label>
                        </div>
                        
                        <?php
						$num_of_employment = get_user_meta($user_id, 'la_1_num_of_employment', true);
						if($num_of_employment) {
							for($i=1; $i<=$num_of_employment; $i++) {
							?>
                            <div class="three_col">
                                <input type="text" name="la_1_employee_<?php echo $i; ?>_name" id="la_1_employee_<?php echo $i; ?>_name" value="<?php echo get_user_meta($user_id, 'la_1_employee_'.$i.'_name', true); ?>" placeholder="Name" />
                            </div>
                            <div class="three_col">
                                <input type="text" name="la_1_employee_<?php echo $i; ?>_city" id="la_1_employee_<?php echo $i; ?>_city" placeholder="City" value="<?php echo get_user_meta($user_id, 'la_1_employee_'.$i.'_city', true); ?>" />
                            </div>
                            <div class="three_col last">
                                <input type="text" name="la_1_employee_<?php echo $i; ?>_state" id="la_1_employee_<?php echo $i; ?>_state" placeholder="State" value="<?php echo get_user_meta($user_id, 'la_1_employee_'.$i.'_state', true); ?>" />
                            </div>
                            <div class="clearfix"></div>
                            
                            <!--<div class="three_col">
                                <input type="text" name="la_1_employee_<?php //echo $i; ?>_foreign_country" id="la_1_employee_<?php //echo $i; ?>_foreign_country" value="<?php //echo get_user_meta($user_id, 'la_1_employee_'.$i.'_foreign_country', true); ?>" placeholder="Foreign Country" />
                            </div>-->
                            <div class="three_col">
                                <input type="text" name="la_1_employee_<?php echo $i; ?>_from_month" id="la_1_employee_<?php echo $i; ?>_from_month" value="<?php echo get_user_meta($user_id, 'la_1_employee_'.$i.'_from_month', true); ?>" placeholder="From MM-YY" />
                            </div>
                            <div class="three_col">
                                <input type="text" name="la_1_employee_<?php echo $i; ?>_to_month" id="la_1_employee_<?php echo $i; ?>_to_month" placeholder="To MM-YY" value="<?php echo get_user_meta($user_id, 'la_1_employee_'.$i.'_to_month', true); ?>" />
                            </div>
                            <div class="three_col last">
                                <input type="text" name="la_1_employee_<?php echo $i; ?>_position_held" id="la_1_employee_<?php echo $i; ?>_position_held" value="<?php echo get_user_meta($user_id, 'la_1_employee_'.$i.'_position_held', true); ?>" placeholder="Position Held" />
                            </div>
                            <div class="clearfix"></div>
							<?php
							}
						} else {
						?>
                        <div class="three_col">
                            <input type="text" name="la_1_employee_1_name" id="la_1_employee_1_name" placeholder="Name" />
                        </div>
                        <div class="three_col">
                            <input type="text" name="la_1_employee_1_city" id="la_1_employee_1_city" placeholder="City" />
                        </div>
                        <div class="three_col last">
                            <input type="text" name="la_1_employee_1_state" id="la_1_employee_1_state" placeholder="State" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <!--<div class="three_col">
                            <input type="text" name="la_1_employee_1_foreign_country" id="la_1_employee_1_foreign_country" placeholder="Foreign Country" />
                        </div>-->
                        <div class="three_col">
                            <input type="text" name="la_1_employee_1_from_month" id="la_1_employee_1_from_month" placeholder="From MM-YY" />
                        </div>
                        <div class="three_col">
                            <input type="text" name="la_1_employee_1_to_month" id="la_1_employee_1_to_month" placeholder="To MM-YY" />
                        </div>
                        <div class="three_col last">
                            <input type="text" name="la_1_employee_1_position_held" id="la_1_employee_1_position_held" placeholder="Position Held" />
                        </div>
                        <div class="clearfix"></div>
                        <?php } ?>
                        <a class="add_more add-employment-experience" href="javascript:void(0);">Add Another Employment Experience</a>
                        <?php
						if($num_of_employment) {
						?>
                        <input type="hidden" name="num_of_employment" id="num_of_employment" value="<?php echo $num_of_employment; ?>" />
						<?php
						} else {
						?>
                        <input type="hidden" name="num_of_employment" id="num_of_employment" value="1" />
                        <?php } ?>
                        <div class="full_col" style="margin:10px 0;">
                            <h5 style="margin-bottom:15px"><strong>The Applicant must read very carefully and answer every question.</strong></h5>
                            
                            <label class="label-control"><strong>1a.</strong> Have you ever been convicted of a misdemeanor, had a judgment withheld or deferred, or are you currently charged with committing a misdemeanor?</label>
                            <label for="la_1_misdemeanor1" class="radio-control"><input type="radio" name="la_1_misdemeanor" id="la_1_misdemeanor1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_misdemeanor', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_misdemeanor2" class="radio-control"><input type="radio" name="la_1_misdemeanor" id="la_1_misdemeanor2" value="No" <?php if(get_user_meta($user_id, 'la_1_misdemeanor', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="discription">You may exclude misdemeanor convictions or pending misdemeanor charges: traffic citations, driving under the influence (DUI), driving while intoxicated (DWI), driving without a license, reckless driving, or driving with a suspended or revoked license. You may also exclude juvenile adjudications (offenses where you were adjudicated delinquent in a juvenile court)</div><!--discription-->
                        
                        <div class="full_col" style="margin:0 0 10px;">                       
                            <label class="label-control"><strong>1b.</strong> Have you ever been convicted of a felony, had a judgment withheld or deferred, or are you currently charged with committing a felony?</label>
                            <label for="la_1_felony1" class="radio-control"><input type="radio" name="la_1_felony" id="la_1_felony1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_felony', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_felony2" class="radio-control"><input type="radio" name="la_1_felony" id="la_1_felony2" value="No" <?php if(get_user_meta($user_id, 'la_1_felony', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="discription">You may exclude juvenile adjudications (offenses where you were adjudicated delinquent in a juvenile court)</div><!--discription-->
                        
                        <div class="radio_group">
                            <label class="label-control">If you have a felony conviction involving dishonesty or breach of trust, have you applied for written consent to engage in the business of insurance in your home state as required by 18 USC 1033?</label>
                            <label for="la_1_felony_conviction1" class="radio-control"><input type="radio" name="la_1_felony_conviction" id="la_1_felony_conviction1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_felony_conviction', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_felony_conviction2" class="radio-control"><input type="radio" name="la_1_felony_conviction" id="la_1_felony_conviction2" value="No" <?php if(get_user_meta($user_id, 'la_1_felony_conviction', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        
                        <div class="radio_group">
                            <label class="label-control">If so, was consent granted? (Attach copy of 1033 consent approved by home state.)</label>
                            <label for="la_1_consent_granted1" class="radio-control"><input type="radio" name="la_1_consent_granted" id="la_1_consent_granted1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_consent_granted', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_consent_granted2" class="radio-control"><input type="radio" name="la_1_consent_granted" id="la_1_consent_granted2" value="No" <?php if(get_user_meta($user_id, 'la_1_consent_granted', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="full_col" style="margin-bottom:10px;">
                            <label class="label-control"><strong>2.</strong> Have you ever been named or involved as a party in an administrative proceeding, including FINRA sanction or arbitration proceeding regarding any professional or occupational license or registration?</label>
                            <label for="la_1_occupational_license1" class="radio-control"><input type="radio" name="la_1_occupational_license" id="la_1_occupational_license1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_occupational_license', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_occupational_license2" class="radio-control"><input type="radio" name="la_1_occupational_license" id="la_1_occupational_license2" value="No" <?php if(get_user_meta($user_id, 'la_1_occupational_license', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="discription">“Involved” means having a license censured, suspended, revoked, canceled, terminated; or, being assessed a fine, a cease and desist order, a prohibition order, a compliance order, placed on probation, sanctioned or surrendering a license to resolve an administrative action. “Involved” also means being named as a party to an administrative or arbitration proceeding, which is related to a professional or occupational license, or registration. “Involved” also means having a license, or registration application denied or the act of withdrawing an application to avoid a denial. INCLUDE any business so named because of your actions in your capacity as an owner, partner, officer or director, or member or manager of a Limited Liability Company. You may EXCLUDE terminations due solely to noncompliance with continuing education requirements or failure to pay a renewal fee.</div><!--discription-->
                        
                        <div class="full_col">
                            <h5><strong>If you answer yes, you must attach to this application:</strong></h5>
                            <ul>
                                <li><strong>a)</strong> a written statement identifying the type of license and explaining the circumstances of each incident,</li>
                                <li><strong>b)</strong> a copy of the Notice of Hearing or other document that states the charges and allegations, and</li>
                                <li><strong>c)</strong> a copy of the official document, which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
                        </div>
                        <div class="two_third">
                            <label for="la_1_applicant_comment" class="label-control">Comment</label>
                            <textarea name="la_1_applicant_comment" id="la_1_applicant_comment" rows="2"><?php echo get_user_meta($user_id, 'la_1_applicant_comment', true); ?></textarea>
                        </div>
                        <div class="one_third et_column_last">
                        <label for="la_1_applicant_file" class="label-control">Upload File</label>
                        <input type="file" name="la_1_applicant_file" id="la_1_applicant_file" />
                        <?php
                        	$applicant_file1 = get_user_meta($user_id, 'la_1_applicant_file', true);
							if($applicant_file1) {
								$filename1 = basename ( get_attached_file( $applicant_file1 ) );
								echo '<br>'.$filename1;
							}
						?>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="radio_group">
                            <label class="label-control"><strong>3.</strong> Has any demand been made or judgment rendered against you or any business in which you are or were an owner, partner, officer or director, or member or manager of a limited liability company, for overdue monies by an insurer, insured or producer, or have you ever been subject to a bankruptcy proceeding? Do not include personal bankruptcies, unless they involve funds held on behalf of others.</label>
                            
                            <label for="la_1_demand1" class="radio-control"><input type="radio" name="la_1_demand" id="la_1_demand1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_demand', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_demand2" class="radio-control"><input type="radio" name="la_1_demand" id="la_1_demand2" value="No" <?php if(get_user_meta($user_id, 'la_1_demand', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="full_col">
                            <label for="la_1_answer_submit" class="label-control">If you answer yes, submit a statement summarizing the details of the indebtedness and arrangements for repayment, and/or type and location of bankruptcy.</label>
                            <textarea name="la_1_answer_submit" id="la_1_answer_submit" rows="2"><?php echo get_user_meta($user_id, 'la_1_answer_submit', true); ?></textarea>
                        </div>
                        
                        <div class="radio_group">
                            <label class="label-control">Have you been notified by any jurisdiction to which you are applying of any delinquent tax obligation that is not the subject of a repayment agreement?</label>
                            
                            <label for="la_1_repayment_agreement1" class="radio-control"><input type="radio" name="la_1_repayment_agreement" id="la_1_repayment_agreement1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_repayment_agreement', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_repayment_agreement2" class="radio-control"><input type="radio" name="la_1_repayment_agreement" id="la_1_repayment_agreement2" value="No" <?php if(get_user_meta($user_id, 'la_1_repayment_agreement', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="full_col">
                            <label for="la_1_answer_jurisdiction" class="label-control">If you answer yes, identify the jurisdiction(s):</label>
                            <input type="text" name="la_1_answer_jurisdiction" id="la_1_answer_jurisdiction" value="<?php echo get_user_meta($user_id, 'la_1_answer_jurisdiction', true); ?>" />
                        </div>
                        
                        <div class="radio_group">
                            <label class="label-control"><strong>5.</strong> Are you currently a party to, or have you ever been found liable in, any lawsuit, arbitrations or mediation proceeding involving allegations of fraud, misappropriation or conversion of funds, misrepresentation or breach of fiduciary duty?</label>
                            
                            <label for="la_1_iduciary_duty1" class="radio-control"><input type="radio" name="la_1_iduciary_duty" id="la_1_iduciary_duty1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_iduciary_duty', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_iduciary_duty2" class="radio-control"><input type="radio" name="la_1_iduciary_duty" id="la_1_iduciary_duty2" value="No" <?php if(get_user_meta($user_id, 'la_1_iduciary_duty', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        
                        <div class="full_col">
                            <h5><strong>If you answer yes, you must attach to this application:</strong></h5>
                            <ul>
                                <li><strong>a)</strong> a written statement summarizing the details of each incident,</li>
                                <li><strong>b)</strong> a copy of the Petition, Complaint or other document that commenced the lawsuit or arbitration, or mediation proceedings, and</li>
                                <li><strong>c)</strong> a copy of the official documents, which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
                        </div>
                        <div class="two_third">
                            <label for="la_1_applicant2_comment" class="label-control">Comment</label>
                            <textarea name="la_1_applicant2_comment" id="la_1_applicant2_comment" rows="2"><?php echo get_user_meta($user_id, 'la_1_applicant2_comment', true); ?></textarea>
                        </div>
                        <div class="one_third et_column_last">
                        <label for="la_1_applicant2_file" class="label-control">Upload File</label>
                        <input type="file" name="la_1_applicant2_file" id="la_1_applicant2_file" />
                        <?php
                        	$applicant_file2 = get_user_meta($user_id, 'la_1_applicant2_file', true);
							if($applicant_file2) {
								$filename2 = basename ( get_attached_file( $applicant_file2 ) );
								echo '<br>'.$filename2;
							}
						?>
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <div class="radio_group">
                            <label class="label-control"><strong>6.</strong> Have you or any business in which you are or were an owner, partner, officer or director, or member or manager of a limited liability company, ever had an insurance agency contract or any other business relationship with an insurance company terminated for any alleged misconduct?</label>
                            
                            <label for="la_1_alleged_misconduct1" class="radio-control"><input type="radio" name="la_1_alleged_misconduct" id="la_1_alleged_misconduct1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_alleged_misconduct', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_alleged_misconduct2" class="radio-control"><input type="radio" name="la_1_alleged_misconduct" id="la_1_alleged_misconduct2" value="No" <?php if(get_user_meta($user_id, 'la_1_alleged_misconduct', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="full_col">
                            <h5><strong>If you answer yes, you must attach to this application:</strong></h5>
                            <ul>
                                <li><strong>a)</strong> a written statement summarizing the details of each incident and explaining why you feel this incident should not prevent you from receiving an insurance license, and</li>
                                <li><strong>b)</strong> copies of all relevant documents.</li>
                            </ul>
                        </div>
                        <div class="two_third">
                            <label for="la_1_applicant3_comment" class="label-control">Comment</label>
                            <textarea name="la_1_applicant3_comment" id="la_1_applicant3_comment" rows="2"><?php echo get_user_meta($user_id, 'la_1_applicant3_comment', true); ?></textarea>
                        </div>
                        <div class="one_third et_column_last">
                            <label for="la_1_applicant3_file" class="label-control">Upload File</label>
                            <input type="file" name="la_1_applicant3_file" id="la_1_applicant3_file" />
							<?php
                                $applicant_file3 = get_user_meta($user_id, 'la_1_applicant3_file', true);
                                if($applicant_file3) {
                                    $filename3 = basename ( get_attached_file( $applicant_file3 ) );
                                    echo '<br>'.$filename3;
                                }
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        
                        <div class="radio_group">
                            <label class="label-control"><strong>7.</strong> Do you have a child support obligation in arrearage?</label>
                            
                            <label for="la_1_obligation_arrearage1" class="radio-control"><input type="radio" name="la_1_obligation_arrearage" id="la_1_obligation_arrearage1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_obligation_arrearage', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_obligation_arrearage2" class="radio-control"><input type="radio" name="la_1_obligation_arrearage" id="la_1_obligation_arrearage2" value="No" <?php if(get_user_meta($user_id, 'la_1_obligation_arrearage', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        
                        <div class="full_col">
                            <h5><strong>If you answer yes,</strong></h5>
                            <ul>
                                <li><strong>a)</strong> by how many months are you in arrearage?</li>
                                <li><strong>b)</strong> are you currently subject to and in compliance with any repayment agreement?</li>
                                <li><strong>c)</strong> are you the subject of a child support related subpoena/warrant? (If you answered yes, provide documentation showing proof of current payments or an approved repayment plan from the appropriate state child support agency.)</li>
                            </ul>
                        </div>
                        <div class="two_third">
                            <label for="la_1_applicant4_comment" class="label-control">Comment</label>
                            <textarea name="la_1_applicant4_comment" id="la_1_applicant4_comment" rows="2"><?php echo get_user_meta($user_id, 'la_1_applicant4_comment', true); ?></textarea>
                        </div>
                        <div class="one_third et_column_last">
                            <label for="la_1_applicant4_file" class="label-control">Upload File</label>
                            <input type="file" name="la_1_applicant4_file" id="la_1_applicant4_file" />
							<?php
                                $applicant_file4 = get_user_meta($user_id, 'la_1_applicant4_file', true);
                                if($applicant_file4) {
                                    $filename4 = basename ( get_attached_file( $applicant_file4 ) );
                                    echo '<br>'.$filename4;
                                }
                            ?>
                        </div>
                        <div class="clearfix"></div>                   
                        
                        <div class="radio_group">
                            <label class="label-control"><strong>8.</strong> In response to a “yes” answer to one or more of the Background Questions for this application, are you submitting document(s) to the NAIC/NIPR Attachments Warehouse?</label>
                            
                            <label for="la_1_warehouse1" class="radio-control"><input type="radio" name="la_1_warehouse" id="la_1_warehouse1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_warehouse', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_warehouse2" class="radio-control"><input type="radio" name="la_1_warehouse" id="la_1_warehouse2" value="No" <?php if(get_user_meta($user_id, 'la_1_warehouse', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="full_col">
                            <h5><strong>If you answer yes</strong></h5>
                            <label class="label-control">Will you be associating (linking) previously filed documents from the NAIC/NIPR Attachments Warehouse to this application?</label>
                            
                            <label for="la_1_NIPR_Attachments1" class="radio-control"><input type="radio" name="la_1_NIPR_Attachments" id="la_1_NIPR_Attachments1" value="Yes" <?php if(get_user_meta($user_id, 'la_1_associating_nipr_attachments', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_1_NIPR_Attachments2" class="radio-control"><input type="radio" name="la_1_NIPR_Attachments" id="la_1_NIPR_Attachments2" value="No" <?php if(get_user_meta($user_id, 'la_1_associating_nipr_attachments', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="discription">Note: If you have previously submitted documents to the Attachments Warehouse that are intended to be filed with this application, you must go to the Attachments Warehouse and associate (link) the supporting document(s) to this application based upon the particular background question number you have answered yes to on this application. You will receive information in a follow-up page at the end of the application process, providing a link to the Attachment Warehouse instructions.</div>
                        
                        <div class="full_col">
                            <label class="label-control"><strong>9.</strong> I authorize InsureStays and/or other affiliated insurance agency of RentalGuardian to acquire these licenses on my behalf based on the information I have truthfully provided in this web form.</label>
                            <input type="text" name="la_1_authorize_insurestays" id="la_1_authorize_insurestays" value="<?php echo get_user_meta($user_id, 'la_1_authorize_insurestays', true); ?>" style="margin-bottom: 0;" />
                            (If you agree, please type in your name for authorization)
                        </div>
                        
                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-tab">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-step">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="licensing-appointments-step1" />
                        <input type="hidden" name="licensing_appointments_step1_progress" id="licensing_appointments_step1_progress" value="<?php echo get_user_meta($user_id, 'licensing_appointments_step1_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_9-->                
                
                
                <div class="company_info_steps company_info_step_9" id="licensing_tab2" data-step="2" data-id-base="licensing_tab" style="display:none;">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="license-appointment2" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Licensing & Appointments <span class="step_active">2</span> of 5</h1>
                            
                        <div class="line"></div>
                        
                        <div class="one_third">
                            <label for="la_2_incorporation_date" class="label-control require">Incorporation/Formation Date </label>
                            <input name="la_2_incorporation_date" id="la_2_incorporation_date" type="text" placeholder="DD-MM-YY" value="<?php echo get_user_meta($user_id, 'la_2_incorporation_date', true); ?>" />
                        </div>
                        <div class="two_third et_column_last">
                            <div class="radio_group">
                                <label class="label-control">Is the business entity affiliated with a financial institution/bank?</label>
                                
                                <label for="la_2_financial_institution1" class="radio-control"><input name="la_2_financial_institution" id="la_2_financial_institution1" type="radio" value="Yes" <?php if(get_user_meta($user_id, 'la_2_affiliated_financial_institution', true) == "Yes") echo 'checked="checked"'; ?>>Yes</label>
                                <label for="la_2_financial_institution2" class="radio-control"><input name="la_2_financial_institution" id="la_2_financial_institution2" type="radio" value="No" <?php if(get_user_meta($user_id, 'la_2_affiliated_financial_institution', true) == "No") echo 'checked="checked"'; ?>>No</label>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="full_col">
                            <label for="la_2_business_address" class="label-control require">Business Address</label>
                            <input type="text" id="la_2_business_address" name="la_2_business_address" value="<?php echo get_user_meta($user_id, 'la_2_business_address', true); ?>" />
                        </div>
                        
                        <div class="full_col">
                            <label class="label-control require">Identify at least one Designated/Responsible Licensed Producer responsible for the business entity’s compliance with the insurance laws, rules and regulations of this state. (Typically same person as Step 1 of Licensing Form)</label>
                        </div>
                        <div class="one_half">
                            <input name="la_2_license_name" id="la_2_license_name" placeholder="Name" type="text" value="<?php echo get_user_meta($user_id, 'la_2_license_name', true); ?>" />
                        </div>
                        <div class="one_half et_column_last">
                            <input name="la_2_license_ssn" id="la_2_license_ssn" placeholder="SSN" type="text" value="<?php echo get_user_meta($user_id, 'la_2_license_ssn', true); ?>" />
                        </div>
                        <!--<div class="three_col last">
                            <input name="la_2_license_npn" id="la_2_license_npn" placeholder="NPN " type="text" value="<?php //echo get_user_meta($user_id, 'la_2_license_npn', true); ?>" />
                        </div>-->
                        <div class="clearfix"></div>
                        
                        
                        <div class="full_col">
                            <label class="label-control require">Identify all owners with 10% or more voting interest:</label>
                        </div>
                        <?php
                        $num_of_owners = get_user_meta($user_id, 'la_2_num_of_owners', true);
						if($num_of_owners) {
							for($i=1; $i<=$num_of_owners; $i++) {
								?>
                                <div class="three_col">
                                    <input name="la_2_entity_<?php echo $i; ?>_name" id="la_2_entity_<?php echo $i; ?>_name" placeholder="Name" type="text" value="<?php echo get_user_meta($user_id, 'la_2_entity_'.$i.'_name', true); ?>" />
                                </div>
                                <div class="three_col">
                                    <input name="la_2_entity_<?php echo $i; ?>_title" id="la_2_entity_<?php echo $i; ?>_title" placeholder="Title" type="text" value="<?php echo get_user_meta($user_id, 'la_2_entity_'.$i.'_title', true); ?>" />
                                </div>
                                <div class="three_col last">
                                    <input name="la_2_entity_<?php echo $i; ?>_ssn" id="la_2_entity_<?php echo $i; ?>_ssn" placeholder="SSN/Fein " type="text" value="<?php echo get_user_meta($user_id, 'la_2_entity_'.$i.'_ssn', true); ?>" />
                                </div>
                                <div class="clearfix"></div>
                                <div class="three_col">
                                    <label for="la_2_entity_<?php echo $i; ?>_birth_day" class="label-control">Date of Birth</label>
                                    <input type="text" name="la_2_entity_<?php echo $i; ?>_birth_day" id="la_2_entity_<?php echo $i; ?>_birth_day" placeholder="DD-MM-YY" value="<?php echo get_user_meta($user_id, 'la_2_entity_'.$i.'_birth_day', true); ?>" />
                                </div>
                                <div class="three_col">
                                    <label for="la_2_entity_<?php echo $i; ?>_interest_rate" class="label-control">% of ownership interest</label>
                                    <input name="la_2_entity_<?php echo $i; ?>_interest_rate" id="la_2_entity_<?php echo $i; ?>_interest_rate" placeholder="% of ownership interest" type="text" value="<?php echo get_user_meta($user_id, 'la_2_entity_'.$i.'_interest_rate', true); ?>" />
                                </div>
                                <div class="three_col last">&nbsp;</div>
                                <div class="clearfix"></div>
                                <?php
							}
						} else {
						?>
                        <div class="three_col">
                            <input name="la_2_entity_1_name" id="la_2_entity_1_name" placeholder="Name" type="text" value="<?php echo get_user_meta($user_id, 'la_2_entity_1_name', true); ?>" />
                        </div>
                        <div class="three_col">
                            <input name="la_2_entity_1_title" id="la_2_entity_1_title" placeholder="Title" type="text" value="<?php echo get_user_meta($user_id, 'la_2_entity_1_title', true); ?>" />
                        </div>
                        <div class="three_col last">
                            <input name="la_2_entity_1_ssn" id="la_2_entity_1_ssn" placeholder="SSN/Fein " type="text" value="<?php echo get_user_meta($user_id, 'la_2_entity_1_ssn', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="three_col">
                            <label for="la_2_entity_1_birth_day" class="label-control require">Date of Birth</label>
                            <input type="text" name="la_2_entity_1_birth_day" id="la_2_entity_1_birth_day" placeholder="DD-MM-YY" value="<?php echo get_user_meta($user_id, 'la_2_entity_1_birth_day', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="la_2_entity_1_interest_rate" class="label-control require">% of ownership interest</label>
                            <input name="la_2_entity_1_interest_rate" id="la_2_entity_1_interest_rate" placeholder="% of ownership interest" type="text" value="<?php echo get_user_meta($user_id, 'la_2_entity_1_interest_rate', true); ?>" />
                        </div>
                        <div class="three_col last">&nbsp;</div>
                        <div class="clearfix"></div>
                        <?php } ?>
                        <a class="add_more add-identify-owners" href="javascript:void(0);">Add More</a>
                        <?php
						if($num_of_owners) {
						?>
                        <input type="hidden" name="num_of_owners" id="num_of_owners" value="<?php echo $num_of_owners; ?>" />
						<?php
						} else {
						?>
                        <input type="hidden" name="num_of_owners" id="num_of_owners" value="1" />
                        <?php } ?>
                        <!--<input type="hidden" name="num_of_owners" id="num_of_owners" value="1" />-->

                        <!--<div class="three_col">
                            <label for="la_2_entity_1_dob" class="label-control require">DOB</label>
                            <input name="la_2_entity_1_dob" id="la_2_entity_1_dob" placeholder="DD-MM-YY" type="text" value="<?php //echo get_user_meta($user_id, 'la_2_entity_1_dob', true); ?>" />
                        </div>
                        <div class="three_col">
                            <div class="radio_group">
                            <label class="label-control require">Owner Yes/No</label>
                            <label for="la_2_entity_1_owner1" class="radio-control"><input name="la_2_entity_1_owner" id="la_2_entity_1_owner1" type="radio" value="Yes" <?php //if(get_user_meta($user_id, 'la_2_entity_1_owner', true) == "Yes") echo 'checked="checked"'; ?>>Yes</label>
                            <label for="la_2_entity_1_owner2" class="radio-control"><input name="la_2_entity_1_owner" id="la_2_entity_1_owner2" type="radio" value="No" <?php //if(get_user_meta($user_id, 'la_2_entity_1_owner', true) == "No") echo 'checked="checked"'; ?>>No</label>
                            </div>
                        </div>-->
                        
                        <!--<div class="one_third">
                            <label for="la_2_birth_day" class="label-control require">Date of Birth</label>
                            <input type="text" name="la_2_birth_day" id="la_2_birth_day" placeholder="DD-MM-YY" value="<?php //echo get_user_meta($user_id, 'la_2_birth_day', true); ?>" />
                        </div>
                        <div class="two_third et_column_last">
                            <label for="la_2_home_address" class="label-control require">Residence/Home Address (Physical Street)</label>
                            <input type="text" name="la_2_home_address" id="la_2_home_address" placeholder="Address" value="<?php //echo get_user_meta($user_id, 'la_2_home_address', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="three_col">
                            <label for="la_2_city" class="label-control require">City</label>
                            <input type="text" name="la_2_city" id="la_2_city" value="<?php //echo get_user_meta($user_id, 'la_2_city', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="la_2_state" class="label-control require">State</label>
                            <input type="text" name="la_2_state" id="la_2_state" value="<?php //echo get_user_meta($user_id, 'la_2_state', true); ?>" />
                        </div>
                        <div class="three_col last">
                            <label for="la_2_zip_code" class="label-control">Zip Code</label>
                            <input type="text" name="la_2_zip_code" id="la_2_zip_code" value="<?php //echo get_user_meta($user_id, 'la_2_zip_code', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <div class="three_col">
                            <label for="la_2_foreign_country" class="label-control">Foreign Country</label>
                            <input type="text" name="la_2_foreign_country" id="la_2_foreign_country" value="<?php //echo get_user_meta($user_id, 'la_2_foreign_country', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="la_2_home_phone" class="label-control require">Home Phone Number</label>
                            <input type="tel" name="la_2_home_phone" id="la_2_home_phone" value="<?php //echo get_user_meta($user_id, 'la_2_home_phone', true); ?>" />
                        </div>
                        <div class="three_col last">
                            <label for="la_2_gender" class="label-control require">Gender</label>
                            <select name="la_2_gender" id="la_2_gender">
                                <option value="">Please Select</option>
                                <option value="male" <?php //if(get_user_meta($user_id, 'la_2_gender', true) == "male") echo 'selected="selected"'; ?>>Male</option>
                                <option value="female" <?php //if(get_user_meta($user_id, 'la_2_gender', true) == "female") echo 'selected="selected"'; ?>>Female</option>
                            </select>
                            
                        </div>
                        <div class="clearfix"></div>-->
                        
                        <!--<div class="one_half">
                            <div class="radio_group">
                                <label class="label-control require">Are you a Citizen of the United States?</label>
                                <label for="la_2_citizen1" class="radio-control"><input type="radio" name="la_2_citizen" id="la_2_citizen1" value="Yes" <?php //if(get_user_meta($user_id, 'la_2_citizen_usa', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_2_citizen2" class="radio-control"><input type="radio" name="la_2_citizen" id="la_2_citizen2" value="No" <?php //if(get_user_meta($user_id, 'la_2_citizen_usa', true) == "No") echo 'checked="checked"'; ?> />No</label>
                            </div>
                        </div>
                        <div class="one_half et_column_last">
                            <label for="la_2_applicant_email" class="label-control require">Individual Applicant Email Address</label>
                            <input type="email" name="la_2_applicant_email" id="la_2_applicant_email" value="<?php //echo get_user_meta($user_id, 'la_2_applicant_email', true); ?>" />
                        </div>
                        <div class="clearfix"></div>-->
                        
                        <!--<div class="full_col">
                            <label for="la_2_applicant_email" class="label-control require">List your Insurance Agency Affiliations</label>
                        </div>
                        <?php
                        /*$num_of_agency_aff = get_user_meta($user_id, 'la_2_num_of_agency_aff', true);
						if($num_of_agency_aff) {
							for($i=1; $i<=$num_of_agency_aff; $i++) {*/
							?>
                                <div class="three_col">
                                    <input type="text" name="la_2_FEIN_<?php //echo $i; ?>" id="la_2_FEIN_<?php //echo $i; ?>" placeholder="FEIN" value="<?php //echo get_user_meta($user_id, 'la_2_FEIN_'.$i, true); ?>" />
                                </div>
                                <div class="three_col">
                                    <input type="text" name="la_2_NPN_<?php //echo $i; ?>" id="la_2_NPN_<?php //echo $i; ?>" placeholder="NPN" value="<?php //echo get_user_meta($user_id, 'la_2_NPN_'.$i, true); ?>" />
                                </div>
                                <div class="three_col last">
                                    <input type="text" name="la_2_agency_name_<?php //echo $i; ?>" id="la_2_agency_name_<?php //echo $i; ?>" placeholder="Name of Agency" value="<?php //echo get_user_meta($user_id, 'la_2_agency_name_'.$i, true); ?>" />
                                </div>
                                <div class="clearfix"></div>
							<?php
							/*}
						} else {*/
						?>
                        <div class="three_col">
                            <input type="text" name="la_2_FEIN_1" id="la_2_FEIN_1" placeholder="FEIN" />
                        </div>
                        <div class="three_col">
                            <input type="text" name="la_2_NPN_1" id="la_2_NPN_1" placeholder="NPN" />
                        </div>
                        <div class="three_col last">
                            <input type="text" name="la_2_agency_name_1" id="la_2_agency_name_1" placeholder="Name of Agency" />
                        </div>
                        <div class="clearfix"></div>
                        <?php //} ?>
                        <a class="add_more add-agency-affiliations" href="javascript:void(0);">Add Another Agency Affiliations</a>
                        <?php
						//if($num_of_agency_aff) {
						?>
                        <input type="hidden" name="num_of_agency_aff" id="num_of_agency_aff2" value="<?php //echo $num_of_agency_aff; ?>" />
						<?php
						//} else {
						?>
                        <input type="hidden" name="num_of_agency_aff" id="num_of_agency_aff2" value="1" />
                        <?php //} ?>
                        <div class="full_col">
                            <label class="label-control">Account for all time for the past five years. Give all employment experience starting with your current employer working back five years. Include full and part-time work, self-employment, military service, unemployment and full-time education. </label>
                        </div>
                        
                        <?php
                        /*$num_of_employment = get_user_meta($user_id, 'la_2_num_of_employment', true);
						if($num_of_employment) {
							for($i=1; $i<=$num_of_employment; $i++) {*/
							?>
                            <div class="three_col">
                                <input type="text" name="la_2_employee_<?php //echo $i; ?>_name" id="la_2_employee_<?php //echo $i; ?>_name" placeholder="Name" value="<?php //echo get_user_meta($user_id, 'la_2_employee_'.$i.'_name', true); ?>" />
                            </div>
                            <div class="three_col">
                                <input type="text" name="la_2_employee_<?php //echo $i; ?>_city" id="la_2_employee_<?php //echo $i; ?>_city" placeholder="City" value="<?php //echo get_user_meta($user_id, 'la_2_employee_'.$i.'_city', true); ?>" />
                            </div>
                            <div class="three_col last">
                                <input type="text" name="la_2_employee_<?php //echo $i; ?>_state" id="la_2_employee_<?php //echo $i; ?>_state" placeholder="State" value="<?php //echo get_user_meta($user_id, 'la_2_employee_'.$i.'_state', true); ?>" />
                            </div>
                            <div class="clearfix"></div>
                            
                            <div class="three_col">
                                <input type="text" name="la_2_employee_<?php //echo $i; ?>_foreign_country" id="la_2_employee_<?php //echo $i; ?>_foreign_country" placeholder="Foreign Country" value="<?php //echo get_user_meta($user_id, 'la_2_employee_'.$i.'_foreign_country', true); ?>" />
                            </div>
                            <div class="three_col">
                                <input type="text" name="la_2_employee_<?php //echo $i; ?>_from_month" id="la_2_employee_<?php //echo $i; ?>_from_month" placeholder="From MM-YY" value="<?php //echo get_user_meta($user_id, 'la_2_employee_'.$i.'_from_month', true); ?>" />
                            </div>
                            <div class="three_col last">
                                <input type="text" name="la_2_employee_<?php //echo $i; ?>_to_month" id="la_2_employee_<?php //echo $i; ?>_to_month" placeholder="To MM-YY" value="<?php //echo get_user_meta($user_id, 'la_2_employee_'.$i.'_to_month', true); ?>" />
                            </div>
                            <div class="clearfix"></div>
                            <div class="full_col">
                                <input type="text" name="la_2_employee_<?php //echo $i; ?>_position_held" id="la_2_employee_<?php //echo $i; ?>_position_held" placeholder="Position Held" value="<?php //echo get_user_meta($user_id, 'la_2_employee_'.$i.'_position_held', true); ?>" />
                            </div>
                            <?php
							/*}
						} else {*/
						?>
                        <div class="three_col">
                            <input type="text" name="la_2_employee_1_name" id="la_2_employee_1_name" placeholder="Name" />
                        </div>
                        <div class="three_col">
                            <input type="text" name="la_2_employee_1_city" id="la_2_employee_1_city" placeholder="City" />
                        </div>
                        <div class="three_col last">
                            <input type="text" name="la_2_employee_1_state" id="la_2_employee_1_state" placeholder="State" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="three_col">
                            <input type="text" name="la_2_employee_1_foreign_country" id="la_2_employee_1_foreign_country" placeholder="Foreign Country" />
                        </div>
                        <div class="three_col">
                            <input type="text" name="la_2_employee_1_from_month" id="la_2_employee_1_from_month" placeholder="From MM-YY" />
                        </div>
                        <div class="three_col last">
                            <input type="text" name="la_2_employee_1_to_month" id="la_2_employee_1_to_month" placeholder="To MM-YY" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="full_col">
                            <input type="text" name="la_2_employee_1_position_held" id="la_2_employee_1_position_held" placeholder="Position Held" />
                        </div>
                        <?php //} ?>
                        <a class="add_more add-employment-experience" href="javascript:void(0);">Add Another Employment Experience</a>
                        <?php
						//if($num_of_employment) {
						?>
                        <input type="hidden" name="num_of_employment" id="num_of_employment2" value="<?php //echo $num_of_employment; ?>" />
						<?php
						//} else {
						?>
                        <input type="hidden" name="num_of_employment" id="num_of_employment2" value="1" />
                        <?php //} ?>-->
                        <div class="full_col">
                            <label class="label-control"><strong>1a.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, ever been convicted of a misdemeanor, had a judgment withheld or deferred or is the business entity or any owner, partner, officer or director of the business entity, or member or manager currently charged with, committing a misdemeanor? </label>
                            <label for="la_2_business_entity1" class="radio-control"><input type="radio" name="la_2_business_entity" id="la_2_business_entity1" value="Yes" <?php if(get_user_meta($user_id, 'la_2_business_entity', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_2_business_entity2" class="radio-control"><input type="radio" name="la_2_business_entity" id="la_2_business_entity2" value="No" <?php if(get_user_meta($user_id, 'la_2_business_entity', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="discription">You may exclude misdemeanor convictions or pending misdemeanor charges: traffic citations, driving under the influence (DUI) or driving while intoxicated (DWI), driving without a license, reckless driving, or driving with a suspended or revoked license. You may also exclude juvenile adjudications (offenses where you were adjudicated delinquent in juvenile court.) </div><!--discription-->
                        
                        <div class="full_col" style="margin:0 0 10px;">                       
                            <label class="label-control"><strong>1b.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company ever been convicted of a felony, had judgment withheld or deferred, or is the business entity or any owner, partner, officer or director of the business entity or member or manager of a limited liability company currently charged with committing a felony? </label>
                            <label for="la_2_business_entity_owner1" class="radio-control"><input type="radio" name="la_2_business_entity_owner" id="la_2_business_entity_owner1" value="Yes" <?php if(get_user_meta($user_id, 'la_2_business_entity_owner', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="la_2_business_entity_owner2" class="radio-control"><input type="radio" name="la_2_business_entity_owner" id="la_2_business_entity_owner2" value="No" <?php if(get_user_meta($user_id, 'la_2_business_entity_owner', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="discription">You may exclude juvenile adjudications (offenses where you were adjudicated delinquent in a juvenile court.)</div><!--discription-->
                        
                        <div class="radio_group">
                            <label class="label-control">If you have a felony conviction involving dishonesty or breach of trust, have you applied for written consent to engage in the business of insurance in your home state as required by 18 USC 1033?</label>
                            <label for="la_2_felony_conviction1" class="radio-control"><input type="radio" name="la_2_felony_conviction" id="la_2_felony_conviction1" value="Yes" <?php if(get_user_meta($user_id, 'la_2_felony_conviction', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_2_felony_conviction2" class="radio-control"><input type="radio" name="la_2_felony_conviction" id="la_2_felony_conviction2" value="No" <?php if(get_user_meta($user_id, 'la_2_felony_conviction', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        
                        <div class="radio_group">
                            <label class="label-control">If so, was consent granted? (Attach copy of 1033 consent approved by home state.)</label>
                            <div class="three_col"><label for="la_2_consent_granted1" class="radio-control"><input type="radio" name="la_2_consent_granted" id="la_2_consent_granted1" value="Yes" <?php if(get_user_meta($user_id, 'la_2_consent_granted', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_2_consent_granted2" class="radio-control"><input type="radio" name="la_2_consent_granted" id="la_2_consent_granted2" value="No" <?php if(get_user_meta($user_id, 'la_2_consent_granted', true) == "No") echo 'checked="checked"'; ?> />No</label>
                            </div>
                            <div class="three_col last">
                                <input type="file" name="la_2_consent_granted_file" id="la_2_consent_granted_file" />
								<?php
                                    $consent_granted_file = get_user_meta($user_id, 'la_2_consent_granted_file', true);
                                    if($consent_granted_file) {
                                        $consent_granted_filename = basename ( get_attached_file( $consent_granted_file ) );
                                        echo '<br>'.$consent_granted_filename;
                                    }
                                ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="full_col" style="margin-bottom:10px;">
                            <label class="label-control"><strong>1c.</strong> Has the business entity or any owner, partner, officer or director of the business entity or member or manager of a limited liability company, ever been convicted of a military offense, had a judgment withheld or deferred, or is the business entity or any owner, partner, officer or director of the business entity or member or manager of a limited liability company, currently charged with committing a military offense?</label>
                            <label for="la_2_military_offense1" class="radio-control"><input type="radio" name="la_2_military_offense" id="la_2_military_offense1" value="Yes" <?php if(get_user_meta($user_id, 'la_2_military_offense', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_2_military_offense2" class="radio-control"><input type="radio" name="la_2_military_offense" id="la_2_military_offense2" value="No" <?php if(get_user_meta($user_id, 'la_2_military_offense', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="discription"><strong>NOTE:</strong> For Questions 1a, 1b, and 1c “Convicted” includes, but is not limited to, having been found guilty by verdict of a judge or jury, having entered a plea of guilty or nolo contendere or no contest, or having been given probation, a suspended sentence or a fine.</div><!--discription-->
                        
                        <div class="full_col">
                            <h5><strong>if you answer yes to any of these questions, you must attach to this application:</strong></h5>
                            <ul>
                                <li><strong>a)</strong> a written statement identifying all parties involved (including their percentage of ownership, if any) and explaining the circumstances of each incident, </li>
                                <li><strong>b)</strong> a copy of the charging document, </li>
                                <li><strong>c)</strong> a copy of the official document which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
                        </div>
                        <div class="two_third">
                            <label for="la_2_applicant1_comment" class="label-control">Comment</label>
                            <textarea name="la_2_applicant1_comment" id="la_2_applicant1_comment" rows="1"><?php echo get_user_meta($user_id, 'la_2_applicant1_comment', true); ?></textarea>
                        </div>
                        <div class="one_third et_column_last">
                            <label for="la_2_applicant1_file" class="label-control">Upload File</label>
                            <input type="file" name="la_2_applicant1_file" id="la_2_applicant1_file" />
							<?php
                                $applicant1_file = get_user_meta($user_id, 'la_2_applicant1_file', true);
                                if($applicant1_file) {
                                    $filename = basename ( get_attached_file( $applicant1_file ) );
                                    echo '<br>'.$filename;
                                }
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col" style="margin-bottom:10px">
                            <label class="label-control"><strong>2.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or manager or member of a limited liability company, ever been named or involved as a party in an administrative proceeding, including a FINRA sanction or arbitration proceeding regarding any professional or occupational license, or registration?</label>
                            
                            <label for="la_2_occupational_license1" class="radio-control"><input type="radio" name="la_2_occupational_license" id="la_2_occupational_license1" value="Yes" <?php if(get_user_meta($user_id, 'la_2_occupational_license', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_2_occupational_license2" class="radio-control"><input type="radio" name="la_2_occupational_license" id="la_2_occupational_license2" value="No" <?php if(get_user_meta($user_id, 'la_2_occupational_license', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="discription">“Involved” means having a license censured, suspended, revoked, canceled, terminated; or, being assessed a fine, a cease and desist order, a prohibition order, a compliance order, placed on probation, sanctioned or surrendering a license to resolve an administrative action. “Involved” also means being named as a party to an administrative or arbitration proceeding, which is related to a professional or occupational license or registration. “Involved” also means having a license application denied or the act of withdrawing an application to avoid a denial. You may EXCLUDE terminations due solely to noncompliance with continuing education requirements or failure to pay a renewal fee. </div>
                        
                        <div class="full_col">
                            <h5><strong>If you answer yes, you must attach to this application:</strong></h5>
                            <ul>
                                <li><strong>a)</strong> a written statement identifying the type of license, all parties involved (including their percentage of ownership, if any) and explaining the circumstances of each incident,</li>
                                <li><strong>b)</strong> a copy of the Notice of Hearing or other document that states the charges and allegations, and </li>
                                <li><strong>c)</strong> a copy of the official document which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
                        </div>
                        <div class="two_third">
                            <label for="la_2_applicant2_comment" class="label-control">Comment</label>
                            <textarea name="la_2_applicant2_comment" id="la_2_applicant2_comment" rows="1"><?php echo get_user_meta($user_id, 'la_2_applicant2_comment', true); ?></textarea>
                        </div>
                        <div class="one_third et_column_last">
                            <label for="la_2_applicant2_file" class="label-control">Upload File</label>
                            <input type="file" name="la_2_applicant2_file" id="la_2_applicant2_file" />
							<?php
                                $applicant2_file = get_user_meta($user_id, 'la_2_applicant2_file', true);
                                if($applicant2_file) {
                                    $filename = basename ( get_attached_file( $applicant2_file ) );
                                    echo '<br>'.$filename;
                                }
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <div class="radio_group">
                            <label class="label-control"><strong>3.</strong> Has any demand been made or judgment rendered against the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, for overdue monies by an insurer, insured or producer, or have you ever been subject to a bankruptcy proceeding? Do not include personal bankruptcies, unless they involve funds held on behalf of others.</label>
                            
                            <label for="la_2_demand1" class="radio-control"><input type="radio" name="la_2_demand" id="la_2_demand1" value="Yes" <?php if(get_user_meta($user_id, 'la_2_demand', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="la_2_demand2" class="radio-control"><input type="radio" name="la_2_demand" id="la_2_demand2" value="No" <?php if(get_user_meta($user_id, 'la_2_demand', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="full_col">
                            <label class="label-control">If you answer yes, submit a statement summarizing the details of the indebtedness and arrangements for repayment.</label>
                            <textarea name="la_2_arrangements_repayment" id="la_2_arrangements_repayment" rows="2"><?php echo get_user_meta($user_id, 'la_2_arrangements_repayment', true); ?></textarea>
                        </div>
                        
                        <div class="radio_group">
                            <label class="label-control"><strong>4.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, ever been notified by any jurisdiction to which you are applying of any delinquent tax obligation that is not the subject of a repayment agreement?</label>
                            
                            <label for="la_2_business_entity2_yes" class="radio-control"><input type="radio" name="la_2_business_entity2" id="la_2_business_entity2_yes" value="Yes" <?php if(get_user_meta($user_id, 'la_2_business_entity2', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="la_2_business_entity2_no" class="radio-control"><input type="radio" name="la_2_business_entity2" id="la_2_business_entity2_no" value="No" <?php if(get_user_meta($user_id, 'la_2_business_entity2', true) == "No") echo 'checked="checked"'; ?> />No</label>
                       </div>
                       
                       <div class="full_col">
                            <label for="la_2_identify_jurisdiction" class="label-control"><strong>If you answer yes, identify the jurisdiction(s):</strong></label>
                            <input type="text" id="la_2_identify_jurisdiction" name="la_2_identify_jurisdiction" value="<?php echo get_user_meta($user_id, 'la_2_identify_jurisdiction', true); ?>" />
                       </div>
                       
                       <div class="radio_group">
                            <label class="label-control"><strong>5.</strong> Is the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, a party to, or ever been found liable in any lawsuit or arbitration proceeding involving allegations of fraud, misappropriation or conversion of funds, misrepresentation or breach of fiduciary duty? </label>
                            
                            <label for="la_2_business_entity3_yes" class="radio-control"><input type="radio" name="la_2_business_entity3" id="la_2_business_entity3_yes" value="Yes" <?php if(get_user_meta($user_id, 'la_2_business_entity3', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="la_2_business_entity3_no" class="radio-control"><input type="radio" name="la_2_business_entity3" id="la_2_business_entity3_no" value="No" <?php if(get_user_meta($user_id, 'la_2_business_entity3', true) == "No") echo 'checked="checked"'; ?> />No</label>
                       </div>
                        
                        <div class="full_col">
                            <h5><strong>If you answer yes, you must attach to this application: </strong></h5>
                            <ul>
                                <li><strong>a)</strong> a written statement summarizing the details of each incident,</li>
                                <li><strong>b)</strong> a copy of the Petition, Complaint or other document that commenced the lawsuit arbitrations, or mediation proceedings and </li>
                                <li><strong>c)</strong> a copy of the official documents which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
                        </div>
                        <div class="two_third">
                            <label for="la_2_applicant3_comment" class="label-control">Comment</label>
                            <textarea name="la_2_applicant3_comment" id="la_2_applicant3_comment" rows="1"><?php echo get_user_meta($user_id, 'la_2_applicant3_comment', true); ?></textarea>
                        </div>
                        <div class="one_third et_column_last">
                            <label for="la_2_applicant3_file" class="label-control">Upload File</label>
                            <input type="file" name="la_2_applicant3_file" id="la_2_applicant3_file" />
							<?php
                                $applicant3_file = get_user_meta($user_id, 'la_2_applicant3_file', true);
                                if($applicant3_file) {
                                    $filename = basename ( get_attached_file( $applicant3_file ) );
                                    echo '<br>'.$filename;
                                }
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        
                        <div class="radio_group">
                            <label class="label-control"><strong>6.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company ever had an insurance agency contract or any other business relationship with an insurance company terminated for any alleged misconduct?</label>
                            
                            <label for="la_2_business_entity4_yes" class="radio-control"><input type="radio" name="la_2_business_entity4" id="la_2_business_entity4_yes" value="Yes" <?php if(get_user_meta($user_id, 'la_2_business_entity4', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="la_2_business_entity4_no" class="radio-control"><input type="radio" name="la_2_business_entity4" id="la_2_business_entity4_no" value="No" <?php if(get_user_meta($user_id, 'la_2_business_entity4', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        
                        <div class="full_col">
                            <h5><strong>If you answer yes, you must attach to this application:</strong></h5>
                            <ul>
                                <li><strong>a)</strong> a written statement summarizing the details of each incident and explaining why you feel this incident should not prevent you from receiving an insurance license, and </li>
                                <li><strong>b)</strong> copies of all relevant documents. </li>
                            </ul>
                        </div>
                        <div class="two_third">
                            <label for="la_2_applicant4_comment" class="label-control">Comment</label>
                            <textarea name="la_2_applicant4_comment" id="la_2_applicant4_comment" rows="1"><?php echo get_user_meta($user_id, 'la_2_applicant4_comment', true); ?></textarea>
                        </div>
                        <div class="one_third et_column_last">
                            <label for="la_2_applicant4_file" class="label-control">Upload File</label>
                            <input type="file" name="la_2_applicant4_file" id="la_2_applicant4_file" />
							<?php
                                $applicant4_file = get_user_meta($user_id, 'la_2_applicant4_file', true);
                                if($applicant4_file) {
                                    $filename = basename ( get_attached_file( $applicant4_file ) );
                                    echo '<br>'.$filename;
                                }
                            ?>
                        </div>
                        <div class="clearfix"></div>                   
                        
                        <div class="radio_group">
                            <label class="label-control"><strong>8.</strong> In response to a “yes” answer to one or more of the Background Questions for this application, are you submitting document(s) to the NAIC/NIPR Attachments Warehouse? </label>
                            
                            <label for="la_2_warehouse1" class="radio-control"><input type="radio" name="la_2_warehouse" id="la_2_warehouse1" value="Yes" <?php if(get_user_meta($user_id, 'la_2_warehouse', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                                <label for="la_2_warehouse2" class="radio-control"><input type="radio" name="la_2_warehouse" id="la_2_warehouse2" value="No" <?php if(get_user_meta($user_id, 'la_2_warehouse', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="full_col">
                            <h5><strong>If you answer yes</strong></h5>
                            <label class="label-control">Will you be associating (linking) previously filed documents from the NAIC/NIPR Attachments Warehouse to this application?</label>
                            
                            <label for="la_2_NIPR_Attachments1" class="radio-control"><input type="radio" name="la_2_NIPR_Attachments" id="la_2_NIPR_Attachments1" value="Yes" <?php if(get_user_meta($user_id, 'la_2_NIPR_Attachments', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="la_2_NIPR_Attachments2" class="radio-control"><input type="radio" name="la_2_NIPR_Attachments" id="la_2_NIPR_Attachments2" value="No" <?php if(get_user_meta($user_id, 'la_2_NIPR_Attachments', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        <div class="discription"><strong>Note:</strong> If you have previously submitted documents to the Attachments Warehouse that are intended to be filed with this application, you must go to the Attachments Warehouse and associate (link) the supporting document(s) to this application based upon the particular background question number you have answered yes to on this application. You will receive information in a follow-up page at the end of the application process, providing a link to the Attachment Warehouse instructions.</div>
                        
                        <div class="full_col">
                            <label class="label-control"><strong>9.</strong> I authorize InsureStays and/or other affiliated insurance agency of RentalGuardian to acquire these licenses on my behalf based on the information I have truthfully provided in this web form.</label>
                            <input type="text" name="la_1_authorize_insurestays" id="la_1_authorize_insurestays" value="<?php echo get_user_meta($user_id, 'la_2_authorize_insurestays', true); ?>" />
                        </div>
                        
                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-step">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-step">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="licensing-appointments-step2" />
                        <input type="hidden" name="licensing_appointments_step2_progress" id="licensing_appointments_step2_progress" value="<?php echo get_user_meta($user_id, 'licensing_appointments_step2_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_9-->


                <div class="company_info_steps company_info_step_9" id="licensing_tab3" data-step="3" data-id-base="licensing_tab" style="display:none;">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="license-appointment3" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Licensing & Appointments <span class="step_active">3</span> of 5</h1>
                        <div class="line"></div>
                        
                        <?php //if(get_user_meta($user_id, 'sign_las3_docusign_form', true) == "Yes") { ?>
                        <!--<p class="already-signed">This PDF has been completed and signed. Please Click "Next" to Continue & Save the PDF - Thank You.</p>
                        <?php //} ?>
                        <!--<div class="form_cont">
                        	<iframe id="dswpf" src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=79f03c34-9124-411d-b3f2-e6cbe2572240"/>
                        </div>-->
                        <!--<label for="sign_las3_docusign_form" class="label-control require">Have you accurately completed the above PDF and Signed it?</label>
                        <select name="sign_las3_docusign_form" id="sign_las3_docusign_form" class="docusign-form-done">
                            <option value="">Please Select</option>
                            <option value="Yes" <?php //if(get_user_meta($user_id, 'sign_las3_docusign_form', true) == "Yes") echo 'selected="selected"'; ?>>Yes</option>
                            <option value="No" <?php //if(get_user_meta($user_id, 'sign_las3_docusign_form', true) == "No") echo 'selected="selected"'; ?>>No</option>
                        </select> -->
                        
                        <h4><strong>SECTION I: AGENCY PRODUCER INFORMATION</strong></h4>
                        <div class="one_half">
                        	<label for="agency_legal_name" class="label-control">Agency Legal Name:</label>
                            <input name="agency_legal_name" id="agency_legal_name" type="text" value="<?php echo get_user_meta($user_id, 'agency_legal_name', true); ?>" />
                        </div>
                        <div class="one_half et_column_last">
                           <label for="agency_employer_id" class="label-control">Federal Employer Identification Number:</label>
                           <input name="agency_employer_id" id="agency_employer_id" type="text" value="<?php echo get_user_meta($user_id, 'agency_employer_id', true); ?>" />
                        </div>
                        <div class="clerfix"></div>
                        
                        <div class="full_col">
                        	<label for="agency_producer_address" class="label-control">Address:</label>
                           <input name="agency_producer_address" id="agency_producer_address" type="text" value="<?php echo get_user_meta($user_id, 'agency_producer_address', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="agency_producer_city" class="label-control">City:</label>
                           <input name="agency_producer_city" id="agency_producer_city" type="text" value="<?php echo get_user_meta($user_id, 'agency_producer_city', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="agency_producer_state" class="label-control">State:</label>
                           <input name="agency_producer_state" id="agency_producer_state" type="text" value="<?php echo get_user_meta($user_id, 'agency_producer_state', true); ?>" />
                        </div>
                        
                        <div class="three_col last">
                        	<label for="agency_producer_zipcode" class="label-control">Zip Code:</label>
                           <input name="agency_producer_zipcode" id="agency_producer_zipcode" type="text" value="<?php echo get_user_meta($user_id, 'agency_producer_zipcode', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col">
                        	<label for="agency_producer_business_email" class="label-control">Business Email:</label>
                            <input name="agency_producer_business_email" id="agency_producer_business_email" type="text" value="<?php echo get_user_meta($user_id, 'agency_producer_business_email', true); ?>" />
                        </div>
                        
                        <div class="full_col">
                        	<label for="agency_producer_license_number" class="label-control">Please indicate which states the Agency is licensed and include the license number for each:</label>
                            <?php
							$agency_producer_license_number = get_user_meta($user_id, 'agency_producer_license_number', true);
							$agency_producer_states = explode(",", $agency_producer_license_number);
							?>
                            <input type="checkbox" name="agency_producer_license_number[]" value="AK" <?php if(is_array($agency_producer_states) && in_array("AK", $agency_producer_states)) echo 'checked="checked"'; ?> /> AK 
                            <input type="checkbox" name="agency_producer_license_number[]" value="AL" <?php if(is_array($agency_producer_states) && in_array("AL", $agency_producer_states)) echo 'checked="checked"'; ?> /> AL 
                            <input type="checkbox" name="agency_producer_license_number[]" value="AR" <?php if(is_array($agency_producer_states) && in_array("AR", $agency_producer_states)) echo 'checked="checked"'; ?> /> AR 
                            <input type="checkbox" name="agency_producer_license_number[]" value="AZ" <?php if(is_array($agency_producer_states) && in_array("AZ", $agency_producer_states)) echo 'checked="checked"'; ?> /> AZ 
                            <input type="checkbox" name="agency_producer_license_number[]" value="CA" <?php if(is_array($agency_producer_states) && in_array("CA", $agency_producer_states)) echo 'checked="checked"'; ?> /> CA 
                            <input type="checkbox" name="agency_producer_license_number[]" value="CO" <?php if(is_array($agency_producer_states) && in_array("CO", $agency_producer_states)) echo 'checked="checked"'; ?> /> CO 
                            <input type="checkbox" name="agency_producer_license_number[]" value="CT" <?php if(is_array($agency_producer_states) && in_array("CT", $agency_producer_states)) echo 'checked="checked"'; ?> /> CT 
                            <input type="checkbox" name="agency_producer_license_number[]" value="DC" <?php if(is_array($agency_producer_states) && in_array("DC", $agency_producer_states)) echo 'checked="checked"'; ?> /> DC 
                            <input type="checkbox" name="agency_producer_license_number[]" value="DE" <?php if(is_array($agency_producer_states) && in_array("DE", $agency_producer_states)) echo 'checked="checked"'; ?> /> DE 
                            <input type="checkbox" name="agency_producer_license_number[]" value="FL" <?php if(is_array($agency_producer_states) && in_array("FL", $agency_producer_states)) echo 'checked="checked"'; ?> /> FL 
                            <input type="checkbox" name="agency_producer_license_number[]" value="GA" <?php if(is_array($agency_producer_states) && in_array("GA", $agency_producer_states)) echo 'checked="checked"'; ?> /> GA 
                            <input type="checkbox" name="agency_producer_license_number[]" value="HI" <?php if(is_array($agency_producer_states) && in_array("HI", $agency_producer_states)) echo 'checked="checked"'; ?> /> HI 
                            <input type="checkbox" name="agency_producer_license_number[]" value="IA" <?php if(is_array($agency_producer_states) && in_array("IA", $agency_producer_states)) echo 'checked="checked"'; ?> /> IA 
                            <input type="checkbox" name="agency_producer_license_number[]" value="ID" <?php if(is_array($agency_producer_states) && in_array("ID", $agency_producer_states)) echo 'checked="checked"'; ?> /> ID 
                            <input type="checkbox" name="agency_producer_license_number[]" value="IL" <?php if(is_array($agency_producer_states) && in_array("IL", $agency_producer_states)) echo 'checked="checked"'; ?> /> IL 
                            <input type="checkbox" name="agency_producer_license_number[]" value="IN" <?php if(is_array($agency_producer_states) && in_array("IN", $agency_producer_states)) echo 'checked="checked"'; ?> /> IN 
                            <input type="checkbox" name="agency_producer_license_number[]" value="KS" <?php if(is_array($agency_producer_states) && in_array("KS", $agency_producer_states)) echo 'checked="checked"'; ?> /> KS 
                            <input type="checkbox" name="agency_producer_license_number[]" value="KY" <?php if(is_array($agency_producer_states) && in_array("KY", $agency_producer_states)) echo 'checked="checked"'; ?> /> KY 
                            <input type="checkbox" name="agency_producer_license_number[]" value="LA" <?php if(is_array($agency_producer_states) && in_array("LA", $agency_producer_states)) echo 'checked="checked"'; ?> /> LA 
                            <input type="checkbox" name="agency_producer_license_number[]" value="MA" <?php if(is_array($agency_producer_states) && in_array("MA", $agency_producer_states)) echo 'checked="checked"'; ?> /> MA 
                            <input type="checkbox" name="agency_producer_license_number[]" value="MD" <?php if(is_array($agency_producer_states) && in_array("MD", $agency_producer_states)) echo 'checked="checked"'; ?> /> MD 
                            <input type="checkbox" name="agency_producer_license_number[]" value="ME" <?php if(is_array($agency_producer_states) && in_array("ME", $agency_producer_states)) echo 'checked="checked"'; ?> /> ME 
                            <input type="checkbox" name="agency_producer_license_number[]" value="MI" <?php if(is_array($agency_producer_states) && in_array("MI", $agency_producer_states)) echo 'checked="checked"'; ?> /> MI 
                            <input type="checkbox" name="agency_producer_license_number[]" value="MN" <?php if(is_array($agency_producer_states) && in_array("MN", $agency_producer_states)) echo 'checked="checked"'; ?> /> MN 
                            <input type="checkbox" name="agency_producer_license_number[]" value="MO" <?php if(is_array($agency_producer_states) && in_array("MO", $agency_producer_states)) echo 'checked="checked"'; ?> /> MO 
                            <input type="checkbox" name="agency_producer_license_number[]" value="MS" <?php if(is_array($agency_producer_states) && in_array("MS", $agency_producer_states)) echo 'checked="checked"'; ?> /> MS 
                            <input type="checkbox" name="agency_producer_license_number[]" value="MT" <?php if(is_array($agency_producer_states) && in_array("MT", $agency_producer_states)) echo 'checked="checked"'; ?> /> MT 
                            <input type="checkbox" name="agency_producer_license_number[]" value="NC" <?php if(is_array($agency_producer_states) && in_array("NC", $agency_producer_states)) echo 'checked="checked"'; ?> /> NC 
                            <input type="checkbox" name="agency_producer_license_number[]" value="ND" <?php if(is_array($agency_producer_states) && in_array("ND", $agency_producer_states)) echo 'checked="checked"'; ?> /> ND 
                            <input type="checkbox" name="agency_producer_license_number[]" value="NE" <?php if(is_array($agency_producer_states) && in_array("NE", $agency_producer_states)) echo 'checked="checked"'; ?> /> NE 
                            <input type="checkbox" name="agency_producer_license_number[]" value="NH" <?php if(is_array($agency_producer_states) && in_array("NH", $agency_producer_states)) echo 'checked="checked"'; ?> /> NH 
                            <input type="checkbox" name="agency_producer_license_number[]" value="NJ" <?php if(is_array($agency_producer_states) && in_array("NJ", $agency_producer_states)) echo 'checked="checked"'; ?> /> NJ 
                            <input type="checkbox" name="agency_producer_license_number[]" value="NM" <?php if(is_array($agency_producer_states) && in_array("NM", $agency_producer_states)) echo 'checked="checked"'; ?> /> NM 
                            <input type="checkbox" name="agency_producer_license_number[]" value="NV" <?php if(is_array($agency_producer_states) && in_array("NV", $agency_producer_states)) echo 'checked="checked"'; ?> /> NV 
                            <input type="checkbox" name="agency_producer_license_number[]" value="NY" <?php if(is_array($agency_producer_states) && in_array("NY", $agency_producer_states)) echo 'checked="checked"'; ?> /> NY 
                            <input type="checkbox" name="agency_producer_license_number[]" value="OH" <?php if(is_array($agency_producer_states) && in_array("OH", $agency_producer_states)) echo 'checked="checked"'; ?> /> OH 
                            <input type="checkbox" name="agency_producer_license_number[]" value="OK" <?php if(is_array($agency_producer_states) && in_array("OK", $agency_producer_states)) echo 'checked="checked"'; ?> /> OK 
                            <input type="checkbox" name="agency_producer_license_number[]" value="OR" <?php if(is_array($agency_producer_states) && in_array("OR", $agency_producer_states)) echo 'checked="checked"'; ?> /> OR 
                            <input type="checkbox" name="agency_producer_license_number[]" value="PA" <?php if(is_array($agency_producer_states) && in_array("PA", $agency_producer_states)) echo 'checked="checked"'; ?> /> PA 
                            <input type="checkbox" name="agency_producer_license_number[]" value="RI" <?php if(is_array($agency_producer_states) && in_array("RI", $agency_producer_states)) echo 'checked="checked"'; ?> /> RI 
                            <input type="checkbox" name="agency_producer_license_number[]" value="SC" <?php if(is_array($agency_producer_states) && in_array("SC", $agency_producer_states)) echo 'checked="checked"'; ?> /> SC 
                            <input type="checkbox" name="agency_producer_license_number[]" value="SD" <?php if(is_array($agency_producer_states) && in_array("SD", $agency_producer_states)) echo 'checked="checked"'; ?> /> SD 
                            <input type="checkbox" name="agency_producer_license_number[]" value="TN" <?php if(is_array($agency_producer_states) && in_array("TN", $agency_producer_states)) echo 'checked="checked"'; ?> /> TN 
                            <input type="checkbox" name="agency_producer_license_number[]" value="TX" <?php if(is_array($agency_producer_states) && in_array("TX", $agency_producer_states)) echo 'checked="checked"'; ?> /> TX 
                            <input type="checkbox" name="agency_producer_license_number[]" value="UT" <?php if(is_array($agency_producer_states) && in_array("UT", $agency_producer_states)) echo 'checked="checked"'; ?> /> UT 
                            <input type="checkbox" name="agency_producer_license_number[]" value="VA" <?php if(is_array($agency_producer_states) && in_array("VA", $agency_producer_states)) echo 'checked="checked"'; ?> /> VA 
                            <input type="checkbox" name="agency_producer_license_number[]" value="VT" <?php if(is_array($agency_producer_states) && in_array("VT", $agency_producer_states)) echo 'checked="checked"'; ?> /> VT 
                            <input type="checkbox" name="agency_producer_license_number[]" value="WA" <?php if(is_array($agency_producer_states) && in_array("WA", $agency_producer_states)) echo 'checked="checked"'; ?> /> WA 
                            <input type="checkbox" name="agency_producer_license_number[]" value="WI" <?php if(is_array($agency_producer_states) && in_array("WI", $agency_producer_states)) echo 'checked="checked"'; ?> /> WI 
                            <input type="checkbox" name="agency_producer_license_number[]" value="WV" <?php if(is_array($agency_producer_states) && in_array("WV", $agency_producer_states)) echo 'checked="checked"'; ?> /> WV 
                            <input type="checkbox" name="agency_producer_license_number[]" value="WY" <?php if(is_array($agency_producer_states) && in_array("WY", $agency_producer_states)) echo 'checked="checked"'; ?> /> WY 
                        </div>
                        <br />
                        <h5 style="margin-bottom:10px;"><strong>Agency Background Questions</strong></h5>
                        <div class="radio_group">
                        	<label class="label-control">Has the Agency or any owner, officer, director, or partner of the Agency ever been charged with a crime in
a court of law in any State, Province, Territory, or country? If yes, provide details including date, charges, disposition, and a description of the circumstances.</label>
							<label for="agency_producer_back_quise_a1" class="radio-control"><input type="radio" name="agency_producer_back_quise_a" id="agency_producer_back_quise_a1" value="Yes" <?php if(get_user_meta($user_id, 'agency_producer_back_quise_a', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="agency_producer_back_quise_a2" class="radio-control"><input type="radio" name="agency_producer_back_quise_a" id="agency_producer_back_quise_a2" value="No" <?php if(get_user_meta($user_id, 'agency_producer_back_quise_a', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="radio_group">
                        	<label class="label-control">Has the Agency or any owner, officer, director, partner, or employee of the Agency ever been the subject of any administrative or disciplinary action by any insurance authority? If yes, provide full details.</label>
							<label for="agency_producer_back_quise_b1" class="radio-control"><input type="radio" name="agency_producer_back_quise_b" id="agency_producer_back_quise_b1" value="Yes" <?php if(get_user_meta($user_id, 'agency_producer_back_quise_b', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="agency_producer_back_quise_b2" class="radio-control"><input type="radio" name="agency_producer_back_quise_b" id="agency_producer_back_quise_b2" value="No" <?php if(get_user_meta($user_id, 'agency_producer_back_quise_b', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="radio_group">
                        	<label class="label-control">Has the Agency or any owner, officer, director, partner, or employee of the Agency ever had a contract or
appointment to sell travel insurance or to act as a travel retailer terminated or cancelled for any reason other than low production? If yes, provide full details.</label>
							<label for="agency_producer_back_quise_c1" class="radio-control"><input type="radio" name="agency_producer_back_quise_c" id="agency_producer_back_quise_c1" value="Yes" <?php if(get_user_meta($user_id, 'agency_producer_back_quise_c', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="agency_producer_back_quise_c2" class="radio-control"><input type="radio" name="agency_producer_back_quise_c" id="agency_producer_back_quise_c2" value="No" <?php if(get_user_meta($user_id, 'agency_producer_back_quise_c', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <h4 style="margin-bottom:10px;"><strong>SECTION II: AGENCY APPLICATION ACKNOWLEDGEMENT AND CERTIFICATION</strong></h4>
                        
                        <div class="radio_group">
                        	<p>On behalf of the Agency, I hereby certify that all of the information submitted in this application and attachments is true and complete.</p>
                            <p>I acknowledge that I understand and that the Agency will comply with the insurance laws and regulations of the jurisdictions in which the Agency is transacting insurance business.</p>
                            <p>I acknowledge that Berkshire Hathaway Global Insurance Services, LLC and its affiliates may correspond with the Agency by fax, email, or other electronic means. I understand that by certifying this application, I am authorizing Berkshire Hathaway Global Insurance Services, LLC and its affiliates to communicate with the Agency in this manner.</p>
                            <p>I certify that I am authorized to make these acknowledgments and certifications on behalf of the Agency.</p>
                       </div><!--radio_group-->

                             <!--<div class="two_third">
                             	<label for="agency_application_name" class="label-control require">NAME & TITLE</label>
                       	  		<input type="text" name="agency_application_name" id="property_printed_name" value="<?php //echo get_user_meta($user_id, 'agency_application_name', true); ?>" />
                             </div>
                             <div class="one_third et_column_last">
                             	<label for="agency_application_date" class="label-control require">DATE</label>
                       	  		<input type="text" name="agency_application_date" id="agency_application_date" value="<?php //if(get_user_meta($user_id, 'agency_application_date', true)) { echo get_user_meta($user_id, 'agency_application_date', true); } else { echo date("Y-m-d"); } ?>" />
                             </div>
                             <div class="clearfix"></div>-->
                            <!--<label class="label-control require">APPLICANT/ AUTHORIZED SIGNATURE</label>
                            <div class="signature-wrapper" style="margin-bottom:25px;">
                                <div class="sig-pad" style="border-color:#E2E2E2; margin-bottom:5px;"></div>
                                <br />(Use mouse or stylus pen to sign)
                                <br />
                                <?php
                                /*if( get_user_meta($user_id, 'agency_application_signature', true) ) {
                                    echo '<img src="'.get_user_meta($user_id, 'agency_application_signature', true).'" alt="" /><br />';
                                }*/
                                ?>
                                <span class="sig-done">Done</span>
                                <span class="sig-clear">Clear</span>
                                <?php //if( get_user_meta($user_id, 'agency_application_signature', true) ) { ?>
                                <textarea id="agency_application_sig_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php //} else { ?>
                                <textarea id="agency_application_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php //} ?>
                          </div>--><!-- .signature-wrapper -->
                          
                          
                          <h4 style="margin-bottom:10px; font-weight:bold;">SECTION III: DESIGNATED RESPONSIBLE PRODUCER INFORMATION</h4>
                          <h5>Please provide your full legal name.</h5>
                          <div class="three_col">
                        	<label for="designate_producer_first_name" class="label-control">First Name:</label>
                           <input name="designate_producer_first_name" id="designate_producer_first_name" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_first_name', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="designate_producer_middle_name" class="label-control">Middle Name:</label>
                           <input name="designate_producer_middle_name" id="designate_producer_middle_name" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_middle_name', true); ?>" />
                        </div>
                        
                        <div class="three_col last">
                        	<label for="designate_producer_last_name" class="label-control">Last Name:</label>
                           <input name="designate_producer_last_name" id="designate_producer_last_name" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_last_name', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col">
                        	<label for="designate_producer_home_address" class="label-control">Home Address:</label>
                            <input name="designate_producer_home_address" id="designate_producer_home_address" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_home_address', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="designate_producer_city" class="label-control">City:</label>
                           <input name="designate_producer_city" id="designate_producer_city" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_city', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="designate_producer_state" class="label-control">State:</label>
                           <input name="designate_producer_state" id="designate_producer_state" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_state', true); ?>" />
                        </div>
                        
                        <div class="three_col last">
                        	<label for="designate_producer_zipcode" class="label-control">Zip Code:</label>
                           <input name="designate_producer_zipcode" id="designate_producer_zipcode" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_zipcode', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <div class="three_col">
                        	<label for="designate_producer_state" class="label-control">State of Residence:</label>
                           <input name="designate_producer_residence" id="designate_producer_residence" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_residence', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="designate_producer_city" class="label-control">Date of Birth:</label>
                           <input name="designate_producer_birth_date" id="designate_producer_birth_date" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_birth_date', true); ?>" />
                        </div>
                        
                        <div class="three_col last">
                        	<label for="designate_producer_business_phone" class="label-control">Business Phone:</label>
                           <input name="designate_producer_business_phone" id="designate_producer_business_phone" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_business_phone', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <div class="three_col">
                        	<label for="designate_producer_title" class="label-control">Title:</label>
                           <input name="designate_producer_title" id="designate_producer_title" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_title', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="designate_producer_primary_email" class="label-control">Primary Email:</label>
                           <input name="designate_producer_primary_email" id="designate_producer_primary_email" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_primary_email', true); ?>" />
                        </div>
                        
                        <div class="three_col last">
                        	<label for="designate_producer_social_security" class="label-control">Socila Security Number:</label>
                           <input name="designate_producer_social_security" id="designate_producer_social_security" type="text" value="<?php echo get_user_meta($user_id, 'designate_producer_social_security', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col">
                        	<label for="designate_producer_license_number" class="label-control">Please indicate which states the Designated Responsible Producer is licensed and include the license number for each:</label>
                            <!--<select style="height:180px; overflow:auto;" name="designate_producer_license_number[]" id="designate_producer_license_number" multiple>-->
                            <?php
							$designate_producer_license_number = get_user_meta($user_id, 'designate_producer_license_number', true);
							$designate_producer_states = explode(",", $designate_producer_license_number);
							?>
                            <input type="checkbox" name="designate_producer_license_number[]" value="AK" <?php if(is_array($designate_producer_states) && in_array("AK", $designate_producer_states)) echo 'checked="checked"'; ?> /> AK 
                            <input type="checkbox" name="designate_producer_license_number[]" value="AL" <?php if(is_array($designate_producer_states) && in_array("AL", $designate_producer_states)) echo 'checked="checked"'; ?> /> AL 
                            <input type="checkbox" name="designate_producer_license_number[]" value="AR" <?php if(is_array($designate_producer_states) && in_array("AR", $designate_producer_states)) echo 'checked="checked"'; ?> /> AR 
                            <input type="checkbox" name="designate_producer_license_number[]" value="AZ" <?php if(is_array($designate_producer_states) && in_array("AZ", $designate_producer_states)) echo 'checked="checked"'; ?> /> AZ 
                            <input type="checkbox" name="designate_producer_license_number[]" value="CA" <?php if(is_array($designate_producer_states) && in_array("CA", $designate_producer_states)) echo 'checked="checked"'; ?> /> CA 
                            <input type="checkbox" name="designate_producer_license_number[]" value="CO" <?php if(is_array($designate_producer_states) && in_array("CO", $designate_producer_states)) echo 'checked="checked"'; ?> /> CO 
                            <input type="checkbox" name="designate_producer_license_number[]" value="CT" <?php if(is_array($designate_producer_states) && in_array("CT", $designate_producer_states)) echo 'checked="checked"'; ?> /> CT 
                            <input type="checkbox" name="designate_producer_license_number[]" value="DC" <?php if(is_array($designate_producer_states) && in_array("DC", $designate_producer_states)) echo 'checked="checked"'; ?> /> DC 
                            <input type="checkbox" name="designate_producer_license_number[]" value="DE" <?php if(is_array($designate_producer_states) && in_array("DE", $designate_producer_states)) echo 'checked="checked"'; ?> /> DE 
                            <input type="checkbox" name="designate_producer_license_number[]" value="FL" <?php if(is_array($designate_producer_states) && in_array("FL", $designate_producer_states)) echo 'checked="checked"'; ?> /> FL 
                            <input type="checkbox" name="designate_producer_license_number[]" value="GA" <?php if(is_array($designate_producer_states) && in_array("GA", $designate_producer_states)) echo 'checked="checked"'; ?> /> GA 
                            <input type="checkbox" name="designate_producer_license_number[]" value="HI" <?php if(is_array($designate_producer_states) && in_array("HI", $designate_producer_states)) echo 'checked="checked"'; ?> /> HI 
                            <input type="checkbox" name="designate_producer_license_number[]" value="IA" <?php if(is_array($designate_producer_states) && in_array("IA", $designate_producer_states)) echo 'checked="checked"'; ?> /> IA 
                            <input type="checkbox" name="designate_producer_license_number[]" value="ID" <?php if(is_array($designate_producer_states) && in_array("ID", $designate_producer_states)) echo 'checked="checked"'; ?> /> ID 
                            <input type="checkbox" name="designate_producer_license_number[]" value="IL" <?php if(is_array($designate_producer_states) && in_array("IL", $designate_producer_states)) echo 'checked="checked"'; ?> /> IL 
                            <input type="checkbox" name="designate_producer_license_number[]" value="IN" <?php if(is_array($designate_producer_states) && in_array("IN", $designate_producer_states)) echo 'checked="checked"'; ?> /> IN 
                            <input type="checkbox" name="designate_producer_license_number[]" value="KS" <?php if(is_array($designate_producer_states) && in_array("KS", $designate_producer_states)) echo 'checked="checked"'; ?> /> KS 
                            <input type="checkbox" name="designate_producer_license_number[]" value="KY" <?php if(is_array($designate_producer_states) && in_array("KY", $designate_producer_states)) echo 'checked="checked"'; ?> /> KY 
                            <input type="checkbox" name="designate_producer_license_number[]" value="LA" <?php if(is_array($designate_producer_states) && in_array("LA", $designate_producer_states)) echo 'checked="checked"'; ?> /> LA 
                            <input type="checkbox" name="designate_producer_license_number[]" value="MA" <?php if(is_array($designate_producer_states) && in_array("MA", $designate_producer_states)) echo 'checked="checked"'; ?> /> MA 
                            <input type="checkbox" name="designate_producer_license_number[]" value="MD" <?php if(is_array($designate_producer_states) && in_array("MD", $designate_producer_states)) echo 'checked="checked"'; ?> /> MD 
                            <input type="checkbox" name="designate_producer_license_number[]" value="ME" <?php if(is_array($designate_producer_states) && in_array("ME", $designate_producer_states)) echo 'checked="checked"'; ?> /> ME 
                            <input type="checkbox" name="designate_producer_license_number[]" value="MI" <?php if(is_array($designate_producer_states) && in_array("MI", $designate_producer_states)) echo 'checked="checked"'; ?> /> MI 
                            <input type="checkbox" name="designate_producer_license_number[]" value="MN" <?php if(is_array($designate_producer_states) && in_array("MN", $designate_producer_states)) echo 'checked="checked"'; ?> /> MN 
                            <input type="checkbox" name="designate_producer_license_number[]" value="MO" <?php if(is_array($designate_producer_states) && in_array("MO", $designate_producer_states)) echo 'checked="checked"'; ?> /> MO 
                            <input type="checkbox" name="designate_producer_license_number[]" value="MS" <?php if(is_array($designate_producer_states) && in_array("MS", $designate_producer_states)) echo 'checked="checked"'; ?> /> MS 
                            <input type="checkbox" name="designate_producer_license_number[]" value="MT" <?php if(is_array($designate_producer_states) && in_array("MT", $designate_producer_states)) echo 'checked="checked"'; ?> /> MT 
                            <input type="checkbox" name="designate_producer_license_number[]" value="NC" <?php if(is_array($designate_producer_states) && in_array("NC", $designate_producer_states)) echo 'checked="checked"'; ?> /> NC 
                            <input type="checkbox" name="designate_producer_license_number[]" value="ND" <?php if(is_array($designate_producer_states) && in_array("ND", $designate_producer_states)) echo 'checked="checked"'; ?> /> ND 
                            <input type="checkbox" name="designate_producer_license_number[]" value="NE" <?php if(is_array($designate_producer_states) && in_array("NE", $designate_producer_states)) echo 'checked="checked"'; ?> /> NE 
                            <input type="checkbox" name="designate_producer_license_number[]" value="NH" <?php if(is_array($designate_producer_states) && in_array("NH", $designate_producer_states)) echo 'checked="checked"'; ?> /> NH 
                            <input type="checkbox" name="designate_producer_license_number[]" value="NJ" <?php if(is_array($designate_producer_states) && in_array("NJ", $designate_producer_states)) echo 'checked="checked"'; ?> /> NJ 
                            <input type="checkbox" name="designate_producer_license_number[]" value="NM" <?php if(is_array($designate_producer_states) && in_array("NM", $designate_producer_states)) echo 'checked="checked"'; ?> /> NM 
                            <input type="checkbox" name="designate_producer_license_number[]" value="NV" <?php if(is_array($designate_producer_states) && in_array("NV", $designate_producer_states)) echo 'checked="checked"'; ?> /> NV 
                            <input type="checkbox" name="designate_producer_license_number[]" value="NY" <?php if(is_array($designate_producer_states) && in_array("NY", $designate_producer_states)) echo 'checked="checked"'; ?> /> NY 
                            <input type="checkbox" name="designate_producer_license_number[]" value="OH" <?php if(is_array($designate_producer_states) && in_array("OH", $designate_producer_states)) echo 'checked="checked"'; ?> /> OH 
                            <input type="checkbox" name="designate_producer_license_number[]" value="OK" <?php if(is_array($designate_producer_states) && in_array("OK", $designate_producer_states)) echo 'checked="checked"'; ?> /> OK 
                            <input type="checkbox" name="designate_producer_license_number[]" value="OR" <?php if(is_array($designate_producer_states) && in_array("OR", $designate_producer_states)) echo 'checked="checked"'; ?> /> OR 
                            <input type="checkbox" name="designate_producer_license_number[]" value="PA" <?php if(is_array($designate_producer_states) && in_array("PA", $designate_producer_states)) echo 'checked="checked"'; ?> /> PA 
                            <input type="checkbox" name="designate_producer_license_number[]" value="RI" <?php if(is_array($designate_producer_states) && in_array("RI", $designate_producer_states)) echo 'checked="checked"'; ?> /> RI 
                            <input type="checkbox" name="designate_producer_license_number[]" value="SC" <?php if(is_array($designate_producer_states) && in_array("SC", $designate_producer_states)) echo 'checked="checked"'; ?> /> SC 
                            <input type="checkbox" name="designate_producer_license_number[]" value="SD" <?php if(is_array($designate_producer_states) && in_array("SD", $designate_producer_states)) echo 'checked="checked"'; ?> /> SD 
                            <input type="checkbox" name="designate_producer_license_number[]" value="TN" <?php if(is_array($designate_producer_states) && in_array("TN", $designate_producer_states)) echo 'checked="checked"'; ?> /> TN 
                            <input type="checkbox" name="designate_producer_license_number[]" value="TX" <?php if(is_array($designate_producer_states) && in_array("TX", $designate_producer_states)) echo 'checked="checked"'; ?> /> TX 
                            <input type="checkbox" name="designate_producer_license_number[]" value="UT" <?php if(is_array($designate_producer_states) && in_array("UT", $designate_producer_states)) echo 'checked="checked"'; ?> /> UT 
                            <input type="checkbox" name="designate_producer_license_number[]" value="VA" <?php if(is_array($designate_producer_states) && in_array("VA", $designate_producer_states)) echo 'checked="checked"'; ?> /> VA 
                            <input type="checkbox" name="designate_producer_license_number[]" value="VT" <?php if(is_array($designate_producer_states) && in_array("VT", $designate_producer_states)) echo 'checked="checked"'; ?> /> VT 
                            <input type="checkbox" name="designate_producer_license_number[]" value="WA" <?php if(is_array($designate_producer_states) && in_array("WA", $designate_producer_states)) echo 'checked="checked"'; ?> /> WA 
                            <input type="checkbox" name="designate_producer_license_number[]" value="WI" <?php if(is_array($designate_producer_states) && in_array("WI", $designate_producer_states)) echo 'checked="checked"'; ?> /> WI 
                            <input type="checkbox" name="designate_producer_license_number[]" value="WV" <?php if(is_array($designate_producer_states) && in_array("WV", $designate_producer_states)) echo 'checked="checked"'; ?> /> WV 
                            <input type="checkbox" name="designate_producer_license_number[]" value="WY" <?php if(is_array($designate_producer_states) && in_array("WY", $designate_producer_states)) echo 'checked="checked"'; ?> /> WY 
                                                        	
                            <!--</select>-->
                        </div>
                        
                        <br />
                        <h5 style="margin-bottom:10px;"><strong>Designated Responsible Producer Background Questions</strong></h5>
                        <div class="radio_group">
                        	<label class="label-control">Have you ever been charged with a crime in a court of law in any State, Province, Territory, or country? If yes, provide details including date, charges, disposition, and a description of the surrounding circumstances.</label>
							<label for="designatey_producer_back_quise_a1" class="radio-control"><input type="radio" name="designate_producer_back_quise_a" id="designate_producer_back_quise_a1" value="Yes" <?php if(get_user_meta($user_id, 'designate_producer_back_quise_a', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="designate_producer_back_quise_a2" class="radio-control"><input type="radio" name="designate_producer_back_quise_a" id="designate_producer_back_quise_a2" value="No" <?php if(get_user_meta($user_id, 'designate_producer_back_quise_a', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="radio_group">
                        	<label class="label-control">Have you ever been the subject of any administrative or disciplinary action by any insurance authority? If yes, provide full details.</label>
							<label for="designate_producer_back_quise_b1" class="radio-control"><input type="radio" name="designate_producer_back_quise_b" id="designate_producer_back_quise_b1" value="Yes" <?php if(get_user_meta($user_id, 'designate_producer_back_quise_b', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="designate_producer_back_quise_b2" class="radio-control"><input type="radio" name="designate_producer_back_quise_b" id="designate_producer_back_quise_b2" value="No" <?php if(get_user_meta($user_id, 'designate_producer_back_quise_b', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="radio_group">
                        	<label class="label-control">Have you ever had a contract or appointment to sell travel insurance or to act as a travel retailer terminated or cancelled for any reason other than low production? If yes, attach full details.</label>
							<label for="designate_producer_back_quise_c1" class="radio-control"><input type="radio" name="designate_producer_back_quise_c" id="designate_producer_back_quise_c1" value="Yes" <?php if(get_user_meta($user_id, 'designate_producer_back_quise_c', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="designate_producer_back_quise_c2" class="radio-control"><input type="radio" name="designate_producer_back_quise_c" id="designate_producer_back_quise_c2" value="No" <?php if(get_user_meta($user_id, 'designate_producer_back_quise_c', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <h4 style="margin-bottom:10px;"><strong>SECTION IV: APPLICATION ACKNOWLEDGEMENT AND CERTIFICATION</strong></h4>
                        
                        <div class="radio_group">
                        	<p>I hereby certify that all of the information submitted in this application and attachments is true, accurate, and complete.</p>
                            
                            <p>I acknowledge that I understand and will comply with the insurance laws and regulations of the jurisdictions in which I am transacting insurance business.</p>
                            <p>I acknowledge that Berkshire Hathaway Global Insurance Services, LLC and its affiliates may correspond with me by fax, e-mail, or other electronic means. I understand that by certifying this application, I am authorizing Berkshire Hathaway Global Insurance Services, LLC and its affiliates to communicate with me in this manner.</p>
                        </div>
                        
                        <h4 style="margin-bottom:10px;"><strong>SECTION V: CONSUMER REPORT DISCLOSURE</strong></h4>
                        
                        <p>As used in this Application, Company means Berkshire Hathaway Global Insurance Services, LLC and their affiliates, including the insurance carriers who underwrite the insurance products being sold by Berkshire Hathaway Global Insurance Services, LLC.</p>
                        <p>In connection with determining your eligibility for an appointment as an insurance producer with Company, Company may from time to time obtain consumer reports and investigative consumer reports, including Producer Database Reports, about you from a consumer reporting agency. These reports may contain information on your criminal history, credit history, character, general reputation, personal characteristics, and mode of living.</p>
                        <p>Any consumer reports or investigative consumer reports, other than a Producer Database Report, will be obtained through the following consumer reporting agency:</p>
                        <h5><strong>Business Information Group, Inc.</strong></h5>
                        P.O. Box 130, Southampton, PA 18966<br />
                        800-260-1680<br />
                        <p><a href="https://consumercare.bigreport.com" target="_blank">https://consumercare.bigreport.com</a>.</p>
                        <p>Business Information Group Inc.’s privacy practices with respect to the preparation and processing of consumer reports may be found at: <a href="https://consumercare.bigreport.com/privacy-policy.html">https://consumercare.bigreport.com/privacy-policy.html</a>.</p>
						<p>A copy of the Consumer Financial Protection Bureau’s “Summary of Your Rights under the Fair Credit Reporting Act” is attached as a part of this application.</p>
                        
                        <h4 style="margin:25px 0 10px;"><strong>SECTION V: CONSUMER REPORT DISCLOSURE</strong></h4>
                        <p><strong>CALIFORNIA</strong>: You may view the file maintained on you by the consumer reporting agency during normal business hours and on reasonable notice. You may obtain a copy of this file, upon submitting proper identification and paying the costs of duplication services, by appearing at the consumer reporting agency’s offices in person, during normal business hours and on reasonable notice, or by mail. You may also receive a summary of the file by telephone. If you appear in person, you may be accompanied by one other person, provided that person furnishes proper identification.</p>
                        <p><strong>MAINE</strong>: You have the right, upon request, to be informed of whether an investigative consumer report was requested, and if one was requested, the name and address of the consumer reporting agency furnishing the report. You may request and receive from the Company, within five business days of our receipt of your request, the name, address and telephone number of the nearest unit designated to handle inquiries for the consumer reporting agency issuing an investigative consumer report concerning you. You also have the right, under Maine law, to request and promptly receive from all such consumer reporting agencies copies of any such investigative consumer reports.</p>
                        <p><strong>MASSACHUSETTS</strong>: You have the right, upon request, to be informed of whether an investigative consumer report was requested, and if one was requested, you have the right, upon written request, to a copy of that report.</p>
                        <p><strong>NEW YORK</strong>: You have the right, upon written request, to be informed of whether or not a consumer report was requested. If a consumer report is requested, you will be provided with the name and address of the consumer reporting agency furnishing the report. You may inspect and receive a copy of the report by contacting that agency.</p>
                        <p><strong>WASHINGTON</strong>: If the Company requests an investigative consumer report, you have the right upon written request made within a reasonable period of time after your receipt of this disclosure, to receive from the consumer reporting agency a complete and accurate disclosure of the nature and scope of the investigation requested by the Company. You also have the right to request from the consumer reporting agency a written summary of your rights and remedies under the Washington Fair Credit Reporting Act.</p>
                        <h4 style="margin:25px 0 10px;"><strong>SECTION VII: AUTHORIZATION AND CONSENT</strong></h4>
                        <p>I have carefully read this Producer Appointment Application form, including all disclosures and authorizations and the attached copy of the Consumer Financial Protection Bureau’s “Summary of Your Rights under the Fair Credit Reporting Act”. I hereby authorize Company to obtain consumer reports and investigative consumer reports about me. I authorize (a) a consumer reporting agency to request information about me from any public or private information sources; (b) anyone to provide information about me to a
consumer reporting agency; (c) a consumer reporting agency to provide Company with one or more reports based on that information; and (d) Company to share those reports with others for legitimate business purposes related to my appointment. I further consent to Company obtaining such reports and information from time to time, as Company, in its sole discretion, deems necessary. This is a continuing authorization and consent that, to the extent permitted by law, will apply for so long as I am applying for an appointment with Company or hold an appointment for Company.</p>
						<div class="radio_group">
                        	<label for="authorization_oklahoma" class="radio-control"><input type="checkbox" name="authorization_oklahoma" id="authorization_oklahoma" value="Yes" <?php if(get_user_meta($user_id, 'authorization_oklahoma', true) == "Yes") echo 'checked="checked"'; ?> /><strong>CALIFORNIA*, MINNESOTA OR OKLAHOMA APPLICANTS:</strong> You may request a copy of any reports received by Company by checking
this box:</label>
                        </div>
                        
                        <p><strong>*California Applicants:</strong> If you chose to receive a copy of the consumer report, it will be sent within three (3) days of the Company receiving a copy of the consumer report and you will receive a copy of the investigative consumer report within seven (7) days of the Company’s receipt of the report (unless you elected not to get a copy of the report).</p>
                        
                        <p>&nbsp;</p>
                        <div class="two_third">
                             	<label for="l3_authorization_name" class="label-control require">NAME & TITLE</label>
                       	  		<input type="text" name="l3_authorization_name" id="l3_authorization_name" value="<?php echo get_user_meta($user_id, 'l3_authorization_name', true); ?>" />
                             </div>
                             <div class="one_third et_column_last">
                             	<label for="l3_authorization_date" class="label-control require">DATE</label>
                       	  		<input type="text" name="l3_authorization_date" id="l3_authorization_date" value="<?php if(get_user_meta($user_id, 'l3_authorization_date', true)) { echo get_user_meta($user_id, 'l3_authorization_date', true); } else { echo date("Y-m-d"); } ?>" />
                             </div>
                             <div class="clearfix"></div>
                            <label class="label-control require">APPLICANT/ AUTHORIZED SIGNATURE</label>
                            <div class="signature-wrapper" style="margin-bottom:25px;">
                                <div class="sig-pad" style="border-color:#E2E2E2; margin-bottom:5px;"></div>
                                <br />(Use mouse or stylus pen to sign)
                                <br />
                                <?php
                                if( get_user_meta($user_id, 'l3_authorization_signature', true) ) {
                                    echo '<img src="'.get_user_meta($user_id, 'l3_authorization_signature', true).'" alt="" /><br />';
                                }
                                ?>
                                <span class="sig-done">Done</span>
                                <span class="sig-clear">Clear</span>
                                <?php if( get_user_meta($user_id, 'l3_authorization_signature', true) ) { ?>
                                <textarea id="l3_authorization_signature_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } else { ?>
                                <textarea id="l3_authorization_signature_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } ?>
                          </div><!-- .signature-wrapper -->
                      
                        <!--<p>Para información en español, visite www.consumerfinance.gov/learnmore o escriba a la Consumer Financial Protection Bureau, 1700 G Street N.W., Washington, DC 20552.</p>
                        <p><strong>A Summary of Your Rights Under the Fair Credit Reporting Act</strong></p>
                        <p>The federal Fair Credit Reporting Act (FCRA) promotes the accuracy, fairness, and privacy of information in the files of consumer reporting agencies. There are many types of consumer reporting agencies, including credit bureaus and specialty agencies (such as agencies that sell information about check writing histories, medical records, and rental history records). Here is a summary of your major rights under the FCRA. <strong>For more information, including information about additional rights, go to:</strong></p>
                        <p><a href="http://www.consumerfinance.gov/learnmore">www.consumerfinance.gov/learnmore</a> <strong>or write to: Consumer Financial Protection Bureau, 1700 G Street N.W., Washington, DC 20552.</strong></p>
                        
                        <p><strong> You must be told if information in your file has been used against you.</strong> Anyone who uses a credit report or another type of consumer report to deny your application for credit, insurance, or employment—or to take another adverse action against you—must tell you, and must give you the name, address, and phone number of the agency that provided the information.</p>
                        
                        <p><strong>You have the right to know what is in your file.</strong> You may request and obtain all the information about you in the files of a consumer reporting agency (your “file disclosure”). You will be required to provide proper identification, which may include your Social Security number. In many cases, the disclosure will be free. You are entitled to a free file disclosure if:</p>
                        
                        <ul>
                        	<li><strong>a)</strong> a person has taken adverse action against you because of information in your credit report;</li>
                            <li><strong>b)</strong> you are the victim of identity theft and place a fraud alert in your file;</li>
                            <li><strong>c)</strong> your file contains inaccurate information as a result of fraud;</li>
                            <li><strong>d)</strong> you are on public assistance;</li>
                            <li><strong>e)</strong> you are unemployed but expect to apply for employment within 60 days.</li>
                        </ul>
                        
                        <p>In addition, all consumers are entitled to one free disclosure every 12 months upon request from each nationwide credit bureau and from nationwide specialty consumer reporting agencies. See <a href="www.consumerfinance.gov/learnmore">www.consumerfinance.gov/learnmore</a> for additional information.</p>
                        
                        <p><strong>You have the right to ask for a credit score.</strong> Credit scores are numerical summaries of your credit-worthiness based on information from credit bureaus. You may request a credit score from consumer reporting agencies that create scores or distribute scores used in residential real property loans, but you will have to pay for it. In some mortgage transactions, you will receive credit score information for free from the mortgage lender.</p>
                        
                        <p><strong>You have the right to dispute incomplete or inaccurate information.</strong> If you identify information in your file that is incomplete or inaccurate, and report it to the consumer
reporting agency, the agency must investigate unless your dispute is frivolous. See www.consumerfinance.gov/learnmore for an explanation of dispute procedures.</p>

<p><strong>Consumer reporting agencies must correct or delete inaccurate, incomplete, or unverifiable information</strong>. Inaccurate, incomplete or unverifiable information must be removed
or corrected, usually within 30 days. However, a consumer reporting agency may continue to report information it has verified as accurate.</p>
<p><strong>Consumer reporting agencies may not report outdated negative information.</strong> In most cases, a consumer reporting agency may not report negative information that is more
than seven years old, or bankruptcies that are more than 10 years old.</p>

<p><strong>Access to your file is limited.</strong> A consumer reporting agency may provide information about you only to people with a valid need -- usually to consider an application with a
creditor, insurer, employer, landlord, or other business. The FCRA specifies those with a valid need for access.</p>

<p><strong>You must give your consent for reports to be provided to employers.</strong> A consumer reporting agency may not give out information about you to your employer, or a potential
employer, without your written consent given to the employer. Written consent generally is not required in the trucking industry. For more information, go to
www.consumerfinance.gov/learnmore.</p>

<p><strong>You may limit “prescreened” offers of credit and insurance you get based on information in your credit report.</strong> Unsolicited “prescreened offers” for credit and insurance must
include a toll-free phone number you can call if you choose to remove your name and address from the lists these offers are based on. You may opt-out with the nationwide
credit bureaus at 1-888-567-8688.</p>

<p><strong>You may seek damages from violators.</strong> If a consumer reporting agency, or, in some cases, a user of consumer reports or a furnisher of information to a consumer reporting
agency violates the FCRA, you may be able to sue in state or federal court.</p>

<p><strong>Identity theft victims and active duty military personnel have additional rights.</strong> For more information, visit <a href="www.consumerfinance.gov/learnmore">www.consumerfinance.gov/learnmore</a>. <strong>States may enforce the
FCRA, and many states have their own consumer reporting laws. In some cases, you may have more rights under state law. For more information, contact your state or local
consumer protection agency or your state Attorney General. For information about your federal rights, contact:</strong></p>

						<h5 style="margin-bottom:10px;"><strong>TYPE OF BUSINESS: CONTACT:</strong></h5>
                        <ul>
                        	<li>1.a. Banks, savings associations, and credit unions with total assets of over $10 billion and their affiliates; b. Such affiliates that are not banks, savings associations, or credit unions also should list, in addition to the CFPB:</li>
                            <li>2. To the extent not included in item 1 above: a. National banks, federal savings associations, and federal branches and federal agencies of foreign
banks; b. State member banks, branches and agencies of foreign banks (other than federal branches, federal agencies, and Insured State Branches of Foreign Banks), commercial lending companies owned or controlled by foreign banks, and organizations operating under section 25 or 25A of the Federal Reserve Act; c. Nonmember Insured Banks, Insured State Branches of Foreign Banks, and insured state savings associations; d. Federal Credit Unions</li>
                            <li>3. Air carriers</li>
                            <li>4. Creditors Subject to the Surface Transportation Board</li>
                            <li>5. Creditors Subject to Packers and Stockyards Act, 1921</li>
                            <li>6. Small Business Investment Companies</li>
                            <li>7. Brokers and Dealers</li>
                            <li>8. Federal Land Banks, Federal Land Bank Associations, Federal Intermediate Credit Banks, and Production Credit Associations</li>
                            <li>9. Retailers, Finance Companies, and All Other Creditors Not Listed Above</li>
                        </ul>
                        
                        <h5 style="margin-bottom:10px;"><strong>TYPE OF BUSINESS: CONTACT:</strong></h5>
                        <p>a. Consumer Financial Protection Bureau, 1700 G Street NW, Washington, DC 20552<br />
b. Federal Trade Commission: Consumer Response Center—FCRA, Washington, DC 20580,
(877) 382- 4357</p>
<p>a. Office of the Comptroller of the Currency, Customer Assistance Group, 1301 McKinney
Street, Suite 3450, Houston, TX 77010-9050<br />
b. Federal Reserve Consumer Help Center, P.O. Box 1200, Minneapolis, MN 55480<br />
c. FDIC Consumer Response Center, 1100 Walnut Street, Box #11, Kansas City, MO 64106<br />
d. National Credit Union Administration, Office of Consumer Protection (OCP), Division of
Consumer Compliance and Outreach (DCCO), 1775 Duke Street, Alexandria, VA 22314</p>
<p>Asst. General Counsel for Aviation Enforcement & Proceedings, Aviation Consumer
Protection Division, Department of Transportation, 1200 New Jersey Avenue, S.E.,
Washington, DC 20590</p>
<p>Office of Proceedings, Surface Transportation Board, Department of Transportation, 395 E.
Street, S.W., Washington, DC 20423</p>
<p>Nearest Packers and Stockyards Administration area supervisor</p>

<p>Associate Deputy Administrator for Capital Access, United States Small Business
Administration, 409 Third Street, SW, 8th Floor, Washington, DC 20416</p>
<p>Securities and Exchange Commission, 100 F St., N.E., Washington, DC 20549</p>

<p>Farm Credit Administration, 1501 Farm Credit Drive, McLean, VA 22102-5090</p>
<p>FTC Regional Office for region in which the creditor operates or Federal Trade
Commission: Consumer Response Center – FCRA, Washington, DC 20580, (877) 382-4357</p>-->
                        
                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-step">Back</a>
							<a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-step">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="licensing-appointments-step3" />
                        <input type="hidden" name="licensing_appointments_step3_progress" id="licensing_appointments_step3_progress" value="<?php echo get_user_meta($user_id, 'licensing_appointments_step3_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_9-->
                

                <div class="company_info_steps company_info_step_9" id="licensing_tab4" data-step="4" data-id-base="licensing_tab" style="display:none;">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="license-appointment4" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Licensing & Appointments <span class="step_active">4</span> of 5</h1>
                        <div class="line"></div>

                        <?php //if(get_user_meta($user_id, 'sign_las4_docusign_form', true) == "Yes") { ?>
                        <!--<p class="already-signed">This PDF has been completed and signed. Please Click "Next" to Continue & Save the PDF - Thank You.</p>
                        <?php //} ?>
                        <!--<div class="form_cont">
                            <iframe id="dswpf" src="https://www.docusign.net/Member/PowerFormSigning.aspx?PowerFormId=118625e6-cd32-46c6-aaa7-9268466c9293"/>
                        </div>-->
                        <!--<label for="sign_las4_docusign_form" class="label-control require">Have you accurately completed the above PDF and Signed it?</label>
                        <select name="sign_las4_docusign_form" id="sign_las4_docusign_form" class="docusign-form-done">
                            <option value="">Please Select</option>
                            <option value="Yes" <?php //if(get_user_meta($user_id, 'sign_las4_docusign_form', true) == "Yes") echo 'selected="selected"'; ?>>Yes</option>
                            <option value="No" <?php //if(get_user_meta($user_id, 'sign_las4_docusign_form', true) == "No") echo 'selected="selected"'; ?>>No</option>
                        </select>-->
                        
                        <h4 style="margin-bottom:10px; font-weight:bold;">SECTION I – INDIVIDUAL AGENT INFORMATION</h4>
                          <div class="three_col">
                        	<label for="individual_agent_info_first_name" class="label-control">First Name:</label>
                           <input name="individual_agent_info_first_name" id="individual_agent_info_first_name" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_first_name', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="individual_agent_info_middle_name" class="label-control">Middle Name:</label>
                           <input name="individual_agent_info_middle_name" id="individual_agent_info_middle_name" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_middle_name', true); ?>" />
                        </div>
                        
                        <div class="three_col last">
                        	<label for="individual_agent_info_last_name" class="label-control">Last Name:</label>
                           <input name="individual_agent_info_last_name" id="individual_agent_info_last_name" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_last_name', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="three_col">
                        	<label for="individual_agent_info_social_security" class="label-control">Social Security Number:</label>
                           <input name="individual_agent_info_social_security" id="individual_agent_info_social_security" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_social_security', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="individual_agent_info_birth_day" class="label-control">Date of Birth:</label>
                           <input name="individual_agent_info_birth_day" id="individual_agent_info_birth_day" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_birth_day', true); ?>" />
                        </div>
                        
                        <div class="three_col last" style="margin-bottom:20px;">
                        	<label class="label-control">Gender</label>
							<label for="individual_agent_info_gender1" class="radio-control"><input type="radio" name="individual_agent_info_gender" id="individual_agent_info_gender1" value="M" <?php if(get_user_meta($user_id, 'individual_agent_info_gender', true) == "M") echo 'checked="checked"'; ?> />M</label>
                            <label for="individual_agent_info_gender2" class="radio-control"><input type="radio" name="individual_agent_info_gender" id="individual_agent_info_gender2" value="F" <?php if(get_user_meta($user_id, 'individual_agent_info_gender', true) == "F") echo 'checked="checked"'; ?> />F</label>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col">
                        	<label for="individual_agent_info_home_address" class="label-control">Home Address:</label>
                           <input name="individual_agent_info_home_address" id="individual_agent_info_home_address" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_home_address', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="individual_agent_info_street" class="label-control">Street:</label>
                           <input name="individual_agent_info_street" id="individual_agent_info_street" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_street', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="individual_agent_info_city" class="label-control">City:</label>
                           <input name="individual_agent_info_city" id="individual_agent_info_city" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_city', true); ?>" />
                        </div>
                        
                        <div class="three_col last">
                        	<label for="individual_agent_info_state" class="label-control">State:</label>
                           <input name="individual_agent_info_state" id="individual_agent_info_state" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_state', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="one_half">
                        	<label for="individual_agent_info_zip" class="label-control">ZIP:</label>
                           <input name="individual_agent_info_zip" id="individual_agent_info_zip" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_zip', true); ?>" />
                        </div>
                        
                        <div class="one_half et_column_last">
                        	<label for="individual_agent_info_county" class="label-control">County:</label>
                           <input name="individual_agent_info_county" id="individual_agent_info_county" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_county', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <div class="three_col">
                        	<label for="individual_agent_info_home_phone" class="label-control">Home Phone Number:</label>
                           <input name="individual_agent_info_home_phone" id="individual_agent_info_home_phone" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_home_phone', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="individual_agent_info_home_fax" class="label-control">Home Fax Number:</label>
                           <input name="individual_agent_info_home_fax" id="individual_agent_info_home_fax" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_home_fax', true); ?>" />
                        </div>
                        
                        <div class="three_col last">
                        	<label for="individual_agent_info_email" class="label-control">Email Address:</label>
                           <input name="individual_agent_info_email" id="individual_agent_info_email" type="text" value="<?php echo get_user_meta($user_id, 'individual_agent_info_email', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <h4 style="margin-bottom:15px;"><strong>SECTION II – AGENCY INFORMATION</strong></h4>
                        
                        <div class="radio_group">
                        	<label class="label-control">The Agency is a:</label>
							<label for="l4_agency_info_type1" class="radio-control"><input type="radio" name="l4_agency_info_type" id="l4_agency_info_type1" value="Individual/Sole Proprietorship" <?php if(get_user_meta($user_id, 'l4_agency_info_type', true) == "Individual/Sole Proprietorship") echo 'checked="checked"'; ?> />Individual/Sole Proprietorship</label>
                            
                            <label for="l4_agency_info_type2" class="radio-control"><input type="radio" name="l4_agency_info_type" id="l4_agency_info_type2" value="Partnership or LLC" <?php if(get_user_meta($user_id, 'l4_agency_info_type', true) == "Partnership or LLC") echo 'checked="checked"'; ?> />Partnership or LLC</label>
                            
                            <label for="l4_agency_info_type3" class="radio-control"><input type="radio" name="l4_agency_info_type" id="l4_agency_info_type3" value="Corporation" <?php if(get_user_meta($user_id, 'l4_agency_info_type', true) == "Corporation") echo 'checked="checked"'; ?> />Corporation</label>
                            
                            <label for="l4_agency_info_type4" class="radio-control"><input type="radio" name="l4_agency_info_type" id="l4_agency_info_type4" value="Other" <?php if(get_user_meta($user_id, 'l4_agency_info_type', true) == "Other") echo 'checked="checked"'; ?> />Other</label>
                        </div>
                        
                        <div class="two_third">
                        	<label for="l4_agency_info_name" class="label-control">Business/Agency Name:</label>
                           <input name="l4_agency_info_name" id="l4_agency_info_name" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_name', true); ?>" />
                        </div>
                        
                        <div class="one_third et_column_last">
                        	<label for="l4_agency_info_ein_number" class="label-control">EIN Number (For Agency Pay):</label>
                           <input name="l4_agency_info_ein_number" id="l4_agency_info_ein_number" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_ein_number', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col">
                        	<label for="l4_agency_info_street_address" class="label-control">Agency Street Address:</label>
                           <input name="l4_agency_info_street_address" id="l4_agency_info_street_address" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_street_address', true); ?>" />
                        </div>
                        
                        <div class="one_fourth">
                        	<label for="l4_agency_info_city" class="label-control">City:</label>
                           <input name="l4_agency_info_city" id="l4_agency_info_city" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_city', true); ?>" />
                        </div>
                        
                        <div class="one_fourth">
                        	<label for="l4_agency_info_state" class="label-control">State:</label>
                           <input name="l4_agency_info_state" id="l4_agency_info_state" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_state', true); ?>" />
                        </div>
                        
                        <div class="one_fourth">
                        	<label for="l4_agency_info_zip" class="label-control">ZIP:</label>
                           <input name="l4_agency_info_zip" id="l4_agency_info_zip" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_zip', true); ?>" />
                        </div>
                        
                        <div class="one_fourth et_column_last">
                        	<label for="l4_agency_info_county" class="label-control">County:</label>
                           <input name="l4_agency_info_county" id="l4_agency_info_county" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_county', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col">
                        	<label for="l4_agency_info_mailing_address" class="label-control">Agency Mailing Address:</label>
                           <input name="l4_agency_info_mailing_address" id="l4_agency_info_mailing_address" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_mailing_address', true); ?>" />
                        </div>
                        
                        <div class="one_fourth">
                        	<label for="l4_agency_info_mailing_city" class="label-control">City:</label>
                           <input name="l4_agency_info_mailing_city" id="l4_agency_info_mailing_city" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_mailing_city', true); ?>" />
                        </div>
                        
                        <div class="one_fourth">
                        	<label for="l4_agency_info_mailing_state" class="label-control">State:</label>
                           <input name="l4_agency_info_mailing_state" id="l4_agency_info_mailing_state" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_mailing_state', true); ?>" />
                        </div>
                        
                        <div class="one_fourth">
                        	<label for="l4_agency_info_mailing_zip" class="label-control">ZIP:</label>
                           <input name="l4_agency_info_mailing_zip" id="l4_agency_info_mailing_zip" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_mailing_zip', true); ?>" />
                        </div>
                        
                        <div class="one_fourth et_column_last">
                        	<label for="l4_agency_info_mailing_county" class="label-control">County:</label>
                           <input name="l4_agency_info_mailing_county" id="l4_agency_info_mailing_county" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_mailing_county', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        
                        <div class="three_col">
                        	<label for="l4_agency_info_phone" class="label-control">Agency Phone Number:</label>
                           <input name="l4_agency_info_phone" id="l4_agency_info_phone" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_phone', true); ?>" />
                        </div>
                        
                        <div class="three_col">
                        	<label for="l4_agency_info_fax" class="label-control">Agency Fax Number:</label>
                           <input name="l4_agency_info_fax" id="l4_agency_info_fax" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_fax', true); ?>" />
                        </div>
                        
                        <div class="three_col last">
                        	<label for="l4_agency_info_email" class="label-control">Agency Email Address:</label>
                           <input name="l4_agency_info_email" id="l4_agency_info_email" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_email', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="full_col">
                        	<label for="l4_agency_info_health_license" class="control-label">State(s) in which to be appointed. Please attach copy(ies) of the current health license(s):</label>
                            <input name="l4_agency_info_health_license" id="l4_agency_info_health_license" type="text" value="<?php echo get_user_meta($user_id, 'l4_agency_info_health_license', true); ?>" />
                        </div>   
                        
                        <h4 style="margin-bottom:15px;"><strong>SECTION III – BROKER/AGENCY QUESTIONNAIRE</strong></h4>  
                        <!--<h5><strong>A letter of explanation must be attached on any “Yes” answer to the following questions.</strong></h5>--> 
                                        
                        <div class="radio_group">
                        	<label class="label-control">1. Have you ever been convicted of any criminal activity involving dishonesty or a breach of trust?</label>
							<label for="l4_agency_questionnaire_a1" class="radio-control"><input type="radio" name="l4_agency_questionnaire_a" id="l4_agency_questionnaire_a1" value="Yes" <?php if(get_user_meta($user_id, 'l4_agency_questionnaire_a', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="l4_agency_questionnaire_a2" class="radio-control"><input type="radio" name="l4_agency_questionnaire_a" id="l4_agency_questionnaire_a2" value="No" <?php if(get_user_meta($user_id, 'l4_agency_questionnaire_a', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>  
                        
                        
                        <div class="radio_group">
                        	<label class="label-control">2. Have you ever been convicted or are currently under indictment for any criminal felony?</label>
							<label for="l4_agency_questionnaire_b1" class="radio-control"><input type="radio" name="l4_agency_questionnaire_b" id="l4_agency_questionnaire_b1" value="Yes" <?php if(get_user_meta($user_id, 'l4_agency_questionnaire_b', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="l4_agency_questionnaire_b2" class="radio-control"><input type="radio" name="l4_agency_questionnaire_b" id="l4_agency_questionnaire_b2" value="No" <?php if(get_user_meta($user_id, 'l4_agency_questionnaire_b', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        
                        <div class="radio_group">
                        	<label class="label-control">3. Have you ever had a license or an appointment cancelled by an insurer for reasons other than low production?</label>
							<label for="l4_agency_questionnaire_c1" class="radio-control"><input type="radio" name="l4_agency_questionnaire_c" id="l4_agency_questionnaire_c1" value="Yes" <?php if(get_user_meta($user_id, 'l4_agency_questionnaire_c', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="l4_agency_questionnaire_c2" class="radio-control"><input type="radio" name="l4_agency_questionnaire_c" id="l4_agency_questionnaire_c2" value="No" <?php if(get_user_meta($user_id, 'l4_agency_questionnaire_c', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>
                        
                        <div class="radio_group">
                        	<label class="label-control">4. Have you ever been suspended, disqualified or disciplined as a member of any profession?</label>
							<label for="l4_agency_questionnaire_d1" class="radio-control"><input type="radio" name="l4_agency_questionnaire_d" id="l4_agency_questionnaire_d1" value="Yes" <?php if(get_user_meta($user_id, 'l4_agency_questionnaire_d', true) == "Yes") echo 'checked="checked"'; ?> />Yes</label>
                            <label for="l4_agency_questionnaire_d2" class="radio-control"><input type="radio" name="l4_agency_questionnaire_d" id="l4_agency_questionnaire_d2" value="No" <?php if(get_user_meta($user_id, 'l4_agency_questionnaire_d', true) == "No") echo 'checked="checked"'; ?> />No</label>
                        </div>   
                        
                        <p>I hereby authorize Nationwide and its representatives to make an independent investigation of my background, references, character, past employment, education, and criminal or police record, including those mandated by both public and private organizations and all public records for the purpose of confirming the information contained on this form and all other obtained information which may be material to my qualifications for licensing and/or appointment.</p>  
                        <p>I release Nationwide, its representatives, and any other person or entity, which provides information pursuant to this authorization, from any and all liabilities, claims or lawsuits in regards to the information obtained from any and all of the above referenced sources used.</p> 
                        
                        <h4 style="margin:20px 0 15px;"><strong>SECTION IV – SIGNATURE</strong></h4>  
                        <h5><strong>I certify that to the best of my knowledge and belief, the above information is correct and complete.</strong></h5>
                        
                        
                        <p>&nbsp;</p>
                        <div class="two_third">
                             	<label for="l4_agency_sign_name" class="label-control require">Print Name</label>
                       	  		<input type="text" name="l4_agency_sign_name" id="l4_agency_sign_name" value="<?php echo get_user_meta($user_id, 'l4_agency_sign_name', true); ?>" />
                             </div>
                             <div class="one_third et_column_last">
                             	<label for="l4_agency_sign_date" class="label-control require">DATE</label>
                       	  		<input type="text" name="l4_agency_sign_date" id="l4_agency_sign_date" value="<?php if(get_user_meta($user_id, 'l4_agency_sign_date', true)) { echo get_user_meta($user_id, 'l4_agency_sign_date', true); } else { echo date("Y-m-d"); } ?>" />
                             </div>
                             <div class="clearfix"></div>
                            <label class="label-control require">Signature</label>
                            <div class="signature-wrapper" style="margin-bottom:25px;">
                                <div class="sig-pad" style="border-color:#E2E2E2; margin-bottom:5px;"></div>
                                <br />(Use mouse or stylus pen to sign)
                                <br />
                                <?php
                                if( get_user_meta($user_id, 'l4_agency_signature', true) ) {
                                    echo '<img src="'.get_user_meta($user_id, 'l4_agency_signature', true).'" alt="" /><br />';
                                }
                                ?>
                                <span class="sig-done">Done</span>
                                <span class="sig-clear">Clear</span>
                                <?php if( get_user_meta($user_id, 'l4_agency_signature', true) ) { ?>
                                <textarea id="l4_agency_signature_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } else { ?>
                                <textarea id="l4_agency_signature_png" name="sig_data" cols="10" rows="5" class="sig-data"></textarea>
                                <?php } ?>
                          </div><!-- .signature-wrapper -->
                        
                                      
                        
                        
                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-step">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-step">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="licensing-appointments-step4" />
                        <input type="hidden" name="licensing_appointments_step4_progress" id="licensing_appointments_step4_progress" value="<?php echo get_user_meta($user_id, 'licensing_appointments_step4_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_9-->


                <div class="company_info_steps company_info_step_9" id="licensing_tab5" data-step="5" data-tab="8" data-id-base="licensing_tab" data-tab-index="prev" style="display:none;">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="license-appointment5" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Licensing & Appointments <span class="step_active">5</span> of 5</h1>
                        <div class="line"></div>
                        
                        <div class="full_col">
                            <label class="label-control require">Licensed Person at your company (please spell out complete name) </label>
                        </div>
                        <div class="three_col">
                            <input type="text" name="licensed_person_first_name" id="licensed_person_first_name" placeholder="First Name" value="<?php echo get_user_meta($user_id, 'licensed_person_first_name', true); ?>" />
                        </div>
                        <div class="three_col">
                            <input type="text" name="licensed_person_last_name" id="licensed_person_last_name" placeholder="Last Name" value="<?php echo get_user_meta($user_id, 'licensed_person_last_name', true); ?>" />
                        </div>
                        <div class="three_col last">
                        </div>
                        <div class="clearfix"></div>
                        <div class="full_col">
                            <label for="client_office_address" class="label-control require">Client Office Address</label>
                            <input type="text" name="client_office_address" id="client_office_address" placeholder="Address" value="<?php echo get_user_meta($user_id, 'client_office_address', true); ?>" />
                        </div><!--full_col-->
                        
                        <div class="three_col">
                            <label for="social_security_number" class="label-control require">Social Security Number</label>
                            <input type="text" name="social_security_number" id="social_security_number" placeholder="Social Security Number" value="<?php echo get_user_meta($user_id, 'licensed_person_social_security_number', true); ?>" />
                        </div>
                        <div class="three_col">
                            <label for="licensed_person_phone_number" class="label-control require">Licensed Person's Phone</label>
                            <input type="tel" name="licensed_person_phone_number" id="licensed_person_phone_number" placeholder="Phone Number" value="<?php echo get_user_meta($user_id, 'licensed_person_phone_number', true); ?>" />
                        </div>
                        <div class="three_col last">
                            <label for="licensed_person_email_address" class="label-control require">Licensed Person's Email</label>
                            <input type="email" name="licensed_person_email_address" id="licensed_person_email_address" placeholder="Email" value="<?php echo get_user_meta($user_id, 'licensed_person_email_address', true); ?>" />
                        </div>
                        <div class="clearfix"></div>
                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-step">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="licensing-appointments-step5" />
                        <input type="hidden" name="licensing_appointments_step5_progress" id="licensing_appointments_step5_progress" value="<?php echo get_user_meta($user_id, 'licensing_appointments_step5_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_9-->


                <div class="company_info_steps company_info_step_7" id="payment_tab" data-tab="9">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                    <h1 class="company_info_title">Payment Details</h1>
                    <!--<p>Thank you so much for completing our onboarding process.  Please sign below and verify that everything to the best of your knowledge is correct and that you have been truthful with all responses.</p>-->
                    <div class="line"></div>
                    
						<form id="payment-form" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                            <div class="payment-details"><!--style="max-width:520px; margin:0 auto;"-->
                                <?php //echo do_shortcode('[fullstripe_payment form="ApplicationPayment"]'); ?>
                                <!--<div style="text-align:center;"><input type="checkbox" name="bill_me_later" id="bill_me_later" value="Bill Me Later" <?php //if(get_user_meta($user_id, 'bill_me_later', true) == "Bill Me Later") echo 'checked="checked"'; ?> style="vertical-align: middle;" /> <label for="bill_me_later" style="vertical-align: middle;">Bill Me Later</label></div>-->
    
                                <p>The Department of Insurance requires a fee to be paid by the licensed party to complete the licensing process. You will be billed $100 for this service.</p>
    
                                <p style="max-width:520px; margin:0 auto;"><label for="pay_signature" class="label-control require">Please Enter Your Name Below</label><input type="text" name="pay_signature" id="pay_signature" value="<?php echo get_user_meta($user_id, 'pay_signature', true); ?>" /></p>
    
                            <p>By signing this signature box, you are authorizing InsureStays to complete the licensing documentation on your behalf with the information you have provided and also agreeing to the $100 cost associated with filing your license paperwork with the Department of Insurance.</p>
                            </div>
                            <div class="button_submit_wrap">
                                <a href="javascript:void(0);" class="btn_back_link" data-action="prev-tab">Back</a>
                                <!--<a href="javascript:void(0);" class="btn_next" data-action="submitted-tab">Submit Onboarding App</a>-->
                                <a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>
                            </div><!--button_submit_wrap-->
                            <input type="hidden" name="submitted_form" value="payment-form" />
                            <input type="hidden" name="payment_progress" id="payment_progress" value="<?php echo get_user_meta($user_id, 'payment_progress', true); ?>" class="hidden-progress" />
                        </form>
                </div><!--company_info_steps company_info_step_7-->
                <?php } ?>
                
                
                <div class="company_info_steps company_info_step_7" id="signature_tab" data-tab="10">
                    <div class="form_header clearfix">
                        <span class="complete_parcentage">0%</span>
                        <span class="progress">PROGRESS</span>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </div><!--form_header-->
                	<form id="signature-form" class="current-step-form" action="<?php echo get_stylesheet_directory_uri(); ?>/ajaxform.php" method="post" enctype="multipart/form-data">
                        <h1 class="company_info_title">Signature / Completion</h1>
                        <p>Thank you so much for completing our onboarding process.  Please sign below and verify that everything to the best of your knowledge is correct and that you have been truthful with all responses.</p>
                        <div class="line"></div>
                        
                        <div class="e_signature" style="max-width:520px; margin:0 auto;">
                            <label for="e_signature" class="label-control require">e-Signature: Please Enter Your Name Below</label>
                            <input type="text" name="e_signature" id="e_signature" value="<?php echo get_user_meta($user_id, 'e_signature', true); ?>" />
                            <br /><span class="sig-save" style="cursor: pointer;display: inline-block;background: #F4AB10;color: #333333;padding: 8px 15px;">Save</span>
                        </div>
                        
                        <!-- Testing Signature Pad -->
                        <!--<div class="signature-wrapper">
                            <div class="sig-pad" style="border-color:#E2E2E2; margin-bottom:5px;"></div>
                            <br />
                            <span class="sig-done">Done</span>
                            <span class="sig-clear">Clear</span>
                            <textarea id="sig_png" name="sig_data" cols="10" rows="5" readonly="readonly" class="sig-data"></textarea>
                        </div>--><!-- .signature-wrapper -->
                        
                        <!--<div class="signature-wrapper">
                            <div class="sig-pad" style="border-color:#E2E2E2; margin-bottom:5px;"></div>
                            <br />
                            <span class="sig-done">Done</span>
                            <span class="sig-clear">Clear</span>
                            <textarea id="w9_png" name="sig_data2" cols="10" rows="5" readonly="readonly" class="sig-data"></textarea>
                        </div>--><!-- .signature-wrapper -->

                        <div class="button_submit_wrap">
                            <a href="javascript:void(0);" class="btn_back_link" data-action="prev-tab">Back</a>
                            <a href="javascript:void(0);" class="btn_back_link" data-action="save-pdf" style="background: #F4AB10; color: #333333;">Save as PDF</a>
                            <!--<a href="javascript:void(0);" class="btn_next" data-action="next-tab">Next</a>-->
                            <a href="javascript:void(0);" class="btn_next" data-action="submitted-tab">Submit Onboarding App</a>
                        </div><!--button_submit_wrap-->
                        <a href="#" target="_blank" class="pdf_gen">Click here to view PDF</a>
                        <input type="hidden" name="submitted_form" value="signature" />
                        <input type="text" name="invisible_captcha" id="invisible_captcha" value="" style="display:none !important;" />
                        <input type="hidden" name="signature_progress" id="signature_progress" value="<?php echo get_user_meta($user_id, 'signature_progress', true); ?>" class="hidden-progress" />
                    </form>
                </div><!--company_info_steps company_info_step_7-->


                <div class="company_info_steps company_info_step_8" id="app_submitted_tab" data-tab="11" style="display:none;">
                	<div class="signout_wrap" style="max-width:520px; margin:0 auto">
                	<img class="aligncenter" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon_complete.png" />
                	<h1 class="company_info_title" style="margin-top:25px;">Application Submitted</h1>
                    <!--<p>We are extremely excited to start working with you and your team!! Someone from the RentalGuardian team will be following up with you once your onboarding app has been processed.  Once processed and approved, you will receive a final account activation email and then you will be live!  We look forward to a terrific partnership!</p>-->
                    <p style="text-align:center;">Thank you for completing the RentalGuardian.com onboarding app. We will immediately begin processing your app and you should be hearing from our team within the next 48 to 72 hours about how to activate your account. Thanks again and we look forward to launching our partnership with you and your team!</p>
                    </div>
                	<div class="button_submit_wrap" style="border-top:none;">
                        <a style="background:#666666;" href="<?php echo wp_logout_url(); ?>" class="btn_next">sign out</a>
                    </div><!--button_submit_wrap-->
                </div><!--company_info_steps company_info_step_8-->

              </div><!-- .company_info_form -->
              <?php //} else { ?>
                <!--<div id="company_info_form" class="company_info_form">
                    <div class="company_info_steps">
                        <div class="form_header clearfix">
                            <a href="<?php //echo wp_logout_url(); ?>" class="btn_signout">Sign Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
                        </div>
                        <div class="signout_wrap" style="max-width:520px; margin:0 auto">
                        <img class="aligncenter" src="<?php //echo get_stylesheet_directory_uri(); ?>/images/icon_complete.png" />
                        <h1 class="company_info_title" style="margin-top:25px;">Application Submitted</h1>
                            <p style="text-align:center;">Thank you for completing the RentalGuardian.com onboarding app. We will immediately begin processing your app and you should be hearing from our team within the next 48 to 72 hours about how to activate your account. Thanks again and we look forward to launching our partnership with you and your team!</p>
                        </div>
                        <div class="button_submit_wrap" style="border-top:none;">
                            <a style="background:#666666;" href="<?php //echo wp_logout_url(); ?>" class="btn_next">sign out</a>
                        </div>
                    </div>
                </div>-->
              <?php //} ?>
        </div>
    <?php 
	}
	$output_content = ob_get_clean();
	return $output_content;
}


add_shortcode('display-register-form', 'get_register_form');
function get_register_form(){
	ob_start();
	if( isset($_POST['wp_submit']) ) {
		$user_full_name = $_POST['user_full_name'];
		$user_email = $_POST['user_email'];
		$user_password = $_POST['user_password'];
		//$password = wp_generate_password( 8, false );
		
		if( username_exists( $user_email ) || email_exists( $user_email ) ) {
			$error = '<p class="error" style="margin:10px 0 0;">This email is already in use!</p>';
		} else {
			$userdata = array(
				'user_login'  =>  $user_email,
				'user_email'  =>  $user_email,
				'first_name'  =>  $user_full_name,
				'user_pass'   =>  $user_password,  // When creating an user, `user_pass` is expected.
				'role'        =>  'subscriber'
			);
			
			$user_id = wp_insert_user( $userdata ) ;
			
			//On success
			if ( ! is_wp_error( $user_id ) ) {
				//update_user_meta($user_id, '_user_phone_number', $phone_number);
				/*$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: Sinkboss <info@sinkboss.com>' . "\r\n";
				$message = 'Hi '.$user_full_name.',<br>';
				$message .= 'Thanks for your account request! Somebody from our team will review your account details soon. Once your account is approved, you\'ll be able to login. Listed below is your account login link, username and temporary password.<br><br>';
				$message .= home_url('/my-account/').'<br>';
				$message .= 'User Name: '.$user_email.'<br>';
				$message .= 'Password: '.$user_password.'<br><br>';
				$message .= 'Thanks,<br><br>The Sinkboss Team<br>===================';
				
				wp_mail( $email, 'Registration Confirmation', $message, $headers );
				wp_mail( 'sinkbossbaby@gmail.com', 'Registration Confirmation', $message, $headers );*/
				?>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						alert("Thank you for registering.");
						window.location = "<?php echo get_site_url().'/login/'; ?>";
					});
				</script>
				<?php
			}
		}
	}
	?>
    <div class="tml tml-register" id="tml-register">
	<a class="close_url" href="<?php echo get_site_url(); ?>">X</a>
	<div class="tml_container">
    	<h1>Register Below to Start</h1>
        <!--<p>If you're returning, <a href="/login/">Log In</a> here</p>-->
        <?php
			//if( isset($_GET['show']) && ($_GET['show'] == "reginfo") ) {
				//echo '<p>If you\'re returning, <a href="/login/">Log In</a> here</p>';
			//}
		?>
        <?php if( isset($error) ) echo $error; ?>
		<form name="registerform" id="registerform" action="" method="post">
				<p class="tml-user-login-wrap">
			<i class="fa fa-user" aria-hidden="true"></i><input name="user_full_name" id="user_login" size="20" placeholder="Full Name" type="text">
		</p>
		
		<p class="tml-user-email-wrap">
			<i class="fa fa-envelope" aria-hidden="true"></i><input name="user_email" id="user_email" size="20" placeholder="Email Address" type="text">
		</p>
        
        <p class="tml-user-pass-wrap">		
			<i class="fa fa-lock" aria-hidden="true"></i><input name="user_password" id="user_password"  size="20" autocomplete="off" placeholder="Password" type="password">
		</p>

		
		<p class="tml-submit-wrap">
			<input name="wp_submit" id="wp-submit" value="Register" type="submit">
		</p>
	</form>
    <div class="login_footer">
    	Already started your application?  <a href="<?php echo get_site_url(); ?>/login">Login</a> to continue
    </div>
	</div><!--tml_container-->
</div>
			
	<?php 
	$output = ob_get_clean();
	return $output;
}

function onboard_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return $redirect_to;
		} else {
			return home_url('/application/');
		}
	} else {
		return $redirect_to;
	}
}
add_filter( 'login_redirect', 'onboard_login_redirect', 10, 3 );

function onboard_admin_bar($content) {
	return ( current_user_can( 'administrator' ) ) ? $content : false;
}
add_filter( 'show_admin_bar' , 'onboard_admin_bar');

function upload_user_file( $file = array() ) {
    
    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
    
    $file_return = wp_handle_upload( $file, array('test_form' => false ) );
    
    if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
        return false;
    } else {
        
        $filename = $file_return['file'];
        
        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $file_return['url']
        );
        
        $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
        
        require_once (ABSPATH . 'wp-admin/includes/image.php' );
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
        wp_update_attachment_metadata( $attachment_id, $attachment_data );
        
        if( 0 < intval( $attachment_id ) ) {
            return $attachment_id;
        }
    }
    
    return false;
}

add_filter( 'wp_mail_from_name', function( $name ) {
	return 'Rental Guardian';
});

add_filter( 'wp_mail_from', function( $email ) {
	return 'info@tryrentalguardian.com';
});

function get_app_users_list() {
	$blogusers = get_users( array( 'role' => 'subscriber', 'orderby' => 'registered', 'order' => 'DESC' ) );
	// Array of WP_User objects.
	$user_ids = array();
	foreach( $blogusers as $user ) {
		$user_ids[] = $user->ID;
	}
	
	return $user_ids;
}

add_action('admin_menu', 'register_application_preview_page');
function register_application_preview_page() {
	add_submenu_page( 'users.php', 'User Applications', 'User Applications', 'manage_options', 'user-application', 'user_applications_callback' );
}

function user_applications_callback() {
	/*if( isset($_GET['action']) && ($_GET['action'] != 'rg_view_user_application') ) {
	}*/
	$user_id = $_GET['user'];
	$userdata = get_userdata( $user_id );
	?>
    <div class="wrap">
    	<h2><?php echo __('Viewing Application - '.$userdata->user_login); ?></h2>
		<?php
		if( isset($_POST['submit_user_applicaiton_sections']) ) {
			$show_company_info = $_POST['show_company_info'];
			$show_product_info = $_POST['show_product_info'];
			$show_w9_form = $_POST['show_w9_form'];
			$show_travel_protection = $_POST['show_travel_protection'];
			$show_property_protection = $_POST['show_property_protection'];
			$show_liability_protection = $_POST['show_liability_protection'];
			$show_schedule_training = $_POST['show_schedule_training'];
			$show_licensing_appointments = $_POST['show_licensing_appointments'];
			
			update_user_meta($user_id, 'cmb_user_company_info', $show_company_info);
			update_user_meta($user_id, 'cmb_user_product_info', $show_product_info);
			update_user_meta($user_id, 'cmb_user_w9_form', $show_w9_form);
			update_user_meta($user_id, 'cmb_user_travel_protection', $show_travel_protection);
			update_user_meta($user_id, 'cmb_user_property_protection', $show_property_protection);
			update_user_meta($user_id, 'cmb_user_liability_protection', $show_liability_protection);
			update_user_meta($user_id, 'cmb_user_schedule_training', $show_schedule_training);
			update_user_meta($user_id, 'cmb_user_licensing_appointments', $show_licensing_appointments);
			
			echo '<p style="color:green;">Changes saved successfully.</p>';
		}
		?>
        <table class="form-table">
            <tr>
                <td style="width: 75%; vertical-align: top; padding-left: 0;">
                    <form action="<?php echo admin_url('/users.php?page=user-application&action=rg_view_user_application&amp;user='.$user_id); ?>" method="post">
                        <table class="form-table">
                            <tr>
                                <td style="padding-top:0; padding-left:0;">
                                    <h3>Company Info <span class="togg-arrow">&rsaquo;</span></h3>
                                    <hr />
                                    <div class="toggle-area">
                                        <label for="show_company_info">Display Section on Application</label>
                                        <select name="show_company_info" id="show_company_info">
                                            <option value="Yes" <?php if(get_user_meta($user_id, 'cmb_user_company_info', true) == "Yes") echo 'selected="selected"'; ?>>Display</option>
                                            <option value="No" <?php if(get_user_meta($user_id, 'cmb_user_company_info', true) == "No") echo 'selected="selected"'; ?>>Hide</option>
                                        </select>
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Company Name/DBA</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'company_name', true) ) {
                                                            echo get_user_meta($user_id, 'company_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Applying</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'appling', true) ) {
                                                            echo get_user_meta($user_id, 'appling', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Tax/Reseller Id</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'reseller_id', true) ) {
                                                            echo get_user_meta($user_id, 'reseller_id', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Type of Company</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'company_type', true) ) {
                                                            echo get_user_meta($user_id, 'company_type', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Years in Business</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'years_in_business', true) ) {
                                                            echo get_user_meta($user_id, 'years_in_business', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Preferred Method of Payment</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'preferred_payment_method', true) ) {
                                                            echo get_user_meta($user_id, 'preferred_payment_method', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->
                                        
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Street Address</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'street_address', true) ) {
                                                            echo get_user_meta($user_id, 'street_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Street Address Line 2</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'street_address_line_2', true) ) {
                                                            echo get_user_meta($user_id, 'street_address_line_2', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">City</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'city', true) ) {
                                                            echo get_user_meta($user_id, 'city', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">State / Province</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'state_province', true) ) {
                                                            echo get_user_meta($user_id, 'state_province', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Postal / Zip Code</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'co_postal_code', true) ) {
                                                            echo get_user_meta($user_id, 'co_postal_code', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Country</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'co_country', true) ) {
                                                            echo get_user_meta($user_id, 'co_country', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->

                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Legal / Authorized Representatives Name</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'representatives_first_name', true) && get_user_meta($user_id, 'representatives_last_name', true) ) {
                                                            echo get_user_meta($user_id, 'representatives_first_name', true).' '.get_user_meta($user_id, 'representatives_last_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Job Position / Title</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'job_position', true) ) {
                                                            echo get_user_meta($user_id, 'job_position', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Applicant E-mail</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'applicant_email', true) ) {
                                                            echo get_user_meta($user_id, 'applicant_email', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Phone Number</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'applicant_phone_number', true) ) {
                                                            echo get_user_meta($user_id, 'applicant_phone_number', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Do you have an Administrative Department?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'administrative_department', true) ) {
                                                            echo get_user_meta($user_id, 'administrative_department', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Do you have an IT / Technology Department?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'technology_department', true) ) {
                                                            echo get_user_meta($user_id, 'technology_department', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Do you have an Accountant/Accounting Department?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'accounting_department', true) ) {
                                                            echo get_user_meta($user_id, 'accounting_department', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->

                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Company Website Address/URL</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'company_website_address', true) ) {
                                                            echo get_user_meta($user_id, 'company_website_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td colspan="2">
                                                        <div class="field-label">Company Email Address</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'company_email', true) ) {
                                                            echo get_user_meta($user_id, 'company_email', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <!--<td>
                                                        <div class="field-label">Main Phone Number Area Code</div>
                                                        <?php
                                                        /*if( get_user_meta($user_id, 'main_phone_area_code', true) ) {
                                                            echo get_user_meta($user_id, 'main_phone_area_code', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }*/
                                                        ?>
                                                    </td>-->
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Main Phone Number</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'main_phone_number', true) ) {
                                                            echo get_user_meta($user_id, 'main_phone_number', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Do you have Properties located in the U.S.?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'properties_located_usa', true) ) {
                                                            echo get_user_meta($user_id, 'properties_located_usa', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Do you have Properties located Internationally?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'properties_located_internationally', true) ) {
                                                            echo get_user_meta($user_id, 'properties_located_internationally', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Current Management Software System</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'content_management_software_system', true) ) {
                                                            echo get_user_meta($user_id, 'content_management_software_system', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Company Information Notes / Comments</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'company_information_notes', true) ) {
                                                            echo get_user_meta($user_id, 'company_information_notes', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->
                                    </div><!-- .toggle-area -->
                                </td>
                            </tr><!-- end of section -->
                            <tr>
                                <td style="padding-left:0;">
                                    <h3>Product Info <span class="togg-arrow">&rsaquo;</span></h3>
                                    <hr />
                                    <div class="toggle-area" style="display:none;">
                                        <label for="show_product_info">Display Section on Application</label>
                                        <select name="show_product_info" id="show_product_info">
                                            <option value="Yes" <?php if(get_user_meta($user_id, 'cmb_user_product_info', true) == "Yes") echo 'selected="selected"'; ?>>Display</option>
                                            <option value="No" <?php if(get_user_meta($user_id, 'cmb_user_product_info', true) == "No") echo 'selected="selected"'; ?>>Hide</option>
                                        </select>
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Desired Geographical Coverage (U.S. Domestic/International)</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'geographical_coverage', true) ) {
                                                            echo get_user_meta($user_id, 'geographical_coverage', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Travel Protection</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_protection', true) ) {
                                                            echo get_user_meta($user_id, 'travel_protection', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Property Protection / Damage Protection</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'property_damage_protection', true) ) {
                                                            echo get_user_meta($user_id, 'property_damage_protection', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Liability & Real Property Protection</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'liability_property_protection', true) ) {
                                                            echo get_user_meta($user_id, 'liability_property_protection', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Who will oversee claims within your team?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'oversee_claims_team_name', true) && get_user_meta($user_id, 'oversee_claims_team_email', true) && get_user_meta($user_id, 'oversee_claims_team_phone', true) ) {
                                                            echo 'Name: '.get_user_meta($user_id, 'oversee_claims_team_name', true).'<br>';
                                                            echo 'Email: '.get_user_meta($user_id, 'oversee_claims_team_email', true).'<br>';
                                                            echo 'Phone: '.get_user_meta($user_id, 'oversee_claims_team_phone', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Additional Comments</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'additional_comments', true) ) {
                                                            echo get_user_meta($user_id, 'additional_comments', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->                                            
                                    </div><!-- .toggle-area -->
                                </td>
                            </tr><!-- end of section -->
                            <tr>
                                <td style="padding-left:0;">
                                    <h3>W9 Form <span class="togg-arrow">&rsaquo;</span></h3>
                                    <hr />
                                    <div class="toggle-area" style="display:none;">
                                        <label for="show_w9_form">Display Section on Application</label>
                                        <select name="show_w9_form" id="show_w9_form">
                                            <option value="Yes" <?php if(get_user_meta($user_id, 'cmb_user_w9_form', true) == "Yes") echo 'selected="selected"'; ?>>Display</option>
                                            <option value="No" <?php if(get_user_meta($user_id, 'cmb_user_w9_form', true) == "No") echo 'selected="selected"'; ?>>Hide</option>
                                        </select>
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Provide your name as shown in your income tax return:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'w9_income_tax', true) ) {
                                                            echo get_user_meta($user_id, 'w9_income_tax', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Your business name:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'w9_business_name', true) ) {
                                                            echo get_user_meta($user_id, 'w9_business_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Check the appropriate box for federal tax classification:</div>
                                                        <?php
														$w9_federal_tax = get_user_meta($user_id, 'w9_federal_tax', true);
														if($w9_federal_tax) {
															$w9_checked_federal_taxes = explode(",", $w9_federal_tax);
															if( is_array($w9_checked_federal_taxes) ) {
																foreach($w9_checked_federal_taxes as $w9_federal_tax) {
																	echo $w9_federal_tax.'<br>';
																}
															}
														} else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Enter your address, city, state and ZIP code:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'w9_address_city_state', true) ) {
                                                            echo get_user_meta($user_id, 'w9_address_city_state', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">List account numbers (optional):</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'w9_account_numbers', true) ) {
                                                            echo get_user_meta($user_id, 'w9_account_numbers', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Specify requester's name and address (optional):</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'w9_requesters_name', true) ) {
                                                            echo get_user_meta($user_id, 'w9_requesters_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Indicate your Federal / Tax ID Number:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'w9_federal_id_number', true) ) {
                                                            echo get_user_meta($user_id, 'w9_federal_id_number', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Authorized Signature:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'w9_signature', true) ) {
                                                            echo '<img src="'.get_user_meta($user_id, 'w9_signature', true).'" style="width:240px;" />';
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Date:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'w9_date', true) ) {
                                                            echo get_user_meta($user_id, 'w9_date', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->                                            
                                    </div><!-- .toggle-area -->
                                </td>
                            </tr><!-- end of section -->
                            <tr>
                                <td style="padding-left:0;">
                                    <h3>Travel Protection <span class="togg-arrow">&rsaquo;</span></h3>
                                    <hr />
                                    <div class="toggle-area" style="display:none;">
                                        <label for="show_travel_protection">Display Section on Application</label>
                                        <select name="show_travel_protection" id="show_travel_protection">
                                            <option value="Yes" <?php if(get_user_meta($user_id, 'cmb_user_travel_protection', true) == "Yes") echo 'selected="selected"'; ?>>Display</option>
                                            <option value="No" <?php if(get_user_meta($user_id, 'cmb_user_travel_protection', true) == "No") echo 'selected="selected"'; ?>>Hide</option>
                                        </select>
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">TYPE</div>
                                                        <?php
														$property_protection_type = get_user_meta($user_id, 'property_protection_type', true);
														if($property_protection_type) {
															$property_protection_types = explode(",", $property_protection_type);
															if( is_array($property_protection_types) ) {
																foreach($property_protection_types as $protection_type) {
																	echo $protection_type.'<br>';
																}
															}
														} else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Total bookings per year?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'property_reservations', true) ) {
                                                            echo get_user_meta($user_id, 'property_reservations', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">What is your average booking cost?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'property_booking_cost', true) ) {
                                                            echo get_user_meta($user_id, 'property_booking_cost', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">What is the average length of stay?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'property_average_length', true) ) {
                                                            echo get_user_meta($user_id, 'property_average_length', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">NAME & TITLE</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'property_printed_name', true) ) {
                                                            echo get_user_meta($user_id, 'property_printed_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Date:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'property_date', true) ) {
                                                            echo get_user_meta($user_id, 'property_date', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                	<td style="vertical-align: top;">
                                                    	<div class="field-label">APPLICANT/ AUTHORIZED SIGNATURE:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'property_signature', true) ) {
                                                            echo '<img src="'.get_user_meta($user_id, 'property_signature', true).'" style="width:240px;" />';
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->                                            
                                    </div><!-- .toggle-area -->
                                </td>
                            </tr><!-- end of section -->
                            <tr>
                                <td style="padding-left:0;">
                                    <h3>Property Protection / Damage Protection <span class="togg-arrow">&rsaquo;</span></h3>
                                    <hr />
                                    <div class="toggle-area" style="display:none;">
                                        <label for="show_property_protection">Display Section on Application</label>
                                        <select name="show_property_protection" id="show_property_protection">
                                            <option value="Yes" <?php if(get_user_meta($user_id, 'cmb_user_property_protection', true) == "Yes") echo 'selected="selected"'; ?>>Display</option>
                                            <option value="No" <?php if(get_user_meta($user_id, 'cmb_user_property_protection', true) == "No") echo 'selected="selected"'; ?>>Hide</option>
                                        </select>
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">TYPE</div>
                                                        <?php
														$travel_protection_type = get_user_meta($user_id, 'travel_protection_type', true);
														if($travel_protection_type) {
															$travel_protection_types = explode(",", $travel_protection_type);
															if( is_array($travel_protection_types) ) {
																foreach($travel_protection_types as $protection_type) {
																	echo $protection_type.'<br>';
																}
															}
														} else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Are the units you rent directly managed by a full-time professional property manager?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'professional_property_manager', true) ) {
                                                            echo get_user_meta($user_id, 'professional_property_manager', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">What percentage of your properties are directly managed by you/ your company?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'percentage_properties', true) ) {
                                                            echo get_user_meta($user_id, 'percentage_properties', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                	<td colspan="3" style="vertical-align: top;">
                                                    	How many of each type of property/unit do you offer for rental:
                                                        <table border="0" cellpadding="10" cellspacing="0">
                                                        	<tr>
                                                            	<th>Type of Unit</th>
                                                            	<th>Number of Units</th>
                                                            </tr>
                                                        	<tr>
                                                            	<td>Single Family</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_single_family', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_single_family', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        	<tr>
                                                            	<td>Condominium</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_condominium', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_condominium', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        	<tr>
                                                            	<td>Apartment</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_apartment', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_apartment', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        	<tr>
                                                            	<td>Time Share</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_time_share', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_time_share', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        	<tr>
                                                            	<td>Lodge/Condo-tel</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_condo_tel', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_condo_tel', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        	<tr>
                                                            	<td>Cabin</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_cabin', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_cabin', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        	<tr>
                                                            	<td>Other</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_other', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_other', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        	<tr>
                                                            	<td>TOTAL</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_total', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_total', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">For the US, provide list of states in which your properties are located.</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_provide_list', true) ) {
                                                            echo get_user_meta($user_id, 'travel_provide_list', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">For international, provide list of countries in which your properties are located</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_provide_list_countries', true) ) {
                                                            echo get_user_meta($user_id, 'travel_provide_list_countries', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">In the next 12 months, do you plan on increasing or decreasing the number of units for rent and/or the number of reservations you accept?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_units_for_rent', true) ) {
                                                            echo get_user_meta($user_id, 'travel_units_for_rent', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">If "Yes", please describe:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_units_for_rent_describe', true) ) {
                                                            echo get_user_meta($user_id, 'travel_units_for_rent_describe', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">How many total bookings did you have during the past/prior 12 months?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_total_bookings', true) ) {
                                                            echo get_user_meta($user_id, 'travel_total_bookings', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">What is your average length of stay?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_average_length', true) ) {
                                                            echo get_user_meta($user_id, 'travel_average_length', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">What is your average booking total amount?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_booking_amount', true) ) {
                                                            echo get_user_meta($user_id, 'travel_booking_amount', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Do you plan on adding the Property Protection Program as a surcharge on every booking?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_property_program', true) ) {
                                                            echo get_user_meta($user_id, 'travel_property_program', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Does your rental/lease agreement include specific guidance and instruction for the guest(s) regarding guest responsibilities with respect to proper care for the rented unit?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_agreement_guidance', true) ) {
                                                            echo get_user_meta($user_id, 'travel_agreement_guidance', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Do you inspect the unit immediately upon check-out for every booking-occupancy?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_booking_occupancy', true) ) {
                                                            echo get_user_meta($user_id, 'travel_booking_occupancy', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Do you require guest-verification of damages?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_guest_verification', true) ) {
                                                            echo get_user_meta($user_id, 'travel_guest_verification', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                	<td colspan="3" style="vertical-align: top;">
                                                    	What is the total amount of renter-caused damage, security deposit deductions, or claimed accidental damages to your rental properties each of the last three (3) years?
                                                        <table border="0" cellpadding="10" cellspacing="0">
                                                        	<tr>
                                                            	<th>Year of Loss</th>
                                                            	<th>Approximate Number of Claim Incidents</th>
                                                            	<th>Total Amount of Losses</th>
                                                            	<th>Nature of/ Type of Losses</th>
                                                            </tr>
                                                        	<tr>
                                                            	<td>2017</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_approximate_number1', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_approximate_number1', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_amount_losses1', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_amount_losses1', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_type_losses1', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_type_losses1', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        	<tr>
                                                            	<td>2016</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_approximate_number2', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_approximate_number2', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_amount_losses2', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_amount_losses2', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_type_losses2', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_type_losses2', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        	<tr>
                                                            	<td>2015</td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_approximate_number3', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_approximate_number3', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_amount_losses3', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_amount_losses3', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            	<td>
																	<?php
                                                                    if( get_user_meta($user_id, 'travel_type_losses3', true) ) {
                                                                        echo get_user_meta($user_id, 'travel_type_losses3', true);
                                                                    } else {
                                                                        echo 'Not Submitted';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Printed Name & Title</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_printed_name', true) ) {
                                                            echo get_user_meta($user_id, 'travel_printed_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Authorized Signature:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_signature', true) ) {
                                                            echo '<img src="'.get_user_meta($user_id, 'travel_signature', true).'" style="width:240px;" />';
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Date</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'travel_date', true) ) {
                                                            echo get_user_meta($user_id, 'travel_date', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->                                            
                                    </div><!-- .toggle-area -->
                                </td>
                            </tr><!-- end of section -->
                            <tr>
                                <td style="padding-left:0;">
                                    <h3>Liability & Real Property Protection <span class="togg-arrow">&rsaquo;</span></h3>
                                    <hr />
                                    <div class="toggle-area" style="display:none;">
                                        <label for="show_liability_protection">Display Section on Application</label>
                                        <select name="show_liability_protection" id="show_liability_protection">
                                            <option value="Yes" <?php if(get_user_meta($user_id, 'cmb_user_liability_protection', true) == "Yes") echo 'selected="selected"'; ?>>Display</option>
                                            <option value="No" <?php if(get_user_meta($user_id, 'cmb_user_liability_protection', true) == "No") echo 'selected="selected"'; ?>>Hide</option>
                                        </select>
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Please provide details of any existing Tenant Damage or Owner Liability Insurance Program:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'liability_insurance_program', true) ) {
                                                            echo get_user_meta($user_id, 'liability_insurance_program', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Insurer & Expiration Date:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'liability_expiration_date', true) ) {
                                                            echo get_user_meta($user_id, 'liability_expiration_date', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Has any application for insurance on behalf your company or any of the present Directors/Partners/Principals or, to your knowledge on behalf of their predecessors in business ever been declined or has any such insurance ever been cancelled or renewal refused?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'liability_refused', true) ) {
                                                            echo get_user_meta($user_id, 'liability_refused', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Have any insured losses which would be subject to this program been incurred by your company over the past 5 years?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'liability_insured_losses', true) ) {
                                                            echo get_user_meta($user_id, 'liability_insured_losses', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">If you answered YES, to either of the two questions above, please provide details:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'liability_insured_details', true) ) {
                                                            echo get_user_meta($user_id, 'liability_insured_details', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">NAME & TITLE:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'liability_printed_name', true) ) {
                                                            echo get_user_meta($user_id, 'liability_printed_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                	<td style="vertical-align: top;">
                                                    	<div class="field-label">APPLICANT/ AUTHORIZED SIGNATURE:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'liability_signature', true) ) {
                                                            echo '<img src="'.get_user_meta($user_id, 'liability_signature', true).'" style="width:240px;" />';
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">DATE:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'liability_date', true) ) {
                                                            echo get_user_meta($user_id, 'liability_date', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->                                            
                                    </div><!-- .toggle-area -->
                                </td>
                            </tr><!-- end of section -->
                            <tr>
                                <td style="padding-left:0;">
                                    <h3>Schedule Training <span class="togg-arrow">&rsaquo;</span></h3>
                                    <hr />
                                    <div class="toggle-area" style="display:none;">
                                        <label for="show_schedule_training">Display Section on Application</label>
                                        <select name="show_schedule_training" id="show_schedule_training">
                                            <option value="Yes" <?php if(get_user_meta($user_id, 'cmb_user_schedule_training', true) == "Yes") echo 'selected="selected"'; ?>>Display</option>
                                            <option value="No" <?php if(get_user_meta($user_id, 'cmb_user_schedule_training', true) == "No") echo 'selected="selected"'; ?>>Hide</option>
                                        </select>
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Please invite me to the two standard weekly training sessions:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'standard_weekly_training_sessions', true) ) {
                                                            echo get_user_meta($user_id, 'standard_weekly_training_sessions', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">I would like to set up a custom date & time for a combined Platform & Products training session:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'product_training_custom_date', true) && get_user_meta($user_id, 'product_training_custom_time', true) && get_user_meta($user_id, 'product_training_custom_timezone', true) ) {
                                                            echo 'Date: '.get_user_meta($user_id, 'product_training_custom_date', true).'<br>';
                                                            echo 'Time: '.get_user_meta($user_id, 'product_training_custom_time', true).'<br>';
                                                            echo 'Timezone: '.get_user_meta($user_id, 'product_training_custom_timezone', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Who will attend:</div>
                                                        <?php
                                                        $num_of_attendee = get_user_meta($user_id, 'num_of_attendee', true);
                                                        if($num_of_attendee) {
                                                            for($i=1; $i<=$num_of_attendee; $i++) {
                                                                echo 'Attendee '.$i.' Name: '.get_user_meta($user_id, 'attendee_'.$i.'_name', true).'<br>';
                                                                echo 'Attendee '.$i.' Email: '.get_user_meta($user_id, 'attendee_'.$i.'_email', true).'<br>';
                                                            }
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->                                            
                                    </div><!-- .toggle-area -->
                                </td>
                            </tr><!-- end of section -->
                            <tr>
                                <td style="padding-top:0; padding-left:0;">
                                    <h3>Licensing &amp; Appointments <span class="togg-arrow">&rsaquo;</span></h3>
                                    <hr />
                                    <div class="toggle-area" style="display:none;">
                                        <label for="show_licensing_appointments">Display Section on Application</label>
                                        <select name="show_licensing_appointments" id="show_licensing_appointments">
                                            <option value="Yes" <?php if(get_user_meta($user_id, 'cmb_user_licensing_appointments', true) == "Yes") echo 'selected="selected"'; ?>>Display</option>
                                            <option value="No" <?php if(get_user_meta($user_id, 'cmb_user_licensing_appointments', true) == "No") echo 'selected="selected"'; ?>>Hide</option>
                                        </select>
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Social Security Number</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_security_number', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_security_number', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">If assigned, National Producer Number (NPN)</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_assigned_npn', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_assigned_npn', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">First Name</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_first_name', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_first_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Middle Name</div>
                                                        <?php

                                                        if( get_user_meta($user_id, 'la_1_middle_name', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_middle_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Last Name</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_last_name', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_last_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Date of Birth</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_birth_day', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_birth_day', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Residence/Home Address (Physical Street)</div>
                                                        <?php

                                                        if( get_user_meta($user_id, 'la_1_home_address', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_home_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">City</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_city', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_city', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">State</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_state', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_state', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Zip Code</div>
                                                        <?php

                                                        if( get_user_meta($user_id, 'la_1_zip_code', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_zip_code', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <!--<div class="field-label">Foreign Country</div>-->
                                                        <?php
                                                        /*if( get_user_meta($user_id, 'la_1_foreign_country', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_foreign_country', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }*/
                                                        ?>
                                                        &nbsp;
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Home Phone Number</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_home_phone', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_home_phone', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Gender</div>
                                                        <?php

                                                        if( get_user_meta($user_id, 'la_1_gender', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_gender', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Are you a Citizen of the United States?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_citizen_usa', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_citizen_usa', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Individual Applicant Email Address</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_1_applicant_email', true) ) {
                                                            echo get_user_meta($user_id, 'la_1_applicant_email', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">List your Insurance Agency Affiliations</div>
                                                        <?php
                                                        $num_of_agency_aff = get_user_meta($user_id, 'la_1_num_of_agency_aff', true);
                                                        if($num_of_agency_aff) {
                                                            for($i=1; $i<=$num_of_agency_aff; $i++) {
                                                                echo 'FEIN: '.get_user_meta($user_id, 'la_1_FEIN_'.$i, true).'<br>';
                                                                echo 'NPN: '.get_user_meta($user_id, 'la_1_NPN_'.$i, true).'<br>';
                                                                echo 'Name of Agency: '.get_user_meta($user_id, 'la_1_agency_name_'.$i, true);
                                                                echo '<hr>';
                                                            }
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Employment Experience</div>
                                                        <?php
                                                        $num_of_employment = get_user_meta($user_id, 'la_1_num_of_employment', true);
                                                        if($num_of_employment) {
                                                            for($i=1; $i<=$num_of_employment; $i++) {
                                                                echo 'Name: '.get_user_meta($user_id, 'la_1_employee_'.$i.'_name', true).'<br>';
                                                                echo 'City: '.get_user_meta($user_id, 'la_1_employee_'.$i.'_city', true).'<br>';
                                                                echo 'State: '.get_user_meta($user_id, 'la_1_employee_'.$i.'_state', true).'<br>';
                                                                //echo 'Foreign Country: '.get_user_meta($user_id, 'la_1_employee_'.$i.'_foreign_country', true).'<br>';
                                                                echo 'From: '.get_user_meta($user_id, 'la_1_employee_'.$i.'_from_month', true).'<br>';
                                                                echo 'To: '.get_user_meta($user_id, 'la_1_employee_'.$i.'_to_month', true).'<br>';
                                                                echo 'Position Held: '.get_user_meta($user_id, 'la_1_employee_'.$i.'_position_held', true).'<br>';
                                                                echo '<hr>';
                                                            }
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->
                                        
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Incorporation/Formation Date</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_2_incorporation_date', true) ) {
                                                            echo get_user_meta($user_id, 'la_2_incorporation_date', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Is the business entity affiliated with a financial institution/bank?</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_2_affiliated_financial_institution', true) ) {
                                                            echo get_user_meta($user_id, 'la_2_affiliated_financial_institution', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Business Address</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_2_business_address', true) ) {
                                                            echo get_user_meta($user_id, 'la_2_business_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Identify at least one Designated/Responsible Licensed Producer</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_2_license_name', true) && get_user_meta($user_id, 'la_2_license_ssn', true) && get_user_meta($user_id, 'la_2_license_npn', true) ) {
                                                            echo 'Name: '.get_user_meta($user_id, 'la_2_license_name', true).'<br>';
                                                            echo 'SSN: '.get_user_meta($user_id, 'la_2_license_ssn', true).'<br>';
                                                            //echo 'NPN: '.get_user_meta($user_id, 'la_2_license_npn', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Identify all owners with 10% or more voting interest:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'la_2_entity_1_name', true) && get_user_meta($user_id, 'la_2_entity_1_title', true) && get_user_meta($user_id, 'la_2_entity_1_ssn', true) ) {
                                                            echo 'Name: '.get_user_meta($user_id, 'la_2_entity_1_name', true).'<br>';
                                                            echo 'Title: '.get_user_meta($user_id, 'la_2_entity_1_title', true).'<br>';
                                                            echo 'SSN: '.get_user_meta($user_id, 'la_2_entity_1_ssn', true).'<br>';
                                                            echo '% of Ownership Interest: '.get_user_meta($user_id, 'la_2_entity_1_interest_rate', true).'<br>';
                                                            echo 'Date of Birth: '.get_user_meta($user_id, 'la_2_birth_day', true).'<br>';
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->

                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                            	<tr>
                                                	<td colspan="3">
                                                    	<h4>AGENCY PRODUCER INFORMATION</h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Agency Legal Name:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_legal_name', true) ) {
                                                            echo get_user_meta($user_id, 'agency_legal_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Federal Employer Identification Number:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_employer_id', true) ) {
                                                            echo get_user_meta($user_id, 'agency_employer_id', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Address:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_producer_address', true) ) {
                                                            echo get_user_meta($user_id, 'agency_producer_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">City:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_producer_city', true) ) {
                                                            echo get_user_meta($user_id, 'agency_producer_city', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">State:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_producer_state', true) ) {
                                                            echo get_user_meta($user_id, 'agency_producer_state', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Zip Code:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_producer_zipcode', true) ) {
                                                            echo get_user_meta($user_id, 'agency_producer_zipcode', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Business Email:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_producer_business_email', true) ) {
                                                            echo get_user_meta($user_id, 'agency_producer_business_email', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Please indicate which states the Agency is licensed and include the license number for each:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_producer_license_number', true) ) {
                                                            echo get_user_meta($user_id, 'agency_producer_license_number', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                	<td style="vertical-align:top;">
                                                    	<div class="field-label">Has the Agency or any owner, officer, director, or partner of the Agency ever been charged with a crime in a court of law in any State, Province, Territory, or country? If yes, provide details including date, charges, disposition, and a description of the circumstances.</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_producer_back_quise_a', true) ) {
                                                            echo get_user_meta($user_id, 'agency_producer_back_quise_a', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                	<td style="vertical-align:top;">
                                                    	<div class="field-label">Has the Agency or any owner, officer, director, partner, or employee of the Agency ever been the subject of any administrative or disciplinary action by any insurance authority? If yes, provide full details.</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_producer_back_quise_b', true) ) {
                                                            echo get_user_meta($user_id, 'agency_producer_back_quise_b', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                	<td style="vertical-align:top;">
                                                    	<div class="field-label">Has the Agency or any owner, officer, director, partner, or employee of the Agency ever had a contract or appointment to sell travel insurance or to act as a travel retailer terminated or cancelled for any reason other than low production? If yes, provide full details.</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'agency_producer_back_quise_c', true) ) {
                                                            echo get_user_meta($user_id, 'agency_producer_back_quise_c', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            	<tr>
                                                	<td colspan="3">
                                                    	<h4>DESIGNATED RESPONSIBLE PRODUCER INFORMATION</h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">First Name:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_first_name', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_first_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Middle Name:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_middle_name', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_middle_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Last Name:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_last_name', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_last_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Home Address:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_home_address', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_home_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">City:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_city', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_city', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">State:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_state', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_state', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Zip Code:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_zipcode', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_zipcode', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">State of Residence:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_residence', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_residence', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Date of Birth:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_birth_date', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_birth_date', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Business Phone:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_business_phone', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_business_phone', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Title:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_title', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_title', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Primary Email:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_primary_email', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_primary_email', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Socila Security Number:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_social_security', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_social_security', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Please indicate which states the Designated Responsible Producer is licensed and include the license number for each:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_license_number', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_license_number', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Have you ever been charged with a crime in a court of law in any State, Province, Territory, or country? If yes, provide details including date, charges, disposition, and a description of the surrounding circumstances.</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_back_quise_a', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_back_quise_a', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Have you ever been the subject of any administrative or disciplinary action by any insurance authority? If yes, provide full details.</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_back_quise_b', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_back_quise_b', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="field-label">Have you ever had a contract or appointment to sell travel insurance or to act as a travel retailer terminated or cancelled for any reason other than low production? If yes, attach full details.</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'designate_producer_back_quise_c', true) ) {
                                                            echo get_user_meta($user_id, 'designate_producer_back_quise_c', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align:top;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Name & Title</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l3_authorization_name', true) ) {
                                                            echo get_user_meta($user_id, 'l3_authorization_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Authorized Signature:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l3_authorization_signature', true) ) {
                                                            echo '<img src="'.get_user_meta($user_id, 'l3_authorization_signature', true).'" style="width:240px;" />';
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                    	<div class="field-label">Date</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l3_authorization_date', true) ) {
                                                            echo get_user_meta($user_id, 'l3_authorization_date', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->

                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                            	<tr>
                                                	<td style="vertical-align: top;">
                                                    	<h4>INDIVIDUAL AGENT INFORMATION</h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">First Name:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_first_name', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_first_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Middle Name:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_middle_name', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_middle_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Last Name:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_last_name', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_last_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Social Security Number:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_social_security', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_social_security', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Date of Birth:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_birth_day', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_birth_day', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Gender:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_gender', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_gender', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Home Address:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_home_address', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_home_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Street:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_street', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_street', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">City:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_city', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_city', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">State:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_state', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_state', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">ZIP:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_zip', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_zip', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">County:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_county', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_county', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Home Phone Number:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_home_phone', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_home_phone', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Home Fax Number:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_home_fax', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_home_fax', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Email Address:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'individual_agent_info_email', true) ) {
                                                            echo get_user_meta($user_id, 'individual_agent_info_email', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                	<td style="vertical-align: top;">
                                                    	<h4>AGENCY INFORMATION</h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">The Agency is a:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_type', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_type', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Business/Agency Name:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_name', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">EIN Number (For Agency Pay):</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_ein_number', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_ein_number', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Agency Street Address:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_street_address', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_street_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">City:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_city', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_city', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">State:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_state', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_state', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">ZIP:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_zip', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_zip', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">County:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_county', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_county', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Agency Mailing Address:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_mailing_address', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_mailing_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">City:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_mailing_city', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_mailing_city', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">State:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_mailing_state', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_mailing_state', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">ZIP:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_mailing_zip', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_mailing_zip', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">County:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_mailing_county', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_mailing_county', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Agency Phone Number:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_phone', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_phone', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Agency Fax Number:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_fax', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_fax', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">Agency Email Address:</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_email', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_email', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="field-label">State(s) in which to be appointed. Please attach copy(ies) of the current health license(s):</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'l4_agency_info_health_license', true) ) {
                                                            echo get_user_meta($user_id, 'l4_agency_info_health_license', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td style="vertical-align: top;">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->

                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Licensed Person at your company (please spell out complete name)</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'licensed_person_first_name', true) && get_user_meta($user_id, 'licensed_person_last_name', true) ) {
                                                            echo get_user_meta($user_id, 'licensed_person_first_name', true).' '.get_user_meta($user_id, 'licensed_person_last_name', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Client Office Address</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'client_office_address', true) ) {
                                                            echo get_user_meta($user_id, 'client_office_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Social Security Number</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'licensed_person_social_security_number', true) ) {
                                                            echo get_user_meta($user_id, 'licensed_person_social_security_number', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="field-label">Licensed Person's Phone</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'licensed_person_phone_number', true) ) {
                                                            echo get_user_meta($user_id, 'licensed_person_phone_number', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="field-label">Licensed Person's Email</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'licensed_person_email_address', true) ) {
                                                            echo get_user_meta($user_id, 'licensed_person_email_address', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->
                                    </div><!-- .toggle-area -->
                                </td>
                            </tr><!-- end of section -->
                            <tr>
                                <td style="padding-left:0;">
                                    <h3>Signature / Completion <span class="togg-arrow">&rsaquo;</span></h3>
                                    <hr />
                                    <div class="toggle-area" style="display:none;">
                                        <!--<label for="show_liability_protection">Display Section on Application</label>
                                        <select name="show_liability_protection" id="show_liability_protection">
                                            <option value="Yes" <?php //if(get_user_meta($user_id, 'cmb_user_liability_protection', true) == "Yes") echo 'selected="selected"'; ?>>Display</option>
                                            <option value="No" <?php //if(get_user_meta($user_id, 'cmb_user_liability_protection', true) == "No") echo 'selected="selected"'; ?>>Hide</option>
                                        </select>-->
                                        <div class="fields-preview" style="background: #ffffff;">
                                            <!-- Fields listed here -->
                                            <table class="form-table">
                                                <tr>
                                                    <td>
                                                        <div class="field-label">E-Signature: Please Enter Your Name Below</div>
                                                        <?php
                                                        if( get_user_meta($user_id, 'e_signature', true) ) {
                                                            echo get_user_meta($user_id, 'e_signature', true);
                                                        } else {
                                                            echo 'Not Yet Submitted';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div><!-- .fields-preview -->                                            
                                    </div><!-- .toggle-area -->
                                </td>
                            </tr><!-- end of section -->
                        </table>
                        <input type="submit" name="submit_user_applicaiton_sections" class="submit-user-applicaiton button button-primary" value="Save Changes" />
                    </form>
                </td>
                <td style="vertical-align: top;">
                    <div class="internal-notes-box">
                        <strong>Add an Internal Notes</strong><br />
                        <textarea name="internal_note" class="internal-note" rows="5" cols="25"></textarea><br />
                        <input type="hidden" name="viewing_user_id" id="viewing_user_id" value="<?php echo $user_id; ?>" />
                        <a href="javascript:void(0);" class="add-notes">ADD</a>
                        <div style="clear:both;"></div>
                    </div>
                    <div class="notes-wrap">
                        <strong>Notes</strong>
                        <ul class="notes-list">
                        <?php
							global $wpdb;
							$table_name = $wpdb->prefix . "internal_notes";
							$internal_notes = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id=$user_id ORDER BY cdate DESC" );
							if($wpdb->num_rows > 0) {
								foreach($internal_notes as $note) {
									echo '<li>'.$note->cdate.' - '.$note->note.' <span class="remove-note" data-note-id="'.$note->ID.'">X</span></li>';
								}
							}
						?>
                        </ul>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

function user_application_action_links($actions, $user_object) {
	if ( isset( $user_object->roles ) && is_array( $user_object->roles ) ) {
		if ( in_array( 'subscriber', $user_object->roles ) ) {
			$actions['user_application'] = "<a class='rg_user_application' href='" . admin_url( "users.php?page=user-application&action=rg_view_user_application&amp;user=$user_object->ID") . "'>" . __( 'View Application', 'cgc_ub' ) . "</a>";
		}
	}
	return $actions;
}
add_filter('user_row_actions', 'user_application_action_links', 10, 2);

function rg_custom_admin_head() {
?>
	<style type="text/css">
		#menu-users .wp-submenu li:last-child { display: none; }
		.fields-preview { color: #999999; }
		.field-label { color:#333333; border-bottom: 1px solid #dadada; padding-bottom: 5px; }
		.users_page_user-application .form-table h3 { position: relative; margin-top:0; }
		.users_page_user-application .form-table .togg-arrow { transform: rotate(90deg); -webkit-transform: rotate(90deg); font-size: 36px;
color: #888888; font-weight: 400; position: absolute; right: 0; cursor: pointer; }
		.internal-notes-box, .notes-wrap { background: #ffffff; padding: 15px; border: 1px solid #cccccc; margin-bottom: 10px; }
		.internal-notes-box .internal-note { width: 100%; background: #f2f2f2; padding: 10px; border: 1px solid #cccccc; margin-top: 5px; }
		.internal-notes-box .add-notes { background: #19BC9D; padding: 8px 25px; border-radius: 2px; text-transform: uppercase; display: inline-block; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; margin-top: 5px; float: right; }
		.notes-wrap ul li { background: #f2f2f2; padding: 4px 18px 5px 5px; position: relative; font-size: 13px; margin-bottom: 1px; color: #666666; }
		.notes-wrap ul li .remove-note { cursor: pointer; position: absolute; right: 8px; top: 4px; }
	</style>
    <script type="text/javascript">
		jQuery( document ).ready(function() {
			jQuery( ".users_page_user-application .togg-arrow" ).on( "click", function() {
				jQuery(this).parent().siblings(".toggle-area").toggle(500);
			});
			jQuery( ".users_page_user-application .add-notes" ).on( "click", function() {
				if(jQuery( ".internal-notes-box textarea[name=internal_note]" ).val() != "") {
					var internal_note = jQuery( ".internal-notes-box textarea[name=internal_note]" ).val();
					var user_id = jQuery( "#viewing_user_id" ).val();
					jQuery.ajax({
						type : "post",
						dataType: "json",
						url : ajaxurl,
						data : {action : 'add_internal_notes', internal_note: internal_note, user_id: user_id},
						beforeSend: function() {
							//jQuery(".clinics_container").html('Loading...').show();
						}, 
						success: function(data) {
							if(data.success == true) {
								jQuery(".notes-list").empty();
								jQuery(".notes-list").append(data.notes_html);
								jQuery(".internal-notes-box textarea[name=internal_note]").val('');
							}
						}
					});
				} else {
					alert("Please enter a note");
				}
			});
			jQuery( ".users_page_user-application" ).on( "click", ".remove-note", function() {
				var note_id = jQuery(this).attr("data-note-id");
				var user_id = jQuery( "#viewing_user_id" ).val();
				jQuery.ajax({
					type : "post",
					dataType: "json",
					url : ajaxurl,
					data : {action : 'delete_internal_notes', note_id: note_id, user_id: user_id},
					beforeSend: function() {
						//jQuery(".clinics_container").html('Loading...').show();
					}, 
					success: function(data) {
						if(data.success == true) {
							jQuery(".remove-note[data-note-id="+note_id+"]").parent("li").remove();
						}
					}
				});
			});
		});
	</script>
<?php
}
add_action( 'admin_head', 'rg_custom_admin_head' );

add_action("wp_ajax_add_internal_notes", "add_internal_notes");
function add_internal_notes() {
    $internal_note = trim($_POST["internal_note"]);
    $user_id = trim($_POST["user_id"]);

	global $wpdb;
	$table_name = $wpdb->prefix . "internal_notes";
	$time = current_time( 'mysql' );

	$wpdb->insert( $table_name, array("user_id" => $user_id, "note" => $internal_note, "cdate" => $time ), array('%d', '%s', '%s'));

	$notes_html = '';
	$internal_notes = $wpdb->get_results( "SELECT * FROM $table_name WHERE user_id=$user_id ORDER BY cdate DESC" );
	if($wpdb->num_rows > 0) {
		foreach($internal_notes as $note) {
			$notes_html .= '<li>'.$note->cdate.' - '.$note->note.' <span class="remove-note" data-note-id="'.$note->ID.'">X</span></li>';
		}
	}
		
	echo json_encode(array("success" => true, "notes_html" => $notes_html));
	exit;
}

add_action("wp_ajax_delete_internal_notes", "delete_internal_notes");
function delete_internal_notes() {
    $note_id = trim($_POST["note_id"]);
    $user_id = trim($_POST["user_id"]);

	global $wpdb;
	$table_name = $wpdb->prefix . "internal_notes";

	$wpdb->delete( $table_name, array( 'ID' => $note_id, 'user_id' => $user_id ), array( '%d', '%d' ) );
		
	echo json_encode(array("success" => true));
	exit;
}

add_action("wp_ajax_send_onboard_app_email", "send_onboard_app_email");
function send_onboard_app_email() {
	$user_id = get_current_user_id();
	$show_company_info = get_user_meta( $user_id, 'cmb_user_company_info', true );
	$show_product_info = get_user_meta( $user_id, 'cmb_user_product_info', true );
	$show_w9_form = get_user_meta( $user_id, 'cmb_user_w9_form', true );
	$show_travel_protection = get_user_meta( $user_id, 'cmb_user_travel_protection', true );
	$show_property_protection = get_user_meta( $user_id, 'cmb_user_property_protection', true );
	$show_liability_protection = get_user_meta( $user_id, 'cmb_user_liability_protection', true );
	$show_schedule_training = get_user_meta( $user_id, 'cmb_user_schedule_training', true );
	$show_licensing_appointments = get_user_meta( $user_id, 'cmb_user_licensing_appointments', true );
	
	/*$bill_me_later = $_POST['bill_me_later'];
	if($bill_me_later != '') {
		update_user_meta( $user_id, 'bill_me_later', $bill_me_later );
	} else {
		update_user_meta( $user_id, 'bill_me_later', '' );
	}*/
	
	//$pay_signature = $_POST['pay_signature'];
	//update_user_meta( $user_id, 'pay_signature', $pay_signature );
	$e_signature = $_POST['e_signature'];
	update_user_meta( $user_id, 'e_signature', $e_signature );
	$signature_progress = $_POST['signature_progress'];
	update_user_meta( $user_id, 'signature_progress', $signature_progress );

	$attachFiles = array();
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	//$headers .= 'From: Rental Guardian <info@sitenex.com>' . "\r\n";
	$message = '';
	if($show_company_info != "No") {
		$message .= '<h3>Company Info</h3>';
		$message .= '<p>Company Name/DBA: '.get_user_meta($user_id, 'company_name', true).'</p>';
		$message .= '<p>Applying: '.get_user_meta($user_id, 'appling', true).'</p>';
		$message .= '<p>Type of Company: '.get_user_meta($user_id, 'company_type', true).'</p>';
		if(get_user_meta($user_id, 'reseller_id', true)) {
			$message .= '<p>Tax/Reseller Id: '.get_user_meta($user_id, 'reseller_id', true).'</p>';
		}
		$message .= '<p>Years in Business: '.get_user_meta($user_id, 'years_in_business', true).'</p>';
		$message .= '<hr>';
		$message .= '<p>Street Address: '.get_user_meta($user_id, 'street_address', true).'</p>';
		if(get_user_meta($user_id, 'street_address_line_2', true)) {
			$message .= '<p>Street Address Line 2: '.get_user_meta($user_id, 'street_address_line_2', true).'</p>';
		}
		$message .= '<p>City: '.get_user_meta($user_id, 'city', true).'</p>';
		$message .= '<p>State / Province: '.get_user_meta($user_id, 'state_province', true).'</p>';
		$message .= '<p>Postal / Zip Code: '.get_user_meta($user_id, 'co_postal_code', true).'</p>';
		$message .= '<p>Country: '.get_user_meta($user_id, 'co_country', true).'</p>';
		$message .= '<hr>';
		$message .= '<p>Legal / Authorized Representatives Name: '.get_user_meta($user_id, 'representatives_first_name', true).' '.get_user_meta($user_id, 'representatives_last_name', true).'</p>';
		if(get_user_meta($user_id, 'job_position', true)) {
			$message .= '<p>Job Position / Title: '.get_user_meta($user_id, 'job_position', true).'</p>';
		}
		$message .= '<p>Applicant E-mail: '.get_user_meta($user_id, 'applicant_email', true).'</p>';
		$message .= '<p>Phone Number: '.get_user_meta($user_id, 'applicant_phone_number', true).'</p>';
		$message .= '<p>Do you have an Administrative Department: '.get_user_meta($user_id, 'administrative_department', true).'</p>';
		$message .= '<p>Do you have an IT / Technology Department: '.get_user_meta($user_id, 'technology_department', true).'</p>';
		$message .= '<p>Do you have an Accountant/Accounting Department: '.get_user_meta($user_id, 'accounting_department', true).'</p>';
		$message .= '<hr>';
		$message .= '<p>Company Website Address/URL: '.get_user_meta($user_id, 'company_website_address', true).'</p>';
		if(get_user_meta($user_id, 'company_email', true)) {
			$message .= '<p>Company Email Address: '.get_user_meta($user_id, 'company_email', true).'</p>';
		}
		//$message .= '<p>Main Phone Number Area Code: '.get_user_meta($user_id, 'main_phone_area_code', true).'</p>';
		$message .= '<p>Main Phone Number: '.get_user_meta($user_id, 'main_phone_number', true).'</p>';
		$message .= '<p>Do you have Properties located in the U.S.?: '.get_user_meta($user_id, 'properties_located_usa', true).'</p>';
		$message .= '<p>Do you have Properties located Internationally?: '.get_user_meta($user_id, 'properties_located_internationally', true).'</p>';
		$message .= '<p>Current Management Software System: '.get_user_meta($user_id, 'content_management_software_system', true).'</p>';
		if(get_user_meta($user_id, 'company_information_notes', true)) {
			$message .= '<p>Company Information Notes / Comments: '.get_user_meta($user_id, 'company_information_notes', true).'</p>';
		}
	}
	if($show_product_info != "No") {
		$message .= '<h3>Requested Coverage Information</h3>';
		$message .= '<p>Desired Geographical Coverage (U.S. Domestic/International): '.get_user_meta($user_id, 'geographical_coverage', true).'</p>';
		$message .= '<p>Travel Protection: '.get_user_meta($user_id, 'travel_protection', true).'</p>';
		$message .= '<p>Property Protection / Damage Protection: '.get_user_meta($user_id, 'property_damage_protection', true).'</p>';
		$message .= '<p>Liability & Real Property Protection: '.get_user_meta($user_id, 'liability_property_protection', true).'</p>';
		$message .= '<p>Who will oversee claims within your team?: Name- '.get_user_meta($user_id, 'oversee_claims_team_name', true).', Email- '.get_user_meta($user_id, 'oversee_claims_team_email', true).', Phone- '.get_user_meta($user_id, 'oversee_claims_team_phone', true).'</p>';
		if(get_user_meta($user_id, 'additional_comments', true)) {
			$message .= '<p>Additional Comments: '.get_user_meta($user_id, 'additional_comments', true).'</p>';
		}
	}
	if($show_w9_form != "No") {
		$message .= '<h3>W-9 Form</h3>';
		$message .= '<p>Have you accurately completed the above PDF and Signed it?: '.get_user_meta($user_id, 'sign_w9_form_docusign_form', true).'</p>';
	}
	if($show_travel_protection != "No") {
		$message .= '<h3>Travel Protection</h3>';
		$message .= '<p>Have you accurately completed the above PDF and Signed it?: '.get_user_meta($user_id, 'sign_travel_docusign_form', true).'</p>';
	}
	if($show_property_protection != "No") {
		$message .= '<h3>Property Protection / Damage Protection</h3>';
		$message .= '<p>Have you accurately completed the above PDF and Signed it?: '.get_user_meta($user_id, 'sign_property_docusign_form', true).'</p>';
	}
	if($show_liability_protection != "No") {
		$message .= '<h3>Liability & Real Property Protection</h3>';
		$message .= '<p>Have you accurately completed the above PDF and Signed it?: '.get_user_meta($user_id, 'sign_liability_docusign_form', true).'</p>';
	}
	if($show_schedule_training != "No") {
		$message .= '<h3>Schedule Training</h3>';
		$message .= '<p>Please invite me to the two standard weekly training sessions: '.get_user_meta($user_id, 'standard_weekly_training_sessions', true).'</p>';
		$message .= '<p>I would like to set up a custom date & time for a combined Platform & Products training session: Date='.get_user_meta($user_id, 'product_training_custom_date', true).', Time-'.get_user_meta($user_id, 'product_training_custom_time', true).', Timezone-'.get_user_meta($user_id, 'product_training_custom_timezone', true).'</p>';
		$message .= '<p style="margin-bottom:0;">Who will attend: </p>';
		$num_of_attendee = get_user_meta($user_id, 'num_of_attendee', true);
		if($num_of_attendee) {
			$message .= '<table border="1" cellspacing="0" cellpadding="10">
							<tr>
								<th>Name</th>
								<th>Email</th>
							</tr>';
			for($i=1; $i<=$num_of_attendee; $i++) {
				$message .= '<tr>
								<td>'.get_user_meta($user_id, 'attendee_'.$i.'_name', true).'</td>
								<td>'.get_user_meta($user_id, 'attendee_'.$i.'_email', true).'</td>
							</tr>';
			}
			$message .= '</table>';
		}
	}
	if($show_licensing_appointments != "No") {
		$message .= '<h3>Licensing &amp; Appointments</h3>';
		$message .= '<p>Social Security Number: '.get_user_meta($user_id, 'la_1_security_number', true).'</p>';
		if(get_user_meta($user_id, 'la_1_assigned_npn', true)) {
			$message .= '<p>If assigned, National Producer Number (NPN): '.get_user_meta($user_id, 'la_1_assigned_npn', true).'</p>';
		}
		$message .= '<p>First Name: '.get_user_meta($user_id, 'la_1_first_name', true).'</p>';
		$message .= '<p>Middle Name: '.get_user_meta($user_id, 'la_1_middle_name', true).'</p>';
		$message .= '<p>Last Name: '.get_user_meta($user_id, 'la_1_last_name', true).'</p>';
		$message .= '<p>Date of Birth: '.get_user_meta($user_id, 'la_1_birth_day', true).'</p>';
		$message .= '<p>Residence/Home Address (Physical Street): '.get_user_meta($user_id, 'la_1_home_address', true).'</p>';
		$message .= '<p>City: '.get_user_meta($user_id, 'la_1_city', true).'</p>';
		$message .= '<p>State: '.get_user_meta($user_id, 'la_1_state', true).'</p>';
		if(get_user_meta($user_id, 'la_1_zip_code', true)) {
			$message .= '<p>Zip Code: '.get_user_meta($user_id, 'la_1_zip_code', true).'</p>';
		}
		/*if(get_user_meta($user_id, 'la_1_foreign_country', true)) {
			$message .= '<p>Foreign Country: '.get_user_meta($user_id, 'la_1_foreign_country', true).'</p>';
		}*/
		$message .= '<p>Home Phone Number: '.get_user_meta($user_id, 'la_1_home_phone', true).'</p>';
		$message .= '<p>Gender: '.get_user_meta($user_id, 'la_1_gender', true).'</p>';
		$message .= '<p>Are you a Citizen of the United States?: '.get_user_meta($user_id, 'la_1_citizen_usa', true).'</p>';
		$message .= '<p>Individual Applicant Email Address: '.get_user_meta($user_id, 'la_1_applicant_email', true).'</p>';
		$message .= '<p>List your Insurance Agency Affiliations:</p>';
		$num_of_agency_aff = get_user_meta($user_id, 'la_1_num_of_agency_aff', true);
		if($num_of_agency_aff) {
			$message .= '<table border="1" cellspacing="0" cellpadding="10">
							<tr>
								<th>FEIN</th>
								<th>NPN</th>
								<th>Name of Agency</th>
							</tr>';
			for($i=1; $i<=$num_of_agency_aff; $i++) {
				$message .= '<tr>
								<td>'.get_user_meta($user_id, 'la_1_FEIN_'.$i, true).'</td>
								<td>'.get_user_meta($user_id, 'la_1_NPN_'.$i, true).'</td>
								<td>'.get_user_meta($user_id, 'la_1_agency_name_'.$i, true).'</td>
							</tr>';
			}
			$message .= '</table>';
		}
		$message .= '<p>Employment Experience:</p>';
		$num_of_employment = get_user_meta($user_id, 'la_1_num_of_employment', true);
		if($num_of_employment) {
			$message .= '<table border="1" cellspacing="0" cellpadding="10">
							<tr>
								<th>Name</th>
								<th>City</th>
								<th>State</th>
								<th>From</th>
								<th>To</th>
								<th>Position Held</th>
							</tr>';
			for($i=1; $i<=$num_of_employment; $i++) {
				$message .= '<tr>
								<td>'.get_user_meta($user_id, 'la_1_employee_'.$i.'_name', true).'</td>
								<td>'.get_user_meta($user_id, 'la_1_employee_'.$i.'_city', true).'</td>
								<td>'.get_user_meta($user_id, 'la_1_employee_'.$i.'_state', true).'</td>
								<td>'.get_user_meta($user_id, 'la_1_employee_'.$i.'_from_month', true).'</td>
								<td>'.get_user_meta($user_id, 'la_1_employee_'.$i.'_to_month', true).'</td>
								<td>'.get_user_meta($user_id, 'la_1_employee_'.$i.'_position_held', true).'</td>
							</tr>';
			}
			$message .= '</table>';
		}
		if(get_user_meta($user_id, 'la_1_misdemeanor', true)) {
			$message .= '<p>1a. Have you ever been convicted of a misdemeanor, had a judgment withheld or deferred, or are you currently charged with committing a misdemeanor?: '.get_user_meta($user_id, 'la_1_misdemeanor', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_felony', true)) {
			$message .= '<p>1b. Have you ever been convicted of a felony, had a judgment withheld or deferred, or are you currently charged with committing a felony?: '.get_user_meta($user_id, 'la_1_felony', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_felony_conviction', true)) {
			$message .= '<p>If you have a felony conviction involving dishonesty or breach of trust, have you applied for written consent to engage in the business of insurance in your home state as required by 18 USC 1033?: '.get_user_meta($user_id, 'la_1_felony_conviction', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_consent_granted', true)) {
			$message .= '<p>If so, was consent granted? (Attach copy of 1033 consent approved by home state.): '.get_user_meta($user_id, 'la_1_consent_granted', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_consent_granted', true)) {
			$message .= '<p>2. Have you ever been named or involved as a party in an administrative proceeding, including FINRA sanction or arbitration proceeding regarding any professional or occupational license or registration?: '.get_user_meta($user_id, 'la_1_occupational_license', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_applicant_comment', true)) {
			$message .= '<p>Comment: '.get_user_meta($user_id, 'la_1_applicant_comment', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_applicant_file', true)) {
			$file_attributes = wp_get_attachment_image_src( get_user_meta($user_id, 'la_1_applicant_file', true), 'full' );
			$filePath = str_replace(home_url("/wp-content"), WP_CONTENT_DIR, $file_attributes[0]);
			$attachFiles[] = $filePath;
		}
		if(get_user_meta($user_id, 'la_1_demand', true)) {
			$message .= '<p>3. Has any demand been made or judgment rendered against you or any business in which you are or were an owner, partner, officer or director, or member or manager of a limited liability company, for overdue monies by an insurer, insured or producer, or have you ever been subject to a bankruptcy proceeding?: '.get_user_meta($user_id, 'la_1_demand', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_answer_submit', true)) {
			$message .= '<p>Statement: '.get_user_meta($user_id, 'la_1_answer_submit', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_repayment_agreement', true)) {
			$message .= '<p>4. Have you been notified by any jurisdiction to which you are applying of any delinquent tax obligation that is not the subject of a repayment agreement?: '.get_user_meta($user_id, 'la_1_repayment_agreement', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_answer_jurisdiction', true)) {
			$message .= '<p>Jurisdiction(s): '.get_user_meta($user_id, 'la_1_answer_jurisdiction', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_iduciary_duty', true)) {
			$message .= '<p>5. Are you currently a party to, or have you ever been found liable in, any lawsuit, arbitrations or mediation proceeding involving allegations of fraud, misappropriation or conversion of funds, misrepresentation or breach of fiduciary duty?: '.get_user_meta($user_id, 'la_1_iduciary_duty', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_applicant2_comment', true)) {
			$message .= '<p>Comment: '.get_user_meta($user_id, 'la_1_applicant2_comment', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_applicant2_file', true)) {
			$file_attributes = wp_get_attachment_image_src( get_user_meta($user_id, 'la_1_applicant2_file', true), 'full' );
			$filePath = str_replace(home_url("/wp-content"), WP_CONTENT_DIR, $file_attributes[0]);
			$attachFiles[] = $filePath;
		}
		if(get_user_meta($user_id, 'la_1_alleged_misconduct', true)) {
			$message .= '<p>6. Have you or any business in which you are or were an owner, partner, officer or director, or member or manager of a limited liability company, ever had an insurance agency contract or any other business relationship with an insurance company terminated for any alleged misconduct?: '.get_user_meta($user_id, 'la_1_alleged_misconduct', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_applicant3_comment', true)) {
			$message .= '<p>Comment: '.get_user_meta($user_id, 'la_1_applicant3_comment', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_applicant3_file', true)) {
			$file_attributes = wp_get_attachment_image_src( get_user_meta($user_id, 'la_1_applicant3_file', true), 'full' );
			$filePath = str_replace(home_url("/wp-content"), WP_CONTENT_DIR, $file_attributes[0]);
			$attachFiles[] = $filePath;
		}

		if(get_user_meta($user_id, 'la_1_obligation_arrearage', true)) {
			$message .= '<p>7. Do you have a child support obligation in arrearage?: '.get_user_meta($user_id, 'la_1_obligation_arrearage', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_applicant4_comment', true)) {
			$message .= '<p>Comment: '.get_user_meta($user_id, 'la_1_applicant4_comment', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_applicant4_file', true)) {
			$file_attributes = wp_get_attachment_image_src( get_user_meta($user_id, 'la_1_applicant4_file', true), 'full' );
			$filePath = str_replace(home_url("/wp-content"), WP_CONTENT_DIR, $file_attributes[0]);
			$attachFiles[] = $filePath;
		}
		if(get_user_meta($user_id, 'la_1_warehouse', true)) {
			$message .= '<p>8. In response to a "yes" answer to one or more of the Background Questions for this application, are you submitting document(s) to the NAIC/NIPR Attachments Warehouse?: '.get_user_meta($user_id, 'la_1_warehouse', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_associating_nipr_attachments', true)) {
			$message .= '<p>Will you be associating (linking) previously filed documents from the NAIC/NIPR Attachments Warehouse to this application?: '.get_user_meta($user_id, 'la_1_associating_nipr_attachments', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_1_authorize_insurestays', true)) {
			$message .= '<p>9. I authorize InsureStays and/or other affiliated insurance agency of RentalGuardian to acquire these licenses on my behalf based on the information I have truthfully provided in this web form.: '.get_user_meta($user_id, 'la_1_authorize_insurestays', true).'</p>';
		}
		/* End of Step 1 */
		$message .= '<hr>';
		$message .= '<p>Incorporation/Formation Date: '.get_user_meta($user_id, 'la_2_incorporation_date', true).'</p>';
		if(get_user_meta($user_id, 'la_2_affiliated_financial_institution', true)) {
			$message .= '<p>Is the business entity affiliated with a financial institution/bank?: '.get_user_meta($user_id, 'la_2_affiliated_financial_institution', true).'</p>';
		}
		$message .= '<p>Business Address: '.get_user_meta($user_id, 'la_2_business_address', true).'</p>';
		$message .= '<p>Identify at least one Designated/Responsible Licensed Producer: Name-'.get_user_meta($user_id, 'la_2_license_name', true).', SSN-'.get_user_meta($user_id, 'la_2_license_ssn', true).'</p>';
		$message .= '<p>Identify all owners with 10% or more voting interest:</p>';
		$message .= '<table border="1" cellspacing="0" cellpadding="10">';
		$message .= '<tr>
						<td>Name: '.get_user_meta($user_id, 'la_2_entity_1_name', true).'</td>
						<td>Title: '.get_user_meta($user_id, 'la_2_entity_1_title', true).'</td>
						<td>SSN: '.get_user_meta($user_id, 'la_2_entity_1_ssn', true).'</td>
						<td>% of Ownership Interest: '.get_user_meta($user_id, 'la_2_entity_1_interest_rate', true).'</td>
						<td>Date of Birth: '.get_user_meta($user_id, 'la_2_birth_day', true).'</td>
					</tr>';
		$message .= '</table>';
		if(get_user_meta($user_id, 'la_2_business_entity', true)) {
			$message .= '<p>1a. Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, ever been convicted of a misdemeanor, had a judgment withheld or deferred or is the business entity or any owner, partner, officer or director of the business entity, or member or manager currently charged with, committing a misdemeanor?: '.get_user_meta($user_id, 'la_2_business_entity', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_business_entity_owner', true)) {
			$message .= '<p>1b. Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company ever been convicted of a felony, had judgment withheld or deferred, or is the business entity or any owner, partner, officer or director of the business entity or member or manager of a limited liability company currently charged with committing a felony?: '.get_user_meta($user_id, 'la_2_business_entity_owner', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_felony_conviction', true)) {
			$message .= '<p>If you have a felony conviction involving dishonesty or breach of trust, have you applied for written consent to engage in the business of insurance in your home state as required by 18 USC 1033?: '.get_user_meta($user_id, 'la_2_felony_conviction', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_consent_granted', true)) {
			$message .= '<p>If so, was consent granted? (Attach copy of 1033 consent approved by home state.): '.get_user_meta($user_id, 'la_2_consent_granted', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_consent_granted_file', true)) {
			$file_attributes = wp_get_attachment_image_src( get_user_meta($user_id, 'la_2_consent_granted_file', true), 'full' );
			$filePath = str_replace(home_url("/wp-content"), WP_CONTENT_DIR, $file_attributes[0]);
			$attachFiles[] = $filePath;
		}
		if(get_user_meta($user_id, 'la_2_military_offense', true)) {
			$message .= '<p>1c. Has the business entity or any owner, partner, officer or director of the business entity or member or manager of a limited liability company, ever been convicted of a military offense, had a judgment withheld or deferred, or is the business entity or any owner, partner, officer or director of the business entity or member or manager of a limited liability company, currently charged with committing a military offense?: '.get_user_meta($user_id, 'la_2_military_offense', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_applicant1_comment', true)) {
			$message .= '<p>Comment: '.get_user_meta($user_id, 'la_2_applicant1_comment', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_applicant1_file', true)) {
			$file_attributes = wp_get_attachment_image_src( get_user_meta($user_id, 'la_2_applicant1_file', true), 'full' );
			$filePath = str_replace(home_url("/wp-content"), WP_CONTENT_DIR, $file_attributes[0]);
			$attachFiles[] = $filePath;
		}
		if(get_user_meta($user_id, 'la_2_occupational_license', true)) {
			$message .= '<p>2. Has the business entity or any owner, partner, officer or director of the business entity, or manager or member of a limited liability company, ever been named or involved as a party in an administrative proceeding, including a FINRA sanction or arbitration proceeding regarding any professional or occupational license, or registration?: '.get_user_meta($user_id, 'la_2_occupational_license', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_applicant2_comment', true)) {
			$message .= '<p>Comment: '.get_user_meta($user_id, 'la_2_applicant2_comment', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_applicant2_file', true)) {
			$file_attributes = wp_get_attachment_image_src( get_user_meta($user_id, 'la_2_applicant2_file', true), 'full' );
			$filePath = str_replace(home_url("/wp-content"), WP_CONTENT_DIR, $file_attributes[0]);
			$attachFiles[] = $filePath;
		}
		if(get_user_meta($user_id, 'la_2_demand', true)) {
			$message .= '<p>3. Has any demand been made or judgment rendered against the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, for overdue monies by an insurer, insured or producer, or have you ever been subject to a bankruptcy proceeding?: '.get_user_meta($user_id, 'la_2_demand', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_arrangements_repayment', true)) {
			$message .= '<p>If you answer yes, submit a statement summarizing the details of the indebtedness and arrangements for repayment: '.get_user_meta($user_id, 'la_2_arrangements_repayment', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_business_entity2', true)) {
			$message .= '<p>4. Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, ever been notified by any jurisdiction to which you are applying of any delinquent tax obligation that is not the subject of a repayment agreement?: '.get_user_meta($user_id, 'la_2_business_entity2', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_identify_jurisdiction', true)) {
			$message .= '<p>Jurisdiction(s): '.get_user_meta($user_id, 'la_2_identify_jurisdiction', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_business_entity3', true)) {
			$message .= '<p>5. Is the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, a party to, or ever been found liable in any lawsuit or arbitration proceeding involving allegations of fraud, misappropriation or conversion of funds, misrepresentation or breach of fiduciary duty?: '.get_user_meta($user_id, 'la_2_business_entity3', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_applicant3_comment', true)) {
			$message .= '<p>Comment: '.get_user_meta($user_id, 'la_2_applicant3_comment', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_applicant3_file', true)) {
			$file_attributes = wp_get_attachment_image_src( get_user_meta($user_id, 'la_2_applicant3_file', true), 'full' );
			$filePath = str_replace(home_url("/wp-content"), WP_CONTENT_DIR, $file_attributes[0]);
			$attachFiles[] = $filePath;
		}
		if(get_user_meta($user_id, 'la_2_business_entity4', true)) {
			$message .= '<p>6. Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company ever had an insurance agency contract or any other business relationship with an insurance company terminated for any alleged misconduct?: '.get_user_meta($user_id, 'la_2_business_entity4', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_applicant4_comment', true)) {
			$message .= '<p>Comment: '.get_user_meta($user_id, 'la_2_applicant4_comment', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_applicant4_file', true)) {
			$file_attributes = wp_get_attachment_image_src( get_user_meta($user_id, 'la_2_applicant4_file', true), 'full' );
			$filePath = str_replace(home_url("/wp-content"), WP_CONTENT_DIR, $file_attributes[0]);
			$attachFiles[] = $filePath;
		}

		if(get_user_meta($user_id, 'la_2_warehouse', true)) {
			$message .= '<p>7. In response to a "yes" answer to one or more of the Background Questions for this application, are you submitting document(s) to the NAIC/NIPR Attachments Warehouse?: '.get_user_meta($user_id, 'la_2_warehouse', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_NIPR_Attachments', true)) {
			$message .= '<p>Will you be associating (linking) previously filed documents from the NAIC/NIPR Attachments Warehouse to this application?: '.get_user_meta($user_id, 'la_2_NIPR_Attachments', true).'</p>';
		}
		if(get_user_meta($user_id, 'la_2_authorize_insurestays', true)) {
			$message .= '<p>9. I authorize InsureStays and/or other affiliated insurance agency of RentalGuardian to acquire these licenses on my behalf based on the information I have truthfully provided in this web form: '.get_user_meta($user_id, 'la_2_authorize_insurestays', true).'</p>';
		}
		/* End of Step 2 */
		$message .= '<hr>';
		$message .= '<h4>Step 3</h4>';
		$message .= '<p>Have you accurately completed the above PDF and Signed it?: '.get_user_meta($user_id, 'sign_las3_docusign_form', true).'</p>';
		$message .= '<hr>';
		$message .= '<h4>Step 4</h4>';
		$message .= '<p>Have you accurately completed the above PDF and Signed it?: '.get_user_meta($user_id, 'sign_las4_docusign_form', true).'</p>';
		$message .= '<hr>';
		$message .= '<p>Licensed Person at your company: '.get_user_meta($user_id, 'licensed_person_first_name', true).' '.get_user_meta($user_id, 'licensed_person_last_name', true).'</p>';
		$message .= '<p>Client Office Address: '.get_user_meta($user_id, 'client_office_address', true).'</p>';
		$message .= '<p>Social Security Number: '.get_user_meta($user_id, 'licensed_person_social_security_number', true).'</p>';
		$message .= '<p>Licensed Person\'s Phone: '.get_user_meta($user_id, 'licensed_person_phone_number', true).'</p>';
		$message .= '<p>Licensed Person\'s Email: '.get_user_meta($user_id, 'licensed_person_email_address', true).'</p>';
	}
	$message .= '<h3>Signature</h3>';
	$message .= '<p>E-Signature: '.get_user_meta($user_id, 'e_signature', true).'</p>';
	
	//@wp_mail( 'saddam987020@gmail.com', 'Onboard Application', $message, $headers, $attachFiles );
	@wp_mail( 'sales@rentalguardian.com', 'Onboard Application', $message, $headers, $attachFiles );
	@wp_mail( 'onboarding@rentalguardian.com', 'Onboard Application', $message, $headers, $attachFiles );
	
	update_user_meta( $user_id, 'onboard_application_submitted', 'Yes' );

	echo json_encode(array("success" => true));
	exit;
}

add_filter( 'retrieve_password_title', function( $title ) {
        $title = __( 'Password Reset - Rental Guardian' );
        return $title;
    }
);

function create_custom_signature($data) {
	$dir = "/signatures";
	//$data = $_POST['imgOutput'];
	$result = '';
	if (is_string($data) && strrpos($data, "data:image/png;base64", -strlen($data)) !== FALSE){
		$data_pieces = explode(",", $data);
		$encoded_image = $data_pieces[1];
		$decoded_image = base64_decode($encoded_image);
		$upload_dir = wp_upload_dir();
		$signature_dir = $upload_dir['basedir'].$dir;
		$signature_dir_url = $upload_dir['baseurl'].$dir;
		if( ! file_exists( $signature_dir ) ) {
			wp_mkdir_p( $signature_dir );
		}
		//$filename = $key."-".time().".png";
		$filename = md5($encoded_image).".png";
		$filepath = $signature_dir."/".$filename;
		
		file_put_contents( $filepath,$decoded_image);
		
		if (file_exists($filepath)){
			// File created : changing posted data to the URL instead of base64 encoded image data
			$fileurl = $signature_dir_url."/".$filename;
			//echo "Signature Successfully Uploaded";
			$result = $fileurl;
		} else { 
			//error_log("Cannot create signature file in directory ".$filepath);
			$result = false;
		}
		return $result;
	} else {
		return false;
	}
}

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

/**
 * Extend TCPDF to work with multiple columns
 */
class MC_TCPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = home_url('/wp-content/uploads/2017/12/logo-1.jpg'); //K_PATH_IMAGES.'logo_example.jpg'
        $this->Image($image_file, 10, 10, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
		// Line break
		$this->Ln();
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

} // end of extended class