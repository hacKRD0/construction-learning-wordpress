

// Create a button that redurectus to Linkedin authorization URL 
// client id = 78gi3jgzf0umlf
// client secrte key = 6Bc7tdJoudk0QgPP


<?php
$client_id = '78gi3jgzf0umlf'; // Replace with your LinkedIn client ID
$redirect_uri = urlencode('https://www.constructionlearning.net/linkedin-callback');
$state = wp_create_nonce('linkedin_auth');
$linkedInAuthUrl = "https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&state=$state&scope=r_liteprofile%20r_emailaddress";
echo '<a href="' . $linkedInAuthUrl . '" class="btn btn-linkedin">Sign in with LinkedIn</a>';
?>

//Handle the LinkedIn Callback 

<?php 
function handle_linkedin_callback() {
    if (isset($_GET['code']) && isset($_GET['state'])) {
        $state = $_GET['state'];
        
        // Verify the state token
        if (!wp_verify_nonce($state, 'linkedin_auth')) {
            wp_die('Invalid state token.');
        }
        
        $code = $_GET['code'];
        $client_id = '78gi3jgzf0umlf';
        $client_secret = '6Bc7tdJoudk0QgPP';
        $redirect_uri = 'https://your-site.com/linkedin-callback';
        
        // Exchange authorization code for access token
        $token_response = wp_remote_post('https://www.linkedin.com/oauth/v2/accessToken', array(
            'body' => array(
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirect_uri,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
            ),
        ));
        
        $token_body = json_decode(wp_remote_retrieve_body($token_response), true);
        $access_token = $token_body['access_token'];
        
        // Fetch user profile data from LinkedIn
        $profile_response = wp_remote_get('https://api.linkedin.com/v2/me', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
            ),
        ));
        
        $profile_body = json_decode(wp_remote_retrieve_body($profile_response), true);
        $email_response = wp_remote_get('https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
            ),
        ));
        
        $email_body = json_decode(wp_remote_retrieve_body($email_response), true);
        $email = $email_body['elements'][0]['handle~']['emailAddress'];
        
        // Login or register the user
        if (email_exists($email)) {
            $user = get_user_by('email', $email);
            wp_set_auth_cookie($user->ID);
        } else {
            $user_id = wp_create_user($profile_body['localizedFirstName'], wp_generate_password(), $email);
            wp_set_auth_cookie($user_id);
        }
        
        // Redirect the user to the home page
        wp_redirect(home_url());
        exit;
    }
}

add_action('init', 'handle_linkedin_callback');
?>


