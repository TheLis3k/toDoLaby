<?php
// ── Konfiguracja MySQL ─────────────────────────────────────────
define('MYSQL_HOST',     getenv('MYSQL_HOST')     ?: 'mysql');
define('MYSQL_PORT',     getenv('MYSQL_PORT')     ?: '3306');
define('MYSQL_DATABASE', getenv('MYSQL_DATABASE') ?: 'myapp_db');
define('MYSQL_USER',     getenv('MYSQL_USER')     ?: 'appuser');
define('MYSQL_PASSWORD', getenv('MYSQL_PASSWORD') ?: 'apppassword123');

// ── Konfiguracja MongoDB ───────────────────────────────────────
define('MONGO_HOST',     getenv('MONGO_HOST')     ?: 'mongodb');
define('MONGO_PORT',     getenv('MONGO_PORT')     ?: '27017');
define('MONGO_DATABASE', getenv('MONGO_DATABASE') ?: 'myapp_mongo');

// ════════════════════════════════════════════════════════════════
// MYSQL — połączenie przez PDO
// ════════════════════════════════════════════════════════════════
//
// Użycie w index.php:
//
//   $pdo = getMysqlConnection();
//
// SELECT:
//   $rows = $pdo->query('SELECT * FROM users')->fetchAll();
//
// SELECT z parametrami (bezpieczne, używaj zawsze dla danych od użytkownika!):
//   $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
//   $stmt->execute(['id' => 1]);
//   $row = $stmt->fetch();
//
// INSERT:
//   $stmt = $pdo->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');
//   $stmt->execute(['name' => 'Jan', 'email' => 'jan@example.com']);
//   $newId = $pdo->lastInsertId();
//
// UPDATE:
//   $stmt = $pdo->prepare('UPDATE users SET name = :name WHERE id = :id');
//   $stmt->execute(['name' => 'Nowe imię', 'id' => 1]);
//
// DELETE:
//   $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
//   $stmt->execute(['id' => 1]);
//
// Tabele tworzysz/edytujesz w:
//   → phpMyAdmin: http://localhost:8081
//   → lub SQL w pliku: docker/mysql/init/01_init.sql (tylko przy pierwszym starcie)
//
// ════════════════════════════════════════════════════════════════

function getMysqlConnection(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            MYSQL_HOST, MYSQL_PORT, MYSQL_DATABASE
        );
        $pdo = new PDO($dsn, MYSQL_USER, MYSQL_PASSWORD, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

// ════════════════════════════════════════════════════════════════
// MONGODB — połączenie przez oficjalną bibliotekę PHP
// ════════════════════════════════════════════════════════════════
//
// Użycie w index.php:
//
//   $col = getMongoCollection('nazwa_kolekcji');
//   // Kolekcja tworzy się automatycznie przy pierwszym zapisie
//
// INSERT (insertOne):
//   $col->insertOne(['title' => 'Tytuł', 'body' => 'Treść']);
//
// INSERT wiele (insertMany):
//   $col->insertMany([
//       ['title' => 'Pierwszy'],
//       ['title' => 'Drugi'],
//   ]);
//
// SELECT wszystkich:
//   $docs = iterator_to_array($col->find());
//
// SELECT z filtrem:
//   $docs = iterator_to_array($col->find(['status' => 'active']));
//
// SELECT jednego:
//   $doc = $col->findOne(['_id' => new MongoDB\BSON\ObjectId('abc123...')]);
//
// UPDATE:
//   $col->updateOne(
//       ['_id' => new MongoDB\BSON\ObjectId('abc123...')],
//       ['$set' => ['title' => 'Nowy tytuł']]
//   );
//
// DELETE:
//   $col->deleteOne(['_id' => new MongoDB\BSON\ObjectId('abc123...')]);
//
// Kolekcje przeglądasz w:
//   → Mongo Express: http://localhost:8082  (login: admin / admin123)
//
// ════════════════════════════════════════════════════════════════

function getMongoCollection(string $collectionName): MongoDB\Collection {
    static $client = null;
    if ($client === null) {
        $uri    = sprintf('mongodb://%s:%s', MONGO_HOST, MONGO_PORT);
        $client = new MongoDB\Client($uri);
    }
    return $client->selectCollection(MONGO_DATABASE, $collectionName);
}