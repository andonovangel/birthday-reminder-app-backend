# memoflick

Never forget another birthday with this birthday reminder application with simple and user-friendly design to help users keep track of birthdays for their friends, family, and colleagues. With an intuitive interface, the app allows users to input and store important birthdates, receive timely reminders, and plan for celebrations. Never miss another birthday with this handy tool that ensures you stay connected and make your loved ones feel special on their special day. The app aims to simplify the process of managing and remembering birthdays, fostering stronger connections and spreading joy.

## Introduction

Welcome to **memoflick**! 🎉

Remembering and celebrating the birthdays of our loved ones can sometimes be challenging in our busy lives. **Memoflick** is here to make that task effortless and enjoyable. This user-friendly application serves as your personal assistant, helping you keep track of important birthdays and ensuring you never miss an opportunity to send warm wishes.

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

API Endpoints
The backend provides the following API endpoints:

GET /api/birthdays: Retrieve all birthdays.

GET /api/birthdays/{id}: Retrieve a specific birthday.

POST /api/birthdays: Add a new birthday.

PUT /api/birthdays/{id}: Update a birthday.

DELETE /api/birthdays/{id}: Delete a birthday.

Make sure to update your frontend or any client application to make requests to these endpoints based on your requirements.

Feel free to customize the backend according to your needs, and refer to the Laravel documentation for additional details on extending functionality or securing your API.

## Contact

Have questions, suggestions, or just want to say hello? I'd love to hear from you!

Email: andonovangel1@gmail.com

LinkedIn: [Angel Andonov](https://www.linkedin.com/in/andonovangel/)

Feel free to reach out for any inquiries related to **memoflick**. We value your feedback and are always open to collaboration and improvement.

Happy birthday celebrating! 🎂🎉
