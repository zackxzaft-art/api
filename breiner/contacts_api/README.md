# Proyecto de Contacts_api

## Autor

**Breiner Bermúdez Hernández**

## Descripción

Este proyecto permite realizar operaciones CRUD sobre los módulos de
**Contactos** y **Usuarios**, utilizando **Laravel Sanctum** para
proteger las rutas privadas.

### Rutas públicas

-   `POST /api/register`
-   `POST /api/login`

## Requisitos

-   PHP 8.3 o superior
-   Composer
-   MySQL
-   Node.js y NPM
-   Git
-   Laravel compatible con el proyecto
-   Postman, Insomnia o curl

## Tecnologías utilizadas

-   Laravel \^13.8
-   Laravel Sanctum \^4.0
-   PHP \^8.3

## Instalación

``` bash
git clone https://github.com/breiner-bh/contacts_api.git
cd contacts_api
composer install
npm install
```

Crear el archivo `.env`:

**Linux / Git Bash**

``` bash
cp .env.example .env
```

**PowerShell**

``` powershell
Copy-Item .env.example .env
```

Generar la clave:

``` bash
php artisan key:generate
```

Configurar la base de datos en `.env`:

``` env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=contacts_api
DB_USERNAME=root
DB_PASSWORD=
```

Crear la base de datos:

``` sql
CREATE DATABASE contacts_api;
```

Ejecutar migraciones y seeders:

``` bash
php artisan migrate
php artisan db:seed
```

O recrear la base de datos:

``` bash
php artisan migrate:fresh --seed
```

Levantar el proyecto:

``` bash
php artisan serve
```

URL base:

    http://127.0.0.1:8000/api

## Endpoints

  -----------------------------------------------------------------------------------------
  Módulo      Método      Endpoint                          Protegida    Descripción
  ----------- ----------- ------------------------------ --------------- ------------------
  Auth        POST        `/api/register`                     ❌ No      Registrar usuario

  Auth        POST        `/api/login`                        ❌ No      Iniciar sesión

  Usuario     GET         `/api/user`                         ✅ Sí      Obtener el usuario
                                                                         autenticado

  Usuarios    GET         `/api/users/{id}/contactos`         ✅ Sí      Listar contactos
                                                                         (index)

  Usuarios    GET         `/api/users/{user}/contacts`        ✅ Sí      Listar contactos
                                                                         del usuario

  Usuarios    PUT         `/api/users/{user}`                 ✅ Sí      Actualizar usuario

  Contactos   GET         `/api/contacts`                     ✅ Sí      Listar contactos

  Contactos   POST        `/api/contacts`                    ✅ Sí\*     Crear contacto

  Contactos   GET         `/api/contacts/{contact}`           ✅ Sí      Mostrar contacto

  Contactos   PUT/PATCH   `/api/contacts/{contact}`           ✅ Sí      Actualizar
                                                                         contacto

  Contactos   DELETE      `/api/contacts/{contact}`           ✅ Sí      Eliminar contacto
  -----------------------------------------------------------------------------------------

> **Nota:** Tienes una ruta adicional:
>
> `Route::post('/contacts', [ContactsController::class, 'store']);`
>
> Esta duplica el endpoint `POST /api/contacts` del `apiResource` y lo
> deja sin protección. Lo recomendable es eliminar esa ruta y usar
> únicamente el `Route::apiResource(...)->middleware('auth:sanctum');`.

## Autenticación

Primero registra un usuario o inicia sesión para obtener un token.

Luego envía el token en todas las rutas protegidas:

``` http
Authorization: Bearer TU_TOKEN_AQUI
```

## Ejecutar pruebas

``` bash
php artisan test
```

Pruebas específicas:

``` bash
php artisan test --filter=ContactsTest

```
