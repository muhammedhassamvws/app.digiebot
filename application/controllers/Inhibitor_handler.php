<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * OpenReceptor Inhibitor Handler Class
 *
 * This class contains the "after error" functions that handles the error logging, mailing etc.
 *
 * @author        Dimitris Krestos
 * @license        Apache License, Version 2.0 (http://www.opensource.org/licenses/apache2.0.php)
 * @link        http://vdw.staytuned.gr/
 * @package        OpenReceptor CMS
 * @version        Version 1.0
 */
// class Inhibitor_Handler extends CI_Controller {
//     function __construct() {
//         parent::__construct();
//     }
    // public function index() {

    //     $this->load->library('session');
    //     mail("khan.waqar278@gmail.com", "Hello", "message");
    //     $message = $this->session->flashdata('error');

        // if ($message) {

        //     if (!$this->_mail_exception($message)) {
                // log a message if mailer fails to send the mail
                // $this->_log_exception('Failed to mail the following exception:');

            // }

            // $this->_log_exception($message);

            // loads a proper view or partial
            // $this->load->view('inhibitor');

        // } else {
            // redirects if there is no error

    //         $this->load->helper('url');

    //         redirect(base_url(), 'refresh');

    //     }

    // }

    /**
     * Error Mailer
     *
     * Sends a mail with the error message
     *
     * @access    private
     * @return    boolean
     */
    // private function _mail_exception($message) {

        // $config['mailtype'] = 'text';
        // $config['wordwrap'] = FALSE;

        // $this->config->load('email', TRUE);
        // $config = $this->config->item('email');

        // $this->load->library('email', $config);

        // $this->email->initialize($config);


        // edit the following lines
    //     $this->email->from('do-not-reply@digiebot.com', 'Digiebot');
    //     $this->email->to('khan.waqar278@gmail.com');
    //     $this->email->subject('An Error Occurred');

    //     $this->email->message($message);

    //     if ($this->email->send()) {

    //         return TRUE;

    //     } else {

    //         return FALSE;

    //     }

    // }

    /**
     * Error Logger
     *
     * Logs the error message
     *
     * @access    private
     * @return    void
     */
//     private function _log_exception($message) {

//         log_message('error', $message, TRUE);
//         $arr_ins = array('datetime' => date("Y-m-d H:i:s"), 'message' => $message);
//         $this->mongo_db->insert("application_errors", $arr_ins);

//     }

// }

/* End of file inhibitor_handler.php */
/* Location: ./application/inhibitor_handler.php */