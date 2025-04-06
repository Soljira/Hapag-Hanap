document.addEventListener("DOMContentLoaded", function () {
  // Store original order of ingredients
  const originalIngredientsOrder = [];
  const recipesContainer = document.querySelector(".recipe-grid");
  let debounceTimer;

  const availableIngredients = document.getElementById("available-ingredients");
  const selectedIngredientsContainer = document.getElementById(
    "selected-ingredients"
  );

  if (availableIngredients && selectedIngredientsContainer) {
    document
      .querySelectorAll("#available-ingredients label")
      .forEach((label) => {
        originalIngredientsOrder.push({
          element: label,
          name: label.querySelector("input").value,
          text: label.textContent.trim(),
        });
      });

    const brTags = availableIngredients.querySelectorAll("br");
    brTags.forEach((br) => br.remove());

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

      if (selectedIngredients.length < 3) {
        recipesContainer.innerHTML =
          '<div class="error">Please select at least 3 ingredients to find recipes</div>';
        return;
      }

      recipesContainer.innerHTML =
        '<div class="loading">Searching recipes...</div>';

      try {
        const minMatch = 3; // DAPAT PUMILI MUNA NG 3 INGREDIENTS BEFORE MAGFETCH NG RECIPES
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
    async function renderRecipes(recipes) {
        recipesContainer.innerHTML = "";
      
        if (recipes.length === 0) {
          recipesContainer.innerHTML = '<div class="no-recipes">No recipes found with these ingredients</div>';
          return;
        }
      
        const recipeCount = document.querySelector(".recipes-container h2");
        if (recipeCount) {
          recipeCount.textContent = `${recipes.length} Recipes Found`;
        }
      
        recipes.forEach((recipe) => {
          const recipeCard = document.createElement("div");
          recipeCard.className = "recipe-card";
          
          // Create tags HTML if tags exist
          let tagsHtml = '';
          if (recipe.tags) {
            // Split the concatenated tags by the pipe separator (matches your SQL query)
            const tagsArray = recipe.tags.split(' | ').filter(tag => tag.trim() !== '');
            tagsHtml = `
              <div class="tags-container">
                ${tagsArray.map(tag => `
                  <span class="tag" data-tag="${encodeURIComponent(tag.trim())}">
                    ${tag.trim()}
                  </span>
                `).join('')}
              </div>
            `;
          }
      
          recipeCard.innerHTML = `
            ${recipe.image_path ? `<img src="${recipe.image_path}" alt="${recipe.name}">` : ''}
            <div class="details">
              <div class="header">
                <h2>${recipe.name}</h2>
              </div>
              ${tagsHtml}
              <p class="author">By: ${recipe.author || "Unknown"}</p>
              ${recipe.description ? `<p class="description">${recipe.description}</p>` : ''}
              <span class="arrow">❯</span>
            </div>
          `;
          
          // Recipe click handler
          recipeCard.addEventListener("click", () => {
            window.location.href = `./recipes/selected-recipe.php?id=${recipe.id}`;
          });
          
          // Add click handlers for tags (prevent bubbling to recipe card)
          recipeCard.querySelectorAll('.tag').forEach(tag => {
            tag.addEventListener('click', function(e) {
              e.stopPropagation();
              const tagName = decodeURIComponent(this.getAttribute('data-tag'));
              filterByTag(tagName);
            });
          });
          
          recipesContainer.appendChild(recipeCard);
        });
      }
      
      // Function to filter recipes by tag
      async function filterByTag(tagName) {
        try {
          const response = await fetch("home.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ tag: tagName })
          });
      
          if (!response.ok) throw new Error("Network response was not ok");
          
          const result = await response.json();
          
          if (result.success) {
            renderRecipes(result.data);
            document.querySelectorAll('.tag').forEach(tag => {
              const currentTag = decodeURIComponent(tag.getAttribute('data-tag'));
              tag.classList.toggle('active', currentTag === tagName);
            });
          }
        } catch (error) {
          console.error("Error filtering by tag:", error);
        }
      }

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

  async function renderRecipes(recipes) {
    recipesContainer.innerHTML = "";
  
    if (recipes.length === 0) {
      recipesContainer.innerHTML = '<div class="no-recipes">No recipes found with these ingredients</div>';
      return;
    }
  
    const recipeCount = document.querySelector(".recipes-container h2");
    if (recipeCount) {
      recipeCount.textContent = `${recipes.length} Recipes Found`;
    }
  
    recipes.forEach((recipe) => {
      const recipeCard = document.createElement("div");
      recipeCard.className = "recipe-card";
      
      let tagsHtml = '';
      if (recipe.tags) {
        const tagsArray = recipe.tags.split(' | ');
        tagsHtml = `<div class="tags-container">${tagsArray.map(tag => 
          `<span class="tag">${tag.trim()}</span>`
        ).join('')}</div>`;
      }
  
      recipeCard.innerHTML = `
        ${recipe.image_path ? `<img src="${recipe.image_path}" alt="${recipe.name}">` : ''}
        <div class="details">
          <div class="header">
            <h2>${recipe.name}</h2>
          </div>
          ${tagsHtml}
          <p class="author">By: ${recipe.author || "Unknown"}</p>
          ${recipe.description ? `<p class="description">${recipe.description}</p>` : ''}
          <span class="arrow">❯</span>
        </div>
      `;
      
      recipeCard.addEventListener("click", () => {
        window.location.href = `./recipes/selected-recipe.php?id=${recipe.id}`;
      });
      recipesContainer.appendChild(recipeCard);
    });
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
