# Prueba Técnica PHP con Arquitectura DDD

## Requisitos del Sistema
Antes de comenzar, asegúrate de tener las siguientes herramientas instaladas en tu sistema:

- **Docker** (versión recomendada: `24.x` o superior)
- **Docker Compose** (versión recomendada: `2.x` o superior)
- **Git** (versión recomendada: `2.40` o superior)
- **PHP** (versión recomendada: `8.4` o superior)
- **Composer** (versión recomendada: `2.x` o superior)
- **MySQL** (versión recomendada: `8.x` o superior)
- **Make** (para ejecutar comandos de automatización con Makefile)

## Instalación y Configuración del Proyecto

### 1️⃣ Clonar el Repositorio
Ejecuta el siguiente comando para clonar el repositorio:
```shell
git clone https://github.com/abhuanco/prueba-tecnica-php-ddd.git
```
```shell
cd prueba-tecnica-php-ddd
```

### 2️⃣ Crear el Archivo `.env`
Copia el archivo `.env.example` y renómbralo como `.env`:
```shell
cp .env.example .env
```
Configura las variables de entorno según tu entorno local.

### 3️⃣ Construir los Contenedores con Makefile
Para levantar el entorno de desarrollo con Docker, ejecuta:
```shell
make up
```

Esto iniciará los siguientes servicios:
- `nginx` (Servidor web en el puerto `9090`)
- `app` (Aplicación PHP con PHP 8.4 y Xdebug)
- `mysql` (Base de datos MySQL en el puerto `3307`)

### 4️⃣ Instalar Dependencias
Ejecuta el siguiente comando para instalar las dependencias de PHP con Composer:

```shell
make install
```

### 5️⃣ Ejecutar las Migraciones de la Base de Datos
```shell
make migrate
```

## Ejecución del Proyecto

### 1️⃣ Ejecutar la Aplicación
Una vez que el entorno está levantado, procede a abrir tu terminal y ejecuta o si prefiere puede usar postman o cualquier herramienta para hacer peticiones http:

```shell
curl --location 'http://localhost:9090' \
--header 'Content-Type: application/json' \
--data-raw '{
  "name": "Rene Huanco",
  "email": "rhuanco@gmail.com",
  "password": "Pa$$W00rD"
}'
```

### 2️⃣ Verificar la Conexión a la Base de Datos y los datos ingresados
Puedes conectarte a la base de datos MySQL usando el comando y la contraseña definida en el archivo `.env` [MYSQL_PASSWORD]:

```shell
make mysql
```

```mysql
SHOW DATABASES;
```
```mysql
USE app_ddd;
```
```mysql
SHOW TABLES;
```
```mysql
SELECT * FROM users;
```

## Pruebas

### 1️⃣ Ejecutar Pruebas Unitarias y de Integración
Para ejecutar las pruebas unitarias con PHPUnit y Cobertura de Código, usa:
```sh
make test
```
Abre `http://localhost:9090/coverage/index.html` en tu navegador para ver el reporte de cobertura.

## Flujo de Desarrollo

### 1️⃣ Levantar el Servidor para Desarrollo
Si quieres iniciar el servidor de desarrollo, usa:
```shell
make up
```

### 2️⃣ Detener los Contenedores
Para detener los contenedores y eliminar volúmenes, usa:
```shell
make down
```
---

## Contacto
Si tienes dudas, puedes escribir a **rene.huanco.choque@gmail.com** o crear un **issue** en el repositorio.

