<div class="app-maker-wrapper">
    
    <?php 
    
    $current_date = strtotime('now');
    
    $available_apps = get_user_meta($current_user->ID, 'current_appointments', true);
    
    $current_month = ;
    $current_year = ;
    
    foreach($available_apps[$current_year][$current_month][$current_day] as $available_app) : ?>
    
        <li class="app_time">
            
            <input type="button" value="<?php echo $available_app['time-start'] . "-" . $available_app['time-end']; ?>">
        </li>
             
    <?php endforeach; ?>
    
</div>