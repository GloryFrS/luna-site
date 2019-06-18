<?php
session_start();
if (isset($_SESSION['utm_source'])) $utm_source = $_SESSION['utm_source'];
else {
if (isset($_GET['utm_source'])) $_SESSION['utm_source'] = $_GET['utm_source'];
else {
$referer = $_SERVER["HTTP_REFERER"];
if (strpos($referer, 'google') !== false) $_SESSION['utm_source'] = 'google';
else if (strpos($referer, 'yandex') !== false) $_SESSION['utm_source'] = 'yandex';
else if (strpos($referer, '2gis') !== false) $_SESSION['utm_source'] = '2gis';
else if (strpos($referer, 'flamp') !== false) $_SESSION['utm_source'] = 'flamp';
else if ($referer != '') $_SESSION['utm_source'] = $referer;
else $_SESSION['utm_source'] = 'link';
}
}

if (isset($_SESSION['utm_medium'])) $utm_medium = $_SESSION['utm_medium'];
else {
if (isset($_GET['utm_medium'])) $_SESSION['utm_medium'] = $_GET['utm_medium'];
else $_SESSION['utm_medium'] = '';
}

if (isset($_SESSION['utm_term'])) $utm_term = $_SESSION['utm_term'];
else {
if (isset($_GET['utm_term'])) $_SESSION['utm_term'] = $_GET['utm_term'];
else $_SESSION['utm_term'] = '';
}

if (isset($_SESSION['utm_content'])) $utm_content = $_SESSION['utm_content'];
else {
if (isset($_GET['utm_content'])) $_SESSION['utm_content'] = $_GET['utm_content'];
else $_SESSION['utm_content'] = '';
}

if (isset($_SESSION['utm_campaign'])) $utm_campaign = $_SESSION['utm_campaign'];
else {
if (isset($_GET['utm_campaign'])) $_SESSION['utm_campaign'] = $_GET['utm_campaign'];
else $_SESSION['utm_campaign'] = '';
}
if (isset($_SESSION['utm_type'])) $utm_type = $_SESSION['utm_type'];
else {
if (isset($_GET['utm_type'])) $_SESSION['utm_type'] = $_GET['utm_type'];
else $_SESSION['utm_type'] = '';
}

$utm_source = $_SESSION['utm_source'];
$utm_medium = $_SESSION['utm_medium'];
$utm_term = $_SESSION['utm_term'];
$utm_content = $_SESSION['utm_content'];
$utm_campaign = $_SESSION['utm_campaign'];
$utm_type = $_SESSION['utm_type'];

if ($utm_source == '') $utm_source = isset($_POST['source']) ? $_POST['source'] : '(неизвестно)';

$recepient = "dm@irsib.pro"; //На какое эмейл идут заявочки

$method = $_SERVER['REQUEST_METHOD'];

//Script Foreach
$c = true;


	$project_name = "LunaApps";
	$form_subject = "Заявка";

	foreach ( $_POST as $key => $value ) {
		if ( $value != "" && $key != "project_name" && $key != "admin_email" && $key != "form_subject" ) {
			$message .= "
			" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
				<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
				<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
			";
		}
	}


$message = "<table style='width: 100%;'>$message</table>";

mail($recepient, $form_subject, $message, "From: $project_name <$recepient>" . "\r\n" . "Reply-To: $recepient" . "\r\n" . "X-Mailer: PHP/" . phpversion() . "\r\n" . "Content-type: text/html; charset=\"utf-8\"");


                 $queryUrl = 'https://irsib.bitrix24.ru/rest/1/23hc0snzv1knezy2/crm.contact.add.json';
                $queryData = http_build_query(array(
                	'fields' => array(
						"NAME" => $_POST['name'], 
						"OPENED" => "Y", 
						"TYPE_ID" => "CLIENT",
						"SOURCE_ID" => "SELF",
			            "PHONE" => array(array("VALUE" => $_POST['phone'], "VALUE_TYPE" => "WORK" )),
			            "ASSIGNED_BY_ID" => 956,
					),
                 ));


                 $curl = curl_init();
                 curl_setopt_array($curl, array(
                     CURLOPT_SSL_VERIFYPEER => 0,
                     CURLOPT_POST => 1,
                     CURLOPT_HEADER => 0,
                     CURLOPT_RETURNTRANSFER => 1,
                     CURLOPT_URL => $queryUrl,
                     CURLOPT_POSTFIELDS => $queryData,
                 ));

                 $result = curl_exec($curl);
                 curl_close($curl);
                 $contact_ID = json_decode($result, 1);
                 $contact_ID = $contact_ID["result"];
                
if (is_numeric($contact_ID)) {
    $queryUrl = 'https://irsib.bitrix24.ru/rest/1/23hc0snzv1knezy2/crm.deal.add.json';
    $queryData = http_build_query(array(
        'fields' => array(
            "TITLE" =>  $_POST['name'] . ":" . $_POST['phone'] ,
            "CURRENCY_ID"=> "RUB", 
            "OPENED" => "Y",
            "OPPORTUNITY" => 0,
            "STAGE_ID" => 'C76:NEW',
            "CONTACT_ID" => $contact_ID,
            "CATEGORY_ID" => 76,
              "COMMENTS" => $_POST['comment'],
			"ASSIGNED_BY_ID" => 956,
                            'UF_CRM_CT_UTM_SOUR' => $utm_source,
                            'UF_CRM_CT_UTM_MEDI' => $utm_medium,
                            'UF_CRM_CT_UTM_CAMP' => $utm_campaign,
                            'UF_CRM_CT_UTM_TERM' => $utm_term,
                            'UF_CRM_CT_UTM_CONT' => $utm_content,
        ),
    ));
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));

    $result = curl_exec($curl);
    curl_close($curl);
    $deal_ID = json_decode($result, 1);
    $deal_ID = $deal_ID["result"];
    

   
}