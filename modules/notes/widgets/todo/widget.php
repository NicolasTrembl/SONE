<div id="todo-widget" class="p-4 rounded">
    <ul id="todo-list" class="list-none">
    </ul>
</div>
<script>
    $(document).ready(function() {
        function renderTodos() {
            const todos = JSON.parse(localStorage.getItem('todos')) || [];
            const sortedTodos = todos.sort((a, b) => b.pinned - a.pinned || a.isDone - b.isDone);
            $('#todo-list').empty();
            sortedTodos.forEach(todo => {
                const todoItem = $(`
                    <li class="flex items-center justify-between p-2 border-b">
                        <div class="flex items-center">
                            <input type="checkbox" class="mr-2" ${todo.isDone ? 'checked' : ''} data-id="${todo.id}">
                            <span class="font-semibold ${todo.isDone ? 'line-through' : ''}">${todo.title}</span>
                        </div>
                        <div class="flex space-x-2">
                            ${todo.categories.map(category => `<span class="px-2 py-1 bg-primary text-OnPrimary rounded-full text-xs">${category}</span>`).join('')}
                        </div>
                    </li>
                `);
                $('#todo-list').append(todoItem);
            });
        }

        $(document).on('change', 'input[type="checkbox"]', function() {
            const id = $(this).data('id');
            const todos = JSON.parse(localStorage.getItem('todos')) || [];
            const todo = todos.find(todo => todo.id === id);
            if (todo) {
                todo.isDone = this.checked;
                localStorage.setItem('todos', JSON.stringify(todos));
                renderTodos();
            }
        });

        renderTodos();
    });
</script>