# memoflick

Never forget another birthday with this birthday reminder application with simple and user-friendly design to help users keep track of birthdays for their friends, family, and colleagues. With an intuitive interface, the app allows users to input and store important birthdates, receive timely reminders, and plan for celebrations. Never miss another birthday with this handy tool that ensures you stay connected and make your loved ones feel special on their special day. The app aims to simplify the process of managing and remembering birthdays, fostering stronger connections and spreading joy.

## Introduction

Welcome to **memoflick**! 🎉

Remembering and celebrating the birthdays of our loved ones can sometimes be challenging in our busy lives. **Memoflick** is here to make that task effortless and enjoyable. This user-friendly application serves as your personal assistant, helping you keep track of important birthdays and ensuring you never miss an opportunity to send warm wishes.

### Built With

These are the technologies used to develop this application.

- [![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
- [![Angular](https://img.shields.io/badge/angular-%23DD0031.svg?style=for-the-badge&logo=angular&logoColor=white)](https://angular.io/)
- [![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
- [![Bootstrap](https://img.shields.io/badge/bootstrap-%238511FA.svg?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

## Features

1. Intuitive Interface
    - A user-friendly design that makes adding and managing birthdays a breeze.
2. Annual Email Reminders
    - Set up automatic reminders via email that notify you every year on the exact date of the birthday. Never forget to send your best wishes and gifts again.
3. Group Reminders
    - Organize your reminders by creating custom groups such as Friends, Family, Coworkers, etc. Streamline your birthday management for a more organized experience.
4. Contact Details
    - Store phone numbers of your loved ones directly within the app, ensuring you have easy access to reach out and make the day extra special.
5. Notes Section
    - Include a dedicated notes section for each reminder. Write down birthday wishes, gift ideas, or any other important information to enhance the personal touch.

**Memoflick** goes beyond simple date tracking, offering a suite of features that enable you to personalize your interactions, stay connected, and celebrate birthdays in a meaningful way. Join this journey to make every birthday special and memorable! Let this birthday reminder app be your companion in fostering meaningful connections with the people who matter most.

## Usage

To get started with the backend of the Birthday Reminder App, follow these steps:

1. Install Dependencies
Make sure you have **Composer** installed.
```
composer install
```
2. Configure Environment
Duplicate the .env.example file and rename it to .env. Update the database configuration and other relevant settings.
```
cp .env.example .env
```
3. Generate Application Key
Generate the application key used for encryption and other security-related tasks.
```
php artisan key:generate
```
4. Run Migrations
Run the database migrations to create the necessary tables.
```
php artisan migrate
```
5. Serve the Application
Use the following command to start the development server.
```
php artisan serve
```
The backend API will be accessible at http://localhost:8000.

### API Endpoints
The backend provides the following API endpoints:

GET /api/birthdays: Retrieve all birthdays.

GET /api/birthdays/{id}: Retrieve a specific birthday.

POST /api/birthdays: Add a new birthday.

PUT /api/birthdays/{id}: Update a birthday.

DELETE /api/birthdays/{id}: Delete a birthday.

## Link to birthday-reminder-app-frontend

Check the [frontend repository](https://github.com/andonovangel/birthday-reminder-app-frontend) of **memoflick**

### Relationship Explanation:

- [birthday-reminder-app-frontend](https://github.com/andonovangel/birthday-reminder-app-frontend) focuses on the user interface and client-side functionality. It contains the code for the web application's user interface, design, and user interactions.
- birthday-reminder-app-backend complements this by providing the server-side logic, database interactions, and overall backend functionality. It handles data processing, business logic, and communicates with the frontend to deliver a complete web application experience.
- Together, they form a comprehensive full-stack web application, where the frontend interacts with the backend to create a seamless and functional user experience.

## Contact

Have questions, suggestions, or just want to say hello? I'd love to hear from you!

Email: andonovangel1@gmail.com

LinkedIn: [Angel Andonov](https://www.linkedin.com/in/andonovangel/)

Feel free to reach out for any inquiries related to **memoflick**. I value your feedback and always open for collaboration and improvements.

Happy birthday celebrating! 🎂🎉
