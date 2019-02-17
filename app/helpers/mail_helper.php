<?php

class Mailer {
	private $modes = [
		'mail_confirm' => [
			'subject' => 'Email verification',
			'action' => 'Verify Email',
			'title' => 'Verify your email address',
			'body' => 'Thanks for signing up for Camagru! We\'re excited to have you as an early user.',
		],
		'like_notif' => [
			'subject' => 'Like notification',
			'action' => 'Check it out',
			'title' => 'Sharing love',
			'body' => 'Someone liked a post of yours recently..',
		],
		'comment_notif' => [
			'subject' => 'Comment notification',
			'action' => 'Check it out',
			'title' => 'Sharing thoughts',
			'body' => 'Someone commented a post of yours recently..',
		],
		'pwd_reset' => [
			'subject' => 'Password reset',
			'action' => 'You can do that here',
			'title' => 'Security is key',
			'body' => 'You asked for updating your password recently..',
		]
	];

	public function __construct($mode, $to, $url) {
		$this->to = $to;
		$this->url = $url;
		$this->body = $this->treat($this->modes[$mode]);
		$this->subject = $this->modes[$mode]['subject'];
		$this->headers = 'Content-type: text/html;charset=iso-8859-1\r\n';
	}

	public function treat($data) {
		$raw = file_get_contents(APPROOT.'/views/mail/mail.html');
		$res = preg_replace('/{{title}}/', $data['title'], $raw);
		$res = preg_replace('/{{body}}/', $data['body'], $res);
		$res = preg_replace('/{{action_url}}/', $this->url, $res);
		$res = preg_replace('/{{action}}/', $data['action'], $res);
		return $res;
	}

	public function send() {
		return mail($this->to, $this->subject, $this->body, $this->headers);
	}
}


?>