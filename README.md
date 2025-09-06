# Stay App

> Escribe tus notas fácilmente

Stay App es una aplicación web sencilla para crear, leer, actualizar y eliminar notas. Proporciona autenticación básica y gestión de notas vinculadas a las cuentas de usuario. Se emplea el uso de Docker (MySQL, Apache2, FTP) para aislar servicios y brindar robustes en la seguridad del aplicativo.

## 1. Preview

![Vista previa](https://i.ibb.co/zVL5GfMx/Facebook-cover-Restaurante-Elegante-Minimal-Verde-removebg-preview-1.png)

![Vista previa](https://i.ibb.co/0yP58yJR/signin.png)

![Vista previa](https://i.ibb.co/60LC8f1Z/home.png)

## 2. Requisitos funcionales

- Docker como sistema de contenedores.
- Servidor web apache.
- PHP como lenguaje de programación para manipular las rutas y recursos del servidor web apache.
- Sistema de registro e ingreso con sesiones.
- Servidor de base de datos MySQL que almacene usuarios y notas.
- Servidor SFTP que almacene archivos adjuntos de las notas almacenadas del usuario.

## 3. Requisitos no funcionales

> Aplicación que permita al usuario registrarse con un correo electrónico y clave, asimismo pueda ingresar a la aplicación con dichas credenciales y escribir notas y adjuntar archivos en las mismas.

## 4. Bosquejo de arquitectura

![Vista previa de Stay App](https://i.ibb.co/m59f42Nz/Sin-t-tulo-2025-08-20-1334.png)

## 5. Arquitectura de la base de datos

- Nombre: `stay_app`
- Tablas:
  - `users` (id, email, password)
  - `notes` (id, title, description, user_id)

## 6. Bitácora de actividades

_Fecha de inicio_: 2025-08-20

_Fecha esperada de finalización_: 2025-08-27

_Tiempo esperado de finalización_: 8 días

| No. | Categoría            | Actividad                                              | Fecha      |
| --- | -------------------- | ------------------------------------------------------ | ---------- |
| 1   | Control de versiones | Inicialización de _repositorio_ git, GitHub            | 2025-08-20 |
| 2   | Contenedores         | Configuración de _Docker_ y Docker Compose             | 2025-08-20 |
| 3   | Backend              | Configuración de servidor Apache                       | 2025-08-21 |
| 4   | DB                   | Configuración de servidor MySQL                        | 2025-08-21 |
| 5   | Backend              | Configuración de servidor de archivos SFTP             | 2025-08-22 |
| 6   | DB                   | Diseño de _base de datos_: users, notes                | 2025-08-23 |
| 7   | Backend              | Implementación de _registro_ usuarios /signup.php      | 2025-08-24 |
| 8   | Backend              | Implementación de _ingreso_ usuarios /signin.php       | 2025-08-24 |
| 9   | Backend              | Implementación de _Crear_, _Leer_, _Eliminar_ de notas | 2025-08-25 |
| 10  | Testing              | _Pruebas_ locales y ajustes de seguridad               | 2025-08-26 |

## 7. Contenedores

Servicios que componen la aplicación, las imágenes asociadas y los puertos expuestos para acceso desde el host.

| Nombre          | Imagen        | Puerto (Host → Contenedor) |
| --------------- | ------------- | -------------------------- |
| stay-app-web-1  | stay-app-web  | 8080 → 80                  |
| stay-app-sftp-1 | stay-app-sftp | 22 → 22                    |
| stay-app-db-1   | mysql:8.0     | 3306 → 3306                |

**RTO sugerido**: 5min.

## 8. Instalación

### 8.1 Descargar Docker

Haz clic [aquí](https://www.docker.com/get-started) para descargar e instalar Docker.

### 8.2 Clonar el repositorio de GitHub

`git clone git@github.com:jeffersonmejia/stay-app.git`

`cd stay-app`

### 8.3 Ejecutar Docker Compose

`docker compose up`

### 8.4 Ver los contenedores en ejecución

`docker ps`

![Containers](https://i.ibb.co/1YpvCHRt/containers.png)

### 8.5 Verificar permisos

Entrar en modo interactivo y observar que los permisos de las carpetas se encuentren de la siguiente manera:

`docker exec -it stay-app-sftp-1 bash`

- Home: `ls -ld /home`
- User: `ls -ld /home/user`
- upload: `ls -ld /home/user/upload`

![Permission](https://i.ibb.co/QFB77Gbv/permission.png)

## 9. Recomendaciones

- Verificar que la base de datos se encuentre encendida con `docker ps`, caso contrario el servicio web no encenderá por motivos de dependencia entre contenedores.

- No utilizar **FTP** porque presenta inconvenientes en la comunicación entre contenedores debido al sistema de puertos activos y pasivos. En su lugar usar **SFTP** que cifra la conexión, es más seguro y actualizado.

- No borrar el contenedor con `docker compose down` porque elimina permisos internos en la subida de archivos y usuarios de la base de datos. En su lugar, detenerlo con `docker compose stop`

- Utilizar el script `server/scripts/docker-start.ps1` y `server/scripts/docker-stop.ps1` al inicio en el proceso de instalación puesto que consume recursos de manera significativa. En su lugar manejar `docker compose up -d` luego de la instalación para encender servicios. Tambien se sugiere usarlo en caso de que docker esté apagado.

## 10. Autor

[Jefferson Mejía](https://jeffersonmejia.github.io/portfolio-app), Ing. Tecnologías de Información y Comunicación
