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

$fullName = trim(($member["first_name"] ?? "") . " " . ($member["last_name"] ?? ""));
$selectedCategories = $member["interest_categories"] ?? [];
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="app-layout">

    <aside class="sidebar">

        <div class="profile-card">
            <div class="profile-avatar">CV</div>
            <div class="profile-name">Χρήστος Βαλσαμίδης</div>
            <div class="profile-role">ESSENS Leader</div>
        </div>

        <h2>MTS CRM</h2>

        <a href="members.php">📊 Dashboard</a>
        <a href="members.php?filter=all">👥 Prospects</a>
        <a href="add-member.php">➕ New Prospect</a>
        <a href="members.php?filter=today">📅 Follow Ups</a>
        <a href="members.php?filter=high">⭐ Priority</a>
        <a href="members.php#ai-tools">🤖 AI Tools</a>
        <a href="#settings">⚙ Settings</a>

    </aside>

    <main class="main-content">

        <div class="page-header">
            <h1>Edit Prospect</h1>
            <p>Επεξεργασία στοιχείων για: <?php echo htmlspecialchars($fullName); ?></p>
        </div>

        <p>
            <a class="button-link" href="profile.php?id=<?php echo urlencode($id); ?>">
                ← Επιστροφή στο προφίλ
            </a>

            <a class="button-link" href="members.php">
                Προβολή λίστας
            </a>
        </p>

        <form method="post">

            <div class="profile-grid">

                <div class="section-card">
                    <h2>Βασικά στοιχεία</h2>

                   <div class="form-group">
         <label>Όνομα</label>
    <input type="text" name="first_name" value="<?php echo htmlspecialchars($member["first_name"] ?? ""); ?>">
</div>

      <div class="form-group">
    <label>Επώνυμο</label>
    <input type="text" name="last_name" value="<?php echo htmlspecialchars($member["last_name"] ?? ""); ?>">
</div>

<div class="form-group">
        <label>Τηλέφωνο</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($member["phone"] ?? ""); ?>" placeholder="Τηλέφωνο"><br><br>
</div>
                    <div class="form-group">
                            <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($member["email"] ?? ""); ?>" placeholder="Email"><br><br>
</div>
                    <div class="form-group">
                            <label>Χώρα</label>
                    <input type="text" name="country" value="<?php echo htmlspecialchars($member["country"] ?? ""); ?>" placeholder="Χώρα">
                    </div>
                </div>

                <div class="section-card">
                    <h2>Social & Marketing</h2>

                    <input type="url" name="facebook_url" value="<?php echo htmlspecialchars($member["facebook_url"] ?? ""); ?>" placeholder="Facebook Profile URL"><br><br>

                    <input type="url" name="instagram_url" value="<?php echo htmlspecialchars($member["instagram_url"] ?? ""); ?>" placeholder="Instagram Profile URL"><br><br>

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
                    </select>
                </div>

            </div>

            <div class="section-card">
                <h2>Περιγραφή</h2>

                <label>Σχόλια / Περιγραφή</label>

<textarea name="description" rows="6"><?php echo htmlspecialchars($member["description"] ?? ""); ?></textarea>
            </div>

            <div class="section-card">
                <h2>Prospect Intelligence Profile</h2>

                <div class="profile-grid">
                    <div>
                    <div class="form-group">
    <label>Επάγγελμα</label>
    <input type="text" name="profession"
           value="<?php echo htmlspecialchars($member["profession"] ?? ""); ?>">
</div>

<div class="form-group">
    <label>Οικογενειακή κατάσταση</label>
    <input type="text" name="family_status"
           value="<?php echo htmlspecialchars($member["family_status"] ?? ""); ?>">
</div>

<div class="form-group">
    <label>Ηλικία</label>
    <input type="number" name="age"
           value="<?php echo htmlspecialchars($member["age"] ?? ""); ?>">
</div>

<div class="form-group">
    <label>Διαθέσιμος χρόνος εβδομαδιαία</label>
    <input type="text" name="available_time"
           value="<?php echo htmlspecialchars($member["available_time"] ?? ""); ?>">
</div>
</div>

                    <div>
                        <label>Ενδιαφέροντα</label>

<textarea name="interests" rows="4"><?php echo htmlspecialchars($member["interests"] ?? ""); ?></textarea><br><br>

                        <label>Στόχοι</label>

<textarea name="goals" rows="4"><?php echo htmlspecialchars($member["goals"] ?? ""); ?></textarea>
                    </div>
                </div>

                <p><strong>Κατηγορίες ενδιαφέροντος:</strong></p>

                <div class="checkbox-grid">
                    <label><input type="checkbox" name="interest_categories[]" value="Άρωμα" <?php echo isChecked($selectedCategories, "Άρωμα"); ?>> Άρωμα</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Συμπληρώματα" <?php echo isChecked($selectedCategories, "Συμπληρώματα"); ?>> Συμπληρώματα</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Καλλυντικά" <?php echo isChecked($selectedCategories, "Καλλυντικά"); ?>> Καλλυντικά</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Επιχείρηση" <?php echo isChecked($selectedCategories, "Επιχείρηση"); ?>> Επιχείρηση</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Work From Home" <?php echo isChecked($selectedCategories, "Work From Home"); ?>> Work From Home</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Extra Income" <?php echo isChecked($selectedCategories, "Extra Income"); ?>> Extra Income</label>
                </div>

                <br>

                <label>Παρατηρήσεις Social Media</label>

<textarea name="social_observations" rows="5"><?php echo htmlspecialchars($member["social_observations"] ?? ""); ?></textarea>
            </div>

<div class="section-card">
    <h2>Follow-Up Manager</h2>

    <div class="followup-grid">

        <div class="form-group">
            <label>Επόμενη επικοινωνία</label>

            <input
                type="date"
                name="next_follow_up_date"
                value="<?php echo htmlspecialchars($member["next_follow_up_date"] ?? ""); ?>">
        </div>

        <div class="form-group">
            <label>Επόμενη ενέργεια</label>

            <input
                type="text"
                name="next_action"
                value="<?php echo htmlspecialchars($member["next_action"] ?? ""); ?>">
        </div>

    </div>

</div>



                    </div>
                </div>
            </div>

            <div class="section-card">
                <button type="submit">Αποθήκευση Αλλαγών</button>

                <a class="button-link" href="profile.php?id=<?php echo urlencode($id); ?>">
                    Άκυρο
                </a>
            </div>

        </form>

    </main>
</div>