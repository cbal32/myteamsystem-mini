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

<h1>AI Prospect Manager</h1>

<?php if ($message): ?>
    <p style="color: green;"><?php echo $message; ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="first_name" placeholder="Όνομα"><br><br>
    <input type="text" name="last_name" placeholder="Επώνυμο"><br><br>
    <input type="text" name="phone" placeholder="Τηλέφωνο"><br><br>
    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="text" name="country" placeholder="Χώρα"><br><br>

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

    <textarea name="description" rows="6" cols="50" placeholder="Σχόλια ή περιγραφή υποψηφίου"></textarea><br><br>

    <button type="submit">Αποθήκευση Υποψηφίου</button>
</form>

<p><a href="members.php">Προβολή λίστας υποψηφίων</a></p>