# Blog API

A Laravel 12 API backend for a simple blog

## Table of Reference
- [Prerequisites](#prerequisites)
- [Local Installation](#local-installation)
- [Running the Application](#running-the-application)
- [Mail Notifications](#mail-notifications)
- [API](#api)
- [API - Access application](#access-application-json-request-body)
- [API - Create new post](#create-new-post-endpoint)
- [API - Posts list filter](#posts-list-filter-endpoint)
- [API - Create new comment](#create-new-comment-endpoint)

---
## Prerequisites
- PHP 8.3+
- Mail SMTP credentials (for real email delivery)

---
## Local Installation
1) Clone the repository
    ```
   git clone https://github.com/ipatramanis/blog.git
    ```
2) Install PHP dependencies
    ```
   composer install
    ```
3) Install NPM dependencies
    ```
   npm install
    ```
4) Copy environment template.
   ```
   cp .env.example .env
   ```
   a. Configure database variables
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=database_name
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   b. Configure mail variables (to send real mail notifications)
   ```
   MAIL_MAILER=log
   MAIL_SCHEME=null
   MAIL_HOST=127.0.0.1
   MAIL_PORT=2525
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```
5) Generate the app key:
   ```
   php artisan key:generate
   ```
6) Run migrations and seed dummy data:
   ```
   php artisan migrate --seed
   ```
---
## Running the Application
- Start the HTTP server: `php artisan serve`
- Start the queue worker (notifications are queued): `php artisan queue:work`

## Mail Notifications
- Real email delivery requires valid SMTP settings in `.env` (`MAIL_MAILER=smtp`, host, port, username, password, etc.).
- If SMTP is not configured, Laravel will still write the rendered mail to `storage/logs/laravel.log` so you can see the message immediately.
- Browser preview of the comment notification is available at:
    ```
    GET /comments/{comment_id}/notifications/preview
  
    replace {comment_id} with a real comment id
  
    Example of browser preview url
    http://localhost:8000/comments/1/notifications/preview
    ```
  
---
## API

### API Authentication
- **Auth:** `POST /api/register`, `POST /api/login`, `POST /api/logout`
- **Access application:** register new user using `POST endpoint:` `/api/register`
  ###### Access application: JSON request body
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "repeat_password": "password"
  }
  ```
  ###### Response with access token
  ```json
  {
    "user": {
        "name": "John Doe",
        "email": "john@example.com",
        "updated_at": "2025-11-24T11:03:13.000000Z",
        "created_at": "2025-11-24T11:03:13.000000Z",
        "id": 2
    },
    "token": "21|oDddgDYHbn2s91aOq4itlUdFzUnTYd4QK9BD83op4834818e"
  }
  ```
- **Use bearer token to get authorized access to the rest of the application.**

---
### API Posts
- #### Create new post endpoint
  - `POST` `/api/comments`
  - Include the **bearer token** in the request header for authentication. 
  - Authenticated user id will be used as post owner.
  - **'new'** tag will be automatically added to a newly created post.
  ###### JSON request body
    ```json
    {
        "title": "My new post title",
        "slug": "my-post",
        "content": "My optional content",
        "category_id": 1,
        "tags": []
    }
    ```
  - `title` required unique post title, max size 255
  - `slug` required, max size 150
  - `content` optional, max size 500
  - `category_id` optional, integer, must be an existing category
  - `tags` optional array, must be an existing tag
  ###### JSON request body
  ```json
  {
    "title": "My new post title",
    "slug": "my-post",
    "content": "My optional content",
    "category_id": 1,
    "author": 9,
    "updated_at": "2025-11-24T12:48:36.000000Z",
    "created_at": "2025-11-24T12:48:36.000000Z",
    "id": 22
  }
  ```

---
- ##### Posts list filter endpoint:
  - `GET` `/api/posts`
  - Posts list filtered by author / category / tags
    ###### JSON request body:
    ```json
    {
      "filter_by": {
          "author_id": 2,
          "category_id": 2,
          "tags": [1, 4]
      }
    }
    ```
      - `author_id` required **integer**, an `id` of post author.
      - `category_id` required **integer**, an category `id`
      - `tags` required **array** of **integers**, each integer is a `tag id`

    ###### Response a list of posts with their relationship author, tags, category
    ```json
    [
        {
            "id": 1,
            "author": {
                "id": 9,
                "name": "John Doe",
                "email": "john@example.com",
                "email_verified_at": null,
                "created_at": "2025-11-24T11:03:13.000000Z",
                "updated_at": "2025-11-24T11:03:13.000000Z"
            },
            "category_id": 8,
            "title": "New post title",
            "content": "New post content",
            "slug": "new-post",
            "created_at": "2025-11-22T13:57:18.000000Z",
            "updated_at": "2025-11-24T13:31:19.000000Z",
            "category": {
                "id": 8,
                "parent_id": 7,
                "title": "Smartphones",
                "created_at": "2025-11-20T18:59:22.000000Z",
                "updated_at": "2025-11-20T18:59:22.000000Z"
            },
            "tags": [
                {
                    "id": 1,
                    "name": "new",
                    "created_at": "2025-11-20T18:59:22.000000Z",
                    "updated_at": "2025-11-20T18:59:22.000000Z",
                    "pivot": {
                        "post_id": 1,
                        "tag_id": 1
                    }
                },
                {
                    "id": 4,
                    "name": "promotion",
                    "created_at": "2025-11-20T18:59:22.000000Z",
                    "updated_at": "2025-11-20T18:59:22.000000Z",
                    "pivot": {
                        "post_id": 1,
                        "tag_id": 4
                    }
                }
            ]
        }
    ]
    ```
---
### API Comments
- ##### Create new comment endpoint:
  - Creating new comment will trigger the mail notification.
  - Endpoint `POST` `/api/comments`
  - Include the **bearer token** in the request header for authentication.
  - Authenticated user id will be applied as comment owner. 
  ###### JSON request body:
  ```json
  {
      "post_id": 1,
      "content": "My first test comment!"
  }
  ```
  ###### Response with newly created comment
    ```json
    {
        "post_id": 1,
        "content": "My first test comment!",
        "user_id": 9,
        "updated_at": "2025-11-24T11:38:29.000000Z",
        "created_at": "2025-11-24T11:38:29.000000Z",
        "id": 55
    }
    ```
  ###### Send mail notification
    - Run artisan command to start queue worker `php artisan queue:work`
    - Laravel default `MAIL_MAILER` is `log`, which will display any new mail notification into the `laravel.log` in the default path `/storage/logs/laravel.log`
    - You can preview any comment in web browser using the following endpoint in the browser `/comments/{comment_id}/preview/notification`
    - Browser example URL, replace {comment_id} with a real comment id
      ```
      http://localhost:8000/comments/1/notifications/preview
      ```
