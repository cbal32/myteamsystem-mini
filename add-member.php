<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dataFile = __DIR__ . "/data/members.json";

    $members = file_exists($dataFile)
        ? json_decode(file_get_contents($dataFile), true)
        : [];

    if (!is_array($members)) {
        $members = [];
    }

    $newMember = [
        "id" => time(),
        "first_name" => $_POST["first_name"] ?? "",
        "last_name" => $_POST["last_name"] ?? "",
        "phone" => $_POST["phone"] ?? "",
        "email" => $_POST["email"] ?? "",
        "country" => $_POST["country"] ?? "",
        "facebook_url" => $_POST["facebook_url"] ?? "",
        "instagram_url" => $_POST["instagram_url"] ?? "",
        "source" => $_POST["source"] ?? "",
        "status" => $_POST["status"] ?? "Νέος",
        "description" => $_POST["description"] ?? "",
        "profession" => $_POST["profession"] ?? "",
        "family_status" => $_POST["family_status"] ?? "",
        "age" => $_POST["age"] ?? "",
        "interests" => $_POST["interests"] ?? "",
        "goals" => $_POST["goals"] ?? "",
        "available_time" => $_POST["available_time"] ?? "",
        "interest_categories" => $_POST["interest_categories"] ?? [],
        "social_observations" => $_POST["social_observations"] ?? "",
        "next_follow_up_date" => $_POST["next_follow_up_date"] ?? "",
        "next_action" => $_POST["next_action"] ?? "",
        "priority" => $_POST["priority"] ?? "Medium",
        "timeline" => [],
        "created_at" => date("Y-m-d H:i:s")
    ];

    $members[] = $newMember;

    file_put_contents(
        $dataFile,
        json_encode($members, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    $message = "Ο υποψήφιος αποθηκεύτηκε επιτυχώς.";
}
?>

<?php require __DIR__ . "/includes/layout-start.php"; ?>

        <div class="page-header">
            <h1>New Prospect</h1>
            <p>Καταχώρηση νέου υποψηφίου συνεργάτη και προετοιμασία AI ανάλυσης.</p>
        </div>

        <?php if ($message): ?>
            <div class="section-card" style="border-left: 5px solid #16a34a;">
                <strong style="color:#166534;"><?php echo htmlspecialchars($message); ?></strong>
                <br><br>
                <a class="button-link" href="members.php">Προβολή λίστας υποψηφίων</a>
            </div>
        <?php endif; ?>

        <form method="post">

            <div class="profile-grid">

                <div class="section-card">
                    <h2>Βασικά στοιχεία</h2>

                    <input type="text" name="first_name" placeholder="Όνομα"><br><br>
                    <input type="text" name="last_name" placeholder="Επώνυμο"><br><br>
                    <input type="text" name="phone" placeholder="Τηλέφωνο"><br><br>
                    <input type="email" name="email" placeholder="Email"><br><br>
                    <input type="text" name="country" placeholder="Χώρα">
                </div>

                <div class="section-card">
                    <h2>Social & Marketing</h2>

                    <input type="url" name="facebook_url" placeholder="Facebook Profile URL"><br><br>
                    <input type="url" name="instagram_url" placeholder="Instagram Profile URL"><br><br>

                    <select name="source">
                        <option value="">Πηγή επαφής</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Instagram">Instagram</option>
                        <option value="TikTok">TikTok</option>
                        <option value="Website">Website</option>
                        <option value="Seminar">Seminar</option>
                        <option value="Referral">Referral</option>
                    </select><br><br>

                    <select name="status">
                        <option value="Νέος">Νέος</option>
                        <option value="Επικοινωνήθηκε">Επικοινωνήθηκε</option>
                        <option value="Ενδιαφέρεται">Ενδιαφέρεται</option>
                        <option value="Εγγράφηκε">Εγγράφηκε</option>
                        <option value="Δεν ενδιαφέρεται">Δεν ενδιαφέρεται</option>
                    </select><br><br>

                    <select name="priority">
                        <option value="Low">Low Priority</option>
                        <option value="Medium" selected>Medium Priority</option>
                        <option value="High">High Priority</option>
                    </select>
                </div>

            </div>

            <div class="section-card">
                <h2>Σχόλια ή περιγραφή</h2>
                <textarea name="description" rows="6" placeholder="Σχόλια ή μικρό βιογραφικό υποψηφίου"></textarea>
            </div>

            <div class="section-card">
                <h2>Prospect Intelligence Profile</h2>

                <div class="profile-grid">
                    <div>
                        <input type="text" name="profession" placeholder="Επάγγελμα"><br><br>
                        <input type="text" name="family_status" placeholder="Οικογενειακή κατάσταση"><br><br>
                        <input type="number" name="age" placeholder="Ηλικία"><br><br>
                        <input type="text" name="available_time" placeholder="Διαθέσιμος χρόνος εβδομαδιαία">
                    </div>

                    <div>
                        <textarea name="interests" rows="4" placeholder="Ενδιαφέροντα"></textarea><br><br>
                        <textarea name="goals" rows="4" placeholder="Οικονομικοί ή προσωπικοί στόχοι"></textarea>
                    </div>
                </div>

                <p><strong>Κατηγορίες ενδιαφέροντος:</strong></p>

                <div class="checkbox-grid">
                    <label><input type="checkbox" name="interest_categories[]" value="Άρωμα"> Άρωμα</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Συμπληρώματα"> Συμπληρώματα</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Καλλυντικά"> Καλλυντικά</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Επιχείρηση"> Επιχείρηση</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Work From Home"> Work From Home</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Extra Income"> Extra Income</label>
                </div>

                <br>

                <textarea name="social_observations" rows="5" placeholder="Τι παρατήρησες από τα social media του υποψηφίου;"></textarea>
            </div>

            <div class="section-card">
                <h2>Follow-Up Manager</h2>

                <div class="profile-grid">
                    <div>
                        <label>Επόμενη επικοινωνία:</label><br>
                        <input type="date" name="next_follow_up_date">
                    </div>

                    <div>
                        <label>Επόμενη ενέργεια:</label><br>
                        <input type="text" name="next_action" placeholder="π.χ. Πρόσκληση στο webinar">
                    </div>
                </div>
            </div>

            <div class="section-card">
                <button type="submit">Αποθήκευση Υποψηφίου</button>
                <a class="button-link" href="members.php">Προβολή λίστας</a>
            </div>

        </form>

<?php require __DIR__ . "/includes/layout-end.php"; ?>