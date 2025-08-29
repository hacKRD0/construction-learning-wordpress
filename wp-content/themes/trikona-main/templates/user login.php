<?php
/* Template Name: Custom Login Page */
if ( is_user_logged_in() ) {
    wp_redirect( home_url() ); // Redirect logged-in users to the homepage
    exit;
}
get_header(); // Include the header template
?>

<div class="login-page">
    <div class="login-box">
        <h1 class="login-title">Welcome Back!</h1>
        <p class="login-subtitle">Login to access your account</p>
        
        <?php 
        // Display WordPress login form
        $args = array(
            'redirect'       => home_url(), // Redirect after login
            'form_id'        => 'loginform',
            'label_username' => __( 'Username or Email Address', 'text-domain' ),
            'label_password' => __( 'Password', 'text-domain' ),
            'label_remember' => __( 'Remember Me', 'text-domain' ),
            'label_log_in'   => __( 'Log In', 'text-domain' ),
            'remember'       => true
        );
        wp_login_form( $args );
        ?>

        <div class="login-links">
            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="forgot-password-link">Forgot your password?</a>
            <a href="<?php echo esc_url( home_url( '/register/' ) ); ?>" class="register-link">Create an account</a>
        </div>
    </div>
</div>

<style>
/* Inline CSS */
.login-page {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #FFC107, #FF5722);
    padding: 20px;
}

.login-box {
    background: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    max-width: 400px;
    width: 100%;
    text-align: center;
}

.login-title {
    font-size: 28px;
    color: #333;
    margin-bottom: 10px;
    font-weight: 700;
}

.login-subtitle {
    font-size: 16px;
    color: #777;
    margin-bottom: 30px;
}

#loginform {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

#loginform label {
    text-align: left;
    font-size: 14px;
    color: #555;
}

#loginform input[type="text"],
#loginform input[type="password"] {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
    outline: none;
    transition: all 0.3s ease;
}

#loginform input[type="text"]:focus,
#loginform input[type="password"]:focus {
    border-color: #FF5722;
    box-shadow: 0 0 5px rgba(255, 87, 34, 0.5);
}

#loginform input[type="submit"] {
    background: #FF5722;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
}

#loginform input[type="submit"]:hover {
    background: #E64A19;
}

.login-links {
    margin-top: 20px;
}

.login-links a {
    color: #FF5722;
    text-decoration: none;
    font-size: 14px;
    margin: 0 5px;
}

.login-links a:hover {
    text-decoration: underline;
}
</style>

<?php get_footer(); // Include the footer template ?>
