<div id="reminders" class="flex p-4 w-full">
    <div class="w-1/2 p-4" id="selected-reminder">
        <h3 class="text-lg font-bold mb-2">Rappel selectionné</h3>
        <p><strong>Rappel :</strong> <span id="reminder-name"></span></p>
        <p><strong>Date & Heure :</strong> <span id="reminder-date"></span></p>
        <p><strong>Description :</strong> <span id="reminder-description"></span></p>
        <p><strong>Parent Node:</strong> <span id="reminder-idParentNode"></span></p>
    </div>
    <div class="w-1/2 p-4">
        <h3 class="text-lg font-bold mb-2">Rappels à venir</h3>
        <div id="reminder-list" class="flex flex-col overflow-y-scrool">

        </div>
    </div>
</div>
<div id="no-reminders" class="hidden text-center text-gray-500">
    Aucun rappel à afficher.
</div>

<script>
    $(document).ready(function() {
        const reminders = JSON.parse(localStorage.getItem('reminders')) || [];

        const reminderList = $('#reminder-list');
        const noReminders = $('#no-reminders');
        const remindersDiv = $('#reminders');
        const selectedReminder = {
            name: $('#reminder-name'),
            date: $('#reminder-date'),
            description: $('#reminder-description'),
            idParentNode: $('#reminder-idParentNode')
        };

        if (reminders.length == 0) {
            noReminders.removeClass('hidden');
            remindersDiv.removeClass('flex').addClass('hidden')

        } else {
            reminders.forEach((reminder, index) => {
                const reminderItem = $('<div></div>')
                    .addClass('cursor-pointer mb-2 p-2 rounded')
                    .text(`${reminder.name}`)
                    .on('click', function() {
                        selectedReminder.name.text(reminder.name);
                        selectedReminder.date.text(reminder.date);
                        selectedReminder.description.text(reminder.description);
                        selectedReminder.idParentNode.text(reminder.idParentNode);
                    });
                reminderList.append(reminderItem);
            });
            selectedReminder.name.text(reminders[0].name);
            selectedReminder.date.text(reminders[0].date);
            selectedReminder.description.text(reminders[0].description);
            selectedReminder.idParentNode.text(reminders[0].idParentNode);
        }
    });
</script>
