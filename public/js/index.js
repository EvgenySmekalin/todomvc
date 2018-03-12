jQuery(function ($) {
    'use strict';

    var ENTER_KEY = 13;
    var ESCAPE_KEY = 27;
    var ID_START = 0;

    var util = {
        uuid: function () {
            return ID_START++;
        },
        pluralize: function (count, word) {
            return count === 1 ? word : word + 's';
        },
    };

    var App = {
        init: function () {
            this.todos = Object.values(TODOS_LIST);
            this.todoList = document.getElementById('todo-list');
            this.listId = document.getElementById('list-id').value;
            this.filter = this.getFilter();
            this.render();
            this.bindEvents();
            this.setStartId();
        },
        getFilter: function () {
            var parser = document.createElement('a');
            parser.href = window.location.href;
            var pathnameArray = parser.pathname.split("/");
            return pathnameArray[pathnameArray.length - 1];
        },
        setStartId: function () {
            if (this.todos.length > 0) {
                ID_START = this.todos[this.todos.length - 1].id + 1;
            }
        },
        bindEvents: function () {
            $('#new-todo').on('keyup', this.create.bind(this));
            $('#toggle-all').on('change', this.toggleAll.bind(this));
            $('#footer').on('click', '#clear-completed', this.destroyCompleted.bind(this));
            $('#todo-list')
                .on('change', '.toggle', this.toggle.bind(this))
                .on('dblclick', 'label', this.editingMode.bind(this))
                .on('keyup', '.edit', this.editKeyup.bind(this))
                .on('focusout', '.edit', this.update.bind(this))
                .on('click', '.destroy', this.destroy.bind(this));
        },
        render: function () {
            var todos = this.todos;
            this.todoTemplate(todos);
            this.renderFooter();
            $('#main').toggle(todos.length > 0);
            $('#toggle-all').prop('checked', this.getActiveTodos().length === 0);
            $('#new-todo').focus();
        },
        renderFooter: function () {
            var todoCount       = this.todos.length;
            var activeTodoCount = this.getActiveTodos().length;
            var activeTodoWord  = util.pluralize(activeTodoCount, 'item');
            var completedTodos  = todoCount - activeTodoCount;
            var template = '<span id="todo-count"><strong>' + activeTodoCount +'</strong> ' + activeTodoWord + ' left</span>\n' +
                           '<ul id="filters">\n' +
                           '    <li><a ' + (this.filter === "all" ? "class=\"selected\"" : "") + ' href="all">All</a></li>\n' +
                           '    <li><a ' + (this.filter === "active" ? "class=\"selected\"" : "") + 'href="active">Active</a></li>\n' +
                           '    <li><a ' + (this.filter === "completed" ? "class=\"selected\"" : "") + 'href="completed">Completed</a></li>\n' +
                           '</ul>\n' + (completedTodos ? '<button id="clear-completed">Clear completed</button>\n' : '');

            $('#footer').toggle(todoCount > 0).html(template);
        },
        todoTemplate: function (todos) {
            this.todoList.innerHTML = '';
            for (var i = 0; i < todos.length; i++) {
                this.todoList.innerHTML += '<li class="' + (todos[i].completed ? "completed" : "") + '" data-id="' + todos[i].id + '">\n' +
                                      '    <div class="view">\n' +
                                      '        <input class="toggle" type="checkbox" ' + (todos[i].completed ? "checked" : "") + '>\n' +
                                      '        <label>' + todos[i].title + '</label>\n' +
                                      '        <button class="destroy"></button>\n' +
                                      '    </div>\n' +
                                      '    <input class="edit" value="' + todos[i].title + '">\n' +
                                      '</li>';
            }
        },
        toggleAll: function (e) {
            var isChecked = $(e.target).prop('checked');

            this.todos.forEach(function (todo) {
                todo.completed = isChecked;
            });

            this.render();
        },
        getActiveTodos: function () {
            return this.todos.filter(function (todo) {
                return !todo.completed;
            });
        },
        destroyCompleted: function () {
            this.todos = this.getActiveTodos();
            this.filter = 'all';
            this.render();
        },
        // accepts an element from inside the `.item` div and
        // returns the corresponding index in the `todos` array
        getIndexFromEl: function (el) {
            var id = $(el).closest('li').data('id');
            var todos = this.todos;
            var i = todos.length;

            while (i--) {
                if (todos[i].id === id) {
                    return i;
                }
            }
        },
        create: function (e) {
            var $input = $(e.target);
            var val = $input.val().trim();

            if (e.which !== ENTER_KEY || !val) {
                return;
            }

            var newItem = {
                id: util.uuid(),
                title: val,
                completed: false,
                list_id: this.listId
            };

            axios.post('list', newItem
            ).then( () => {
                this.todos.push(newItem);
                $input.val('');
                this.render();
            }).catch( (error) => {
                console.log(error.response.data);
            });
        },
        toggle: function (e) {
            axios.patch('list',
            {
                'id': $(e.target).closest('li').data('id'),
                'completed': 'toggle',
                list_id: this.listId
            }).then( () => {
                var id = this.getIndexFromEl(e.target);
                this.todos[id].completed = !this.todos[id].completed;
                this.render();
            }).catch( (error) => {
                console.log(error.response.data);
            });
        },
        editingMode: function (e) {
            var $input = $(e.target).closest('li').addClass('editing').find('.edit');
            $input.val($input.val()).focus();
        },
        editKeyup: function (e) {
            if (e.which === ENTER_KEY) {
                e.target.blur();
            }

            if (e.which === ESCAPE_KEY) {
                $(e.target).data('abort', true).blur();
            }
        },
        update: function (e) {
            var el = e.target;
            var $el = $(el);
            var val = $el.val().trim();

            if (!val) {
                this.destroy(e);
                return;
            }

            if ($el.data('abort')) {
                $el.data('abort', false);
            } else {
                axios.patch('list', {
                    'id': $(e.target).closest('li').data('id'),
                    'title': val,
                    list_id: this.listId
                }).then( () => {
                    var id = this.getIndexFromEl(el);
                    this.todos[id].title = val;
                    this.render();
                }).catch( (error) => {
                    console.log(error.response.data);
                });
            }
        },
        destroy: function (e) {
            axios({
                method: 'delete',
                url: 'list',
                data: {'id': $(e.target).closest('li').data('id'), list_id: this.listId},
                headers: {'Content-Type': 'application/json'}
            }).then( () => {
                var id= this.getIndexFromEl(e.target);
                this.todos.splice(id, 1);
                this.render();
            }).catch( (error) => {
                console.log(error.response.data);
            });
        }
    };

    App.init();
});