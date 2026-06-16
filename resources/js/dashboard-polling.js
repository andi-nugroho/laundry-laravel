const isDev = import.meta.env.DEV;

export function registerDashboardPolling() {
    window.dashboardPolling = function () {
        return {
            status: 'Polling aktif',
            lastUpdated: '--:--:--',
            isRefreshing: false,
            intervalId: null,
            init() {
                this.refreshStats(true);
                this.intervalId = window.setInterval(() => {
                    this.refreshStats(true);
                }, 30000);

                window.addEventListener('beforeunload', () => {
                    if (this.intervalId) {
                        window.clearInterval(this.intervalId);
                    }
                }, { once: true });
            },
            async refreshStats(markUpdated = true) {
                if (this.isRefreshing) {
                    return;
                }

                this.isRefreshing = true;
                this.status = 'Memperbarui data...';

                try {
                    const response = await fetch(`/dashboard/stats?_=${Date.now()}`, {
                        headers: {
                            Accept: 'application/json',
                            'Cache-Control': 'no-cache',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error(`Dashboard stats request failed with ${response.status}`);
                    }

                    const data = await response.json();

                    Object.entries(data.stats || {}).forEach(([key, value]) => {
                        document.querySelectorAll(`[data-stat-key="${key}"]`).forEach((element) => {
                            element.textContent = value.formatted;
                        });
                    });

                    if (markUpdated) {
                        this.lastUpdated = data.updated_at || new Date().toLocaleTimeString('id-ID');
                    }

                    this.status = 'Polling aktif';
                } catch (error) {
                    this.status = 'Polling fallback';

                    if (isDev) {
                        console.warn('Dashboard stats polling failed', error);
                    }
                } finally {
                    this.isRefreshing = false;
                }
            },
        };
    };
}
