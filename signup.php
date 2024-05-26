<?php
// template name: sign up


if (is_user_logged_in()) {
    wp_redirect(site_url());
}

if (isset($_REQUEST['email'])) {


    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
    $fname = isset($_REQUEST['fname']) ? $_REQUEST['fname'] : '';
    $lname = isset($_REQUEST['lname']) ? $_REQUEST['lname'] : '';
    $website = isset($_REQUEST['website']) ? $_REQUEST['website'] : '';

    if (email_exists($email)) {
        return wp_redirect(get_permalink() . '?type=danger&message=Email Already Exist');
    }
    if (username_exists($username)) {
        return wp_redirect(get_permalink() . '?type=danger&message=Username Already Exist');
    }


    try {

        wp_insert_user(
            array(
                'user_login' => $username,
                'user_email' => $email,
                'user_pass' => $password,
                'first_name' => $fname,
                'last_name' => $lname,
                'meta_input' => array(
                    // 'first_name' => $fname,
                    // 'last_name ' => $lname,
                    'website' => $website,
                )
            )
        );

        wp_redirect(site_url() . '/login?type=success&message=Account Created Successfully');

    } catch (Exception $e) {
        print_r($e->getMessage());
    }
}



get_header();



?>


<div class="container py-5">
    <div class="row">
        <div class="col-md-6 offset-md-3 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="fs-3">Sign Up</h2>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group mb-2">
                            <label class="form-label">Email</label>
                            <input type="text" required name="email" placeholder="Enter your Email"
                                class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        @
                                    </div>
                                </div>
                                <input type="text" required name="username" placeholder="Enter your username"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-2">
                                    <label class="form-label">First Name</label>

                                    <input type="text" name="fname" placeholder="John" required class="form-control">
                                </div>
                            </div>
                            <div class="col">

                                <div class="form-group mb-2">
                                    <label class="form-label">Last Name</label>

                                    <input type="text" name="lname" placeholder="Doe" required class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label">Password</label>
                            <input type="password" required name="password" placeholder="Enter your Password" required
                                class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" placeholder="Confirm Password" required
                                class="form-control">
                        </div>


                        <div class="form-group mb-2">
                            <label class="form-label">Website</label>

                            <input type="text" name="website" placeholder="https://your-website.com" required
                                class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Sign Up</button>
                        <div class="text-center">
                            <a href="<?php echo site_url() . '/login'; ?>" class="btn btn-link">Already have an
                                Account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer();