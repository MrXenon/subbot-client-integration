<?php
/**
 * Created by PhpStorm.
 * User: Kevin Schuit
 * Date: 4-11-2020
 * Time: 16:23
 */

// Include model:
include SUBBOT_CLIENT_INTEGRATION_PLUGIN_MODEL_DIR . "/client.php";

// Declare class variable:
$client = new client();

// Set base url to current file and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array('page' => basename(__FILE__, ".php"));

// Add params to base url
$base_url = add_query_arg($params, $base_url);

// Get the GET data in filtered array
$get_array = $client->getGetValues();

// Keep track of current action.
$action = FALSE;
if (!empty($get_array)) {

    // Check actions
    if (isset($get_array['action'])) {
        $action = $client->handleGetAction($get_array);
    }
}

/* Na checken     */
// Get the POST data in filtered array
$post_array = $client->getPostValues();

// Collect Errors
$error = FALSE;
// Check the POST data
if (!empty($post_array['add'])) {

    // Check the add form:
    $add = FALSE;
    // Save event types
    $result = $client->save($post_array);
    if ($result) {
        // Save was succesfull
        $add = TRUE;
    } else {
        // Indicate error
        $error = TRUE;
    }
}

// Check the update form:
if (isset($post_array['update'])) {
    // Save event types
    $client->update($post_array);
}

// Add bootstrap.
include_once SUBBOT_CLIENT_INTEGRATION_PLUGIN_INCLUDES_BOOTSTRAP_DIR . '/bootstrap.php';
// include stylesheet
wp_enqueue_style('style', '/wp-content/plugins/subbot-client-integration/includes/bootstrap/style.css');
?>


<div class="wrap">
    <h1 class="spacing">Discord Client list</h1>

    <?php
    // Check if action = update : then end update form
    echo(($action == 'update') ? '</form>' : '');
    /** Finally add the new entry line only if no update action **/
    if ($action !== 'update') {
        ?>
        <form action="<?= $base_url; ?>" method="post" enctype="multipart/form-data">
            <tr>
                <table class="col-md-12">
                    <tr>
                        <td style="width: 155px;"><span>Discord ID:</span></td>
                        <td><input class="col-md-6 form-control" type="text" name="discordId" required></td>
                    </tr>
                    <tr>
                        <td style="width: 155px;"><span>Expiration:</span></td>
                        <td><input class="col-md-6 form-control" type="date" name="expiration" required></td>
                    </tr>
                    <tr>
                        <td style="width: 155px;"><span>Type:</span></td>
                        <td><input class="col-md-6 form-control" type="text" name="type" required></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input class="spacing col-sm-6 col-md-2 col-lg-2 btn btn-dark col-6" type="submit" name="add" value="Add"/>
                        </td>
                    </tr>
                </table>
        </form>
        <?php
    } // if action !== update
    ?>
    <?php
    if (isset($add)) {
        echo($add ? "" : "");
    }
    // Check if action == update : then start update form
    echo(($action == 'update') ? '<form action="' . $base_url . '" method="post">' : '');
    ?>
    <table class="table table-backend">
        <thead class="table-dark">
        <tr>
            <th width="200">DiscordId</th>
            <th width="200">Expiration</th>
            <th width="200">Type</th>
            <th colspan="2">Action</th>
        </tr>
        </thead>
        <?php
        //*
        if ($client->getNrOfClients() < 1) {
            ?>
            <tr>
                <td colspan="5">The client list is currently empty, please add client so our Discord bot can hand out ranks accordingly.
            </tr>
        <?php } else {
            $client_list = $client->getClientList();

            //** Show all clients in the table
            foreach ($client_list as $client_obj) {

                // Create update link
                $params = array('action' => 'update', 'id' => $client_obj->getId());

                // Add params to base url update link
                $upd_link = add_query_arg($params, $base_url);

                // Create delete link
                $params = array('action' => 'delete', 'id' => $client_obj->getId());

                // Add params to base url delete link
                $del_link = add_query_arg($params, $base_url);
                ?>

                <tr>
                    <?php
                    // If update and id match show update form
                    // Add hidden field id for id transfer
                    if (($action == 'update') && ($client_obj->getId() == $get_array['id'])) {
                        ?>
                        <td width="180"><input type="hidden" name="id" value="<?= $client_obj->getId(); ?>">
                            <input type="text" name="discordId" value="<?= $client_obj->getDiscordId(); ?>"></td>
                        <td width="1000"><input type="date" name="expiration"
                                                value="<?= $client_obj->getExpiration(); ?>"></td>
                        <td width="1000"><input type="text" name="type"
                                                value="<?= $client_obj->getType(); ?>"></td>
                        <td colspan="2"><input class="btn btn-dark"  type="submit" name="update" value="Updaten"/></td>
                    <?php } else { ?>
                        <td width="200"><?= $client_obj->getDiscordId(); ?></td>
                        <td width="200"><?= $client_obj->getExpiration(); ?></td>
                        <td width="200"><?= $client_obj->getType(); ?></td>
                        <?php if ($action !== 'update') {
                            // If action is update don’t show the action button
                            ?>
                            <td><a class="btn btn-dark"  href="<?= $upd_link; ?>">Update</a>
                           <a class="btn btn-dark"  href="<?= $del_link; ?>">Remove</a></td>
                            <?php
                        } // if action !== update
                        ?>
                    <?php } // if acton !== update ?>
                </tr>
                <?php
            }
            ?>
        <?php }
        ?>
    </table>
</div>