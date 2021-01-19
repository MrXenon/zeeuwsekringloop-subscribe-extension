<?php
// Include the Event class from the model.
require_once ZKL_SE_MODEL_DIR . '/ZklSubscriptions.php';
require_once ZKL_SE_MODEL_DIR . '/ZklEvents.php';

$subscriptions = new ZklSubscriptions();
$events = new ZklEvents();

// Set base url to current file and add page specific vars
$base_url = get_site_url() . '/evenementen/';
//$params = array('page' => basename(__FILE__, ".php"));

// Add params to base url
//$base_url = add_query_arg($params, $base_url);

// Keep track of current action.
$action = FALSE;
if (!empty($get_array)) {

    // Check actions
    if (isset($get_array['action'])) {
        $action = $subscriptions->handleGetAction($get_array);
    }
}
/* Na checken */
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
//Get the list with the event categories
$subscription_list = $subscriptions->getSubscriptionList();
$events_list = $events->getEventList();

$events2 = $events->getNrOfEvents();

//Set timezone default:
date_default_timezone_set('Europe/Amsterdam');
?>
<style>
    textarea{
        resize:none;
    }
    input[type=submit]{
        background-color: #1B7723;
    }
</style>
<?php if($events2 == 0){
    echo '<div class="col-md-6">
<p>
Helaas zijn er op dit moment geen evenementen beschikbaar om op in te schrijven.
Check later nog eens om te zien of er evenementen beschikbaar zijn.
</p>
</div>';
}else{

?>

<h2>Aanmelden voor evenement</h2>

<form action="<?= $base_url; ?>" method="post">

    <table class="col-md-12">
        <tr>
            <td style="width: 155px;"><span>Voornaam:</span></td>
            <td><input class="col-md-12 form-control" type="text" name="fname"></td>
        </tr>
        <tr>
            <td style="width: 155px;"><span>Achternaam:</span></td>
            <td><input class="col-md-12 form-control" type="text" name="lname"></td>
        </tr>
        <tr>
            <td style="width: 155px;"><span>Email:</span></td>
            <td><input class="col-md-12 form-control" type="email" name="email"></td>
        </tr>
        <tr>
            <td style="width: 155px;"><span>Evenement:</span></td>
            <td>
            <select name="event" class="form-control" required id="sel2">
                <?php
                // Create the vak drop down
                foreach ($events_list as $event_obj) {
                    ?>
                    <option value="<?php echo $event_obj->getId(); ?>"><?php  echo $event_obj->getEvent(); ?></option>
                <?php }
                ?></select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input class="spacing col-md-4 btn btn-dark col-12" type="submit" name="add" value="Toevoegen"/>
            </td>
        </tr>
    </table>
</form>

<?php } ?>