# Endeavour Assessment

## Repository: `endevaour_assessment_05_07_2025`

This project was developed as part of the Endeavour application process. It provides a robust system for importing data from CSV or JSON files into a MySQL database using Laravel.

The application directly choose the correct File Processor regarding to extension of uploaded file.



---

## Tech Stack

- **Composer**
- **Laravel 12+**
- **MySQL**
- **Docker**

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/erciyescagan/endevaour_assessment_05_07_2025.git
cd endevaour_assessment_05_07_2025
```

### 2. Start Docker containers

```bash
docker compose up -d --build
```

### 3. Access the application container

```bash
docker exec -it merterciyescagan_app bash
```

### 4. Install PHP dependencies

```bash
composer install
```

### 5. Set up environment variables

```bash
cp .env.example .env
```

### 6. Run database migrations

```bash
php artisan migrate
```

---

## Usage

### Start the queue worker

```bash
php artisan queue:work
```

### Import file into the database

Ensure the file is already placed inside the `storage/app` directory.

```bash
php artisan import:file {filename}
```

Replace `{filename}` with the actual name of your file (e.g., `example.json`).

---

## Features

### Filtering Records

To apply filters before persisting the data, modify the `initializeService()` method by creating new objects from required filter classes in the `ImportSubjectJob` class:

```php
private function initializeService(): void
{
    $this->service = new ImportSubjectService([
        new AgeFilter(),
        new CheckedFilter(),
        new TripleDigitFilter()
    ]);
}
```

Each filter implements a common interface to allow flexible and testable data filtering.

### Creating a New File Processor

```bash
php artisan make:file-processor {file}
```

### Creating a New Filtering 

```bash
php artisan make:filter {file}
```

### Creating a New Service 

Every service class must be tied to a corresponding model.

```bash
php artisan make:service {file} {--model=}
```
