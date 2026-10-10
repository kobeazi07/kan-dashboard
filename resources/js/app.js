//

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */


import './echo';

window.Echo.channel('dashboard-progress')
    .listen('.dashboard.progress.updated', (event) => {
        console.log('Event diterima:', event);

        const data = event.data;

        Object.entries(data).forEach(([division, documents]) => {
            documents.forEach((doc) => {
                const cardElements = window.document.querySelectorAll(
                    '.card[data-division][data-document-name]'
                );

                cardElements.forEach((card) => {
                    if (
                        card.dataset.division !== division ||
                        card.dataset.documentName !== doc.name
                    ) {
                        return;
                    }

                    const progressValue = card.querySelector(
                        '[data-progress-value]'
                    );

                    const progressBar = card.querySelector(
                        '[data-progress-bar]'
                    );

                    const progress = doc.progress;

                    if (progress === null || progress === undefined) {
                        progressValue.textContent = 'Belum tersedia';
                        progressValue.classList.add('unavailable');
                    } else {
                        const value = Number(progress);

                        progressValue.textContent =
                            `${Number(value.toFixed(2))}%`;

                        progressValue.classList.remove('unavailable');
                    }

                    const width =
                        progress === null || progress === undefined
                            ? 0
                            : Math.max(
                                0,
                                Math.min(100, Number(progress))
                            );

                    progressBar.style.width = `${width}%`;
                });
            });
        });
    });

