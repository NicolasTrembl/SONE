<div class="flex h-full flex-col lg:flex-row">
    <div class="flex flex-col w-full lg:w-1/3 p-4 ">
        <div class="flex flex-row justify-between items-center p-4">
            <h2 class="text-xl font-bold">Notes</h2>
            <button id="newNoteBtn" class="bg-primary text-OnPrimary p-2 rounded">+ Nouvelle note</button>
        </div>
        <button id="burgerMenuBtn" class="bg-primary text-OnPrimary p-2 m-4 rounded lg:hidden">
            Afficher liste des notes
        </button>
        
        <div id="notesSidebarOverlay" class="relative inset-0 hidden lg:relative w-full lg:bg-transparent lg:block">
            <div id="notesSidebar" class="w-full h-full bg-surface rounded-md p-4 flex-col flex-grow hidden lg:flex">
                
                <input 
                    type="text" 
                    id="searchNotes" 
                    placeholder="Chercher..." 
                    class="mb-4 p-2 border rounded w-full bg-background text-OnBackground"
                >
                <div id="notesList" class="overflow-y-auto flex-grow"></div>
            </div>
        </div>
    </div>


    <div class="w-full p-4 h-full lg:w-2/3">
        <div id="noteContent" class="bg-surface p-4 rounded shadow h-full">
            <div id="emptyState" class="h-full flex items-center justify-center text-OnSurface">
                Sélectionnez une note ou créez-en une
            </div>
            <div id="noteEditor" class="hidden h-full flex-col">
                <input 
                    type="text" 
                    id="noteTitle" 
                    placeholder="Titre" 
                    class="text-2xl font-bold mb-4 w-full border-b pb-2 rounded bg-background text-OnBackground"
                >
                <div class="flex-grow">
                    <textarea 
                        id="noteText" 
                        class="w-full h-full border rounded p-2 bg-background text-OnBackground"
                        placeholder="Écrivez votre note ici..."
                    ></textarea>
                </div>
                <div class="mt-4 flex justify-between flex-wrap">
                    <div class="flex space-x-2 mb-2 lg:mb-0">
                        <button id="addTaskBtn" class="bg-secondary text-OnSecondary p-2 rounded">+ Tâche</button>
                        <button id="addReminderBtn" class="bg-tertiary text-OnTertiary p-2 rounded">+ Rappel</button>
                    </div>
                    <button id="deleteNoteBtn" class="bg-red-500 text-white p-2 rounded">Supprimer la note</button>
                </div>
                <div id="noteExtras" class="mt-4">
                    <div id="tasksList"></div>
                    <div id="remindersList"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-surface text-OnSurface p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Ajouter une tâche</h2>
        <input 
            type="text" 
            id="taskTitle" 
            placeholder="Titre de la tâche" 
            class="w-full p-2 border rounded mb-4 bg-background text-OnBackground"
        >
        <input 
            type="text" 
            id="taskInput" 
            placeholder="Description de la tâche" 
            class="w-full p-2 border rounded mb-4 bg-background text-OnBackground"
        >
        <div class="flex justify-end space-x-2">
            <button id="cancelTaskBtn" class="bg-secondary text-OnSecondary p-2 rounded">Annuler</button>
            <button id="saveTaskBtn" class="bg-primary text-OnPrimary p-2 rounded">Confirmer</button>
        </div>
    </div>
</div>

<div id="reminderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-surface text-OnSurface p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Ajouter un rappel</h2>
        <input 
            type="text" 
            id="reminderName" 
            placeholder="Titre du rappel" 
            class="w-full p-2 border rounded mb-4 bg-background text-OnBackground"
        >
        <input 
            type="text" 
            id="reminderInput" 
            placeholder="Description du rappel" 
            class="w-full p-2 border rounded mb-4 bg-background text-OnBackground"
        >
        <input 
            type="datetime-local" 
            id="reminderDate" 
            class="w-full p-2 border rounded mb-4 bg-background text-OnBackground"
        >
        <div class="flex justify-end space-x-2">
            <button id="cancelReminderBtn" class="bg-secondary text-OnSecondary p-2 rounded">Annuler</button>
            <button id="saveReminderBtn" class="bg-primary text-OnPrimary p-2 rounded">Confirmer</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuid.min.js"></script>

<script>

$('#burgerMenuBtn').on('click', () => {
    $('#notesSidebarOverlay').toggleClass('hidden');
    $('#notesSidebar').toggleClass('hidden');
});

$('#notesSidebarOverlay').on('click', (e) => {
    if (e.target.id === 'notesSidebarOverlay') {
        $('#notesSidebarOverlay').addClass('hidden');
        $('#notesSidebar').addClass('hidden');
    }
});

