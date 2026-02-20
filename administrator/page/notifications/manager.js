document.addEventListener("DOMContentLoaded", function() {
    /**doc
    *    The Best Notification Code I've MADE 
    *    Very Cooooooooooool
    */

    var xhrForNotifications        = new XMLHttpRequest();
    var xhrForNotificationsLength  = new XMLHttpRequest();
    var xhrToClearNotifications    = new XMLHttpRequest();
    var notificationsDropdown      = document.querySelector("#notifications-dropdown");
    var notifications              = document.querySelector("#notifications-holder");
    var notificationsLength        = document.querySelector("#notifications-counter");
    var notificationsOpener        = document.querySelector("#notifications-opener");
    var notificationsIcon          = document.querySelector("#notifications-icon");
    var data                       = null;

    function fetchNotifications() {
        var notificationsCercle = setInterval(() => {
            // Notifications Length
            xhrForNotificationsLength.open(
                "get", "/eosi/administrator/page/notifications/notifications.php?query=fetch-notifications-length"
            );
            xhrForNotificationsLength.send();
            xhrForNotificationsLength.onload = () => {
                data = xhrForNotificationsLength.response.trim();
                notificationsLength.innerHTML = data;
                if (data > 0) {
                    notificationsIcon.classList.add("fa-shake");
                } else {
                    notificationsIcon.classList.remove("fa-shake");
                }
            }
    
            // Notifications
            xhrForNotifications.open("get", "/eosi/administrator/page/notifications/notifications.php?query=fetch-notifications");
            xhrForNotifications.send();
            xhrForNotifications.onload = () => {
                data = xhrForNotifications.response.trim();
                notifications.innerHTML = data;
                if (notificationsLength.textContent == 0) {
                    notifications.innerHTML = 
                    `<span id="empty-notifications-message">
                        Aucun Notifications
                    </span>`;
                }
            }
    
            notificationsOpener.onclick = () => {
                if (notificationsDropdown.style.visibility == "visible") {
                    clearInterval(notificationsCercle);
                    notificationsIcon.classList.remove("fa-shake");
                    setInterval(() => {
                        // Notifications Length
                        xhrForNotificationsLength.open(
                            "get", "/eosi/administrator/page/notifications/notifications.php?query=fetch-notifications-length"
                        );
                        xhrForNotificationsLength.send();
                        xhrForNotificationsLength.onload = () => {
                            data = xhrForNotificationsLength.response.trim();
                            notificationsLength.innerHTML = data;
                        }
                        // Notifications
                        xhrForNotifications.open("get", "/eosi/administrator/page/notifications/notifications.php?query=fetch-notifications");
                        xhrForNotifications.send();
                        xhrForNotifications.onload = () => {
                            data = xhrForNotifications.response.trim();
                            notifications.innerHTML = data;
                            if (notificationsLength.textContent == 0) {
                                notifications.innerHTML = 
                                `<span id="empty-notifications-message">
                                    Aucun Notifications
                                </span>`;
                            }
                        }
                    }, 1000);
                } else {
                    xhrToClearNotifications.open("get", "/eosi/administrator/page/notifications/notifications.php?query=clear");
                    xhrToClearNotifications.send();
                    fetchNotifications();
                }
            }
        }, 1000);
    }
    fetchNotifications();
});