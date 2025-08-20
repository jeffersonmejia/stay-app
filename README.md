# Stay App
> Write your notes easily

Stay App is a simple web application to create, read, update, and delete notes. It provides basic authentication and note management linked to user accounts. 

## Preview

![Stay App Preview](https://i.ibb.co/4w5thJkR/Sin-t-tulo-2025-08-20-1334.png)

## Installation

### Download Docker

Click [here](https://www.docker.com/get-started) to download and install Docker.

### Clone GitHub repository

`git clone git@github.com:jeffersonmejia/stay-app.git`
`cd stay-app`

### 1.2 Clone GitHub repository

`git clone git@github.com:jeffersonmejia/stay-app.git`

`cd stay_app`

### 1.3 Run compose

`docker compose app`

### 1.4 Watch container process

`docker ps`

## Database
- Name: `stay_app`  
- Tables:
  - `users` (id, username, password)  
  - `notes` (id, title, description, user_id) 
