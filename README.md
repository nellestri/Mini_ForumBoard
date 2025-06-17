# Mini Forum

A modern, feature-rich forum application built with Laravel. This project provides a platform for users to create topics, post replies, and share images in a safe, moderated environment.

## Features

- User authentication and dashboard
- Create, edit, and delete topics and replies
- Markdown formatting support for posts and replies
- Image uploads for topics and replies
- Moderation tools and safe environment
- User statistics and activity tracking
- Responsive, dark-themed UI

## Getting Started

### Prerequisites

- PHP >= 8.0
- Composer
- Node.js & npm

### Installation

1. **Clone the repository:**
   ```sh
   git clone https://github.com/yourusername/mini-forum.git
   cd mini-forum
   ```
2. **Install PHP dependencies:**
   ```sh
   composer install
   ```
3. **Set up the environment file:**
   ```sh
   cp .env.example .env
   ```
4. **Generate the application key:**
   ```sh
   php artisan key:generate
   ```
5. **Run the database migrations:**
   ```sh
   php artisan migrate
   ```
6. **Install front-end dependencies and build assets:**
   ```sh
   npm install
   npm run dev
   ```
7. **Start the development server:**
   ```sh
   php artisan serve
   ```

Now you can access the forum application at `http://localhost:8000`.

## Usage

- Register a new account or log in to an existing account.
- Create a new topic by clicking on the "New Topic" button.
- Fill in the title and content of your topic, using Markdown for formatting if desired.
- Upload images by dragging and dropping them into the post editor or by using the image upload button.
- Submit your topic and participate in discussions by posting replies.
- Moderators can manage topics and replies, as well as view user statistics and activity.

## Contributing

Contributions are welcome! Please follow these steps to contribute to the project:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Make your changes and commit them.
4. Push your branch to your forked repository.
5. Create a pull request describing your changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
