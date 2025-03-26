<div id="onboarding" class="hidden fixed inset-0 bg-background bg-opacity-90 items-center justify-center z-50 p-6">
    <div class="bg-surface text-OnSurface rounded-lg shadow-lg p-8 max-w-lg w-full text-center">
        <h1 class="text-2xl font-bold mb-4">Bienvenue sur SONE</h1>
        <p class="mb-4">Ce site a besoin de vos identifiants ECE pour récupérer vos notes, cours et agenda.</p>
        <p class="mb-6">Vous pouvez choisir de ne pas vous connecter et profiter des outils qui n'ont pas besoin de ces données.</p>
        <div class="flex gap-4 justify-center">
            <button class="rounded bg-primary text-OnPrimary py-2 px-4 hover:bg-primary-dark" onclick="choseToConnect()">Se connecter</button>
            <button class="rounded bg-secondary text-OnSecondary py-2 px-4 hover:bg-secondary-dark" onclick="showCalendar()">Continuer sans se connecter</button>
        </div>
        <div id="calLastOption" class="hidden mt-6 text-center w-full">
            <hr class="border border-OnSurface mb-4">
            <p class="mb-4">Vous pouvez quand même profiter de l'agenda si vous avez le lien vers l'ical</p>
            <input type="text" class="w-full p-2 border border-surface rounded mb-4" placeholder="Lien vers l'ical">
            <div class="flex justify-center gap-4">
                <button class="rounded bg-primary text-OnPrimary py-2 px-4 hover:bg-primary-dark" >Ajouter l'agenda</button>
                <button class="rounded bg-secondary text-OnSecondary py-2 px-4 hover:bg-secondary-dark" onclick="choseNotToConnect()">Continuer sans l'agenda</button>
            </div>
        </div>
    </div>
</div>
<div id="logIn" class="hidden fixed inset-0 bg-background bg-opacity-90 items-center justify-center z-50 p-6">
    <div class="bg-surface text-OnSurface rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold mb-4">Connexion</h2>
        <form>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <div class="relative">
                    <input type="email" id="email" class="w-full p-2 border border-surface rounded" placeholder="Votre email" required>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="text-gray-400"></i>
                    </span>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2">Mot de passe</label>
                <div class="relative">
                    <input type="password" id="password" class="w-full p-2 border border-surface rounded" placeholder="Votre mot de passe" required>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility()">
                        <i id="password-icon" data-lucide="eye" class="text-gray-400"></i>
                    </span>
                </div>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="remember" class="mr-2">
                <label for="remember" class="text-sm">Se souvenir de moi</label>
            </div>
            <div class="flex justify-center gap-4">
                <button type="button" class="rounded bg-primary text-OnPrimary py-2 px-4 hover:bg-primary-dark" onclick="logIn()">Se connecter</button>
                <button type="button" class="rounded bg-secondary text-OnSecondary py-2 px-4 hover:bg-secondary-dark" onclick="onboarding()">Annuler</button>
            </div>
            <div id="loading" class="hidden text-center mt-4">
                <svg class="animate-spin h-5 w-5 text-primary mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-OnSurface mt-2">Connexion en cours...</p>
            </div>
        </form>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = $('#password');
    const passwordIcon = $('#password-icon');
    if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        passwordIcon.removeClass('lucide-eye').addClass('lucide-eye-off');
    } else {
        passwordInput.attr('type', 'password');
        passwordIcon.removeClass('lucide-eye-off').addClass('lucide-eye');
    }
}

