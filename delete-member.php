<link rel="stylesheet" href="assets/css/style.css">



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
    echo "<h1>Ο υποψήφιος δεν βρέθηκε</h1>";
    echo '<p><a href="members.php">Επιστροφή στη λίστα</a></p>';
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

$fullName = trim(($member["first_name"] ?? "") . " " . ($member["last_name"] ?? ""));
?>

<h1>Διαγραφή Υποψηφίου</h1>

<p>Είσαι σίγουρος ότι θέλεις να διαγράψεις τον υποψήφιο:</p>

<h2><?php echo htmlspecialchars($fullName); ?></h2>

<p style="color:red;"><strong>Προσοχή:</strong> Η διαγραφή δεν μπορεί να αναιρεθεί.</p>

<form method="post">
    <button type="submit" style="color:white;background:red;padding:10px 20px;">
        Ναι, διαγραφή
    </button>
</form>

<br>

<p>
    <a href="profile.php?id=<?php echo urlencode($id); ?>">
        Όχι, επιστροφή στο προφίλ
    </a>
</p>