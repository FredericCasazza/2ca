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

// Moment.js
global.moment = require('moment');

// Import Boostratp
import 'bootstrap';

// Import Admin LTE
import 'admin-lte';

// Import Tempusdominus
import 'tempusdominus-bootstrap-4';

// Import Fontawesome
//import '@fortawesome/fontawesome-free/js/all.min';

// Import Bootbox
const bootbox = require('bootbox');
global.bootbox = bootbox;

// Import FOSJsRouting
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);
global.Routing = Routing;