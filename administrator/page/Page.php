<?php
    class Page {
        public static function to_login_if_no_session_was_created($user_id, $user_role) {
            if (!($_SESSION['user_id'] === $user_id && $_SESSION['user_role'] === "administrator")) {
                header('Location: /eosi/');
                exit;
            }
        }
        
        public static function get_head() : string {
            return <<<HTML
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="shortcut icon" href="/eosi/website-icon.svg" type="image/x-icon">
                <link href="https://api.fontshare.com/v2/css?f[]=satoshi@500&display=swap" rel="stylesheet">
                <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css'
                integrity='sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=='
                crossorigin='anonymous' />
                <link rel="stylesheet" href="/eosi/administrator/page/style.css">
                <script src="/eosi/administrator/page/main.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" 
                integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer">
                </script>
                <script src="/eosi/administrator/page/notifications/manager.js"></script>
            HTML;
        }

        public static function get_header($userpath = null) : string {
            return <<<HTML
                <div id="global-search-and-notifications-and-profile-and-logout">
                    <div id="global-search">
                        <button type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                        <input id="global-search-input" type="text" placeholder="Rechercher Plus Rapidement ICI" autocomplete="off" list="search-list-options">
                        <datalist id="search-list-options"></datalist>
                    </div>
                    <div id="notifications">
                        <button type="button" title="Notifications" id="notifications-opener">
                            <i class="fa-regular fa-bell" id="notifications-icon"></i>
                            <small id="notifications-counter"></small>
                        </button>
                        <nav id="notifications-dropdown">
                            <ul id="notifications-holder">
                            </ul>
                        </nav>
                    </div>
                    <hr>
                    <div id="profile-and-logout">
                        <button type="button"><i class="fa-solid fa-bars"></i></button>
                        <nav id="profile-and-logout-dropdown">
                            <ul>
                                <li>
                                    <i class="fa-solid fa-user"></i>
                                    <a href="/eosi/administrator/read/profile.php">Mon Profile</a>
                                </li>
                                <li>
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <a href="/eosi/authentification/logout.php">Déconnexion</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <nav id="userpath"><ul>$userpath</ul></nav>
            HTML;
        }

        public static function get_aside() : string {
            return <<<HTML
                <nav id="navbar">
                    <ul id="first-navbar">
                        <li>
                            <i class="fa-solid fa-chart-line"></i>
                            <a href="/eosi/administrator/read/dashboard.php">Le Tableau de Board</a>
                        </li>
                        <li id="exception-li-aside">
                            <button>
                                <i class="fa-solid fa-envelope"></i>Les Courriers<i class="fa-solid fa-chevron-down fa-xs"></i>
                            </button>
                            <ul>
                                <li>
                                    <i class="fa-solid fa-share"></i>
                                    <a href="/eosi/administrator/read/outgoing-mails.php">
                                        Courriers de Départ
                                    </a>
                                </li>
                                <li>
                                    <i class="fa-solid fa-reply"></i>
                                    <a href="/eosi/administrator/read/incoming-mails.php">
                                        Courriers à Arrivée
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul id="last-navbar">
                        <li>
                            <i class="fa-solid fa-users"></i>
                            <a href="/eosi/administrator/read/employees.php">Les Employés</a>
                        </li>
                        <li>
                            <i class="fa-solid fa-users-viewfinder"></i>
                            <a href="/eosi/administrator/read/divisions.php">Les Divisions</a>
                        </li>
                    </ul>
                </nav>
            HTML;
        }
    }
?>