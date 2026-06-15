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

<?php require __DIR__ . "/includes/layout-start.php"; ?>

<div class="page-header">
    <h1><?php echo t('prospect_manager'); ?></h1>
    <p><?php echo t('prospect_manager_desc'); ?></p>
</div>

<h2><?php echo t('dashboard'); ?></h2>

<div class="dashboard">

    <a href="members.php?filter=all">
        <div class="card">
            <strong><?php echo t('total_prospects'); ?></strong><br>
            <?php echo $totalProspects; ?>
        </div>
    </a>

    <a href="members.php?filter=overdue">
        <div class="card">
            <strong>🔴 <?php echo t('overdue'); ?></strong><br>
            <?php echo $overdueCount; ?>
        </div>
    </a>

    <a href="members.php?filter=today">
        <div class="card">
            <strong>🟠 <?php echo t('today'); ?></strong><br>
            <?php echo $todayCount; ?>
        </div>
    </a>

    <a href="members.php?filter=scheduled">
        <div class="card">
            <strong>🟢 <?php echo t('scheduled'); ?></strong><br>
            <?php echo $scheduledCount; ?>
        </div>
    </a>

    <a href="members.php?filter=high">
        <div class="card">
            <strong>⭐ <?php echo t('high_priority'); ?></strong><br>
            <?php echo $highPriorityCount; ?>
        </div>
    </a>

    <div class="card">
        <strong>✅ <?php echo t('registered'); ?></strong><br>
        <?php echo $registeredCount; ?>
    </div>

</div>

<p>
    <strong><?php echo t('active_filter'); ?>:</strong>
    <?php echo htmlspecialchars($filter); ?>
</p>

<?php if ($search !== ""): ?>
    <p>
        <strong><?php echo t('search_term'); ?>:</strong>
        <?php echo htmlspecialchars($search); ?>
    </p>
<?php endif; ?>

<form method="get" action="members.php" class="search-form">
    <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">

    <input
        type="text"
        name="search"
        value="<?php echo htmlspecialchars($search); ?>"
        placeholder="<?php echo t('search_extended'); ?>"
    >

    <button type="submit"><?php echo t('search'); ?></button>

    <a href="members.php?filter=<?php echo urlencode($filter); ?>">
        <?php echo t('clear'); ?>
    </a>
</form>

<p>
    <a href="add-member.php" class="button-link">
        + <?php echo t('add_new_prospect'); ?>
    </a>
</p>

<?php if (empty($members)): ?>
    <p><?php echo t('no_prospects_filter'); ?></p>
<?php else: ?>
    <table>
        <tr>
            <th><?php echo t('name'); ?></th>
            <th><?php echo t('phone'); ?></th>
            <th><?php echo t('email'); ?></th>
            <th><?php echo t('source'); ?></th>
            <th><?php echo t('status'); ?></th>
            <th><?php echo t('priority_col'); ?></th>
            <th><?php echo t('follow_up'); ?></th>
            <th><?php echo t('date'); ?></th>
            <th><?php echo t('actions'); ?></th>
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
               <td>

<?php

$status = $member["status"] ?? "";

switch ($status) {

    case "Νέος":
        echo t('status_new');
        break;

    case "Επικοινωνήθηκε":
        echo t('status_contacted');
        break;

    case "Ενδιαφέρεται":
        echo t('status_interested');
        break;

    case "Εγγράφηκε":
        echo t('status_registered');
        break;

    case "Δεν ενδιαφέρεται":
        echo t('status_not_interested');
        break;

    default:
        echo htmlspecialchars($status);
}

?>

</td>

                <td>
                    <?php
                    $priority = $member["priority"] ?? "Medium";

                    switch ($priority) {
                        case "High":
    echo "<span style='color:red;font-weight:bold;'>🔴 " . t('priority_high') . "</span>";
    break;

case "Low":
    echo "<span style='color:green;font-weight:bold;'>🟢 " . t('priority_low') . "</span>";
    break;

default:
    echo "<span style='color:orange;font-weight:bold;'>🟠 " . t('priority_medium') . "</span>";
                    }
                    ?>
                </td>

                <td>
                    <?php
                    $followUpDate = $member["next_follow_up_date"] ?? "";

                    if ($followUpDate === "") {
                        echo "<span style='color:#666;'>" . t('not_set') . "</span>";
                    } elseif ($followUpDate < $today) {
                        echo "<span style='color:red;font-weight:bold;'>🔴 " . t('overdue') . "</span><br>" . htmlspecialchars($followUpDate);
                    } elseif ($followUpDate === $today) {
                        echo "<span style='color:orange;font-weight:bold;'>🟠 " . t('today') . "</span><br>" . htmlspecialchars($followUpDate);
                    } else {
                        echo "<span style='color:green;font-weight:bold;'>🟢 " . t('scheduled') . "</span><br>" . htmlspecialchars($followUpDate);
                    }
                    ?>
                </td>

                <td><?php echo htmlspecialchars($member["created_at"] ?? ""); ?></td>

                <td>
                    <a href="edit-member.php?id=<?php echo urlencode($member["id"] ?? ""); ?>">
                        ✏ <?php echo t('edit'); ?>
                    </a>

                    <br>

                    <a href="delete-member.php?id=<?php echo urlencode($member["id"] ?? ""); ?>" class="danger-link">
                        🗑 <?php echo t('delete'); ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require __DIR__ . "/includes/layout-end.php"; ?>