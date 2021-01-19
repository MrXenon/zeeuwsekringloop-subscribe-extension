<?php
// Include model:
include ZKL_SE_MODEL_DIR . "/ZklSubscriptions.php";

// Declare class variable:
$subscriptions = new ZklSubscriptions();

// Set base url to current file and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array('page' => basename(__FILE__, ".php"));

// Add params to base url
$base_url = add_query_arg($params, $base_url);

// Get the GET data in filtered array
$get_array = $subscriptions->getGetValues();

// Keep track of current action.
$action = FALSE;
if (!empty($get_array)) {

    // Check actions
    if (isset($get_array['action'])) {
        $action = $subscriptions->handleGetAction($get_array);
    }
}

/* Na checken     */
// Get the POST data in filtered array
$post_array = $subscriptions->getPostValues();

// Collect Errors
$error = FALSE;
// Check the POST data
if (!empty($post_array['add'])) {

    // Check the add form:
    $add = FALSE;
    // Save event types
    $result = $subscriptions->save($post_array);
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
    $subscriptions->update($post_array);
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
            <th width="500">Naam</th>
            <th width="500">Email</th>
            <th width="500">Event</th>
            <th width="2000">Delete</th>
        </tr>
        </thead>
        <!-- <tr><td colspan="3">Event types rij 1</td></tr> -->
        <?php
        //*
        if ($subscriptions->getNrOfSubscriptions() < 1) {
            ?>
            <tr>
                <td colspan="6">Start recieving information
            </tr>
        <?php } else {
            $type_list = $subscriptions->getSubscriptionList();

            //** Show all event types in the tabel
            foreach ($type_list as $subscriptions_obj) {

                // Create update link
                $params = array('action' => 'update', 'id' => $subscriptions_obj->getId());

                // Add params to base url update link
                $upd_link = add_query_arg($params, $base_url);

                // Create delete link
                $params = array('action' => 'delete', 'id' => $subscriptions_obj->getId());

                // Add params to base url delete link
                $del_link = add_query_arg($params, $base_url);
                ?>

                <tr>
                    <td width="10"><?php echo $subscriptions_obj->getId();
                        ?></td>
                    <?php
                    // If update and id match show update form
                    // Add hidden field id for id transfer
                    if (($action == 'update') && ($subscriptions_obj->getId() == $get_array['id'])) {
                        ?>
                    <?php } else { ?>
                        <td width="180"><?php echo $subscriptions_obj->getFname(); ?>
                       <?php echo $subscriptions_obj->getLname(); ?></td>
                        <td width="200"><?php echo $subscriptions_obj->getEmail(); ?></td>
                        <td width="200"><?php
                            // Change vak from an ID to a NAME
                            $id = $subscriptions_obj->getEvent();
                            if(($subscriptions_obj->getEventById($id)) == '') {
                                echo 'Geen evenement beschikbaar';
                            }else {
                                echo($subscriptions_obj->getEventById($id));
                            }
                            ?></td>
                        <?php if ($action !== 'update') {
                            // If action is update don’t show the action button
                            ?>
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