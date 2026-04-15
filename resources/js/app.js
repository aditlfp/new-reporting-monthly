import './bootstrap';

import Alpine from 'alpinejs';
import 'remixicon/fonts/remixicon.css'
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

if (document.querySelector('[data-page="send-img-create"]')) {
  import('./pages/user/send-img/create');
}
