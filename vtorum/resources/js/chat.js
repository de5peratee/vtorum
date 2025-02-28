$(document).ready(function () {
    $("#chat-form").submit(function (event) {
        event.preventDefault();

        let message = $("#message").val().trim();
        if (message === '') return;

        $("#message").prop('disabled', true);
        $("form button.css").prop('disabled', true);

        $.ajax({
            url: "/chat",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            data: { "content": message }
        }).done(function (res) {
            $(".messages").append('<div class="right message"><p>' + message + '</p></div>');
            $(".messages").append('<div class="left message"><p>' + res.response + '</p></div>');

            $("#message").val('');
            $("form #message").prop('disabled', false);
            $("form button.css").prop('disabled', false);
        }).fail(function () {
            alert('Ошибка при запросе к ИИ');
        });
    });
});
