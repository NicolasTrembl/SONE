<!-- Empty, filled by JS -->
<script>

$(document).ready(function () {
    let layout = null// localStorage.getItem("userLayout");

    if (!layout) {
        layout = JSON.stringify([
            { classes: "overflow-hidden rounded-lg md:col-span-4 md:row-span-3 bg-surface", content: "grades/widgets/split_average" },
            { classes: "overflow-hidden rounded-lg md:col-span-2 md:row-span-3 md:col-start-5 md:row-start-3 bg-surface", content: "grades/widgets/radar" },
            { classes: "overflow-hidden rounded-lg md:col-span-3 md:row-span-2 md:col-start-2 md:row-start-4 bg-surface", content: "" },
            { classes: "overflow-hidden rounded-lg md:col-span-2 md:row-span-2 md:col-start-5 md:row-start-1 bg-surface", content: "" },
            { classes: "overflow-hidden rounded-lg md:row-span-2 md:col-start-1 md:row-start-4 bg-surface", content: "grades/widgets/average" }
        ]);
        localStorage.setItem("userLayout", layout);
    }

    layout = JSON.parse(layout);



    $.post("core/get_layout.php", { layout: JSON.stringify(layout) }, function (html) {
        $("main").html(html);
        $("main").removeClass().addClass("flex-grow md:grid md:grid-cols-6 md:grid-rows-5 md:gap-4 flex flex-col p-6 gap-2 sd:p-2");    
    });
});


</script>