<?php

class Pages extends Controller {

	public function __construct() {

	}

	public function index() {
		$data = [
			'title' => 'Camagru',
			'description' => 'Simple instagram like app'
		];
		$this->view('pages/index', $data);
	}
	
	public function about() {
		$data = [
			'title' => 'About us',
			'description' => 'This app lets you share images with friends!'
		];
		$this->view('pages/about', $data);
	}

}

?>