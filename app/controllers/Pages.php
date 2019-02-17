<?php

class Pages extends Controller {

	public function setup() {
		$this->setupModel = $this->model('Setup');
		$this->setupModel->execute();
		redirect('posts');
	}

	public function index() {
		redirect('posts');
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