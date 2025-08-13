# Bazaar Project

## Requirements
- Docker
- Docker Compose
- Make (for Ubuntu / Mac)

---

## Installation

1. Clone the project:
    ```bash
    git clone git@github.com:brann-meius/bazaar.git bazaar
    ```
2. Run:
    ```bash
    cd bazaar
    ```

---

## Project Launch

### Ubuntu / Mac
1. Run:
    ```bash
    make init
    ```

---

### Windows

1. Create `.env` file from the template:

   **PowerShell:**
    ```powershell
    cp .env.example .env
    ```

   **CMD:**
    ```cmd
    copy .env.example .env
    ```

2. Run build and containers:
    ```bash
    docker-compose up -d --build
    ```

---

## Access

1. Wait for the containers (~15 seconds), You can check the project building progress inside the app container
