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
wp_enqueue_style('style', '/wp-content/plugins/zeeuwsekringloop-file-extension/includes/bootstrap/style.css');

?>


<div class="wrap">

    <?php
    if (isset($add)) {
        echo($add ? "<p>Added a new type</p>" : "");
    }
    // Check if action == update : then start update form
    echo(($action == 'update') ? '<form action="' . $base_url . '" method="post">' : '');
    ?>
    <h2>Overview</h2>
    <table class="table table-backend">
        <thead class="table-dark">
        <tr>
            <th width="10">#</th>
            <th width="500">Event</th>
            <th width="500">Host</th>
            <th width="2000">Description</th>
            <th width="500">Event Date</th>
            <th width="500">Estimated Time</th>
            <th colspan="2" width="200">Actions</th>
        </tr>
        </thead>
        <!-- <tr><td colspan="3">Event types rij 1</td></tr> -->
        <?php
        //*
        if ($events->getNrOfEvents() < 1) {
            ?>
            <tr>
                <td colspan="6">Start recieving information
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
                    <td width="10"><?php echo $events_obj->getId();
                        ?></td>
                    <?php
                    // If update and id match show update form
                    // Add hidden field id for id transfer
                    if (($action == 'update') && ($events_obj->getId() == $get_array['id'])) {
                        ?>
                        <td width="500"><input type="hidden" name="id" value="<?php echo $events_obj->getId(); ?>">
                            <input type="text" name="event" value="<?php echo $events_obj->getEvent(); ?>"></td>
                        <td width="200"><input style="width:200px;" type="text" name="host" value="<?php echo $events_obj->getHost(); ?>"></td>
                        <td width="200"><textarea style="width:500px; resize:none; height:200px;" name="description"><?php echo $events_obj->getDescription(); ?></textarea></td>
                        <td width="200"><input style="width:200px;" type="text" name="event_date" value="<?php echo $events_obj->getEventDate(); ?>"></td>
                        <td width="200"><input style="width:200px;" type="text" name="event_estimated_time" value="<?php echo $events_obj->getEventEstimatedTime(); ?>"></td>
                        <td colspan="2"><input type="submit" name="update" value="Updaten"/></td>
                    <?php } else { ?>
                        <td width="180"><?php echo $events_obj->getEvent(); ?></td>
                        <td width="200"><?php echo $events_obj->getHost(); ?></td>
                        <td width="200"><?php echo $events_obj->getDescription(); ?></td>
                        <td width="200"><?php echo $events_obj->getEventDate(); ?></td>
                        <td width="200"><?php echo $events_obj->getEventEstimatedTime(); ?></td>
                        <?php if ($action !== 'update') {
                            // If action is update don’t show the action button
                            ?>
                            <td><a href="<?php echo $upd_link; ?>">Update</a></td>
                            <td><a href="<?php echo $del_link; ?>">Delete</a></td>
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

        <?php
    } // if action !== update
    ?>
</div>