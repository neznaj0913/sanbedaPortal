document.addEventListener('DOMContentLoaded', () => {
    rebindSendEmailButtons(); 

    if (document.querySelector('#visitorTable')) {
        fetchVisitors(); 
        setInterval(fetchVisitors, 1000); 
    }

    if (document.getElementById('visitorsByHour') || document.getElementById('visitorsByPurpose')) {
        setupCharts(); 
    }
});

function setupCharts() {
    const hourCtx = document.getElementById('visitorsByHour');
    if (hourCtx && window.Chart) {
        const hourLabels = JSON.parse(hourCtx.dataset.labels || "[]");
        const hourData = JSON.parse(hourCtx.dataset.data || "[]");

        if (hourLabels.length === 0) {
            hourCtx.insertAdjacentHTML('afterend', "<p class='text-center text-muted'>No visitor data available</p>");
        } else {
            new Chart(hourCtx, {
                type: 'line',
                data: {
                    labels: hourLabels,
                    datasets: [{
                        label: 'Time-ins',
                        data: hourData,
                        borderColor: '#d6001c',
                        backgroundColor: 'rgba(214, 0, 28, 0.2)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    }

   const companyCtx = document.getElementById('visitorsByPurpose');
if (companyCtx && window.Chart) {
    const companyLabels = JSON.parse(companyCtx.dataset.labels || "[]");
    const companyData = JSON.parse(companyCtx.dataset.data || "[]");

    if (companyLabels.length === 0) {
        companyCtx.insertAdjacentHTML('afterend', "<p class='text-center text-muted'>No company data available</p>");
    } else {
        new Chart(companyCtx, {
            type: 'doughnut',
            data: {
                labels: companyLabels,
                datasets: [{
                    data: companyData,
                    backgroundColor: ['#d6001c', '#ffc107', '#28a745', '#007bff', '#6f42c1']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 15, font: { size: 12 } }
                    }
                }
            }
        });
    }
}

}

function fetchVisitors() {
    fetch(FETCH_VISITORS_URL)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#visitorTable tbody');
            if (!tbody) return;
            tbody.innerHTML = '';

            const activeVisitors = data.visitors.filter(v => v.status !== 'Timed Out');

            if (activeVisitors.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center text-muted">No active visitors found.</td>
                    </tr>`;
                return;
            }

            activeVisitors.forEach(visitor => {
                const formatTime = (timeString) => {
                    if (!timeString) return '-';
                    const date = new Date(timeString);
                    if (isNaN(date)) return timeString;
                    return date.toLocaleString('en-US', {
                        month: '2-digit',
                        day: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    });
                };

                const timeIn = formatTime(visitor.time_in);
                const timeOut = formatTime(visitor.time_out);
                const isInside = visitor.status === 'Inside';

                const row = `
                    <tr data-visitor-id="${visitor.id}">
                    
                    
                        <td>${visitor.company_affiliation ?? '-'}</td>
                        <td>${visitor.first_name} ${visitor.last_name}</td>
                        <td>${visitor.purpose}</td>
                        <td>${visitor.contact_person}<br>
                            <small class="text-muted">${visitor.contact_info ?? ''}</small>
                        </td>
                        <td>${timeIn}</td>

                        <!-- Time Out Column -->
                        <td class="time-out-cell">
                            ${isInside ? `
                                <a href="/visitor/${visitor.id}/time-out"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Mark this visitor as Time Out?')">
                                   ⏰ Time Out
                                </a>
                            ` : `
                                ${timeOut}
                            `}
                        </td>

                        <td>${isInside 
                            ? '<span class="text-success fw-bold">🟢 Inside</span>' 
                            : `<span class="text-muted">${visitor.status}</span>`}</td>

                        <!-- Actions Column -->
                        <td>
                            <form class="send-email-form d-inline">
                                <button type="button"
                                    class="btn btn-sm btn-outline-primary send-email-btn"
                                    ${!isInside ? 'disabled' : ''}
                                    data-send-url="/visitor/${visitor.id}/send-email"
                                    data-status="${visitor.status}">
                                    ✉️ Send Email
                                </button>
                            </form>

                            <form action="/visitor/${visitor.id}/reject"
                                  method="POST"
                                  class="mt-1">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit"
                                        class="btn btn-sm btn-outline-secondary w-100"
                                        onclick="return confirm('Are you sure you want to reject (delete) this visitor?')">
                                    ❌ Reject
                                </button>
                            </form>
                        </td>
                    </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });

            rebindSendEmailButtons();
        })
        .catch(err => console.error('Error fetching visitors:', err));
}


function rebindSendEmailButtons() {
    document.querySelectorAll('.send-email-btn').forEach(btn => {
        btn.onclick = async () => {
            const sendUrl = btn.dataset.sendUrl;
            const status = btn.dataset.status;

            if (!sendUrl) return alert('Error: Missing email route.');
            if (status !== 'Inside') return alert('Cannot send email — visitor already timed out.');
            if (btn.disabled) return;

            const originalText = btn.textContent;
            btn.textContent = 'Sending...';
            btn.disabled = true;

            try {
                const response = await fetch(sendUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    btn.textContent = '✅ Sent!';
                } else {
                    console.error('Email send failed:', data.message);
                    btn.textContent = '❌ Error';
                    alert(data.message);
                }
            } catch (error) {
                console.error('Send email failed:', error);
                btn.textContent = '⚠️ Failed';
            }

            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 1500);
        };
    });
}
