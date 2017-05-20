<?php
/**
 * Template for messages.
 */

?>

<div class="message-count">
    <h3>Messages ( <?php echo count( $current_messages ); ?> ) | Unread ( <span id="unread-count"><?php echo (int) $unread_count; ?></span> )</h3>
</div>

<ul id="message_list">
	<?php foreach ( array_reverse( $current_messages ) as $message ) :
        $message_user_info = isset( $message['user'] ) ? get_userdata( (int) $message['user'] ) : '';
        $date = isset( $message['message_date'] ) && '' !== $message['message_date'] ? sanitize_text_field( wp_unslash( $message['message_date'] ) ) : '';
        $message_copy = isset( $message['message'] ) && '' !== $message['message'] ? sanitize_text_field( wp_unslash( $message['message'] ) ) : '';
        $message_id = isset( $message['messageid'] ) && '' !== $message['messageid'] ? sanitize_text_field( wp_unslash( $message['messageid'] ) ) : '';
        $message_status = isset( $message['read_status'] ) && '' !== $message['read_status'] ? sanitize_text_field( wp_unslash( $message['read_status'] ) ) : '';
        $read_message = '' !== $message_status && 'read' === $message_status ? 'read-message' : '';
	?>
	<li class="message-item">
		<div class="message-date">
			<?php echo esc_html( date("F j, Y h:i A", $date ) ); ?>
		</div>
		<div class="user-message-info">
			<a href="<?php echo esc_url( $profile_pages->get_user_profile_url( $message_user_info->ID ) ); ?>">

				<div class="message-user">
					<?php echo esc_html( $message_user_info->display_name ); ?>
				</div>
				<div class="prof-image-message">
					<?php echo wp_kses_post( get_wp_user_avatar( $message_user_info->ID, 36 ) ); ?>
				</div>
			</a>
		</div>
		<div class="user-message">
			<div class="message-wrapper">
				<?php echo esc_html( substr( $message_copy, 0, 80 ) . '...' ); ?>
			</div>
			<div class="view-message">
				<input class="view-button <?php echo esc_attr( $read_message ); ?>" id="<?php echo esc_attr( $message_id ); ?>" type="button" value="<?php echo esc_html( $message['read_status'] ); ?>">
			</div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>
