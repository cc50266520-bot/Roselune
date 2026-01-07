# Rosélune – AI-Based Makeup Recommendation System

Rosélune is an online makeup store that uses AI-based skin tone detection to recommend suitable makeup products such as foundation, lipstick, and blush. The system aims to improve the online shopping experience by providing personalized recommendations based on facial analysis.

## Features
- User registration and login
- Upload photo or use camera for face capture
- Automatic skin tone detection (light / medium / dark)
- Personalized makeup recommendations (foundation, lipstick, blush)
- Product browsing and category filtering
- Shopping cart and checkout system
- Admin dashboard for managing products and orders

## Technologies Used
- PHP
- MySQL (phpMyAdmin)
- HTML, CSS, JavaScript
- XAMPP
- face-api.js (for face detection)

## How to Run Locally
1. Install **XAMPP**
2. Copy the project folder to:
C:\xampp\htdocs\HasnaaChakik_51831003

markdown
Copy code
3. Start **Apache** and **MySQL** from XAMPP Control Panel
4. Open **phpMyAdmin** and create a database named:
roselune

markdown
Copy code
5. Import the provided SQL file into the `roselune` database
6. Open your browser and go to:
http://localhost/HasnaaChakik_51831003/index.php

pgsql
Copy code

## Admin Access
To access the admin dashboard, set the value of `is_admin` to `1` for the desired user in the `users` table.

## Author
**Hasnaa Chakik**  
Lebanese International University
