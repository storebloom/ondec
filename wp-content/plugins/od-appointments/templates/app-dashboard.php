<?php
/**
 * Template for the appointment portion of the dashboard.
 */

if ( 'false' === $app_settings ) : ?>
	<button id="open-app-settings">Enable Appointments</button>

	<div class="appointment-options">
	</div>
	<?php else: ?>
	<div class="appointment-options">
		<?php echo $this->od_app_settings(); ?>

			<h3>Today's Appointments</h3>

		<?php echo wp_kses_post( $this->get_todays_appointments() ); ?>

		<button id="open-app-calendar">View Appointment Calendar</button>

		<div class="my-appointments">
			<div class="year-picker">
				<label for="app-month-picker">Month:</label><input name="app-month-picker" type="number" class="current-app-month" min="01" max="12" value="<?php echo date('m'); ?>"/>
				<label for="app-year-picker">Year:</label><input name="app-year-picker" type="number" class="current-app-year" value="<?php echo date('Y'); ?>" min="<?php echo date('Y'); ?>"/>
			</div>
			<div class="calendar-wrapper"></div>
		</div>
		<div class="pop-notification" id="approval-appointment">
			<h3>Approve Client Appointment?</h3>

			<p>Send a message to the client along with your choice.(optional)</p>

			<textarea id="app-approval-message"></textarea>

			<button id="approve-appointment">Approve</button>
			<button id="close-appointment">Close</button>
			<button id="deny-appointment">Deny</button>
		</div>
	</div>
<?php endif; ?>

