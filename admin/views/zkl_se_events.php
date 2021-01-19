<?php
// Include model:
include ZKL_SE_MODEL_DIR . "/ZklEvents.php";

// Declare class variable:
$events = new ZklEvents();

// Set base url to current file and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array('page' => basename(__FILE__, ".php"));

// Add params to base url
$base_url = add_query_arg($params, $base_url);

// Get the GET data in filtered array
$get_array = $events->getGetValues();

// Keep track of current action.
$action = FALSE;
if (!empty($get_array)) {

    // Check actions
    if (isset($get_array['action'])) {
        $action = $events->handleGetAction($get_array);
    }
}

/* Na checken     */
// Get the POST data in filtered array
$post_array = $events->getPostValues();

// Collect Errors
$error = FALSE;
// Check the POST data
if (!empty($post_array['add'])) {

    // Check the add form:
    $add = FALSE;
    // Save event types
    $result = $events->save($post_array);
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
    $events->update($post_array);
}
// Add bootstrap.
include_once ZKL_SE_BOOTSTRAP_DIR . '/bootstrap.php';
// include stylesheet
wp_enqueue_style('style', '/wp-content/plugins/zeeuwsekringloop-subscribe-extension/includes/bootstrap/style.css');

?>
<div class="wrap">

    <?php
    if (isset($add)) {
        echo($add ? "<p>Added a new event</p>" : "");
    }
    // Check if action == update : then start update form
    echo(($action == 'update') ? '<form action="' . $base_url . '" method="post">' : '');
    ?>
    <table>
        <h2>Events</h2>
        <br><br>
        <?php
        //*
        if ($events->getNrOfEvents() < 1) {
            ?>
            <tr>
                <td colspan="3">Start adding Events
            </tr>
        <?php } else {
            $type_list = $events->getEventList();

            //** Show all event types in the tabel
            foreach ($type_list as $events_obj) {

                // Create update link
                $params = array('action' => 'update', 'id' => $events_obj->getId());

                // Add params to base url update link
                $upd_link = add_query_arg($params, $base_url);

                // Create delete link
                $params = array('action' => 'delete', 'id' => $events_obj->getId());

                // Add params to base url delete link
                $del_link = add_query_arg($params, $base_url);
                ?>

                <tr>
                    </td>
                    <?php
                    // If update and id match show update form
                    // Add hidden field id for id transfer
                    if (($action == 'update') && ($events_obj->getId() == $get_array['id'])) {
                        ?>
                        <td width="180"><input type="hidden" name="id" value="<?php echo $events_obj->getId(); ?>">
                            <input type="text" name="file_name" value="<?php echo $events_obj->getName(); ?>"></td>
                        <td width="200"><input type="text" name="file_description" value="<?php echo $events_obj->getDescription(); ?>"></td>
                        <td width="200"><input type="text" name="file_link" value="<?php echo $events_obj->getLink(); ?>"></td>
                        <td colspan="2"><input type="submit" name="update" value="Updaten"/></td>
                    <?php } else { ?>
                        <?php if ($action !== 'update') {
                            // If action is update don’t show the action button
                            ?>

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
    <?php
    // Check if action = update : then end update form
    echo(($action == 'update') ? '</form>' : '');
    /** Finally add the new entry line only if no update action **/
    if ($action !== 'update') {
        ?>
        <form action="<?php echo $base_url; ?>" method="post">
            <tr>
                <table class="col-md-12">
                    <tr>
                        <td style="width: 155px;"><span>Event:</span></td>
                        <td><input class="col-md-4 form-control" type="text" name="event"></td>
                    </tr>
                    <tr>
                        <td style="width: 155px;"><span>Host:</span></td>
                        <td><input class="col-md-4 form-control" type="text" name="host"></td>
                    </tr>
                    <tr>
                        <td style="width: 155px;"><span>Description:</span></td>
                        <td><textarea class="col-md-4 form-control" type="text" name="description"></textarea></td>
                    </tr>
                    <tr>
                        <td style="width: 155px;"><span>Event Date:</span></td>
                        <td><input class="col-md-4 form-control" type="date" name="event_date"></td>
                    </tr>
                    <tr>
                        <td style="width: 155px;"><span>Event Estimated Time:</span></td>
                        <td><input class="col-md-4 form-control" type="time" name="event_estimated_time"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input class="spacing col-md-4 btn btn-dark col-12" type="submit" name="add" value="Toevoegen"/>
                        </td>
                    </tr>
                </table>
        </form>
        <?php
    } // if action !== update
    ?>
</div>