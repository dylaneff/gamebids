<?php


/**
 * A class representing an auction listing
 */
class Listing {

    private $_db,
            $_data,
            $_minBid;

    /**
     * Construct the Listing object
     * @param null $listing_id
     */
    public function __construct($listing_id = null) {
        $this->_db = DB::getInstance();
        if(!$listing_id) {

        } else {
            $this->find($listing_id);
        }

    }

    /**
     * Create a new listing record
     * @param array $fields
     * @throws Exception
     */
    public function create($fields = array()) {
        if (!$this->_db->insert('listings', $fields)) {
            throw new Exception('There was an issue creating the listing.');
        }
        else {
        }
    }

    /**
     * Update a listing record
     * @param array $fields
     * @param null $id
     * @throws Exception
     */
    public function update($fields = array(), $id = null) {

        if(!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }

        if(!$this->_db->update('listings', $id, $fields)) {
            throw new Exception('There was an issue updating your information.');
        }
    }

    /**
     * Lookup a listing in the database using its id
     * Populate the data array with the listings data if true
     * @param null $listing
     * @return bool
     */
    public function find($listing = null) {
        if($listing) {

            $data = $this->_db->get('listings', array('id', '=', $listing));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }


    /**
     * Does the listing object exist?
     * @return bool
     */
    public function exists() {
        return (!empty($this->_data))? true : false ;
    }

    /**
     * Delete the the listing record
     */
    public function delete(){
        //delete the listing
        $this->_db->delete('listings', ['id', '=', $this->data()->id]);
        //and it's bids
        $this->_db->delete('bids', ['listing_id', '=', $this->data()->id]);

    }

    /**
     * Return the listing data
     * @return mixed
     */
    public function data() {
        return $this->_data;
    }


    /**
     * Get the bid history for this listing in descending order with newest bid first
     * @return collection of bid objects
     */
    public function getHistory() {
        $sql = 'SELECT * FROM bids WHERE listing_id = ? ORDER BY bids.time DESC';
        $this->_db->query($sql, [$this->data()->id]);
        $_bidHistory = $this->_db->results();
        return $_bidHistory;

    }


    /***
     * Check if an auction has been bid on
     * @return bool
     */
    public function hasBids(){

        foreach ($this->getHistory() as $tempBid) {
            //using this var to check if the history is empty
            $tempArray = (array)$tempBid;

            if(!empty($tempArray)) {
               return true;
                break;
            } else {
                return false;
            }

        }

    }


    /**
     * Return the username of the listing owner
     * @return string
     */
    public function getOwner() {
        $data = $this->_db->get('users', ['id', '=', $this->data()->owner_id]);
        if($data->count()){
            $data = $data->first();
            return $data->username;
        }
            return '';
    }


    /**
     * Get an array of listings (all or by category)
     * @param null $category
     * @return mixed
     */
    public function getListings($category = null) {
        if(!$category) {
            $sql = "SELECT * FROM listings WHERE active = ?";
            $this->_db->query($sql, [1]);
        } else {
            $sql = "SELECT * FROM listings WHERE active = ? AND category = ?;";
            $this->_db->query($sql, [1, $category]);
        }

            return $this->_db->results();
    }

    /**
     * Get the highest bidders username of this listing
     * Or given the listing id return the specified listings highest bidders username
     * @param $listing_id
     * @return $this|mixed|string
     */
    public function getHighestBidder($listing_id = null) {
        if(!isset($listing_id)){
            $listing_id = $this->_data->id;
        }
        $sql = 'SELECT * FROM bids WHERE listing_id = ? ORDER BY bids.amount DESC LIMIT 1';
        $data = $this->_db->query($sql, [$listing_id]);
        if($data->count()){
            $data = $data->first();
            return $data->username;
        }
        return '';
    }

    /**
     * Get the highest bid for this listing
     * Or given a listing id retrieve the highest bid for the specified listing
     * If there are no bids retrieve the starting price
     * @param $listing_id
     * @return float
     */
    public function getHighestBid($listing_id = null) {
        if(!$listing_id){
            $listing_id = $this->_data->id;
        }
        $sql = 'SELECT * FROM bids WHERE listing_id = ? ORDER BY bids.amount DESC  LIMIT 1';
        $data = $this->_db->query($sql, [ $listing_id]);
        if($data->count()){
            $data = $data->first();
            return $data->amount;
        } else {
            return $this->_data->start_price;
        }
    }


    /**
     * Has the reserve price been met
     * @return bool true | false
     */
    public function reservePriceMet(){
        $reserve = $this->_data->reserve_price;
        if ($this->getHighestBid() >= $reserve){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the end time of the auction
     *
     */
    public function getEndTime(){
        return $this->_data->end_time;
    }

    /**
     * Get the end time of auction as string
     * YYYY/MM/DD HH:II:SS
     */
    public function getEndTimeString(){
        $date = new DateTime($this->_data->end_time);
        $final = $date->format('Y/m/d h:i:s A');
        return $final;
    }

    /**
     * Check a bid against the minimum bid
     * @param $bid float
     * @return true if bid is ok | false if bid is too low
     */
    public function checkBid($bid){
        $highBid = $this->getHighestBid();
            Switch(true){
                case $highBid < 20:
                    $this->_minBid = $highBid + 0.50;
                break;
                case $highBid < 200:
                    $this->_minBid = $highBid + 1.00;
                    break;
                case $highBid < 1000:
                    $this->_minBid = $highBid + 5.00;
                    break;
                case $highBid < 5000:
                    $this->_minBid = $highBid + 10.00;
                    break;
                case $highBid < 25000:
                    $this->_minBid = $highBid + 50.00;
                    break;
                case $highBid >= 25000:
                    $this->_minBid = $highBid + 100.00;
                    break;
            }
        if($bid >= $this->_minBid){
            return true;
        } else {
            return false;
        }

    }

    /*******
     * Get the minimum bid
     * @return float
     */
    public function getMinimumBid(){
        $this->checkBid(0);
        return $this->_minBid;
    }


    /***
     * Display the time left in this auction
     * @return string the time left
     */
    public function timeLeft(){
        //Get the difference in time between now and the end time
        $endTime = new DateTime($this->_data->end_time);
        $currentTime = new DateTime();
        $difference = $currentTime->diff($endTime);

        //check for what should be displayed
        if($difference->d > 1){
            $difference = $difference->format('%D days %H h');
        } else if($difference->d > 0){
            $difference = $difference->format('%D day %H h');
        } else if(!($difference->d > 0) && $difference->h > 0  ){
            $difference = $difference->format('%H hours %M m');
        } else {
            $difference = $difference->format('%M m');
        }


        return $difference;
    }

    /*****
     * Has the listing ended?
     * @return bool
     */
    public function listingEnded(){
        if(!$this->data()->active){
            return true;
        }
        return false;
    }

    /*****
     * Did the listing sell
     * @return bool
     */
    public function listingSold(){
        if(!($this->data()->active) && $this->hasBids() && $this->reservePriceMet()){
            return true;
        }
        return false;
    }


    /*****
     *
     * SORTING METHODS
     *
     *
     *
     */

    /**
     * Get a collection of listings priced lowest to highest
     * @param null $category
     * @return mixed
     */
    public function getListingsByPriceAsc($category = null) {
        if(!$category) {
            //Find the max price between both tables and sort by the price
            $sql = "SELECT l.*, COALESCE(MAX(b.amount), l.start_price) price
                    FROM  listings l  LEFT JOIN bids b ON l.id = b.listing_id
                    WHERE l.active = ? GROUP BY l.id ORDER BY price";
            $this->_db->query($sql, [1]);
        } else {
            $sql = "SELECT l.*, COALESCE(MAX(b.amount), l.start_price) price
                    FROM  listings l  LEFT JOIN bids b ON l.id = b.listing_id
                    WHERE l.active = ? AND l.category = ? GROUP BY l.id ORDER BY price";
            $this->_db->query($sql, [1, $category]);
        }

        return $this->_db->results();
    }

    /**
     * Get a collection of listings priced highest to lowest
     * @param null $category
     * @return mixed
     */
    public function getListingsByPriceDesc($category = null) {
        if(!$category) {
            $sql = "SELECT l.*, COALESCE(MAX(b.amount), l.start_price) price
                    FROM  listings l  LEFT JOIN bids b ON l.id = b.listing_id
                    WHERE l.active = ? GROUP BY l.id ORDER BY price DESC";
            $this->_db->query($sql, [1]);
        } else {
            $sql = "SELECT l.*, COALESCE(MAX(b.amount), l.start_price) price
                    FROM  listings l  LEFT JOIN bids b ON l.id = b.listing_id
                    WHERE l.active = ? AND l.category = ? GROUP BY l.id ORDER BY price";
            $this->_db->query($sql, [1, $category]);
        }

        return $this->_db->results();
    }



    /**
     * Get a collection of listings by title A to Z
     * @param null $category
     * @return mixed
     */
    public function getListingsByTitleAsc($category = null) {
        if(!$category) {
            $sql = "SELECT * FROM listings WHERE active = ? ORDER BY listings.title";
            $this->_db->query($sql, [1]);
        } else {
            $sql = "SELECT * FROM listings WHERE active = ? AND category = ? ORDER BY listings.title";
            $this->_db->query($sql, [1, $category]);
        }

        return $this->_db->results();
    }

    /**
     * Get a collection of listings by title Z to A
     * @param null $category
     * @return mixed
     */
    public function getListingsByTitleDesc($category = null) {
        if(!$category) {
            $sql = "SELECT * FROM listings WHERE active = ? ORDER BY listings.title DESC ";
            $this->_db->query($sql, [1]);
        } else {
            $sql = "SELECT * FROM listings WHERE active = ? AND category = ? ORDER BY listings.title DESC";
            $this->_db->query($sql, [1, $category]);
        }

        return $this->_db->results();
    }


    /**
     * Get a collection of listings by least time left
     * @param null $category
     * @return mixed
     */
    public function getListingsByTimeAsc($category = null) {
        if(!$category) {
            $sql = "SELECT * FROM listings WHERE active = ? ORDER BY TIMESTAMPDIFF(SECOND,end_time, NOW())
                    ";
            $this->_db->query($sql, [1]);
        } else {
            $sql = "SELECT * FROM listings WHERE active = ? AND category = ? ORDER BY TIMESTAMPDIFF(SECOND,end_time, NOW())
                    ";
            $this->_db->query($sql, [1, $category]);
        }

        return $this->_db->results();
    }

    /**
     * Get a collection of listings by most time left
     * @param null $category
     * @return mixed
     */
    public function getListingsByTimeDesc($category = null) {
        if(!$category) {
            $sql = "SELECT * FROM listings WHERE active = ? ORDER BY TIMESTAMPDIFF(SECOND,end_time, NOW()) DESC";
            $this->_db->query($sql, [1]);
        } else {
            $sql = "SELECT * FROM listings WHERE active = ? AND category = ? ORDER BY TIMESTAMPDIFF(SECOND,end_time, NOW()) DESC";
            $this->_db->query($sql, [1, $category]);
        }

        return $this->_db->results();
    }





}