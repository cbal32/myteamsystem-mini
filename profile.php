<?php
$dataFile = __DIR__ . "/data/members.json";

$members = file_exists($dataFile)
    ? json_decode(file_get_contents($dataFile), true)
    : [];

if (!is_array($members)) {
    $members = [];
}

$id = $_GET["id"] ?? null;
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
?>

<h1>
    <?php echo htmlspecialchars(($member["first_name"] ?? "") . " " . ($member["last_name"] ?? "")); ?>
</h1>

<p><a href="members.php">← Επιστροφή στη λίστα</a></p>

<h2>Βασικά στοιχεία</h2>
<p><strong>Τηλέφωνο:</strong> <?php echo htmlspecialchars($member["phone"] ?? ""); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($member["email"] ?? ""); ?></p>
<p><strong>Χώρα:</strong> <?php echo htmlspecialchars($member["country"] ?? ""); ?></p>

<h2>Social Media</h2>

<p><strong>Facebook:</strong>
    <?php if (!empty($member["facebook_url"])): ?>
        <a href="<?php echo htmlspecialchars($member["facebook_url"]); ?>" target="_blank">Άνοιγμα Facebook</a>
    <?php else: ?>
        Δεν έχει δηλωθεί
    <?php endif; ?>
</p>

<p><strong>Instagram:</strong>
    <?php if (!empty($member["instagram_url"])): ?>
        <a href="<?php echo htmlspecialchars($member["instagram_url"]); ?>" target="_blank">Άνοιγμα Instagram</a>
    <?php else: ?>
        Δεν έχει δηλωθεί
    <?php endif; ?>
</p>

<h2>Marketing Info</h2>
<p><strong>Πηγή:</strong> <?php echo htmlspecialchars($member["source"] ?? ""); ?></p>
<p><strong>Κατάσταση:</strong> <?php echo htmlspecialchars($member["status"] ?? ""); ?></p>
<p><strong>Ημερομηνία καταχώρησης:</strong> <?php echo htmlspecialchars($member["created_at"] ?? ""); ?></p>

<h2>Σχόλια ή περιγραφή</h2>
<p><?php echo nl2br(htmlspecialchars($member["description"] ?? "")); ?></p>

<h2>AI Contact Strategy</h2>
<p>Εδώ αργότερα θα εμφανίζονται τα AI σενάρια επικοινωνίας.</p>

<button disabled>Generate AI Contact Scenarios</button>