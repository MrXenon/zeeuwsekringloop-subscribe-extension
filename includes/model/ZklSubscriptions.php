<?php
/**
 * Created by PhpStorm.
 * User: black
 * Date: 19-1-2021
 * Time: 12:37
 */

class ZklSubscriptions
{
    /**
     * getPostValues :
     * Filter input and retrieve POST input params
     * @return array containing known POST input fields
     */
    public function getPostValues(){

        // Define the check for params
        $post_check_array = array (
            // submit action
            'add' => array('filter' => FILTER_SANITIZE_STRING ),
            'update'   => array('filter' =>FILTER_SANITIZE_STRING ),
            // List all update form fields !!!
            // file name
            'fname'   => array('filter' => FILTER_SANITIZE_STRING ),
            // file description
            'lname'   => array('filter' => FILTER_SANITIZE_STRING ),
            // file link
            'email'   => array('filter' => FILTER_SANITIZE_STRING ),
            'event'   => array('filter' => FILTER_SANITIZE_STRING ),
            // Id of current row
            'id'    => array( 'filter'    => FILTER_VALIDATE_INT ),
        );
        // Get filtered input:
        $inputs = filter_input_array( INPUT_POST, $post_check_array );
        // RTS
        return $inputs;
    }

    /**
     * @global Submissions $wpdb The Wordpress database class
     * @param Submissions $input_array containing insert data
     * @return boolean TRUE on succes OR FALSE
     */
    public function save($input_array){
        try {
            if (!isset($input_array['fname']) OR
                !isset($input_array['lname'])OR
                !isset($input_array['email'])OR
                !isset($input_array['event'])){
                // Mandatory fields are missing
                throw new Exception(__("Missing mandatory fields"));
            }
            if ( (strlen($input_array['fname']) < 1) OR
                (strlen($input_array['lname']) < 1) OR
                (strlen($input_array['email']) < 1) OR
                (strlen($input_array['event']) < 1)){
                // Mandatory fields are empty
                throw new Exception( __("Empty mandatory fields") );
            }

            global $wpdb;

            // Insert query
            $wpdb->query($wpdb->prepare("INSERT INTO `". $this->getTableName() ."` ( `zkl_fname`, `zkl_lname`, `zkl_email`,`zkl_event`)".
                " VALUES ( '%s', '%s','%s', '%d');",$input_array['fname'],
                $input_array['lname'],$input_array['email'],$input_array['event'] ) );
            // Error ? It's in there:
            if ( !empty($wpdb->last_error) ){
                $this->last_error = $wpdb->last_error;
                return FALSE;
            }
        } catch (Exception $exc) {
            echo '<div class="alert text-center alert-danger">
            <strong>Error!</strong> U heeft één of meerdere velden niet correct ingevuld.
            </div>';
        }
        echo '<div class="alert alert-success text-center">
            <strong>Success!</strong> Succesvol aangemaakt.</div>';
        return TRUE;
    }

    /**
     *
     * @return int number of Submissions stored in db
     */
    public function getNrOfSubscriptions(){
        global $wpdb;

        $query = "SELECT COUNT(*) AS nr FROM `".$this->getTableName() ."`";
        $result = $wpdb->get_results( $query, ARRAY_A );

        return $result[0]['nr'];
    }

    /**
     *
     * @return Submissions
     */
    public function getSubscriptionList(){

        global $wpdb;
        $return_array = array();

        $result_array = $wpdb->get_results( "SELECT * FROM `". $this->getTableName() .
            "` ORDER BY `zkl_subscription_id`", ARRAY_A);


        // For all database results:
        foreach ( $result_array as $idx => $array){
            // New object
            $subscriptions = new ZklSubscriptions();
            // Set all info
            $subscriptions->setId($array['zkl_subscription_id']);
            $subscriptions->setFname($array['zkl_fname']);
            $subscriptions->setLname($array['zkl_lname']);
            $subscriptions->setEmail($array['zkl_email']);
            $subscriptions->setEvent($array['zkl_event']);

            // Add new object toe return array.
            $return_array[] = $subscriptions;
        }
        return $return_array;
    }

    /**
     *
     * @param Submissions $id Id of the event type
     */
    public function setId( $id ){
        if ( is_int(intval($id) ) ){
            $this->id = $id;
        }
    }

    /**
     *
     * @param Subscriptions $name name of the event type
     */
    public function setFname($fname ){
        if ( is_string( $fname )){
            $this->fname = trim($fname);
        }
    }

    /**
     *
     * @param Subscriptions $desc The help text of the event type
     */
    public function setLname ($lname){
        if ( is_string($lname)){
            $this->lname = trim($lname);
        }
    }

    /**
     *
     * @param Subscriptions $desc The help text of the event type
     */
    public function setEmail($email){
        if ( is_string($email)){
            $this->email = trim($email);
        }
    }

    /**
     *
     * @param Subscriptions $desc The help text of the event type
     */
    public function setEvent($event){
        if ( is_string($event)){
            $this->event = trim($event);
        }
    }

