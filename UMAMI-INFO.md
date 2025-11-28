# 📊 Umami Analytics - Instrukcja

## ✅ Co zostało skonfigurowane?

Strona ma już zainstalowany **Umami Analytics** z Twojego serwera:

```html
<script defer src="https://analytics.zarnecki.org/script.js" 
        data-website-id="9c7d1c47-f398-4ab2-bd19-0fc39b92dab3"></script>
```

## 🤔 Pytanie: Czy klient ma mieć własne konto?

### Opcja A: Udostępnij tylko link do statystyk (PROSTSZE)

**Zalety:**
- ✅ Klient nie musi się logować
- ✅ Nie ma dostępu do ustawień (nie zepsuje niczego)
- ✅ Może tylko oglądać statystyki
- ✅ Możesz kontrolować co widzi

**Jak to zrobić:**

1. Zaloguj się do Umami (`https://analytics.zarnecki.org`)
2. Wejdź w ustawienia strony "Motelik Za Grosik"
3. Włącz **"Share URL"** / **"Publiczny link"**
4. Skopiuj wygenerowany link (np. `https://analytics.zarnecki.org/share/xxx`)
5. Wyślij link klientowi

**Link będzie wyglądał mniej więcej tak:**
```
https://analytics.zarnecki.org/share/9c7d1c47-f398-4ab2-bd19-0fc39b92dab3/motelikzagrosik.com.pl
```

Klient kliknie i od razu widzi statystyki - bez logowania! 🎯

---

### Opcja B: Stwórz klientowi konto (BARDZIEJ PROFESJONALNE)

**Zalety:**
- ✅ Klient ma swój login i hasło
- ✅ Bardziej profesjonalnie wygląda
- ✅ Może sobie ustawić powiadomienia email (jeśli Umami to obsługuje)
- ✅ Widzi statystyki jako "właściciel"

**Wady:**
- ❌ Klient może przypadkowo coś zmienić
- ❌ Musisz mu założyć konto i dać dane
- ❌ Musi pamiętać login/hasło

**Jak to zrobić:**

1. Zaloguj się do Umami jako admin
2. Przejdź do **Settings → Users** / **Ustawienia → Użytkownicy**
3. Kliknij **"Add User"** / **"Dodaj użytkownika"**
4. Wypełnij dane:
   - Username: `motelikzagrosik` lub email klienta
   - Password: Wygeneruj bezpieczne hasło
   - Role: **"View Only"** (tylko odczyt) lub **"User"** (może edytować swoją stronę)
5. Przypisz temu użytkownikowi dostęp do strony "Motelik Za Grosik"
6. Wyślij klientowi dane:
   ```
   Panel statystyk: https://analytics.zarnecki.org
   Login: motelikzagrosik
   Hasło: [bezpieczne_hasło]
   ```

---

## 🎯 Moja rekomendacja: **Opcja A (Share URL)**

**Dlaczego?**

1. **Prostsze dla klienta** - jeden link, zero logowania
2. **Bezpieczniejsze dla Ciebie** - klient nie ma dostępu do ustawień
3. **Szybsze** - nie musisz zakładać konta
4. **Elastyczne** - możesz zawsze zmienić uprawnienia lub wyłączyć link

---

## 📱 Co klient zobaczy w statystykach?

Niezależnie od opcji, klient będzie widział:

- 📊 **Liczba odwiedzin** (dziś, wczoraj, ostatnie 7/30 dni)
- 🌍 **Skąd przychodzą goście** (miasta, kraje)
- 📱 **Urządzenia** (desktop, mobile, tablet)
- 🔗 **Źródła ruchu** (Google, Facebook, bezpośrednie wejścia)
- 📄 **Najpopularniejsze strony** (które sekcje są najczęściej oglądane)
- ⏱️ **Czas na stronie** (jak długo goście zostają)

---

## 🔒 RODO i prywatność

Umami jest RODO-compliant z definicji:
- ✅ Nie używa cookies
- ✅ Nie zbiera danych osobowych
- ✅ Anonimizuje IP
- ✅ Hostowane na Twoim serwerze (nie Google)
- ✅ Nie trzeba baneru cookie!

---

## 🛠️ Co dalej?

1. **Zdecyduj się** na Opcję A lub B
2. **Skonfiguruj** dostęp dla klienta w Umami
3. **Wyślij** link lub dane logowania
4. **Gotowe!** Statystyki już zbierają się 📈

---

## 💡 Bonus: Własne cele (Events)

Jeśli chcesz śledzić konkretne akcje (np. kliknięcia w telefon, otwarcie formularza), możesz dodać **custom events** w kodzie:

```javascript
// Przykład: tracking kliknięcia w telefon
document.querySelector('a[href^="tel:"]').addEventListener('click', () => {
  umami.track('Kliknięcie w telefon');
});
```

Ale to opcjonalne - podstawowe statystyki działają już teraz! ✅

---

**Potrzebujesz pomocy z konfiguracją?** Daj znać! 🚀
