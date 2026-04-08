function checkIngredient(ingredientName) {
    const rows = document.getElementById('ingredients-tbody').getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].cells[0].textContent.trim() === ingredientName) return true;
    }
    return false;
}

function removeIngredient(btn) { btn.closest('tr').remove(); }
function removeStep(btn) { btn.closest('tr').remove(); }

const addIngredientBtn = document.getElementById('add_ingredient');
if (addIngredientBtn) {
    addIngredientBtn.addEventListener('click', function () {
        const input = document.getElementById('ingredient_name');
        const name  = input.value.trim();
        if (name !== '' && !checkIngredient(name)) {
            const tbody = document.getElementById('ingredients-tbody');
            const row   = document.createElement('tr');
            row.innerHTML = `<td>${name}</td><td><button type="button" class="btn btn-danger" onclick="removeIngredient(this)">Remove</button></td>`;
            tbody.appendChild(row);
            input.value = '';
        }
    });
}

const addStepBtn = document.getElementById('add_step');
if (addStepBtn) {
    addStepBtn.addEventListener('click', function () {
        const input = document.getElementById('step_name');
        const name  = input.value.trim();
        if (name !== '') {
            const tbody = document.getElementById('steps-tbody');
            const row   = document.createElement('tr');
            row.innerHTML = `<td>${name}</td><td><button type="button" class="btn btn-danger" onclick="removeStep(this)">Remove</button></td>`;
            tbody.appendChild(row);
            input.value = '';
        }
    });
}

const mainForm = document.getElementById('main-form');
if (mainForm) {
    mainForm.addEventListener('submit', function () {
        const ingredientRows = document.getElementById('ingredients-tbody').getElementsByTagName('tr');
        document.getElementById('ingredients_input').value = JSON.stringify(Array.from(ingredientRows).map(r => r.cells[0].textContent.trim()));

        const stepRows = document.getElementById('steps-tbody').getElementsByTagName('tr');
        document.getElementById('steps_input').value = JSON.stringify(Array.from(stepRows).map(r => r.cells[0].textContent.trim()));
    });
}

const btnCancel = document.getElementById('btn-cancel');
if (btnCancel) {
    btnCancel.addEventListener('click', function () {
        document.getElementById('recipe-result').style.display = 'none';
    });
}