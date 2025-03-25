const endpoint = "http://localhost:3000";

/**
 * Récupère le token d'authentification
 * @param {Object} data - Informations de connexion {email, password, remember}
 * @param {Function} callback - Fonction appelée avec le token en argument
 * @returns {Promise<string|null>} - Token ou null en cas d'échec
 */
async function getToken(data, callback = (token) => {}) {
    const { email, password, remember } = data;

    let encrypted;
    
    if (remember && localStorage.getItem("password")) {
        encrypted = localStorage.getItem("password");
    } else {
        
        encrypted = password;
    }

    if (remember) {
        localStorage.setItem("email", email);
        localStorage.setItem("password", encrypted);
    }

    try {
        const response = await fetch(`${endpoint}/login`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, encryptedPassword: encrypted }),
        });

        const json = await response.json();
        if (response.ok) {
            const token = json["token"];
            localStorage.setItem("token", token);            
            callback(token);
            return token;
        } else {
            console.error("Erreur de connexion :", json.message);
            return null;
        }
    } catch (error) {
        console.error("Erreur réseau :", error);
        return null;
    }
}



/**
 * Récupère les notes de l'utilisateur
 * @param {string} token - Token d'authentification
 * @param {Function} callback - Fonction appelée avec les notes
 * @returns {Promise<Object|null>} - Données des notes ou null en cas d'échec
 */
async function getGrades(token, callback = (grades) => {}) {
    function saveAndCallback(data) {
        try {
            localStorage.setItem("grades", JSON.stringify(data["semesters"]));
            localStorage.setItem("student_info", JSON.stringify(data["student_info"]));
        } finally {
            callback(data);
        }
    }
    return fetchData(`${endpoint}/notes`, token, saveAndCallback);
}

/**
 * Récupère les cours de l'utilisateur
 * @param {string} token - Token d'authentification
 * @param {Function} callback - Fonction appelée avec les cours
 * @returns {Promise<Object|null>} - Données des cours ou null en cas d'échec
 */
async function getCourses(token, callback = (courses) => {}) {
    return fetchData(`${endpoint}/courses`, token, callback);
}

/**
 * Vérifie si un token est valide
 * @param {string} token - Token d'authentification
 * @returns {Promise<boolean>} - True si valide, false sinon
 */
async function checkToken(token) {
    try {
        const response = await fetch(`${endpoint}/check`, {
            method: "GET",
            headers: { "Content-Type": "application/json", "Authorization": `Bearer ${token}` },
        });

        const data = await response.json();
        return data.valid;
    } catch (error) {
        console.error("Erreur lors de la vérification du token :", error);
        return false;
    }
}

/**
 * Récupère les événements du calendrier depuis une URL iCal
 * @param {Function} callback - Fonction appelée avec le calendrier
 * @returns {Promise<string|boolean>} - Données iCal ou false en cas d'échec
 */
async function getCalendar(callback = (calendar) => {}) {
    const localCal = JSON.parse(localStorage.getItem("calendar") || "{}");

    if (localCal.savedAt && localCal.savedAt > Date.now() - 1000 * 60 * 30) {
        console.log("📅 Utilisation du cache du calendrier");
        callback(localCal.data);
        return localCal.data;
    }

    let localOptions = JSON.parse(localStorage.getItem("settings") || "{}");

    if (!localOptions.icalUrl) {
        const url = prompt("Merci d'entrer le lien vers l'ical", "");
        if (!url) return false;

        localOptions.icalUrl = url;
        localStorage.setItem("settings", JSON.stringify(localOptions));
    }

    const url = encodeURIComponent(localOptions.icalUrl);
    try {
        const response = await fetch(`${endpoint}/calendar?url=${url}`);
        if (!response.ok) throw new Error("Erreur API calendrier");

        const text = await response.text();
        localStorage.setItem("calendar", JSON.stringify({ savedAt: Date.now(), data: text }));
        callback(text);
        return text;
    } catch (error) {
        console.error("Erreur lors de la récupération du calendrier :", error);
        return false;
    }
}

/**
 * Fonction générique pour récupérer des données avec un token
 * @param {string} url - URL de l'API
 * @param {string} token - Token d'authentification
 * @param {Function} callback - Fonction appelée avec les données
 * @returns {Promise<Object|null>} - Données JSON ou null en cas d'erreur
 */
async function fetchData(url, token, callback) {
    try {
        const response = await fetch(url, {
            method: "GET",
            headers: { "Content-Type": "application/json", "Authorization": `Bearer ${token}` },
        });

        if (!response.ok) throw new Error(`Erreur ${response.status}`);

        const data = await response.json();


        callback(data);
        return data;
    } catch (error) {
        console.error("Erreur de requête :", error);
        return null;
    }
}

