@extends('layouts.app')

<!-- Push a style dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div id="todomvc">
                    <section id="todoapp">
                        <header id="header">
                            <h1>todos</h1>
                            <input id="new-todo" placeholder="What needs to be done?" autofocus>
                        </header>
                        <section id="main">
                            <input id="toggle-all" type="checkbox">
                            <label for="toggle-all">Mark all as complete</label>
                            <ul id="todo-list"></ul>
                        </section>
                        <footer id="footer"></footer>
                    </section>
                    <footer id="info">
                        <p>Double-click to edit a todo</p>
                        <p>Created by <a href="http://sindresorhus.com">Sindre Sorhus</a></p>
                        <p>Part of <a href="http://todomvc.com">TodoMVC</a></p>
                    </footer>
                </div>

            </div>
        </div>
    </div>

@endsection

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src=" {{ asset('js/index.js') }}"></script>
@endpush

<script id="todo-template" type="text/x-custom-template">
    <li class="completed" data-id="">
        <div class="view">
            <input class="toggle" type="checkbox" checked>
            <label></label>
            <button class="destroy"></button>
        </div>
        <input class="edit" value="">
    </li>
</script>
<script id="footer-template" type="text/x-custom-template">
    <span id="todo-count"><strong></strong>  left</span>
    <ul id="filters">
        <li>
            <a class="selected" href="#/all">All</a>
        </li>
        <li>
            <a class="selected"href="#/active">Active</a>
        </li>
        <li>
            <a class="selected"href="#/completed">Completed</a>
        </li>
    </ul>
    <button id="clear-completed">Clear completed</button>
</script>


