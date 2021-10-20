// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import Filter from "./modules/Filter";
import './bootstrap';

new Filter(document.querySelector('.js-filter'));

const buttonMenu = document.getElementById('button-menu');
buttonMenu.addEventListener('click', toggleMenu);
function toggleMenu() {
    let menuToggle = document.getElementById('menu-toggle')
    menuToggle.classList.toggle('hidden');
}

const buttonReservations = document.getElementById('button-reservations');
buttonReservations.addEventListener('click', toggleReservations);
function toggleReservations() {
    let menuToggle = document.getElementById('drowrap-reservations')
    menuToggle.classList.toggle('hidden');
}

const buttonGestions = document.getElementById('button-gestions');
buttonGestions.addEventListener('click', toggleGestions);
function toggleGestions() {
    let menuToggle = document.getElementById('drowrap-gestions')
    menuToggle.classList.toggle('hidden');
}