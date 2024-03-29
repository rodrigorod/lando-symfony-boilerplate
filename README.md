# 🚀 Lando Symfony Boilerplate 🎼

# 📋 Requirements

---

- [Docker](http://docker.com)
- [Lando](http://lando.dev)

# ⚡ Install

---

1. Modify the configuration to your needs
2. Run `lando start`

# 🔧 Configuration

---

```yaml
# .lando.yaml
name: lando-symfony-boilerplate
recipe: symfony
config:
  webroot: public
  php: '8.1'
  via: apache:2.4
  database: mysql:5.7
  port: 3306
  cache: redis
  xdebug: false
services:
  database:
    creds:
      database: db
      # username: symfony
      # password: symfony
      # host: database
app_mount: disabled
```

Change the database credentials to your needs. Then update it in the `.env` file

```bash
# .env
DATABASE_URL="mysql://symfony:symfony@database:3306/db?serverVersion=5.7&charset=utf8mb4"
```

⚠️ If you made any changes in the lando configuration, then run `lando rebuild`

## Coding standards / Unit tests

**Run tests**

```sh
lando composer tests
```

**Fix coding standards automatically**
```sh
lando composer standards-fix
```

**Running each test separately**
```sh
lando composer test-phpcpd
lando composer test-phpmd
lando composer test-phpstan
lando composer test-phpunit
lando composer test-twig
```
