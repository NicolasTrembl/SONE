<div class="flex gap-4 p-4 bg-gray-100 rounded-xl">
    <div class="flex-1 bg-blue-900 text-white p-4 rounded-xl flex flex-col justify-center">
        <h2 id="course-name" class="text-xl font-semibold">Chargement...</h2>
        <p id="course-info" class="text-sm opacity-80"></p>
        <p id="course-time" class="text-lg font-bold mt-2"></p>
        <p id="course-room" class="text-sm opacity-80"></p>
    </div>

    <div class="flex-2 bg-white p-4 rounded-xl shadow-md max-h-56 overflow-y-auto">
        <h3 class="text-lg font-semibold mb-2">Prochains cours</h3>
        <ul id="course-list" class="space-y-2"></ul>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let courses = parseICal(localStorage.getItem("calendarData"));
        if (courses.length > 0) {
            displayNextCourse(courses);
            displayUpcomingCourses(courses);
        } else {
            document.getElementById("course-name").textContent = "Aucun cours trouvé.";
        }
    });

    function parseICal(data) {
        if (!data) return [];
        
        let events = [];
        let lines = data.split("\n");
        let event = {};
        
        for (let line of lines) {
            if (line.startsWith("BEGIN:VEVENT")) {
                event = {};
            } else if (line.startsWith("END:VEVENT")) {
                events.push(event);
            } else if (line.startsWith("SUMMARY:")) {
                event.name = line.replace("SUMMARY:", "").trim();
            } else if (line.startsWith("DTSTART:")) {
                event.start = parseICalDate(line.replace("DTSTART:", "").trim());
            } else if (line.startsWith("DTEND:")) {
                event.end = parseICalDate(line.replace("DTEND:", "").trim());
            } else if (line.startsWith("DESCRIPTION:")) {
                event.details = line.replace("DESCRIPTION:", "").trim();
            } else if (line.startsWith("LOCATION:")) {
                event.room = line.replace("LOCATION:", "").trim();
            }
        }

        return events.filter(e => e.start > new Date());
    }

    function parseICalDate(dateString) {
        return new Date(
            dateString.substring(0, 4),
            dateString.substring(4, 6) - 1,
            dateString.substring(6, 8),
            dateString.substring(9, 11),
            dateString.substring(11, 13)
        );
    }

    function displayNextCourse(courses) {
        let nextCourse = courses[0];
        document.getElementById("course-name").textContent = nextCourse.name;
        document.getElementById("course-info").textContent = nextCourse.details || "Aucune info disponible";
        document.getElementById("course-time").textContent = `🕒 ${formatDate(nextCourse.start)}`;
        document.getElementById("course-room").textContent = `📍 ${nextCourse.room || "Non spécifiée"}`;
    }

    function displayUpcomingCourses(courses) {
        let list = document.getElementById("course-list");
        list.innerHTML = "";
        
        courses.slice(1, 7).forEach(course => {
            let li = document.createElement("li");
            li.classList.add("border-b", "border-gray-300", "py-2", "text-sm");
            li.innerHTML = `<strong>${formatDate(course.start)}</strong> - ${course.name}`;
            list.appendChild(li);
        });
    }

    function formatDate(date) {
        return `${date.getDate()}/${date.getMonth() + 1} ${date.getHours()}h${String(date.getMinutes()).padStart(2, "0")}`;
    }
</script>
