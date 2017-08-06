<?php
/**
 * Template Name: Dashboard Profile
 *
 * This is the template used to house dashboard for the current user.
 *
 *
 * @package ondec_custom_theme
 */

session_start();

global $current_user, $wp_roles, $dash_profile;

get_header();

$dashboard_info = $dash_profile->get_dashboard_usermeta( $current_user->ID );

?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="user-wrapper">
            <div class="user-information">
                <h2><?php echo esc_html( $current_user->display_name ); ?>'s Profile</h2>

                <?php echo wp_kses_post( get_wp_user_avatar( $current_user->ID, 96 ) ); ?>

                <h3>current dec status:</h3>

                <input class="<?php echo 'ondec' === $dashboard_info['decstatus'] ? 'currently-ondec' : 'currently-offdec'; ?>" id="submit-decstatus" type="button" value="Currently <?php echo esc_html( $dashboard_info['decstatus'] ); ?>">

                <p>
                    <a href="/<?php echo esc_attr( $current_user->roles[0] ); ?>/<?php echo esc_attr( $current_user->user_login ); ?>">view profile</a>
                    |
                    <a href="edit-profile-info">edit profile</a>
                </p>

                <div id="userstatuswrapper" >
                    <input type="text" placeholder="what's up?" id="userstatus" value="<?php echo esc_html( $dashboard_info['userstatus'] ); ?>">
                    <input id="statussubmit" type="button" value="update">
                </div>
            </div>
            <div class="user-tools">
                <h2>Your Tools</h2>

                <?php if ( 'client' !== $current_user->roles[0] && shortcode_exists( 'od-appointments' ) ) {
                    do_shortcode( '[od-appointments]' );
                } ?>
            </div>
        </div>
        <div class="member-lists">
            <div class="list-sections">
		        <?php for ( $x = 0; $x < 3; $x ++ ) : ?>
                    <div class="list-section-wrapper section-<?php echo (int) $x; ?>">
                        <h3 class="list-title" >
                            <div class="list-count"></div>
                        </h3>
                        <div class="od-my-list single-member-list">
                            <ul></ul>
                        </div>
                    </div>
		        <?php endfor; ?>
            </div>
        </div>
        <div class="mymessages">
            <div id="load_messages_container"></div>
        </div>
        <div id="messagepop-wrapper">
            <div class="messageOverlay"></div>
            <div class="vertical-offset">
                <div class="messageBox">
                    <div class="view-message" >
                        <h2 id="messagepop-user"></h2>
                        <h3 id="messagepop-message"></h3>
                    </div>
                    <a href="#" id="reply-message" >reply</a>
                    <a id="closeMessage" >close</a>
                </div>
            </div>
        </div>
    </main>
</div>
<?php
get_footer();
