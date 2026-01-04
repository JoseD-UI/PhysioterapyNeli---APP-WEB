import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import MainApp from './MainApp';

const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(<MainApp />);
} else {
    console.error('Target container #app not found in the DOM.');
}
