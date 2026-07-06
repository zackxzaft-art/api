# Contact API

API REST hecha con Laravel para registrar usuarios, iniciar sesion con Laravel Sanctum y administrar contactos privados por usuario.

Cada usuario puede:

- Registrarse.
- Iniciar sesion y recibir un token.
- Actualizar su informacion.
- Crear contactos.
- Listar sus contactos con paginacion.
- Ver, editar y eliminar solo sus propios contactos.
- Evitar contactos repetidos con el mismo numero de telefono.

## Requisitos

Antes de instalar el proyecto necesitas tener:

- PHP 8.3 o superior.
- Composer.
- MySQL o MariaDB.
- Git.
- Git Bash para ejecutar los ejemplos de `curl`.

Puedes verificar PHP y Composer con:

```bash
php -v
composer -V
```

## Instalacion

### 1. Clonar el repositorio

```bash
git clone URL_DEL_REPOSITORIO
cd contact_api
```

Si ya tienes el proyecto descargado, solo entra a la carpeta:

```bash
cd contact_api
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Crear el archivo `.env`

```bash
cp .env.example .env
```

### 4. Generar la llave de Laravel

```bash
php artisan key:generate
```

### 5. Configurar la base de datos

Crea una base de datos en MySQL, por ejemplo:

```sql
CREATE DATABASE contact_api;
```

Luego abre el archivo `.env` y configura tus datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=contact_api
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### 6. Ejecutar migraciones

```bash
php artisan migrate
```

Si quieres cargar datos de prueba:

```bash
php artisan db:seed
```

### 7. Levantar el servidor

```bash
php artisan serve
```

La API quedara disponible en:

```txt
http://127.0.0.1:8000/api
```

## Ejecutar Tests

Para correr todos los tests:

```bash
php artisan test
```

El proyecto tiene tests para:

- Registro de usuario.
- Actualizacion de usuario.
- Validacion de correo repetido.
- Creacion de contactos.
- Listado de contactos del usuario autenticado.
- Validacion de telefono repetido.
- Bloqueo de contactos de otros usuarios.

## Rutas Publicas

Estas rutas no necesitan token:

| Metodo | Ruta | Descripcion |
| --- | --- | --- |
| POST | `/api/register` | Registrar usuario |
| POST | `/api/login` | Iniciar sesion |

## Rutas Protegidas

Estas rutas necesitan token Bearer:

| Metodo | Ruta | Descripcion |
| --- | --- | --- |
| GET | `/api/user` | Ver usuario autenticado |
| PUT | `/api/user` | Actualizar usuario autenticado |
| POST | `/api/logout` | Cerrar sesion |
| GET | `/api/contacts` | Listar contactos |
| POST | `/api/contacts` | Crear contacto |
| GET | `/api/contacts/{id}` | Ver contacto |
| PUT/PATCH | `/api/contacts/{id}` | Actualizar contacto |
| DELETE | `/api/contacts/{id}` | Eliminar contacto |

## Probar la API con curl en Git Bash

En los ejemplos se usa esta URL base:

```bash
BASE_URL="http://127.0.0.1:8000/api"
```

Ejecuta eso primero en Git Bash:

```bash
BASE_URL="http://127.0.0.1:8000/api"
```

### 1. Registrar un usuario

```bash
curl -X POST "http://127.0.0.1:8000/api/register" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Perez",
    "email": "juan@example.com",
    "password": "password123"
  }'
```

Respuesta esperada:

```json
{
  "message": "Usuario registrado correctamente",
  "user": {
    "id": 1,
    "name": "Juan Perez",
    "email": "juan@example.com"
  },
  "token": "TOKEN_GENERADO"
}
```

Guarda el token que devuelve la respuesta. Lo vas a usar en las rutas privadas.

### 2. Iniciar sesion

```bash
curl -X POST "$BASE_URL/login" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@example.com",
    "password": "password123"
  }'
