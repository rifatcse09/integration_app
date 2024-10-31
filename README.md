# Bit Integration - Shopify App

## Project Setup

This README will guide you through setting up the Bit Integration Shopify App. Follow the steps below to get the project running on your local machine.

### Prerequisites

Ensure you have the following installed on your machine:

- **PHP** (>= 8.2)
- **Redis extension** (if you want to use Redis as the queue connection)
- **Nginx**
- **PostgreSQL** (>= 14)
- **ngrok** (for local HTTPS tunneling)

## Docker Services
- nginx
- app
- web
- worker
- database
- redis
- certbot
- mailhog

## Requirements
- Docker
- Docker Compose

## Stop & Remove all the containers (optional)
To stop and remove all Docker containers, you can run the following commands:

```shell
docker stop $(docker ps -a -q)
docker rm $(docker ps -a -q)
```

## Installation
1. Clone the Bit-integrations-Docker project and navigate to the project directory:

   HTTPS:
   ```shell
   git clone https://github.com/JoulesLabs/bit-integrations-docker.git 
   cd bit-integrations-docker
   ```

   SSH:
   ```shell
   git clone git@github.com:JoulesLabs/bit-integrations-docker.git
   cd bit-integrations-docker
   ```

## File Overview
The project structure looks like this:

```shell
bit-integrations-docker
├── .docker
├── app
│   └── all app files
├── web
│   └── all web files
├── .dockerignore
├── .gitignore
├── .env
├── docker-compose.yml.example
├── README.md
└── make-ssl.sh.example
```

### Environment Variables

Create a `.env` file in the root directory of your project by copying from the `.env.example` file and set the following environment variables:

```bash
cp .env.example .env
```

Then, open the `.env` file and set the following environment variables:

```env
SHOPIFY_APP_NAME="Bit Integration"
SHOPIFY_API_VERSION=2024-04
SHOPIFY_API_KEY=
SHOPIFY_API_SECRET=
SHOPIFY_API_SCOPES=read_orders,read_products,read_themes,read_customers,read_content,write_content,read_locales
SHOPIFY_API_REDIRECT=/back/authenticate
WEBHOOKS_JOB_QUEUE=webhook
```

### Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yourusername/bit-integrations.git
   git clone https://github.com/yourusername/bit-web.git
   cd app
   cp .env.example .env
   cd web
   cp .env.example .env
   docker-compose up -d build
   ```


3. **Install PHP dependencies:**

   Using Composer:

   ```bash
   docker-compose app composer install
   ```

4. **Set up PostgreSQL:**

   Ensure PostgreSQL is running and create a new database for the project. Update your `.env` file with the database connection details:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

5. **Run database migrations:**

   ```bash
   docker-compose app php artisan migrate
   ```

6. **Seed the database:**

   ```bash
   docker-compose app php artisan db:seed
   ```

7. **Set up Redis (if using Redis for queues):**

   Ensure Redis is running and update your `.env` file to configure the queue connection:

   ```env
   QUEUE_CONNECTION=redis
   ```

8. **Set up ngrok:**

   Start an ngrok tunnel to forward HTTP traffic to your local server:

   ```bash
   ngrok http 8000
   ```

   Note the forwarding URL provided by ngrok (e.g., `https://abcd1234.ngrok.io`), as you will need it in the next step.

9. **Configure Shopify App URL:**

   Update your app settings in the Shopify Partner Dashboard with the ngrok URL. Set the App URL and Redirect URLs to match the ngrok forwarding URL.

10. **Start the development server:**

    Using Artisan:

    ```bash
    php artisan serve
    ```

### Webhooks

The app uses webhooks to handle various Shopify events. The `WEBHOOKS_JOB_QUEUE` environment variable defines the queue for webhook jobs. Ensure your job processing system is configured to handle this queue.

### Customization

- Replace `https://github.com/yourusername/yourrepository.git` with your actual repository URL.
- Adjust the directory structure and paths in the instructions if they differ.
- Add more sections as needed, such as **Troubleshooting**, **FAQ**, **Credits**, or **Contact**.
