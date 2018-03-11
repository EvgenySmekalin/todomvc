jQuery(function ($) {
    'use strict';

    var ENTER_KEY = 13;
    var ESCAPE_KEY = 27;

    var util = {
        uuid: function () {
            /*jshint bitwise:false */
            var i, random;
            var uuid = '';

            for (i = 0; i < 32; i++) {
                random = Math.random() * 16 | 0;
                if (i === 8 || i === 12 || i === 16 || i === 20) {
                    uuid += '-';
                }
                uuid += (i === 12 ? 4 : (i === 16 ? (random & 3 | 8) : random)).toString(16);
            }

            return uuid;
        },
        pluralize: function (count, word) {
            return count === 1 ? word : word + 's';
        },
        store: function (namespace, data) {
            if (arguments.length > 1) {
                return localStorage.setItem(namespace, JSON.stringify(data));
            } else {
                var store = localStorage.getItem(namespace);
                return (store && JSON.parse(store)) || [];
            }
        }
    };

    var App = {
        init: function () {
            this.todos = util.store('todos-jquery');
            this.bindEvents();
            this.render();

            // new Router({
            //     '/:filter': function (filter) {
            //         this.filter = filter;
            //         this.render();
            //     }.bind(this)
            // }).init('/all');
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
            var todos = this.getFilteredTodos();
            this.todoTemplate(todos);
            $('#main').toggle(todos.length > 0);
            $('#toggle-all').prop('checked', this.getActiveTodos().length === 0);
            this.renderFooter();
            $('#new-todo').focus();
            util.store('todos-jquery', this.todos);
        },
        renderFooter: function () {
            var todoCount = this.todos.length;
            var activeTodoCount = this.getActiveTodos().length;

            var activeTodoWord = util.pluralize(activeTodoCount, 'item');
            var completedTodos = todoCount - activeTodoCount;
            var filter = this.filter;

            var template = '<span id="todo-count"><strong>' + activeTodoCount +'</strong> ' + activeTodoWord + ' left</span>\n' +
                           '<ul id="filters">\n' +
                           '    <li><a ' + (filter === "all" ? "class=\"selected\"" : "") + ' href="#/all">All</a></li>\n' +
                           '    <li><a ' + (filter === "active" ? "class=\"selected\"" : "") + 'href="#/active">Active</a></li>\n' +
                           '    <li><a ' + (filter === "completed" ? "class=\"selected\"" : "") + 'href="#/completed">Completed</a></li>\n' +
                           '</ul>\n' + (completedTodos ? '<button id="clear-completed">Clear completed</button>\n' : '');

            $('#footer').toggle(todoCount > 0).html(template);
        },
        todoTemplate: function (todos) {
            var todoList = document.getElementById('todo-list');
            todoList.innerHTML = '';
            for (var i = 0; i < todos.length; i++) {
                todoList.innerHTML += '<li class="' + (todos[i].completed ? "completed" : "") + '" data-id="' + todos[i].id + '">\n' +
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
        getCompletedTodos: function () {
            return this.todos.filter(function (todo) {
                return todo.completed;
            });
        },
        getFilteredTodos: function () {
            if (this.filter === 'active') {
                return this.getActiveTodos();
            }

            if (this.filter === 'completed') {
                return this.getCompletedTodos();
            }

            return this.todos;
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

            this.todos.push({
                id: util.uuid(),
                title: val,
                completed: false
            });

            $input.val('');

            this.render();
        },
        toggle: function (e) {
            var i = this.getIndexFromEl(e.target);
            this.todos[i].completed = !this.todos[i].completed;
            this.render();
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
                this.todos[this.getIndexFromEl(el)].title = val;
            }

            this.render();
        },
        destroy: function (e) {
            this.todos.splice(this.getIndexFromEl(e.target), 1);
            this.render();
        }
    };

    App.init();
});