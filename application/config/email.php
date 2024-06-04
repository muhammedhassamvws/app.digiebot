<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_user'] = 'crypto@kulabrands.com';
$config['smtp_pass'] = 'rmjvpdcbewpiwepx';
$config['smtp_port'] = 465;
$config['charset'] = 'utf-8';
$config['mailtype'] = 'html';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n";

// $this->config->load('email', TRUE);
// $config = $this->config->item('email');