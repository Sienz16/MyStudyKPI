<?php
echo '<ul>
        <li>
            <a onclick="toggleNav()">
                <span class="nav-icon" id="toggleBtn1"><ion-icon name="list-outline"></ion-icon></span>
                <span class="nav-icon" id="toggleBtn2"><ion-icon name="school-outline"></ion-icon></span>
                <span class="nav-title">Dashboardz</span>
            </a>
        </li>
        <li class="' . ($activePage == 'home' ? 'active' : '') . '">
            <a href="home.php">
                <span class="nav-icon"><ion-icon name="home-outline"></ion-icon></span>
                <span class="nav-title">Home</span>
                <span class="nav-arrow"><ion-icon name="caret-back-outline"></ion-icon></span>
            </a>
        </li>
        <li class="' . ($activePage == 'profile' ? 'active' : '') . '">
            <a href="profile.php">
                <span class="nav-icon"><ion-icon name="person-outline"></ion-icon></span>
                <span class="nav-title">Profile</span>
                <span class="nav-arrow"><ion-icon name="caret-back-outline"></ion-icon></span>
            </a>
        </li>
        <li class="' . ($activePage == 'kpi' ? 'active' : '') . '">
            <a href = "my_kpi.php">
                <span class = "nav-icon"><ion-icon name="bar-chart-outline"></ion-icon></span>
                <span class = "nav-title">KPI Indicator</span>
                <span class = "nav-arrow"><ion-icon name="caret-back-outline"></ion-icon></span>
            </a>
        </li>
        <li class="' . ($activePage == 'activities' ? 'active' : '') . '">
            <a href = "my_activities.php">
                <span class = "nav-icon"><ion-icon name="bicycle-outline"></ion-icon></span>
                <span class = "nav-title">List of Activities</span>
                <span class = "nav-arrow"><ion-icon name="caret-back-outline"></ion-icon></span>
            </a>
        </li>
        <li class="' . ($activePage == 'challenge' ? 'active' : '') . '">
            <a href = "my_challenge.php">
                <span class = "nav-icon"><ion-icon name="golf-outline"></ion-icon></span>
                <span class = "nav-title">Challenge</span>
                <span class = "nav-arrow"><ion-icon name="caret-back-outline"></ion-icon></span>
            </a>
        </li>
        <li>
            <a style = "cursor: pointer" onclick = "confirmLogout()">
                <span class = "nav-icon"><ion-icon name="log-out-outline"></ion-icon></span>
                <span class = "nav-title">Logout</span>
                <span class = "nav-arrow"><ion-icon name="caret-back-outline"></ion-icon></span>
            </a>
        </li> 
    </ul>';
?>