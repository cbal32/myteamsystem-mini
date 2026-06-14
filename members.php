<?php
$dataFile = __DIR__ . "/data/members.json";

$members = file_exists($dataFile)
    ? json_decode(file_get_contents($dataFile), true)
    : [];

if (!is_array($members)) {
    $members = [];
}

$totalProspects = count($members);
$today = date("Y-m-d");
$filter = $_GET["filter"] ?? "all";
$search = trim($_GET["search"] ?? "");

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

$filteredMembers = [];

foreach ($members as $member) {
    if ($search !== "") {
        $searchText = mb_strtolower(
            ($member["first_name"] ?? "") . " " .
            ($member["last_name"] ?? "") . " " .
            ($member["phone"] ?? "") . " " .
            ($member["email"] ?? "") . " " .
            ($member["facebook_url"] ?? "") . " " .
            ($member["instagram_url"] ?? "") . " " .
            ($member["profession"] ?? "") . " " .
            ($member["description"] ?? ""),
            "UTF-8"
        );

        if (mb_strpos($searchText, mb_strtolower($search, "UTF-8")) === false) {
            continue;
        }
    }

    $followUpDate = $member["next_follow_up_date"] ?? "";
    $priority = $member["priority"] ?? "Medium";

    if ($filter === "overdue" && $followUpDate !== "" && $followUpDate < $today) {
        $filteredMembers[] = $member;
    } elseif ($filter === "today" && $followUpDate === $today) {
        $filteredMembers[] = $member;
    } elseif ($filter === "scheduled" && $followUpDate !== "" && $followUpDate > $today) {
        $filteredMembers[] = $member;
    } elseif ($filter === "high" && $priority === "High") {
        $filteredMembers[] = $member;
    } elseif ($filter === "all") {
        $filteredMembers[] = $member;
    }
}

$members = $filteredMembers;
?>

<link rel="stylesheet" href="assets/css/style.css">
<div class="app-layout">

 <aside class="sidebar">

    <div class="profile-card">

  <img src="assets/images/avatar.jpg" class="profile-avatar-image">

        <div class="profile-name">
            Χρήστος Βαλσαμίδης
        </div>

        <div class="profile-role">
            ESSENS Leader
        </div>

    </div>

    <h2>MTS CRM</h2>
 <a href="members.php">📊 Dashboard</a>
<a href="members.php?filter=all">👥 Prospects</a>
<a href="add-member.php">➕ New Prospect</a>
<a href="members.php?filter=today">📅 Follow Ups</a>
<a href="members.php?filter=high">⭐ Priority</a>
<a href="#ai-tools">🤖 AI Tools</a>
<a href="#settings">⚙ Settings</a>
    </aside>

    <main class="main-content">

<div class="page-header">
    <h1>AI Prospect Manager</h1>
    <p>Διαχείριση υποψηφίων, follow-ups και AI επικοινωνίας.</p>
</div>

<h2>Dashboard</h2>

<div class="dashboard">

    <a href="members.php?filter=all">
        <div class="card">
            <strong>Σύνολο Prospects</strong><br>
            <?php echo $totalProspects; ?>
        </div>
    </a>

    <a href="members.php?filter=overdue">
        <div class="card">
            <strong>🔴 Overdue</strong><br>
            <?php echo $overdueCount; ?>
        </div>
    </a>

    <a href="members.php?filter=today">
        <div class="card">
            <strong>🟠 Today</strong><br>
            <?php echo $todayCount; ?>
        </div>
    </a>

    <a href="members.php?filter=scheduled">
        <div class="card">
            <strong>🟢 Scheduled</strong><br>
            <?php echo $scheduledCount; ?>
        </div>
    </a>

    <a href="members.php?filter=high">
        <div class="card">
            <strong>⭐ High Priority</strong><br>
            <?php echo $highPriorityCount; ?>
        </div>
    </a>

    <div class="card">
        <strong>✅ Εγγεγραμμένοι</strong><br>
        <?php echo $registeredCount; ?>
    </div>

</div>

<p>
    <strong>Ενεργό φίλτρο:</strong>
    <?php echo htmlspecialchars($filter); ?>
</p>

<?php if ($search !== ""): ?>
    <p>
        <strong>Αναζήτηση:</strong>
        <?php echo htmlspecialchars($search); ?>
    </p>
<?php endif; ?>

<form method="get" action="members.php" class="search-form">
    <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">

    <input
        type="text"
        name="search"
        value="<?php echo htmlspecialchars($search); ?>"
        placeholder="Αναζήτηση με όνομα, τηλέφωνο, email, επάγγελμα..."
    >

    <button type="submit">Search</button>

    <a href="members.php?filter=<?php echo urlencode($filter); ?>">Clear</a>
</form>

<p><a href="add-member.php" class="button-link">+ Προσθήκη νέου υποψηφίου</a></p>

<?php if (empty($members)): ?>
    <p>Δεν υπάρχουν υποψήφιοι για αυτό το φίλτρο.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Όνομα</th>
            <th>Τηλέφωνο</th>
            <th>Email</th>
            <th>Πηγή</th>
            <th>Κατάσταση</th>
            <th>Priority</th>
            <th>Follow-Up</th>
            <th>Ημερομηνία</th>
            <th>Ενέργειες</th>
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

                    if ($followUpDate === "") {
                        echo "<span style='color:#666;'>Δεν έχει οριστεί</span>";
                    } elseif ($followUpDate < $today) {
                        echo "<span style='color:red;font-weight:bold;'>🔴 Overdue</span><br>" . htmlspecialchars($followUpDate);
                    } elseif ($followUpDate === $today) {
                        echo "<span style='color:orange;font-weight:bold;'>🟠 Today</span><br>" . htmlspecialchars($followUpDate);
                    } else {
                        echo "<span style='color:green;font-weight:bold;'>🟢 Scheduled</span><br>" . htmlspecialchars($followUpDate);
                    }
                    ?>
                </td>

                <td><?php echo htmlspecialchars($member["created_at"] ?? ""); ?></td>

                <td>
                    <a href="edit-member.php?id=<?php echo urlencode($member["id"] ?? ""); ?>">
                        ✏ Edit
                    </a>

                    <br>

                    <a href="delete-member.php?id=<?php echo urlencode($member["id"] ?? ""); ?>" class="danger-link">
                        🗑 Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

    </main>
</div>