
//automatically refreshes at midnight for the date to updates 
(function() {
    const now   = new Date();
    const msUntilMidnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1) - now;
    setTimeout(() => location.reload(), msUntilMidnight);
})();

function toggleAddForm(id) {
    const form = document.getElementById(id);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// Script for the cool sidebar popping out
let btn = document.querySelector('#btn');
let sidebar = document.querySelector('.sidebar');

btn.onclick = function(){
	sidebar.classList.toggle('active');
};
