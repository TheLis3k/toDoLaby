-- Ten plik wykonuje się automatycznie przy pierwszym starcie MySQL
-- Możesz tu dodać tabele startowe

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Przykładowe dane
INSERT INTO users (name, email) VALUES
    ('Jan Kowalski', 'jan@example.com'),
    ('Anna Nowak', 'anna@example.com');