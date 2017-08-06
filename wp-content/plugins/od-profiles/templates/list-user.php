<?php
/**
 * Template Name: Dashboard user list item.
 *
 * This is the template used to house dashboard users in the current users member lists.
 *
 *
 * @package ondec_custom_theme
 */
?>

<li class="decmember-<?php echo esc_attr( $user_data->ID ); ?>">
    <a href='<?php echo esc_attr( $user_data->roles[0] ) . '/' . esc_attr( $user_data->user_login ); ?>'>
        <div class="dec-user">
            <?php echo esc_html( $user_data->display_name ); ?>
        </div>
        <div class="dec-image">
            <?php echo wp_kses_post( get_wp_user_avatar($user_data->ID, 130) ); ?>
        </div>
    </a>
</li>

<input id="<?php echo esc_attr( $user_data->user_login ); ?>" class="decremove" type="button" value="remove">
