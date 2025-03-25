<div class="relative w-full h-full flex flex-col p-3 rounded-lg min-h-4 max-h-[80vh] md:overflow-hidden ">
    <div class="swiper-container flex-grow flex flex-col w-full min-h-4 max-h-[60vh] md:overflow-hidden">
        <div class="swiper-wrapper" id="moduleSlides"></div>
        <div class="swiper-pagination !text-primary mt-2"></div>
        <div class="swiper-button-prev !text-primary w-8 h-8 absolute left-2 top-1/2 transform -translate-y-1/2"></div>
        <div class="swiper-button-next !text-primary w-8 h-8 absolute right-2 top-1/2 transform -translate-y-1/2"></div>
    </div>
</div>

<script>
function initModuleCarousel(grades) {
    const moduleSlides = document.getElementById("moduleSlides");
    moduleSlides.innerHTML = "";

    grades.sort((a, b) => {
        let [aFYear, aSYear] = a.year.split("/");
        let [bFYear, bSYear] = b.year.split("/");
        return aFYear - bFYear || aSYear - bSYear || a.name.includes("Semestre 2");
    });

    grades = grades.reverse();

    grades.forEach(semester => {
        let years = semester.year.split("/");
        let slideContent = `
            <div class="swiper-slide flex flex-col items-center justify-center text-center p-3 h-full min-h-5vh">
                <span class="text-lg font-semibold mb-2">Année Scolaire: ${years.join(" - ")}</span>
                <hr class="w-full border-t-OnSurface mb-4">
                <span class="text-lg font-bold text-gray-800">${semester.name}</span>
        `;


        // NOT A REALLY RESPONSIVE DESIGN
        // TODO : MAKE IT RESPONSIVE (using css)
        if (window.innerWidth <= 768) {
            let widgetSize = Math.min(window.innerWidth * 0.8, 150);
            console.log(widgetSize);
            let maxRadius = 40; 
            let strokeWidth = 4; 
            let radiusStep = strokeWidth + 3; 

            let totalModules = semester.modules.length;
            let layers = [];
            let texts = [];

            semester.modules.forEach((module, index) => {
                let average = calculateModuleAverage(module);
                let percent = isNaN(average) ? 0 : (average / 20) * 100;
                let strokeColor = isNaN(average) ? "#f43f5e" : (percent > 50 ? "#22c55e" : "#ef4444");

                let radius = maxRadius - index * radiusStep;
                let circumference = 2 * Math.PI * radius;
                let dashOffset = circumference * (1 - percent / 100);

                layers.push(`
                    <circle cx="50" cy="50" r="${radius}" stroke="${strokeColor}" stroke-width="${strokeWidth}" fill="none"
                        stroke-dasharray="${circumference}" stroke-dashoffset="${dashOffset}"
                        stroke-linecap="round" transform="rotate(-90 50 50)">
                    </circle>
                `);
                texts.push(`
                    <path id="textPath${index}" d="M 50,50 m -${radius},0 a ${radius},${radius} 0 1,1 ${radius * 2},0 a ${radius},${radius} 0 1,1 -${radius * 2},0" fill="none"></path>
                    <text font-size="4" font-weight="bold" fill="black">
                        <textPath xlink:href="#textPath${index}" startOffset="50%" text-anchor="middle">
                            ${module.name.replaceAll("MODULE", "").substring(0, (module.name.length > 25) ? 25 : module.name.length)}
                        </textPath>
                    </text>
                `);
            });

            slideContent += `
                <div class="flex items-center justify-center w-full mt-4">
                    <svg class="w-${widgetSize} h-${widgetSize}" viewBox="0 0 100 100">
                        ${layers.join("")}
                        <text x="50" y="55" text-anchor="middle" font-size="14" font-weight="bold" fill="black">
                            ${calculateSemesterAverage(semester).toFixed(1)}
                        </text>
                        ${texts.join("")}
                    </svg>
                </div>
            `;

        } else {
            slideContent += `<div class="flex flex-wrap justify-center gap-4 w-full h-75 mt-4">`;

            semester.modules.forEach(module => {
                let average = calculateModuleAverage(module);
                let percent = isNaN(average) ? 0 : (average / 20) * 100;
                let strokeColor = isNaN(average) ? "#f43f5e" : (percent > 50 ? "#22c55e" : "#ef4444");

                let widgetHeight = document.querySelector(".swiper-container").clientHeight;
                let hFactor = Math.max(60, widgetHeight * 0.2);

                let radius = hFactor / 3;
                let circumference = 2 * Math.PI * radius;
                let dashOffset = circumference * (1 - percent / 100);

                slideContent += `
                    <div class="flex flex-col items-center justify-center h-55 text-center p-3 bg-surface w-40">
                        <div class="flex items-start justify-center w-full h-55"> 
                            <svg class="w-${hFactor} h-${hFactor}" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="${radius}" stroke="#ddd" stroke-width="8" fill="none"></circle>
                                <circle cx="50" cy="50" r="${radius}" stroke="${strokeColor}" stroke-width="8" fill="none"
                                    stroke-dasharray="${circumference}" stroke-dashoffset="${dashOffset}"
                                    stroke-linecap="round" transform="rotate(-90 50 50)">
                                </circle>
                                <text x="50" y="55" text-anchor="middle" font-size="14" font-weight="bold" fill="black">
                                    ${isNaN(average) ? "N/A" : average.toFixed(1)}
                                </text>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-500">${module.name}</span>
                    </div>
                `;
            });

            slideContent += `</div>`;
        }

        slideContent += `</div>`;
        moduleSlides.innerHTML += slideContent;
    });

    new Swiper('.swiper-container', {
        loop: true,
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });
}

/** Fonction pour calculer la moyenne du semestre **/
function calculateSemesterAverage(semester) {
    let sum = 0;
    let count = 0;
    semester.modules.forEach(module => {
        let average = calculateModuleAverage(module);
        if (!isNaN(average)) {
            sum += average;
            count++;
        }
    });
    return count > 0 ? sum / count : 0;
}

document.addEventListener("gradesLoaded", () => {
    let grades = JSON.parse(localStorage.getItem('grades'));
    if (grades) initModuleCarousel(grades);
});

</script>
