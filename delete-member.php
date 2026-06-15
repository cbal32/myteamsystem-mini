<?php

$dataFile = __DIR__ . "/data/members.json";

$members = file_exists($dataFile)
    ? json_decode(file_get_contents($dataFile), true)
    : [];

if (!is_array($members)) {
    $members = [];
}

$id = $_GET["id"] ?? "";

$member = null;

foreach ($members as $item) {

    if ((string)($item["id"] ?? "") === (string)$id) {
        $member = $item;
        break;
    }
}

if (!$member) {

    require __DIR__ . "/includes/layout-start.php";
    ?>

    <div class="section-card">

        <h1><?php echo t('prospect_not_found'); ?></h1>

        <a class="button-link" href="members.php">
            <?php echo t('back_to_list'); ?>
        </a>

    </div>

    <?php

    require __DIR__ . "/includes/layout-end.php";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $newMembers = [];

    foreach ($members as $item) {

        if ((string)($item["id"] ?? "") !== (string)$id) {
            $newMembers[] = $item;
        }
    }

    file_put_contents(
        $dataFile,
        json_encode($newMembers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    header("Location: members.php");
    exit;
}

$fullName = trim(
    ($member["first_name"] ?? "") .
    " " .
    ($member["last_name"] ?? "")
);

require __DIR__ . "/includes/layout-start.php";
?>

<div class="page-header">

    <h1><?php echo t('delete_prospect'); ?></h1>

    <p>
        <?php echo t('delete_prospect_desc'); ?>
    </p>

</div>

<div class="section-card" style="max-width:700px;">

    <h2>
        <?php echo htmlspecialchars($fullName); ?>
    </h2>

    <p>
        <?php echo t('delete_confirmation_text'); ?>
    </p>

    <div
        style="
        background:#fef2f2;
        border:1px solid #fecaca;
        color:#991b1b;
        padding:16px;
        border-radius:12px;
        margin:20px 0;
    ">

        <strong>
            ⚠ <?php echo t('warning'); ?>
        </strong>

        <br><br>

        <?php echo t('delete_warning_text'); ?>

    </div>

    <form method="post">

        <div style="display:flex;gap:12px;flex-wrap:wrap;">

            <button
                type="submit"
                style="
                background:#dc2626;
                color:white;
                border:none;
                padding:12px 24px;
                border-radius:10px;
                cursor:pointer;
            ">
                🗑 <?php echo t('yes_delete'); ?>
            </button>

            <a
                class="button-link"
                href="profile.php?id=<?php echo urlencode($id); ?>">
                <?php echo t('cancel'); ?>
            </a>

        </div>

    </form>

</div>

<?php require __DIR__ . "/includes/layout-end.php"; ?>