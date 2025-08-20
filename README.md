# Stay App
> Write your notes easily

Stay App is a simple web application to create, read, update, and delete notes. It provides basic authentication and note management linked to user accounts. 

## 1. Preview

![Stay App Preview](https://i.ibb.co/m59f42Nz/Sin-t-tulo-2025-08-20-1334.png)

## 2. Installation

### 2.1 Download Docker

Click [here](https://www.docker.com/get-started) to download and install Docker.

### 2.2 Clone GitHub repository

`git clone git@github.com:jeffersonmejia/stay-app.git`
`cd stay-app`

### 2.3 Clone GitHub repository

`git clone git@github.com:jeffersonmejia/stay-app.git`

`cd stay_app`

### 2.4 Run compose

`docker compose app`

### 2.5 Watch container process

`docker ps`

## 3. Database arquitecture
- Name: `stay_app`  
- Tables:
  - `users` (id, username, password)  
  - `notes` (id, title, description, user_id) 
