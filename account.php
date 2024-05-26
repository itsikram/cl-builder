<?php
// template name: Account

if (!is_user_logged_in()) {
    wp_redirect(site_url());
}

if (isset($_REQUEST['logout'])) {
    wp_logout();
    wp_redirect(site_url());

}


$user_data = get_userdata(get_current_user_id());
$user_id = $user_data->data->ID;

if (isset($_REQUEST['account_details'])) {
    $fname = isset($_REQUEST['fName']) ? sanitize_text_field($_REQUEST['fName']) : '';
    $lname = isset($_REQUEST['fName']) ? sanitize_text_field($_REQUEST['lName']) : '';


    try {
        wp_update_user(
            array(
                "ID" => $user_id,
                "first_name" => $fname,
                "last_name" => $lname,
            )
        );

        wp_redirect(get_permalink() . '?type=success&message=Account Details Updated Successfully');

    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }

}


// define variable for user data

$user_first_name = get_user_meta($user_id, 'first_name', true);
$user_last_name = get_user_meta($user_id, 'last_name', true);





get_header();






?>

<div class="container py-3">
    <div class="row">
        <div class="col">
            <p>Hi, <?php echo $user_data->data->user_nicename; ?></p>

            <div class="card">
                <div class="card-header">
                    My Account
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link active text-start" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-account" type="button" role="tab"
                                    aria-controls="v-pills-account" aria-selected="true">Account Details</button>
                                <button class="nav-link text-start" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-address" type="button" role="tab"
                                    aria-controls="v-pills-address" aria-selected="false">Address</button>
                                <button class="nav-link  text-start" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-password" type="button" role="tab"
                                    aria-controls="v-pills-password" aria-selected="false">Password</button>
                                <a href="<?php echo get_permalink() . '?logout=true' ?>"
                                    class="nav-link text-danger  text-start" id="v-pills-settings-tab" type="button"
                                    role="tab" aria-selected="false">Logout</a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content w-md-75 w-100" id="v-pills-tab">
                                <div class="tab-pane fade show active" id="v-pills-account" role="tabpanel"
                                    aria-labelledby="v-pills-home-tab">
                                    <div class="card card-body">
                                        <h3>Account Details</h3>
                                        <form method="POST">
                                            <input type="hidden" name="account_details">
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="" class="form-label mb-0">Username</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    @
                                                                </div>
                                                            </div>
                                                            <input disabled type="text"
                                                                value="<?php echo $user_data->data->user_login; ?>"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="input-fName" class="form-label mb-0">First
                                                            name</label>
                                                        <input type="text" value="<?php echo $user_first_name; ?>"
                                                            placeholder="First name" name="fName" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="input-fName" class="form-label mb-0">Last
                                                            name</label>
                                                        <input type="text" value="<?php echo $user_last_name; ?>"
                                                            placeholder="Last name" name="lName" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary my-2">Save Changes</button>


                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-address" role="tabpanel"
                                    aria-labelledby="v-pills-profile-tab">...</div>
                                <div class="tab-pane fade" id="v-pills-password" role="tabpanel"
                                    aria-labelledby="v-pills-messages-tab">...</div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php get_footer();