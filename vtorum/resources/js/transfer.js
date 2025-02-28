import Sortable from 'sortablejs';

document.addEventListener("DOMContentLoaded", function () {
    const sortableList = document.getElementById("sortable");

    // Инициализация Sortable
    new Sortable(sortableList, {
        animation: 150, // Плавная анимация
        handle: '.drag-handle', // Перетаскивание только по иконке
        onEnd: function(evt) {
            // Получаем новый порядок элементов
            const orderedIds = [];
            const items = sortableList.querySelectorAll('.sortable-item');

            items.forEach(item => {
                const id = item.getAttribute('data-id');

                if (id) {  // Проверяем, что атрибут существует
                    orderedIds.push(id);
                } else {
                    console.error("Не найден атрибут 'data-id' для элемента:", item);
                }
            });

            // Если список пуст, прекращаем выполнение
            if (orderedIds.length === 0) {
                console.error("Нет элементов для обновления порядка");
                return;
            }

            // Отправляем новый порядок на сервер
            fetch('/update-record-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order: orderedIds })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Ошибка сервера");
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        console.log("Порядок обновлен!");
                    } else {
                        console.error("Ошибка обновления порядка:", data.message);
                    }
                })
                .catch(error => {
                    console.error("Ошибка:", error);
                });
        }
    });
});

