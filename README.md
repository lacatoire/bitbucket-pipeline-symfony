# 🚀 Bitbucket Pipeline for Symfony

This repository contains the configuration of a Bitbucket pipeline optimized for a Symfony project.

## 📂 Project Structure

```
bitbucket-pipelines.yml                 # Main configuration file for Bitbucket Pipelines
/.docker/
  ├── phpstan/phpstan.neon              # PHPStan configuration
  ├── php-cs-fixer/.php-cs-fixer.php    # PHP CodeSniffer configuration
phpunit.xml.dist                        # PHPUnit configuration
README.md                               # Project documentation
```

## 🛠️ Prerequisites

Before running the pipeline, make sure you have:
- A Bitbucket account
- A Symfony project with a `composer.json`
- Docker installed if you want to test locally
- Configured environment variables in Bitbucket (`SONARCLOUD_TOKEN`, etc.)

## 🔧 Configuration

### 1️⃣ Environment Variables

Add the necessary environment variables in **Bitbucket Pipelines**:

| Name                 | Description |
|----------------------|-------------|
| `SONARCLOUD_TOKEN`   | SonarCloud access token |
| `MYSQL_DATABASE`     | Database name |
| `MYSQL_USER`        | MySQL username |
| `MYSQL_PASSWORD`    | MySQL password |

Copy `.env.example` to `.env` and adjust the values if needed.

```sh
cp .env.example .env
```

## 🚀 Composer Scripts

Scripts are defined in `composer.json` to automate certain tasks:

```json
"scripts": {
    "codesniffer": "PHP_CS_FIXER_IGNORE_ENV=1 php vendor/bin/php-cs-fixer fix src --dry-run --verbose --show-progress=dots --config=.docker/php-cs-fixer/.php-cs-fixer.php",
    "codesniffer-fix": "PHP_CS_FIXER_IGNORE_ENV=1 php vendor/bin/php-cs-fixer fix src --verbose --show-progress=dots --config=.docker/php-cs-fixer/.php-cs-fixer.php",
    "phpstan": "php -d memory_limit=2G ./vendor/bin/phpstan analyse --configuration .docker/phpstan/phpstan.neon",
    "phpstan-ci": "php -d memory_limit=2G ./vendor/bin/phpstan analyse --configuration .docker/phpstan/phpstan.neon --error-format=json --no-progress > .phpstan/phpstan-report.json",
    "phpunit": "php -d memory_limit=2G ./vendor/bin/phpunit --bootstrap ./tests/bootstrap.php --configuration ./phpunit.xml.dist ./tests/unit",
    "phpunit-integration": "php -d memory_limit=2G ./vendor/bin/phpunit --bootstrap ./tests/bootstrap.php --configuration ./phpunit.xml.dist ./tests/Controller"
}
```

These scripts allow:
- **Code verification and formatting** (`codesniffer`, `codesniffer-fix`)
- **Static code analysis** (`phpstan`, `phpstan-ci`)
- **Symfony cache warming** (`cache:warmup`)
- **Running unit and integration tests** (`phpunit`, `phpunit-integration`)

To run a script, use:

```sh
composer <script_name>
```

Example:

```sh
composer phpstan
```

## 🏗️ Pipelines

The `bitbucket-pipelines.yml` file automatically runs:

1. Check if the branch is up to date (`outdated-branch`)
2. Linter with PHP CS Fixer (`linter`)
3. Static analysis with PHPStan (`phpstan`)
4. Unit tests with PHPUnit (`unit_phpunit`)
5. Integration tests with PHPUnit (`integration_phpunit`)
6. Code analysis with SonarCloud (`sonarcloud`)

### 📌 Automatic Execution

Each pull request triggers the following steps:

```yaml
pipelines:
  pull-requests:
    '**':
      - step: *outdated-branch
      - parallel:
          - step: *linter
          - step: *phpstan
          - step: *unit_phpunit
          - step: *integration_phpunit
      - step: *sonarcloud
```

## 📝 Best Practices

- Always sync your branch with `main` before pushing
- Ensure tests pass locally before creating a PR
- Configure Git hooks to run PHP CS Fixer and PHPStan before committing

## 🎯 Goal

This pipeline ensures code quality and integrity of Symfony functionalities while integrating continuous analysis via SonarCloud.

💡 **Possible improvements:** Add automated deployment if needed.

---
