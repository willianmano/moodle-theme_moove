import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';

let rootelement = document.getElementById('root');
const root = ReactDOM.createRoot(rootelement);
const dataset = rootelement.dataset;
root.render(<App test={dataset.test} />);

