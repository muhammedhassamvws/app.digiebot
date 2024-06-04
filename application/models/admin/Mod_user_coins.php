<?php
class mod_user_coins extends CI_Model {

    function __construct() {

        parent::__construct();
    }

    //get_all_coins
    public function get_all_coins() {
        $user_id = $this->session->userdata('admin_id');
        $this->mongo_db->sort(array('_id' => -1));
        $this->mongo_db->where(array('user_id' => ($user_id)));
        $get_coins = $this->mongo_db->get('coins');
        $coins_arr = iterator_to_array($get_coins);

        return $coins_arr;

    } //end get_all_user_coins

    public function get_all_coins_sql() {
        $user_id = $this->session->userdata('admin_id');
        $this->db->dbprefix('coins');
        $this->db->select('*');
        $this->db->where('`id` NOT IN (SELECT `coin_id` FROM `tr_user_coins` WHERE user_id = ' . $user_id . ')', NULL, FALSE);
        $get_coins = $this->db->get('coins');

        //echo $this->db->last_query(); exit;
        $coins_arr = $get_coins->result_array();
        //print_r($coins_arr);
        return $coins_arr;

    } //end get_all_user_coins

    public function get_all_coins_mongo() {
        $this->mongo_db->where(array('user_id' => $user_id));
        $this->mongo_db->select(array('coin_id'));
        $res = $this->mongo_db->get('user_coins');
        $arr = itrator_to_array($res);
        $this->mongo_db->where_not_in('_id', $arr);
        $res2 = $this->db->get('coins');
        $arr2 = itrator_to_array($res2);

        return $arr2;
    }

    public function get_all_user_coins() {
        $id = $this->session->userdata('admin_id');

        $this->db->select('*');
        $this->db->where('user_id', $id);
        $this->db->from('coins');
        $this->db->join('user_coins', 'coins.id = user_coins.coin_id');
        $query = $this->db->get();
        $coins_arr = $query->result_array();

        //echo $this->db->last_query();
        //$coins_arr = $get_coins->result_array();

        return $coins_arr;

    } //end get_all_user_coins

    public function get_all_user_coins_mongo() {

        $this->mongo_db->where(array('user_id' => $user_id));
        $this->mongo_db->select(array('coin_id'));
        $res = $this->mongo_db->get('user_coins');
        $arr = itrator_to_array($res);
        $this->mongo_db->where_in('_id', $arr);
        $res2 = $this->db->get('coins');
        $arr2 = itrator_to_array($res2);

        $id = $this->session->userdata('admin_id');

        $this->db->select('*');
        $this->db->where('user_id', $id);
        $this->db->from('coins');
        $this->db->join('user_coins', 'coins.id = user_coins.coin_id');
        $query = $this->db->get();
        $coins_arr = $query->result_array();

        //echo $this->db->last_query();
        //$coins_arr = $get_coins->result_array();

        return $coins_arr;

    } //end get_all_user_coins

    //get_coin
    public function get_coin($coin_id) {

        $this->mongo_db->where('_id', $this->mongo_db->mongoId($coin_id));
        $get_coin = $this->mongo_db->get('coins');
        $coin_arr = iterator_to_array($get_coin);
        return $coin_arr[0];

    } //end get_coin

    //add_coin
    public function add_coin($data) {
        extract($data);
        $user_id = (trim($this->session->userdata('admin_id')));

        //delete all user coins
        $db = $this->mongo_db->customQuery();
        $delete_where['user_id'] = $user_id;
        $db->coins->deleteMany($delete_where);

        $created_date = date('Y-m-d G:i:s');

        for ($i = 0; $i < count($coins); $i++) {
            $coin_arr = $this->get_coin($coins[$i]);

            $ins_data = array(
                'user_id' => (trim($this->session->userdata('admin_id'))),
                'symbol' => $coin_arr['symbol'],
                'coin_name' => $coin_arr['coin_name'],
                'coin_logo' => $coin_arr['coin_logo'],
            );

            ///
            $ins_into_db = $this->mongo_db->insert('coins', $ins_data);
            // $filter = array('user_id' => $this->session->userdata('admin_id'), 'symbol' => $coin_arr['symbol']);
            // $ins_into_db = $db->coins->updateOne($filter, array('$set' => $ins_data), array('upsert' => true));
        }
        if ($ins_into_db) {
            return true;
        }

    } //end add_coin()

    //edit_coin
    public function edit_coin($data) {

        extract($data);

        $upd_data = array(
            'coin_name' => (trim($coin_name)),
            'symbol' => (trim($symbol)),
        );

        if ($_FILES) {
            $path_to_store = 'assets/coin_logo/';
            $file_name = $_FILES['logo']['name'];
            $temp_name = $_FILES['logo']['tmp_name'];
            $size = $_FILES['logo']['size'];
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $new_file = 'attachment-' . time() . uniqid(rand()) . '.' . $ext;
            if (!(move_uploaded_file($temp_name, $path_to_store . $new_file))) {
                echo "Uploading Failed";exit;
            } else {
                $upd_data['coin_logo'] = SURL . $path_to_store . $new_file;
            }
        }
        //Update the record into the database.
        $this->db->dbprefix('coins');
        $this->db->where('id', $coin_id);
        $upd_into_db = $this->db->update('coins', $upd_data);

        if ($upd_into_db) {
            return $coin_id;
        }

    } //end edit_coin()

    //delete_coin
    public function delete_coin($coin_id) {

        //Delete coin Record
        $this->mongo_db->where(array('_id' => $coin_id));
        $this->mongo_db->delete('coins');

        return true;

    } //end delete_coin()

}
?>
