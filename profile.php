<?php
require_once __DIR__ . "/config.php";

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
                    "note" => $note,
                    "next_action" => $_POST["timeline_next_action"] ?? ""
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

$fullName = trim(($member["first_name"] ?? "") . " " . ($member["last_name"] ?? ""));

$categories = $member["interest_categories"] ?? [];
$categoriesText = is_array($categories) && !empty($categories)
    ? implode(", ", $categories)
    : "";

$aiBrief = "Προφίλ Υποψηφίου\n\n";
$aiBrief .= "Ο/Η " . $fullName . " ";

if (!empty($member["age"])) {
    $aiBrief .= "είναι " . $member["age"] . " ετών. ";
}

if (!empty($member["profession"])) {
    $aiBrief .= "Εργάζεται ως " . $member["profession"] . ". ";
}

if (!empty($member["family_status"])) {
    $aiBrief .= "Οικογενειακή κατάσταση: " . $member["family_status"] . ". ";
}

$aiBrief .= "\n\n";

if (!empty($member["interests"])) {
    $aiBrief .= "Ενδιαφέροντα:\n" . $member["interests"] . "\n\n";
}

if (!empty($member["goals"])) {
    $aiBrief .= "Στόχοι:\n" . $member["goals"] . "\n\n";
}

if (!empty($member["available_time"])) {
    $aiBrief .= "Διαθέσιμος χρόνος εβδομαδιαία: " . $member["available_time"] . "\n\n";
}

if (!empty($member["description"])) {
    $aiBrief .= "Σχόλια / Περιγραφή:\n" . $member["description"] . "\n\n";
}

if (!empty($member["social_observations"])) {
    $aiBrief .= "Παρατηρήσεις από social media:\n" . $member["social_observations"] . "\n\n";
}

if (is_array($categories) && !empty($categories)) {
    $aiBrief .= "Κατηγορίες ενδιαφέροντος:\n- " . implode("\n- ", $categories) . "\n\n";
}

if (!empty($member["facebook_url"])) {
    $aiBrief .= "Facebook: " . $member["facebook_url"] . "\n";
}

if (!empty($member["instagram_url"])) {
    $aiBrief .= "Instagram: " . $member["instagram_url"] . "\n";
}

$aiBrief .= "\nΖήτησε από το AI να προτείνει 2-3 διαφορετικά σενάρια επικοινωνίας, με φιλικό και ανθρώπινο ύφος.";

$aiPrompt = "
Ονοματεπώνυμο: $fullName

Ηλικία: " . ($member["age"] ?? "") . "

Επάγγελμα: " . ($member["profession"] ?? "") . "

Οικογενειακή κατάσταση: " . ($member["family_status"] ?? "") . "

Ενδιαφέροντα:
" . ($member["interests"] ?? "") . "

Στόχοι:
" . ($member["goals"] ?? "") . "

Περιγραφή:
" . ($member["description"] ?? "") . "

Παρατηρήσεις Social Media:
" . ($member["social_observations"] ?? "") . "

Κατηγορίες ενδιαφέροντος:
" . ($categoriesText ?: "Δεν έχουν δηλωθεί") . "

Facebook Profile:
" . ($member["facebook_url"] ?? "") . "

Instagram Profile:
" . ($member["instagram_url"] ?? "") . "

Δημιούργησε:
1. Ψυχολογική ανάλυση
2. 3 σενάρια επικοινωνίας
3. Follow-up μηνύματα
4. Πιθανές αντιρρήσεις
5. Πρόταση προϊόντων
";
?>

<?php require __DIR__ . "/includes/layout-start.php"; ?>

<div class="page-header">
    <h1><?php echo htmlspecialchars($fullName); ?></h1>
    <p><?php echo t('profile_desc'); ?></p>
</div>

<p>
    <a class="button-link" href="members.php">← <?php echo t('back_to_list'); ?></a>
    <a class="button-link" href="edit-member.php?id=<?php echo urlencode($id); ?>">✏ <?php echo t('edit_prospect'); ?></a>
</p>

<div class="profile-grid">

    <div class="section-card">
        <h2><?php echo t('basic_info'); ?></h2>

        <p><strong><?php echo t('phone'); ?>:</strong> <?php echo htmlspecialchars($member["phone"] ?? ""); ?></p>
        <p><strong><?php echo t('email'); ?>:</strong> <?php echo htmlspecialchars($member["email"] ?? ""); ?></p>
        <p><strong><?php echo t('country'); ?>:</strong> <?php echo htmlspecialchars($member["country"] ?? ""); ?></p>
        <p><strong><?php echo t('created_at'); ?>:</strong> <?php echo htmlspecialchars($member["created_at"] ?? ""); ?></p>
    </div>

    <div class="section-card">
        <h2><?php echo t('marketing_info'); ?></h2>

        <p><strong><?php echo t('source'); ?>:</strong> <?php echo htmlspecialchars($member["source"] ?? ""); ?></p>
        <p><strong><?php echo t('status'); ?>:</strong> <?php echo htmlspecialchars($member["status"] ?? ""); ?></p>
        <p><strong><?php echo t('priority_col'); ?>:</strong> <?php echo htmlspecialchars($member["priority"] ?? "Medium"); ?></p>

        <p><strong>Facebook:</strong>
            <?php if (!empty($member["facebook_url"])): ?>
                <a href="<?php echo htmlspecialchars($member["facebook_url"]); ?>" target="_blank"><?php echo t('facebook_open'); ?></a>
            <?php else: ?>
                <?php echo t('not_declared'); ?>
            <?php endif; ?>
        </p>

        <p><strong>Instagram:</strong>
            <?php if (!empty($member["instagram_url"])): ?>
                <a href="<?php echo htmlspecialchars($member["instagram_url"]); ?>" target="_blank"><?php echo t('instagram_open'); ?></a>
            <?php else: ?>
                <?php echo t('not_declared'); ?>
            <?php endif; ?>
        </p>
    </div>

