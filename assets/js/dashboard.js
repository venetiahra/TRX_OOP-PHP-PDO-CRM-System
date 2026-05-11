document.addEventListener('DOMContentLoaded', function () {
  if (!window.TRX_DASHBOARD || typeof Chart === 'undefined') return;

  const monthCtx = document.getElementById('monthlyChart');
  const statusCtx = document.getElementById('statusChart');

  const commonOptions = {
    plugins: {
      legend: {
        labels: { color: '#d8c08e', font: { family: 'Inter' } }
      }
    },
    scales: {
      x: {
        ticks: { color: '#af9b74' },
        grid: { color: 'rgba(233,202,109,0.08)' }
      },
      y: {
        beginAtZero: true,
        ticks: { color: '#af9b74' },
        grid: { color: 'rgba(233,202,109,0.08)' }
      }
    }
  };

  if (monthCtx) {
    new Chart(monthCtx, {
      type: 'line',
      data: {
        labels: window.TRX_DASHBOARD.monthlyLabels,
        datasets: [{
          label: 'Clients Added',
          data: window.TRX_DASHBOARD.monthlyValues,
          borderColor: '#e9ca6d',
          backgroundColor: 'rgba(233, 202, 109, 0.16)',
          fill: true,
          tension: 0.35,
          pointRadius: 4,
          pointBackgroundColor: '#d3ad57'
        }]
      },
      options: commonOptions
    });
  }

  if (statusCtx) {
    new Chart(statusCtx, {
      type: 'doughnut',
      data: {
        labels: ['Active', 'Inactive'],
        datasets: [{
          data: window.TRX_DASHBOARD.statusValues,
          backgroundColor: ['#e9ca6d', '#64502a'],
          borderColor: ['#e9ca6d', '#64502a'],
          borderWidth: 0
        }]
      },
      options: {
        plugins: {
          legend: {
            position: 'bottom',
            labels: { color: '#d8c08e', font: { family: 'Inter' } }
          }
        }
      }
    });
  }
});