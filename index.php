<?php
// Connessione database
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "cookie"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Inizializza sessione
session_start();

// Verifica se è stata impostata una lingua nella sessione
if (!isset($_SESSION['lang'])) {
    // Se non è stata impostata, verifica se c'è un cookie per la lingua
    if (isset($_COOKIE['lang'])) {
        $_SESSION['lang'] = $_COOKIE['lang'];
    } else {
        // Imposta la lingua predefinita (italiano)
        $_SESSION['lang'] = 'it';
    }
}

echo "Lingua attuale: " . $_SESSION['lang'];

// Funzione per traduzioni
function getTranslation($conn, $label) {
    $lang = $_SESSION['lang'];
    $sql = "SELECT translation FROM translations WHERE label = '$label' AND lang = '$lang'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['translation'];
    } else {
        return $label; // Se la traduzione non è disponibile, ritorna la label originale
    }
}

// Imposta il cookie per la lingua
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + (86400 * 30), "/"); // Cookie valido per 30 giorni
    $_SESSION['lang'] = $lang;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getTranslation($conn, 'News'); ?></title>
</head>
<body>

<!-- Lingua -->
<div>
    <a href="?lang=en">English</a> | 
    <a href="?lang=it">Italiano</a>
</div>

<!-- Titolo della pagina -->
<h1><?php echo getTranslation($conn, 'Homepage'); ?></h1>

<!-- Sezione del contenuto -->
<div>
    <h2><?php echo getTranslation($conn, 'About Us'); ?></h2>
    <p><?php echo getTranslation($conn, 'Welcome to our website!'); ?></p>
    <button><?php echo getTranslation($conn, 'Read More'); ?></button>
</div>

<!-- Messaggi di avviso -->
<div>
    <p><?php echo getTranslation($conn, 'This is an important message.'); ?></p>
</div>

</body>
</html>

<?php
// Chiudi la connessione al database
$conn->close();
?>

