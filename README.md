# Stay App

> Escribe tus notas fácilmente

Stay App es una aplicación web sencilla para crear, leer, actualizar y eliminar notas. Proporciona autenticación básica y gestión de notas vinculadas a las cuentas de usuario.

## 1. Vista previa

![Vista previa de Stay App](https://i.ibb.co/m59f42Nz/Sin-t-tulo-2025-08-20-1334.png)

## 2. Instalación

### 2.1 Descargar Docker

Haz clic [aquí](https://www.docker.com/get-started) para descargar e instalar Docker.

### 2.2 Clonar el repositorio de GitHub

`git clone git@github.com:jeffersonmejia/stay-app.git`

`cd stay-app`

### 2.3 Ejecutar Docker Compose

`docker compose app`

### 2.4 Ver los contenedores en ejecución

`docker ps`

## 3. Arquitectura de la base de datos

- Nombre: `stay_app`
- Tablas:
  - `users` (id, email, password)
  - `notes` (id, title, description, user_id)

# Bitácora de actividades

| No. | Actividad                                 | Fecha      |
| --- | ----------------------------------------- | ---------- |
| 1   | Inicialización de repositorio git, github | 2025-08-20 |
| 2   | Configuración de Docker, Docker Compose   | 2025-08-20 |
| 3   | Diseño de base de datos: users, notes     | 2025-08-21 |
| 4   | Implementación de registro usuarios       | 2025-08-22 |
| 5   | Implementación de ingreso usuarios        | 2025-08-22 |
| 6   | Implementación de CRUD de notas           | 2025-08-23 |
| 7   | Pruebas locales y ajustes de seguridad    | 2025-08-25 |
