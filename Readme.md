# Endevaour Assessment
## endevaour_assessment_05_07_2025

This project was developed as part of the Endevaour application process. It allows retrieveing data from csv or json file to mysql database.

---

## Tech Stack

- **Composer**
- **Laravel+**
- **MySQL** 
- **Docker** 

---

## Installation

### Clone repository

```bash
git clone https://github.com/erciyescagan/endevaour_assessment_05_07_2025.git
```

### Copy .env.example in .env

```bash
mv .env.example .env
```

### Docker

```bash
docker compose up -d --build 
```

```bash
docker exec -it merterciyescagan_app bash 
```

### Install composer dependencies

```bash
composer install 
```

### Migrate database

```bash
php artisan migrate
```

## Usage 

### Start Queue Working

```bash
php artisan queue:work
```

### Import File Command

```bash
php artisan import:subjects {file} 
```

### Filtering

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


### Optional Feature

You are able to define a start index and end index.

```bash
php artisan import:subjects {file} --start={startIndex} --end={endInndex}
```
