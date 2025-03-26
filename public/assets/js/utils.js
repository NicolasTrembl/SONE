function logAndFetchAll(email, password) {

    if (!email || !password) {
        email = localStorage.getItem("email");
        password = localStorage.getItem("password");
        if (!email || !password || email === "" || password === "" || email === "undefined" || password === "undefined") {
            console.error("Pas d'email ou de mot de passe enregistré");
            return;
        }
    }

    const gradeLoaded = new Event("gradesLoaded");

    getToken({ email, password, remember: false }).then(token => {
        if (!token) {
            console.error("Erreur d'authentification");
            return;
        }

        getCalendarLink(token);

        getGrades(token, grades => {
            if (!grades) {
                console.error("Erreur de récupération des notes");
                return;
            }

            var names = document.getElementsByClassName("name")
            for (const element of names) {
                element.innerText = grades.student_info.name;
            }
            document.dispatchEvent(gradeLoaded);
        });
    });
}


function writeAllWithCached() {
    const grades = JSON.parse(localStorage.getItem("grades"));
    if (!grades) {
        console.log("Pas de notes en cache");
        return;
    }
    const student_info = JSON.parse(localStorage.getItem("student_info"));
    if (!student_info) {
        console.log("Pas d'info étudiant en cache");
        return;
    }
    var names = document.getElementsByClassName("name")
    for (const element of names) {
        element.innerText = student_info.name;
    }
}

// GRADE related stuff
function getLastSemester() {
    let grades = JSON.parse(localStorage.getItem("grades"));
    if (!grades) {
        console.log("Pas de notes en cache");
        return;
    }
    
    // get the one with the highest pair of year 
    let last = grades[0];
    lastYear = parseInt(last.year.split("/")[1]);
    for (let index = 1; index < grades.length; index++) {
        const element = grades[index];
        if (parseInt(element.year.split("/")[1]) > lastYear) {
            last = element;
            lastYear = parseInt(element.year.split("/")[1]);
        }
        if (parseInt(element.year.split("/")[1]) == lastYear) {
            // Compare the name
            if (element.name.includes("Semestre 2")) {
                last = element;
            } 
        }
    }

    return last;

}

function calculateEvaluationAverage(evaluations) {
    let totalWeightedGrades = 0;
    let totalWeights = 0;

    for (const evaluation of evaluations) {
        for (const grade of evaluation["grades"]) {
            totalWeightedGrades += grade["grade"] * grade["weight"];
            totalWeights += grade["weight"];
        }
    }

    return totalWeights > 0 ? totalWeightedGrades / totalWeights : NaN;
}

function calculateCourseAverage(course) {
    let totalWeightedEvaluations = 0;
    let totalEvaluationWeights = 0;

    for (const evaluation of course["evaluations"]) {
        const evaluationAverage = calculateEvaluationAverage([evaluation]);
        if (!isNaN(evaluationAverage)) {
            totalWeightedEvaluations += evaluationAverage * evaluation["coefficient"];
            totalEvaluationWeights += evaluation["coefficient"];
        }
    }

    return totalEvaluationWeights > 0 ? totalWeightedEvaluations / totalEvaluationWeights : NaN;
}

function calculateModuleAverage(module) {
    let totalWeightedCourses = 0;
    let totalCourseCredits = 0;

    for (const course of module["courses"]) {
        const courseAverage = calculateCourseAverage(course);
        if (!isNaN(courseAverage)) {
            totalWeightedCourses += courseAverage * course["credits"];
            totalCourseCredits += course["credits"];
        }
    }

    return totalCourseCredits > 0 ? totalWeightedCourses / totalCourseCredits : NaN;
}

function calculateSemesterAverage(sem) {
    if (!sem || !sem["modules"]) {
        return NaN;
    }

    let totalWeightedModules = 0;
    let totalModuleCredits = 0;

    for (const module of sem["modules"]) {
        const moduleAverage = calculateModuleAverage(module);
        if (!isNaN(moduleAverage)) {
            totalWeightedModules += moduleAverage * module["credits"];
            totalModuleCredits += module["credits"];
        }
    }

    return totalModuleCredits > 0 ? totalWeightedModules / totalModuleCredits : NaN;
}