function logIn() {
    const email = $('#email').val();
    const password = $('#password').val();
    const remember = $('#remember').is(':checked');

    if (!email || !password) {
        alert("Veuillez remplir tous les champs");
        return;
    }

    $('#loading').removeClass('hidden');

    getToken({email, password, remember}, (token) => {
        $('#loading').addClass('hidden');
        if (token) {
            layout = JSON.stringify([
                { classes: "overflow-hidden rounded-lg md:col-span-4 md:row-span-3                               bg-surface", content: "grades/widgets/split_average" },
                { classes: "overflow-hidden rounded-lg md:col-span-2 md:row-span-3 md:col-start-5 md:row-start-3 bg-surface", content: "grades/widgets/radar" },
                { classes: "overflow-hidden rounded-lg md:col-span-3 md:row-span-2 md:col-start-2 md:row-start-4 bg-surface", content: "" },
                { classes: "overflow-hidden rounded-lg md:col-span-2 md:row-span-2 md:col-start-5 md:row-start-1 bg-surface", content: "" },
                { classes: "overflow-hidden rounded-lg               md:row-span-2 md:col-start-1 md:row-start-4 bg-surface", content: "grades/widgets/average" }
            ]);
            localStorage.setItem("userLayout", layout);
            localStorage.setItem("onboardingData", JSON.stringify({ done: true, logged: true }));
            showLayout();
        } else {
            alert("Identifiants incorrects");
        }
    });
}

function onboarding() {
    $("#logIn").removeClass("flex").addClass("hidden");
    $("#onboarding").removeClass("hidden").addClass("flex");
}

function showCalendar() {
    $("#calLastOption").removeClass("hidden");
    $("#calLastOption");
}

function choseToConnect() {
    $("#onboarding").removeClass("flex").addClass("hidden");
    $("#logIn").removeClass("hidden").addClass("flex");
}

function choseNotToConnect() {
    layout = JSON.stringify([
        { classes: "overflow-hidden rounded-lg md:col-span-4 md:row-span-3                               bg-surface", content: "map/widgets/search" },
        { classes: "overflow-hidden rounded-lg md:col-span-2 md:row-span-5 md:col-start-5                bg-surface", content: "notes/widgets/todo" },
        { classes: "overflow-hidden rounded-lg md:col-span-2 md:row-span-2                md:row-start-4 bg-surface", content: "report_filler/widgets/button" },
        { classes: "overflow-hidden rounded-lg md:col-span-2 md:row-span-2 md:col-start-3 md:row-start-4 bg-surface", content: "notes/widgets/reminder" }
    ]);


    localStorage.setItem("userLayout", layout);
    localStorage.setItem("onboardingData", JSON.stringify({ done: true }));
    showLayout();
}


function showLayout() {
    let layout = localStorage.getItem("userLayout");

    if (!layout) {
        layout = JSON.stringify([
            { classes: "overflow-hidden rounded-lg md:col-span-4 md:row-span-3                               bg-surface", content: "grades/widgets/split_average" },
            { classes: "overflow-hidden rounded-lg md:col-span-2 md:row-span-3 md:col-start-5 md:row-start-3 bg-surface", content: "grades/widgets/radar" },
            { classes: "overflow-hidden rounded-lg md:col-span-3 md:row-span-2 md:col-start-2 md:row-start-4 bg-surface", content: "" },
            { classes: "overflow-hidden rounded-lg md:col-span-2 md:row-span-2 md:col-start-5 md:row-start-1 bg-surface", content: "" },
            { classes: "overflow-hidden rounded-lg               md:row-span-2 md:col-start-1 md:row-start-4 bg-surface", content: "grades/widgets/average" }
        ]);
        localStorage.setItem("userLayout", layout);
    }

    layout = JSON.parse(layout);

        

    $.post("core/get_layout.php", { layout: JSON.stringify(layout) }, function (html) {
        $("main").html(html);
        $("main").removeClass().addClass("flex-grow md:grid md:grid-cols-6 md:grid-rows-5 md:gap-4 flex flex-col p-6 gap-2 sd:p-2 md:pt-16 max-h-screen"); 
        
        writeAllWithCached();


        logAndFetchAll();

   
    });
}

$(document).ready(function () {
    let onboardingData = localStorage.getItem("onboardingData");
    if (!onboardingData) {
        onboarding();
    } else {
        onboardingData = JSON.parse(onboardingData);
        if (onboardingData.length > 0 ||!onboardingData.done) {
            onboarding();
        } else {
            showLayout();
        }
    }
});




</script>