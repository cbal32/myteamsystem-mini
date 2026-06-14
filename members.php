<?php
$dataFile = __DIR__ . "/data/members.json";

$members = file_exists($dataFile)
    ? json_decode(file_get_contents($dataFile), true)
    : [];

if (!is_array($members)) {
    $members = [];
}
?>


<?php
$totalProspects = count($members);
$today = date("Y-m-d");

$overdueCount = 0;
$todayCount = 0;
$scheduledCount = 0;
$highPriorityCount = 0;
$registeredCount = 0;

foreach ($members as $member) {
    $followUpDate = $member["next_follow_up_date"] ?? "";
    $priority = $member["priority"] ?? "Medium";
    $status = $member["status"] ?? "";

    if ($followUpDate !== "") {
        if ($followUpDate < $today) {
            $overdueCount++;
        } elseif ($followUpDate === $today) {
            $todayCount++;
        } else {
            $scheduledCount++;
        }
    }

    if ($priority === "High") {
        $highPriorityCount++;
    }

    if ($status === "Εγγράφηκε") {
        $registeredCount++;
    }
}
?>



<h1>Λίστα Υποψηφίων</h1>

<h2>Dashboard</h2>

<div style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom:25px;">

    <div style="border:1px solid #ccc; padding:15px; min-width:160px;">
        <strong>Σύνολο Prospects</strong><br>
        <?php echo $totalProspects; ?>
    </div>

    <div style="border:1px solid #ccc; padding:15px; min-width:160px;">
        <strong>🔴 Overdue</strong><br>
        <?php echo $overdueCount; ?>
    </div>

    <div style="border:1px solid #ccc; padding:15px; min-width:160px;">
        <strong>🟠 Today</strong><br>
        <?php echo $todayCount; ?>
    </div>

    <div style="border:1px solid #ccc; padding:15px; min-width:160px;">
        <strong>🟢 Scheduled</strong><br>
        <?php echo $scheduledCount; ?>
    </div>

    <div style="border:1px solid #ccc; padding:15px; min-width:160px;">
        <strong>⭐ High Priority</strong><br>
        <?php echo $highPriorityCount; ?>
    </div>

    <div style="border:1px solid #ccc; padding:15px; min-width:160px;">
        <strong>✅ Εγγεγραμμένοι</strong><br>
        <?php echo $registeredCount; ?>
    </div>

</div>


<p><a href="add-member.php">+ Προσθήκη νέου υποψηφίου</a></p>

<?php if (empty($members)): ?>
    <p>Δεν υπάρχουν ακόμα υποψήφιοι.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Όνομα</th>
            <th>Τηλέφωνο</th>
            <th>Email</th>
            <th>Πηγή</th>
            <th>Κατάσταση</th>
            <th>Priority</th>
            <th>Follow-Up</th>
            <th>Ημερομηνία</th>
        </tr>

<?php foreach ($members as $member): ?>
    <tr>

        <td>
            <a href="profile.php?id=<?php echo urlencode($member["id"] ?? ""); ?>">
                <?php echo htmlspecialchars(($member["first_name"] ?? "") . " " . ($member["last_name"] ?? "")); ?>
            </a>
        </td>

        <td><?php echo htmlspecialchars($member["phone"] ?? ""); ?></td>

        <td><?php echo htmlspecialchars($member["email"] ?? ""); ?></td>

        <td><?php echo htmlspecialchars($member["source"] ?? ""); ?></td>

        <td><?php echo htmlspecialchars($member["status"] ?? ""); ?></td>
        <td>
<?php

$priority = $member["priority"] ?? "Medium";

switch ($priority) {

    case "High":
        echo "<span style='color:red;font-weight:bold;'>🔴 High</span>";
        break;

    case "Low":
        echo "<span style='color:green;font-weight:bold;'>🟢 Low</span>";
        break;

    default:
        echo "<span style='color:orange;font-weight:bold;'>🟠 Medium</span>";
}

?>
</td>

        <td>
            <?php
            $followUpDate = $member["next_follow_up_date"] ?? "";
            $today = date("Y-m-d");

            if ($followUpDate === "") {

                echo "Δεν έχει οριστεί";

            } elseif ($followUpDate < $today) {

                echo "🔴 Overdue<br>" . htmlspecialchars($followUpDate);

            } elseif ($followUpDate === $today) {

                echo "🟠 Today<br>" . htmlspecialchars($followUpDate);

            } else {

                echo "🟢 Scheduled<br>" . htmlspecialchars($followUpDate);

            }
            ?>
        </td>

        <td><?php echo htmlspecialchars($member["created_at"] ?? ""); ?></td>

    </tr>
<?php endforeach; ?>
    </table>
<?php endif; ?>