/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
import '../css/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
global.$ = global.jQuery = $;

import 'bootstrap';
import 'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min';
import 'admin-lte/dist/js/adminlte.min';
import '@fortawesome/fontawesome-free/js/all.min';
