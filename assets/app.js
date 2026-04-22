import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import '@fortawesome/fontawesome-free/css/all.min.css';;
import './styles/app.css';

import 'bootstrap/dist/css/bootstrap.min.css'; // Подключаем стили
import 'bootstrap'; // Подключаем JS (модалки, дропдауны и т.д.)

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