class NotesApp {
    constructor() {
        this.notes = JSON.parse(localStorage.getItem('notes')) || [];
        this.todos = JSON.parse(localStorage.getItem('todos')) || [];
        this.reminders = JSON.parse(localStorage.getItem('reminders')) || [];
        this.currentNoteId = null;

        this.initEventListeners();
        this.renderNotesList();
    }

    initEventListeners() {
        $('#newNoteBtn').on('click', () => this.createNewNote());
        $('#searchNotes').on('input', () => this.filterNotes());

        // Auto-save on input
        $('#noteTitle').on('input', () => this.saveNote());
        $('#noteText').on('input', () => this.saveNote());

        // Task modal listeners
        $('#addTaskBtn').on('click', () => this.openTaskModal());
        $('#cancelTaskBtn').on('click', () => this.closeTaskModal());
        $('#saveTaskBtn').on('click', () => this.addTask());

        // Reminder modal listeners
        $('#addReminderBtn').on('click', () => this.openReminderModal());
        $('#cancelReminderBtn').on('click', () => this.closeReminderModal());
        $('#saveReminderBtn').on('click', () => this.addReminder());

        // Delete note
        $('#deleteNoteBtn').on('click', () => this.deleteNote());
    }

    createNewNote() {
        const newNote = {
            id: uuid.v1(),
            title: 'Nouvelle note',
            content: '',
            categories: [],
            tasks: [],
            reminders: [],
            createdAt: new Date().toISOString()
        };

        this.notes.unshift(newNote);
        this.saveNotesToLocalStorage();
        this.renderNotesList();
        this.openNote(newNote.id);
    }


    saveNote() {
        const note = this.notes.find(n => n.id === this.currentNoteId);
        if (!note) return;

        note.title = $('#noteTitle').val();
        note.content = $('#noteText').val();

        this.saveNotesToLocalStorage();
        this.renderNotesList();
    }

    addTask() {
        const taskInput = $('#taskInput').val();
        const taskTitle = $('#taskTitle').val();
        if (!taskTitle || !taskInput) return;

        const note = this.notes.find(n => n.id === this.currentNoteId);


        const task = {
            id: `t${uuid.v1()}`,
            title: taskTitle,
            description: taskInput,
            categories: note.categories || [],
            isDone: false,
            isPinned: false,
        };

        this.todos.push(task);
        this.saveTodosToLocalStorage();

        if (note) {
            note.tasks.push(task.id);
            this.saveNotesToLocalStorage();
            this.openNote(this.currentNoteId);
        }

        $('#taskInput').val('');
        $('#taskTitle').val('');
        // $('#taskCategory').val('');
        this.closeTaskModal();
    }

    toggleTask(taskId) {
        const task = this.todos.find(t => t.id === taskId);
        if (!task) return;

        task.isDone = !task.isDone;
        this.saveTodosToLocalStorage();
        this.openNote(this.currentNoteId);
    }

    addReminder() {
        const reminderName = $('#reminderName').val();
        const reminderDate = $('#reminderDate').val();
        // const reminderCategory = $('#reminderCategory').val();
        if (!reminderName || !reminderDate) return;

        const note = this.notes.find(n => n.id === this.currentNoteId);


        const reminder = {
            id: `r${uuid.v1()}`,
            name: reminderName,
            date: new Date(reminderDate).toISOString(),
            categories: note.categories || []
            // category: reminderCategory
        };

        this.reminders.push(reminder);
        this.saveRemindersToLocalStorage();

        if (note) {
            note.reminders.push(reminder.id);
            this.saveNotesToLocalStorage();
            this.openNote(this.currentNoteId);
        }

        $('#reminderName, #reminderDate').val('');
        this.closeReminderModal();
    }

    deleteNote() {
        this.notes = this.notes.filter(n => n.id !== this.currentNoteId);
        this.currentNoteId = null;

        this.saveNotesToLocalStorage();
        this.renderNotesList();

        $('#emptyState').removeClass('hidden');
        $('#noteEditor').addClass('hidden');
    }

    filterNotes() {
        const searchTerm = $('#searchNotes').val().toLowerCase();
        const filteredNotes = this.notes.filter(note => 
            note.title.toLowerCase().includes(searchTerm) || 
            note.content.toLowerCase().includes(searchTerm)
        );

        const $notesList = $('#notesList');
        $notesList.empty();

        filteredNotes.forEach(note => {
            const $noteItem = $(`
                <div 
                    class="note-item p-3 border-b cursor-pointer hover:bg-OnSurface ${note.id === this.currentNoteId ? 'bg-primary text-OnPrimary' : ''} hover:text-surface"
                    data-note-id="${note.id}"
                >
                    <h3 class="font-bold">${note.title}</h3>
                    <p class="text-sm ">
                        ${note.content.substring(0, 50)}${note.content.length > 50 ? '...' : ''}
                    </p>
                </div>
            `);

            $noteItem.on('click', () => this.openNote(note.id));
            $notesList.append($noteItem);
        });
    }

