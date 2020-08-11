# Vigotech event notifier

Trátase dun pequeno programa para notificar eventos asociados aos grupos de Vigotech (obtidos de https://vigotech.org/vigotech.json)

Actualmente realiza notificacións a 
- Twitter ([@VigoTechAllianc](https://twitter.com/VigoTechAllianc))
- Slack (https://vigotechalliance.slack.com, canle #eventos)
- Telegram (canle de difusión https://t.me/vigotech)

# Instalación

O máis cómodo é facer uso da receita de Docker Compose [`docker-compose.yaml`](docker-compose.yaml) facendo un
```
docker-compose up -d
```

E logo facer a instalación das dependencias con composer:

```
docker-compose exec php bash composer install
```

# Configuración

## Variables de contorna e entornos locais

Pódese utilizar as variables de de contorna especificadas no arquivo [`.env.example`](.env.example) 

```ini
GROUPS_JSON_URL=https://vigotech.org/vigotech.json

#Eventrite
EVENTBRITE_OAUTH_TOKEN=

#Slack
SLACK_WEBHOOK_URL=
SLACK_ICON_URL=

#Twitter
TWITTER_CONSUMER_KEY=
TWITTER_CONSUMER_SECRET=
TWITTER_TOKEN=
TWITTER_TOKEN_SECRET=

#Telegram
TELEGRAM_TOKEN_BOT=
TELEGRAM_CHAT_ID=@@Vigotechtest
```

Tamén se pode copiar o ficheiro [`.env.example`](.env.example) a `.env`. No caso de precisar de dúas contornas de execución (por exemplo para probas) pódese  utilizar tamén un arquivo con nome `.env.local` que terá prioridade sobre as variables de entorno e as engadidas no `.env`.

## Outra configuración

O resto da configuración reside en [`services/config.yaml`](services/config.yaml), onde se define o idioma e formato para as datas e os notificadores habilitados:

```
  date:
    locale: 'gl_ES'
    format: "eeee, d 'de' MMMM 'ás' HH:mm'h'"
    timezone: 'Europe/Madrid'
```

```yaml
  event.notifier:
    class: Vigotech\Service\EventNotifier\EventNotifier
    arguments:
      - '@event.notifier.slack'
      - '@event.notifier.twitter'
      - '@event.notifier.telegram'
```

# Execución

Executando a axuda do comando `vigotech:notify` obtemos:
```
docker-compose exec php_cli ./bin/console vigotech:notify --help

Description:
  Notify users about events via defined notifiers

Usage:
  vigotech:notify [options]

Options:
  -M, --month           Notifies events for current month
  -w, --weekly          Notifies events for next 7 days
  -d, --daily           Notifies today's events
  -u, --upcoming        Notifies upcoming events
  -p, --preview         Set preview mode: Push messages to CLI. Don't publish anything
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Notify users about events via enabled notifiers (added at config/services.yaml)
