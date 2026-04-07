 // This section is the exact same as the add recipe
    function checkIngredient(ingredientName) {
        const rows = document.getElementById('ingredients-tbody').getElementsByTagName('tr');
        for (let i = 0; i < rows.length; i++) {
            if (rows[i].cells[0].innerText === ingredientName) return true;
        }
        return false;
    }

    function removeIngredient(btn) { btn.closest('tr').remove(); }
    function removeStep(btn) { btn.closest('tr').remove(); }

    document.getElementById('add_ingredient').addEventListener('click', function () {
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

    document.getElementById('add_step').addEventListener('click', function () {
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

    document.getElementById('main-form').addEventListener('submit', function () {
        const ingredientRows = document.getElementById('ingredients-tbody').getElementsByTagName('tr');
        document.getElementById('ingredients_input').value = JSON.stringify(Array.from(ingredientRows).map(r => r.cells[0].innerText));

        const stepRows = document.getElementById('steps-tbody').getElementsByTagName('tr');
        document.getElementById('steps_input').value = JSON.stringify(Array.from(stepRows).map(r => r.cells[0].innerText));
    });