    saveNotesToLocalStorage() {
        localStorage.setItem('notes', JSON.stringify(this.notes));
    }

    saveTodosToLocalStorage() {
        localStorage.setItem('todos', JSON.stringify(this.todos));
    }

    saveRemindersToLocalStorage() {
        localStorage.setItem('reminders', JSON.stringify(this.reminders));
    }

    openTaskModal() {
        $('#taskModal').removeClass('hidden').addClass('flex');
    }

    closeTaskModal() {
        $('#taskModal').removeClass('flex').addClass('hidden');
    }

    openReminderModal() {
        $('#reminderModal').removeClass('hidden').addClass('flex');
    }

    closeReminderModal() {
        $('#reminderModal').removeClass('flex').addClass('hidden');
    }

    renderNotesList() {
        const $notesList = $('#notesList');
        $notesList.empty();

        this.notes.forEach(note => {
            const $noteItem = $(`
                <div 
                    class="note-item p-3 border-b cursor-pointer hover:bg-gray-100 ${note.id === this.currentNoteId ? 'bg-blue-50' : ''}"
                    data-note-id="${note.id}"
                >
                    <h3 class="font-bold">${note.title}</h3>
                    <p class="text-sm text-gray-500">
                        ${note.content.substring(0, 50)}${note.content.length > 50 ? '...' : ''}
                    </p>
                </div>
            `);
                    
                    // <span class="text-xs text-gray-400">${note.category}</span>

            $noteItem.on('click', () => this.openNote(note.id));
            $notesList.append($noteItem);
        });
    }

    deleteNoteById(noteId) {
        this.notes = this.notes.filter(n => n.id !== noteId);
        if (this.currentNoteId === noteId) {
            this.currentNoteId = null;
            $('#emptyState').removeClass('hidden');
            $('#noteEditor').addClass('hidden');
        }
        this.saveNotesToLocalStorage();
        this.renderNotesList();
    }

    openNote(noteId) {
        const note = this.notes.find(n => n.id === noteId);
        if (!note) return;

        this.currentNoteId = noteId;
        this.renderNotesList();

        $('#emptyState').addClass('hidden');
        $('#noteEditor').removeClass('hidden');

        $('#noteTitle').val(note.title);
        $('#noteText').val(note.content);

        const $tasksList = $('#tasksList').empty();
        note.tasks?.forEach(taskId => {
            const task = this.todos.find(t => t.id === taskId);
            if (task) {
                const $taskItem = $(`
                    <div class="flex items-center mb-2">
                        <input 
                            type="checkbox" 
                            ${task.isDone ? 'checked' : ''} 
                            class="mr-2"
                            onchange="notesApp.toggleTask('${task.id}')"
                        >
                        <span>${task.title}</span>
                        <button class="delete-task-btn text-red-500 text-sm ml-2">Supprimer</button>
                    </div>
                `);

                $taskItem.find('.delete-task-btn').on('click', () => this.deleteTask(task.id));
                $tasksList.append($taskItem);
            }
        });

        const $remindersList = $('#remindersList').empty();
        note.reminders?.forEach(reminderId => {
            const reminder = this.reminders.find(r => r.id === reminderId);
            if (reminder) {
                const $reminderItem = $(`
                    <div class="mb-2">
                        <span class="font-bold">📅 ${new Date(reminder.date).toLocaleString()}</span>
                        <span>${reminder.name}</span>
                        <button class="delete-reminder-btn text-red-500 text-sm ml-2">Supprimer</button>
                    </div>
                `);

                $reminderItem.find('.delete-reminder-btn').on('click', () => this.deleteReminder(reminder.id));
                $remindersList.append($reminderItem);
            }
        });
    }

    deleteTask(taskId) {
        this.todos = this.todos.filter(t => t.id !== taskId);
        const note = this.notes.find(n => n.id === this.currentNoteId);
        if (note) {
            note.tasks = note.tasks.filter(id => id !== taskId);
            this.saveNotesToLocalStorage();
            this.openNote(this.currentNoteId);
        }
        this.saveTodosToLocalStorage();
    }

    deleteReminder(reminderId) {
        this.reminders = this.reminders.filter(r => r.id !== reminderId);
        const note = this.notes.find(n => n.id === this.currentNoteId);
        if (note) {
            note.reminders = note.reminders.filter(id => id !== reminderId);
            this.saveNotesToLocalStorage();
            this.openNote(this.currentNoteId);
        }
        this.saveRemindersToLocalStorage();
    }
}

// Initialize the app
const notesApp = new NotesApp();
window.notesApp = notesApp;
</script>
