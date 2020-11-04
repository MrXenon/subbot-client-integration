<?php
/**
 * Created by PhpStorm.
 * User: black
 * Date: 4-11-2020
 * Time: 14:34
 */

class client
{
    /**
     * getPostValues :
     * Filter input and retrieve POST input params
     *
     * @return array containing known POST input fields
     */
    public function getPostValues()
    {

        // Define the check for params
        $post_check_array = array(
            // submit action
            'add' => array('filter' => FILTER_SANITIZE_STRING),
            'update' => array('filter' => FILTER_SANITIZE_STRING),
            // List all update form fields !!!
            // Discord ID.
            'discordId' => array('filter' => FILTER_SANITIZE_STRING),
            // expiration of purchase
            'expiration' => array('filter' => FILTER_SANITIZE_STRING),
            // type of purchase
            'type' => array('filter' => FILTER_SANITIZE_STRING),
            // Id of current row
            'id' => array('filter' => FILTER_VALIDATE_INT),
        );
        // Get filtered input:
        $inputs = filter_input_array(INPUT_POST, $post_check_array);
        // RTS
        return $inputs;
    }

    /**
     *
     * @global Client $wpdb The Wordpress database class
     * @param Client $input_array containing insert data
     * @return boolean TRUE on succes OR FALSE
     */
    public function save($input_array)
    {
        try {
            if (!isset($input_array['discordId']) OR
                !isset($input_array['expiration']) OR
                !isset($input_array['type'])) {
                // Mandatory fields are missing
                throw new Exception(__("Missing mandatory fields"));
            }
            if ((strlen($input_array['discordId']) < 1) OR
                (strlen($input_array['expiration']) < 1) OR
                (strlen($input_array['type']) < 1)) {
                // Mandatory fields are empty
                throw new Exception(__("Empty mandatory fields"));
            }

            global $wpdb;

            // Insert query
            $wpdb->query($wpdb->prepare("INSERT INTO `" . $this->getTableName()
                . "` ( `discordId`, `expiration`, `type`)" .
                " VALUES ( '%s', '%s','%s');",
                $input_array['discordId'], $input_array['expiration'], $input_array['type']));
            // Error ? It's in there:
            if (!empty($wpdb->last_error)) {
                $this->last_error = $wpdb->last_error;
                return FALSE;
                var_dump($input_array);
            }
        } catch (Exception $exc) {
            echo '<div class="alert text-center alert-danger">
            <strong>Error!</strong> One or more fields are empty.
            </div>';
        }
        echo '<div class="alert alert-success text-center">
            <strong>Success!</strong> Client has been create.</div>';
        return TRUE;
    }

    /**
     *
     * @return int number of Clients stored in db
     */
    public function getNrOfClients()
    {
        global $wpdb;

        $query = "SELECT COUNT(*) AS nr FROM `" . $this->getTableName()
            . "`";
        $result = $wpdb->get_results($query, ARRAY_A);
        return $result[0]['nr'];
    }

    /**
     *
     * @return Client
     */
    public function getClientList()
    {
        global $wpdb;
        $return_array = array();
        $result_array = $wpdb->get_results("SELECT * FROM `" . $this->getTableName() .
            "` ORDER BY `id`", ARRAY_A);
        // For all database results:
        foreach ($result_array as $idx => $array) {
            // New object
            $client = new client();
            // Set all info
            $client->setId($array['id']);
            $client->setDiscordId($array['discordId']);
            $client->setExpiration($array['expiration']);
            $client->setType($array['type']);
            // Add new object to return array.
            $return_array[] = $client;
        }
        return $return_array;
    }

    /**
     *
     * @param Client $id Id of the Client
     */
    public function setId($id)
    {
        if (is_int(intval($id))) {
            $this->id = $id;
        }
    }

    /**
     *
     * @param client $discordId set discordId of the client
     */
    public function setDiscordId($discordId)
    {
        if (is_string($discordId)) {
            $this->discordId = trim($discordId);
        }
    }


