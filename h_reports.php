<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports | Habit Tracker</title>

  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      padding: 20px 0;
    }

    .container {
      margin-top: 40px;
      max-width: 1400px;
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    /* Header Section */
    .header-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #e0e0e0;
    }

    .page-title {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .page-title i {
      font-size: 2.5rem;
      color: #667eea;
    }

    .page-title h1 {
      font-size: 2rem;
      font-weight: 700;
      color: #333;
      margin: 0;
    }

    .back-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 10px 25px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .back-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
      color: white;
    }

    /* Filter Section */
    .filter-section {
      background: #f8f9fa;
      padding: 25px;
      border-radius: 15px;
      margin-bottom: 30px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .filter-header {
      font-size: 1.2rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .filter-header i {
      color: #667eea;
    }

    .filter-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .filter-tab {
      padding: 10px 25px;
      border: 2px solid #e0e0e0;
      border-radius: 50px;
      background: white;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s;
      color: #6c757d;
    }

    .filter-tab:hover {
      border-color: #667eea;
      color: #667eea;
    }

    .filter-tab.active {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-color: transparent;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .date-picker-row {
      display: flex;
      gap: 15px;
      align-items: end;
      flex-wrap: wrap;
    }

    .date-group {
      flex: 1;
      min-width: 200px;
    }

    .date-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #495057;
      font-size: 0.9rem;
    }

    .date-group input {
      width: 100%;
      padding: 10px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s;
    }

    .date-group input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .apply-filter-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 10px 30px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .apply-filter-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    /* Stats Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s;
      border-left: 4px solid;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .stat-card.primary {
      border-left-color: #667eea;
    }

    .stat-card.success {
      border-left-color: #28a745;
    }

    .stat-card.warning {
      border-left-color: #ffc107;
    }

    .stat-card.danger {
      border-left-color: #dc3545;
    }

    .stat-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .stat-card-title {
      font-size: 0.9rem;
      color: #6c757d;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .stat-card-icon {
      width: 45px;
      height: 45px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
    }

    .stat-card.primary .stat-card-icon {
      background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stat-card.success .stat-card-icon {
      background: linear-gradient(135deg, #28a745, #4CAF50);
    }

    .stat-card.warning .stat-card-icon {
      background: linear-gradient(135deg, #ffc107, #FF9800);
    }

    .stat-card.danger .stat-card-icon {
      background: linear-gradient(135deg, #dc3545, #f44336);
    }

    .stat-card-value {
      font-size: 2.2rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 5px;
    }

    .stat-card-subtitle {
      font-size: 0.85rem;
      color: #6c757d;
    }

    /* Charts Section */
    .charts-section {
      background: white;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 30px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .section-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title i {
      color: #667eea;
    }

    .chart-placeholder {
      width: 100%;
      height: 400px;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-size: 1.2rem;
      font-weight: 600;
      border: 2px dashed #dee2e6;
    }

    /* Habits Table */
    .habits-table-section {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .table-responsive {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table thead {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    table th {
      padding: 15px;
      text-align: left;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    table tbody tr {
      border-bottom: 1px solid #e0e0e0;
      transition: all 0.3s;
    }

    table tbody tr:hover {
      background: #f8f9fa;
    }

    table td {
      padding: 15px;
      color: #495057;
    }

    .habit-name-col {
      font-weight: 600;
      color: #333;
    }

    .progress-badge {
      display: inline-block;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .progress-badge.high {
      background: #d4edda;
      color: #155724;
    }

    .progress-badge.medium {
      background: #fff3cd;
      color: #856404;
    }

    .progress-badge.low {
      background: #f8d7da;
      color: #721c24;
    }

    .type-badge {
      display: inline-block;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .type-badge.good {
      background: #d4edda;
      color: #155724;
    }

    .type-badge.bad {
      background: #f8d7da;
      color: #721c24;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #6c757d;
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 20px;
      opacity: 0.4;
    }

    .empty-state h3 {
      margin-bottom: 10px;
      color: #333;
    }

    @media (max-width: 768px) {
      .filter-tabs {
        flex-direction: column;
      }

      .filter-tab {
        width: 100%;
        text-align: center;
      }

      .date-picker-row {
        flex-direction: column;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <!-- Header -->
    <div class="header-section">
      <div class="page-title">
        <i class="bi bi-graph-up-arrow"></i>
        <h1>Reports & Analytics</h1>
      </div>
      <a href="h_home.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
      </a>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <div class="filter-header">
        <i class="bi bi-funnel"></i>
        Filter Reports
      </div>

      <!-- Time Period Tabs -->
      <div class="filter-tabs">
        <div class="filter-tab active" data-period="daily">
          <i class="bi bi-calendar-day"></i> Daily
        </div>
        <div class="filter-tab" data-period="weekly">
          <i class="bi bi-calendar-week"></i> Weekly
        </div>
        <div class="filter-tab" data-period="monthly">
          <i class="bi bi-calendar-month"></i> Monthly
        </div>
        <div class="filter-tab" data-period="yearly">
          <i class="bi bi-calendar-range"></i> Yearly
        </div>
        <div class="filter-tab" data-period="custom">
          <i class="bi bi-calendar2-range"></i> Custom Range
        </div>
      </div>

      <!-- Date Picker (Hidden by default, shown for Custom) -->
      <div class="date-picker-row" id="customDatePicker" style="display: none;">
        <div class="date-group">
          <label>Start Date</label>
          <input type="date" id="startDate" class="form-control">
        </div>
        <div class="date-group">
          <label>End Date</label>
          <input type="date" id="endDate" class="form-control">
        </div>
        <button class="apply-filter-btn" id="applyFilter">
          <i class="bi bi-check-circle"></i> Apply Filter
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
      <div class="stat-card primary">
        <div class="stat-card-header">
          <span class="stat-card-title">Total Habits</span>
          <div class="stat-card-icon">
            <i class="bi bi-list-check"></i>
          </div>
        </div>
        <div class="stat-card-value" id="totalHabits">0</div>
        <div class="stat-card-subtitle">Active habits tracked</div>
      </div>

      <div class="stat-card success">
        <div class="stat-card-header">
          <span class="stat-card-title">Completion Rate</span>
          <div class="stat-card-icon">
            <i class="bi bi-check-circle"></i>
          </div>
        </div>
        <div class="stat-card-value" id="completionRate">0%</div>
        <div class="stat-card-subtitle">Overall progress</div>
      </div>

      <div class="stat-card warning">
        <div class="stat-card-header">
          <span class="stat-card-title">Pomodoro Sessions</span>
          <div class="stat-card-icon">
            <i class="bi bi-clock-history"></i>
          </div>
        </div>
        <div class="stat-card-value" id="pomodoroSessions">0</div>
        <div class="stat-card-subtitle">Total sessions completed</div>
      </div>

      <div class="stat-card danger">
        <div class="stat-card-header">
          <span class="stat-card-title">Focus Time</span>
          <div class="stat-card-icon">
            <i class="bi bi-hourglass-split"></i>
          </div>
        </div>
        <div class="stat-card-value" id="focusTime">0h</div>
        <div class="stat-card-subtitle">Total hours focused</div>
      </div>
    </div>

    <!-- Progress Chart -->
    <div class="charts-section">
      <div class="section-title">
        <i class="bi bi-bar-chart-line"></i>
        Progress Overview
      </div>
      <div class="chart-placeholder">
        <i class="bi bi-graph-up"></i> Chart will be rendered here
      </div>
    </div>

    <!-- Habits Performance Table -->
    <div class="habits-table-section">
      <div class="section-title">
        <i class="bi bi-table"></i>
        Habit Performance Details
      </div>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Habit Name</th>
              <th>Type</th>
              <th>Progress</th>
              <th>Sessions</th>
              <th>Focus Time</th>
              <th>Completion</th>
            </tr>
          </thead>
          <tbody id="habitsTableBody">
            <tr>
              <td colspan="6" class="empty-state">
                <i class="bi bi-inbox"></i>
                <h3>No data available</h3>
                <p>Select a time period to view reports</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  $(document).ready(function() {
    let selectedPeriod = 'daily';

    // Filter tab click
    $('.filter-tab').click(function() {
      $('.filter-tab').removeClass('active');
      $(this).addClass('active');
      selectedPeriod = $(this).data('period');

      if (selectedPeriod === 'custom') {
        $('#customDatePicker').slideDown(300);
      } else {
        $('#customDatePicker').slideUp(300);
        loadReports(selectedPeriod);
      }
    });

    // Apply custom filter
    $('#applyFilter').click(function() {
      const startDate = $('#startDate').val();
      const endDate = $('#endDate').val();

      if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
      }

      if (new Date(startDate) > new Date(endDate)) {
        alert('Start date must be before end date');
        return;
      }

      loadReports('custom', startDate, endDate);
    });

    // Load reports function - CONNECTED TO BACKEND
    function loadReports(period, startDate = null, endDate = null) {
      console.log('Loading reports for:', period, startDate, endDate);
      
      // Show loading state
      $('#totalHabits').html('<div class="spinner-border spinner-border-sm"></div>');
      $('#completionRate').html('<div class="spinner-border spinner-border-sm"></div>');
      $('#pomodoroSessions').html('<div class="spinner-border spinner-border-sm"></div>');
      $('#focusTime').html('<div class="spinner-border spinner-border-sm"></div>');
      $('#habitsTableBody').html(`
        <tr>
          <td colspan="6" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 text-muted">Loading report data...</p>
          </td>
        </tr>
      `);

      // Build URL parameters
      let url = './reports/get_reports_data.php?period=' + period;
      if (period === 'custom' && startDate && endDate) {
        url += '&start_date=' + startDate + '&end_date=' + endDate;
      }

      console.log('Request URL:', url);

      // AJAX call to backend
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          console.log('✅ Report data received:', response);

          if (response.status === 'success') {
            // Update stats cards
            $('#totalHabits').text(response.stats.total_habits);
            $('#completionRate').text(response.stats.completion_rate + '%');
            $('#pomodoroSessions').text(response.stats.pomodoro_sessions);
            $('#focusTime').text(response.stats.focus_hours + 'h');

            // Update habits table
            if (response.habits && response.habits.length > 0) {
              let tableHtml = '';
              response.habits.forEach(function(habit) {
                // Determine progress badge class
                let progressClass = 'low';
                if (habit.progress >= 75) progressClass = 'high';
                else if (habit.progress >= 50) progressClass = 'medium';

                // Type badge
                let typeClass = habit.type === 'Good' ? 'good' : 'bad';

                // Completion icon
                let completionIcon = habit.completed 
                  ? '<i class="bi bi-check-circle text-success" title="Completed"></i>' 
                  : '<i class="bi bi-dash-circle text-warning" title="In Progress"></i>';

                // Sessions display (only for Good habits)
                let sessionsDisplay = habit.type === 'Good' ? habit.sessions : '-';
                let focusTimeDisplay = habit.type === 'Good' ? habit.focus_time : '-';

                tableHtml += `
                  <tr>
                    <td class="habit-name-col">${escapeHtml(habit.habit_name)}</td>
                    <td><span class="type-badge ${typeClass}">${habit.type}</span></td>
                    <td><span class="progress-badge ${progressClass}">${habit.progress}%</span></td>
                    <td>${sessionsDisplay}</td>
                    <td>${focusTimeDisplay}</td>
                    <td>${completionIcon}</td>
                  </tr>
                `;
              });
              $('#habitsTableBody').html(tableHtml);
            } else {
              $('#habitsTableBody').html(`
                <tr>
                  <td colspan="6" class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>No habits found</h3>
                    <p>No habit data available for this period</p>
                    ${response.date_range ? `<small>(${response.date_range.formatted_start} - ${response.date_range.formatted_end})</small>` : ''}
                  </td>
                </tr>
              `);
            }

            // Log additional insights (for debugging)
            if (response.insights) {
              console.log('📊 Top Habits:', response.insights.top_habits);
              console.log('⚠️ Struggling Habits:', response.insights.struggling_habits);
            }

            // Log chart data (ready for future charts)
            if (response.chart_data) {
              console.log('📈 Chart Data:', response.chart_data);
            }

          } else {
            console.error('❌ Error in response:', response.message);
            showError(response.message || 'Unknown error occurred');
          }
        },
        error: function(xhr, status, error) {
          console.error('❌ AJAX Error:', {
            status: status,
            error: error,
            response: xhr.responseText,
            statusCode: xhr.status
          });

          let errorMessage = 'Failed to load reports. ';
          if (xhr.status === 404) {
            errorMessage += 'Backend file not found. Please check if ./reports/get_reports_data.php exists.';
          } else if (xhr.status === 500) {
            errorMessage += 'Server error. Check PHP error logs.';
          } else if (xhr.status === 0) {
            errorMessage += 'Network error. Check your connection.';
          } else {
            errorMessage += 'Please try again.';
          }

          showError(errorMessage);
          
          // Show detailed error in console
          try {
            const errorResponse = JSON.parse(xhr.responseText);
            console.error('Server response:', errorResponse);
          } catch (e) {
            console.error('Raw response:', xhr.responseText);
          }
        }
      });
    }

    // Show error state
    function showError(message) {
      $('#totalHabits').text('0');
      $('#completionRate').text('0%');
      $('#pomodoroSessions').text('0');
      $('#focusTime').text('0h');
      $('#habitsTableBody').html(`
        <tr>
          <td colspan="6" class="empty-state">
            <i class="bi bi-exclamation-triangle text-danger"></i>
            <h3>Error Loading Data</h3>
            <p>${escapeHtml(message)}</p>
            <button class="btn btn-sm btn-primary mt-3" onclick="location.reload()">
              <i class="bi bi-arrow-clockwise"></i> Retry
            </button>
          </td>
        </tr>
      `);
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    // Load daily reports by default
    console.log('🚀 Initializing reports page...');
    loadReports('daily');

    // Set today's date as default for custom range
    const today = new Date().toISOString().split('T')[0];
    $('#endDate').val(today);
    const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
    $('#startDate').val(weekAgo);
  });
</script>




</body>

</html>
