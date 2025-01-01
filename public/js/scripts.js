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

    // Generate grid
    gridContainer.innerHTML = "";
    rowHints.innerHTML = "";
    colHints.innerHTML = "";

    // Create table element
    const table = document.createElement("table");

    // Create top header row (column letters)
    const headerRow = document.createElement("tr");
    const emptyCorner = document.createElement("td"); // Empty corner for row numbers
    headerRow.appendChild(emptyCorner);
    for (let j = 0; j < cols; j++) {
      const headerCell = document.createElement("td");
      headerCell.textContent = String.fromCharCode(65 + j); // Convert index to letters (A, B, C, ...)
      headerRow.appendChild(headerCell);
    }
    table.appendChild(headerRow);

    const grid = [];
    for (let i = 0; i < rows; i++) {
      const row = [];
      const tr = document.createElement("tr");

      // Add row number
      const rowHeader = document.createElement("td");
      rowHeader.textContent = i + 1; // Row number
      tr.appendChild(rowHeader);

      for (let j = 0; j < cols; j++) {
        const td = document.createElement("td");
        const input = document.createElement("input");
        input.type = "text";
        input.maxLength = 1;
        input.dataset.row = i;
        input.dataset.col = j;
        row.push({ row: i, col: j, is_black: false, solution: "" });

        // Mark cell as black or not
        input.addEventListener("input", () => {
          const value = input.value.trim().toUpperCase();
          row[j].is_black = value === "#";
          row[j].solution = value !== "#" ? value : "";
          input.style.backgroundColor = input.value ? "white" : "gray";
        });
        input.style.backgroundColor = "gray";
        td.appendChild(input);
        tr.appendChild(td);
      }
      table.appendChild(tr);
      grid.push(row);
    }

    gridContainer.appendChild(table);

    // Set dynamic dimensions
    const gridWidth = gridContainer.offsetWidth;
    const gridHeight = gridContainer.offsetHeight;

    const cellWidth = Math.floor(gridWidth / cols);
    const cellHeight = Math.floor(gridHeight / rows);

    document.querySelectorAll("#crosswordGrid td").forEach((td) => {
      td.style.width = `${cellWidth}px`;
      td.style.height = `${cellHeight}px`;
    });
    // Generate row hints
    for (let i = 0; i < rows; i++) {
      const li = document.createElement("li");
      const input = document.createElement("input");
      input.type = "text";
      input.required = true;
      input.placeholder = `Hint for row ${i + 1}`; // Row number as placeholder
      li.appendChild(input);
      rowHints.appendChild(li);
    }

    // Generate column hints
    for (let j = 0; j < cols; j++) {
      const li = document.createElement("li");
      const input = document.createElement("input");
      input.type = "text";
      input.required = true;
      input.placeholder = `Hint for column ${String.fromCharCode(65 + j)}`; // Column letter as placeholder
      li.appendChild(input);
      colHints.appendChild(li);
    }

    gridDataInput.value = JSON.stringify(grid);
  });

  // Save hints and grid structure on form submission
  document.getElementById("gridForm").addEventListener("submit", async (e) => {
    e.preventDefault(); // Prevent the default form submission behavior

    const gridData = [];
    const hints = { row: {}, col: {} };

    // Collect grid letters
    document.querySelectorAll("#crosswordGrid tr").forEach((tr, rowIndex) => {
      if (rowIndex === 0) return; // Skip header row

      const row = [];
      tr.querySelectorAll("td").forEach((td, colIndex) => {
        if (colIndex === 0) return; // Skip row number cell

        const input = td.querySelector("input");
        const value = input.value.trim().toUpperCase();
        row.push(value || ""); // Empty cells are stored as empty strings
      });
      gridData.push(row);
    });

    // Collect row hints
    document.querySelectorAll("#rowHints input").forEach((input, index) => {
      const hint = input.value.trim();
      if (hint) {
        hints.row[index + 1] = hint; // Row numbers are 1-based
      }
    });

    // Collect column hints
    document.querySelectorAll("#colHints input").forEach((input, index) => {
      const hint = input.value.trim();
      if (hint) {
        hints.col[String.fromCharCode(65 + index)] = hint; // Columns use letters
      }
    });

    // Prepare the payload
    const payload = {
      name: document.getElementById("name").value.trim(),
      dimensions: document.getElementById("dimensions").value.trim(),
      difficulty: document.getElementById("difficulty").value.trim(),
      cells: gridData,
      clues: hints,
      creator_id: 1, // Example: Replace with actual user ID
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
        document.getElementById("gridForm").reset();
        // window.location.href = "/grids";
      } else {
        alert(`Error: ${result.message}`);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An unexpected error occurred.");
    }
  });
});
