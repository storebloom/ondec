<div class="my-appointments-wrapper">
    
    <table class="od-calendar">
        <th>
            <tr>
                <td>
                    <div class="day">
                        <div class="number">1</div>
                        
                            <div class="appointment-wrapper">
                                <?php if(current_appointment($current_selected_my, '1')) : ?>
                                
                                <?php $app_info = current_appointment($current_selected_my, '1'); ?>
                                
                                <ul>
                                <?php foreach($app_info as $app): ?>
                                
                                    <li class="app_user">

                                        <?php $app_user = get_userdata($app['user']); ?>
                                        <a class="view-app" id="$app['user']" href="/clients/<?php echo $app_user->login-name; ?>">
                                            <h3><?php echo $app_user->display_name; ?></h3>
                                            <div class="app-pic"><?php echo get_wp_user_avatar($app['user'], 30); ?></div>
                                            <div class="app-time"><?php echo $app['time-start'] . "-" . $app['time-end'];?></div>
                                            <div class="app-message"><?php echo $app['message']; ?></div>
                                        </a>
                                        
                                    </li>
                                <?php endforeach; endif; ?>
                                </ul>
                            </div>
                    </div>
                </td>
                <td>
                    <div class="day">
                        <div class="number">2</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">3</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">4</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">5</div>
                    </div>
                </td>
            </tr>
        </th>
        <th>
            <tr>
                <td>
                    <div class="day">
                        <div class="number">6</div>
                    </div>
                </td>
                <td>
                    <div class="day">
                        <div class="number">7</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">8</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">9</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">10</div>
                    </div>
                </td>
            </tr>
        </th>
        <th>
            <tr>
                <td>
                    <div class="day">
                        <div class="number">11</div>
                    </div>
                </td>
                <td>
                    <div class="day">
                        <div class="number">12</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">13</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">14</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">15</div>
                    </div>
                </td>
            </tr>
        </th>
        <th>
            <tr>
                <td>
                    <div class="day">
                        <div class="number">16</div>
                    </div>
                </td>
                <td>
                    <div class="day">
                        <div class="number">17</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">18</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">19</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">20</div>
                    </div>
                </td>
            </tr>
        </th>
        <th>
            <tr>
                <td>
                    <div class="day">
                        <div class="number">21</div>
                    </div>
                </td>
                <td>
                    <div class="day">
                        <div class="number">22</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">23</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">24</div>
                    </div>
                </td><td>
                    <div class="day">
                        <div class="number">25</div>
                    </div>
                </td>
            </tr>
        </th>
        <th>
            <tr>
                <td>
                    <div class="day">
                        <div class="number">26</div>
                    </div>
                </td>
                <td>
                    <div class="day">
                        <div class="number">27</div>
                    </div>
                </td>
                <td>
                    <div class="day">
                        <div class="number">28</div>
                    </div>
                </td>
                <td>
                    <div class="day">
                        <div class="number">29</div>
                    </div>
                </td>
                <td>
                    <div class="day">
                        <div class="number">30</div>
                    </div>
                </td>
            </tr>
        </th>
        <th>
            <tr style="display:<?php echo isset($month_count) ? $month_count : "none"; ?>;">
                <td>
                    <div class="day">
                        <div class="number">31</div>
                    </div>
                </td>
            </tr>
        </th>
    </table>
</div>