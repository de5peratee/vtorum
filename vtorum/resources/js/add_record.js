document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".create-button").addEventListener("click", function (event) {
        event.preventDefault();

        if (window.location.pathname !== "{{ route('records.create') }}") {
            fetch("{{ route('records.create') }}")
                .then(response => response.text())
                .then(html => {
                    document.querySelector(".main-content").innerHTML = html;
                })
                .catch(error => console.error("Ошибка загрузки:", error));
        }
    });
});