```

La respuesta tambien devuelve un token.

### 3. Guardar el token en una variable

Copia el token recibido y guardalo asi:

```bash
TOKEN="PEGA_AQUI_TU_TOKEN"
```

Luego las rutas privadas usan este header:

```bash
-H "Authorization: Bearer $TOKEN"
```

### 4. Ver el usuario autenticado

```bash
curl -X GET "$BASE_URL/user" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

### 5. Actualizar el usuario autenticado

```bash
curl -X PUT "$BASE_URL/user" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "name": "Juan Actualizado",
    "email": "juan.actualizado@example.com"
  }'
```

Respuesta esperada:

```json
{
  "message": "Usuario actualizado correctamente"
}
```

## Contactos

Los contactos pertenecen al usuario autenticado. Por eso, al crear un contacto no debes enviar `user_id`; la API lo asigna automaticamente usando el token.

### 1. Crear contacto

```bash
curl -X POST "http://127.0.0.1:8000/api/contacts" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "name": "Carlos Lopez",
    "phone_number": "3001234567"
  }'
```

Respuesta esperada:

```json
{
  "message": "Contacto creado correctamente",
  "data": {
    "id": 1,
    "name": "Carlos Lopez",
    "phone_number": "3001234567"
  }
}
```

### 2. Listar contactos

```bash
curl -X GET "http://127.0.0.1:8000/contacts" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|ivaiEesxprx8SoVOjtiAeqR9YMwcXjJi5wRZ2Efd33b115eb"
```

La lista esta paginada. Por defecto devuelve 5 contactos por pagina.

### 3. Listar contactos con paginacion

Pagina 1:

```bash
curl -X GET "$BASE_URL/contacts?page=1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

Pagina 2:

```bash
curl -X GET "$BASE_URL/contacts?page=2" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

Cambiar cantidad por pagina:

```bash
curl -X GET "$BASE_URL/contacts?per_page=10" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

Tambien puedes combinar ambos:

```bash
curl -X GET "$BASE_URL/contacts?page=2&per_page=10" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

El maximo permitido por la API es 50 contactos por pagina.

### 4. Ver un contacto

Cambia `1` por el ID real del contacto:

```bash
curl -X GET "$BASE_URL/contacts/1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

Si intentas ver un contacto de otro usuario, la API responde con `403 Forbidden`.

### 5. Actualizar un contacto

```bash
curl -X PUT "$BASE_URL/contacts/1" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "name": "Carlos Actualizado",
    "phone_number": "3007654321"
  }'
```

Respuesta esperada:

```json
{
  "message": "Contacto actualizado correctamente"
}
```

### 6. Eliminar un contacto

```bash
curl -X DELETE "$BASE_URL/contacts/1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

Respuesta esperada:

```json
{
  "message": "Contacto eliminado correctamente"
}
```

### 7. Cerrar sesion

```bash
curl -X POST "$BASE_URL/logout" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

## Validaciones importantes

### Usuario

Para registrar usuario:

- `name` es obligatorio.
- `email` es obligatorio, debe ser valido y no debe estar repetido.
- `password` es obligatorio y minimo de 8 caracteres.

### Contacto

Para crear contacto:

- `name` es obligatorio.
- `phone_number` es opcional.
- `phone_number` no se puede repetir para el mismo usuario.
- `user_id` no se envia desde el cliente; se toma desde el token.

## Codigos de respuesta comunes

| Codigo | Significado |
| --- | --- |
| 200 | Peticion correcta |
| 201 | Recurso creado correctamente |
| 401 | No autenticado o token invalido |
| 403 | No tienes permiso para acceder a ese recurso |
| 422 | Error de validacion |
| 404 | Recurso no encontrado |

## Flujo rapido para probar

```bash
BASE_URL="http://127.0.0.1:8000/api"
```

```bash
curl -X POST "$BASE_URL/register" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"name":"Ana Gomez","email":"ana@example.com","password":"password123"}'
```

```bash
TOKEN="PEGA_AQUI_TU_TOKEN"
```

```bash
curl -X POST "$BASE_URL/contacts" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"name":"Maria Contacto","phone_number":"3012223333"}'
```

```bash
curl -X GET "$BASE_URL/contacts?page=1&per_page=5" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```
