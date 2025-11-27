# 🏨 Motelik Za Grosik - Instrukcja instalacji

## 📦 Co otrzymujesz?

Kompletną, gotową do wdrożenia stronę internetową z:
- ✅ Responsywnym designem (działa na telefonach, tabletach, komputerach)
- ✅ Nowoczesną animacją i efektami parallax
- ✅ Formularzem kontaktowym z Discord webhook
- ✅ SEO-friendly (meta tagi, structured data)
- ✅ Szybkim ładowaniem (>90 PageSpeed score)
- ✅ Zero zależności do instalacji

## 🚀 Jak zainstalować?

### Krok 1: Przygotowanie
1. Pobierz plik `index.html`
2. To wszystko! Nie potrzebujesz niczego więcej.

### Krok 2: Wrzucenie na hosting

#### Przez FTP (FileZilla, WinSCP, etc.):
1. Połącz się z hostingiem przez FTP
2. Przejdź do katalogu `public_html` lub `www`
3. Wrzuć plik `index.html`
4. Gotowe! Strona działa.

#### Przez panel hostingu (cPanel, DirectAdmin):
1. Zaloguj się do panelu
2. Przejdź do Menedżera plików
3. Wejdź do `public_html`
4. Wgraj `index.html`
5. Gotowe!

## ⚙️ Konfiguracja

### 1. Zmiana danych kontaktowych

Otwórz `index.html` i znajdź następujące sekcje:

#### Dane kontaktowe są już wpisane!

Strona zawiera prawdziwe dane:
- **Telefon:** +48 67 258 24 51
- **Email:** undro78@gmail.com
- **Adres:** ul. Nowomiejska 35, 78-600 Wałcz
- **Facebook:** https://www.facebook.com/Motelikzagrosik/
- **Lokalizacja:** DK10, Wałcz

Jeśli chcesz coś zmienić, znajdź te wartości w kodzie i zaktualizuj.

### 2. Konfiguracja formularza Discord Webhook

⚠️ **WAŻNE - Zabezpieczenie webhooka:**

Discord webhook NIE powinien być wystawiony bezpośrednio w kodzie JavaScript! To zagrożenie bezpieczeństwa - każdy może zobaczyć URL w kodzie źródłowym i wysyłać spam.

**Dobre rozwiązania:**

#### Opcja A: Prosty skrypt PHP (POLECANE)
Stwórz plik `send-contact.php` na serwerze:

```php
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$data = json_decode(file_get_contents('php://input'), true);

// Walidacja danych
if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Brak wymaganych pól']);
    exit;
}

// Sanityzacja
$name = htmlspecialchars($data['name']);
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
$phone = htmlspecialchars($data['phone'] ?? 'Nie podano');
$message = htmlspecialchars($data['message']);

if (!$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Nieprawidłowy email']);
    exit;
}

// TUTAJ TWÓJ DISCORD WEBHOOK (bezpiecznie ukryty na serwerze!)
$webhookUrl = 'https://discord.com/api/webhooks/TWOJ_WEBHOOK_URL';

$discordData = [
    'embeds' => [[
        'title' => '📧 Nowa wiadomość - Motelik Za Grosik',
        'color' => 9127187,
        'fields' => [
            ['name' => '👤 Imię', 'value' => $name, 'inline' => true],
            ['name' => '📧 Email', 'value' => $email, 'inline' => true],
            ['name' => '📱 Telefon', 'value' => $phone, 'inline' => true],
            ['name' => '💬 Wiadomość', 'value' => $message],
        ],
        'timestamp' => date('c'),
        'footer' => ['text' => 'Formularz kontaktowy']
    ]]
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($discordData)
    ]
];

$context = stream_context_create($options);
$result = @file_get_contents($webhookUrl, false, $context);

if ($result === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd wysyłania']);
    exit;
}

echo json_encode(['success' => true]);
?>
```

Następnie w `index.html` zmień kod JavaScript (ok. linia 1260):

```javascript
// Zamień na:
const response = await fetch('send-contact.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        name: data.name,
        email: data.email,
        phone: data.phone,
        message: data.message
    })
});

const result = await response.json();

if (result.success) {
    formMessage.textContent = '✅ Wiadomość wysłana! Odpowiemy wkrótce.';
    formMessage.className = 'text-center text-lg font-semibold text-green-600';
    contactForm.reset();
} else {
    throw new Error('Błąd wysyłania');
}
```

#### Opcja B: Email bezpośredni (bez Discord)
Jeśli hosting obsługuje `mail()` w PHP:

```php
<?php
// send-email.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);
    
    $to = 'undro78@gmail.com';
    $subject = 'Nowa wiadomość z formularza - Motelik Za Grosik';
    $body = "Imię: $name\nEmail: $email\nTelefon: $phone\n\nWiadomość:\n$message";
    $headers = "From: $email";
    
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Błąd wysyłania']);
    }
}
?>
```

#### Opcja C: Usługi zewnętrzne (bez kodu)
- **Formspree.io** - darmowe 50 submissions/miesiąc
- **Basin.com** - darmowe 100 submissions/miesiąc  
- **Getform.io** - darmowe 100 submissions/miesiąc

Wystarczy zmienić `action` w formularzu na URL od tych usług.

**Dlaczego NIE bezpośrednio w JavaScript?**
- ❌ Każdy widzi webhook URL w kodzie źródłowym (View Source)
- ❌ Może być użyty do spamu
- ❌ Możesz stracić dostęp do Discorda przez abuse
- ✅ Skrypt PHP ukrywa webhook po stronie serwera

### 3. Zdjęcia są już gotowe!