    /**
     *
     * @return int The db id of this event
     */
    public function getId(){
        return $this->id;
    }

    /**
     *
     * @return string The name of the download file
     */
    public function getFname(){
        return $this->fname;
    }

    /**
     *
     * @return string The description of the download file
     */
    public function getLname(){
        return $this->lname;
    }

    /**
     *
     * @return string the link of the download files
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     *
     * @return string the link of the download files
     */
    public function getEvent(){
        return $this->event;
    }

    /**
     * getGetValues :
     *  Filter input and retrieve GET input params
     *
     * @return array containing known GET input fields
     */
    public function getGetValues(){
        //Define the check for params
        $get_check_array = array (
            //Action
            'action' => array('filter' => FILTER_SANITIZE_STRING ),

            //Id of current row
            'id' => array('filter' => FILTER_VALIDATE_INT ));

        //Get filtered input:
        $inputs = filter_input_array( INPUT_GET, $get_check_array );

        // RTS
        return $inputs;

    }

    /**
     *  Check the action and perform action on :
     *  -delete
     *
     * @param type $get_array all get vars en values
     * @return string the action provided by the $_GET array.
     */
    public function handleGetAction( $get_array ){
        $action = '';

        switch($get_array['action']){
            case 'update':
                // Indicate current action is update if id provided
                if ( !is_null($get_array['id']) ){
                    $action = $get_array['action'];
                }
                break;

            case 'delete':
                // Delete current id if provided
                if ( !is_null($get_array['id']) ){
                    $this->delete($get_array);
                }
                $action = 'delete';
                break;

            default:
                // Oops
                break;
        }
        return $action;
    }

    /**
     *
     * @global type $wpdb
     * @return type string table name with wordpress (and app prefix)
     */
    private function getTableName(){
        global $wpdb;
        return $table = $wpdb->prefix . "zkl_subscriptions";
    }

    /**
     * The function takes the input data array and changes the
     * indexes to the column names
     * In case of update or insert action
     *
     * @param type $input_data_array  data array(id, name, descpription)
     * @param type $action            update | insert
     * @return type array with collumn index and values OR FALSE
     */
    private function getTableDataArray($input_data_array, $action=''){
        // Get the Table Column Names.
        $keys = $this->getTableColumnNames($this->getTableName());

        // Get data array with table collumns
        // NULL if collumns and data does not match in count
        //
        // Note: The order of the fields shall be the same for both!
        $table_data = array_combine($keys, $input_data_array);

        switch ( $action ){
            case 'update':  // Intended fall-through

            case 'insert':
                // Remove the index -> is primary key and can
                // therefore not be changed!

                if (!empty($table_data)){
                    unset($table_data['zkl_subscription_id']);
                }
                break;
            // Remove
        }

        return $table_data;
    }

    /**
     * Get the column names of the specified table
     * @global type $wpdb
     * @param type $table
     * @return type
     */
    private function getTableColumnNames($table){
        global $wpdb;
        try {
            $result_array = $wpdb->get_results("SELECT `COLUMN_NAME`"."FROM INFORMATION_SCHEMA.COLUMNS".
                " WHERE `TABLE_SCHEMA`='".DB_NAME."' AND TABLE_NAME = '".$this->getTableName() ."'", ARRAY_A);
            $keys = array();
            foreach ( $result_array as $idx => $row ){
                $keys[$idx] = $row['COLUMN_NAME'];
            }
            return $keys;
        } catch (Exception $exc) {
            // @todo: Fix error handlin
            echo $exc->getTraceAsString();
            $this->last_error = $exc->getMessage();
            return FALSE;
        }
    }

    /**
     * @global type $wpdb The WordPress database class
     * @param type $input_array containing delete id
     * @return boolean TRUE on succes OR FALSE
     */
    public function delete($input_array){
        try {
            // Check input id
            if (!isset($input_array['id']) ) throw new Exception(__("Missing mandatory fields") );
            global $wpdb;

            // Delete row by provided id (WordPress style)
            $wpdb->delete( $this->getTableName(),
                array( 'zkl_subscription_id' => $input_array['id'] ),
                array( '%d' ) );

            // Where format
            //*/
            // Error ? It's in there:
            if ( !empty($wpdb->last_error) ){
                throw new Exception( $wpdb->last_error);
            }
        } catch (Exception $exc) {
            echo '<div class="alert alert-danger text-center">
            <strong>Error!</strong> Er ging iets mis.</div>';
        }
        echo '<div class="alert alert-success text-center">
            <strong>Success!</strong> Succesvol verwijderd.</div>';
        return TRUE;
    }

    // Get Event name based on ID
    public function getEventById($id) {
        //Calling wpdb
        global $wpdb;
        //Setting var as an array
        //Database query
        $result_array = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "zkl_events WHERE zkl_event_id = $id", ARRAY_A );
        // Loop through images
        foreach ($result_array as $array) {
            $event = $array['zkl_event'];
        }
        // Return array
        return $event;
    }
}