</div>

<div class="section-card">
    <h2><?php echo t('description'); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($member["description"] ?? "")); ?></p>
</div>

<div class="section-card">
    <h2>Prospect Intelligence Profile</h2>

    <p><strong><?php echo t('profession'); ?>:</strong> <?php echo htmlspecialchars($member["profession"] ?? ""); ?></p>
    <p><strong><?php echo t('family_status'); ?>:</strong> <?php echo htmlspecialchars($member["family_status"] ?? ""); ?></p>
    <p><strong><?php echo t('age'); ?>:</strong> <?php echo htmlspecialchars($member["age"] ?? ""); ?></p>

    <p><strong><?php echo t('interests'); ?>:</strong><br>
        <?php echo nl2br(htmlspecialchars($member["interests"] ?? "")); ?>
    </p>

    <p><strong><?php echo t('goals'); ?>:</strong><br>
        <?php echo nl2br(htmlspecialchars($member["goals"] ?? "")); ?>
    </p>

    <p><strong><?php echo t('available_time'); ?>:</strong>
        <?php echo htmlspecialchars($member["available_time"] ?? ""); ?>
    </p>

    <p><strong><?php echo t('interest_categories'); ?>:</strong><br>
        <?php echo htmlspecialchars($categoriesText ?: t('not_declared')); ?>
    </p>

    <p><strong><?php echo t('social_observations'); ?>:</strong><br>
        <?php echo nl2br(htmlspecialchars($member["social_observations"] ?? "")); ?>
    </p>
</div>

<div class="section-card">
    <h2><?php echo t('followup_manager'); ?></h2>

    <p><strong><?php echo t('next_contact'); ?>:</strong>
        <?php echo htmlspecialchars($member["next_follow_up_date"] ?? ""); ?>
    </p>

    <p><strong><?php echo t('next_action'); ?>:</strong>
        <?php echo htmlspecialchars($member["next_action"] ?? ""); ?>
    </p>
</div>

<div class="section-card">
    <h2><?php echo t('notes_timeline'); ?></h2>

    <form method="post">
        <input type="hidden" name="action" value="add_timeline_note">

        <textarea name="timeline_note" rows="4" placeholder="<?php echo t('write_note'); ?>"></textarea><br><br>

        <input type="text" name="timeline_next_action" placeholder="<?php echo t('next_action_example'); ?>"><br><br>

        <select name="timeline_type">
            <option value="Σημείωση"><?php echo t('note'); ?></option>
            <option value="Τηλεφώνημα"><?php echo t('phone_call'); ?></option>
            <option value="Facebook Message"><?php echo t('fb_message'); ?></option>
            <option value="Instagram Message"><?php echo t('ig_message'); ?></option>
            <option value="Zoom">Zoom</option>
            <option value="Webinar">Webinar</option>
            <option value="Follow-up">Follow-up</option>
        </select><br><br>

        <button type="submit"><?php echo t('add_timeline'); ?></button>
    </form>

    <br>

    <?php if (!empty($member["timeline"]) && is_array($member["timeline"])): ?>
        <?php foreach (array_reverse($member["timeline"]) as $entry): ?>
            <div class="timeline-entry">
                <strong><?php echo formatDateTime($entry["date"] ?? ""); ?></strong>
                -
                <?php echo htmlspecialchars($entry["type"] ?? ""); ?>

                <p><?php echo nl2br(htmlspecialchars($entry["note"] ?? "")); ?></p>

                <?php if (!empty($entry["next_action"])): ?>
                    <p>
                        <strong><?php echo t('next_action'); ?>:</strong>
                        <?php echo htmlspecialchars($entry["next_action"]); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p><?php echo t('no_history'); ?></p>
    <?php endif; ?>
</div>

<div class="section-card" id="ai-tools">
    <h2><?php echo t('ai_contact_strategy'); ?></h2>
    <p><?php echo t('ai_contact_desc'); ?></p>

    <button disabled><?php echo t('generate_ai_scenarios'); ?></button>
</div>

<div class="section-card">
    <h2><?php echo t('ai_brief_generator'); ?></h2>

    <textarea rows="18" readonly><?php echo htmlspecialchars($aiBrief); ?></textarea>

    <br><br>

    <form method="post" action="generate-ai.php">
        <input
            type="hidden"
            name="ai_brief"
            value="<?php echo htmlspecialchars($aiBrief, ENT_QUOTES); ?>"
        >

        <button type="submit">
            <?php echo t('generate_ai_scenarios'); ?>
        </button>
    </form>

    <br>

    <button onclick="navigator.clipboard.writeText(document.querySelector('textarea[readonly]').value)">
        <?php echo t('copy_ai_brief'); ?>
    </button>
</div>

<div class="section-card">
    <h2><?php echo t('ai_master_prompt'); ?></h2>

    <textarea id="aiPromptBox" rows="25" readonly><?php
        echo htmlspecialchars($aiPrompt);
    ?></textarea>

    <br><br>

    <button onclick="copyPrompt()">
        <?php echo t('copy_ai_prompt'); ?>
    </button>
</div>

<?php require __DIR__ . "/includes/layout-end.php"; ?>

<script>
function copyPrompt() {
    const text = document.getElementById('aiPromptBox');
    text.select();
    text.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(text.value);
    alert('<?php echo t('prompt_copied'); ?>');
}
</script>