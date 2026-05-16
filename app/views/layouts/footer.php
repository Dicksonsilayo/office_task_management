        </div>

    </div>

</div>
<script>


function fetchNotifications() {

    fetch("index.php?page=api_notifications")
        .then(res => res.json())
        .then(data => {

            // update badge
            const badge = document.getElementById("notif-count");

            if (badge) {
                badge.innerText = data.unread;

                if (data.unread == 0) {
                    badge.style.display = "none";
                } else {
                    badge.style.display = "inline-block";
                }
            }

        })
        .catch(err => console.log("Notification error:", err));
}

// run every 5 seconds
setInterval(fetchNotifications, 5000);





function toggleNotifDropdown() {
    const box = document.getElementById("notifDropdown");

    if (!box) return;

    box.style.display = (box.style.display === "block") ? "none" : "block";
}

// close dropdown when clicking outside
document.addEventListener("click", function (event) {

    const wrapper = document.querySelector(".notif-wrapper");

    if (!wrapper) return;

    const dropdown = document.getElementById("notifDropdown");

    if (dropdown && !wrapper.contains(event.target)) {
        dropdown.style.display = "none";
    }
});
</script>

</body>
</html>