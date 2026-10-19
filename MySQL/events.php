<?php
require_once 'db.php';
$regMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_event'])) {
    $enrollment = trim($_POST['enrollment_no'] ?? '');
    $eventId    = (int)($_POST['event_id'] ?? 0);
    $stmtStudent = $pdo->prepare("SELECT student_id FROM students WHERE enrollment_no = :enrollment");
    $stmtStudent->execute([':enrollment' => $enrollment]);
    $student = $stmtStudent->fetch();
    if (!$student) {
        $regMessage = "Enrollment number not found. Please register first.";
    } else {
        try {
            $stmtReg = $pdo->prepare("INSERT INTO event_registrations (student_id, event_id) VALUES (:s_id, :e_id)");
            $stmtReg->execute([
                ':s_id' => $student['student_id'],
                ':e_id' => $eventId
            ]);
            $regMessage = "Successfully registered for the event!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $regMessage = "You are already registered for this event.";
            } else {
                $regMessage = "Error: " . $e->getMessage();
            }
        }
    }
}
$stmtEvents = $pdo->query("SELECT * FROM events ORDER BY event_id ASC");
$events = $stmtEvents->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Events - StudentHub</title>
    <link rel="stylesheet" href="StudentHub.css">
</head>
<body>
    <h1>StudentHub Events</h1>
    <?php if (!empty($regMessage)): ?>
        <p><strong><?= htmlspecialchars($regMessage) ?></strong></p>
    <?php endif; ?>
    <h2>Upcoming College Events</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Event</th>
            <th>Action</th>
        </tr>
        <?php foreach ($events as $event): ?>
        <tr>
            <td><?= htmlspecialchars($event['event_date']) ?></td>
            <td><?= htmlspecialchars($event['event_title']) ?></td>
            <td>
                <form method="POST" action="events.php" style="border:none; padding:0; margin:0;">
                    <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                    <input type="text" name="enrollment_no" placeholder="Enrollment No" style="width:130px; font-size:12px;" required>
                    <button type="submit" name="register_event" style="padding:4px 8px; font-size:12px;">Register</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br><br>
    <footer>StudentHub © 2026</footer>
</body>
</html>