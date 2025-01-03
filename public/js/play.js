document.addEventListener("DOMContentLoaded", () => {
  if (!userLoggedIn) {
    const modal = document.getElementById("loginModal");
    const playAnonymous = document.getElementById("playAnonymous");
    const goToLogin = document.getElementById("goToLogin");

    modal.classList.add("visible");

    playAnonymous.addEventListener("click", () => {
      modal.classList.remove("visible");
    });

    goToLogin.addEventListener("click", () => {
      window.location.href = "/login";
    });
  }

  const validateGridButton = document.getElementById("validateGrid");
  const crosswordGridForm = document.getElementById("playGridForm");

  validateGridButton.addEventListener("click", async () => {
    const solvedModal = document.getElementById("solvedModal");
    const notSolvedModal = document.getElementById("notSolvedModal");
    const solvedClose = document.getElementById("solvedClose");
    const notSolvedClose = document.getElementById("notSolvedClose");
    const form = document.getElementById("playGridForm");
    const inputs = form.querySelectorAll("input:not([disabled])");
    const answers = [];

    solvedClose.addEventListener("click", () => {
      solvedModal.classList.remove("visible");
      window.location.reload();
    });

    notSolvedClose.addEventListener("click", () => {
      notSolvedModal.classList.remove("visible");
    });

    inputs.forEach((input) => {
      const row = input.dataset.row;
      const col = input.dataset.col;
      const value = input.value.trim().toUpperCase();

      answers.push({ row: parseInt(row), col: parseInt(col), value });
    });

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

    inputs.forEach((input) => {
      const row = input.dataset.row;
      const col = input.dataset.col;
      const value = input.value.trim().toUpperCase();

      progress.push({
        row: parseInt(row),
        col: parseInt(col),
        value: value || "+",
      });
    });

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
        window.location.href = "/saved-grids";
      } else {
        console.error("Error saving progress:", result.error);
        alert("Ooops! Your Prress was not saved!");
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });

  if (!crosswordGridForm) return;

  const validateInput = (input) => {
    const correctValue = input.dataset.value
      ? atob(input.dataset.value).toUpperCase()
      : "";
    const enteredValue = input.value.trim().toUpperCase();

    if (enteredValue === "") {
      input.style.backgroundColor = "white";
      input.style.color = "black";
    } else if (enteredValue === correctValue) {
      input.style.backgroundColor = "green";
      input.style.color = "white";
    } else {
      input.style.backgroundColor = "red";
      input.style.color = "white";
    }
  };

  const inputs = crosswordGridForm.querySelectorAll(
    "input[type='text']:not([disabled])"
  );
  inputs.forEach((input) => validateInput(input));

  crosswordGridForm.addEventListener("input", (event) => {
    const input = event.target;

    if (input.tagName.toLowerCase() === "input") {
      validateInput(input);
    }
  });
});
