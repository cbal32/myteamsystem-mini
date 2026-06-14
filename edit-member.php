
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
$memberIndex = null;

foreach ($members as $index => $item) {
    if ((string)($item["id"] ?? "") === (string)$id) {
        $memberIndex = $index;
        break;
    }
}

if ($memberIndex === null) {
    echo "<h1>Ο υποψήφιος δεν βρέθηκε</h1>";
    echo '<p><a href="members.php">Επιστροφή στη λίστα</a></p>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $members[$memberIndex]["first_name"] = $_POST["first_name"] ?? "";
    $members[$memberIndex]["last_name"] = $_POST["last_name"] ?? "";
    $members[$memberIndex]["phone"] = $_POST["phone"] ?? "";
    $members[$memberIndex]["email"] = $_POST["email"] ?? "";
    $members[$memberIndex]["country"] = $_POST["country"] ?? "";

    $members[$memberIndex]["facebook_url"] = $_POST["facebook_url"] ?? "";
    $members[$memberIndex]["instagram_url"] = $_POST["instagram_url"] ?? "";

    $members[$memberIndex]["source"] = $_POST["source"] ?? "";
    $members[$memberIndex]["status"] = $_POST["status"] ?? "Νέος";
    $members[$memberIndex]["priority"] = $_POST["priority"] ?? "Medium";

    $members[$memberIndex]["profession"] = $_POST["profession"] ?? "";
    $members[$memberIndex]["family_status"] = $_POST["family_status"] ?? "";
    $members[$memberIndex]["age"] = $_POST["age"] ?? "";
    $members[$memberIndex]["interests"] = $_POST["interests"] ?? "";
    $members[$memberIndex]["goals"] = $_POST["goals"] ?? "";
    $members[$memberIndex]["available_time"] = $_POST["available_time"] ?? "";
    $members[$memberIndex]["interest_categories"] = $_POST["interest_categories"] ?? [];
    $members[$memberIndex]["social_observations"] = $_POST["social_observations"] ?? "";

    $members[$memberIndex]["next_follow_up_date"] = $_POST["next_follow_up_date"] ?? "";
    $members[$memberIndex]["next_action"] = $_POST["next_action"] ?? "";

    $members[$memberIndex]["description"] = $_POST["description"] ?? "";

    file_put_contents(
        $dataFile,
        json_encode($members, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    header("Location: profile.php?id=" . urlencode($id));
    exit;
}

$member = $members[$memberIndex];

function isSelected($current, $value) {
    return ($current === $value) ? "selected" : "";
}

function isChecked($array, $value) {
    return (is_array($array) && in_array($value, $array)) ? "checked" : "";
}
?>

<h1>Edit Prospect</h1>

<p><a href="profile.php?id=<?php echo urlencode($id); ?>">← Επιστροφή στο προφίλ</a></p>

<form method="post">

    <h2>Βασικά στοιχεία</h2>

    <input type="text" name="first_name" value="<?php echo htmlspecialchars($member["first_name"] ?? ""); ?>" placeholder="Όνομα"><br><br>

    <input type="text" name="last_name" value="<?php echo htmlspecialchars($member["last_name"] ?? ""); ?>" placeholder="Επώνυμο"><br><br>

    <input type="text" name="phone" value="<?php echo htmlspecialchars($member["phone"] ?? ""); ?>" placeholder="Τηλέφωνο"><br><br>

    <input type="email" name="email" value="<?php echo htmlspecialchars($member["email"] ?? ""); ?>" placeholder="Email"><br><br>

    <input type="text" name="country" value="<?php echo htmlspecialchars($member["country"] ?? ""); ?>" placeholder="Χώρα"><br><br>

    <h2>Social Media</h2>

    <input type="url" name="facebook_url" value="<?php echo htmlspecialchars($member["facebook_url"] ?? ""); ?>" placeholder="Facebook Profile URL"><br><br>

    <input type="url" name="instagram_url" value="<?php echo htmlspecialchars($member["instagram_url"] ?? ""); ?>" placeholder="Instagram Profile URL"><br><br>

    <h2>Marketing Info</h2>

    <label>Πηγή επαφής:</label><br>
    <select name="source">
        <?php
        $sources = ["", "Facebook", "Instagram", "TikTok", "Website", "Seminar", "Referral"];
        foreach ($sources as $source) {
            $label = $source === "" ? "Πηγή επαφής" : $source;
            echo "<option value=\"" . htmlspecialchars($source) . "\" " . isSelected($member["source"] ?? "", $source) . ">" . htmlspecialchars($label) . "</option>";
        }
        ?>
    </select><br><br>

    <label>Κατάσταση:</label><br>
    <select name="status">
        <?php
        $statuses = ["Νέος", "Επικοινωνήθηκε", "Ενδιαφέρεται", "Εγγράφηκε", "Δεν ενδιαφέρεται"];
        foreach ($statuses as $status) {
            echo "<option value=\"" . htmlspecialchars($status) . "\" " . isSelected($member["status"] ?? "Νέος", $status) . ">" . htmlspecialchars($status) . "</option>";
        }
        ?>
    </select><br><br>

    <label>Priority:</label><br>
    <select name="priority">
        <?php
        $priorities = ["Low", "Medium", "High"];
        foreach ($priorities as $priority) {
            echo "<option value=\"" . htmlspecialchars($priority) . "\" " . isSelected($member["priority"] ?? "Medium", $priority) . ">" . htmlspecialchars($priority) . "</option>";
        }
        ?>
    </select><br><br>

    <h2>Prospect Intelligence Profile</h2>

    <input type="text" name="profession" value="<?php echo htmlspecialchars($member["profession"] ?? ""); ?>" placeholder="Επάγγελμα"><br><br>

    <input type="text" name="family_status" value="<?php echo htmlspecialchars($member["family_status"] ?? ""); ?>" placeholder="Οικογενειακή κατάσταση"><br><br>

    <input type="number" name="age" value="<?php echo htmlspecialchars($member["age"] ?? ""); ?>" placeholder="Ηλικία"><br><br>

    <textarea name="interests" rows="3" cols="60" placeholder="Ενδιαφέροντα"><?php echo htmlspecialchars($member["interests"] ?? ""); ?></textarea><br><br>

    <textarea name="goals" rows="3" cols="60" placeholder="Οικονομικοί ή προσωπικοί στόχοι"><?php echo htmlspecialchars($member["goals"] ?? ""); ?></textarea><br><br>

    <input type="text" name="available_time" value="<?php echo htmlspecialchars($member["available_time"] ?? ""); ?>" placeholder="Διαθέσιμος χρόνος εβδομαδιαία"><br><br>

    <?php $selectedCategories = $member["interest_categories"] ?? []; ?>

    <p><strong>Κατηγορίες ενδιαφέροντος:</strong></p>

    <label><input type="checkbox" name="interest_categories[]" value="Άρωμα" <?php echo isChecked($selectedCategories, "Άρωμα"); ?>> Άρωμα</label><br>
    <label><input type="checkbox" name="interest_categories[]" value="Συμπληρώματα" <?php echo isChecked($selectedCategories, "Συμπληρώματα"); ?>> Συμπληρώματα</label><br>
    <label><input type="checkbox" name="interest_categories[]" value="Καλλυντικά" <?php echo isChecked($selectedCategories, "Καλλυντικά"); ?>> Καλλυντικά</label><br>
    <label><input type="checkbox" name="interest_categories[]" value="Επιχείρηση" <?php echo isChecked($selectedCategories, "Επιχείρηση"); ?>> Επιχείρηση</label><br>
    <label><input type="checkbox" name="interest_categories[]" value="Work From Home" <?php echo isChecked($selectedCategories, "Work From Home"); ?>> Work From Home</label><br>
    <label><input type="checkbox" name="interest_categories[]" value="Extra Income" <?php echo isChecked($selectedCategories, "Extra Income"); ?>> Extra Income</label><br><br>

    <textarea name="social_observations" rows="5" cols="60" placeholder="Τι παρατήρησες από τα social media του υποψηφίου;"><?php echo htmlspecialchars($member["social_observations"] ?? ""); ?></textarea><br><br>

    <h2>Follow-Up Manager</h2>

    <label>Επόμενη επικοινωνία:</label><br>
    <input type="date" name="next_follow_up_date" value="<?php echo htmlspecialchars($member["next_follow_up_date"] ?? ""); ?>"><br><br>

    <label>Επόμενη ενέργεια:</label><br>
    <input type="text" name="next_action" value="<?php echo htmlspecialchars($member["next_action"] ?? ""); ?>" placeholder="π.χ. Πρόσκληση στο webinar"><br><br>

    <h2>Περιγραφή</h2>

    <textarea name="description" rows="6" cols="70" placeholder="Σχόλια ή περιγραφή υποψηφίου"><?php echo htmlspecialchars($member["description"] ?? ""); ?></textarea><br><br>

    <button type="submit">Αποθήκευση Αλλαγών</button>

</form>