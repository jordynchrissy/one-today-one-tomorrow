

// ========================================
// OTOT — DAILY MOOD TRACKER
// ========================================

const moodCard = document.querySelector(".mood-card");

if (moodCard) {

    const moodButtons = moodCard.querySelectorAll(".mood-btn");
    const moodResponse = document.getElementById("mood-response");

    const isLoggedIn = moodCard.dataset.loggedIn === "true";
    const todayMood = moodCard.dataset.todayMood;
    const csrfToken = moodCard.dataset.csrfToken;

    const moodMessages = {
        happy: "Keep a little sunshine for tomorrow. ☀️",
        good: "Here's to the little things that make today lovely. 🌷",
        okay: "Not every day has to be extraordinary. ☁️",
        sad: "Give yourself a little kindness today. 🌧️",
        tired: "You deserve a moment to rest. 🌙"
    };

    // Highlight the selected mood
    function selectMood(mood) {

        moodButtons.forEach(button => {

            const selected = button.dataset.mood === mood;

            button.classList.toggle("selected", selected);
            button.setAttribute("aria-pressed", selected);

        });

        moodResponse.textContent = moodMessages[mood] || "";
    }

    // Restore today's saved mood
    if (todayMood) {
        selectMood(todayMood);
    }

    // Handle mood button clicks
    moodButtons.forEach(button => {

        button.addEventListener("click", async () => {

            const mood = button.dataset.mood;

            // Ask guests to log in
            if (!isLoggedIn) {

                moodResponse.textContent =
                    "Log in to save your little check-in. ♡";

                return;
            }

            // Prevent overlapping requests
            moodButtons.forEach(btn => btn.disabled = true);

            moodResponse.textContent =
                "Saving your little check-in...";

            try {

                const formData = new FormData();

                formData.append("mood", mood);
                formData.append("csrf_token", csrfToken);

                const response = await fetch("save-mood.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {

                    selectMood(mood);

                } else {

                    moodResponse.textContent = result.message;

                }

            } catch (error) {

                moodResponse.textContent =
                    "Something went wrong. Please try again. ♡";

                console.error(error);

            } finally {

                moodButtons.forEach(btn => btn.disabled = false);

            }

        });

    });

}



/* ========================================
   OTOT HEART NAVIGATION
======================================== */

const heartToggle = document.getElementById("heartToggle");
const heartMenu = document.getElementById("heartMenu");
const heartNav = document.querySelector(".heart-nav");

if (heartToggle && heartMenu && heartNav) {

    // Opens or closes the menu
    function setHeartMenu(isOpen) {
        heartNav.classList.toggle("open", isOpen);

        heartToggle.setAttribute(
            "aria-expanded",
            String(isOpen)
        );

        heartToggle.setAttribute(
            "aria-label",
            isOpen ? "Close navigation menu" : "Open navigation menu"
        );

        // Prevent keyboard access while hidden
        heartMenu.inert = !isOpen;
    }

    // Start with the menu closed
    setHeartMenu(false);

    // Click the heart to toggle the menu
    heartToggle.addEventListener("click", () => {
        const isOpen = heartNav.classList.contains("open");
        setHeartMenu(!isOpen);
    });

    // Close when clicking outside the menu
    document.addEventListener("click", (event) => {
        if (!heartNav.contains(event.target)) {
            setHeartMenu(false);
        }
    });

    // Close when Escape is pressed
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" &&
            heartNav.classList.contains("open")) {

            setHeartMenu(false);
            heartToggle.focus();
        }
    });
}
