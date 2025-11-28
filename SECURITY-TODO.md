Plan zmian CORS dla środowiska produkcyjnego:

- Ogranicz nagłówek `Access-Control-Allow-Origin` w `send-contact.php` do produkcyjnej domeny:
  `https://motelikzagrosik.com.pl`
- Rozważ dodanie prostego antyspamu (hCaptcha lub token czasowy).
- W razie braku `allow_url_fopen` użyj cURL zamiast `file_get_contents`.
- Loguj błędy do osobnego pliku (np. `logs/contact.log`) z rotacją.

Uwaga: w środowisku testowym pozostaw `*` aby umożliwić integrację i testy lokalne.
