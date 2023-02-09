# Instalação

### 1. Baixar imagens Docker

Utilizado a imagem [bitnami/codeigniter](https://hub.docker.com/r/bitnami/codeigniter/)

```bash
docker compose up
```

### 2. Instalar imagem do Composer

```bash
docker pull composer
```

### 3. Instalar dependências do PHP Composer

```bash
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer install
```

### 4. Executar Migrates

Pelo navegador:

```bash
http://localhost:8000/migrate
```

Por comando [Spark ](https://codeigniter4.github.io/userguide/cli/spark_commands.html#):

```bash
php spark migrate
```