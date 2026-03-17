# 🐳 PHP + MySQL + MongoDB — Docker Setup

Kompletne środowisko deweloperskie na Dockerze: **PHP 8.2 + Apache**, **MySQL 8**, **MongoDB 7**, **phpMyAdmin**, **Mongo Express**.

---

## 📁 Struktura projektu

```
projekt/
├── docker-compose.yml          # Główny plik konfiguracji Dockera
├── .env                        # Hasła i zmienne środowiskowe (NIE commituj!)
├── .gitignore
├── docker/
│   ├── php/
│   │   └── Dockerfile          # Obraz PHP z rozszerzeniami MySQL + MongoDB
│   └── mysql/
│       └── init/
│           └── 01_init.sql     # SQL wykonywany przy pierwszym starcie MySQL
└── src/                        # ← TU piszesz swój kod PHP
    ├── index.php
    ├── .htaccess
    ├── config/
    │   └── db.php              # Konfiguracja połączeń z bazami
    └── css/
        └── style.css
```

---

## 🚀 Uruchomienie (pierwsze uruchomienie)

### 1. Otwórz folder w VSC i upewnij się, że Docker Desktop działa

Ikona Dockera w zasobniku systemowym powinna być **zielona** (Running).

### 2. Otwórz terminal w VSC (`Ctrl + ~`) i wpisz:

```bash
docker-compose up --build
```

> Pierwsze uruchomienie trwa ~3–5 minut — Docker pobiera obrazy i buduje PHP z rozszerzeniami.

### 3. Gotowe! Otwórz w przeglądarce:

| Serwis        | Adres                      |
|---------------|----------------------------|
| Strona PHP    | http://localhost:8080       |
| phpMyAdmin    | http://localhost:8081       |
| Mongo Express | http://localhost:8082       |

---

## 🔄 Codzienna praca

```bash
# Uruchom kontenery (bez przebudowywania)
docker-compose up -d

# Zatrzymaj kontenery
docker-compose down

# Wyświetl logi (np. błędy PHP)
docker-compose logs -f php

# Wejdź do kontenera PHP (terminal wewnątrz)
docker exec -it php_app bash
```

---

## ⚙️ Zmiana haseł / konfiguracji

Edytuj plik `.env`:

```env
MYSQL_ROOT_PASSWORD=rootpassword123
MYSQL_DATABASE=myapp_db
MYSQL_USER=appuser
MYSQL_PASSWORD=apppassword123
MONGO_DATABASE=myapp_mongo
```

> ⚠️ Po zmianie `.env` musisz usunąć wolumeny i zrestartować:
> ```bash
> docker-compose down -v
> docker-compose up --build
> ```

---

## 🗄️ Praca z bazami danych

### MySQL
- GUI: **phpMyAdmin** → http://localhost:8081
- Połączenie z poziomu PHP: przez `getMysqlConnection()` w `config/db.php`
- Tabele startowe: dodaj SQL do `docker/mysql/init/01_init.sql`

### MongoDB
- GUI: **Mongo Express** → http://localhost:8082 (login: `admin` / `admin123`)
- Połączenie z poziomu PHP: przez `getMongoCollection('nazwa_kolekcji')` w `config/db.php`

---

## 🧩 Instalacja bibliotek PHP (Composer)

Wejdź do kontenera i użyj Composera:

```bash
docker exec -it php_app bash
composer require vendor/package
exit
```

Plik `composer.json` pojawi się w `src/` i będzie zsynchronizowany z Twoim dyskiem.

---

## ⚠️ Tricky rzeczy — na co uważać

### 1. `docker-compose up --build` vs `up`
`--build` przebudowuje obraz PHP (potrzebne gdy zmieniasz `Dockerfile`).
Bez `--build` Docker używa cache — szybciej, ale nie widzi zmian w Dockerfile.

### 2. Zmiany w `src/` widoczne od razu — bez restartu
Folder `src/` jest zamontowany jako **volume** — zmieniasz plik w VSC → odświeżasz przeglądarkę → gotowe. Nie musisz restartować Dockera.

### 3. MySQL nie startuje / błąd połączenia
Najczęstsza przyczyna: MySQL potrzebuje ~10 sekund żeby wystartować, a PHP próbuje połączyć się za szybko. Odczekaj chwilę i odśwież stronę.

### 4. "Port is already allocated" — błąd przy starcie
Inny program używa portu 8080, 3306 lub 27017. Sprawdź co to:
```bash
netstat -ano | findstr :8080
```
Albo zmień port w `docker-compose.yml`, np. `"8090:80"`.

### 5. Dane w MySQL znikają po `docker-compose down -v`
Flaga `-v` usuwa wolumeny (dane baz). Bez `-v` dane są bezpieczne.
```bash
docker-compose down       # ✅ dane zostają
docker-compose down -v    # ❌ dane usunięte
```

### 6. `.env` na GitHubie
Plik `.env` jest w `.gitignore` — NIE wrzucaj go na GitHub. Zamiast tego stwórz `.env.example` z pustymi wartościami jako wzór dla innych.

### 7. MongoDB extension dla PHP
Rozszerzenie `mongodb` dla PHP różni się od popularnego `mongo` (stary, nierozwijany). Używaj zawsze `mongodb` + biblioteki `mongodb/mongodb` przez Composer.

### 8. Błędy widoczne tylko w logach
Jeśli strona się nie ładuje, sprawdź logi:
```bash
docker-compose logs -f php
docker-compose logs -f mysql
docker-compose logs -f mongodb
```

### 9. Wolumin MySQL przy zmianie hasła
Jeśli zmienisz hasło w `.env`, ale wolumin MySQL już istnieje — stare hasło zostanie zachowane. Rozwiązanie:
```bash
docker-compose down -v
docker-compose up --build
```

### 10. Apache mod_rewrite (przekierowania)
Jeśli chcesz ładne URL-e (np. `/user/123` zamiast `?id=123`), odkomentuj odpowiednie linie w `src/.htaccess`. `mod_rewrite` jest już włączony w Dockerfile.

---

## 🛑 Resetowanie wszystkiego od zera

```bash
docker-compose down -v --rmi local
docker-compose up --build
```

---

## 📚 Przydatne linki

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [PHP dokumentacja](https://www.php.net/docs.php)
- [MongoDB PHP Library](https://www.mongodb.com/docs/drivers/php/)
- [PDO (MySQL w PHP)](https://www.php.net/manual/en/book.pdo.php)