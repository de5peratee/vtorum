@extends('layouts.app')

@section('title', 'Чат с GPT')

@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <style>
        p {
            padding-top: 0;
            padding-bottom: 0;
            margin: 10px;
            line-height: 1;
        }

        .chat {
            max-width: 1000px;
            margin: 2px auto;
            display: flex;
            flex-direction: column;
            height: 85vh;
            border-radius: 10px;
            overflow: hidden;
        }

        .messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message-container {
            display: flex;
            align-items: flex-end;
        }

        .message {
            padding: 2px 20px;
            border-radius: 15px;
            font-size: 14px;
            max-width: 70%;
            word-wrap: break-word;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .left {
            align-self: flex-start;
        }

        .right {
            background: #e4fdff;
            align-self: flex-end;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 10px;
        }

        .left-container {
            justify-content: flex-start;
        }

        .right-container {
            justify-content: flex-end;
        }

        .bottom {
            display: flex;
            align-items: center;
            padding: 10px;
        }

        .input-container {
            flex-grow: 1;
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 25px;
            padding: 5px 5px;
        }

        .bottom input {
            flex-grow: 1;
            border: none;
            background: transparent;
            padding: 10px;
            font-size: 16px;
            outline: none;
        }

        .send-button {
            width: 40px;
            height: 40px;
            background: black;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        .send-button:hover {
            background: black;
        }

        .send-button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .send-button img {
            width: 18px;
            height: 18px;
        }
    </style>

    <div class="chat">
        <div class="messages" id="messages"></div>
        <div class="bottom">
            <form id="chat-form" class="input-container">
                <input type="text" id="message" name="message" placeholder="Введите сообщение..." autocomplete="off">
                <button type="submit" class="send-button" disabled>
                    <img src="{{ asset('icon/send.svg') }}" alt="Отправить">
                </button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const messageInput = $("#message");
            const sendButton = $(".send-button");

            messageInput.on("input", function () {
                sendButton.prop('disabled', messageInput.val().trim() === '');
            });

            $("#chat-form").submit(function (event) {
                event.preventDefault();

                let userMessage = messageInput.val().trim();
                if (userMessage === '') return;

                messageInput.prop('disabled', true);
                sendButton.prop('disabled', true);

                $("#messages").append(`
                    <div class="message-container right-container">
                        <div class="message right">${marked.parse(userMessage)}</div>
                        <img class="avatar" src="{{ asset('icon/user.svg') }}" alt="User">
                    </div>
                `);
                scrollToBottom();

                $.ajax({
                    url: "/chat",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    data: JSON.stringify({ content: userMessage }),
                    success: function (res) {
                        let replyText = res.response || 'Ошибка ответа от сервера';
                        $("#messages").append(`
                            <div class="message-container left-container">
                                <img class="avatar" src="{{ asset('icon/gpt.svg') }}" alt="GPT">
                                <div class="message left">${marked.parse(replyText)}</div>
                            </div>
                        `);
                        scrollToBottom();
                    },
                    error: function (xhr) {
                        alert("Ошибка сервера: " + xhr.responseText);
                    },
                    complete: function () {
                        messageInput.prop('disabled', false).val('');
                        sendButton.prop('disabled', true);
                    }
                });
            });

            function scrollToBottom() {
                $("#messages").animate({ scrollTop: $("#messages")[0].scrollHeight }, 500);
            }
        });
    </script>
@endsection
