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
    : "Δεν έχουν δηλωθεί";

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
" . $categoriesText . "

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
        <a href="#ai-tools">🤖 AI Tools</a>
        <a href="#settings">⚙ Settings</a>

    </aside>

    <main class="main-content">

        <div class="page-header">
            <h1><?php echo htmlspecialchars($fullName); ?></h1>
            <p>Prospect Profile, follow-up history και AI επικοινωνία.</p>
        </div>

        <p>
            <a class="button-link" href="members.php">← Επιστροφή στη λίστα</a>
            <a class="button-link" href="edit-member.php?id=<?php echo urlencode($id); ?>">✏ Edit Prospect</a>
        </p>

        <div class="profile-grid">

            <div class="section-card">
                <h2>Βασικά στοιχεία</h2>

                <p><strong>Τηλέφωνο:</strong> <?php echo htmlspecialchars($member["phone"] ?? ""); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($member["email"] ?? ""); ?></p>
                <p><strong>Χώρα:</strong> <?php echo htmlspecialchars($member["country"] ?? ""); ?></p>
                <p><strong>Ημερομηνία καταχώρησης:</strong> <?php echo htmlspecialchars($member["created_at"] ?? ""); ?></p>
            </div>

            <div class="section-card">
                <h2>Marketing Info</h2>

                <p><strong>Πηγή:</strong> <?php echo htmlspecialchars($member["source"] ?? ""); ?></p>
                <p><strong>Κατάσταση:</strong> <?php echo htmlspecialchars($member["status"] ?? ""); ?></p>
                <p><strong>Προτεραιότητα:</strong> <?php echo htmlspecialchars($member["priority"] ?? "Medium"); ?></p>

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
            </div>

        </div>

        <div class="section-card">
            <h2>Σχόλια ή περιγραφή</h2>
            <p><?php echo nl2br(htmlspecialchars($member["description"] ?? "")); ?></p>
        </div>

        <div class="section-card">
            <h2>Prospect Intelligence Profile</h2>

            <p><strong>Επάγγελμα:</strong> <?php echo htmlspecialchars($member["profession"] ?? ""); ?></p>
            <p><strong>Οικογενειακή κατάσταση:</strong> <?php echo htmlspecialchars($member["family_status"] ?? ""); ?></p>
            <p><strong>Ηλικία:</strong> <?php echo htmlspecialchars($member["age"] ?? ""); ?></p>

            <p><strong>Ενδιαφέροντα:</strong><br>
                <?php echo nl2br(htmlspecialchars($member["interests"] ?? "")); ?>
            </p>

            <p><strong>Στόχοι:</strong><br>
                <?php echo nl2br(htmlspecialchars($member["goals"] ?? "")); ?>
            </p>

            <p><strong>Διαθέσιμος χρόνος εβδομαδιαία:</strong>
                <?php echo htmlspecialchars($member["available_time"] ?? ""); ?>
            </p>

            <p><strong>Κατηγορίες ενδιαφέροντος:</strong><br>
                <?php echo htmlspecialchars($categoriesText); ?>
            </p>

            <p><strong>Παρατηρήσεις από social media:</strong><br>
                <?php echo nl2br(htmlspecialchars($member["social_observations"] ?? "")); ?>
            </p>
        </div>

        <div class="section-card">
            <h2>Follow-Up Manager</h2>

            <p><strong>Επόμενη επικοινωνία:</strong>
                <?php echo htmlspecialchars($member["next_follow_up_date"] ?? ""); ?>
            </p>

            <p><strong>Επόμενη ενέργεια:</strong>
                <?php echo htmlspecialchars($member["next_action"] ?? ""); ?>
            </p>
        </div>

        <div class="section-card">
            <h2>Notes Timeline</h2>

            <form method="post">
                <input type="hidden" name="action" value="add_timeline_note">

                <textarea name="timeline_note" rows="4" placeholder="Γράψε σημείωση επικοινωνίας..."></textarea><br><br>

                <input type="text" name="timeline_next_action" placeholder="Επόμενη ενέργεια π.χ. Πρόσκληση σε webinar"><br><br>

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

            <br>

            <?php if (!empty($member["timeline"]) && is_array($member["timeline"])): ?>
                <?php foreach (array_reverse($member["timeline"]) as $entry): ?>
                    <div class="timeline-entry">
                        <strong><?php echo htmlspecialchars($entry["date"] ?? ""); ?></strong>
                        -
                        <?php echo htmlspecialchars($entry["type"] ?? ""); ?>

                        <p><?php echo nl2br(htmlspecialchars($entry["note"] ?? "")); ?></p>

                        <?php if (!empty($entry["next_action"])): ?>
                            <p>
                                <strong>Επόμενη ενέργεια:</strong>
                                <?php echo htmlspecialchars($entry["next_action"]); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Δεν υπάρχει ακόμα ιστορικό επικοινωνίας.</p>
            <?php endif; ?>
        </div>

        <div class="section-card" id="ai-tools">
            <h2>AI Contact Strategy</h2>
            <p>Εδώ αργότερα θα εμφανίζονται τα AI σενάρια επικοινωνίας.</p>

            <button disabled>Generate AI Contact Scenarios</button>
        </div>

        <div class="section-card">
            <h2>AI Brief Generator</h2>

            <textarea rows="18" readonly><?php echo htmlspecialchars($aiBrief); ?></textarea>

            <br><br>

            <form method="post" action="generate-ai.php">
                <input
                    type="hidden"
                    name="ai_brief"
                    value="<?php echo htmlspecialchars($aiBrief, ENT_QUOTES); ?>"
                >

                <button type="submit">
                    Generate AI Contact Scenarios
                </button>
            </form>

            <br>

            <button onclick="navigator.clipboard.writeText(document.querySelector('textarea[readonly]').value)">
                Copy AI Brief
            </button>
        </div>

        <div class="section-card">
            <h2>AI Master Prompt</h2>

            <textarea id="aiPromptBox" rows="25" readonly><?php
                echo htmlspecialchars($aiPrompt);
            ?></textarea>

            <br><br>

            <button onclick="copyPrompt()">
                Copy AI Prompt
            </button>
        </div>

    </main>
</div>

<script>
function copyPrompt() {
    const text = document.getElementById('aiPromptBox');
    text.select();
    text.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(text.value);
    alert('Το prompt αντιγράφηκε.');
}
</script>