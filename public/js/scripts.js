document.addEventListener("DOMContentLoaded", () => {
  const generateGridButton = document.getElementById("generateGrid");
  const gridContainer = document.getElementById("crosswordGrid");
  const rowHints = document.getElementById("rowHints");
  const colHints = document.getElementById("colHints");
  const gridDataInput = document.getElementById("gridData");
  const hintDataInput = document.getElementById("hintData");

  generateGridButton.addEventListener("click", () => {
    const dimensionsInput = document.getElementById("dimensions").value;
    const [rows, cols] = dimensionsInput.split("x").map(Number);

    if (!rows || !cols || rows <= 0 || cols <= 0) {
      alert("Please enter valid dimensions (e.g., 5x5).");
      return;
    }

    gridContainer.innerHTML = "";
    rowHints.innerHTML = "";
    colHints.innerHTML = "";

    const table = document.createElement("table");

    const headerRow = document.createElement("tr");
    const emptyCorner = document.createElement("td");
    headerRow.appendChild(emptyCorner);
    for (let j = 0; j < cols; j++) {
      const headerCell = document.createElement("td");
      headerCell.textContent = String.fromCharCode(65 + j);
      headerRow.appendChild(headerCell);
    }
    table.appendChild(headerRow);
    const grid = [];

    const cellSize =
      Math.min(window.innerWidth / cols, window.innerHeight / rows) * 0.8;
    const fontSize = `${cellSize * 0.5}px`;

    for (let i = 0; i < rows; i++) {
      const row = [];
      const tr = document.createElement("tr");

      const rowHeader = document.createElement("td");
      rowHeader.textContent = i + 1;

      rowHeader.style.textAlign = "center";
      tr.appendChild(rowHeader);

      for (let j = 0; j < cols; j++) {
        const td = document.createElement("td");
        const input = document.createElement("input");
        input.type = "text";
        input.maxLength = 1;
        input.dataset.row = i;
        input.dataset.col = j;
        row.push({ row: i, col: j, is_black: false, solution: "" });

        input.addEventListener("input", () => {
          const value = input.value.trim().toUpperCase();
          row[j].is_black = value === "#";
          row[j].solution = value !== "#" ? value : "";
          input.style.backgroundColor = input.value
            ? "white"
            : "rgba(0, 0, 0, 0.2)";
        });

        input.style.backgroundColor = "rgba(0, 0, 0, 0.2)";
        input.style.fontSize = fontSize;
        input.style.width = `${cellSize}px`;
        input.style.height = `${cellSize}px`;
        input.style.textAlign = "center";
        input.style.boxSizing = "border-box";

        td.appendChild(input);
        tr.appendChild(td);
      }
      table.appendChild(tr);
      grid.push(row);
    }

    gridContainer.appendChild(table);

    gridContainer.appendChild(table);

    const gridWidth = gridContainer.offsetWidth;
    const gridHeight = gridContainer.offsetHeight;

    const cellWidth = Math.floor(gridWidth / cols);
    const cellHeight = Math.floor(gridHeight / rows);

    document.querySelectorAll("#crosswordGrid td").forEach((td) => {
      td.style.width = `${cellWidth}px`;
      td.style.height = `${cellHeight}px`;
    });

    for (let i = 0; i < rows; i++) {
      const li = document.createElement("li");
      const input = document.createElement("input");
      input.type = "text";

      input.placeholder = `Hint for row ${i + 1}`;
      li.appendChild(input);
      rowHints.appendChild(li);
    }

    for (let j = 0; j < cols; j++) {
      const li = document.createElement("li");
      const input = document.createElement("input");
      input.type = "text";

      input.placeholder = `Hint for column ${String.fromCharCode(65 + j)}`;
      li.appendChild(input);
      colHints.appendChild(li);
    }

    gridDataInput.value = JSON.stringify(grid);
  });

  document.getElementById("gridForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const gridData = [];
    const hints = { row: {}, col: {} };

    document.querySelectorAll("#crosswordGrid tr").forEach((tr, rowIndex) => {
      if (rowIndex === 0) return;

      const row = [];
      tr.querySelectorAll("td").forEach((td, colIndex) => {
        if (colIndex === 0) return;

        const input = td.querySelector("input");
        const value = input.value.trim().toUpperCase();
        row.push(value || "");
      });
      gridData.push(row);
    });

    document.querySelectorAll("#rowHints input").forEach((input, index) => {
      const hint = input.value.trim();
      if (hint) {
        hints.row[index + 1] = hint;
      }
    });

    document.querySelectorAll("#colHints input").forEach((input, index) => {
      const hint = input.value.trim();
      if (hint) {
        hints.col[String.fromCharCode(65 + index)] = hint;
      }
    });

    const payload = {
      name: document.getElementById("name").value.trim(),
      dimensions: document.getElementById("dimensions").value.trim(),
      difficulty: document.getElementById("difficulty").value.trim(),
      cells: gridData,
      clues: hints,
      creator_id: 1,
    };

    try {
      const response = await fetch("/api/grids/create", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (response.ok) {
        console.log(result);

        alert(result.message);
        window.location.reload();
      } else {
        alert(`Error: ${result.message}`);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An unexpected error occurred.");
    }
  });
});
