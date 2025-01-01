document.addEventListener("DOMContentLoaded", () => {
  if (!userLoggedIn) {
    const modal = document.getElementById("loginModal");
    const playAnonymous = document.getElementById("playAnonymous");
    const goToLogin = document.getElementById("goToLogin");

    // Show the modal
    modal.classList.add("visible");

    // Handle "Play as Anonymous"
    playAnonymous.addEventListener("click", () => {
      modal.classList.remove("visible");
      // Redirect to play as anonymous if needed
    });

    // Handle "Login"
    goToLogin.addEventListener("click", () => {
      window.location.href = "/login";
    });
  }

  const validateGridButton = document.getElementById("validateGrid");

  validateGridButton.addEventListener("click", async () => {
    const solvedModal = document.getElementById("solvedModal");
    const notSolvedModal = document.getElementById("notSolvedModal");
    const solvedClose = document.getElementById("solvedClose");
    const notSolvedClose = document.getElementById("notSolvedClose");
    const form = document.getElementById("playGridForm");
    const inputs = form.querySelectorAll("input:not([disabled])");
    const answers = [];

    // Close solved modal
    solvedClose.addEventListener("click", () => {
      solvedModal.classList.remove("visible");
      window.location.reload();
    });

    // Close not solved modal
    notSolvedClose.addEventListener("click", () => {
      notSolvedModal.classList.remove("visible");
    });

    // Collect answers from all enabled inputs
    inputs.forEach((input) => {
      const row = input.dataset.row;
      const col = input.dataset.col;
      const value = input.value.trim().toUpperCase();

      answers.push({ row: parseInt(row), col: parseInt(col), value });
    });

    // Prepare payload
    const payload = {
      gameId: gameId,
      answers,
    };

    try {
      const response = await fetch("/api/grids/validate", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (result.status === "success") {
        solvedModal.classList.add("visible");
      } else {
        notSolvedModal.classList.add("visible");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An unexpected error occurred.");
    }
  });

  const saveProgressButton = document.getElementById("saveProgress");

  saveProgressButton.addEventListener("click", async () => {
    const form = document.getElementById("playGridForm");
    const inputs = form.querySelectorAll("input:not([disabled])");
    const progress = [];

    // Collect data from all enabled inputs
    inputs.forEach((input) => {
      const row = input.dataset.row;
      const col = input.dataset.col;
      const value = input.value.trim().toUpperCase();

      progress.push({
        row: parseInt(row),
        col: parseInt(col),
        value: value || "+", // Fill empty cells with '+'
      });
    });

    // Prepare payload
    const payload = {
      game_id: gameId,
      progress,
    };

    try {
      const response = await fetch("/api/grids/saveProgress", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (response.ok) {
        console.log("Progress saved successfully:", result.message);
      } else {
        console.error("Error saving progress:", result.error);
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });
});
