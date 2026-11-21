
// =========================================
// ONE TODAY, ONE TOMORROW
// Homepage Interactivity
// =========================================

// Select all mood buttons
const moodButtons = document.querySelectorAll(".mood-btn");

// Find the message displayed below the buttons
const moodResponse = document.getElementById("mood-response");

// Respond when a user selects a mood
moodButtons.forEach(button => {

    button.addEventListener("click", () => {

        // Remove previous selections
        moodButtons.forEach(btn => {
            btn.classList.remove("selected");
            btn.setAttribute("aria-pressed", "false");
        });

        // Highlight the selected mood
        button.classList.add("selected");
        button.setAttribute("aria-pressed", "true");

        // Get the selected mood
        const selectedMood = button.dataset.mood;

        // Display a personalized message
        moodResponse.textContent =
            `You're feeling ${selectedMood.toLowerCase()} today. Thank you for checking in with yourself. ♡`;

    });

});
