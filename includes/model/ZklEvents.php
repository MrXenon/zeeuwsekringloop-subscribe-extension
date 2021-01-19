<?php
/**
 * Created by PhpStorm.
 * User: black
 * Date: 19-1-2021
 * Time: 14:36
 */

class ZklEvents
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
            'event'   => array('filter' => FILTER_SANITIZE_STRING ),
            'host'   => array('filter' => FILTER_SANITIZE_STRING ),
            'description'   => array('filter' => FILTER_SANITIZE_STRING ),
            'event_date'   => array('filter' => FILTER_SANITIZE_STRING ),
            'event_estimated_time'   => array('filter' => FILTER_SANITIZE_STRING ),
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
            if (!isset($input_array['event']) OR
                !isset($input_array['host'])OR
                !isset($input_array['event_date'])OR
                !isset($input_array['event_estimated_time'])){
                // Mandatory fields are missing
                throw new Exception(__("Missing mandatory fields"));
            }
            if ( (strlen($input_array['event']) < 1) OR
                (strlen($input_array['host']) < 1) OR
                (strlen($input_array['event_date']) < 1) OR
                (strlen($input_array['event_estimated_time']) < 1)){
                // Mandatory fields are empty
                throw new Exception( __("Empty mandatory fields") );
            }

            global $wpdb;

            // Insert query
            $wpdb->query($wpdb->prepare("INSERT INTO `". $this->getTableName()
                ."` ( `zkl_event`, `zkl_host`,`zkl_description`,`zkl_event_date`,`zkl_event_estimated_time`)".
                " VALUES ( '%s', '%s','%s','%s','%s');",$input_array['event'],
                $input_array['host'],$input_array['description'],$input_array['event_date'],$input_array['event_estimated_time']) );
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
    public function getNrOfEvents(){
        global $wpdb;

        $query = "SELECT COUNT(*) AS nr FROM `". $this->getTableName() ."`";
        $result = $wpdb->get_results( $query, ARRAY_A );

        return $result[0]['nr'];
    }

    /**
     *
     * @return Submissions
     */
    public function getEventList(){

        global $wpdb;
        $return_array = array();

        $result_array = $wpdb->get_results( "SELECT * FROM `". $this->getTableName() .
            "` ORDER BY `zkl_event_id`", ARRAY_A);


        // For all database results:
        foreach ( $result_array as $idx => $array){
            // New object
            $event = new ZklEvents();
            // Set all info
            $event->setId($array['zkl_event_id']);
            $event->setEvent($array['zkl_event']);
            $event->setHost($array['zkl_host']);
            $event->setDescription($array['zkl_description']);
            $event->setEventDate($array['zkl_event_date']);
            $event->setEstimatedTime($array['zkl_event_estimated_time']);

            // Add new object toe return array.
            $return_array[] = $event;
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
     * @param Submissions $name name of the event type
     */
    public function setEvent($event ){
        if ( is_string( $event )){
            $this->event = trim($event);
        }
    }

    /**
     *
     * @param Submissions $desc The help text of the event type
     */
    public function setHost ($host){
        if ( is_string($host)){
            $this->host = trim($host);
        }
    }


    /**
     *
     * @param Submissions $desc The help text of the event type
     */
    public function setDescription($description){
        if ( is_string($description)){
            $this->description = trim($description);
        }
    }

    /**
     *
     * @param Submissions $desc The help text of the event type
     */
    public function setEventDate($event_date){
        if ( is_string($event_date)){
            $this->event_date = trim($event_date);
        }
    }

    /**
     *
     * @param Submissions $desc The help text of the event type
     */
    public function setEstimatedTime($event_estimated_time){
        if ( is_string($event_estimated_time)){
            $this->event_estimated_time = trim($event_estimated_time);
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
    public function getEvent(){
        return $this->event;
    }

    /**
     *
     * @return string The description of the download file
     */
    public function getHost(){
        return $this->host;
    }


    /**
     *
     * @return string the link of the download files
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     *
     * @return string the link of the download files
     */
    public function getEventDate(){
        return $this->event_date;
    }

    /**
     *
     * @return string the link of the download files
     */
    public function getEventEstimatedTime(){
        return $this->event_estimated_time;
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
        return $table = $wpdb->prefix . "zkl_events";
    }

    /**
     *
     * @global type $wpdb WordPress database
     * @param type $input_array post_array
     * @return boolean TRUE on Succes else FALSE
     * @throws Exception
     */
    public function update($input_array){
        try {
            $array_fields = array('id', 'event', 'host', 'description', 'event_date', 'event_estimated_time');
            $table_fields = array( 'zkl_event_id', 'zkl_event' , 'zkl_host','zkl_description','zkl_event_date','zkl_event_estimated_time');
            $data_array = array();

            // Check fields
            foreach( $array_fields as $field){

                // Check fields
                if (!isset($input_array[$field])){
                    throw new Exception(__("$field is mandatory for update."));
                }

                // Add data_array (without hash idx)
                // (input_array is POST data -> Could have more fields)
                $data_array[] = $input_array[$field];
            }
            global $wpdb;
            // Update query
            //*
            $wpdb->query($wpdb->prepare("UPDATE `".$this->getTableName()."`
            SET `zkl_event` = '%s', `zkl_host` = '%s', `zkl_description` = '%s', `zkl_event_date` = '%s', `zkl_event_estimated_time` = '%s'".
                "WHERE `".$this->getTableName()."`.`zkl_event_id` ='%d';",$input_array['event'],
                $input_array['host'], $input_array['description'], $input_array['event_date'], $input_array['event_estimated_time'], $input_array['id']) );

        } catch (Exception $exc) {
            echo '<div class="alert alert-danger text-center">
            <strong>Error!</strong> Er ging iets mis.</div>';
            return FALSE;
        }
        echo '<div class="alert alert-success text-center">
            <strong>Success!</strong> Succesvol bijgewerkt.</div>';
        return TRUE;
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
                    unset($table_data['zkl_event_id']);
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
                array( 'zkl_event_id' => $input_array['id'] ),
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
}