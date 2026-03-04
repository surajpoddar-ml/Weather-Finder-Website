let API_KEY = "ed9df2220a6916b925408e169eecb6d5";
let searcharea = document.getElementById("searcharea");
let searchbutton = document.getElementById("searchbutton");
let displayresult = document.querySelector(".displayresult");

function showLoading() {
    displayresult.innerHTML = `<h1 class="weathercard">Updating weather...</h1>`;
}

async function fetchweather(city, isInitialLoad = false) {
    let storageKey = "weather_" + city.toLowerCase();
    let savedData = localStorage.getItem(storageKey);

    if (savedData) {
        updateDisplay(JSON.parse(savedData));
    }

    if (navigator.onLine) {
        if (!savedData) showLoading();
        
        try {
            const response = await fetch(`connection.php?city=${city}`);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const data = await response.json();

            if (data.cod === 200) {
                localStorage.setItem(storageKey, JSON.stringify(data));
                updateDisplay(data);
            } else if (!savedData) {
                displayresult.innerHTML = `<h1 class="weathercard"><span>${data.message || 'CITY NOT FOUND'}</span></h1>`;
            }
        }
        catch (error) {
            console.error(error);
            if (!savedData) {
                displayresult.innerHTML = `<h1 class="weathercard">Server Error.</h1>`;
            }
        }
    } else {
        if (!savedData) {
            displayresult.innerHTML = `<h1 class="weathercard">No Internet & No Saved Data.</h1>`;
        }
    }
}

function updateDisplay(gotweather) {
    displayresult.innerHTML = `
        <div class="weathercard">
            <div class="citydate">
                <h1>${gotweather.name}</h1>
                <p>${new Date(gotweather.dt * 1000).toDateString()}</p>
                <p>${new Date(gotweather.dt * 1000).toLocaleTimeString()}</p>
                <p>${gotweather.weather[0].main}</p>
            </div>
            <div class="weathericon">
                <img src="https://openweathermap.org/img/wn/${gotweather.weather[0].icon}@4x.png" alt="Icon">
            </div>
            <div class="weatherdetails">
                <p><b><span>Main Weather:</span></b> ${gotweather.weather[0].main}</p>
                <p><b><span>Condition:</span></b> ${gotweather.weather[0].description}</p><br>
                <div class="imagesection">
                    <div class="tempbox">
                        <img src="https://img.icons8.com/color/48/000000/temperature.png" alt="Temp">
                        <p><b>Temperature:</b> ${gotweather.main.temp} °C</p>
                    </div>
                    <div class="pressurebox">
                        <img src="https://img.icons8.com/color/48/000000/pressure.png" alt="Pressure">
                        <p><b>Pressure:</b> ${gotweather.main.pressure} hPa</p>
                    </div>
                </div>
                <div class="imagesection2">
                    <div class="humiditybox">
                        <img src="https://img.icons8.com/color/48/000000/hygrometer.png" alt="Humidity">
                        <p><b>Humidity:</b> ${gotweather.main.humidity} %</p>
                    </div>
                    <div class="windbox">
                        <img src="https://img.icons8.com/color/48/000000/wind.png" alt="Wind">
                        <p><b>Wind:</b> ${gotweather.wind.speed} m/s, ${gotweather.wind.deg}°</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

searchbutton.addEventListener("click", function () {
    let cityweather = searcharea.value.trim();
    if (cityweather) {
        fetchweather(cityweather, false);
    }
});

searcharea.addEventListener("keydown", function (event) {
    if (event.key === "Enter") searchbutton.click();
});

window.addEventListener("load", function() {
    fetchweather("Bristol", true); 
});