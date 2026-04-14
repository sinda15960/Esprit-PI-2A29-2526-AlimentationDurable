// Form validation (sans HTML5)
document.addEventListener('DOMContentLoaded', function() {
    const recipeForm = document.getElementById('recipeForm');
    
    if(recipeForm) {
        recipeForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate title
            const title = document.getElementById('title');
            const titleError = document.getElementById('titleError');
            if(title && title.value.trim().length < 3) {
                titleError.textContent = 'Le titre doit contenir au moins 3 caractères';
                isValid = false;
            } else if(titleError) {
                titleError.textContent = '';
            }
            
            // Validate description
            const description = document.getElementById('description');
            const descriptionError = document.getElementById('descriptionError');
            if(description && description.value.trim().length < 20) {
                descriptionError.textContent = 'La description doit contenir au moins 20 caractères';
                isValid = false;
            } else if(descriptionError) {
                descriptionError.textContent = '';
            }
            
            // Validate ingredients
            const ingredients = document.getElementById('ingredients');
            const ingredientsError = document.getElementById('ingredientsError');
            if(ingredients && ingredients.value.trim().length < 10) {
                ingredientsError.textContent = 'La liste des ingrédients doit contenir au moins 10 caractères';
                isValid = false;
            } else if(ingredientsError) {
                ingredientsError.textContent = '';
            }
            
            // Validate prep time
            const prepTime = document.getElementById('prep_time');
            const prepTimeError = document.getElementById('prepTimeError');
            if(prepTime && (!prepTime.value || prepTime.value <= 0)) {
                prepTimeError.textContent = 'Le temps de préparation doit être un nombre positif';
                isValid = false;
            } else if(prepTimeError) {
                prepTimeError.textContent = '';
            }
            
            // Validate cook time
            const cookTime = document.getElementById('cook_time');
            const cookTimeError = document.getElementById('cookTimeError');
            if(cookTime && (cookTime.value === '' || cookTime.value < 0)) {
                cookTimeError.textContent = 'Le temps de cuisson doit être un nombre valide';
                isValid = false;
            } else if(cookTimeError) {
                cookTimeError.textContent = '';
            }
            
            if(!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.error-message:not(:empty)');
                if(firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    
    // Add instruction dynamically
    const addButton = document.getElementById('addInstruction');
    if(addButton) {
        let instructionCount = document.querySelectorAll('.instruction-group').length;
        
        addButton.addEventListener('click', function() {
            const container = document.getElementById('instructions-container');
            const newInstruction = document.createElement('div');
            newInstruction.className = 'instruction-group';
            newInstruction.innerHTML = `
                <div class="form-group">
                    <label>Étape ${instructionCount + 1}</label>
                    <textarea name="instructions[${instructionCount}][description]" rows="3" placeholder="Description de l'étape..." required></textarea>
                    <input type="text" name="instructions[${instructionCount}][tip]" placeholder="Astuce (optionnel)">
                    <button type="button" class="btn-remove-instruction" onclick="this.closest('.instruction-group').remove()">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            `;
            container.appendChild(newInstruction);
            instructionCount++;
        });
    }
});

// Delete modal
function showDeleteModal(id, title) {
    const modal = document.getElementById('deleteModal');
    const deleteTitle = document.getElementById('deleteRecipeTitle');
    const deleteForm = document.getElementById('deleteForm');
    
    if(modal && deleteTitle && deleteForm) {
        deleteTitle.textContent = title;
        deleteForm.action = `index.php?action=backDeleteRecipe&id=${id}`;
        modal.style.display = 'block';
        
        // Close modal handlers
        const closeBtn = modal.querySelector('.close');
        const cancelBtn = modal.querySelector('.btn-cancel');
        
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        };
        
        cancelBtn.onclick = function() {
            modal.style.display = 'none';
        };
        
        window.onclick = function(event) {
            if(event.target === modal) {
                modal.style.display = 'none';
            }
        };
    }
}

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.style.display = 'none';
        }, 300);
    });
}, 5000);