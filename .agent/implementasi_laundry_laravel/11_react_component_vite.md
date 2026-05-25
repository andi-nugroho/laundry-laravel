# 11 - React Component via Vite

## Tujuan

Menambahkan komponen React interaktif di dalam Blade tanpa mengubah Laravel menjadi full SPA.

## Install React

```bash
./vendor/bin/sail npm install @vitejs/plugin-react react react-dom
```

## Install UI Library

```bash
./vendor/bin/sail npm install framer-motion lenis lucide-react recharts
```

## Konsep

Laravel tetap menjadi backend utama.
Blade tetap menjadi template utama.
React hanya digunakan untuk komponen interaktif tertentu.

## Contoh Blade

```blade
<div id="dashboard-chart"></div>

@viteReactRefresh
@vite('resources/js/dashboard.jsx')
```

## Contoh React Mount

```jsx
import React from 'react';
import { createRoot } from 'react-dom/client';

function DashboardChart() {
    return (
        <div>
            <h2>Grafik Pendapatan</h2>
        </div>
    );
}

const element = document.getElementById('dashboard-chart');

if (element) {
    createRoot(element).render(<DashboardChart />);
}
```

## Komponen yang Cocok Menggunakan React

- Grafik dashboard
- Animated statistic card
- Status tracking laundry
- Modal detail booking
- Table interaktif
