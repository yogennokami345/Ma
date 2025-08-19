<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/version-1.0.0-blue.svg" alt="Version"></a>
  <a href="#"><img src="https://img.shields.io/badge/license-MIT-green.svg" alt="License"></a>
  <a href="#"><img src="https://img.shields.io/badge/open%20source-❤️-red.svg" alt="Open Source"></a>
  <a href="#"><img src="https://img.shields.io/badge/bun-powered-ff69b4.svg" alt="Bun Powered"></a>
</p>

# 📚 Nuva

**Nuva** is a modern and open source online reader for comics and ebooks. Designed with performance and user experience in mind, Nuva provides a seamless reading experience with advanced features for managing and enjoying your digital library.

## ✨ Key Features

- 📖 **Online Reading**: Smooth and responsive interface for reading comics and ebooks
- 🗂️ **Library Management**: Organize and manage your collection with ease
- 🌐 **Multi-format Support**: Supports popular formats like PDF, EPUB, CBZ, and CBR
- 🔍 **Search and Filter**: Powered by Meilisearch for fast and accurate results
- 🎨 **Customizable Themes**: Light and dark modes with ShadCN Vue components
- 📊 **Reading Progress**: Track your progress across multiple books
- ⚡ **Optimized Performance**: Redis caching and efficient database queries
- 📱 **Responsive Design**: Works perfectly on desktops, tablets, and mobile devices

## 🛠️ Tech Stack

### Backend
- **Laravel** - Modern and elegant PHP framework
- **Inertia.js** - Seamless server-side rendering with Vue.js
- **Redis** - High-performance caching
- **PostgreSQL** - Relational database
- **Meilisearch** - Lightning-fast search engine

### Frontend
- **Vue.js** - Reactive JavaScript framework
- **ShadCN Vue** - Accessible and customizable UI components
- **Tailwind CSS** - Utility-first CSS framework
- **Filament** - Modern admin panel for managing the application

### Development Tools
- **Bun** - Ultra-fast JavaScript runtime and package manager
- **Makefile** - Simplified development commands
- **Vite** - Modern build tool
- **Composer** - PHP dependency manager

## 📋 Prerequisites

- PHP >= 8.1
- Composer
- Bun >= 1.0.0
- PostgreSQL >= 12
- Redis >= 6.0
- Meilisearch >= 1.0

## 🛠️ Installation

1. **Clone the repository**
```bash
git clone https://github.com/YanEmmanuel/nuva.git
cd nuva
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install JavaScript dependencies with Bun**
```bash
bun install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Database and search configuration**
```bash
# Edit .env file with your PostgreSQL, Redis, and Meilisearch credentials
php artisan migrate --seed
```

6. **Build assets**
```bash
bun run build
# or for development
bun run dev
```

7. **Start the application**
```bash
make up
```

8. **Start the frontend in development mode**
```bash
make dev
```

Access `http://localhost:8000` in your browser.

## 🎮 Basic Usage

### Adding Comics/Ebooks

1. Upload your files through the admin panel (`/admin`).
2. Organize your library by categories, tags, or series.
3. Start reading directly from your browser.

### Reading Features

- **Zoom and Pan**: Adjust the view for comics and images.
- **Bookmarks**: Save your favorite pages for quick access.
- **Reading Modes**: Single page, double page, or continuous scrolling.

### Admin Panel

Manage your library, users, and system settings through the intuitive admin interface.

## 🔧 Advanced Configuration

### Supported Formats

```php
// config/nuva.php
return [
    'supported_formats' => ['pdf', 'epub', 'cbz', 'cbr'],
    'max_upload_size' => 100, // in MB
    'thumbnail_generation' => true,
];
```

### Custom Themes

```javascript
// resources/js/theme-config.js
const themeConfig = {
    defaultTheme: 'dark',
    availableThemes: ['light', 'dark', 'sepia'],
    fontSize: 16,
    lineHeight: 1.5,
};
```

## 📚 Documentation

- [Installation Guide](docs/installation.md)
- [API Reference](docs/api.md)
- [Developer Guide](docs/developer-guide.md)
- [Usage Examples](docs/examples.md)
- [FAQ](docs/faq.md)

## 🤝 Contributing

**Nuva is 100% open source!** Contributions are very welcome. Please read our [contribution guide](CONTRIBUTING.md) before submitting your PR.

### How to Contribute

1. Fork the project
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Types of Contributions

- 🐛 Bug fixes
- ✨ New features
- 📝 Documentation improvements
- 🎨 Interface enhancements
- ⚡ Performance optimizations
- 🧪 Automated tests

<!-- ## 🔒 Security

If you discover a security vulnerability, please send an email to security@nuva.dev. All security vulnerabilities will be promptly investigated and resolved. -->

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

MIT License

Copyright (c) 2025 Yan Emmanuel

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

---

<p align="center">
  <b>⭐ If this project helped you, consider giving it a star!</b>
</p>

<p align="center">
  Made with ❤️ by the open source community
</p>
