<div class="relative w-full h-full flex flex-col p-3 rounded-lg min-h-4 max-h-[80vh]">
    <div class="swiper-container flex-grow flex flex-col w-full min-h-4 max-h-[60vh]">
        <div class="swiper-wrapper" id="radarSlides"></div>
        <div class="swiper-pagination !text-primary mt-2"></div>
        <div class="swiper-button-prev w-8 h-8 !text-primary absolute left-2 top-1/2 transform -translate-y-1/2"></div>
        <div class="swiper-button-next w-8 h-8 !text-primary absolute right-2 top-1/2 transform -translate-y-1/2"></div>
    </div>
</div>

<script>
function initRadarCarousel(grades) {
    const radarSlides = document.getElementById("radarSlides");
    radarSlides.innerHTML = ""; 

    
    grades.sort((a, b) => {
        let [aFYear, aSYear] = a.year.split("/");
        let [bFYear, bSYear] = b.year.split("/");
        return aFYear - bFYear || aSYear - bSYear || a.name.includes("Semestre 2");
    });

    grades = grades.reverse();

    grades.forEach((semester, index) => {
        let slideContent = `
            <div class="swiper-slide flex flex-col items-center justify-center text-center p-3 h-full">
                <span class="text-lg font-bold mb-2">Année Scolaire: ${semester.year}</span>
                <hr class="w-full border-t-OnSurface mb-4">
                <span class="text-lg font-semibold">${semester.name}</span>
                <div class="w-full flex justify-center">
                    <canvas id="radarChart-${index}" class="max-w-[90%] max-h-[60vh]"></canvas>
                </div>
            </div>
        `;
        radarSlides.innerHTML += slideContent;
    });

    new Swiper('.swiper-container', {
        loop: true,
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });

    grades.forEach((semester, index) => {
    let ctx = document.getElementById(`radarChart-${index}`).getContext("2d");

    let labels = [];
    let data = [];

    semester.modules.forEach(module => {
        module.courses.forEach(course => {
            if (!labels.includes(course.name)) {
                labels.push((course.name.includes("/") ? course.name.split("/")[0] : course.name));

                let totalWeightedSum = 0;
                let totalWeight = 0;

                course.evaluations.forEach(evaluation => {
                    evaluation.grades.forEach(gradeObj => {
                        totalWeightedSum += gradeObj.grade * gradeObj.weight;
                        totalWeight += gradeObj.weight;
                    });
                });

                let average = totalWeight > 0 ? totalWeightedSum / totalWeight : 0;
                data.push(average);
            }
        });
    });

    let primary = getComputedStyle(document.documentElement).getPropertyValue("--primary-color");
    console.log(primary);

    new Chart(ctx, {
        type: "radar",
        data: {
            labels: labels,
            datasets: [{
                label: `Moyennes - ${semester.name}`,
                data: data,
                backgroundColor: "transparent", 
                borderColor: primary, 
                pointBackgroundColor: primary, 
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    suggestedMin: 0,
                    suggestedMax: 20
                }
            },
            plugins: {
                legend: {
                    display: false 
                }
            }
        }
    });

});


}

document.addEventListener("gradesLoaded", () => {
    let grades = JSON.parse(localStorage.getItem('grades'));
    if (grades) initRadarCarousel(grades);
});
</script>
