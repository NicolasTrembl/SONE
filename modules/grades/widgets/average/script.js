function writeSemesterAverage() {
    const $averageGrade = document.getElementById('averageGrade');
    const $gaugeFill = document.getElementById('gaugeFill');

    const gradesMap = localStorage.getItem('grades');
    if (gradesMap) {
        const grades = JSON.parse(gradesMap);
        const lastSemester = getLastSemester(grades);
        const average = calculateSemesterAverage(lastSemester);

        console.log(average);
        
        if (average === null || isNaN(average)) {
            $averageGrade.textContent = "N/A";
            $gaugeFill.style.height = "100%";
        } else {
            $averageGrade.textContent = average.toFixed(1);
            $gaugeFill.style.height = `${(average / 20) * 100}%`;
        }
    } else {
        $averageGrade.textContent = "N/A";
        $gaugeFill.style.height = "100%";
    }
}


$(writeSemesterAverage);
$(document).on('gradesLoaded', writeSemesterAverage);
