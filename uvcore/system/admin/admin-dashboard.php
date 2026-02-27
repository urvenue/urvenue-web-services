<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div id="uvs-admin-dashboard" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['dashboard']; */ echo esc_attr( $uvs_admin_optstabs_state['dashboard'] ); ?>">
    <div class="uvs-admin-opt-title">Dashboard</div>
    <div class="uvs-admin-opt-descr">Welcome to UrVenue Integrations, Make your configurations.</div>
    <div class="uvs-admin-opt-space"></div>

    <ul class="uvs-admin-iconboxlist">
        <li>
            <a href="#events-global">
                <i class="uwsicon-calendar-1"></i>
                <span>Events</span>
            </a>
        </li>
        <!--<li>
            <a href="#artists-artistpage">
                <i class="uwsicon-group"></i>
                <span>Artists</span>
            </a>
        </li>-->
        <li>
            <a href="#venues">
                <i class="uwsicon-th-list"></i>
                <span>Venues</span>
            </a>
        </li>
        <li>
            <a href="#ui-color-palette">
                <i class="uwsicon-palette"></i>
                <span>UI Colors</span>
            </a>
        </li>
        <li>
            <a href="#seo">
                <i class="uwsicon-search-1"></i>
                <span>SEO</span>
            </a>
        </li>
    </ul>
</div>