import './bootstrap';

import Alpine from 'alpinejs';

const isDev = import.meta.env.DEV;

window.dashboardRealtime = function () {
    return {
        status: 'Realtime standby',
        lastUpdated: '--:--:--',
        connected: false,
        subscribed: false,
        init() {
            this.refreshStats(true);

            if (!window.Echo) {
                this.status = 'Realtime fallback';
                return;
            }

            this.status = 'Menghubungkan realtime...';
            this.bindConnectionStatus();
            this.subscribeDashboardChannel();
        },
        subscribeDashboardChannel() {
            if (this.subscribed || !window.Echo) {
                return;
            }

            try {
                window.Echo.channel('dashboard')
                    .listen('.booking.changed', () => {
                        if (isDev) {
                            console.log('Booking changed received');
                        }

                        this.refreshStats(true);
                    })
                    .listen('.payment.changed', () => {
                        if (isDev) {
                            console.log('Payment changed received');
                        }

                        this.refreshStats(true);
                    });

                this.subscribed = true;
            } catch (error) {
                this.status = 'Realtime fallback';
                this.connected = false;

                if (isDev) {
                    console.warn('Dashboard realtime subscription failed', error);
                }
            }
        },
        bindConnectionStatus() {
            const connection = window.Echo?.connector?.pusher?.connection;

            if (!connection) {
                this.status = 'Realtime aktif';
                this.connected = true;
                return;
            }

            connection.bind('connected', () => {
                this.status = 'Realtime aktif';
                this.connected = true;
            });

            connection.bind('disconnected', () => {
                this.status = 'Realtime fallback';
                this.connected = false;
            });

            connection.bind('unavailable', () => {
                this.status = 'Realtime fallback';
                this.connected = false;
            });

            connection.bind('failed', () => {
                this.status = 'Realtime fallback';
                this.connected = false;
            });
        },
        async refreshStats(markUpdated = true) {
            try {
                const response = await window.axios.get('/dashboard/stats', {
                    headers: {
                        'Cache-Control': 'no-cache',
                    },
                    params: {
                        _: Date.now(),
                    },
                });

                Object.entries(response.data.stats || {}).forEach(([key, value]) => {
                    document.querySelectorAll(`[data-stat-key="${key}"]`).forEach((element) => {
                        element.textContent = value.formatted;
                    });
                });

                if (markUpdated) {
                    this.lastUpdated = response.data.updated_at || new Date().toLocaleTimeString('id-ID');
                }
            } catch (error) {
                if (!this.connected) {
                    this.status = 'Realtime fallback';
                }

                if (isDev) {
                    console.warn('Dashboard stats refresh failed', error);
                }
            }
        },
    };
};

window.Alpine = Alpine;

Alpine.start();