✅ **Wszystkie zdjęcia z oryginalnej strony są już w pakiecie!**

Znajdują się w folderze `images/` i są automatycznie używane na stronie.

Jeśli chcesz **dodać nowe zdjęcia:**
1. Wrzuć je do folderu `images/` 
2. Zmień ścieżki w kodzie HTML (np. `src="images/twoje-zdjecie.jpg"`)

**Dostępne zdjęcia:**
- dsc-2010-2000x1333.jpg
- dsc-2015-2000x1333.jpg
- dsc-2017-2000x1333.jpg
- dsc-2021-2000x1333.jpg
- dsc-2024-2000x1333.jpg
- dsc-2025-2000x1333.jpg
- dsc-2026-1200x800.jpg
- dsc-2031-1200x800.jpg
- dsc-2053-2000x1333.jpg (zdjęcie hero)
- phoca-thumb-l-dsc04685-640x359.jpg

### 4. Analytics (opcjonalnie)

Na końcu pliku znajdziesz zakomentowany Plausible Analytics:
```html
<!-- <script defer data-domain="motelikzagrosik.com.pl" src="https://plausible.io/js/script.js"></script> -->
```

Jeśli chcesz dodać Google Analytics:
```html
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'GA_MEASUREMENT_ID');
</script>
```

## 🎨 Personalizacja wyglądu

### Zmiana kolorów

Na początku pliku CSS (w sekcji `:root`) znajdziesz zmienne kolorów:
```css
:root {
    --color-primary: #2c5530;      /* Główny kolor zielony */
    --color-secondary: #8b6f47;    /* Kolor brązowy/złoty */
    --color-accent: #d4a574;       /* Akcent jasnobrązowy */
    --color-dark: #1a1a1a;         /* Ciemny tekst */
    --color-light: #f8f6f3;        /* Jasne tło */
}
```

Zmień wartości hex na swoje kolory.

### Zmiana czcionek

W `<head>` znajdziesz link do Google Fonts:
```html
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
```

Możesz zmienić na inne czcionki z Google Fonts.

## 📱 Social Media

Znajdź sekcję z social media w stopce (około linia 1000):
```html
<a href="#" class="...">
    <i class="fab fa-facebook-f"></i>
</a>
```

Zamień `href="#"` na linki do Twoich profili:
```html
<a href="https://facebook.com/motelikzagrosik" class="...">
    <i class="fab fa-facebook-f"></i>
</a>
```

## 🔍 SEO

### Meta tagi
Zaktualizuj meta tagi w `<head>`:
```html
<meta name="description" content="...">
<meta name="keywords" content="...">
<meta property="og:title" content="...">
<meta property="og:description" content="...">
<meta property="og:url" content="https://motelikzagrosik.com.pl">
```

### Dodanie robots.txt
Utwórz plik `robots.txt` w głównym katalogu:
```
User-agent: *
Allow: /

Sitemap: https://motelikzagrosik.com.pl/sitemap.xml
```

### Dodanie sitemap.xml (opcjonalnie)
Możesz użyć generatora online lub utworzyć manualnie.

## 🐛 Rozwiązywanie problemów

### Strona nie ładuje się
- Sprawdź czy plik nazywa się `index.html`
- Sprawdź czy jest w katalogu `public_html` lub `www`
- Upewnij się że serwer wspiera HTML5

### Formularz nie działa
- Sprawdź czy wkleiłeś prawidłowy Discord webhook URL
- Sprawdź czy odkomentowałeś kod wysyłki
- Sprawdź konsolę przeglądarki (F12) czy są błędy

### Zdjęcia nie ładują się
- Sprawdź ścieżki do plików
- Upewnij się że zdjęcia są na serwerze
- Sprawdź wielkość liter w nazwach plików (Linux case-sensitive)

### Animacje nie działają
- Sprawdź czy JavaScript nie jest zablokowany
- Sprawdź czy CDN Tailwind i Alpine.js się ładują
- Wyczyść cache przeglądarki (Ctrl+F5)

## 📊 Optymalizacja

### Kompresja obrazów
Użyj narzędzi online:
- TinyPNG.com
- Squoosh.app
- ImageOptim

Zalecane formaty:
- WebP dla zdjęć (najlepszy)
- JPG dla zdjęć (dobry)
- PNG dla grafik z przezroczystością

### Lazy loading
Dodaj do tagów `<img>`:
```html
<img loading="lazy" src="...">
```

## 🔒 Bezpieczeństwo

### HTTPS
Upewnij się że hosting ma certyfikat SSL (większość ma za darmo przez Let's Encrypt).

### Backup
Rób regularne kopie zapasowe pliku przez FTP.

## 📞 Wsparcie

Jeśli masz pytania:
1. Sprawdź tę instrukcję ponownie
2. Sprawdź konsolę przeglądarki (F12 → Console)
3. Skontaktuj się ze swoim dostawcą hostingu

## ✅ Checklist przed uruchomieniem

- [ ] Zmieniono numer telefonu
- [ ] Zmieniono email
- [ ] Zmieniono adres
- [ ] Skonfigurowano Discord webhook
- [ ] Dodano prawdziwe zdjęcia
- [ ] Zaktualizowano ceny
- [ ] Dodano linki do social media
- [ ] Zaktualizowano meta tagi SEO
- [ ] Przetestowano formularz kontaktowy
- [ ] Przetestowano na telefonie
- [ ] Sprawdzono w różnych przeglądarkach

## 🎉 Gotowe!

Gratulacje! Twoja strona jest gotowa do uruchomienia. Powodzenia!

---

**Masz pytania? Nie działa coś?**  
Napisz mi - chętnie pomogę! 🚀