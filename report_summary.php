<?php
require_once 'db_connect.php';
session_start();

// Allow access to both admins and users
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$viewer_id = $_SESSION['admin_id'] ?? $_SESSION['user_id'];
$is_admin = isset($_SESSION['admin_id']);

// Fetch reports
if ($is_admin) {
    // Admin sees reports they generated
    $stmt = $conn->prepare("SELECT * FROM tblReports WHERE admin_id = ? ORDER BY generated_at DESC");
    $stmt->execute([$viewer_id]);
} else {
    // User sees reports that include their habits
    $stmt = $conn->prepare("
        SELECT DISTINCT r.*
        FROM tblReports r
        JOIN tblReportDetails d ON r.report_id = d.report_id
        WHERE d.user_id = ?
        ORDER BY r.generated_at DESC
    ");
    $stmt->execute([$viewer_id]);
}
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Habit Reports</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'navbar.php'; ?>
  <div class="form-container">
    <h2>Generated Reports</h2>

    <?php if (empty($reports)): ?>
      <p>No reports available.</p>
    <?php else: ?>
      <?php foreach ($reports as $r): ?>
        <div class="report-block">
          <h3><?php echo htmlspecialchars($r['report_title']); ?></h3>
          <p><em>Generated on: <?php echo date("F j, Y, g:i a", strtotime($r['generated_at'])); ?></em></p>

          <?php
          // Fetch report details
          $detail_sql = "
              SELECT u.username, h.habit_name, d.completion_rate, d.notes
              FROM tblReportDetails d
              JOIN tblUsers u ON d.user_id = u.user_id
              JOIN tblHabits h ON d.habit_id = h.habit_id
              WHERE d.report_id = ?" . ($is_admin ? "" : " AND d.user_id = ?");
          $params = $is_admin ? [$r['report_id']] : [$r['report_id'], $viewer_id];

          $stmt = $conn->prepare($detail_sql);
          $stmt->execute($params);
          $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
          ?>

          <?php if (empty($details)): ?>
            <p>No habit data found in this report.</p>
          <?php else: ?>
            <table class="styled-table">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Habit</th>
                  <th>Completion Rate</th>
                  <th>Notes</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($details as $d): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($d['username']); ?></td>
                    <td><?php echo htmlspecialchars($d['habit_name']); ?></td>
                    <td><?php echo $d['completion_rate']; ?>%</td>
                    <td><?php echo htmlspecialchars($d['notes']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
        <hr>
      <?php endforeach; ?>
    <?php endif; ?>

    <p><a href="<?php echo $is_admin ? 'admin_dashboard.php' : 'dashboard.php'; ?>">← Back to Dashboard</a></p>
  </div>
</body>
</html>