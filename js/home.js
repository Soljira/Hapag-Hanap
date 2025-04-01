document.addEventListener("DOMContentLoaded", function () {
  // Store original order of ingredients
  const originalIngredientsOrder = [];
  const recipesContainer = document.querySelector(".recipe-grid");
  let debounceTimer;

  // Get containers
  const availableIngredients = document.getElementById("available-ingredients");
  const selectedIngredientsContainer = document.getElementById(
    "selected-ingredients"
  );

  if (availableIngredients && selectedIngredientsContainer) {
    // Store original order on page load
    document
      .querySelectorAll("#available-ingredients label")
      .forEach((label) => {
        originalIngredientsOrder.push({
          element: label,
          name: label.querySelector("input").value,
          text: label.textContent.trim(),
        });
      });

    // Remove all existing BR tags
    const brTags = availableIngredients.querySelectorAll("br");
    brTags.forEach((br) => br.remove());

    // Handle checkbox changes
    availableIngredients.addEventListener("change", handleIngredientChange);
    selectedIngredientsContainer.addEventListener(
      "change",
      handleIngredientChange
    );

    function handleIngredientChange(e) {
      if (e.target.type === "checkbox") {
        if (e.target.checked) {
          moveToSelected(e.target);
        } else {
          moveToAvailable(e.target);
        }
        // Trigger recipe search with debounce
        debounceSearch();
      }
    }

    function debounceSearch() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(searchRecipes, 300);
    }

    async function searchRecipes() {
      const selectedIngredients = Array.from(
        document.querySelectorAll(
          '#selected-ingredients input[type="checkbox"]'
        )
      ).map((checkbox) => checkbox.value);

      if (selectedIngredients.length === 0) {
        recipesContainer.innerHTML =
          '<div class="empty-state">Select ingredients to find recipes</div>';
        return;
      }

      // Add client-side validation for minimum 3 ingredients
      if (selectedIngredients.length < 3) {
        recipesContainer.innerHTML =
          '<div class="error">Please select at least 3 ingredients to find recipes</div>';
        return;
      }

      recipesContainer.innerHTML =
        '<div class="loading">Searching recipes...</div>';

      try {
        const minMatch = 3; // Fixed minimum match of 3
        const response = await fetch("home.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ ingredients: selectedIngredients, minMatch }),
        });

        if (!response.ok) {
          throw new Error(
            `Server returned ${response.status}: ${response.statusText}`
          );
        }

        const result = await response.json();

        if (!result.success) {
          throw new Error(result.error || "Unknown server error");
        }

        renderRecipes(result.data);
      } catch (error) {
        console.error("Error:", error);
        recipesContainer.innerHTML = `
                <div class="error">
                    Error loading recipes: ${error.message}
                    <br><br>
                    <small>Check console for details</small>
                </div>
            `;
      }
    }
    function renderRecipes(recipes) {
      recipesContainer.innerHTML = "";

      if (recipes.length === 0) {
        recipesContainer.innerHTML =
          '<div class="no-recipes">No recipes found with these ingredients</div>';
        return;
      }

      const recipeCount = document.querySelector(".recipes-container h2");
      if (recipeCount) {
        recipeCount.textContent = `${recipes.length} Recipes Found`;
      }

      recipes.forEach((recipe) => {
        const recipeCard = document.createElement("div");
        recipeCard.className = "recipe-card";
        recipeCard.innerHTML = `
                      ${
                        recipe.image_path
                          ? `<img src="${recipe.image_path}" alt="${recipe.name}">`
                          : ""
                      }
                      <div class="details">
                          <div class="header">
                              <h2>${recipe.name}</h2>
                          </div>
                          <span class="tag">${
                            recipe.category || "Uncategorized"
                          }</span>
                          <p class="author">By: ${
                            recipe.author || "Unknown"
                          }</p>
                          ${
                            recipe.description
                              ? `<p class="description">${recipe.description}</p>`
                              : ""
                          }
                          <p class="match-count">Matches ${
                            recipe.match_count
                          } ingredients</p>
                          <span class="arrow">❯</span>
                      </div>
                  `;
        recipeCard.addEventListener("click", () => {
          window.location.href = `recipe-detail.php?id=${recipe.id}`;
        });
        recipesContainer.appendChild(recipeCard);
      });
    }

    // Initial empty state
    recipesContainer.innerHTML =
      '<div class="empty-state">Select ingredients to find recipes</div>';
  }

  // Move ingredient to selected list
  function moveToSelected(checkbox) {
    const label = checkbox.parentNode;
    const newLabel = label.cloneNode(true);
    newLabel.querySelector("input").checked = true;
    selectedIngredientsContainer.appendChild(newLabel);
    label.remove();
  }

  // Move ingredient back to available list
  function moveToAvailable(checkbox) {
    const label = checkbox.parentNode;
    const ingredientName = checkbox.value;
    const originalItem = originalIngredientsOrder.find(
      (item) => item.name === ingredientName
    );

    if (originalItem) {
      const newLabel = originalItem.element.cloneNode(true);
      newLabel.querySelector("input").checked = false;
      availableIngredients.appendChild(newLabel);
    }

    label.remove();
  }

  // Search functionality
  const searchInput = document.getElementById("ingredient-search");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase().trim();
      const ingredients = document.querySelectorAll(
        "#available-ingredients label"
      );

      ingredients.forEach((label) => {
        const text = label.textContent.toLowerCase().trim();
        label.style.display = text.includes(searchTerm) ? "flex" : "none";
      });
    });
  }

  // Find recipes button
  const findRecipesBtn = document.getElementById("find-recipes");
  if (findRecipesBtn) {
    findRecipesBtn.addEventListener("click", function () {
      const selectedIngredients = Array.from(
        document.querySelectorAll(
          '#selected-ingredients input[type="checkbox"]'
        )
      );

      if (selectedIngredients.length === 0) {
        alert("Please select at least one ingredient first.");
        return;
      }

      // Add the new validation check
      if (selectedIngredients.length < 3) {
        alert("Please select at least 3 ingredients to find recipes.");
        recipesContainer.innerHTML =
          '<div class="error">Please select at least 3 ingredients to find recipes</div>';
        return;
      }

      // Trigger the search
      debounceSearch();
    });
  }
});
