// Init lucide icons & mobile nav
$(function () {
    const $openNav = $("#openNav");
    const $closeNav = $("#closeNav");
    const $mobileNav = $("#mobileNav");
    const $overlay = $("#overlay");

    function openSidebar() {
        $mobileNav.removeClass("translate-x-full");
        $overlay.removeClass("hidden");
    }

    function closeSidebar() {
        $mobileNav.addClass("translate-x-full");
        $overlay.addClass("hidden");
    }

    $openNav.on("click", openSidebar);
    $closeNav.on("click", closeSidebar);
    $overlay.on("click", closeSidebar);

    lucide.createIcons();
});

// Typing effect
$(function() {
    function startTyping(element) {
        const label = $(element).find("span");
        label.css({ opacity: 1, width: "auto" });

        let text = label.data("original");
        label.text("");

        [...text].forEach((char, i) => {
            setTimeout(() => {
                label.text(label.text() + char);
            }, i * 100);
        });

        $(element).css("padding-right", "1rem");
    }

    function resetTyping(element) {
        const label = $(element).find("span");
        label.css({ opacity: 0, width: 0 });
        label.text(label.data("original"));
        $(element).css("padding-right", "0.5rem");
    }

    $(".group").hover(
        function() {
            startTyping(this);
        },
        function() {
            resetTyping(this);
        }
    );
});
// Overflow menu when hovering settings in desktop
$(function() {
    const $settings = $("#settings");
    const $overflow = $("#overflow");

    $settings.hover(
        function() {
            $overflow.removeClass("hidden");
        },
        function() {
            // Do nothing when mouse leaves $settings
        }
    );

    $overflow.hover(
        function() {
            $overflow.removeClass("hidden");
        },
        function() {
            $overflow.addClass("hidden");
        }
    );
});