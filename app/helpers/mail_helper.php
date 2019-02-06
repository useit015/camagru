<?php

function sendVerificationMail($data) {
	$to = $data['email'];
	$subject = 'Email Verification';
	$url = URLROOT.'/users/verify/'.$data['vkey'];
	$raw = file_get_contents(APPROOT.'/views/inc/mail.html');
	$body = preg_replace('/{{action_url}}/', $url, $raw);
	$headers = 'Content-type: text/html;charset=iso-8859-1\r\n';
	return mail($to, $subject, $body, $headers);
}

function sendResetMail($data) {
	$to = $data['email'];
	$subject = 'Password Reset';
	$raw = file_get_contents(APPROOT.'/views/inc/mail.html');
	$body = preg_replace('/{{action_url}}/', $data['url'], $raw);
	$headers = 'Content-type: text/html;charset=iso-8859-1\r\n';
	return mail($to, $subject, $body, $headers);
}

?>