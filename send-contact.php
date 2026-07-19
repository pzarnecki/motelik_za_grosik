<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metoda niedozwolona']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Brak wymaganych pól']);
    exit;
}

$name = htmlspecialchars(strip_tags($data['name']));
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
$phone = isset($data['phone']) ? htmlspecialchars(strip_tags($data['phone'])) : 'Nie podano';
$message = htmlspecialchars(strip_tags($data['message']));

if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Nieprawidłowy adres email']);
    exit;
}

if (strlen($message) > 2000) {
    http_response_code(400);
    echo json_encode(['error' => 'Wiadomość zbyt długa (max 2000 znaków)']);
    exit;
}

$formSubject = isset($data['subject']) ? htmlspecialchars(strip_tags($data['subject'])) : 'Brak tematu';

$to = 'motelikzagrosik@gmail.com';
$subject = "Formularz WWW [{$formSubject}]: {$name}";
$body = "Temat: {$formSubject}\nImię i nazwisko: {$name}\nEmail: {$email}\nTelefon: {$phone}\n\nWiadomość:\n{$message}";
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'Reply-To: ' . $email;
$headersString = implode("\r\n", $headers);

$sent = @mail($to, $subject, $body, $headersString);

if (!$sent) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd wysyłania wiadomości']);
    exit;
}

http_response_code(200);
echo json_encode(['success' => true, 'message' => 'Wiadomość wysłana pomyślnie']);
?>
