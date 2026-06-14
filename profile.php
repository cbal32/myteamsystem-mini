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
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "add_timeline_note") {
    $note = trim($_POST["timeline_note"] ?? "");
    $type = $_POST["timeline_type"] ?? "Σημείωση";

    if ($note !== "") {
        foreach ($members as &$item) {
            if ((string)($item["id"] ?? "") === (string)$id) {
                if (empty($item["timeline"]) || !is_array($item["timeline"])) {
                    $item["timeline"] = [];
                }

                $item["timeline"][] = [
                    "date" => date("Y-m-d H:i:s"),
                    "type" => $type,
                    "note" => $note
                ];

                $member = $item;
                break;
            }
        }

        unset($item);

        file_put_contents(
            $dataFile,
            json_encode($members, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        header("Location: profile.php?id=" . urlencode($id));
        exit;
    }
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
<h2>Timeline / Ιστορικό Επικοινωνίας</h2>

<form method="post">
    <input type="hidden" name="action" value="add_timeline_note">

    <textarea name="timeline_note" rows="4" cols="60" placeholder="Γράψε σημείωση επικοινωνίας..."></textarea><br><br>

    <select name="timeline_type">
        <option value="Σημείωση">Σημείωση</option>
        <option value="Τηλεφώνημα">Τηλεφώνημα</option>
        <option value="Facebook Message">Facebook Message</option>
        <option value="Instagram Message">Instagram Message</option>
        <option value="Zoom">Zoom</option>
        <option value="Webinar">Webinar</option>
        <option value="Follow-up">Follow-up</option>
    </select><br><br>

    <button type="submit">Προσθήκη στο Timeline</button>
</form>

<?php if (!empty($member["timeline"]) && is_array($member["timeline"])): ?>
    <ul>
        <?php foreach (array_reverse($member["timeline"]) as $entry): ?>
            <li>
                <strong><?php echo htmlspecialchars($entry["date"] ?? ""); ?></strong>
                -
                <?php echo htmlspecialchars($entry["type"] ?? ""); ?>
                <br>
                <?php echo nl2br(htmlspecialchars($entry["note"] ?? "")); ?>
            </li>
            <br>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Δεν υπάρχει ακόμα ιστορικό επικοινωνίας.</p>
<?php endif; ?>
<h2>AI Contact Strategy</h2>
<p>Εδώ αργότερα θα εμφανίζονται τα AI σενάρια επικοινωνίας.</p>

<button disabled>Generate AI Contact Scenarios</button>