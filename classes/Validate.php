<?php

/**
 * A class used to validate form data
 */
class Validate {


    private $_passed = false,
        $_errors = array(),
        $_IOErrors = array(),
        $_uploadOK = false,
        $_db = null;

    /**
     *  Create a new validate object
     */
    public function __construct(){
        $this->_db = DB::getInstance();
    }

    /**
     * Check the validity of source date using an array of rules
     * If there are errors they are logged
     * If there no errors $_passed is true, if there are errors it is false
     * Return this object with the updated variables
     * @param $source
     * @param array $items
     * @return $this
     */
    public function check($source, $items = array()){
        foreach($items as $item => $rules){
            foreach($rules as $rule => $rule_value){
                $value = trim($source[$item]);
                //escape item uppercase the words and translate underscores to spaces
                $item = escape($item);
                $cleanItem = str_replace('_', ' ', $item);
                $cleanItem = ucwords($cleanItem);
                if($rule === 'required' && empty($value)){
                    $this->addError("{$cleanItem} is required");
                }else if(!empty($value)){
                    switch($rule){
                        //String length minimum rule
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$cleanItem} must be a minimum of {$rule_value} characters");
                            }
                            break;
                        //String length maximumum rule
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$cleanItem} should not exceed {$rule_value} characters");
                            }
                            break;
                        //Must match another form item
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError(str_replace('_', ' ', ucwords($rule_value))." must match {$cleanItem}");
                            }
                            break;
                        //Must be unique
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()){
                                $this->addError("{$cleanItem} already exists");
                            }
                            break;
                        //Alphanumeric with underscore At least one letter
                        case 'alphanumeric_'://^\d*[a-zA-Z][a-zA-Z0-9_]*$
                            if(!preg_match('/^(?=[^A-Za-z]*[A-Za-z])[A-Za-z0-9_]+$/', $value)){
                            $this->addError("{$cleanItem} must only contain (at least one) letters, numbers and underscores");
                            }
                            break;
                        //Greater than
                        case '>':
                            if($value <= $rule_value){
                                $this->addError("{$cleanItem} must be greater than {$rule_value}");
                            }
                            break;
                        //Greater than or equal to
                        case '>=':
                            if($value < $rule_value){
                                $this->addError("{$cleanItem} must be at least {$rule_value}");
                            }
                            break;
                        //Less than
                        case '<':
                            if($value >= $rule_value){
                                $this->addError("{$cleanItem} must be a less than {$rule_value}");
                            }
                            break;
                        //Less than or equal to
                        case '<=':
                            if($value > $rule_value){
                                $this->addError("{$cleanItem} must be at most {$rule_value}");
                            }
                            break;
                    }
                }
            }
        }
        if(empty($this->_errors)){
            $this->_passed = true;
        } else {
            $this->_passed = false;
        }
        return $this;
    }

    /**
     * Validate a file and upload it to the server
     * Logs errors to $_IOerrors
     * If there are no errors and it uploads it returns the file path
     * Otherwise errors are logged
     * @param $file
     * @return string target file | false
     */
    public function checkUpload($file){

        $target_dir = 'images/listings/';
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = substr(Hash::unique(), 0, 10);


        $target_file = $target_dir . $file_name . '.' . $extension;
        if(file_exists($target_file)){
            while(file_exists($target_file)){
                $file_name = substr(Hash::unique(), 0, 10);
                $target_file = $target_dir . $file_name . $extension;
            }
        }


        // Check if image file is a actual image or fake image
            $check = getimagesize($file['tmp_name']);
            if(!$check) {
                $this->addIOError('File is not an image.');
            }
        // Check file size
        if ($file['size'] > 3000000) {
            $this->addIOError('Sorry, the file is too large.');
        }
        // Allow certain file formats
        // * Should change this to an array of allowable extensions
        $allowedFiletypes = ['jpg', 'jpeg', 'png', 'gif'];

        if(!in_array(strtolower($extension), $allowedFiletypes)) {
            $this->addIOError('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
        }
        //Check if errors occurred
        if (!empty($this->_IOErrors)) {
            $this->addIOError('Sorry, your file was not uploaded.');
            return false;
        // if everything is ok, try to upload file
        } else {
                return $target_file;

        }
    }


    /**
     * Add an error to the error array
     * @param $error
     */
    private function addError($error){
        $this->_errors[] = $error;
    }

    /**
     * Get the errors array
     * @return array
     */
    public function errors(){
        return $this->_errors;
    }

    /**
     * Add an error to the IO error array
     * @param $error
     */
    private function addIOError($error){
        $this->_IOErrors[] = $error;
    }

    /**
     * Get the IO errors array
     * @return array
     */
    public function IOErrors(){
        return $this->_IOErrors;
    }

    /**
     * Did the data pass validation?
     * @return bool
     */
    public function passed(){
        return $this->_passed;
    }



    /**
     * Echo an unordered list of errors
     */
    public function print_errors() {
        $output = array();
        foreach($this->_errors as $error) {
            $output[] = '<li>' . $error . '</li>';
        }
    echo '<ul class="error">' . implode('', $output) . '</ul>';
    }

    /**
     * Echo an unordered list of IO errors
     */
    public function print_IOErrors() {
        $output = array();
        foreach($this->_IOErrors as $error) {
            $output[] = '<li>' . $error . '</li>';
        }
        echo '<ul class="error">' . implode('', $output) . '</ul>';
    }

}