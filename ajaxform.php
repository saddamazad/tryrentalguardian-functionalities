<?php
require_once("../../../wp-load.php");

/*if( ! empty( $_FILES ) ) {
    foreach( $_FILES as $file ) {
        if( is_array( $file ) ) {
            $attachment_id = upload_user_file( $file );
			$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
			$url = '';
			if( $image_attributes ) {
				$url = '<div class="the-image"><a href="javascript: void(0);" class="fancybox"><img src="'.$image_attributes[0].'"></a><span class="set-cover-img" data-attachment="'.$attachment_id.'">Set AS Cover Image</span> - <span class="remove-image" data-attachment="'.$attachment_id.'"><img src="'.get_stylesheet_directory_uri().'/images/icon-delete.png"></span></div>';
			}

			$returnArray = array(
				"Success" => true,
				"imageHTML" => $url,
				"attachment_id" => $attachment_id
			);
			
			echo json_encode($returnArray);
			exit;
        }
    }
} else {
	$company_name = $_POST['company_name'];
	$user_id = get_current_user_id();
	echo json_encode( array("Success" => true, "company_name" => $company_name, "user_id" => $user_id) );
	exit;
}*/

if( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "company_info") ) {
	$company_name = $_POST['company_name'];
	$appling = $_POST['appling'];
	$company_type = $_POST['company_type'];
	$reseller_id = $_POST['reseller_id'];
	$years_in_business = $_POST['years_in_business'];
	$preferred_payment_method = $_POST['preferred_payment_method'];

	$street_address = $_POST['street_address'];
	$street_address_line_2 = $_POST['street_address_line_2'];
	$city = $_POST['city'];
	$state_provinc = $_POST['state_provinc'];
	$postal_code = $_POST['postal_code'];
	$country = $_POST['country'];

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$job_position = $_POST['job_position'];
	$applicant_email = $_POST['applicant_email'];
	$phone_number = $_POST['phone_number'];
	$administrative_department = $_POST['administrative_department'];
	$technology_department = $_POST['technology_department'];
	$accounting_department = $_POST['accounting_department'];

	$company_website_address = $_POST['company_website_address'];
	$company_email = $_POST['company_email'];
	$main_phone_number  = $_POST['main_phone_number'];
	$properties_located = $_POST['properties_located'];
	$properties_located_internationally = $_POST['properties_located_internationally'];
	$management_software_system = $_POST['management_software_system'];
	$company_information_notes = $_POST['company_information_notes'];

	$company_info_progress = $_POST['company_info_progress'];
		
	$user_id = get_current_user_id();
	update_user_meta($user_id, 'company_name', $company_name);
	update_user_meta($user_id, 'appling', $appling);
	update_user_meta($user_id, 'company_type', $company_type);
	update_user_meta($user_id, 'reseller_id', $reseller_id);
	update_user_meta($user_id, 'years_in_business', $years_in_business);
	update_user_meta($user_id, 'preferred_payment_method', $preferred_payment_method);

	update_user_meta($user_id, 'street_address', $street_address);
	update_user_meta($user_id, 'street_address_line_2', $street_address_line_2);
	update_user_meta($user_id, 'city', $city);
	update_user_meta($user_id, 'state_province', $state_provinc);
	update_user_meta($user_id, 'co_postal_code', $postal_code);
	update_user_meta($user_id, 'co_country', $country);

	update_user_meta($user_id, 'representatives_first_name', $first_name);
	update_user_meta($user_id, 'representatives_last_name', $last_name);
	update_user_meta($user_id, 'job_position', $job_position);
	update_user_meta($user_id, 'applicant_email', $applicant_email);
	update_user_meta($user_id, 'applicant_phone_number', $phone_number);
	update_user_meta($user_id, 'administrative_department', $administrative_department);
	update_user_meta($user_id, 'technology_department', $technology_department);
	update_user_meta($user_id, 'accounting_department', $accounting_department);
	
	if( isset($_POST['admin_dept_name']) ) {
		update_user_meta($user_id, 'admin_dept_name', $_POST['admin_dept_name']);
	}
	if( isset($_POST['admin_dept_email']) ) {
		update_user_meta($user_id, 'admin_dept_email', $_POST['admin_dept_email']);
	}
	if( isset($_POST['tech_dept_name']) ) {
		update_user_meta($user_id, 'tech_dept_name', $_POST['tech_dept_name']);
	}
	if( isset($_POST['tech_dept_email']) ) {
		update_user_meta($user_id, 'tech_dept_email', $_POST['tech_dept_email']);
	}
	if( isset($_POST['accounting_dept_name']) ) {
		update_user_meta($user_id, 'accounting_dept_name', $_POST['accounting_dept_name']);
	}
	if( isset($_POST['accounting_dept_email']) ) {
		update_user_meta($user_id, 'accounting_dept_email', $_POST['accounting_dept_email']);
	}
	
	update_user_meta($user_id, 'company_website_address', $company_website_address);
	update_user_meta($user_id, 'company_email', $company_email);
	update_user_meta($user_id, 'main_phone_number', $main_phone_number);
	update_user_meta($user_id, 'properties_located_usa', $properties_located);
	update_user_meta($user_id, 'properties_located_internationally', $properties_located_internationally);
	update_user_meta($user_id, 'content_management_software_system', $management_software_system);
	update_user_meta($user_id, 'company_information_notes', $company_information_notes);

	update_user_meta($user_id, 'company_info_progress', $company_info_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set some language-dependent strings (optional)
		/*if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}*/
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Company Info</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Company Name/DBA:</strong>
							<br>
							'.get_user_meta($user_id, 'company_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Applying:</strong>
							<br>
							'.get_user_meta($user_id, 'appling', true).'
						</td>
						<td>
							<strong style="color:#333333;">Tax/Reseller Id:</strong>
							<br>
							'.get_user_meta($user_id, 'reseller_id', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Type of Company:</strong>
							<br>
							'.get_user_meta($user_id, 'company_type', true).'
						</td>
						<td>
							<strong style="color:#333333;">Years in Business:</strong>
							<br>
							'.get_user_meta($user_id, 'years_in_business', true).'
						</td>
						<td>
							<strong style="color:#333333;">Preferred Method of Payment:</strong>
							<br>
							'.get_user_meta($user_id, 'preferred_payment_method', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Street Address:</strong>
							<br>
							'.get_user_meta($user_id, 'street_address', true).'
						</td>
						<td>
							<strong style="color:#333333;">Street Address Line 2:</strong>
							<br>
							'.get_user_meta($user_id, 'street_address_line_2', true).'
						</td>
						<td>
							<strong style="color:#333333;">City:</strong>
							<br>
							'.get_user_meta($user_id, 'city', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">State / Province:</strong>
							<br>
							'.get_user_meta($user_id, 'state_province', true).'
						</td>
						<td>
							<strong style="color:#333333;">Postal / Zip Code:</strong>
							<br>
							'.get_user_meta($user_id, 'co_postal_code', true).'
						</td>
						<td>
							<strong style="color:#333333;">Country:</strong>
							<br>
							'.get_user_meta($user_id, 'co_country', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Legal / Authorized Representatives Name:</strong>
							<br>
							'.get_user_meta($user_id, 'representatives_first_name', true).' '.get_user_meta($user_id, 'representatives_last_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Job Position / Title:</strong>
							<br>
							'.get_user_meta($user_id, 'job_position', true).'
						</td>
						<td>
							<strong style="color:#333333;">Applicant E-mail:</strong>
							<br>
							'.get_user_meta($user_id, 'applicant_email', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Phone Number:</strong>
							<br>
							'.get_user_meta($user_id, 'applicant_phone_number', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you have an Administrative Department?</strong>
							<br>
							'.get_user_meta($user_id, 'administrative_department', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you have an IT / Technology Department?</strong>
							<br>
							'.get_user_meta($user_id, 'technology_department', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Do you have an Accountant/Accounting Department?</strong>
							<br>
							'.get_user_meta($user_id, 'accounting_department', true).'
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Company Website Address/URL:</strong>
							<br>
							'.get_user_meta($user_id, 'company_website_address', true).'
						</td>
						<td colspan="2">
							<strong style="color:#333333;">Company Email Address:</strong>
							<br>
							'.get_user_meta($user_id, 'company_email', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Main Phone Number:</strong>
							<br>
							'.get_user_meta($user_id, 'main_phone_number', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you have Properties located in the U.S.?</strong>
							<br>
							'.get_user_meta($user_id, 'properties_located_usa', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you have Properties located Internationally?</strong>
							<br>
							'.get_user_meta($user_id, 'properties_located_internationally', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Current Management Software System:</strong>
							<br>
							'.get_user_meta($user_id, 'content_management_software_system', true).'
						</td>
						<td>
							<strong style="color:#333333;">Company Information Notes / Comments:</strong>
							<br>
							'.get_user_meta($user_id, 'company_information_notes', true).'
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Company-Info-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'company_info_pdf', $pdf_dir_url.'/Company-Info-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Company-Info-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "company_info_step_2") ) {
	$street_address = $_POST['street_address'];
	$street_address_line_2 = $_POST['street_address_line_2'];
	$city = $_POST['city'];
	$state_provinc = $_POST['state_provinc'];
	$postal_code = $_POST['postal_code'];
	$country = $_POST['country'];
	$company_info_step_2_progress = $_POST['company_info_step_2_progress'];
	
	$user_id = get_current_user_id();
	update_user_meta($user_id, 'street_address', $street_address);
	update_user_meta($user_id, 'street_address_line_2', $street_address_line_2);
	update_user_meta($user_id, 'city', $city);
	update_user_meta($user_id, 'state_province', $state_provinc);
	update_user_meta($user_id, 'co_postal_code', $postal_code);
	update_user_meta($user_id, 'co_country', $country);
	update_user_meta($user_id, 'company_info_step_2_progress', $company_info_step_2_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Company Info <span style="color: #3894D7;">2</span> of 4</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Street Address:</strong>
							<br>
							'.get_user_meta($user_id, 'street_address', true).'
						</td>
						<td>
							<strong style="color:#333333;">Street Address Line 2:</strong>
							<br>
							'.get_user_meta($user_id, 'street_address_line_2', true).'
						</td>
						<td>
							<strong style="color:#333333;">City:</strong>
							<br>
							'.get_user_meta($user_id, 'city', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">State / Province:</strong>
							<br>
							'.get_user_meta($user_id, 'state_province', true).'
						</td>
						<td>
							<strong style="color:#333333;">Postal / Zip Code:</strong>
							<br>
							'.get_user_meta($user_id, 'co_postal_code', true).'
						</td>
						<td>
							<strong style="color:#333333;">Country:</strong>
							<br>
							'.get_user_meta($user_id, 'co_country', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Company-Info-2-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'company_info_2_pdf', $pdf_dir_url.'/Company-Info-2-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Company-Info-2-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "company_info_step_3") ) {
	//$name_prefix = $_POST['name_prefix'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$job_position = $_POST['job_position'];
	$applicant_email = $_POST['applicant_email'];
	$phone_number = $_POST['phone_number'];
	$administrative_department = $_POST['administrative_department'];
	$technology_department = $_POST['technology_department'];
	$accounting_department = $_POST['accounting_department'];
	$company_info_step_3_progress = $_POST['company_info_step_3_progress'];
	
	$user_id = get_current_user_id();
	//update_user_meta($user_id, 'name_prefix', $name_prefix);
	update_user_meta($user_id, 'representatives_first_name', $first_name);
	update_user_meta($user_id, 'representatives_last_name', $last_name);
	update_user_meta($user_id, 'job_position', $job_position);
	update_user_meta($user_id, 'applicant_email', $applicant_email);
	update_user_meta($user_id, 'applicant_phone_number', $phone_number);
	update_user_meta($user_id, 'administrative_department', $administrative_department);
	update_user_meta($user_id, 'technology_department', $technology_department);
	update_user_meta($user_id, 'accounting_department', $accounting_department);
	update_user_meta($user_id, 'company_info_step_3_progress', $company_info_step_3_progress);
	
	if( isset($_POST['admin_dept_name']) ) {
		update_user_meta($user_id, 'admin_dept_name', $_POST['admin_dept_name']);
	}
	if( isset($_POST['admin_dept_email']) ) {
		update_user_meta($user_id, 'admin_dept_email', $_POST['admin_dept_email']);
	}
	if( isset($_POST['tech_dept_name']) ) {
		update_user_meta($user_id, 'tech_dept_name', $_POST['tech_dept_name']);
	}
	if( isset($_POST['tech_dept_email']) ) {
		update_user_meta($user_id, 'tech_dept_email', $_POST['tech_dept_email']);
	}
	if( isset($_POST['accounting_dept_name']) ) {
		update_user_meta($user_id, 'accounting_dept_name', $_POST['accounting_dept_name']);
	}
	if( isset($_POST['accounting_dept_email']) ) {
		update_user_meta($user_id, 'accounting_dept_email', $_POST['accounting_dept_email']);
	}
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Company Info <span style="color: #3894D7;">3</span> of 4</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Legal / Authorized Representatives Name:</strong>
							<br>
							'.get_user_meta($user_id, 'representatives_first_name', true).' '.get_user_meta($user_id, 'representatives_last_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Job Position / Title:</strong>
							<br>
							'.get_user_meta($user_id, 'job_position', true).'
						</td>
						<td>
							<strong style="color:#333333;">Applicant E-mail:</strong>
							<br>
							'.get_user_meta($user_id, 'applicant_email', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Phone Number:</strong>
							<br>
							'.get_user_meta($user_id, 'applicant_phone_number', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you have an Administrative Department?</strong>
							<br>
							'.get_user_meta($user_id, 'administrative_department', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you have an IT / Technology Department?</strong>
							<br>
							'.get_user_meta($user_id, 'technology_department', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Do you have an Accountant/Accounting Department?</strong>
							<br>
							'.get_user_meta($user_id, 'accounting_department', true).'
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Company-Info-3-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'company_info_3_pdf', $pdf_dir_url.'/Company-Info-3-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Company-Info-3-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "company_info_step_4") ) {
	$company_website_address = $_POST['company_website_address'];
	$company_email = $_POST['company_email'];
	//$area_code = $_POST['area_code'];
	$main_phone_number  = $_POST['main_phone_number'];
	$properties_located = $_POST['properties_located'];
	$properties_located_internationally = $_POST['properties_located_internationally'];
	$management_software_system = $_POST['management_software_system'];
	$company_information_notes = $_POST['company_information_notes'];
	$company_info_step_4_progress = $_POST['company_info_step_4_progress'];
	
	$user_id = get_current_user_id();
	update_user_meta($user_id, 'company_website_address', $company_website_address);
	update_user_meta($user_id, 'company_email', $company_email);
	//update_user_meta($user_id, 'main_phone_area_code', $area_code);
	update_user_meta($user_id, 'main_phone_number', $main_phone_number);
	update_user_meta($user_id, 'properties_located_usa', $properties_located);
	update_user_meta($user_id, 'properties_located_internationally', $properties_located_internationally);
	update_user_meta($user_id, 'content_management_software_system', $management_software_system);
	update_user_meta($user_id, 'company_information_notes', $company_information_notes);
	update_user_meta($user_id, 'company_info_step_4_progress', $company_info_step_4_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Company Info <span style="color: #3894D7;">4</span> of 4</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Company Website Address/URL:</strong>
							<br>
							'.get_user_meta($user_id, 'company_website_address', true).'
						</td>
						<td colspan="2">
							<strong style="color:#333333;">Company Email Address:</strong>
							<br>
							'.get_user_meta($user_id, 'company_email', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Main Phone Number:</strong>
							<br>
							'.get_user_meta($user_id, 'main_phone_number', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you have Properties located in the U.S.?</strong>
							<br>
							'.get_user_meta($user_id, 'properties_located_usa', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you have Properties located Internationally?</strong>
							<br>
							'.get_user_meta($user_id, 'properties_located_internationally', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Current Management Software System:</strong>
							<br>
							'.get_user_meta($user_id, 'content_management_software_system', true).'
						</td>
						<td>
							<strong style="color:#333333;">Company Information Notes / Comments:</strong>
							<br>
							'.get_user_meta($user_id, 'company_information_notes', true).'
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Company-Info-4-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'company_info_4_pdf', $pdf_dir_url.'/Company-Info-4-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Company-Info-4-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "product-info") ) {
	$geographical_coverage = $_POST['geographical_coverage'];
	$travel_protection = $_POST['travel_protection'];
	$property_damage_protection = $_POST['property_damage_protection'];
	$liability_property_protection  = $_POST['liability_property_protection'];
	$team_name = $_POST['team_name'];
	$team_email = $_POST['team_email'];
	$team_phone = $_POST['team_phone'];
	$additional_comments = $_POST['additional_comments'];
	$product_info_progress = $_POST['product_info_progress'];
	
	$user_id = get_current_user_id();
	update_user_meta($user_id, 'geographical_coverage', $geographical_coverage);
	update_user_meta($user_id, 'travel_protection', $travel_protection);
	update_user_meta($user_id, 'property_damage_protection', $property_damage_protection);
	update_user_meta($user_id, 'liability_property_protection', $liability_property_protection);
	update_user_meta($user_id, 'oversee_claims_team_name', $team_name);
	update_user_meta($user_id, 'oversee_claims_team_email', $team_email);
	update_user_meta($user_id, 'oversee_claims_team_phone', $team_phone);
	update_user_meta($user_id, 'additional_comments', $additional_comments);
	update_user_meta($user_id, 'product_info_progress', $product_info_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Requested Coverage Information</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Desired Geographical Coverage (U.S. Domestic/International):</strong>
							<br>
							'.get_user_meta($user_id, 'geographical_coverage', true).'
						</td>
						<td>
							<strong style="color:#333333;">Travel Protection:</strong>
							<br>
							'.get_user_meta($user_id, 'travel_protection', true).'
						</td>
						<td>
							<strong style="color:#333333;">Property Protection / Damage Protection:</strong>
							<br>
							'.get_user_meta($user_id, 'property_damage_protection', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Liability & Real Property Protection:</strong>
							<br>
							'.get_user_meta($user_id, 'liability_property_protection', true).'
						</td>
						<td>
							<strong style="color:#333333;">Who will oversee claims within your team?</strong>
							<br>
							Name: '.get_user_meta($user_id, 'oversee_claims_team_name', true).'<br>
							Email: '.get_user_meta($user_id, 'oversee_claims_team_email', true).'<br>
							Phone: '.get_user_meta($user_id, 'oversee_claims_team_phone', true).'<br>
						</td>
						<td>
							<strong style="color:#333333;">Additional Comments:</strong>
							<br>
							'.get_user_meta($user_id, 'additional_comments', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Requested-Coverage-Information-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'requested_coverage_information_pdf', $pdf_dir_url.'/Requested-Coverage-Information-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Requested-Coverage-Information-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "schedule-training") ) {
	$standard_weekly_training = $_POST['standard_weekly_training'];
	$product_training_date = $_POST['product_training_date'];
	$product_training_time = $_POST['product_training_time'];
	$product_training_timezone  = $_POST['product_training_timezone'];
	$schedule_training_progress  = $_POST['schedule_training_progress'];
	
	$num_of_attendee = $_POST['num_of_attendee'];

	$user_id = get_current_user_id();
	update_user_meta($user_id, 'standard_weekly_training_sessions', $standard_weekly_training);
	update_user_meta($user_id, 'product_training_custom_date', $product_training_date);
	update_user_meta($user_id, 'product_training_custom_time', $product_training_time);
	update_user_meta($user_id, 'product_training_custom_timezone', $product_training_timezone);
	update_user_meta($user_id, 'num_of_attendee', $num_of_attendee);
	update_user_meta($user_id, 'schedule_training_progress', $schedule_training_progress);
	
	for($i=1; $i<=$num_of_attendee; $i++) {
		$attend_name = $_POST['attend_'.$i.'_name'];
		$attend_email = $_POST['attend_'.$i.'_email'];

		update_user_meta($user_id, 'attendee_'.$i.'_name', $attend_name);
		update_user_meta($user_id, 'attendee_'.$i.'_email', $attend_email);
	}
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Schedule Training</h1>';
		$html .= '<table border="0" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Please invite me to the two standard weekly training sessions:</strong>
							<br>
							'.get_user_meta($user_id, 'standard_weekly_training_sessions', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">I would like to set up a custom date & time for a combined Platform & Products training session:</strong>
							<br>
							Date: '.get_user_meta($user_id, 'product_training_custom_date', true).'<br>
							Time: '.get_user_meta($user_id, 'product_training_custom_time', true).'<br>
							Timezone: '.get_user_meta($user_id, 'product_training_custom_timezone', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Who will attend:</strong>
							<br>';
							$num_of_attendee = get_user_meta($user_id, 'num_of_attendee', true);
							if($num_of_attendee) {
								for($i=1; $i<=$num_of_attendee; $i++) {
									$html .= 'Attendee '.$i.' Name: '.get_user_meta($user_id, 'attendee_'.$i.'_name', true).'<br>';
									$html .= 'Attendee '.$i.' Email: '.get_user_meta($user_id, 'attendee_'.$i.'_email', true).'<br>';
								}
							}
			$html .= '</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Schedule-Training-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'schedule_training_pdf', $pdf_dir_url.'/Schedule-Training-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Schedule-Training-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "licensing-appointments-step1") ) {
	$la_1_security_number = $_POST['la_1_security_number'];
	$la_1_npn = $_POST['la_1_npn'];
	$la_1_first_name = $_POST['la_1_first_name'];
	$la_1_middle_name = $_POST['la_1_middle_name'];
	$la_1_last_name = $_POST['la_1_last_name'];
	$la_1_birth_day = $_POST['la_1_birth_day'];
	$la_1_home_address = $_POST['la_1_home_address'];
	$la_1_city = $_POST['la_1_city'];
	$la_1_state = $_POST['la_1_state'];
	$la_1_zip_code = $_POST['la_1_zip_code'];
	//$la_1_foreign_country = $_POST['la_1_foreign_country'];
	$la_1_home_phone = $_POST['la_1_home_phone'];
	$la_1_gender = $_POST['la_1_gender'];
	$la_1_citizen_usa = $_POST['la_1_citizen'];
	$la_1_applicant_email = $_POST['la_1_applicant_email'];
	$la_1_misdemeanor = $_POST['la_1_misdemeanor'];
	$la_1_felony = $_POST['la_1_felony'];
	$la_1_felony_conviction = $_POST['la_1_felony_conviction'];
	$la_1_consent_granted = $_POST['la_1_consent_granted'];
	$la_1_occupational_license = $_POST['la_1_occupational_license'];
	$la_1_applicant_comment = $_POST['la_1_applicant_comment'];
	$la_1_applicant_file = $_FILES['la_1_applicant_file'];
	$la_1_demand = $_POST['la_1_demand'];
	$la_1_answer_submit = $_POST['la_1_answer_submit'];
	$la_1_repayment_agreement = $_POST['la_1_repayment_agreement'];
	$la_1_answer_jurisdiction = $_POST['la_1_answer_jurisdiction'];
	$la_1_iduciary_duty = $_POST['la_1_iduciary_duty'];
	$la_1_applicant2_comment = $_POST['la_1_applicant2_comment'];
	$la_1_applicant2_file = $_FILES['la_1_applicant2_file'];
	$la_1_alleged_misconduct = $_POST['la_1_alleged_misconduct'];
	$la_1_applicant3_comment = $_POST['la_1_applicant3_comment'];
	$la_1_applicant3_file = $_FILES['la_1_applicant3_file'];
	$la_1_obligation_arrearage = $_POST['la_1_obligation_arrearage'];
	$la_1_applicant4_comment = $_POST['la_1_applicant4_comment'];
	$la_1_applicant4_file = $_FILES['la_1_applicant4_file'];
	$la_1_warehouse = $_POST['la_1_warehouse'];
	$la_1_NIPR_Attachments = $_POST['la_1_NIPR_Attachments'];
	$la_1_authorize_insurestays = $_POST['la_1_authorize_insurestays'];
	
	$num_of_agency_aff = $_POST['num_of_agency_aff'];
	$num_of_employment = $_POST['num_of_employment'];

	$licensing_appointments_step1_progress = $_POST['licensing_appointments_step1_progress'];

	$user_id = get_current_user_id();
	update_user_meta($user_id, 'licensing_appointments_step1_progress', $licensing_appointments_step1_progress);
	update_user_meta($user_id, 'la_1_security_number', $la_1_security_number);
	update_user_meta($user_id, 'la_1_assigned_npn', $la_1_npn);
	update_user_meta($user_id, 'la_1_first_name', $la_1_first_name);
	update_user_meta($user_id, 'la_1_middle_name', $la_1_middle_name);
	update_user_meta($user_id, 'la_1_last_name', $la_1_last_name);
	update_user_meta($user_id, 'la_1_birth_day', $la_1_birth_day);
	update_user_meta($user_id, 'la_1_home_address', $la_1_home_address);
	update_user_meta($user_id, 'la_1_city', $la_1_city);
	update_user_meta($user_id, 'la_1_state', $la_1_state);
	update_user_meta($user_id, 'la_1_zip_code', $la_1_zip_code);
	//update_user_meta($user_id, 'la_1_foreign_country', $la_1_foreign_country);
	update_user_meta($user_id, 'la_1_home_phone', $la_1_home_phone);
	update_user_meta($user_id, 'la_1_gender', $la_1_gender);
	update_user_meta($user_id, 'la_1_citizen_usa', $la_1_citizen_usa);
	update_user_meta($user_id, 'la_1_applicant_email', $la_1_applicant_email);
	update_user_meta($user_id, 'la_1_misdemeanor', $la_1_misdemeanor);
	update_user_meta($user_id, 'la_1_felony', $la_1_felony);
	update_user_meta($user_id, 'la_1_felony_conviction', $la_1_felony_conviction);
	update_user_meta($user_id, 'la_1_consent_granted', $la_1_consent_granted);
	update_user_meta($user_id, 'la_1_occupational_license', $la_1_occupational_license);
	update_user_meta($user_id, 'la_1_applicant_comment', $la_1_applicant_comment);

	if( isset($_FILES['la_1_applicant_file']) && is_array( $_FILES['la_1_applicant_file'] ) ) {
		$attachment_id = upload_user_file( $_FILES['la_1_applicant_file'] );
		//$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
		update_user_meta($user_id, 'la_1_applicant_file', $attachment_id);
	}
	
	update_user_meta($user_id, 'la_1_demand', $la_1_demand);
	update_user_meta($user_id, 'la_1_answer_submit', $la_1_answer_submit);
	update_user_meta($user_id, 'la_1_repayment_agreement', $la_1_repayment_agreement);
	update_user_meta($user_id, 'la_1_answer_jurisdiction', $la_1_answer_jurisdiction);
	update_user_meta($user_id, 'la_1_iduciary_duty', $la_1_iduciary_duty);
	update_user_meta($user_id, 'la_1_applicant2_comment', $la_1_applicant2_comment);

	if( isset($_FILES['la_1_applicant2_file']) && is_array( $_FILES['la_1_applicant2_file'] ) ) {
		$attachment_id = upload_user_file( $_FILES['la_1_applicant2_file'] );
		update_user_meta($user_id, 'la_1_applicant2_file', $attachment_id);
	}
	
	update_user_meta($user_id, 'la_1_alleged_misconduct', $la_1_alleged_misconduct);
	update_user_meta($user_id, 'la_1_applicant3_comment', $la_1_applicant3_comment);

	if( isset($_FILES['la_1_applicant3_file']) && is_array( $_FILES['la_1_applicant3_file'] ) ) {
		$attachment_id = upload_user_file( $_FILES['la_1_applicant3_file'] );
		update_user_meta($user_id, 'la_1_applicant3_file', $attachment_id);
	}
	
	update_user_meta($user_id, 'la_1_obligation_arrearage', $la_1_obligation_arrearage);
	update_user_meta($user_id, 'la_1_applicant4_comment', $la_1_applicant4_comment);

	if( isset($_FILES['la_1_applicant4_file']) && is_array( $_FILES['la_1_applicant4_file'] ) ) {
		$attachment_id = upload_user_file( $_FILES['la_1_applicant4_file'] );
		update_user_meta($user_id, 'la_1_applicant4_file', $attachment_id);
	}
	
	update_user_meta($user_id, 'la_1_warehouse', $la_1_warehouse);
	update_user_meta($user_id, 'la_1_associating_nipr_attachments', $la_1_NIPR_Attachments);
	update_user_meta($user_id, 'la_1_authorize_insurestays', $la_1_authorize_insurestays);

	update_user_meta($user_id, 'la_1_num_of_agency_aff', $num_of_agency_aff);
	update_user_meta($user_id, 'la_1_num_of_employment', $num_of_employment);
	
	for($i=1; $i<=$num_of_agency_aff; $i++) {
		$FEIN = $_POST['la_1_FEIN_'.$i];
		$NPN = $_POST['la_1_NPN_'.$i];
		$agency_name = $_POST['la_1_agency_name_'.$i];

		update_user_meta($user_id, 'la_1_FEIN_'.$i, $FEIN);
		update_user_meta($user_id, 'la_1_NPN_'.$i, $NPN);
		update_user_meta($user_id, 'la_1_agency_name_'.$i, $agency_name);
	}
	for($i=1; $i<=$num_of_employment; $i++) {
		$employee_name = $_POST['la_1_employee_'.$i.'_name'];
		$employee_city = $_POST['la_1_employee_'.$i.'_city'];
		$employee_state = $_POST['la_1_employee_'.$i.'_state'];
		//$employee_foreign_country = $_POST['la_1_employee_'.$i.'_foreign_country'];
		$employee_from_month = $_POST['la_1_employee_'.$i.'_from_month'];
		$employee_to_month = $_POST['la_1_employee_'.$i.'_to_month'];
		$employee_position_held = $_POST['la_1_employee_'.$i.'_position_held'];

		update_user_meta($user_id, 'la_1_employee_'.$i.'_name', $employee_name);
		update_user_meta($user_id, 'la_1_employee_'.$i.'_city', $employee_city);
		update_user_meta($user_id, 'la_1_employee_'.$i.'_state', $employee_state);
		//update_user_meta($user_id, 'la_1_employee_'.$i.'_foreign_country', $employee_foreign_country);
		update_user_meta($user_id, 'la_1_employee_'.$i.'_from_month', $employee_from_month);
		update_user_meta($user_id, 'la_1_employee_'.$i.'_to_month', $employee_to_month);
		update_user_meta($user_id, 'la_1_employee_'.$i.'_position_held', $employee_position_held);
	}
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Licensing & Appointments <span style="color: #3894D7;">1</span> of 5</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Social Security Number:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_security_number', true).'
						</td>
						<td colspan="2">
							<strong style="color:#333333;">If assigned, National Producer Number (NPN):</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_assigned_npn', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">First Name:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_first_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Middle Name:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_middle_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Last Name:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_last_name', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Date of Birth:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_birth_day', true).'
						</td>
						<td colspan="2">
							<strong style="color:#333333;">Residence/Home Address (Physical Street):</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_home_address', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">City:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_city', true).'
						</td>
						<td>
							<strong style="color:#333333;">State:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_state', true).'
						</td>
						<td>
							<strong style="color:#333333;">Zip Code:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_zip_code', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong style="color:#333333;">Home Phone Number:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_home_phone', true).'
						</td>
						<td>
							<strong style="color:#333333;">Gender:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_gender', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Are you a Citizen of the United States?</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_citizen_usa', true).'
						</td>
						<td colspan="2">
							<strong style="color:#333333;">Individual Applicant Email Address:</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_applicant_email', true).'
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="10" cellspacing="0">
					<tr>
						<td colspan="3">List your Insurance Agency Affiliations</td>
					</tr>';
			$num_of_agency_aff = get_user_meta($user_id, 'la_1_num_of_agency_aff', true);
			if($num_of_agency_aff) {
				for($i=1; $i<=$num_of_agency_aff; $i++) {
					$html .= '<tr>
								<td>
									<strong style="color:#333333;">FEIN:</strong>
									<br>
									'.get_user_meta($user_id, 'la_1_FEIN_'.$i, true).'
								</td>
								<td>
									<strong style="color:#333333;">NPN:</strong>
									<br>
									'.get_user_meta($user_id, 'la_1_NPN_'.$i, true).'
								</td>
								<td>
									<strong style="color:#333333;">Name of Agency:</strong>
									<br>
									'.get_user_meta($user_id, 'la_1_agency_name_'.$i, true).'
								</td>
							</tr>';
				}
			}
		$html .= '</table>
				<table border="0" cellpadding="10" cellspacing="0">
					<tr>
						<td colspan="3">Account for all time for the past five years. Give all employment experience starting with your current employer working back five years. Include full and part-time work, self-employment, military service, unemployment and full-time education.</td>
					</tr>';
		$num_of_employment = get_user_meta($user_id, 'la_1_num_of_employment', true);
		if($num_of_employment) {
			for($i=1; $i<=$num_of_employment; $i++) {
				$html .= '<tr>
							<td>
								<strong style="color:#333333;">Name:</strong>
								<br>
								'.get_user_meta($user_id, 'la_1_employee_'.$i.'_name', true).'
							</td>
							<td>
								<strong style="color:#333333;">City:</strong>
								<br>
								'.get_user_meta($user_id, 'la_1_employee_'.$i.'_city', true).'
							</td>
							<td>
								<strong style="color:#333333;">State:</strong>
								<br>
								'.get_user_meta($user_id, 'la_1_employee_'.$i.'_state', true).'
							</td>
						</tr>
						<tr>
							<td>
								<strong style="color:#333333;">From MM-YY:</strong>
								<br>
								'.get_user_meta($user_id, 'la_1_employee_'.$i.'_from_month', true).'
							</td>
							<td>
								<strong style="color:#333333;">To MM-YY:</strong>
								<br>
								'.get_user_meta($user_id, 'la_1_employee_'.$i.'_to_month', true).'
							</td>
							<td>
								<strong style="color:#333333;">Position Held:</strong>
								<br>
								'.get_user_meta($user_id, 'la_1_employee_'.$i.'_position_held', true).'
							</td>
						</tr>';
			}
		}
		$html .= '</table>';
		$html .= '<table border="0" cellpadding="10">
					<tr>
						<td colspan="2">
							<h3>The Applicant must read the following very carefully and answer every question.</h3>
							<strong>1a.</strong> Have you ever been convicted of a misdemeanor, had a judgment withheld or deferred, or are you currently charged with committing a misdemeanor?
							<br>
							'.get_user_meta($user_id, 'la_1_misdemeanor', true).'
							<br>
							<em>You may exclude the following misdemeanor convictions or pending misdemeanor charges: traffic citations, driving under the influence (DUI), driving while intoxicated (DWI), driving without a license, reckless driving, or driving with a suspended or revoked license. You may also exclude juvenile adjudications (offenses where you were adjudicated delinquent in a juvenile court)</em>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>1b.</strong> Have you ever been convicted of a felony, had a judgment withheld or deferred, or are you currently charged with committing a felony?
							<br>
							'.get_user_meta($user_id, 'la_1_felony', true).'
							<br>
							<em>You may exclude juvenile adjudications (offenses where you were adjudicated delinquent in a juvenile court)</em>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							If you have a felony conviction involving dishonesty or breach of trust, have you applied for written consent to engage in the business of insurance in your home state as required by 18 USC 1033?
							<br>
							'.get_user_meta($user_id, 'la_1_felony_conviction', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							If so, was consent granted? (Attach copy of 1033 consent approved by home state.)
							<br>
							'.get_user_meta($user_id, 'la_1_consent_granted', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>2.</strong> Have you ever been named or involved as a party in an administrative proceeding, including FINRA sanction or arbitration proceeding regarding any professional or occupational license or registration?
							<br>
							'.get_user_meta($user_id, 'la_1_occupational_license', true).'
							<br>
							<em>"Involved" means having a license censured, suspended, revoked, canceled, terminated; or, being assessed a fine, a cease and desist order, a prohibition order, a compliance order, placed on probation, sanctioned or surrendering a license to resolve an administrative action. "Involved" also means being named as a party to an administrative or arbitration proceeding, which is related to a professional or occupational license, or registration. "Involved" also means having a license, or registration application denied or the act of withdrawing an application to avoid a denial. INCLUDE any business so named because of your actions in your capacity as an owner, partner, officer or director, or member or manager of a Limited Liability Company. You may EXCLUDE terminations due solely to noncompliance with continuing education requirements or failure to pay a renewal fee.</em>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>If you answer yes, you must attach to this application:</h3>
							<ul>
                                <li>a written statement identifying the type of license and explaining the circumstances of each incident,</li>
                                <li>a copy of the Notice of Hearing or other document that states the charges and allegations, and</li>
                                <li>a copy of the official document, which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Comment</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_applicant_comment', true).'
						</td>
						<td>
							<strong style="color:#333333;">Upload File</strong>
							<br>';
                        	$applicant_file1 = get_user_meta($user_id, 'la_1_applicant_file', true);
							if($applicant_file1) {
								$filename1 = wp_get_attachment_url( $applicant_file1 );
								$html .= $filename1;
							}
			$html .= '</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>3.</strong> Has any demand been made or judgment rendered against you or any business in which you are or were an owner, partner, officer or director, or member or manager of a limited liability company, for overdue monies by an insurer, insured or producer, or have you ever been subject to a bankruptcy proceeding? Do not include personal bankruptcies, unless they involve funds held on behalf of others.
							<br>
							'.get_user_meta($user_id, 'la_1_demand', true).'
							<br>
							If you answer yes, submit a statement summarizing the details of the indebtedness and arrangements for repayment, and/or type and location of bankruptcy.
							<br>
							'.get_user_meta($user_id, 'la_1_answer_submit', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>4.</strong> Have you been notified by any jurisdiction to which you are applying of any delinquent tax obligation that is not the subject of a repayment agreement?
							<br>
							'.get_user_meta($user_id, 'la_1_repayment_agreement', true).'
							<br>
							If you answer yes, identify the jurisdiction(s):
							<br>
							'.get_user_meta($user_id, 'la_1_answer_jurisdiction', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>5.</strong> Are you currently a party to, or have you ever been found liable in, any lawsuit, arbitrations or mediation proceeding involving allegations of fraud, misappropriation or conversion of funds, misrepresentation or breach of fiduciary duty?
							<br>
							'.get_user_meta($user_id, 'la_1_iduciary_duty', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>If you answer yes, you must attach to this application:</h3>
							<ul>
                                <li>a written statement summarizing the details of each incident,</li>
                                <li>a copy of the Petition, Complaint or other document that commenced the lawsuit or arbitration, or mediation proceedings, and</li>
                                <li>a copy of the official documents, which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Comment</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_applicant2_comment', true).'
						</td>
						<td>
							<strong style="color:#333333;">Upload File</strong>
							<br>';
                        	$applicant_file2 = get_user_meta($user_id, 'la_1_applicant2_file', true);
							if($applicant_file2) {
								$filename2 = wp_get_attachment_url( $applicant_file2 );
								$html .= $filename2;
							}
			$html .= '</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>6.</strong> Have you or any business in which you are or were an owner, partner, officer or director, or member or manager of a limited liability company, ever had an insurance agency contract or any other business relationship with an insurance company terminated for any alleged misconduct?
							<br>
							'.get_user_meta($user_id, 'la_1_alleged_misconduct', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>If you answer yes, you must attach to this application:</h3>
							<ul>
                                <li>a written statement summarizing the details of each incident and explaining why you feel this incident should not prevent you from receiving an insurance license, and</li>
                                <li>copies of all relevant documents.</li>
                            </ul>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Comment</strong>
							<br>
							'.get_user_meta($user_id, 'la_1_applicant3_comment', true).'
						</td>
						<td>
							<strong style="color:#333333;">Upload File</strong>
							<br>';
							$applicant_file3 = get_user_meta($user_id, 'la_1_applicant3_file', true);
							if($applicant_file3) {
								$filename3 = wp_get_attachment_url( $applicant_file3 );
								$html .= $filename3;
							}
			$html .= '</td>
					</tr>
					
					<tr>
						<td colspan="2">
							<strong>7.</strong> Do you have a child support obligation in arrearage?
							<br>
							'.get_user_meta($user_id, 'la_1_obligation_arrearage', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>If you answer yes,</h3>
							<ul>
                                <li>by how many months are you in arrearage?</li>
                                <li>are you currently subject to and in compliance with any repayment agreement?</li>
                                <li>are you the subject of a child support related subpoena/warrant? (If you answered yes, provide documentation showing proof of current payments or an approved repayment plan from the appropriate state child support agency.)</li>
                            </ul>
						</td>
					</tr>
					<tr>
						<td>
							Comment
							<br>
							'.get_user_meta($user_id, 'la_1_applicant4_comment', true).'
						</td>
						<td>
							Upload File
							<br>';
							$applicant_file4 = get_user_meta($user_id, 'la_1_applicant4_file', true);
							if($applicant_file4) {
								$filename4 = wp_get_attachment_url( $applicant_file4 );
								$html .= $filename4;
							}
			$html .= '</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>8.</strong> In response to a "yes" answer to one or more of the Background Questions for this application, are you submitting document(s) to the NAIC/NIPR Attachments Warehouse?
							<br>
							'.get_user_meta($user_id, 'la_1_warehouse', true).'
							<br>
							<strong>If you answer yes:</strong><br>
							Will you be associating (linking) previously filed documents from the NAIC/NIPR Attachments Warehouse to this application?
							<br>
							'.get_user_meta($user_id, 'la_1_associating_nipr_attachments', true).'
							<br>
							<em>Note: If you have previously submitted documents to the Attachments Warehouse that are intended to be filed with this application, you must go to the Attachments Warehouse and associate (link) the supporting document(s) to this application based upon the particular background question number you have answered yes to on this application. You will receive information in a follow-up page at the end of the application process, providing a link to the Attachment Warehouse instructions.</em>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>9.</strong> I authorize InsureStays and/or other affiliated insurance agency of RentalGuardian to acquire these licenses on my behalf based on the information I have truthfully provided in this web form.
							<br>
							'.get_user_meta($user_id, 'la_1_authorize_insurestays', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Licensing-Appointments-1-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'licensing_appointments_1_pdf', $pdf_dir_url.'/Licensing-Appointments-1-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Licensing-Appointments-1-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "licensing-appointments-step2") ) {
	$la_2_incorporation_date = $_POST['la_2_incorporation_date'];
	$la_2_affiliated_financial_institution = $_POST['la_2_affiliated_financial_institution'];
	$la_2_business_address = $_POST['la_2_business_address'];
	$la_2_license_name = $_POST['la_2_license_name'];
	$la_2_license_ssn = $_POST['la_2_license_ssn'];
	//$la_2_license_npn = $_POST['la_2_license_npn'];
	//$la_2_entity_1_dob = $_POST['la_2_entity_1_dob'];
	//$la_2_entity_1_owner = $_POST['la_2_entity_1_owner'];
	/*$la_2_home_address = $_POST['la_2_home_address'];
	$la_2_city = $_POST['la_2_city'];
	$la_2_state = $_POST['la_2_state'];
	$la_2_zip_code = $_POST['la_2_zip_code'];
	$la_2_foreign_country = $_POST['la_2_foreign_country'];
	$la_2_home_phone = $_POST['la_2_home_phone'];
	$la_2_gender = $_POST['la_2_gender'];
	$la_2_citizen = $_POST['la_2_citizen'];
	$la_2_applicant_email = $_POST['la_2_applicant_email'];
	
	$la_2_FEIN_1 = $_POST['la_2_FEIN_1'];
	$la_2_NPN_1 = $_POST['la_2_NPN_1'];
	$la_2_agency_name_1 = $_POST['la_2_agency_name_1'];
	
	$la_2_employee_1_name = $_POST['la_2_employee_1_name'];
	$la_2_employee_1_city = $_POST['la_2_employee_1_city'];
	$la_2_employee_1_state = $_POST['la_2_employee_1_state'];
	$la_2_employee_1_foreign_country = $_POST['la_2_employee_1_foreign_country'];
	$la_2_employee_1_from_month = $_POST['la_2_employee_1_from_month'];
	$la_2_employee_1_to_month = $_POST['la_2_employee_1_to_month'];
	$la_2_employee_1_position_held = $_POST['la_2_employee_1_position_held'];*/
	
	$la_2_business_entity = $_POST['la_2_business_entity'];
	$la_2_business_entity_owner = $_POST['la_2_business_entity_owner'];
	$la_2_felony_conviction = $_POST['la_2_felony_conviction'];
	$la_2_consent_granted = $_POST['la_2_consent_granted'];
	$la_2_consent_granted_file = $_FILES['la_2_consent_granted_file'];
	$la_2_military_offense = $_POST['la_2_military_offense'];
	
	$la_2_applicant1_comment = $_POST['la_2_applicant1_comment'];
	$la_2_applicant1_file = $_FILES['la_2_applicant1_file'];
	
	$la_2_occupational_license = $_POST['la_2_occupational_license'];
	$la_2_applicant2_comment = $_POST['la_2_applicant2_comment'];
	$la_2_applicant2_file = $_FILES['la_2_applicant2_file'];
	
	$la_2_demand = $_POST['la_2_demand'];
	$la_2_arrangements_repayment = $_POST['la_2_arrangements_repayment'];
	$la_2_business_entity2 = $_POST['la_2_business_entity2'];
	$la_2_identify_jurisdiction = $_POST['la_2_identify_jurisdiction'];
	$la_2_business_entity3 = $_POST['la_2_business_entity3'];
	$la_2_applicant3_comment = $_POST['la_2_applicant3_comment'];
	$la_2_applicant3_file = $_FILES['la_2_applicant3_file'];
	
	$la_2_business_entity4 = $_POST['la_2_business_entity4'];
	$la_2_applicant4_comment = $_POST['la_2_applicant4_comment'];
	$la_2_applicant4_file = $_FILES['la_2_applicant4_file'];
	
	$la_2_warehouse = $_POST['la_2_warehouse'];
	$la_2_NIPR_Attachments = $_POST['la_2_NIPR_Attachments'];
	$la_1_authorize_insurestays = $_POST['la_1_authorize_insurestays'];
	
	//$num_of_agency_aff = $_POST['num_of_agency_aff'];
	//$num_of_employment = $_POST['num_of_employment'];
	$num_of_owners = $_POST['num_of_owners'];

	$licensing_appointments_step2_progress = $_POST['licensing_appointments_step2_progress'];

	$user_id = get_current_user_id();
	update_user_meta($user_id, 'licensing_appointments_step2_progress', $licensing_appointments_step2_progress);
	update_user_meta($user_id, 'la_2_incorporation_date', $la_2_incorporation_date);
	update_user_meta($user_id, 'la_2_affiliated_financial_institution', $la_2_affiliated_financial_institution);
	update_user_meta($user_id, 'la_2_business_address', $la_2_business_address);
	update_user_meta($user_id, 'la_2_license_name', $la_2_license_name);
	update_user_meta($user_id, 'la_2_license_ssn', $la_2_license_ssn);
	//update_user_meta($user_id, 'la_2_license_npn', $la_2_license_npn);
	//update_user_meta($user_id, 'la_2_entity_1_dob', $la_2_entity_1_dob);
	//update_user_meta($user_id, 'la_2_entity_1_owner', $la_2_entity_1_owner);
	/*update_user_meta($user_id, 'la_2_home_address', $la_2_home_address);
	update_user_meta($user_id, 'la_2_city', $la_2_city);
	update_user_meta($user_id, 'la_2_state', $la_2_state);
	update_user_meta($user_id, 'la_2_zip_code', $la_2_zip_code);
	update_user_meta($user_id, 'la_2_foreign_country', $la_2_foreign_country);
	update_user_meta($user_id, 'la_2_home_phone', $la_2_home_phone);
	update_user_meta($user_id, 'la_2_gender', $la_2_gender);
	update_user_meta($user_id, 'la_2_citizen_usa', $la_2_citizen);
	update_user_meta($user_id, 'la_2_applicant_email', $la_2_applicant_email);*/
	
	update_user_meta($user_id, 'la_2_business_entity', $la_2_business_entity);
	update_user_meta($user_id, 'la_2_business_entity_owner', $la_2_business_entity_owner);
	update_user_meta($user_id, 'la_2_felony_conviction', $la_2_felony_conviction);
	update_user_meta($user_id, 'la_2_consent_granted', $la_2_consent_granted);

	if( isset($_FILES['la_2_consent_granted_file']) && is_array( $_FILES['la_2_consent_granted_file'] ) ) {
		$attachment_id = upload_user_file( $_FILES['la_2_consent_granted_file'] );
		//$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
		update_user_meta($user_id, 'la_2_consent_granted_file', $attachment_id);
	}
	
	update_user_meta($user_id, 'la_2_military_offense', $la_2_military_offense);
	update_user_meta($user_id, 'la_2_applicant1_comment', $la_2_applicant1_comment);

	if( isset($_FILES['la_2_applicant1_file']) && is_array( $_FILES['la_2_applicant1_file'] ) ) {
		$attachment_id = upload_user_file( $_FILES['la_2_applicant1_file'] );
		update_user_meta($user_id, 'la_2_applicant1_file', $attachment_id);
	}
	
	update_user_meta($user_id, 'la_2_occupational_license', $la_2_occupational_license);
	update_user_meta($user_id, 'la_2_applicant2_comment', $la_2_applicant2_comment);

	if( isset($_FILES['la_2_applicant2_file']) && is_array( $_FILES['la_2_applicant2_file'] ) ) {
		$attachment_id = upload_user_file( $_FILES['la_2_applicant2_file'] );
		update_user_meta($user_id, 'la_2_applicant2_file', $attachment_id);
	}
	
	update_user_meta($user_id, 'la_2_demand', $la_2_demand);
	update_user_meta($user_id, 'la_2_arrangements_repayment', $la_2_arrangements_repayment);
	update_user_meta($user_id, 'la_2_business_entity2', $la_2_business_entity2);
	update_user_meta($user_id, 'la_2_identify_jurisdiction', $la_2_identify_jurisdiction);
	update_user_meta($user_id, 'la_2_business_entity3', $la_2_business_entity3);
	update_user_meta($user_id, 'la_2_applicant3_comment', $la_2_applicant3_comment);

	if( isset($_FILES['la_2_applicant3_file']) && is_array( $_FILES['la_2_applicant3_file'] ) ) {
		$attachment_id = upload_user_file( $_FILES['la_2_applicant3_file'] );
		update_user_meta($user_id, 'la_2_applicant3_file', $attachment_id);
	}
	
	update_user_meta($user_id, 'la_2_business_entity4', $la_2_business_entity4);
	update_user_meta($user_id, 'la_2_applicant4_comment', $la_2_applicant4_comment);

	if( isset($_FILES['la_2_applicant4_file']) && is_array( $_FILES['la_2_applicant4_file'] ) ) {
		$attachment_id = upload_user_file( $_FILES['la_2_applicant4_file'] );
		update_user_meta($user_id, 'la_2_applicant4_file', $attachment_id);
	}

	update_user_meta($user_id, 'la_2_warehouse', $la_2_warehouse);
	update_user_meta($user_id, 'la_2_NIPR_Attachments', $la_2_NIPR_Attachments);
	update_user_meta($user_id, 'la_2_authorize_insurestays', $la_1_authorize_insurestays);

	//update_user_meta($user_id, 'la_2_num_of_agency_aff', $num_of_agency_aff);
	//update_user_meta($user_id, 'la_2_num_of_employment', $num_of_employment);
	update_user_meta($user_id, 'la_2_num_of_owners', $num_of_owners);
	
	/*for($i=1; $i<=$num_of_agency_aff; $i++) {
		$FEIN = $_POST['la_2_FEIN_'.$i];
		$NPN = $_POST['la_2_NPN_'.$i];
		$agency_name = $_POST['la_2_agency_name_'.$i];

		update_user_meta($user_id, 'la_2_FEIN_'.$i, $FEIN);
		update_user_meta($user_id, 'la_2_NPN_'.$i, $NPN);
		update_user_meta($user_id, 'la_2_agency_name_'.$i, $agency_name);
	}
	for($i=1; $i<=$num_of_employment; $i++) {
		$employee_name = $_POST['la_2_employee_'.$i.'_name'];
		$employee_city = $_POST['la_2_employee_'.$i.'_city'];
		$employee_state = $_POST['la_2_employee_'.$i.'_state'];
		$employee_foreign_country = $_POST['la_2_employee_'.$i.'_foreign_country'];
		$employee_from_month = $_POST['la_2_employee_'.$i.'_from_month'];
		$employee_to_month = $_POST['la_2_employee_'.$i.'_to_month'];
		$employee_position_held = $_POST['la_2_employee_'.$i.'_position_held'];

		update_user_meta($user_id, 'la_2_employee_'.$i.'_name', $employee_name);
		update_user_meta($user_id, 'la_2_employee_'.$i.'_city', $employee_city);
		update_user_meta($user_id, 'la_2_employee_'.$i.'_state', $employee_state);
		update_user_meta($user_id, 'la_2_employee_'.$i.'_foreign_country', $employee_foreign_country);
		update_user_meta($user_id, 'la_2_employee_'.$i.'_from_month', $employee_from_month);
		update_user_meta($user_id, 'la_2_employee_'.$i.'_to_month', $employee_to_month);
		update_user_meta($user_id, 'la_2_employee_'.$i.'_position_held', $employee_position_held);
	}*/
	for($i=1; $i<=$num_of_owners; $i++) {
		$la_2_entity_name = $_POST['la_2_entity_'.$i.'_name'];
		$la_2_entity_title = $_POST['la_2_entity_'.$i.'_title'];
		$la_2_entity_ssn = $_POST['la_2_entity_'.$i.'_ssn'];
		$la_2_entity_interest_rate = $_POST['la_2_entity_'.$i.'_interest_rate'];
		$la_2_entity_birth_day = $_POST['la_2_entity_'.$i.'_birth_day'];
	
		update_user_meta($user_id, 'la_2_entity_'.$i.'_name', $la_2_entity_name);
		update_user_meta($user_id, 'la_2_entity_'.$i.'_title', $la_2_entity_title);
		update_user_meta($user_id, 'la_2_entity_'.$i.'_ssn', $la_2_entity_ssn);
		update_user_meta($user_id, 'la_2_entity_'.$i.'_interest_rate', $la_2_entity_interest_rate);
		update_user_meta($user_id, 'la_2_entity_'.$i.'_birth_day', $la_2_entity_birth_day);
	}
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Licensing & Appointments <span style="color: #3894D7;">2</span> of 5</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Incorporation/Formation Date:</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_incorporation_date', true).'
						</td>
						<td>
							<strong style="color:#333333;">Is the business entity affiliated with a financial institution/bank?</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_affiliated_financial_institution', true).'
						</td>
						<td>
							<strong style="color:#333333;">Business Address:</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_business_address', true).'
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="10">
					<tr>
						<td colspan="3">Identify at least one Designated/Responsible Licensed Producer responsible for the business entity\'s compliance with the insurance laws, rules and regulations of this state. (Typically same person as Step 1 of Licensing Form)</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong style="color:#333333;">Name:</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_license_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">SSN:</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_license_ssn', true).'
						</td>
					</tr>
					<tr>
						<td colspan="3">Identify all owners with 10% or more voting interest:</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Name:</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_entity_1_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Title:</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_entity_1_title', true).'
						</td>
						<td>
							<strong style="color:#333333;">SSN/Fein:</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_entity_1_ssn', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">% of ownership interest:</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_entity_1_interest_rate', true).'
						</td>
						<td>
							<strong style="color:#333333;">Date of Birth:</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_birth_day', true).'
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<table border="0" cellpadding="10">
					<tr>
						<td colspan="2">
							<br>
							<strong>1a.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, ever been convicted of a misdemeanor, had a judgment withheld or deferred or is the business entity or any owner, partner, officer or director of the business entity, or member or manager currently charged with, committing a misdemeanor?
							<br>
							'.get_user_meta($user_id, 'la_2_business_entity', true).'
							<br><br>
							<em>You may exclude the following misdemeanor convictions or pending misdemeanor charges: traffic citations, driving under the influence (DUI) or driving while intoxicated (DWI), driving without a license, reckless driving, or driving with a suspended or revoked license. You may also exclude juvenile adjudications (offenses where you were adjudicated delinquent in juvenile court.)</em>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>1b.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company ever been convicted of a felony, had judgment withheld or deferred, or is the business entity or any owner, partner, officer or director of the business entity or member or manager of a limited liability company currently charged with committing a felony?
							<br>
							'.get_user_meta($user_id, 'la_2_business_entity_owner', true).'
							<br><br>
							<em>You may exclude juvenile adjudications (offenses where you were adjudicated delinquent in a juvenile court.)</em>
							<br><br>
							If you have a felony conviction involving dishonesty or breach of trust, have you applied for written consent to engage in the business of insurance in your home state as required by 18 USC 1033?
							<br>
							'.get_user_meta($user_id, 'la_2_felony_conviction', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">If so, was consent granted? (Attach copy of 1033 consent approved by home state.)</td>
					</tr>
					<tr>
						<td style="padding-top:0;">
							'.get_user_meta($user_id, 'la_2_consent_granted', true).'
						</td>
						<td>';
						$consent_granted_file = get_user_meta($user_id, 'la_2_consent_granted_file', true);
						if($consent_granted_file) {
							$consent_granted_filename = wp_get_attachment_url( $consent_granted_file );
							$html .= '<br>'.$consent_granted_filename;
						}
			$html .= '</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>1c.</strong> Has the business entity or any owner, partner, officer or director of the business entity or member or manager of a limited liability company, ever been convicted of a military offense, had a judgment withheld or deferred, or is the business entity or any owner, partner, officer or director of the business entity or member or manager of a limited liability company, currently charged with committing a military offense?
							<br>
							'.get_user_meta($user_id, 'la_2_military_offense', true).'
							<br><br>
							<em><strong>NOTE:</strong> For Questions 1a, 1b, and 1c "Convicted" includes, but is not limited to, having been found guilty by verdict of a judge or jury, having entered a plea of guilty or nolo contendere or no contest, or having been given probation, a suspended sentence or a fine.</em>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>if you answer yes to any of these questions, you must attach to this application:</h3>
							<ul>
                                <li>a written statement identifying all parties involved (including their percentage of ownership, if any) and explaining the circumstances of each incident, </li>
                                <li>a copy of the charging document, </li>
                                <li>a copy of the official document which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Comment</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_applicant1_comment', true).'
						</td>
						<td>
							<strong style="color:#333333;">Upload File</strong>
							<br>';
                        	$applicant_file1 = get_user_meta($user_id, 'la_2_applicant1_file', true);
							if($applicant_file1) {
								$filename1 = wp_get_attachment_url( $applicant_file1 );
								$html .= $filename1;
							}
			$html .= '</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>2.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or manager or member of a limited liability company, ever been named or involved as a party in an administrative proceeding, including a FINRA sanction or arbitration proceeding regarding any professional or occupational license, or registration?
							<br>
							'.get_user_meta($user_id, 'la_2_occupational_license', true).'
							<br><br>
							<em>"Involved" means having a license censured, suspended, revoked, canceled, terminated; or, being assessed a fine, a cease and desist order, a prohibition order, a compliance order, placed on probation, sanctioned or surrendering a license to resolve an administrative action. "Involved" also means being named as a party to an administrative or arbitration proceeding, which is related to a professional or occupational license or registration. "Involved" also means having a license application denied or the act of withdrawing an application to avoid a denial. You may EXCLUDE terminations due solely to noncompliance with continuing education requirements or failure to pay a renewal fee.</em>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>If you answer yes, you must attach to this application:</h3>
							<ul>
                                <li>a written statement identifying the type of license, all parties involved (including their percentage of ownership, if any) and explaining the circumstances of each incident,</li>
                                <li>a copy of the Notice of Hearing or other document that states the charges and allegations, and</li>
                                <li>a copy of the official document which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Comment</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_applicant2_comment', true).'
						</td>
						<td>
							<strong style="color:#333333;">Upload File</strong>
							<br>';
                        	$applicant_file2 = get_user_meta($user_id, 'la_2_applicant2_file', true);
							if($applicant_file2) {
								$filename2 = wp_get_attachment_url( $applicant_file2 );
								$html .= $filename2;
							}
			$html .= '</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>3.</strong> Has any demand been made or judgment rendered against the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, for overdue monies by an insurer, insured or producer, or have you ever been subject to a bankruptcy proceeding? Do not include personal bankruptcies, unless they involve funds held on behalf of others.
							<br>
							'.get_user_meta($user_id, 'la_2_demand', true).'
							<br><br>
							If you answer yes, submit a statement summarizing the details of the indebtedness and arrangements for repayment.
							<br>
							'.get_user_meta($user_id, 'la_2_arrangements_repayment', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>4.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, ever been notified by any jurisdiction to which you are applying of any delinquent tax obligation that is not the subject of a repayment agreement?
							<br>
							'.get_user_meta($user_id, 'la_2_business_entity2', true).'
							<br><br>
							If you answer yes, identify the jurisdiction(s):
							<br>
							'.get_user_meta($user_id, 'la_2_identify_jurisdiction', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>5.</strong> Is the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company, a party to, or ever been found liable in any lawsuit or arbitration proceeding involving allegations of fraud, misappropriation or conversion of funds, misrepresentation or breach of fiduciary duty?
							<br>
							'.get_user_meta($user_id, 'la_2_business_entity3', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>If you answer yes, you must attach to this application:</h3>
							<ul>
                                <li>a written statement summarizing the details of each incident,</li>
                                <li>a copy of the Petition, Complaint or other document that commenced the lawsuit arbitrations, or mediation proceedings and</li>
                                <li>a copy of the official documents which demonstrates the resolution of the charges or any final judgment.</li>
                            </ul>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Comment</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_applicant3_comment', true).'
						</td>
						<td>
							<strong style="color:#333333;">Upload File</strong>
							<br>';
                        	$applicant_file3 = get_user_meta($user_id, 'la_2_applicant3_file', true);
							if($applicant_file3) {
								$filename3 = wp_get_attachment_url( $applicant_file3 );
								$html .= $filename3;
							}
			$html .= '</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>6.</strong> Has the business entity or any owner, partner, officer or director of the business entity, or member or manager of a limited liability company ever had an insurance agency contract or any other business relationship with an insurance company terminated for any alleged misconduct?
							<br>
							'.get_user_meta($user_id, 'la_2_business_entity4', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>If you answer yes, you must attach to this application:</h3>
							<ul>
                                <li>a written statement summarizing the details of each incident and explaining why you feel this incident should not prevent you from receiving an insurance license, and</li>
                                <li>copies of all relevant documents.</li>
                            </ul>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Comment</strong>
							<br>
							'.get_user_meta($user_id, 'la_2_applicant4_comment', true).'
						</td>
						<td>
							<strong style="color:#333333;">Upload File</strong>
							<br>';
							$applicant_file4 = get_user_meta($user_id, 'la_2_applicant4_file', true);
							if($applicant_file4) {
								$filename4 = wp_get_attachment_url( $applicant_file4 );
								$html .= $filename4;
							}
			$html .= '</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>8.</strong> In response to a "yes" answer to one or more of the Background Questions for this application, are you submitting document(s) to the NAIC/NIPR Attachments Warehouse?
							<br>
							'.get_user_meta($user_id, 'la_2_warehouse', true).'
							<br>
							<strong>If you answer yes:</strong><br>
							Will you be associating (linking) previously filed documents from the NAIC/NIPR Attachments Warehouse to this application?
							<br>
							'.get_user_meta($user_id, 'la_2_NIPR_Attachments', true).'
							<br><br>
							<em><strong>Note:</strong> If you have previously submitted documents to the Attachments Warehouse that are intended to be filed with this application, you must go to the Attachments Warehouse and associate (link) the supporting document(s) to this application based upon the particular background question number you have answered yes to on this application. You will receive information in a follow-up page at the end of the application process, providing a link to the Attachment Warehouse instructions.</em>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong>9.</strong> I authorize InsureStays and/or other affiliated insurance agency of RentalGuardian to acquire these licenses on my behalf based on the information I have truthfully provided in this web form.
							<br>
							'.get_user_meta($user_id, 'la_2_authorize_insurestays', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Licensing-Appointments-2-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'licensing_appointments_2_pdf', $pdf_dir_url.'/Licensing-Appointments-2-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Licensing-Appointments-2-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "licensing-appointments-step5") ) {
	$licensed_person_first_name = $_POST['licensed_person_first_name'];
	$licensed_person_last_name = $_POST['licensed_person_last_name'];
	$client_office_address = $_POST['client_office_address'];
	$social_security_number  = $_POST['social_security_number'];
	$licensed_person_phone_number = $_POST['licensed_person_phone_number'];
	$licensed_person_email_address = $_POST['licensed_person_email_address'];
	$licensing_appointments_step5_progress = $_POST['licensing_appointments_step5_progress'];

	$user_id = get_current_user_id();
	update_user_meta($user_id, 'licensed_person_first_name', $licensed_person_first_name);
	update_user_meta($user_id, 'licensed_person_last_name', $licensed_person_last_name);
	update_user_meta($user_id, 'client_office_address', $client_office_address);
	update_user_meta($user_id, 'licensed_person_social_security_number', $social_security_number);
	update_user_meta($user_id, 'licensed_person_phone_number', $licensed_person_phone_number);
	update_user_meta($user_id, 'licensed_person_email_address', $licensed_person_email_address);
	update_user_meta($user_id, 'licensing_appointments_step5_progress', $licensing_appointments_step5_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Licensing & Appointments <span style="color: #3894D7;">5</span> of 5</h1>';
		$html .= '<table border="0" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Licensed Person at your company (please spell out complete name)</strong>
							<br>
							'.get_user_meta($user_id, 'licensed_person_first_name', true).' '.get_user_meta($user_id, 'licensed_person_last_name', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Client Office Address:</strong>
							<br>
							'.get_user_meta($user_id, 'client_office_address', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Social Security Number:</strong>
							<br>
							'.get_user_meta($user_id, 'licensed_person_social_security_number', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Licensed Person\'s Phone:</strong>
							<br>
							'.get_user_meta($user_id, 'licensed_person_phone_number', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Licensed Person\'s Email:</strong>
							<br>
							'.get_user_meta($user_id, 'licensed_person_email_address', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Licensing-Appointments-5-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'licensing_appointments_5_pdf', $pdf_dir_url.'/Licensing-Appointments-5-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Licensing-Appointments-5-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "licensing-appointments-step4") ) {
	//$sign_las4_docusign_form = $_POST['sign_las4_docusign_form'];
	$licensing_appointments_step4_progress = $_POST['licensing_appointments_step4_progress'];
	$individual_agent_info_first_name = $_POST['individual_agent_info_first_name'];
	$individual_agent_info_middle_name = $_POST['individual_agent_info_middle_name'];
	$individual_agent_info_last_name = $_POST['individual_agent_info_last_name'];
	$individual_agent_info_social_security = $_POST['individual_agent_info_social_security'];
	$individual_agent_info_birth_day = $_POST['individual_agent_info_birth_day'];
	$individual_agent_info_gender = $_POST['individual_agent_info_gender'];
	$individual_agent_info_home_address = $_POST['individual_agent_info_home_address'];
	$individual_agent_info_street = $_POST['individual_agent_info_street'];
	$individual_agent_info_city = $_POST['individual_agent_info_city'];
	$individual_agent_info_state = $_POST['individual_agent_info_state'];
	$individual_agent_info_zip = $_POST['individual_agent_info_zip'];
	$individual_agent_info_county = $_POST['individual_agent_info_county'];
	$individual_agent_info_home_phone = $_POST['individual_agent_info_home_phone'];
	$individual_agent_info_home_fax = $_POST['individual_agent_info_home_fax'];
	$individual_agent_info_email = $_POST['individual_agent_info_email'];
	$l4_agency_info_type = $_POST['l4_agency_info_type'];
	$l4_agency_info_name = $_POST['l4_agency_info_name'];
	$l4_agency_info_ein_number = $_POST['l4_agency_info_ein_number'];
	$l4_agency_info_street_address = $_POST['l4_agency_info_street_address'];
	$l4_agency_info_city = $_POST['l4_agency_info_city'];
	$l4_agency_info_state = $_POST['l4_agency_info_state'];
	$l4_agency_info_zip = $_POST['l4_agency_info_zip'];
	$l4_agency_info_county = $_POST['l4_agency_info_county'];
	$l4_agency_info_mailing_address = $_POST['l4_agency_info_mailing_address'];
	$l4_agency_info_mailing_city = $_POST['l4_agency_info_mailing_city'];
	$l4_agency_info_mailing_state = $_POST['l4_agency_info_mailing_state'];
	$l4_agency_info_mailing_zip = $_POST['l4_agency_info_mailing_zip'];
	$l4_agency_info_mailing_county = $_POST['l4_agency_info_mailing_county'];
	$l4_agency_info_phone = $_POST['l4_agency_info_phone'];
	$l4_agency_info_fax = $_POST['l4_agency_info_fax'];
	$l4_agency_info_email = $_POST['l4_agency_info_email'];
	$l4_agency_info_health_license = $_POST['l4_agency_info_health_license'];
	$l4_agency_questionnaire_a = $_POST['l4_agency_questionnaire_a'];
	$l4_agency_questionnaire_b = $_POST['l4_agency_questionnaire_b'];
	$l4_agency_questionnaire_c = $_POST['l4_agency_questionnaire_c'];
	$l4_agency_questionnaire_d = $_POST['l4_agency_questionnaire_d'];
	$l4_agency_sign_name = $_POST['l4_agency_sign_name'];
	$l4_agency_sign_date = $_POST['l4_agency_sign_date'];

	$user_id = get_current_user_id();
	//update_user_meta($user_id, 'sign_las4_docusign_form', $sign_las4_docusign_form);
	update_user_meta($user_id, 'licensing_appointments_step4_progress', $licensing_appointments_step4_progress);
	update_user_meta($user_id, 'individual_agent_info_first_name', $individual_agent_info_first_name);
	update_user_meta($user_id, 'individual_agent_info_middle_name', $individual_agent_info_middle_name);
	update_user_meta($user_id, 'individual_agent_info_last_name', $individual_agent_info_last_name);
	update_user_meta($user_id, 'individual_agent_info_social_security', $individual_agent_info_social_security);
	update_user_meta($user_id, 'individual_agent_info_birth_day', $individual_agent_info_birth_day);
	update_user_meta($user_id, 'individual_agent_info_gender', $individual_agent_info_gender);
	update_user_meta($user_id, 'individual_agent_info_home_address', $individual_agent_info_home_address);
	update_user_meta($user_id, 'individual_agent_info_street', $individual_agent_info_street);
	update_user_meta($user_id, 'individual_agent_info_city', $individual_agent_info_city);
	update_user_meta($user_id, 'individual_agent_info_state', $individual_agent_info_state);
	update_user_meta($user_id, 'individual_agent_info_zip', $individual_agent_info_zip);
	update_user_meta($user_id, 'individual_agent_info_county', $individual_agent_info_county);
	update_user_meta($user_id, 'individual_agent_info_home_phone', $individual_agent_info_home_phone);
	update_user_meta($user_id, 'individual_agent_info_home_fax', $individual_agent_info_home_fax);
	update_user_meta($user_id, 'individual_agent_info_email', $individual_agent_info_email);
	update_user_meta($user_id, 'l4_agency_info_type', $l4_agency_info_type);
	update_user_meta($user_id, 'l4_agency_info_name', $l4_agency_info_name);
	update_user_meta($user_id, 'l4_agency_info_ein_number', $l4_agency_info_ein_number);
	update_user_meta($user_id, 'l4_agency_info_street_address', $l4_agency_info_street_address);
	update_user_meta($user_id, 'l4_agency_info_city', $l4_agency_info_city);
	update_user_meta($user_id, 'l4_agency_info_state', $l4_agency_info_state);
	update_user_meta($user_id, 'l4_agency_info_zip', $l4_agency_info_zip);
	update_user_meta($user_id, 'l4_agency_info_county', $l4_agency_info_county);
	update_user_meta($user_id, 'l4_agency_info_mailing_address', $l4_agency_info_mailing_address);
	update_user_meta($user_id, 'l4_agency_info_mailing_city', $l4_agency_info_mailing_city);
	update_user_meta($user_id, 'l4_agency_info_mailing_state', $l4_agency_info_mailing_state);
	update_user_meta($user_id, 'l4_agency_info_mailing_zip', $l4_agency_info_mailing_zip);
	update_user_meta($user_id, 'l4_agency_info_mailing_county', $l4_agency_info_mailing_county);
	update_user_meta($user_id, 'l4_agency_info_phone', $l4_agency_info_phone);
	update_user_meta($user_id, 'l4_agency_info_fax', $l4_agency_info_fax);
	update_user_meta($user_id, 'l4_agency_info_email', $l4_agency_info_email);
	update_user_meta($user_id, 'l4_agency_info_health_license', $l4_agency_info_health_license);
	update_user_meta($user_id, 'l4_agency_questionnaire_a', $l4_agency_questionnaire_a);
	update_user_meta($user_id, 'l4_agency_questionnaire_b', $l4_agency_questionnaire_b);
	update_user_meta($user_id, 'l4_agency_questionnaire_c', $l4_agency_questionnaire_c);
	update_user_meta($user_id, 'l4_agency_questionnaire_d', $l4_agency_questionnaire_d);
	update_user_meta($user_id, 'l4_agency_sign_name', $l4_agency_sign_name);
	update_user_meta($user_id, 'l4_agency_sign_date', $l4_agency_sign_date);
	if( isset($_POST['sig_data']) ) {
		$sig_data = $_POST['sig_data'];
		if($sig_data) {
			$signature = create_custom_signature($sig_data);
			update_user_meta($user_id, 'l4_agency_signature', $signature);
		}
	} else {
		delete_user_meta($user_id, 'l4_agency_signature');
	}

	update_user_meta($user_id, 'licensing_appointments_step4_progress', $licensing_appointments_step4_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Licensing & Appointments <span style="color: #3894D7;">4</span> of 5</h1>';
		$html .= '<table border="0" cellpadding="10" cellspacing="0">
					<tr>
						<td colspan="3"><h4>SECTION I – INDIVIDUAL AGENT INFORMATION</h4></td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">First Name:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_first_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Middle Name:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_middle_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Last Name:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_last_name', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Social Security Number:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_social_security', true).'
						</td>
						<td>
							<strong style="color:#333333;">Date of Birth:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_birth_day', true).'
						</td>
						<td>
							<strong style="color:#333333;">Gender:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_gender', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Home Address:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_home_address', true).'
						</td>
						<td>
							<strong style="color:#333333;">Street:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_street', true).'
						</td>
						<td>
							<strong style="color:#333333;">City:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_city', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">State:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_state', true).'
						</td>
						<td>
							<strong style="color:#333333;">ZIP:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_zip', true).'
						</td>
						<td>
							<strong style="color:#333333;">County:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_county', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Home Phone Number:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_home_phone', true).'
						</td>
						<td>
							<strong style="color:#333333;">Home Phone Number:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_home_fax', true).'
						</td>
						<td>
							<strong style="color:#333333;">Email Address:</strong>
							<br>
							'.get_user_meta($user_id, 'individual_agent_info_email', true).'
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<h4>SECTION II – AGENCY INFORMATION</h4>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">The Agency is a:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_type', true).'
						</td>
						<td>
							<strong style="color:#333333;">Business/Agency Name:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">EIN Number (For Agency Pay):</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_ein_number', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Agency Street Address:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_street_address', true).'
						</td>
						<td>
							<strong style="color:#333333;">City:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_city', true).'
						</td>
						<td>
							<strong style="color:#333333;">State:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_state', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">ZIP:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_zip', true).'
						</td>
						<td>
							<strong style="color:#333333;">County:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_county', true).'
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Agency Mailing Address:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_mailing_address', true).'
						</td>
						<td>
							<strong style="color:#333333;">City:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_mailing_city', true).'
						</td>
						<td>
							<strong style="color:#333333;">State:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_mailing_state', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">ZIP:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_mailing_zip', true).'
						</td>
						<td>
							<strong style="color:#333333;">County:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_mailing_county', true).'
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Agency Phone Number:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_phone', true).'
						</td>
						<td>
							<strong style="color:#333333;">Agency Fax Number:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_fax', true).'
						</td>
						<td>
							<strong style="color:#333333;">Agency Email Address:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_email', true).'
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<strong style="color:#333333;">State(s) in which to be appointed. Please attach copy(ies) of the current health license(s):</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_info_health_license', true).'
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<h4>SECTION III – BROKER/AGENCY QUESTIONNAIRE</h4>
							<!--<strong>A letter of explanation must be attached on any "Yes" answer to the following questions.</strong>-->
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="10">
					<tr>
						<td>
							<strong style="color:#333333;">1. Have you ever been convicted of any criminal activity involving dishonesty or a breach of trust?</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_questionnaire_a', true).'
						</td>
						<td>
							<strong style="color:#333333;">2. Have you ever been convicted or are currently under indictment for any criminal felony?</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_questionnaire_b', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">3. Have you ever had a license or an appointment cancelled by an insurer for reasons other than low production?</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_questionnaire_c', true).'
						</td>
						<td>
							<strong style="color:#333333;">4. Have you ever been suspended, disqualified or disciplined as a member of any profession?</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_questionnaire_d', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<p>I hereby authorize Nationwide and its representatives to make an independent investigation of my background, references, character, past employment, education, and criminal or police record, including those mandated by both public and private organizations and all public records for the purpose of confirming the information contained on this form and all other obtained information which may be material to my qualifications for licensing and/or appointment.</p>
							<p>I release Nationwide, its representatives, and any other person or entity, which provides information pursuant to this authorization, from any and all liabilities, claims or lawsuits in regards to the information obtained from any and all of the above referenced sources used.</p>
						</td>
					</tr>
				</table>';
				
		$html .= '<br><br><table border="0" cellspacing="0">
					<tr>
						<td colspan="3"><strong>I certify that to the best of my knowledge and belief, the above information is correct and complete.</strong></td>
					</tr>
					<tr>
						<td style="text-align:center;">
							<br><br>
							<strong style="color:#333333;">Print Name:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_sign_name', true).'
						</td>
						<td style="text-align:center;">
							<br><br>
							<strong style="color:#333333;">Signature:</strong>
							<br>
							<img src="'.get_user_meta($user_id, 'l4_agency_signature', true).'" style="width:240px;" />
						</td>
						<td style="text-align:center;">
							<br><br>
							<strong style="color:#333333;">Date:</strong>
							<br>
							'.get_user_meta($user_id, 'l4_agency_sign_date', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Licensing-Appointments-4-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'licensing_appointments_4_pdf', $pdf_dir_url.'/Licensing-Appointments-4-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Licensing-Appointments-4-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "licensing-appointments-step3") ) {
	//$sign_las3_docusign_form = $_POST['sign_las3_docusign_form'];
	$agency_legal_name = $_POST['agency_legal_name'];
	$agency_employer_id = $_POST['agency_employer_id'];
	$agency_producer_address = $_POST['agency_producer_address'];
	$agency_producer_city = $_POST['agency_producer_city'];
	$agency_producer_state = $_POST['agency_producer_state'];
	$agency_producer_zipcode = $_POST['agency_producer_zipcode'];
	$agency_producer_business_email = $_POST['agency_producer_business_email'];
	$agency_producer_license_number = $_POST['agency_producer_license_number'];
	$agency_producer_back_quise_a = $_POST['agency_producer_back_quise_a'];
	$agency_producer_back_quise_b = $_POST['agency_producer_back_quise_b'];
	$agency_producer_back_quise_c = $_POST['agency_producer_back_quise_c'];
	$designate_producer_first_name = $_POST['designate_producer_first_name'];
	$designate_producer_middle_name = $_POST['designate_producer_middle_name'];
	$designate_producer_last_name = $_POST['designate_producer_last_name'];
	$designate_producer_home_address = $_POST['designate_producer_home_address'];
	$designate_producer_city = $_POST['designate_producer_city'];
	$designate_producer_state = $_POST['designate_producer_state'];
	$designate_producer_zipcode = $_POST['designate_producer_zipcode'];
	$designate_producer_residence = $_POST['designate_producer_residence'];
	$designate_producer_birth_date = $_POST['designate_producer_birth_date'];
	$designate_producer_business_phone = $_POST['designate_producer_business_phone'];
	$designate_producer_title = $_POST['designate_producer_title'];
	$designate_producer_primary_email = $_POST['designate_producer_primary_email'];
	$designate_producer_social_security = $_POST['designate_producer_social_security'];
	$designate_producer_license_number = $_POST['designate_producer_license_number'];
	$designate_producer_back_quise_a = $_POST['designate_producer_back_quise_a'];
	$designate_producer_back_quise_b = $_POST['designate_producer_back_quise_b'];
	$designate_producer_back_quise_c = $_POST['designate_producer_back_quise_c'];
	$authorization_oklahoma = $_POST['authorization_oklahoma'];
	$l3_authorization_name = $_POST['l3_authorization_name'];
	$l3_authorization_date = $_POST['l3_authorization_date'];

	$licensing_appointments_step3_progress = $_POST['licensing_appointments_step3_progress'];

	$user_id = get_current_user_id();
	//update_user_meta($user_id, 'sign_las3_docusign_form', $sign_las3_docusign_form);
	update_user_meta($user_id, 'agency_legal_name', $agency_legal_name);
	update_user_meta($user_id, 'agency_employer_id', $agency_employer_id);
	update_user_meta($user_id, 'agency_producer_address', $agency_producer_address);
	update_user_meta($user_id, 'agency_producer_city', $agency_producer_city);
	update_user_meta($user_id, 'agency_producer_state', $agency_producer_state);
	update_user_meta($user_id, 'agency_producer_zipcode', $agency_producer_zipcode);
	update_user_meta($user_id, 'agency_producer_business_email', $agency_producer_business_email);
	update_user_meta($user_id, 'agency_producer_license_number', join(",",$agency_producer_license_number));
	update_user_meta($user_id, 'agency_producer_back_quise_a', $agency_producer_back_quise_a);
	update_user_meta($user_id, 'agency_producer_back_quise_b', $agency_producer_back_quise_b);
	update_user_meta($user_id, 'agency_producer_back_quise_c', $agency_producer_back_quise_c);
	update_user_meta($user_id, 'designate_producer_first_name', $designate_producer_first_name);
	update_user_meta($user_id, 'designate_producer_middle_name', $designate_producer_middle_name);
	update_user_meta($user_id, 'designate_producer_last_name', $designate_producer_last_name);
	update_user_meta($user_id, 'designate_producer_home_address', $designate_producer_home_address);
	update_user_meta($user_id, 'designate_producer_city', $designate_producer_city);
	update_user_meta($user_id, 'designate_producer_state', $designate_producer_state);
	update_user_meta($user_id, 'designate_producer_zipcode', $designate_producer_zipcode);
	update_user_meta($user_id, 'designate_producer_residence', $designate_producer_residence);
	update_user_meta($user_id, 'designate_producer_birth_date', $designate_producer_birth_date);
	update_user_meta($user_id, 'designate_producer_business_phone', $designate_producer_business_phone);
	update_user_meta($user_id, 'designate_producer_title', $designate_producer_title);
	update_user_meta($user_id, 'designate_producer_primary_email', $designate_producer_primary_email);
	update_user_meta($user_id, 'designate_producer_social_security', $designate_producer_social_security);
	update_user_meta($user_id, 'designate_producer_license_number', join(",",$designate_producer_license_number));
	update_user_meta($user_id, 'designate_producer_back_quise_a', $designate_producer_back_quise_a);
	update_user_meta($user_id, 'designate_producer_back_quise_b', $designate_producer_back_quise_b);
	update_user_meta($user_id, 'designate_producer_back_quise_c', $designate_producer_back_quise_c);
	update_user_meta($user_id, 'authorization_oklahoma', $authorization_oklahoma);
	update_user_meta($user_id, 'l3_authorization_name', $l3_authorization_name);
	update_user_meta($user_id, 'l3_authorization_date', $l3_authorization_date);
	if( isset($_POST['sig_data']) ) {
		$sig_data = $_POST['sig_data'];
		if($sig_data) {
			$signature = create_custom_signature($sig_data);
			update_user_meta($user_id, 'l3_authorization_signature', $signature);
		}
	} else {
		delete_user_meta($user_id, 'l3_authorization_signature');
	}

	update_user_meta($user_id, 'licensing_appointments_step3_progress', $licensing_appointments_step3_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Licensing & Appointments <span style="color: #3894D7;">3</span> of 5</h1>';
		$html .= '<table border="0" cellpadding="10" cellspacing="0">
					<tr>
						<td colspan="2">SECTION I: AGENCY PRODUCER INFORMATION</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Agency Legal Name:</strong>
							<br>
							'.get_user_meta($user_id, 'agency_legal_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Federal Employer Identification Number:</strong>
							<br>
							'.get_user_meta($user_id, 'agency_employer_id', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Address:</strong>
							<br>
							'.get_user_meta($user_id, 'agency_producer_address', true).'
						</td>
						<td>
							<strong style="color:#333333;">City:</strong>
							<br>
							'.get_user_meta($user_id, 'agency_producer_city', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">State:</strong>
							<br>
							'.get_user_meta($user_id, 'agency_producer_state', true).'
						</td>
						<td>
							<strong style="color:#333333;">Zip Code:</strong>
							<br>
							'.get_user_meta($user_id, 'agency_producer_zipcode', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Business Email:</strong>
							<br>
							'.get_user_meta($user_id, 'agency_producer_business_email', true).'
						</td>
						<td>
							<strong style="color:#333333;">Please indicate which states the Agency is licensed and include the license number for each:</strong>
							<br>
							'.get_user_meta($user_id, 'agency_producer_license_number', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>Agency Background Questions</h3>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Has the Agency or any owner, officer, director, or partner of the Agency ever been charged with a crime in a court of law in any State, Province, Territory, or country? If yes, provide details including date, charges, disposition, and a description of the circumstances.</strong>
							<br>
							'.get_user_meta($user_id, 'agency_producer_back_quise_a', true).'
						</td>
						<td>
							<strong style="color:#333333;">Has the Agency or any owner, officer, director, partner, or employee of the Agency ever been the subject of any administrative or disciplinary action by any insurance authority? If yes, provide full details.</strong>
							<br>
							'.get_user_meta($user_id, 'agency_producer_back_quise_b', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Has the Agency or any owner, officer, director, partner, or employee of the Agency ever had a contract or appointment to sell travel insurance or to act as a travel retailer terminated or cancelled for any reason other than low production? If yes, provide full details.</strong>
							<br>
							'.get_user_meta($user_id, 'agency_producer_back_quise_c', true).'
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>SECTION II: AGENCY APPLICATION ACKNOWLEDGEMENT AND CERTIFICATION</h3>
							<p>On behalf of the Agency, I hereby certify that all of the information submitted in this application and attachments is true and complete.</p>
                            <p>I acknowledge that I understand and that the Agency will comply with the insurance laws and regulations of the jurisdictions in which the Agency is transacting insurance business.</p>
                            <p>I acknowledge that Berkshire Hathaway Global Insurance Services, LLC and its affiliates may correspond with the Agency by fax, email, or other electronic means. I understand that by certifying this application, I am authorizing Berkshire Hathaway Global Insurance Services, LLC and its affiliates to communicate with the Agency in this manner.</p>
                            <p>I certify that I am authorized to make these acknowledgments and certifications on behalf of the Agency.</p>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>SECTION III: DESIGNATED RESPONSIBLE PRODUCER INFORMATION</h3>
							<p>Please provide your full legal name.</p>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">First Name:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_first_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Middle Name:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_middle_name', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Last Name:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_last_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Home Address:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_home_address', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">City:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_city', true).'
						</td>
						<td>
							<strong style="color:#333333;">State:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_state', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Zip Code:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_zipcode', true).'
						</td>
						<td>
							<strong style="color:#333333;">State of Residence:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_residence', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Date of Birth:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_birth_date', true).'
						</td>
						<td>
							<strong style="color:#333333;">Business Phone:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_business_phone', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Title:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_title', true).'
						</td>
						<td>
							<strong style="color:#333333;">Primary Email:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_primary_email', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Socila Security Number:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_social_security', true).'
						</td>
						<td>
							<strong style="color:#333333;">Please indicate which states the Designated Responsible Producer is licensed and include the license number for each:</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_license_number', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>Designated Responsible Producer Background Questions</h3>
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Have you ever been charged with a crime in a court of law in any State, Province, Territory, or country? If yes, provide details including date, charges, disposition, and a description of the surrounding circumstances.</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_back_quise_a', true).'
						</td>
						<td>
							<strong style="color:#333333;">Have you ever been the subject of any administrative or disciplinary action by any insurance authority? If yes, provide full details.</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_back_quise_b', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong style="color:#333333;">Have you ever had a contract or appointment to sell travel insurance or to act as a travel retailer terminated or cancelled for any reason other than low production? If yes, attach full details.</strong>
							<br>
							'.get_user_meta($user_id, 'designate_producer_back_quise_c', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>SECTION IV: APPLICATION ACKNOWLEDGEMENT AND CERTIFICATION</h3>
							<p>I hereby certify that all of the information submitted in this application and attachments is true, accurate, and complete.</p>
                            
                            <p>I acknowledge that I understand and will comply with the insurance laws and regulations of the jurisdictions in which I am transacting insurance business.</p>
                            <p>I acknowledge that Berkshire Hathaway Global Insurance Services, LLC and its affiliates may correspond with me by fax, e-mail, or other electronic means. I understand that by certifying this application, I am authorizing Berkshire Hathaway Global Insurance Services, LLC and its affiliates to communicate with me in this manner.</p>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>SECTION V: CONSUMER REPORT DISCLOSURE</h3>
							<p>As used in this Application, Company means Berkshire Hathaway Global Insurance Services, LLC and their affiliates, including the insurance carriers who underwrite the insurance products being sold by Berkshire Hathaway Global Insurance Services, LLC.</p>
							<p>In connection with determining your eligibility for an appointment as an insurance producer with Company, Company may from time to time obtain consumer reports and investigative consumer reports, including Producer Database Reports, about you from a consumer reporting agency. These reports may contain information on your criminal history, credit history, character, general reputation, personal characteristics, and mode of living.</p>
							<p>Any consumer reports or investigative consumer reports, other than a Producer Database Report, will be obtained through the following consumer reporting agency:</p>
							<h5><strong>Business Information Group, Inc.</strong></h5>
							P.O. Box 130, Southampton, PA 18966<br>
							800-260-1680<br>
							<p><a href="https://consumercare.bigreport.com" target="_blank">https://consumercare.bigreport.com</a>.</p>
							<p>Business Information Group Inc.\'s privacy practices with respect to the preparation and processing of consumer reports may be found at: <a href="https://consumercare.bigreport.com/privacy-policy.html">https://consumercare.bigreport.com/privacy-policy.html</a>.</p>
							<p>A copy of the Consumer Financial Protection Bureau\'s "Summary of Your Rights under the Fair Credit Reporting Act" is attached as a part of this application.</p>
							
							<h3><strong>SECTION V: CONSUMER REPORT DISCLOSURE</strong></h3>
							<p><strong>CALIFORNIA</strong>: You may view the file maintained on you by the consumer reporting agency during normal business hours and on reasonable notice. You may obtain a copy of this file, upon submitting proper identification and paying the costs of duplication services, by appearing at the consumer reporting agency\'s offices in person, during normal business hours and on reasonable notice, or by mail. You may also receive a summary of the file by telephone. If you appear in person, you may be accompanied by one other person, provided that person furnishes proper identification.</p>
							<p><strong>MAINE</strong>: You have the right, upon request, to be informed of whether an investigative consumer report was requested, and if one was requested, the name and address of the consumer reporting agency furnishing the report. You may request and receive from the Company, within five business days of our receipt of your request, the name, address and telephone number of the nearest unit designated to handle inquiries for the consumer reporting agency issuing an investigative consumer report concerning you. You also have the right, under Maine law, to request and promptly receive from all such consumer reporting agencies copies of any such investigative consumer reports.</p>
							<p><strong>MASSACHUSETTS</strong>: You have the right, upon request, to be informed of whether an investigative consumer report was requested, and if one was requested, you have the right, upon written request, to a copy of that report.</p>
							<p><strong>NEW YORK</strong>: You have the right, upon written request, to be informed of whether or not a consumer report was requested. If a consumer report is requested, you will be provided with the name and address of the consumer reporting agency furnishing the report. You may inspect and receive a copy of the report by contacting that agency.</p>
							<p><strong>WASHINGTON</strong>: If the Company requests an investigative consumer report, you have the right upon written request made within a reasonable period of time after your receipt of this disclosure, to receive from the consumer reporting agency a complete and accurate disclosure of the nature and scope of the investigation requested by the Company. You also have the right to request from the consumer reporting agency a written summary of your rights and remedies under the Washington Fair Credit Reporting Act.</p>
							
							<h3><strong>SECTION VII: AUTHORIZATION AND CONSENT</strong></h3>
							<p>I have carefully read this Producer Appointment Application form, including all disclosures and authorizations and the attached copy of the Consumer Financial Protection Bureau\'s "Summary of Your Rights under the Fair Credit Reporting Act". I hereby authorize Company to obtain consumer reports and investigative consumer reports about me. I authorize (a) a consumer reporting agency to request information about me from any public or private information sources; (b) anyone to provide information about me to a
