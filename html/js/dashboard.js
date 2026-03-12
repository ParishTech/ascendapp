// Dashboard JavaScript
// html/js/dashboard.js

document.addEventListener('DOMContentLoaded', async () => {
    await loadDashboardData();
    setupClockToggle();
});

async function loadDashboardData() {
    const user = JSON.parse(sessionStorage.getItem('user'));

    // Load clock history
    const historyResponse = await api.getClockHistory(10);
    if (historyResponse && historyResponse.success) {
        populateActivityTable(historyResponse.records);
        updateStats(historyResponse);
    }

    // Load current clock status
    const currentResponse = await api.getCurrentClock();
    if (currentResponse && currentResponse.success) {
        updateClockStatus(currentResponse);
    }

    // Load performance feedback if server
    if (user.role === 'server') {
        const feedbackResponse = await api.getServerNotes(user.id, 5);
        if (feedbackResponse && feedbackResponse.success) {
            populateFeedback(feedbackResponse);
        }
    }
}

function populateActivityTable(records) {
    const tbody = document.getElementById('activity-table');
    tbody.innerHTML = '';

    if (records.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">No clock records yet</td></tr>';
        return;
    }

    records.forEach(record => {
        const row = document.createElement('tr');
        const date = new Date(record.date + ' ' + record.time);
        const clockIn = new Date(record.clock_in);
        const clockOut = record.clock_out ? new Date(record.clock_out) : null;

        row.innerHTML = `
            <td>${date.toLocaleDateString()}</td>
            <td>${clockIn.toLocaleTimeString()}</td>
            <td>${clockOut ? clockOut.toLocaleTimeString() : '-'}</td>
            <td>${record.duration_minutes ? record.duration_minutes + ' min' : '-'}</td>
        `;
        tbody.appendChild(row);
    });
}

function updateStats(historyResponse) {
    const records = historyResponse.records;
    
    // Total hours this month
    const now = new Date();
    const thisMonth = records.filter(r => {
        const recordDate = new Date(r.clock_in);
        return recordDate.getMonth() === now.getMonth() && recordDate.getFullYear() === now.getFullYear();
    });

    const totalMinutes = thisMonth.reduce((sum, r) => sum + (r.duration_minutes || 0), 0);
    const totalHours = (totalMinutes / 60).toFixed(1);

    document.getElementById('total-hours').textContent = totalHours;
    document.getElementById('total-sessions').textContent = thisMonth.length;
}

function updateClockStatus(currentResponse) {
    const clockToggle = document.getElementById('clock-toggle');
    const statusMessage = document.getElementById('status-text') || createStatusMessage();

    if (currentResponse.clocked_in) {
        statusMessage.textContent = 'Clocked In';
        statusMessage.classList.remove('off-duty');
        statusMessage.classList.add('on-duty');
        
        document.getElementById('clock-in-btn').style.display = 'none';
        document.getElementById('clock-out-btn').style.display = 'block';

        // Update elapsed time
        updateElapsedTime(currentResponse.record.clock_in);
    } else {
        statusMessage.textContent = 'Off Duty';
        statusMessage.classList.remove('on-duty');
        statusMessage.classList.add('off-duty');
        
        document.getElementById('clock-in-btn').style.display = 'block';
        document.getElementById('clock-out-btn').style.display = 'none';
    }
}

function createStatusMessage() {
    const div = document.createElement('p');
    div.id = 'status-text';
    div.className = 'status-message off-duty';
    document.querySelector('.clock-status').insertBefore(div, document.querySelector('.elapsed-time'));
    return div;
}

function updateElapsedTime(clockInTime) {
    const clockInDate = new Date(clockInTime);
    const updateTime = () => {
        const now = new Date();
        const elapsed = Math.floor((now - clockInDate) / 1000);
        
        const hours = Math.floor(elapsed / 3600);
        const minutes = Math.floor((elapsed % 3600) / 60);
        const seconds = elapsed % 60;

        const timeString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        const elapsedElement = document.getElementById('elapsed-time');
        if (elapsedElement) {
            elapsedElement.textContent = `Elapsed: ${timeString}`;
        }
    };

    updateTime();
    setInterval(updateTime, 1000);
}

function setupClockToggle() {
    const toggleBtn = document.getElementById('clock-toggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', async () => {
            const current = await api.getCurrentClock();
            if (current && current.clocked_in) {
                await clockOut();
            } else {
                // Show mass selection
                alert('Please clock in from the Clock In/Out page');
            }
        });
    }
}

async function populateFeedback(feedbackResponse) {
    const feedbackList = document.getElementById('feedback-list');
    const notes = feedbackResponse.notes;

    if (notes.length === 0) {
        feedbackList.innerHTML = '<p class="text-center">No feedback yet</p>';
        return;
    }

    feedbackList.innerHTML = '';
    notes.forEach(note => {
        const feedbackCard = document.createElement('div');
        feedbackCard.className = 'card';
        feedbackCard.innerHTML = `
            <h4>${note.mc_name} - ${new Date(note.created_at).toLocaleDateString()}</h4>
            <p><strong>Timeliness:</strong> ${note.timeliness}/5</p>
            <p><strong>Demeanor:</strong> ${note.demeanor}/5</p>
            <p><strong>Accuracy:</strong> ${note.accuracy}/5</p>
            ${note.notes ? `<p><strong>Notes:</strong> ${note.notes}</p>` : ''}
            ${note.has_referral ? `<p class="warning">⚠️ Training referral: ${note.referral_reason}</p>` : ''}
        `;
        feedbackList.appendChild(feedbackCard);
    });
}
