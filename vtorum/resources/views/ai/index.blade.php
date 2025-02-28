@extends('layouts.app')

@section('title', 'Чат с ИИ')

@section('content')
    <link rel="icon" href="https://assets.edlin.app/favicon/favicon.ico"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    <div class="chat">
        <!-- Chat -->
        <div class="messages">
            <div class="left message">
                <img src="{{ asset('images/icon_chat.jpg') }}" alt="logo">
                <p>Привет!</p>
            </div>
        </div>
        <!-- End Chat -->

        <!-- Footer -->
        <div class="bottom">
            <form id="chat-form">
                <input type="text" id="message" name="message" placeholder="Введите сообщение..." autocomplete="off">
                <button type="submit">Отправить</button>
            </form>
        </div>
        <!-- End Footer -->
    </div>

    <script>
        $("form").submit(function (event) {
            event.preventDefault();

            //Stop empty messages
            if ($("form #message").val().trim() === '') {
                return;
            }

            //Disable form
            $("form #message").prop('disabled', true);
            $("form button").prop('disabled', true);

            $.ajax({
                url: "/chat",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                },
                data: {
                    "model": "gpt-3.5-turbo",
                    "content": $("form #message").val()
                }
            }).done(function (res) {

                //Populate sending message
                $(".messages > .message").last().after('<div class="right message">' +
                    '<p>' + $("form #message").val() + '</p>' +
                    '<img src="https://assets.edlin.app/images/rossedlin/03/rossedlin-03-100.jpg" alt="Avatar">' +
                    '</div>');

                //Populate receiving message
                $(".messages > .message").last().after('<div class="left message">' +
                    '<img src="https://assets.edlin.app/images/rossedlin/03/rossedlin-03-100.jpg" alt="Avatar">' +
                    '<p>' + res + '</p>' +
                    '</div>');

                //Cleanup
                $("form #message").val('');
                $(document).scrollTop($(document).height());

                //Enable form
                $("form #message").prop('disabled', false);
                $("form button").prop('disabled', false);
            });
        });
    </script>
@endsection
