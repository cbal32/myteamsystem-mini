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

    $message = "saved";
}
?>

<?php require __DIR__ . "/includes/layout-start.php"; ?>

<div class="page-header">
    <h1><?php echo t('new_prospect_title'); ?></h1>
    <p><?php echo t('new_prospect_desc'); ?></p>
</div>

<?php if ($message): ?>
    <div class="section-card" style="border-left: 5px solid #16a34a;">
        <strong style="color:#166534;"><?php echo t('prospect_saved'); ?></strong>
        <br><br>
        <a class="button-link" href="members.php"><?php echo t('view_list'); ?></a>
    </div>
<?php endif; ?>

<form method="post">

    <div class="profile-grid">

        <div class="section-card">
            <h2><?php echo t('basic_details'); ?></h2>

            <div class="form-group">
                <label><?php echo t('first_name'); ?></label>
                <input type="text" name="first_name">
            </div>

            <div class="form-group">
                <label><?php echo t('last_name'); ?></label>
                <input type="text" name="last_name">
            </div>

            <div class="form-group">
                <label><?php echo t('phone'); ?></label>
                <input type="text" name="phone">
            </div>

            <div class="form-group">
                <label><?php echo t('email'); ?></label>
                <input type="email" name="email">
            </div>

            <div class="form-group">
                <label><?php echo t('country'); ?></label>
                <input type="text" name="country">
            </div>
        </div>

        <div class="section-card">
            <h2><?php echo t('social_marketing'); ?></h2>

            <div class="form-group">
                <label><?php echo t('facebook_url'); ?></label>
                <input type="url" name="facebook_url">
            </div>

            <div class="form-group">
                <label><?php echo t('instagram_url'); ?></label>
                <input type="url" name="instagram_url">
            </div>

            <div class="form-group">
                <label><?php echo t('contact_source'); ?></label>
                <select name="source">
              
                <option value="Friend"><?php echo t('friend'); ?></option>
<option value="Acquaintance"><?php echo t('acquaintance'); ?></option>
<option value="Facebook">Facebook</option>
<option value="Instagram">Instagram</option>
<option value="TikTok">TikTok</option>
<option value="Website">Website</option>
<option value="Seminar"><?php echo t('seminar_source'); ?></option>
<option value="Referral"><?php echo t('referral_source'); ?></option>
                </select>
            </div>

            <div class="form-group">
                <label><?php echo t('status'); ?></label>
                <select name="status">
                    <option value="Νέος"><?php echo t('new_status'); ?></option>
                    <option value="Επικοινωνήθηκε"><?php echo t('contacted_status'); ?></option>
                    <option value="Ενδιαφέρεται"><?php echo t('interested_status'); ?></option>
                    <option value="Εγγράφηκε"><?php echo t('registered_status'); ?></option>
                    <option value="Δεν ενδιαφέρεται"><?php echo t('not_interested_status'); ?></option>
                </select>
            </div>

            <div class="form-group">
                <label><?php echo t('priority_col'); ?></label>
                <select name="priority">
                    <option value="Low"><?php echo t('low_priority'); ?></option>
                    <option value="Medium" selected><?php echo t('medium_priority'); ?></option>
                    <option value="High"><?php echo t('high_priority_label'); ?></option>
                </select>
            </div>
        </div>

    </div>

    <div class="section-card">
        <h2><?php echo t('description'); ?></h2>

        <textarea
            name="description"
            rows="6"
            placeholder="<?php echo t('comments_description'); ?>"></textarea>
    </div>

    <div class="section-card">
        <h2>Prospect Intelligence Profile</h2>

        <div class="profile-grid">
            <div>
                <div class="form-group">
                    <label><?php echo t('profession'); ?></label>
                    <input type="text" name="profession">
                </div>

                <div class="form-group">
                    <label><?php echo t('family_status'); ?></label>
                    <input type="text" name="family_status">
                </div>

                <div class="form-group">
                    <label><?php echo t('age'); ?></label>
                    <input type="number" name="age">
                </div>

                <div class="form-group">
                    <label><?php echo t('available_time'); ?></label>
                    <input type="text" name="available_time">
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label><?php echo t('interests'); ?></label>
                    <textarea name="interests" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label><?php echo t('goals'); ?></label>
                    <textarea
                        name="goals"
                        rows="4"
                        placeholder="<?php echo t('economic_personal_goals'); ?>"></textarea>
                </div>
            </div>
        </div>

        <p><strong><?php echo t('interest_categories'); ?>:</strong></p>

        <div class="checkbox-grid">
            <label><input type="checkbox" name="interest_categories[]" value="Άρωμα"> <?php echo t('perfume'); ?></label>
            <label><input type="checkbox" name="interest_categories[]" value="Συμπληρώματα"> <?php echo t('supplements'); ?></label>
            <label><input type="checkbox" name="interest_categories[]" value="Καλλυντικά"> <?php echo t('cosmetics'); ?></label>
            <label><input type="checkbox" name="interest_categories[]" value="Επιχείρηση"> <?php echo t('business'); ?></label>
            <label><input type="checkbox" name="interest_categories[]" value="Work From Home"> <?php echo t('work_from_home'); ?></label>
            <label><input type="checkbox" name="interest_categories[]" value="Extra Income"> <?php echo t('extra_income'); ?></label>
        </div>

        <br>

        <div class="form-group">
            <label><?php echo t('social_observations'); ?></label>
            <textarea
                name="social_observations"
                rows="5"
                placeholder="<?php echo t('social_observations_placeholder'); ?>"></textarea>
        </div>
    </div>

    <div class="section-card">
        <h2><?php echo t('followup_manager'); ?></h2>

        <div class="followup-grid">

            <div class="form-group">
                <label><?php echo t('next_contact'); ?></label>
                <input type="date" name="next_follow_up_date">
            </div>

            <div class="form-group">
                <label><?php echo t('next_action'); ?></label>
                <input
                    type="text"
                    name="next_action"
                    placeholder="<?php echo t('webinar_invitation_example'); ?>">
            </div>

        </div>
    </div>

    <div class="section-card">
        <button type="submit"><?php echo t('save_prospect'); ?></button>
        <a class="button-link" href="members.php"><?php echo t('view_list'); ?></a>
    </div>

</form>

<?php require __DIR__ . "/includes/layout-end.php"; ?>