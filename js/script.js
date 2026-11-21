
// Homepage mood selector
// Frontend-only: no database saving yet.

const moodButtons = document.querySelectorAll('.mood-btn');
const moodResponse = document.getElementById('mood-response');

if (moodResponse) {
    moodButtons.forEach(button => {
        button.addEventListener('click', () => {

            moodButtons.forEach(other => {
                other.classList.remove('selected');
                other.setAttribute('aria-pressed', 'false');
            });

            button.classList.add('selected');
            button.setAttribute('aria-pressed', 'true');

            moodResponse.textContent =
                `You're feeling ${button.dataset.mood.toLowerCase()} today. Thank you for checking in with yourself. ♡`;
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
