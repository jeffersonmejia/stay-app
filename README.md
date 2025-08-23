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

| No. | Actividad                                   | Fecha      |
| --- | ------------------------------------------- | ---------- |
| 1   | Inicialización de _repositorio_ git, github | 2025-08-20 |
| 2   | Configuración de _Docker_, Docker Compose   | 2025-08-20 |
| 3   | Diseño de _base de datos_: users, notes     | 2025-08-21 |
| 4   | Implementación de _registro_ usuarios       | 2025-08-22 |
| 5   | Implementación de _ingreso_ usuarios        | 2025-08-22 |
| 6   | Implementación de _CRUD_ de notas           | 2025-08-23 |
| 7   | _Pruebas_ locales y ajustes de seguridad    | 2025-08-25 |

# 6. Autor

[Jefferson Mejía](https://jeffersonmejia.github.io/portfolio-app), Ing. Tecnologías de la Información
