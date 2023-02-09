# Configuração

Configurações iniciais para iniciar a aplicação.

## 1. Configurar arquivo .env

Edite o arquivo `.env` na raiz do projeto.

#### Configuração do banco de dados

```dotenv
database.default.hostname =
database.default.database =
database.default.username =
database.default.password =
database.default.DBDriver =
database.default.DBPrefix =
database.default.port =
```

#### Configurações de Sessões
[doc](https://codeigniter.com/user_guide/libraries/sessions.html)

```dotenv
session.driver =
session.cookieName =
session.expiration =
session.savePath =
session.matchIP =
session.timeToUpdate =
session.regenerateDestroy =
```

#### Configurações GitHub

```dotenv
github_client_id =
github_client_secret =
github_redirect_uri =
```