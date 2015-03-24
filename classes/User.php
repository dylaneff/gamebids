<?php


class User {
    private $_db, // the database singleton
            $_data, // object with the users properties
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;

    /**
     * Construct the User object
     * @param null $user
     */
    public function __construct($user = null) {
        $this->_db = DB::getInstance();

        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if(!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    $this->logout();
                }
            }
        }else {
            $this->find($user);

        }

    }

    /**
     * Create a new user record
     * @param array $fields
     * @throws Exception
     */
    public function create($fields = array()) {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was an issue creating the account.');
        }
    }

    /**
     * Update a user record
     * @param array $fields
     * @param null $id
     * @throws Exception
     */
    public function update($fields = array(), $id = null) {

        if(!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }

        if(!$this->_db->update('users', $id, $fields)) {
            throw new Exception('There was an issue updating your information.');
        }
    }

    /**
     * Lookup the user in the database
     * @param null $user
     * @return bool
     */
    public function find($user = null) {
        if($user) {
            $field = (is_numeric($user)) ? 'id' : 'username';

            $data = $this->_db->get('users', array($field, '=', $user));
            if ($this->_db->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    /**
     * Log the user in
     * @param null $username
     * @param null $password
     * @param $remember
     * @return bool
     */
    public function login($username = null, $password = null, $remember = false) {
        if(!$username && !$password && $this->exists()){

            Session::put($this->_sessionName, $this->data()->id);
            return true;
        }
        $user = $this->find(escape($username));
        if($user) {
            if($this->data()->password === Hash::make($password, $this->data()->salt)) {
                Session::put($this->_sessionName, $this->data()->id);

                if($remember) {

                $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

                    if(!$hashCheck->count()) {
                        $hash =  Hash::unique();
                        $this->_db->insert('users_session', [
                           'user_id' => $this->data()->id,
                            'hash' => $hash
                        ]);
                    } else {
                        $hash = $hashCheck->first()->hash;
                    }

                    Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));

                }

                return true;
            }
        }

        return false;
    }


    /**
     * Does the user object exist?
     * @return bool
     */
    public function exists() {
        return (!empty($this->_data))? true : false ;
    }

    /**
     * Log the user out
     * delete the session and cookie
     */
    public function logout(){

        $this->_db->delete('users_session', ['user_id', '=', $this->data()->id]);

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    /**
     * Return the user data associative array
     * @return mixed
     */
    public function data() {
        return $this->_data;
    }

    /**
     * Is the user logged in?
     * @return bool
     */
    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }


    /*******
     * Get all of the users active or inactive listings from the database
     * Active listings by default
     * @param int $active
     * @return mixed
     */
    public function getListings($active = null) {
        (isset($active)? $active : $active = 1);
        $sql = "SELECT * FROM listings WHERE active = ? AND owner_id = ?";
        $this->_db->query($sql, [$active, $this->data()->id]);

        return $this->_db->results();
    }


}