<?php
/**
 * Skrypt PHP do obsługi formularza kontaktowego
 * Bezpiecznie wysyła dane na Discord Webhook (ukryty na serwerze)
 * 
 * INSTALACJA:
 * 1. Wklej swój Discord Webhook URL w linii 20
 * 2. Wrzuć ten plik na serwer jako send-contact.php
 * 3. Upewnij się że serwer ma włączone allow_url_fopen
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// WKLEJ TUTAJ SWÓJ DISCORD WEBHOOK URL
$webhookUrl = 'https://discord.com/api/webhooks/TWOJ_WEBHOOK_ID/TWOJ_WEBHOOK_TOKEN';

// Tylko metoda POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metoda niedozwolona']);
    exit;
}

// Pobierz dane z requestu
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Walidacja wymaganych pól
if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Brak wymaganych pól']);
    exit;
}

// Sanityzacja i walidacja danych
$name = htmlspecialchars(strip_tags($data['name']));
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
$phone = isset($data['phone']) ? htmlspecialchars(strip_tags($data['phone'])) : 'Nie podano';
$message = htmlspecialchars(strip_tags($data['message']));

if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Nieprawidłowy adres email']);
    exit;
}

// Limit długości wiadomości (zabezpieczenie przed spamem)
if (strlen($message) > 2000) {
    http_response_code(400);
    echo json_encode(['error' => 'Wiadomość zbyt długa (max 2000 znaków)']);
    exit;
}

// Przygotuj payload dla Discorda
$discordPayload = [
    'embeds' => [[
        'title' => '📧 Nowa wiadomość z formularza - Motelik Za Grosik',
        'description' => 'Formularz kontaktowy na stronie',
        'color' => 9127187, // Kolor amber
        'fields' => [
            [
                'name' => '👤 Imię i nazwisko',
                'value' => $name,
                'inline' => true
            ],
            [
                'name' => '📧 Email',
                'value' => $email,
                'inline' => true
            ],
            [
                'name' => '📱 Telefon',
                'value' => $phone,
                'inline' => true
            ],
            [
                'name' => '💬 Wiadomość',
                'value' => strlen($message) > 1000 ? substr($message, 0, 1000) . '...' : $message,
                'inline' => false
            ]
        ],
        'timestamp' => date('c'),
        'footer' => [
            'text' => 'Motelik Za Grosik - Formularz kontaktowy'
        ]
    ]]
];

// Opcje dla file_get_contents
$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($discordPayload),
        'timeout' => 10
    ]
];

$context = stream_context_create($options);

// Wyślij do Discord
$result = @file_get_contents($webhookUrl, false, $context);

// Sprawdź czy wysłano poprawnie
if ($result === false) {
    // Loguj błąd (opcjonalnie)
    error_log('Discord webhook failed: ' . print_r($http_response_header, true));
    
    http_response_code(500);
    echo json_encode(['error' => 'Błąd wysyłania wiadomości']);
    exit;
}

// Sukces!
http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Wiadomość wysłana pomyślnie'
]);
?>
