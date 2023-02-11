// This is all you.
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

import './clipboard';
import {toDarkMode, toLightMode, toSystemMode} from './theme';

window.Alpine = Alpine;

Alpine.plugin(focus);
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    window.toDarkMode = toDarkMode;
    window.toLightMode = toLightMode;
    window.toSystemMode = toSystemMode;
});
