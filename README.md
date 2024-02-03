<p align="center">
    <a href="https://learnwithventure.com" target="_blank">
    <img src="https://learnwithventure.com/assets/logo.png" width="50" alt="Venture Logo">
    </a>
</p>

## Venture - AI-Enhanced Homeschooling Platform

### Introduction

Welcome to Venture, a pioneering platform in the realm of homeschooling. At the heart of Venture lies a mission to transform how students learn at home. Leveraging the power of Artificial Intelligence (AI), Venture offers an unparalleled, personalized learning experience for each student.

### Key Features

- AI-Driven Customization: Venture integrates advanced AI to tailor the learning experience, adapting to each student's unique learning style, pace, and preferences.
- Comprehensive Curriculum: Our platform covers a wide range of subjects, ensuring a well-rounded educational journey.
- Interactive Learning: With the use of Laravel, InertiaJS, VueJS, and Tailwind CSS, Venture provides a dynamic, responsive, and engaging user interface.
- Expert Support: Access to a network of educational experts to guide and assist in the homeschooling journey.

### Getting Started

Installation:
- First, pull down the github repo and make sure to run `php artisan composer install`.
- Next, you'll need to run `php artisan migrate --seed`. This will create your tables and seed them.
    - This will create a user of `admin@test.com` which you can login with using `password` as the password.
    - It will also create a student with a username of `teststudent` and a password of `password`.
- Finally, you'll need to run `npm install && npm run dev`.

Configuration:
- You'll need to set the following variables in your .env file.
    - OPENAI_API_KEY
    - OPENAI_ORGANIZATION
    - STRIPE_KEY
    - STRIPE_SECRET
    - STRIPE_WEBHOOK_SECRET
    - STRIPE_MONTHLY_PRICE
    - STRIPE_ANNUAL_PRICE
    - STUDENT_FREE_QUESTION_COUNT
    - NOVA_LICENSE_KEY

Notes: Most of the emails that are sent are sent via the queue system so you'll need to make sure to run `php artisan queue:work` if your QUEUE_CONNECTION variable is set to database.

### Contribution

Venture is a student-led initiative. We welcome contributions from the community. Whether you're a developer, an educator, or a student, your insights and code contributions can help shape the future of homeschooling.

### Support

For support, queries, or feedback, please contact us at support@learnwithventure.com. Your input is invaluable in making Venture a better platform for all.

### Releases

Releases are created in Github Actions via tag v.*. Keep in mind that before a release is created via a tag push, the CHANGELOG.md will have to be updated. Things to keep in mind:

1. The changelog format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
2. This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
