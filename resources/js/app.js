import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.dashboardRealtime = () => ({
    status: 'Realtime fallback',
    lastUpdated: '--:--:--',
    connected: false,
    init() {},
});

async function startApp() {
    if (document.querySelector('[data-dashboard-realtime]')) {
        try {
            const { registerDashboardRealtime } = await import('./dashboard-realtime');
            registerDashboardRealtime();
        } catch (error) {
            if (import.meta.env.DEV) {
                console.warn('Dashboard realtime module failed to load', error);
            }
        }
    }

    Alpine.start();
}

startApp();