    /**
     *
     * @param client $expiration set expiration date of the client
     */
    public function setExpiration($expiration)
    {
        if (is_string($expiration)) {
            $this->expiration = trim($expiration);
        }
    }
    /**
     *
     * @param client $type set type of the client purchase
     */
    public function setType($type)
    {
        if (is_string($type)) {
            $this->type = trim($type);
        }
    }

    /**
     *
     * @return int The db id of this Client
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string The discordId of the Client
     */
    public function getDiscordId()
    {
        return $this->discordId;
    }


    /**
     *
     * @return string The expiration date of the client
     */
    public function getExpiration()
    {
        return $this->expiration;
    }
    /**
     *
     * @return string The type of the client purchase
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * getGetValues :
     *  Filter input and retrieve GET input params
     *
     * @return array containing known GET input fields
     */
    public function getGetValues()
    {
        //Define the check for params
        $get_check_array = array(
            //Action
            'action' => array('filter' => FILTER_SANITIZE_STRING),
            //Id of current row
            'id' => array('filter' => FILTER_VALIDATE_INT));
        //Get filtered input:
        $inputs = filter_input_array(INPUT_GET, $get_check_array);
        // RTS
        return $inputs;

    }

    /**
     *  Check the action and perform action on :
     *  -delete
     *
     * @param Client $get_array all get vars en values
     * @return string the action provided by the $_GET array.
     */
    public function handleGetAction($get_array)
    {
        $action = '';
        switch ($get_array['action']) {
            case 'update':
                // Indicate current action is update if id provided
                if (!is_null($get_array['id'])) {
                    $action = $get_array['action'];
                }
                break;
            case 'delete':
                // Delete current id if provided
                if (!is_null($get_array['id'])) {
                    $this->delete($get_array);
                }
                $action = 'delete';
                break;
            default:
                // no default, break action.
                break;
        }
        return $action;
    }
    /**
     *
     * @global Client $wpdb
     * @return Client string table name with wordpress (and app prefix)
     */
    private function getTableName()
    {
        global $wpdb;
        return $table = $wpdb->prefix. "clients";
    }

    /**
     *
     * @global Client $wpdb WordPress database
     * @param Client $input_array post_array
     * @return boolean TRUE on Succes else FALSE
     * @throws Exception
     */
    public function update($input_array)
    {
        try {
            $array_fields = array('id', 'discordId', 'expiration','type');
            $table_fields = array('id', 'discordId', 'expiration','type');
            $data_array = array();

            // Check fields
            foreach ($array_fields as $field) {

                // Check fields
                if (!isset($input_array[$field])) {
                    throw new Exception(__("$field is mandatory for update."));
                }

                // Add data_array (without hash idx)
                // (input_array is POST data -> Could have more fields)
                $data_array[] = $input_array[$field];
            }
            global $wpdb;
            // Update query
            //*
            $wpdb->query($wpdb->prepare("UPDATE " . $this->getTableName() . "
            SET `discordId` = '%s', `expiration` = '%s',`type` = '%s' " .
                "WHERE `" . $this->getTableName() . "`.`id` ='%d';", $input_array['discordId'],
                $input_array['expiration'],$input_array['type'], $input_array['id']));

        } catch (Exception $exc) {
            echo '<div class="alert alert-danger text-center">
            <strong>Error!</strong> Er ging iets mis.</div>';
            return FALSE;
        }
        echo '<div class="alert alert-success text-center">
            <strong>Success!</strong> Succesvol bijgewerkt.</div>';
        return TRUE;
    }

    public function delete($input_array)
    {
        try {
            // Check input id
            if (!isset($input_array['id'])) throw new Exception(__("Missing mandatory fields"));
            global $wpdb;
            // Delete row by provided id (WordPress style)
            $wpdb->delete($this->getTableName(),
                array('id' => $input_array['id']),
                array('%d'));
            // Where format
            //*/
            // Error ? It's in there:
            if (!empty($wpdb->last_error)) {
                throw new Exception($wpdb->last_error);
            }
        } catch (Exception $exc) {
            echo '<div class="alert alert-danger text-center">
            <strong>Error!</strong> Something went wrong.</div>';
        }
        echo '<div class="alert alert-success text-center">
            <strong>Success!</strong> Client has been deleted.</div>';
        return TRUE;
    }

}