// Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initDashboardCharts();
    initDashboardFilters();
    initQuickActions();
    initRealTimeUpdates();
});

// Dashboard Charts
function initDashboardCharts() {
    // Patient Visits Chart
    const visitsChart = document.getElementById('visitsChart');
    if (visitsChart) {
        initVisitsChart();
    }
    
    // Patient Demographics Chart
    const demographicsChart = document.getElementById('demographicsChart');
    if (demographicsChart) {
        initDemographicsChart();
    }
}

function initVisitsChart() {
    // Sample data - in real app, fetch from API
    const data = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Patient Visits',
            data: [45, 52, 38, 65, 59, 72, 80],
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    };
    
    const options = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    };
    
    // Create chart if Chart.js is available
    if (window.Chart) {
        new Chart(document.getElementById('visitsChart'), {
            type: 'line',
            data: data,
            options: options
        });
    }
}

function initDemographicsChart() {
    const data = {
        labels: ['0-18', '19-35', '36-50', '51-65', '65+'],
        datasets: [{
            data: [15, 30, 25, 20, 10],
            backgroundColor: [
                '#3498db',
                '#2ecc71',
                '#9b59b6',
                '#f39c12',
                '#e74c3c'
            ]
        }]
    };
    
    const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    };
    
    if (window.Chart) {
        new Chart(document.getElementById('demographicsChart'), {
            type: 'pie',
            data: data,
            options: options
        });
    }
}

// Dashboard Filters
function initDashboardFilters() {
    // Date filter
    const dateFilter = document.getElementById('dateFilter');
    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            updateDashboardData(this.value);
        });
    }
    
    // Department filter
    const deptFilter = document.getElementById('deptFilter');
    if (deptFilter) {
        deptFilter.addEventListener('change', function() {
            filterDashboardByDepartment(this.value);
        });
    }
}

function updateDashboardData(timeRange) {
    // Show loading state
    showLoadingState();
    
    // Simulate API call
    setTimeout(() => {
        // Update stats
        updateDashboardStats();
        
        // Update charts
        if (window.Chart) {
            Chart.helpers.each(Chart.instances, function(chart) {
                chart.update();
            });
        }
        
        // Hide loading state
        hideLoadingState();
    }, 1000);
}

function filterDashboardByDepartment(dept) {
    // Filter appointments list
    const appointments = document.querySelectorAll('.appointment-item');
    appointments.forEach(item => {
        if (dept === 'all' || item.dataset.department === dept) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Quick Actions
function initQuickActions() {
    const quickActions = document.querySelectorAll('.action-btn');
    quickActions.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.href.includes('#')) {
                e.preventDefault();
                const action = this.dataset.action;
                performQuickAction(action);
            }
        });
    });
}

function performQuickAction(action) {
    switch (action) {
        case 'new_patient':
            window.location.href = 'dashboard/patients.php?action=new';
            break;
        case 'schedule_appointment':
            window.location.href = 'dashboard/appointments.php?action=schedule';
            break;
        case 'new_prescription':
            window.location.href = 'dashboard/prescriptions.php?action=new';
            break;
        case 'upload_lab':
            window.location.href = 'dashboard/lab-results.php?action=upload';
            break;
        default:
            console.log('Action not implemented:', action);
    }
}

// Real-time Updates
function initRealTimeUpdates() {
    // Update time every minute
    updateDashboardTime();
    setInterval(updateDashboardTime, 60000);
    
    // Check for new notifications every 30 seconds
    setInterval(checkNotifications, 30000);
    
    // Auto-refresh stats every 5 minutes
    setInterval(updateDashboardStats, 300000);
}

function updateDashboardTime() {
    const timeElement = document.querySelector('.date-display');
    if (timeElement) {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        timeElement.innerHTML = `
            <i class="fas fa-calendar-alt"></i>
            ${now.toLocaleDateString('en-US', options)}
        `;
    }
}

function checkNotifications() {
    // Simulate checking for new notifications
    fetch('api/notifications.php?check=1')
        .then(response => response.json())
        .then(data => {
            if (data.new_notifications > 0) {
                showNotification(`You have ${data.new_notifications} new notification(s)`, 'info');
            }
        })
        .catch(error => console.error('Error checking notifications:', error));
}

function updateDashboardStats() {
    fetch('api/dashboard-stats.php')
        .then(response => response.json())
        .then(data => {
            // Update stat cards
            updateStatCard('total_patients', data.total_patients);
            updateStatCard('today_appointments', data.today_appointments);
            updateStatCard('pending_prescriptions', data.pending_prescriptions);
            updateStatCard('pending_tests', data.pending_tests);
        })
        .catch(error => console.error('Error updating stats:', error));
}

function updateStatCard(statId, value) {
    const card = document.querySelector(`[data-stat="${statId}"]`);
    if (card) {
        const numberElement = card.querySelector('h3');
        if (numberElement) {
            // Animate number change
            const current = parseInt(numberElement.textContent.replace(/,/g, ''));
            if (current !== value) {
                animateNumber(numberElement, current, value);
            }
        }
    }
}

function animateNumber(element, start, end) {
    const duration = 1000;
    const steps = 60;
    const stepValue = (end - start) / steps;
    let current = start;
    let step = 0;
    
    const timer = setInterval(() => {
        current += stepValue;
        step++;
        
        if (step >= steps) {
            current = end;
            clearInterval(timer);
        }
        
        element.textContent = Math.round(current).toLocaleString();
    }, duration / steps);
}

// Loading States
function showLoadingState() {
    const dashboard = document.querySelector('.dashboard');
    if (dashboard) {
        dashboard.classList.add('loading');
        
        // Create loading overlay
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        dashboard.appendChild(overlay);
    }
}

function hideLoadingState() {
    const dashboard = document.querySelector('.dashboard');
    if (dashboard) {
        dashboard.classList.remove('loading');
        
        const overlay = dashboard.querySelector('.loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }
}

// Export to global scope
window.Dashboard = {
    updateStats: updateDashboardStats,
    refresh: () => updateDashboardData('today'),
    showLoading: showLoadingState,
    hideLoading: hideLoadingState
};