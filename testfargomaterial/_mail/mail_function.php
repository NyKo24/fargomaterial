<?//fonction d'envoie de mail

require_once(BASE_REP."_mail/mail_config.php");
/**
 * Envoie un email à $mailTo avec pour sujet $subject et comme contenu $contenu
 * ATTENTION : chaque envoie nécéssite une connexion au serveur SMTP 
 * 
 * @paran $arrayTo String : Adresse email du destinataire
 * @param $subject string : Sujet de l'email
 * @param $contenu string : chaine de caractère en HTML reprensant le corps du mail
 * @param [$debug] booleen defaut=false : active le mode debug
 */
function envoyerUnMail($mailTo,$subject, $contenu,$from,$reply = MAIL_ADRESSE, $debug=false) {

	$mail = new PHPMailer();
	$mail->CharSet = 'UTF-8';
	if($debug)
		$mail->SMTPDebug = 3; // active le mod débug 
	
	$mail->isSMTP();       
	                               // Set mailer to use SMTP
	$mail->Host = MAIL_SERVER;  // Specify main and backup SMTP servers
	$mail->SMTPAuth = MAIL_AUTH;  // Enable SMTP authentication
	$mail->Username = MAIL_USER;  // SMTP username
	$mail->Password = MAIL_PASSWORD;  // SMTP password
	$mail->SMTPSecure = MAIL_CRYPTAGE;  // Enable TLS encryption, `ssl` also accepted
	$mail->Port = MAIL_PORT;  // TCP port to connect to
	
	$mail->From = MAIL_ADRESSE;
	$mail->FromName = $from;
	$mail->addAddress($mailTo);   // Add a recipient
	// Name is optional
	$mail->addReplyTo($reply);
	

	$mail->isHTML(true); // Set email format to HTML
	
	$mail->Subject = $subject;
	$mail->Body    = $contenu;
	
	
	if($mail->send()) {
		return true;
	} else {
		echo $mail->ErrorInfo;
		return false;
	}
	
	
	
}

/**
 * Envoie un email unique en metant toute les adresse de $arrayTo en Cci, avec pour sujet $subject et comme contenu $contenu
 *
 * @paran $arrayTo array : Tableau indicé contenant les adresse des email des destinataires
 * @param $subject string : Sujet de l'email
 * @param $contenu string : chaine de caractère en HTML reprensant le corps du mail
 * @param [$debug] booleen defaut=false : active le mode debug
 */
function envoyerMailGroupe($arrayTo,$subject, $contenu,$debug=false) {
	require_once("phpmailer/class.phpmailer.php");

	$mail = new PHPMailer();

	$body = $contenu;
	$body = eregi_replace("[\]", '', $body);

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->CharSet="UTF-8";
	$mail->Host = MAIL_SERVER;
	$mail->SMTPDebug = 2;
	$mail->SMTPAuth = true;
	$mail->Port = MAIL_PORT;
	$mail->Username = MAIL_SERVER;
	$mail->Password = MAIL_PASSWORD;
	$mail->SetFrom(MAIL_ADRESSE, TITRE_SITE);
	$mail->AddReplyTo(MAIL_ADRESSE, TITRE_SITE);
	$mail->Subject = $subject;


	$mail->MsgHTML($body);

	foreach($arrayTo as $adresse)
		$mail->AddBCC($adresse, $adresse);

	/**
	 * ENVOI et DEBUG
	*/
	if (ENVOI_MAIL_OK) {
		if (!$mail->Send()) {
			print_r("Mailer Error: " . $mail->ErrorInfo);
		} elseif ($debug) {
			print_r("Message envoy� � :" . print_r($arrayTo));
			print_r($contenu);
			print_r("---------------\n");
		}
	} elseif ($debug) {
		print_r("Simulation de Message envoy� � :" . print_r($arrayTo));
		print_r($contenu);
		print_r("---------------\n");
	}
}
?>