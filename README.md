
# 🌦️ Weather Sansar – Smart Weather Web Application

A full-stack weather web application that fetches real-time weather data for any searched city using the OpenWeather API.  
The application intelligently stores weather data in a MySQL database (2-hour caching system) and browser localStorage for improved performance and offline access.

---

## 🚀 Features

- 🌍 Search weather for any city worldwide
- ⚡ Real-time weather data via OpenWeather API
- 🗄️ MySQL database caching (auto deletes data older than 2 hours)
- 💾 LocalStorage support for offline usage
- 🌐 Online/Offline detection using `navigator.onLine`
- 📱 Fully responsive design (Mobile / Tablet / Desktop)
- 🔄 Smart data source switching (API ↔ Database ↔ Local Storage)
- 🎨 Modern UI with gradients and weather icons

---

## 🧠 Smart Data Flow Architecture

1. Check Local Storage first
2. If online → Request from PHP backend
3. Backend checks MySQL database (valid within 2 hours)
4. If not found → Fetch from OpenWeather API
5. Store data in database
6. Save response to localStorage
7. Display weather dynamically

This ensures:
- Faster repeat searches
- Reduced API calls
- Offline usability
- Better performance

---

## 🛠️ Tech Stack

### Frontend
- HTML5
- CSS3 (Media Queries for responsiveness)
- JavaScript (ES6, Async/Await, Fetch API)

### Backend
- PHP (MySQLi + Prepared Statements)

### Database
- MySQL (Auto-created database & table)

### API
- OpenWeather API

---

## 📂 Project Structure

```

Weather-Sansar/
│
├── index.html
├── weather.js
├── weather.css
├── connection.php
└── README.md

````

---

## ⚙️ Installation & Setup

### 1️⃣ Clone Repository

```bash
git clone https://github.com/your-username/weather-sansar.git
cd weather-sansar
````

### 2️⃣ Setup Local Server

* Install XAMPP / WAMP
* Move project folder into `htdocs`
* Start Apache & MySQL

### 3️⃣ Database

No manual setup required.
Database and table are automatically created when the project runs.

### 4️⃣ Add Your API Key

Inside `connection.php`, replace:

```php
$api_key = "YOUR_API_KEY";
```

Get your API key from:
[https://openweathermap.org/api](https://openweathermap.org/api)

---

## 🔐 Backend Features

* Auto database creation (`weather_db`)
* Auto table creation (`weather_history`)
* Deletes records older than 2 hours
* Prepared statements to prevent SQL injection
* JSON response handling
* CORS support
* Graceful API error handling

---

## 💻 Frontend Features

* Async/Await fetch handling
* Check the frontend https://weathersansarapi.infinityfreeapp.com/
* Try/Catch error handling
* LocalStorage caching per city
* Enter key support for search
* Default city loaded on startup (Bristol)
* Dynamic DOM rendering

---

## 📱 Responsive Design

* Mobile optimized
* Tablet layout supported
* Desktop optimized weather card
* Media queries for smooth adaptability

---

## 🔒 Security Practices

* SQL Injection prevention via prepared statements
* Escaped user input
* Controlled API usage
* Limited cache window (2 hours)

---

## 🚀 Future Enhancements

* User authentication system
* Weather forecast (5-day forecast)
* Search history dashboard
* Dark/Light mode toggle
* Cloud deployment
* Geolocation detection

---

## 👨‍💻 Author

Suraj Poddar
Full-Stack Developer

---

## 📜 License

This project is licensed under the MIT License.

---

## ⭐ Why This Project Is Strong

✔ Full-stack implementation
✔ API + Database caching logic
✔ Offline support
✔ Performance optimization
✔ Responsive UI
✔ Secure backend implementation
✔ Production-ready architecture