consumer reporting agency; (c) a consumer reporting agency to provide Company with one or more reports based on that information; and (d) Company to share those reports with others for legitimate business purposes related to my appointment. I further consent to Company obtaining such reports and information from time to time, as Company, in its sole discretion, deems necessary. This is a continuing authorization and consent that, to the extent permitted by law, will apply for so long as I am applying for an appointment with Company or hold an appointment for Company.</p>
							<p><strong>CALIFORNIA*, MINNESOTA OR OKLAHOMA APPLICANTS:</strong><br>
							'.get_user_meta($user_id, 'authorization_oklahoma', true).'</p>
						</td>
					</tr>
				</table>';
				
		$html .= '<br><br><table border="0" cellspacing="0">
					<tr>
						<td style="text-align:center;">
							<strong style="color:#333333;">NAME & TITLE:</strong>
							<br>
							'.get_user_meta($user_id, 'l3_authorization_name', true).'
						</td>
						<td style="text-align:center;">
							<strong style="color:#333333;">APPLICANT/ AUTHORIZED SIGNATURE:</strong>
							<br>
							<img src="'.get_user_meta($user_id, 'l3_authorization_signature', true).'" style="width:240px;" />
						</td>
						<td style="text-align:center;">
							<strong style="color:#333333;">Date:</strong>
							<br>
							'.get_user_meta($user_id, 'l3_authorization_date', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Licensing-Appointments-3-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'licensing_appointments_3_pdf', $pdf_dir_url.'/Licensing-Appointments-3-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Licensing-Appointments-3-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "liability-protection") ) {
	//$sign_liability_docusign_form = $_POST['sign_liability_docusign_form'];
	$liability_protection_progress = $_POST['liability_protection_progress'];

	$user_id = get_current_user_id();
	//update_user_meta($user_id, 'sign_liability_docusign_form', $sign_liability_docusign_form);
	if( isset($_POST['liability_following_box']) ) {
		$skip_liability_form = $_POST['liability_following_box'];
		update_user_meta($user_id, 'skip_liability_form', $skip_liability_form);
	} else {
		delete_user_meta($user_id, 'skip_liability_form');
	}
	if( isset($_POST['liability_insurance_program']) ) {
		$liability_insurance_program = $_POST['liability_insurance_program'];
		update_user_meta($user_id, 'liability_insurance_program', $liability_insurance_program);
	} else {
		delete_user_meta($user_id, 'liability_insurance_program');
	}
	if( isset($_POST['liability_expiration_date']) ) {
		$liability_expiration_date = $_POST['liability_expiration_date'];
		update_user_meta($user_id, 'liability_expiration_date', $liability_expiration_date);
	} else {
		delete_user_meta($user_id, 'liability_expiration_date');
	}
	if( isset($_POST['liability_refused']) ) {
		$liability_refused = $_POST['liability_refused'];
		update_user_meta($user_id, 'liability_refused', $liability_refused);
	} else {
		delete_user_meta($user_id, 'liability_refused');
	}
	if( isset($_POST['liability_insured_losses']) ) {
		$liability_insured_losses = $_POST['liability_insured_losses'];
		update_user_meta($user_id, 'liability_insured_losses', $liability_insured_losses);
	} else {
		delete_user_meta($user_id, 'liability_insured_losses');
	}
	if( isset($_POST['liability_insured_details']) ) {
		$liability_insured_details = $_POST['liability_insured_details'];
		update_user_meta($user_id, 'liability_insured_details', $liability_insured_details);
	} else {
		delete_user_meta($user_id, 'liability_insured_details');
	}
	if( isset($_POST['liability_printed_name']) ) {
		$liability_printed_name = $_POST['liability_printed_name'];
		update_user_meta($user_id, 'liability_printed_name', $liability_printed_name);
	} else {
		delete_user_meta($user_id, 'liability_printed_name');
	}
	if( isset($_POST['liability_date']) ) {
		$liability_date = $_POST['liability_date'];
		update_user_meta($user_id, 'liability_date', $liability_date);
	} else {
		delete_user_meta($user_id, 'liability_date');
	}
	if( isset($_POST['sig_data']) ) {
		$sig_data = $_POST['sig_data'];
		if($sig_data) {
			$signature = create_custom_signature($sig_data);
			update_user_meta($user_id, 'liability_signature', $signature);
		}
	} else {
		delete_user_meta($user_id, 'liability_signature');
	}

	update_user_meta($user_id, 'liability_protection_progress', $liability_protection_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Liability & Real Property Protection</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Please provide the following details of any existing Tenant Damage or Owner Liability Insurance Program:</strong>
							<br>
							'.get_user_meta($user_id, 'liability_insurance_program', true).'
						</td>
						<td>
							<strong style="color:#333333;">Insurer & Expiration Date:</strong>
							<br>
							'.get_user_meta($user_id, 'liability_expiration_date', true).'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<strong style="color:#333333;">Has any application for insurance on behalf your company or any of the present Directors/Partners/Principals or, to your knowledge on behalf of their predecessors in business ever been declined or has any such insurance ever been cancelled or renewal refused?</strong>
							<br>
							'.get_user_meta($user_id, 'liability_refused', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Have any insured losses which would be subject to this program been incurred by your company over the past 5 years?</strong>
							<br>
							'.get_user_meta($user_id, 'liability_insured_losses', true).'
						</td>
						<td>
							<strong style="color:#333333;">If you answered YES, to either of the two questions above, please provide details:</strong>
							<br>
							'.get_user_meta($user_id, 'liability_insured_details', true).'
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="10">
					<tr>
						<td colspan="3">
							<h3>APPLICANT/ SUBSCRIBER ACKNOWLEDGEMENTS</h3>
							<p>The participating coverage-provider(s)/insurer(s) rely upon the representations, declarations, and statements in this Application for coverage approval decisions. All such representations, declarations and statements attached to this Application are the basis for coverage offered, if any. The Applicant agrees that the representations, declarations, and statements made in this Application shall be the basis of underwriting and/or coverage approval and that should coverage/a policy be issued, the Application will be attached to and made a part the coverage/policy contract.</p>
							
							<p>All written statements and materials furnished to agents of InsureStays and the coverage-provider(s)/insurer(s) submitted in conjunction with this Application are hereby incorporated by reference into this Application and made a part hereof. Nothing contained herein or incorporated herein by reference shall constitute notice of a claim or potential claim under any contract of coverage/insurance.</p>
							
							<p>This Application does not bind the Applicant to buy coverage, nor does it obligate the coverage-provider(s) /insurer(s) to approve coverage.</p>
							
							<p>The undersigned Applicant declares that the statements set forth in this Application are true. The Applicant further agrees that</p>
							<ul>
								<li>if the information provided in this Application changes between the date of this Application and the effective date of the coverage, the Applicant will immediately notify InsureStays and the provider/insurer(s) of such changes, and</li>
								<li>based on such changes, the provider/insurer(s) may withdraw or modify any outstanding quotations and/or authorizations or agreement to bind coverage.</li>
							</ul>
						</td>
					</tr>
				</table>';
				
		$html .= '<br><br><br><br><br><br><table border="0" cellspacing="0">
					<tr>
						<td style="text-align:center;">
							<strong style="color:#333333;">NAME & TITLE:</strong>
							<br>
							'.get_user_meta($user_id, 'liability_printed_name', true).'
						</td>
						<td style="text-align:center;">
							<strong style="color:#333333;">APPLICANT/ AUTHORIZED SIGNATURE:</strong>
							<br>
							<img src="'.get_user_meta($user_id, 'liability_signature', true).'" style="width:240px;" />
						</td>
						<td style="text-align:center;">
							<strong style="color:#333333;">Date:</strong>
							<br>
							'.get_user_meta($user_id, 'liability_date', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Liability-Protection-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'liability_protection_pdf', $pdf_dir_url.'/Liability-Protection-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Liability-Protection-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "property-protection") ) {
	//$sign_property_docusign_form = $_POST['sign_property_docusign_form'];
	$property_protection_progress = $_POST['property_protection_progress'];

	$user_id = get_current_user_id();
	//update_user_meta($user_id, 'sign_property_docusign_form', $sign_property_docusign_form);
	if( isset($_POST['property_following_box']) ) {
		$skip_property_form = $_POST['property_following_box'];
		update_user_meta($user_id, 'skip_property_form', $skip_property_form);
	} else {
		delete_user_meta($user_id, 'skip_property_form');
	}
	if( isset($_POST['professional_property_manager']) ) {
		$professional_property_manager = $_POST['professional_property_manager'];
		update_user_meta($user_id, 'professional_property_manager', $professional_property_manager);
	} else {
		delete_user_meta($user_id, 'professional_property_manager');
	}
	if( isset($_POST['percentage_properties']) ) {
		$percentage_properties = $_POST['percentage_properties'];
		update_user_meta($user_id, 'percentage_properties', $percentage_properties);
	} else {
		delete_user_meta($user_id, 'percentage_properties');
	}
	if( isset($_POST['travel_protection_type']) && !empty($_POST['travel_protection_type']) ) {
		$travel_protection_type = join(",", $_POST['travel_protection_type']);
		update_user_meta($user_id, 'travel_protection_type', $travel_protection_type);
	} else {
		delete_user_meta($user_id, 'travel_protection_type');
	}
	if( isset($_POST['travel_single_family']) ) {
		$travel_single_family = $_POST['travel_single_family'];
		update_user_meta($user_id, 'travel_single_family', $travel_single_family);
	} else {
		delete_user_meta($user_id, 'travel_single_family');
	}
	if( isset($_POST['travel_condominium']) ) {
		$travel_condominium = $_POST['travel_condominium'];
		update_user_meta($user_id, 'travel_condominium', $travel_condominium);
	} else {
		delete_user_meta($user_id, 'travel_condominium');
	}
	if( isset($_POST['travel_apartment']) ) {
		$travel_apartment = $_POST['travel_apartment'];
		update_user_meta($user_id, 'travel_apartment', $travel_apartment);
	} else {
		delete_user_meta($user_id, 'travel_apartment');
	}
	if( isset($_POST['travel_time_share']) ) {
		$travel_time_share = $_POST['travel_time_share'];
		update_user_meta($user_id, 'travel_time_share', $travel_time_share);
	} else {
		delete_user_meta($user_id, 'travel_time_share');
	}
	if( isset($_POST['travel_condo_tel']) ) {
		$travel_condo_tel = $_POST['travel_condo_tel'];
		update_user_meta($user_id, 'travel_condo_tel', $travel_condo_tel);
	} else {
		delete_user_meta($user_id, 'travel_condo_tel');
	}
	if( isset($_POST['travel_cabin']) ) {
		$travel_cabin = $_POST['travel_cabin'];
		update_user_meta($user_id, 'travel_cabin', $travel_cabin);
	} else {
		delete_user_meta($user_id, 'travel_cabin');
	}
	if( isset($_POST['travel_other']) ) {
		$travel_other = $_POST['travel_other'];
		update_user_meta($user_id, 'travel_other', $travel_other);
	} else {
		delete_user_meta($user_id, 'travel_other');
	}
	if( isset($_POST['travel_total']) ) {
		$travel_total = $_POST['travel_total'];
		update_user_meta($user_id, 'travel_total', $travel_total);
	} else {
		delete_user_meta($user_id, 'travel_total');
	}
	if( isset($_POST['travel_provide_list']) ) {
		$travel_provide_list = $_POST['travel_provide_list'];
		update_user_meta($user_id, 'travel_provide_list', $travel_provide_list);
	} else {
		delete_user_meta($user_id, 'travel_provide_list');
	}
	if( isset($_POST['travel_provide_list_countries']) ) {
		$travel_provide_list_countries = $_POST['travel_provide_list_countries'];
		update_user_meta($user_id, 'travel_provide_list_countries', $travel_provide_list_countries);
	} else {
		delete_user_meta($user_id, 'travel_provide_list_countries');
	}
	if( isset($_POST['travel_units_for_rent']) ) {
		$travel_units_for_rent = $_POST['travel_units_for_rent'];
		update_user_meta($user_id, 'travel_units_for_rent', $travel_units_for_rent);
	} else {
		delete_user_meta($user_id, 'travel_units_for_rent');
	}
	if( isset($_POST['travel_units_for_rent_describe']) ) {
		$travel_units_for_rent_describe = $_POST['travel_units_for_rent_describe'];
		update_user_meta($user_id, 'travel_units_for_rent_describe', $travel_units_for_rent_describe);
	} else {
		delete_user_meta($user_id, 'travel_units_for_rent_describe');
	}
	if( isset($_POST['travel_total_bookings']) ) {
		$travel_total_bookings = $_POST['travel_total_bookings'];
		update_user_meta($user_id, 'travel_total_bookings', $travel_total_bookings);
	} else {
		delete_user_meta($user_id, 'travel_total_bookings');
	}
	if( isset($_POST['travel_average_length']) ) {
		$travel_average_length = $_POST['travel_average_length'];
		update_user_meta($user_id, 'travel_average_length', $travel_average_length);
	} else {
		delete_user_meta($user_id, 'travel_average_length');
	}
	if( isset($_POST['travel_booking_amount']) ) {
		$travel_booking_amount = $_POST['travel_booking_amount'];
		update_user_meta($user_id, 'travel_booking_amount', $travel_booking_amount);
	} else {
		delete_user_meta($user_id, 'travel_booking_amount');
	}
	if( isset($_POST['travel_property_program']) ) {
		$travel_property_program = $_POST['travel_property_program'];
		update_user_meta($user_id, 'travel_property_program', $travel_property_program);
	} else {
		delete_user_meta($user_id, 'travel_property_program');
	}
	if( isset($_POST['travel_agreement_guidance']) ) {
		$travel_agreement_guidance = $_POST['travel_agreement_guidance'];
		update_user_meta($user_id, 'travel_agreement_guidance', $travel_agreement_guidance);
	} else {
		delete_user_meta($user_id, 'travel_agreement_guidance');
	}
	if( isset($_POST['travel_booking_occupancy']) ) {
		$travel_booking_occupancy = $_POST['travel_booking_occupancy'];
		update_user_meta($user_id, 'travel_booking_occupancy', $travel_booking_occupancy);
	} else {
		delete_user_meta($user_id, 'travel_booking_occupancy');
	}
	if( isset($_POST['travel_guest_verification']) ) {
		$travel_guest_verification = $_POST['travel_guest_verification'];
		update_user_meta($user_id, 'travel_guest_verification', $travel_guest_verification);
	} else {
		delete_user_meta($user_id, 'travel_guest_verification');
	}
	if( isset($_POST['travel_approximate_number1']) ) {
		$travel_approximate_number1 = $_POST['travel_approximate_number1'];
		update_user_meta($user_id, 'travel_approximate_number1', $travel_approximate_number1);
	} else {
		delete_user_meta($user_id, 'travel_approximate_number1');
	}
	if( isset($_POST['travel_approximate_number2']) ) {
		$travel_approximate_number2 = $_POST['travel_approximate_number2'];
		update_user_meta($user_id, 'travel_approximate_number2', $travel_approximate_number2);
	} else {
		delete_user_meta($user_id, 'travel_approximate_number2');
	}
	if( isset($_POST['travel_approximate_number3']) ) {
		$travel_approximate_number3 = $_POST['travel_approximate_number3'];
		update_user_meta($user_id, 'travel_approximate_number3', $travel_approximate_number3);
	} else {
		delete_user_meta($user_id, 'travel_approximate_number3');
	}
	if( isset($_POST['travel_amount_losses1']) ) {
		$travel_amount_losses1 = $_POST['travel_amount_losses1'];
		update_user_meta($user_id, 'travel_amount_losses1', $travel_amount_losses1);
	} else {
		delete_user_meta($user_id, 'travel_amount_losses1');
	}
	if( isset($_POST['travel_amount_losses2']) ) {
		$travel_amount_losses2 = $_POST['travel_amount_losses2'];
		update_user_meta($user_id, 'travel_amount_losses2', $travel_amount_losses2);
	} else {
		delete_user_meta($user_id, 'travel_amount_losses2');
	}
	if( isset($_POST['travel_amount_losses3']) ) {
		$travel_amount_losses3 = $_POST['travel_amount_losses3'];
		update_user_meta($user_id, 'travel_amount_losses3', $travel_amount_losses3);
	} else {
		delete_user_meta($user_id, 'travel_amount_losses3');
	}
	if( isset($_POST['travel_type_losses1']) ) {
		$travel_type_losses1 = $_POST['travel_type_losses1'];
		update_user_meta($user_id, 'travel_type_losses1', $travel_type_losses1);
	} else {
		delete_user_meta($user_id, 'travel_type_losses1');
	}
	if( isset($_POST['travel_type_losses2']) ) {
		$travel_type_losses2 = $_POST['travel_type_losses2'];
		update_user_meta($user_id, 'travel_type_losses2', $travel_type_losses2);
	} else {
		delete_user_meta($user_id, 'travel_type_losses2');
	}
	if( isset($_POST['travel_type_losses3']) ) {
		$travel_type_losses3 = $_POST['travel_type_losses3'];
		update_user_meta($user_id, 'travel_type_losses3', $travel_type_losses3);
	} else {
		delete_user_meta($user_id, 'travel_type_losses3');
	}
	if( isset($_POST['travel_printed_name']) ) {
		$travel_printed_name = $_POST['travel_printed_name'];
		update_user_meta($user_id, 'travel_printed_name', $travel_printed_name);
	} else {
		delete_user_meta($user_id, 'travel_printed_name');
	}
	if( isset($_POST['travel_date']) ) {
		$travel_date = $_POST['travel_date'];
		update_user_meta($user_id, 'travel_date', $travel_date);
	} else {
		delete_user_meta($user_id, 'travel_date');
	}
	if( isset($_POST['sig_data']) ) {
		$sig_data = $_POST['sig_data'];
		if($sig_data) {
			$signature = create_custom_signature($sig_data);
			update_user_meta($user_id, 'travel_signature', $signature);
		}
	} else {
		delete_user_meta($user_id, 'travel_signature');
	}

	update_user_meta($user_id, 'property_protection_progress', $property_protection_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Property Protection / Damage Protection</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Property Protection Type:</strong>
							<br>';
							$travel_protection_type = get_user_meta($user_id, 'travel_protection_type', true);
							if($travel_protection_type) {
								$travel_protection_types = explode(",", $travel_protection_type);
								if( is_array($travel_protection_types) ) {
									foreach($travel_protection_types as $protection_type) {
										$html .= $protection_type.'<br>';
									}
								}
							}
			$html .= '</td>
						<td>
							<strong style="color:#333333;">Are the units you rent directly managed by a full-time professional property manager?</strong>
							<br>
							'.get_user_meta($user_id, 'professional_property_manager', true).'
						</td>
						<td>
							<strong style="color:#333333;">What percentage of your properties are directly managed by you/ your company?</strong>
							<br>
							'.get_user_meta($user_id, 'percentage_properties', true).'
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="10">
					<tr>
						<td colspan="2">
							<strong style="color:#333333;">How many of each type of property/unit do you offer for rental:</strong>
						</td>
					</tr>
					<tr>
						<th>Type of Unit</th>
						<th>Number of Units</th>
					</tr>
					<tr>
						<td>Single Family</td>
						<td>'.get_user_meta($user_id, 'travel_single_family', true).'</td>
					</tr>
					<tr>
						<td>Condominium</td>
						<td>'.get_user_meta($user_id, 'travel_condominium', true).'</td>
					</tr>
					<tr>
						<td>Apartment</td>
						<td>'.get_user_meta($user_id, 'travel_apartment', true).'</td>
					</tr>
					<tr>
						<td>Time Share</td>
						<td>'.get_user_meta($user_id, 'travel_time_share', true).'</td>
					</tr>
					<tr>
						<td>Lodge/Condo-tel</td>
						<td>'.get_user_meta($user_id, 'travel_condo_tel', true).'</td>
					</tr>
					<tr>
						<td>Cabin</td>
						<td>'.get_user_meta($user_id, 'travel_cabin', true).'</td>
					</tr>
					<tr>
						<td>Other</td>
						<td>'.get_user_meta($user_id, 'travel_other', true).'</td>
					</tr>
					<tr>
						<td style="text-align:right;">TOTAL</td>
						<td>'.get_user_meta($user_id, 'travel_total', true).'</td>
					</tr>
				</table>
				<br><br><br>
				<table border="1" cellpadding="10">
					<tr>
						<td>
							<strong style="color:#333333;">For the US, provide list of states in which your properties are located:</strong>
							<br>
							'.get_user_meta($user_id, 'travel_provide_list', true).'
						</td>
						<td colspan="2">
							<strong style="color:#333333;">For international, provide list of countries in which your properties are located:</strong>
							<br>
							'.get_user_meta($user_id, 'travel_provide_list_countries', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">In the next 12 months, do you plan on increasing or decreasing the number of units for rent and/or the number of reservations you accept?</strong>
							<br>
							'.get_user_meta($user_id, 'travel_units_for_rent', true).'
						</td>
						<td>
							<strong style="color:#333333;">If "Yes", please describe:</strong>
							<br>
							'.get_user_meta($user_id, 'travel_units_for_rent_describe', true).'
						</td>
						<td>
							<strong style="color:#333333;">How many total bookings did you have during the past/prior 12 months?</strong>
							<br>
							'.get_user_meta($user_id, 'travel_total_bookings', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">What is your average length of stay?</strong>
							<br>
							'.get_user_meta($user_id, 'travel_average_length', true).'
						</td>
						<td>
							<strong style="color:#333333;">What is your average booking total amount?</strong>
							<br>
							'.get_user_meta($user_id, 'travel_booking_amount', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you plan to mandate the Property Protection Program with every booking?</strong>
							<br>
							'.get_user_meta($user_id, 'travel_property_program', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Does your rental/lease agreement include specific guidance and instruction for the guest(s) regarding guest responsibilities with respect to proper care for the rented unit?</strong>
							<br>
							'.get_user_meta($user_id, 'travel_agreement_guidance', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you inspect the unit immediately upon check-out for every booking-occupancy?</strong>
							<br>
							'.get_user_meta($user_id, 'travel_booking_occupancy', true).'
						</td>
						<td>
							<strong style="color:#333333;">Do you require guest-verification of damages?</strong>
							<br>
							'.get_user_meta($user_id, 'travel_guest_verification', true).'
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="10">
					<tr>
						<td colspan="4">
							<strong style="color:#333333;">What is the total amount of renter-caused damage, security deposit deductions, or claimed accidental damages to your rental properties each of the last three (3) years?</strong>
						</td>
					</tr>
					<tr>
						<th>Year of Loss</th>
						<th>Approximate Number of Claim Incidents</th>
						<th>Total Amount of Losses</th>
						<th>Nature of/ Type of Losses</th>
					</tr>
					<tr>
						<td>2017</td>
						<td>'.get_user_meta($user_id, 'travel_approximate_number1', true).'</td>
						<td>'.get_user_meta($user_id, 'travel_amount_losses1', true).'</td>
						<td>'.get_user_meta($user_id, 'travel_type_losses1', true).'</td>
					</tr>
					<tr>
						<td>2016</td>
						<td>'.get_user_meta($user_id, 'travel_approximate_number2', true).'</td>
						<td>'.get_user_meta($user_id, 'travel_amount_losses2', true).'</td>
						<td>'.get_user_meta($user_id, 'travel_type_losses2', true).'</td>
					</tr>
					<tr>
						<td>2015</td>
						<td>'.get_user_meta($user_id, 'travel_approximate_number3', true).'</td>
						<td>'.get_user_meta($user_id, 'travel_amount_losses3', true).'</td>
						<td>'.get_user_meta($user_id, 'travel_type_losses3', true).'</td>
					</tr>
				</table>
				<table border="0" cellpadding="10">
					<tr>
						<td colspan="3">
							<h3>APPLICANT/ SUBSCRIBER ACKNOWLEDGEMENTS</h3>
							<p>The participating coverage-provider(s)/insurer(s) rely upon the representations, declarations,  and statements in this Application for coverage approval decisions. All such representations, declarations and statements attached to this Application are the basis for coverage offered, if any. The Applicant agrees that the representations, declarations, and statements made in this Application shall be the basis of underwriting and coverage approval and that should coverage/a policy be issued, the Application may be attached to and made a part the coverage/policy contract.</p>
							
							<p>All written statements and materials furnished to agents of InsureStays and the coverage-provider(s)/insurer(s) submitted in conjunction with this Application are hereby incorporated by reference into this Application and made a part hereof. Nothing contained herein or incorporated herein by reference shall constitute notice of a claim or potential claim under any contract of coverage/insurance.</p>
							
							<p>This Application does not bind the Applicant to buy coverage, nor does it obligate the coverage-provider(s) /insurer(s) to approve coverage. </p>
							
							<p>The undersigned Applicant declares that the statements set forth in this Application are true. The Applicant further agrees that</p>
							<ul>
								<li>if the information provided in this Application changes between the date of this Application and the effective date of the coverage, the Applicant will immediately notify InsureStays and the provider/insurer(s) of such changes, and </li>
								<li>based on such changes, the provider/insurer(s) may withdraw or modify any outstanding quotations and/or authorizations or agreement to bind coverage.</li>
							</ul>
							<p>If and when coverage/a policy is issued, this Application is attached to and made a part of the coverage/policy; therefore, it is necessary that all questions be answered in detail. The Applicant hereby acknowledges that by signing below where indicated, that this signed statement will be attached to the coverage/policy.</p>
						</td>
					</tr>
				</table>';
				
				$html .= '<br><br><br><br><table border="0" cellspacing="0">
							<tr>
								<td style="text-align:center;">
									<strong style="color:#333333;">Printed Name & Title:</strong>
									<br>
									'.get_user_meta($user_id, 'travel_printed_name', true).'
								</td>
								<td style="text-align:center;">
									<strong style="color:#333333;">Authorized Signature:</strong>
									<br>
									<img src="'.get_user_meta($user_id, 'travel_signature', true).'" style="width:240px;" />
								</td>
								<td style="text-align:center;">
									<strong style="color:#333333;">Date:</strong>
									<br>
									'.get_user_meta($user_id, 'travel_date', true).'
								</td>
							</tr>
						</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Property-Protection-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'property_protection_pdf', $pdf_dir_url.'/Property-Protection-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Property-Protection-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "travel-protection") ) {
	//$sign_travel_docusign_form = $_POST['sign_travel_docusign_form'];
	$travel_protection_progress = $_POST['travel_protection_progress'];

	$user_id = get_current_user_id();
	//update_user_meta($user_id, 'sign_travel_docusign_form', $sign_travel_docusign_form);
	if( isset($_POST['travel_following_box']) ) {
		$skip_travel_form = $_POST['travel_following_box'];
		update_user_meta($user_id, 'skip_travel_form', $skip_travel_form);
	} else {
		delete_user_meta($user_id, 'skip_travel_form');
	}
	if( isset($_POST['property_protection_type']) && !empty($_POST['property_protection_type']) ) {
		$property_protection_type = join(",", $_POST['property_protection_type']);
		update_user_meta($user_id, 'property_protection_type', $property_protection_type);
	} else {
		delete_user_meta($user_id, 'property_protection_type');
	}
	if( isset($_POST['property_reservations']) ) {
		$property_reservations = $_POST['property_reservations'];
		update_user_meta($user_id, 'property_reservations', $property_reservations);
	} else {
		delete_user_meta($user_id, 'property_reservations');
	}
	if( isset($_POST['property_booking_cost']) ) {
		$property_booking_cost = $_POST['property_booking_cost'];
		update_user_meta($user_id, 'property_booking_cost', $property_booking_cost);
	} else {
		delete_user_meta($user_id, 'property_booking_cost');
	}
	if( isset($_POST['property_average_length']) ) {
		$property_average_length = $_POST['property_average_length'];
		update_user_meta($user_id, 'property_average_length', $property_average_length);
	} else {
		delete_user_meta($user_id, 'property_average_length');
	}
	if( isset($_POST['number_of_properties_booked1']) ) {
		$number_of_properties_booked1 = $_POST['number_of_properties_booked1'];
		update_user_meta($user_id, 'number_of_properties_booked1', $number_of_properties_booked1);
	} else {
		delete_user_meta($user_id, 'number_of_properties_booked1');
	}
	if( isset($_POST['number_of_properties_booked2']) ) {
		$number_of_properties_booked2 = $_POST['number_of_properties_booked2'];
		update_user_meta($user_id, 'number_of_properties_booked2', $number_of_properties_booked2);
	} else {
		delete_user_meta($user_id, 'number_of_properties_booked2');
	}
	if( isset($_POST['number_of_total_cancellations1']) ) {
		$number_of_total_cancellations1 = $_POST['number_of_total_cancellations1'];
		update_user_meta($user_id, 'number_of_total_cancellations1', $number_of_total_cancellations1);
	} else {
		delete_user_meta($user_id, 'number_of_total_cancellations1');
	}
	if( isset($_POST['number_of_total_cancellations2']) ) {
		$number_of_total_cancellations2 = $_POST['number_of_total_cancellations2'];
		update_user_meta($user_id, 'number_of_total_cancellations2', $number_of_total_cancellations2);
	} else {
		delete_user_meta($user_id, 'number_of_total_cancellations2');
	}
	if( isset($_POST['why_guest_cancels_their_booking']) ) {
		$why_guest_cancels_their_booking = $_POST['why_guest_cancels_their_booking'];
		update_user_meta($user_id, 'why_guest_cancels_their_booking', $why_guest_cancels_their_booking);
	} else {
		delete_user_meta($user_id, 'why_guest_cancels_their_booking');
	}
	if( isset($_POST['property_printed_name']) ) {
		$property_printed_name = $_POST['property_printed_name'];
		update_user_meta($user_id, 'property_printed_name', $property_printed_name);
	} else {
		delete_user_meta($user_id, 'property_printed_name');
	}
	if( isset($_POST['property_date']) ) {
		$property_date = $_POST['property_date'];
		update_user_meta($user_id, 'property_date', $property_date);
	} else {
		delete_user_meta($user_id, 'property_date');
	}
	if( isset($_POST['sig_data']) ) {
		$sig_data = $_POST['sig_data'];
		if($sig_data) {
			$signature = create_custom_signature($sig_data);
			update_user_meta($user_id, 'property_signature', $signature);
		}
	} else {
		delete_user_meta($user_id, 'property_signature');
	}

	update_user_meta($user_id, 'travel_protection_progress', $travel_protection_progress);

	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Travel Protection</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Travel Protection Type:</strong>
							<br>';
							$property_protection_type = get_user_meta($user_id, 'property_protection_type', true);
							if($property_protection_type) {
								$property_protection_types = explode(",", $property_protection_type);
								if( is_array($property_protection_types) ) {
									foreach($property_protection_types as $protection_type) {
										$html .= $protection_type.'<br>';
									}
								}
							}
			$html .= '</td>
						<td>
							<strong style="color:#333333;">How many reservations do you average per year?</strong>
							<br>
							'.get_user_meta($user_id, 'property_reservations', true).'
						</td>
						<td>
							<strong style="color:#333333;">What is your average booking cost?</strong>
							<br>
							'.get_user_meta($user_id, 'property_booking_cost', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">What is the average length of stay?</strong>
							<br>
							'.get_user_meta($user_id, 'property_average_length', true).'
						</td>
						<td>
							<strong style="color:#333333;">Please describe typical reasons why a guest cancels their booking:</strong>
							<br>
							'.get_user_meta($user_id, 'why_guest_cancels_their_booking', true).'
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<table border="0" cellpadding="10">
					<tr>
						<th>YEAR</th>
						<th>NUMBER OF PROPERTIES BOOKED</th>
						<th>NUMBER OF TOTAL CANCELLATIONS</th>
					</tr>
					<tr>
						<td>2017</td>
						<td>'.get_user_meta($user_id, 'number_of_properties_booked1', true).'</td>
						<td>'.get_user_meta($user_id, 'number_of_total_cancellations1', true).'</td>
					</tr>
					<tr>
						<td>2016</td>
						<td>'.get_user_meta($user_id, 'number_of_properties_booked2', true).'</td>
						<td>'.get_user_meta($user_id, 'number_of_total_cancellations2', true).'</td>
					</tr>
				</table>
				<table border="0" cellpadding="10">
					<tr>
						<td colspan="3">
							<h3>APPLICANT/ SUBSCRIBER ACKNOWLEDGEMENTS</h3>
							<p>The participating coverage-provider(s)/insurer(s) rely upon the representations, declarations, and statements in this Application for coverage approval decisions. All such representations, declarations and statements attached to this Application are the basis for coverage offered, if any. The Applicant agrees that the representations, declarations, and statements made in this Application shall be the basis of underwriting and/or coverage approval and that should coverage/a policy be issued, the Application will be attached to and made a part the coverage/policy contract.</p>
							
							<p>All written statements and materials furnished to agents of InsureStays and the coverage-provider(s)/insurer(s) submitted in conjunction with this Application are hereby incorporated by reference into this Application and made a part hereof. Nothing contained herein or incorporated herein by reference shall constitute notice of a claim or potential claim under any contract of coverage/insurance.</p>
							
							<p>This Application does not bind the Applicant to buy coverage, nor does it obligate the coverage-provider(s) /insurer(s) to approve coverage.</p>
							
							<p>The undersigned Applicant declares that the statements set forth in this Application are true. The Applicant further agrees that</p>
							<ul>
								<li>if the information provided in this Application changes between the date of this Application and the effective date of the coverage, the Applicant will immediately notify InsureStays and the provider/insurer(s) of such changes, and</li>
								<li>based on such changes, the provider/insurer(s) may withdraw or modify any outstanding quotations and/or authorizations or agreement to bind coverage.</li>
							</ul>
						</td>
					</tr>
				</table>';
				
		$html .= '<br><br><br><br><br><br><br><br><table border="0" cellspacing="0">
					<tr>
						<td style="text-align:center;">
							<strong style="color:#333333;">NAME & TITLE:</strong>
							<br>
							'.get_user_meta($user_id, 'property_printed_name', true).'
						</td>
						<td style="text-align:center;">
							<strong style="color:#333333;">APPLICANT/ AUTHORIZED SIGNATURE:</strong>
							<br>
							<img src="'.get_user_meta($user_id, 'property_signature', true).'" style="width:240px;" />
						</td>
						<td style="text-align:center;">
							<strong style="color:#333333;">Date:</strong>
							<br>
							'.get_user_meta($user_id, 'property_date', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Travel-Protection-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'travel_protection_pdf', $pdf_dir_url.'/Travel-Protection-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Travel-Protection-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "w9-form") ) {
	//$sign_w9_form_docusign_form = $_POST['sign_w9_form_docusign_form'];
	$w9_form_progress = $_POST['w9_form_progress'];

	$user_id = get_current_user_id();
	//update_user_meta($user_id, 'sign_w9_form_docusign_form', $sign_w9_form_docusign_form);
	if( isset($_POST['w9_following_box']) ) {
		$skip_w9_form = $_POST['w9_following_box'];
		update_user_meta($user_id, 'skip_w9_form', $skip_w9_form);
	} else {
		delete_user_meta($user_id, 'skip_w9_form');
	}

	if( isset($_POST['w9_income_tax']) ) {
		$w9_income_tax = $_POST['w9_income_tax'];
		update_user_meta($user_id, 'w9_income_tax', $w9_income_tax);
	} else {
		delete_user_meta($user_id, 'w9_income_tax');
	}
	if( isset($_POST['w9_business_name']) ) {
		$w9_business_name = $_POST['w9_business_name'];
		update_user_meta($user_id, 'w9_business_name', $w9_business_name);
	} else {
		delete_user_meta($user_id, 'w9_business_name');
	}
	if( isset($_POST['w9_federal_tax']) && !empty($_POST['w9_federal_tax']) ) {
		$w9_federal_tax = join(",", $_POST['w9_federal_tax']);
		update_user_meta($user_id, 'w9_federal_tax', $w9_federal_tax);
		/*foreach($_POST['w9_federal_tax'] as $check) {
		}*/
	} else {
		delete_user_meta($user_id, 'w9_federal_tax');
	}
	if( isset($_POST['w9_address_city_state']) ) {
		$w9_address_city_state = $_POST['w9_address_city_state'];
		update_user_meta($user_id, 'w9_address_city_state', $w9_address_city_state);
	} else {
		delete_user_meta($user_id, 'w9_address_city_state');
	}
	if( isset($_POST['w9_account_numbers']) ) {
		$w9_account_numbers = $_POST['w9_account_numbers'];
		update_user_meta($user_id, 'w9_account_numbers', $w9_account_numbers);
	} else {
		delete_user_meta($user_id, 'w9_account_numbers');
	}
	if( isset($_POST['w9_requesters_name']) ) {
		$w9_requesters_name = $_POST['w9_requesters_name'];
		update_user_meta($user_id, 'w9_requesters_name', $w9_requesters_name);
	} else {
		delete_user_meta($user_id, 'w9_requesters_name');
	}
	if( isset($_POST['w9_federal_id_number']) ) {
		$w9_federal_id_number = $_POST['w9_federal_id_number'];
		update_user_meta($user_id, 'w9_federal_id_number', $w9_federal_id_number);
	} else {
		delete_user_meta($user_id, 'w9_federal_id_number');
	}
	if( isset($_POST['w9_date']) ) {
		$w9_date = $_POST['w9_date'];
		update_user_meta($user_id, 'w9_date', $w9_date);
	} else {
		delete_user_meta($user_id, 'w9_date');
	}
	if( isset($_POST['sig_data']) ) {
		$sig_data = $_POST['sig_data'];
		if($sig_data) {
			$signature = create_custom_signature($sig_data);
			update_user_meta($user_id, 'w9_signature', $signature);
		}
	} else {
		delete_user_meta($user_id, 'w9_signature');
	}
	
	update_user_meta($user_id, 'w9_form_progress', $w9_form_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">W-9 Form</h1>';
		$html .= '<table border="1" cellpadding="10" cellspacing="0">
					<tr>
						<td>
							<strong style="color:#333333;">Provide your name as shown in your income tax return:</strong>
							<br>
							'.get_user_meta($user_id, 'w9_income_tax', true).'
						</td>
						<td>
							<strong style="color:#333333;">Your business name:</strong>
							<br>
							'.get_user_meta($user_id, 'w9_business_name', true).'
						</td>
						<td>
							<strong style="color:#333333;">Check the appropriate box for federal tax classification:</strong>
							<br>';
								$w9_federal_tax = get_user_meta($user_id, 'w9_federal_tax', true);
								if($w9_federal_tax) {
									$w9_checked_federal_taxes = explode(",", $w9_federal_tax);
									if( is_array($w9_checked_federal_taxes) ) {
										foreach($w9_checked_federal_taxes as $w9_federal_tax) {
											$html .= $w9_federal_tax.'<br>';
										}
									}
								}
			$html .= '</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Enter your address, city, state and ZIP code:</strong>
							<br>
							'.get_user_meta($user_id, 'w9_address_city_state', true).'
						</td>
						<td>
							<strong style="color:#333333;">List account numbers (optional):</strong>
							<br>
							'.get_user_meta($user_id, 'w9_account_numbers', true).'
						</td>
						<td>
							<strong style="color:#333333;">Specify requester\'s name and address (optional):</strong>
							<br>
							'.get_user_meta($user_id, 'w9_requesters_name', true).'
						</td>
					</tr>
					<tr>
						<td>
							<strong style="color:#333333;">Indicate your Federal / Tax ID Number:</strong>
							<br>
							'.get_user_meta($user_id, 'w9_federal_id_number', true).'
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
				
				$html .= '<br><br><br><br><table border="0" cellspacing="0">
							<tr>
								<td style="text-align:center;">
									<strong style="color:#333333;">Authorized Signature:</strong>
									<br>
									<img src="'.get_user_meta($user_id, 'w9_signature', true).'" style="width:240px;" />
								</td>
								<td style="text-align:center;">
									<strong style="color:#333333;">Date:</strong>
									<br>
									'.get_user_meta($user_id, 'w9_date', true).'
								</td>
							</tr>
						</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/W9-Form-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'w9_form_pdf', $pdf_dir_url.'/W9-Form-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/W9-Form-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	//echo json_encode( array("Success" => true) );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "signature") ) {
	$e_signature = $_POST['e_signature'];
	$signature_progress = $_POST['signature_progress'];
	
	$user_id = get_current_user_id();
	update_user_meta($user_id, 'e_signature', $e_signature);
	update_user_meta($user_id, 'signature_progress', $signature_progress);
	
	$jsonArray = array("Success" => true);
	
	if( isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == "PDF" ) {
		// create new PDF document
		$pdf = new MC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Rental Guardian');
		$pdf->SetTitle('Onboarding Form');
		$pdf->SetSubject('Onboarding Form Info');
		$pdf->SetKeywords('Onboarding, PDF, Rental, Guardian');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 010', PDF_HEADER_STRING);
		
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// set font
		$pdf->SetFont('helvetica', '', 10);
		
		// add a page
		$pdf->AddPage();
		
		/*$html = '<div style="text-align:center;"><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></div>';*/
		$html = '<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="'.home_url('/wp-content/uploads/2017/12/new-rental-guardian-logo-1.png').'" style="width: 150px;" /></td>
						<td style="text-align:right;"><img src="'.home_url('/wp-content/uploads/2018/03/insure-stays.jpg').'" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>';
		$html .= '<h1 style="text-align:center;">Signature / Completion</h1>';
		$html .= '<br><br><br><br><br><table border="0" cellpadding="10" cellspacing="0">
					<tr>
						<td style="text-align:center;">
							<strong style="color:#333333;">e-Signature:</strong>
							<br>
							'.get_user_meta($user_id, 'e_signature', true).'
						</td>
					</tr>
				</table>';
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		$upload_dir = wp_upload_dir();
		$pdf_dir = $upload_dir['basedir'].'/onboard-pdfs';
		$pdf_dir_url = $upload_dir['baseurl'].'/onboard-pdfs';
		if( ! file_exists( $pdf_dir ) ) {
			wp_mkdir_p( $pdf_dir );
		}
		//Close and output PDF document
		$pdf->Output($pdf_dir.'/Signature-'.$user_id.'.pdf', 'F');
		
		update_user_meta($user_id, 'signature_pdf', $pdf_dir_url.'/Signature-'.$user_id.'.pdf');
		$jsonArray["pdf_url"] = $pdf_dir_url.'/Signature-'.$user_id.'.pdf';
	}
	
	echo json_encode( $jsonArray );
	exit;
} elseif( isset($_POST['submitted_form']) && ($_POST['submitted_form'] == "payment-form") ) {
	$pay_signature = $_POST['pay_signature'];
	$payment_progress = $_POST['payment_progress'];
	
	$user_id = get_current_user_id();
	update_user_meta($user_id, 'pay_signature', $pay_signature);
	update_user_meta($user_id, 'payment_progress', $payment_progress);
	
	$jsonArray = array("Success" => true);
	
	echo json_encode( $jsonArray );
	exit;
}
?>