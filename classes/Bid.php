<?php

/**
 * A class representing an auction bid
 */
class Bid {

    private $_db, //The database
        $_data; // Array of object properties

    /**
     * Construct the bid object
     * @param null $bid_id
     */
    public function __construct($bid_id = null) {
        $this->_db = DB::getInstance();
        if(!$bid_id) {

        } else {
            $this->find($bid_id);
        }

    }

    /**
     * Create a new bid record
     * @param array $fields
     * @throws Exception
     */
    public function create($fields = array()) {
        if (!$this->_db->insert('bids', $fields)) {
            throw new Exception('There was an issue creating the bid.');
        }
    }

    /**
     * Lookup a bid in the database using its id
     * @param null $bid
     * @return bool
     */
    public function find($bid = null) {
        if($bid) {

            $data = $this->_db->get('bids', array('id', '=', $bid));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }


    /**
     * Does the bid object exist?
     * @return bool
     */
    public function exists() {
        return (!empty($this->_data))? true : false ;
    }

    /**
     * Delete this bid record
     */
    public function delete(){
        //delete the bid
        $this->_db->delete('bids', ['id', '=', $this->data()->id]);
        //and it's bids
        $this->_db->delete('bids', ['bid_id', '=', $this->data()->id]);

    }

    /**
     * Return the bid data
     * @return mixed An object containing the bids properties
     */
    public function data() {
        return $this->_data;
    }


}