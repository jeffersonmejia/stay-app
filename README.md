# Stay App

> Escribe tus notas fácilmente

Stay App es una aplicación web sencilla para crear, leer, actualizar y eliminar notas. Proporciona autenticación básica y gestión de notas vinculadas a las cuentas de usuario.

## 1. Preview

![Vista previa](https://i.ibb.co/zVL5GfMx/Facebook-cover-Restaurante-Elegante-Minimal-Verde-removebg-preview-1.png)

## 2. Bosquejo de arquitectura

![Vista previa de Stay App](https://i.ibb.co/m59f42Nz/Sin-t-tulo-2025-08-20-1334.png)

## 3. Instalación

### 3.1 Descargar Docker

Haz clic [aquí](https://www.docker.com/get-started) para descargar e instalar Docker.

### 3.2 Clonar el repositorio de GitHub

`git clone git@github.com:jeffersonmejia/stay-app.git`

`cd stay-app`

### 3.3 Ejecutar Docker Compose

`docker compose app`

### 3.4 Ver los contenedores en ejecución

`docker ps`

## 4. Arquitectura de la base de datos

- Nombre: `stay_app`
- Tablas:
  - `users` (id, email, password)
  - `notes` (id, title, description, user_id)

# 5. Bitácora de actividades

*Fecha de inicio*:  2025-08-20

*Fecha esperada de finalización*:  2025-08-27

| No. | Categoría        | Actividad                                        | Fecha      |
| --- | ---------------- | ------------------------------------------------ | ---------- |
|  1  | Control de versiones | Inicialización de _repositorio_ git, GitHub     | 2025-08-20 |
|  2  | Contenedores     | Configuración de _Docker_ y Docker Compose      | 2025-08-20 |
|  3  | Backend          | Configuración de servidor Apache                | 2025-08-21 |
|  4  | DB               | Configuración de servidor MySQL                 | 2025-08-21 |
|  5  | Backend          | Configuración de servidor de archivos FTP       | 2025-08-22 |
|  6  | DB               | Diseño de _base de datos_: users, notes        | 2025-08-23 |
|  7  | Backend          | Implementación de _registro_ usuarios /signup.php | 2025-08-24 |
|  8  | Backend          | Implementación de _ingreso_ usuarios /signin.php  | 2025-08-24 |
|  9  | Backend          | Implementación de _CRUD_ de notas              | 2025-08-25 |
| 10  | Testing          | _Pruebas_ locales y ajustes de seguridad      | 2025-08-26 |

# 6. Autor

[Jefferson Mejía](https://jeffersonmejia.github.io/portfolio-app), Ing. Tecnologías de la Información
