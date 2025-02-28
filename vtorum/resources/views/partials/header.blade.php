<header>
    <div class="logo">
        <img src="{{ asset('images/logo.svg') }}" alt="logo">
    </div>
<nav>
    <ul>
        <li><a href="{{ route('home') }}">Главная</a></li>
        <li><a href="{{ route('notes.index') }}">Заметки</a></li>
{{--        <li><a href="{{ route('tasks.index') }}">Задачи</a></li>--}}
        <li><a href="{{ route('ai.chat') }}">ИИ-функции</a></li>
    </ul>
</nav>

</header>























{{--        @guest--}}
{{--            <a href="{{route('auth')}}" class="primary-btn">--}}
{{--                Войти--}}
{{--                <img src="{{asset('../images/icons/login_icon.svg')}}" alt="icon">--}}
{{--            </a>--}}
{{--        @endguest--}}

{{--        @auth--}}
{{--            <div class="profile-bar">--}}
{{--                <div class="avatar">--}}
{{--                    <div class="notify-count">--}}
{{--                        <span>9+</span>--}}
{{--                    </div>--}}

{{--                    <img src="{{ asset('images/nigga.png') }}" alt="Img">--}}
{{--                </div>--}}

{{--                <div class="profile-info">--}}
{{--                    <p class="text-small">Имя пользователя</p>--}}
{{--                    <div class="whistleblower">--}}
{{--                        <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="Icon">--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        @endauth--}}

    </nav>
</header>