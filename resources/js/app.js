import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.dashboardPolling = () => ({
    status: 'Auto refresh standby',
    lastUpdated: '--:--:--',
    isRefreshing: false,
    init() {},
});

async function startApp() {
    if (document.querySelector('[data-dashboard-polling]')) {
        try {
            const { registerDashboardPolling } = await import('./dashboard-polling');
            registerDashboardPolling();
        } catch (error) {
            if (import.meta.env.DEV) {
                console.warn('Dashboard polling module failed to load', error);
            }
        }
    }

    Alpine.start();
}

startApp();
