// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import Filter from "./modules/Filter";
import './bootstrap';

new Filter(document.querySelector('.js-filter'));