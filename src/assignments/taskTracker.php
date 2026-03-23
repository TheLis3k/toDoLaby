<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rozbudowany Menedżer Zadań</title>
    <style>
        :root {
            --primary-blue: #2c3e50;
            --accent-blue: #3498db;
            --bg-color: #f4f7f6;
            --card-bg: #ffffff;
            --text-color: #333;
            --border-color: #ccc;
            --alert-bg: #f8d7da;
            --alert-text: #721c24;
            --required-color: #e74c3c;
        }

        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        }

        .main-header {
            text-align: center;
            max-width: 900px;
            margin: 0 auto 20px auto;
        }

        .main-header h1 {
            color: var(--primary-blue);
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .blue-line {
            height: 2px;
            background-color: var(--accent-blue);
            width: 100%;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
            color: var(--primary-blue);
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .alert {
            background-color: var(--alert-bg);
            color: var(--alert-text);
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 0.95rem;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .grid-row {
            display: grid;
            gap: 20px;
            margin-bottom: 20px;
        }

        .grid-2-1 { grid-template-columns: 2fr 1fr; }
        .grid-3 { grid-template-columns: 1fr 1fr 1fr; }
        .full-width { grid-template-columns: 1fr; }

        .field {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 0.95rem;
            color: var(--primary-blue);
        }

        .req {
            color: var(--required-color);
            margin-left: 3px;
        }

        input, select, textarea {
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
            width: 100%;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .resources-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--primary-blue);
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .checkbox-item input {
            width: auto;
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .grid-row {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>
<body>

    <header class="main-header">
        <h1>Rozbudowany Menedżer Zadań</h1>
        <div class="blue-line"></div>
    </header>

    <div class="container">
        <h2>Dodaj nowe zadanie</h2>

        <section class="alert">
            <ul>
                <li>Kategoria jest wymagana</li>
            </ul>
        </section>

        <form action="#">
            <div class="grid-row grid-2-1">
                <div class="field">
                    <label for="title">Tytuł zadania:<span class="req">*</span></label>
                    <input type="text" id="title" required>
                </div>
                <div class="field">
                    <label for="category">Kategoria:<span class="req">*</span></label>
                    <select id="category" required>
                        <option value="" selected>Wybierz kategorię</option>
                        <option value="home">Domowe</option>
                        <option value="work">Praca</option>
                        <option value="study">Nauka</option>
                        <option value="hobby">Hobby</option>
                        <option value="other">Inne</option>
                    </select>
                </div>
            </div>

            <div class="grid-row full-width">
                <div class="field">
                    <label for="description">Opis zadania:</label>
                    <textarea id="description"></textarea>
                </div>
            </div>

            <div class="grid-row grid-3">
                <div class="field">
                    <label for="priority">Priorytet:<span class="req">*</span></label>
                    <select id="priority" required>
                        <option value="">Wybierz priorytet</option>
                        <option value="1">Niski</option>
                        <option value="2">Średni</option>
                        <option value="3">Wysoki</option>
                    </select>
                </div>
                <div class="field">
                    <label for="status">Status:</label>
                    <select id="status">
                        <option value="">Wybierz status</option>
                        <option value="new">Nowe</option>
                        <option value="pending">W trakcie</option>
                        <option value="done">Zakończone</option>
                    </select>
                </div>
                <div class="field">
                    <label for="due-date">Data wykonania:<span class="req">*</span></label>
                    <input type="text" id="due-date" placeholder="14.04.2025" required>
                </div>
            </div>

            <div class="grid-row grid-3">
                <div class="field">
                    <label for="time">Szacowany czas (minuty):</label>
                    <input type="number" id="time" placeholder="">
                </div>
                <div class="field">
                    <label for="location">Lokalizacja:</label>
                    <input type="text" id="location">
                </div>
                <div class="field">
                    <label for="assignee">Osoba przypisana:</label>
                    <input type="text" id="assignee">
                </div>
            </div>

            <div class="resources-section">
                <div class="resources-title">Potrzebne zasoby:</div>
                <div class="checkbox-group">
                    <label class="checkbox-item"><input type="checkbox"> Komputer</label>
                    <label class="checkbox-item"><input type="checkbox"> Internet</label>
                    <label class="checkbox-item"><input type="checkbox"> Telefon</label>
                    <label class="checkbox-item"><input type="checkbox"> Samochód</label>
                    <label class="checkbox-item"><input type="checkbox"> Książka</label>
                    <label class="checkbox-item"><input type="checkbox"> Narzędzia</label>
                    <label class="checkbox-item"><input type="checkbox"> Dokumenty</label>
                    <label class="checkbox-item"><input type="checkbox"> Inne</label>
                </div>
            </div>
        </form>
    </div>

</body>
</html>
