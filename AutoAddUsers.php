<?php

class AutoAddUsersPlugin extends MantisPlugin {

    function register() {
        $this->name = lang_get( 'plugin_autoadduser_title' );
        $this->description = lang_get( 'plugin_autoadduser_description' );
        $this->page = 'config';

        $this->version     = '1.0';
        $this->requires    = array(
            'MantisCore'       => '2.0.0',
        );

        $this->author      = 'Alexis POKORSKI';
        $this->contact     = '';
        $this->url         = '';
    }

    /**
     * Event hook declaration.
     * @return array
     */
    function hooks(){
        return array(
            "EVENT_MANAGE_PROJECT_CREATE" => 'addUsers'
        );
    }


    function config(){
        return array(
            'list_of_users' => 'user;profile;user;profile'
        );
    }

    function usrExist($usr){
        global $g_database_name;
        global $g_hostname;
        global $g_db_username;
        global $g_db_password;

        $query = "SELECT id FROM mantis_user_table where username = '". $usr . "'";
        //Connexion + selection de la base
        $mysqli = new mysqli($g_hostname, $g_db_username, $g_db_password, $g_database_name);

        // Exécution de la requête
        if ($result = $mysqli->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $usr_id = $row['id'];
            }
            /* Libération du jeu de résultats */
            $result->free();
        }
        $mysqli->close();

        return $usr_id;
    }

    /**
     * Add a list of user to an project
     */
    function addUsers( $p_event,  $p_event_args){
        $project_id = $p_event_args;
        $allUsers = explode(';', plugin_config_get('list_of_users'));

        // Add all users to project
        for ($i = 0; $i < count($allUsers); $i= $i+2) {
            $usr = $this->usrExist($allUsers[$i]);
            $access_level = MantisEnum::getValue(config_get( 'access_levels_enum_string' ), $allUsers[$i+1]);
            project_add_user( $project_id, $usr, $access_level );
        }
    }


}