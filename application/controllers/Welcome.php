<?php
/**
 *
 */
class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        redirect(base_url() . "admin/login");
    }
}