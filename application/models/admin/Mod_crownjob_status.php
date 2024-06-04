<?php
class mod_cronjob_status extends CI_Model {

    function __construct() {

        parent::__construct();
        
        
    }
    public function get_cron_listing(){

        $data = $this->mongo_db->get('cronjob_listing_update');
        return iterator_to_array($data);
    }
}
