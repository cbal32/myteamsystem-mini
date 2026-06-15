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

<?php require __DIR__ . "/includes/layout-start.php"; ?>

        <div class="page-header">
<h1><?php echo t('edit_prospect_title'); ?></h1>

<p>
    <?php echo t('edit_prospect_desc'); ?>:
    <?php echo htmlspecialchars($fullName); ?>
</p>
        </div>

        <p>
            <a class="button-link" href="profile.php?id=<?php echo urlencode($id); ?>">
              ← <?php echo t('back_to_list'); ?>
            </a>

            <a class="button-link" href="members.php">
                ← <?php echo t('back_to_list'); ?>
            </a>
        </p>

        <form method="post">

            <div class="profile-grid">

                <div class="section-card">
                    <h2>Βασικά στοιχεία</h2>

                   <div class="form-group">
         <label><?php echo t('first_name'); ?></label>
    <input type="text" name="first_name" value="<?php echo htmlspecialchars($member["first_name"] ?? ""); ?>">
</div>

      <div class="form-group">
   <label><?php echo t('last_name'); ?></label>
    <input type="text" name="last_name" value="<?php echo htmlspecialchars($member["last_name"] ?? ""); ?>">
</div>

<div class="form-group">
        <label><?php echo t('last_name'); ?></label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($member["phone"] ?? ""); ?>" placeholder="Τηλέφωνο"><br><br>
</div>
                    <div class="form-group">
                            <label><?php echo t('email'); ?></label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($member["email"] ?? ""); ?>" placeholder="Email"><br><br>
</div>
                    <div class="form-group">
                            <label><?php echo t('email'); ?></label>
                    <input type="text" name="country" value="<?php echo htmlspecialchars($member["country"] ?? ""); ?>" placeholder="Χώρα">
                    </div>
                </div>

                <div class="section-card">
                    <h2><?php echo t('social_marketing'); ?></h2>

                    <input type="url" name="facebook_url" value="<?php echo htmlspecialchars($member["facebook_url"] ?? ""); ?>" placeholder="Facebook Profile URL"><br><br>

                    <input type="url" name="instagram_url" value="<?php echo htmlspecialchars($member["instagram_url"] ?? ""); ?>" placeholder="Instagram Profile URL"><br><br>

                   <label><?php echo t('contact_source'); ?></label>
                    <select name="source">
                        <?php
                      $sources = [
    
    "Friend" => t('friend'),
    "Acquaintance" => t('acquaintance'),
    "Facebook" => "Facebook",
    "Instagram" => "Instagram",
    "TikTok" => "TikTok",
    "Website" => "Website",
    "Seminar" => t('seminar_source'),
    "Referral" => t('referral_source')
];

                        foreach ($sources as $value => $label) {

    echo "<option value='" .
        htmlspecialchars($value) .
        "' " .
        isSelected($member["source"] ?? "", $value) .
        ">" .
        htmlspecialchars($label) .
        "</option>";
}
                        ?>
                    </select><br><br>

                    <label><?php echo t('status'); ?></label><br>
                    <select name="status">
                        <?php
                        

$statuses = [
    "Νέος" => t('new_status'),
    "Επικοινωνήθηκε" => t('contacted_status'),
    "Ενδιαφέρεται" => t('interested_status'),
    "Εγγράφηκε" => t('registered_status'),
    "Δεν ενδιαφέρεται" => t('not_interested_status')
];

foreach ($statuses as $value => $label) {

    echo "<option value='" .
        htmlspecialchars($value) .
        "' " .
        isSelected($member["status"] ?? "Νέος", $value) .
        ">" .
        htmlspecialchars($label) .
        "</option>";
}




                      
                        ?>
                    </select><br><br>

                    <label><?php echo t('priority_col'); ?></label><br>
                    <select name="priority">
                        <?php
                     
$priorities = [
    "Low" => t('low_priority'),
    "Medium" => t('medium_priority'),
    "High" => t('high_priority_label')
];

foreach ($priorities as $value => $label) {

    echo "<option value='" .
        htmlspecialchars($value) .
        "' " .
        isSelected($member["priority"] ?? "Medium", $value) .
        ">" .
        htmlspecialchars($label) .
        "</option>";
}




                        ?>
                    </select>
                </div>

            </div>

            <div class="section-card">
              <h2><?php echo t('description'); ?></h2>

                <label><?php echo t('comments_description'); ?></label>

<textarea name="description" rows="6"><?php echo htmlspecialchars($member["description"] ?? ""); ?></textarea>
            </div>

            <div class="section-card">
                <h2>Prospect Intelligence Profile</h2>

                <div class="profile-grid">
                    <div>
                    <div class="form-group">
    <label><?php echo t('profession'); ?></label>
    <input type="text" name="profession"
           value="<?php echo htmlspecialchars($member["profession"] ?? ""); ?>">
</div>

<div class="form-group">
    <label><?php echo t('family_status'); ?></label>
    <input type="text" name="family_status"
           value="<?php echo htmlspecialchars($member["family_status"] ?? ""); ?>">
</div>

<div class="form-group">
    <label><?php echo t('age'); ?></label>
    <input type="number" name="age"
           value="<?php echo htmlspecialchars($member["age"] ?? ""); ?>">
</div>

<div class="form-group">
    <label><?php echo t('available_time'); ?></label>
    <input type="text" name="available_time"
           value="<?php echo htmlspecialchars($member["available_time"] ?? ""); ?>">
</div>
</div>

                    <div>
                        <label><?php echo t('interests'); ?></label>

<textarea name="interests" rows="4"><?php echo htmlspecialchars($member["interests"] ?? ""); ?></textarea><br><br>

                        <label><?php echo t('goals'); ?></label>

<textarea name="goals" rows="4"><?php echo htmlspecialchars($member["goals"] ?? ""); ?></textarea>
                    </div>
                </div>

                <?php echo t('interest_categories'); ?>

                <div class="checkbox-grid">
                    <label><input type="checkbox" name="interest_categories[]" value="Άρωμα" <?php echo isChecked($selectedCategories, "Άρωμα"); ?>> Άρωμα</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Συμπληρώματα" <?php echo isChecked($selectedCategories, "Συμπληρώματα"); ?>> Συμπληρώματα</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Καλλυντικά" <?php echo isChecked($selectedCategories, "Καλλυντικά"); ?>> Καλλυντικά</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Επιχείρηση" <?php echo isChecked($selectedCategories, "Επιχείρηση"); ?>> Επιχείρηση</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Work From Home" <?php echo isChecked($selectedCategories, "Work From Home"); ?>> Work From Home</label>
                    <label><input type="checkbox" name="interest_categories[]" value="Extra Income" <?php echo isChecked($selectedCategories, "Extra Income"); ?>> Extra Income</label>
                </div>

                <br>

                <label><?php echo t('social_observations'); ?></label>

<textarea name="social_observations" rows="5"><?php echo htmlspecialchars($member["social_observations"] ?? ""); ?></textarea>
            </div>

<div class="section-card">
    <h2>Follow-Up Manager</h2>

    <div class="followup-grid">

        <div class="form-group">
          <label><?php echo t('next_contact'); ?></label>

            <input
                type="date"
                name="next_follow_up_date"
                value="<?php echo htmlspecialchars($member["next_follow_up_date"] ?? ""); ?>">
        </div>

        <div class="form-group">
            <label><?php echo t('next_action'); ?></label>

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
                   <?php echo t('cancel'); ?>
                </a>
            </div>

        </form>

<?php require __DIR__ . "/includes/layout-end.php"; ?>