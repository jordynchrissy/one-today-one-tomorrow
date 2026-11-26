
(() => {

    const bingo = document.getElementById("bingo");

    if (!bingo) return;

    const squares = bingo.querySelectorAll(".bingo-square");
    const count = document.getElementById("bingo-count");
    const wins = document.getElementById("bingo-wins");
    const progress = document.getElementById("bingo-progress-fill");
    const celebration = document.getElementById("bingo-celebration");
    const resetButton = document.getElementById("bingo-reset-btn");

    // The centre square is always completed
    const completedSquares = new Set([12]);
    let previousWinningLines = new Set();


const activityPool = [

    // Little everyday joys
    "Watch the sunset",
    "Enjoy a favourite snack",
    "Notice something pretty",
    "Wear something colourful",
    "Enjoy some fresh air",
    "Notice the clouds",
    "Watch the sunrise",
    "Listen to the rain",
    "Enjoy a warm drink",
    "Look at the stars",
    "Notice a little flower",
    "Enjoy a favourite meal",
    "Open the curtains",
    "Find something that makes you smile",
    "Enjoy a slow morning",

    // Creative little moments
    "Doodle something",
    "Take a cute photo",
    "Make something creative",
    "Draw a little flower",
    "Create a mood board",
    "Make a paper heart",
    "Try a new colour combination",
    "Write a tiny poem",
    "Design something fun",
    "Decorate a notebook",
    "Draw a little cartoon",
    "Make a cute wallpaper",
    "Create a collage",
    "Try a new art style",
    "Make a little bookmark",

    // Music and entertainment
    "Listen to a favourite song",
    "Discover a new song",
    "Watch a comfort show",
    "Play a favourite game",
    "Make a little playlist",
    "Listen to an old favourite",
    "Watch a funny video",
    "Watch a favourite movie",
    "Sing along to a song",
    "Discover a new artist",
    "Read a few pages",
    "Listen to a podcast",
    "Revisit a childhood cartoon",
    "Listen to a movie soundtrack",
    "Dance to a favourite song",

    // Kindness and connection
    "Send a kind message",
    "Compliment someone",
    "Write a thank-you note",
    "Share a funny memory",
    "Talk to someone you trust",
    "Give someone encouragement",
    "Share a favourite song",
    "Make someone smile",
    "Spend time with a friend",
    "Do a little act of kindness",
    "Appreciate something about yourself",
    "Reconnect with a friend",
    "Share something interesting",
    "Ask someone about their day",
    "Celebrate a little accomplishment",

    // Gentle moments
    "Drink some water",
    "Take a little break",
    "Stretch gently",
    "Enjoy a quiet moment",
    "Revisit a happy memory",
    "Learn something new",
    "Do something just for fun",
    "Write a little note",
    "Rest somewhere comfortable",
    "Put on something cosy",
    "Enjoy a screen-free moment",
    "Write down a favourite quote",
    "Make time for a hobby",
    "Notice something you appreciate",
    "Give yourself time to rest"

];


function generateNewCard() {

    const shuffled = [...activityPool];

    // Shuffle using the Fisher-Yates algorithm
    for (let i = shuffled.length - 1; i > 0; i--) {

        const j = Math.floor(Math.random() * (i + 1));

        [shuffled[i], shuffled[j]] =
            [shuffled[j], shuffled[i]];

    }

    // Select 24 unique activities
    const newTasks = shuffled.slice(0, 24);

    // Insert the free space in the centre
    newTasks.splice(12, 0, "FREE SPACE ♡");

    // Update the bingo card
    squares.forEach((square, index) => {

        square.querySelector(".bingo-task").textContent =
            newTasks[index];

    });

}


    // All possible winning combinations
    const winningLines = [

        // Horizontal rows
        [0, 1, 2, 3, 4],
        [5, 6, 7, 8, 9],
        [10, 11, 12, 13, 14],
        [15, 16, 17, 18, 19],
        [20, 21, 22, 23, 24],

        // Vertical columns
        [0, 5, 10, 15, 20],
        [1, 6, 11, 16, 21],
        [2, 7, 12, 17, 22],
        [3, 8, 13, 18, 23],
        [4, 9, 14, 19, 24],

        // Diagonals
        [0, 6, 12, 18, 24],
        [4, 8, 12, 16, 20]

    ];


function launchConfetti(amount = 70) {

    // Respect the user's motion preferences
    if (window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    ).matches) return;

    const colours = [
        "#ee3294",
        "#f58a18",
        "#50bdb4",
        "#f6ce7a",
        "#d8c5e9",
        "#ffb6d2"
    ];

    const container = document.createElement("div");

    container.className = "bingo-confetti";
    container.setAttribute("aria-hidden", "true");

    for (let i = 0; i < amount; i++) {

        const piece = document.createElement("span");

        piece.className = "confetti-piece";

        piece.style.left = `${Math.random() * 100}%`;

        piece.style.backgroundColor =
            colours[Math.floor(Math.random() * colours.length)];

        piece.style.borderRadius =
            Math.random() > 0.5 ? "50%" : "2px";

        piece.style.setProperty(
            "--duration",
            `${2 + Math.random() * 2}s`
        );

        piece.style.setProperty(
            "--delay",
            `${Math.random() * 0.5}s`
        );

        piece.style.setProperty(
            "--drift",
            `${Math.random() * 300 - 150}px`
        );

        piece.style.setProperty(
            "--rotation",
            `${Math.random() * 1440 - 720}deg`
        );

        container.appendChild(piece);

    }

    document.body.appendChild(container);

    // Remove confetti after the animation
    setTimeout(() => {
        container.remove();
    }, 5000);

}


    function updateBingo() {

        // Update the appearance of every square
        squares.forEach(square => {

            const index = Number(square.dataset.square);
            const isCompleted = completedSquares.has(index);

            square.classList.toggle("completed", isCompleted);

            square.setAttribute(
                "aria-pressed",
                String(isCompleted)
            );

        });

        // Exclude the free space from the counter
        const totalCompleted = completedSquares.size - 1;

        count.textContent =
            `${totalCompleted} of 24 little joys`;

        // Update progress bar
        progress.style.width =
            `${(totalCompleted / 24) * 100}%`;

        // Find completed bingo lines
        const completedLines = winningLines.filter(line =>
            line.every(index => completedSquares.has(index))
        );

        wins.textContent = `${completedLines.length} BINGO!`;


// Check whether a new bingo line was completed

const currentWinningLines = new Set(
    completedLines.map(line => line.join("-"))
);

const hasNewBingo = [...currentWinningLines].some(
    line => !previousWinningLines.has(line)
);

if (hasNewBingo) {

    if (totalCompleted === 24) {

        // Extra confetti for a full house
        launchConfetti(150);

    } else {

        launchConfetti(70);

    }

}

previousWinningLines = currentWinningLines;


        // Highlight winning squares
        const winningSquares = new Set(completedLines.flat());

        squares.forEach(square => {

            const index = Number(square.dataset.square);

            square.classList.toggle(
                "winning-square",
                winningSquares.has(index)
            );

        });

        // Display celebration message
        celebration.hidden = completedLines.length === 0;

        if (totalCompleted === 24) {

            celebration.textContent =
                "✦ FULL HOUSE! A whole card of little joys! ♡";

        } else {

            celebration.textContent =
                "✦ BINGO! Here's to the little things! ✦";

        }

    }

    // Toggle individual squares
    squares.forEach(square => {

        square.addEventListener("click", () => {

            const index = Number(square.dataset.square);

            if (index === 12) return;

            if (completedSquares.has(index)) {

                completedSquares.delete(index);

            } else {

                completedSquares.add(index);

            }

            updateBingo();

        });

    });



    
// Start a fresh card
resetButton.addEventListener("click", () => {

    const confirmed = confirm(
        "Ready for a fresh little adventure? This will replace your current bingo card."
    );

    if (!confirmed) return;

    const grid = bingo.querySelector(".bingo-grid");

    const reducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    ).matches;

    function refreshCard() {
        completedSquares.clear();
        completedSquares.add(12);

        generateNewCard();
        updateBingo();
    }

    if (reducedMotion) {
        refreshCard();
        return;
    }

    resetButton.disabled = true;

    squares.forEach(square => {
        square.disabled = true;
    });

    grid.classList.add("shuffling");

    setTimeout(() => {

        refreshCard();

    }, 325);

    setTimeout(() => {

        grid.classList.remove("shuffling");

        resetButton.disabled = false;

        squares.forEach(square => {
            square.disabled =
                Number(square.dataset.square) === 12;
        });

    }, 650);

});

updateBingo();

})();

