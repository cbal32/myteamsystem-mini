<?php
require_once __DIR__ . "/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$aiBrief = trim($_POST["ai_brief"] ?? "");

if ($aiBrief === "") {
    die("Δεν υπάρχει AI Brief.");
}

$prompt = "
Είσαι έμπειρος σύμβουλος network marketing.

Με βάση το παρακάτω προφίλ υποψηφίου, δημιούργησε 3 διαφορετικά σενάρια επικοινωνίας στα Ελληνικά.

Για κάθε σενάριο δώσε:
1. Τίτλο στρατηγικής
2. Πρώτο μήνυμα
3. Δεύτερο μήνυμα follow-up
4. Τρίτο μήνυμα αν δεν απαντήσει
5. Σύντομη εξήγηση γιατί ταιριάζει αυτό το σενάριο

Προφίλ υποψηφίου:

" . $aiBrief;

$payload = [
    "model" => "gpt-4.1-mini",
    "input" => $prompt
];

$ch = curl_init("https://api.openai.com/v1/responses");

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer " . OPENAI_API_KEY
    ],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE)
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("cURL Error: " . curl_error($ch));
}

curl_close($ch);

echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";
exit;
?>

<h1>AI Contact Scenarios</h1>

<p><a href="javascript:history.back()">← Επιστροφή στο προφίλ</a></p>

<pre style="white-space: pre-wrap; background:#f4f4f4; padding:20px; border-radius:8px;"><?php
echo htmlspecialchars($aiText);
?></pre>