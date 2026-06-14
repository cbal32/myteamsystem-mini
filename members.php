<?php
$dataFile = __DIR__ . "/data/members.json";

$members = file_exists($dataFile)
    ? json_decode(file_get_contents($dataFile), true)
    : [];

if (!is_array($members)) {
    $members = [];
}
?>

<h1>Λίστα Υποψηφίων</h1>

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
            <th>Ημερομηνία</th>
        </tr>

        <?php foreach ($members as $member): ?>
            <tr>
                <td><?php echo htmlspecialchars(($member["first_name"] ?? "") . " " . ($member["last_name"] ?? "")); ?></td>
                <td><?php echo htmlspecialchars($member["phone"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($member["email"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($member["source"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($member["status"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($member["created_at"] ?? ""); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>