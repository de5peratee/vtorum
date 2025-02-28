document.addEventListener("DOMContentLoaded", function () {
    function setupTagInput(inputId, listId, hiddenInputName) {
        const inputField = document.getElementById(inputId);
        const tagList = document.getElementById(listId);
        const hiddenInput = document.querySelector(`input[name="${hiddenInputName}"]`);
        let tags = [];

        // Загружаем теги, если они уже переданы в скрытые поля
        const existingTags = hiddenInput.value.split(",").filter(tag => tag.trim() !== "");
        tags = [...tags, ...existingTags];
        updateTagList();

        inputField.addEventListener("keypress", function (e) {
            if (e.key === "Enter" && inputField.value.trim() !== "") {
                e.preventDefault();
                let tagText = inputField.value.trim();

                // Проверка, что тег еще не добавлен
                if (!tags.includes(tagText)) {
                    tags.push(tagText);
                    updateTagList();
                }

                inputField.value = ""; // Очищаем поле ввода
            }
        });

        function updateTagList() {
            tagList.innerHTML = ""; // Очищаем список тегов
            tags.forEach(tag => {
                if (tag.trim() !== "") {
                    let tagElement = document.createElement("div");
                    tagElement.classList.add("tag");
                    tagElement.innerHTML = `${tag} <span>&times;</span>`; // Добавляем крестик для удаления

                    tagElement.querySelector("span").addEventListener("click", function () {
                        tags = tags.filter(t => t !== tag); // Удаляем тег из массива
                        updateTagList(); // Обновляем список
                    });

                    tagList.appendChild(tagElement);
                }
            });

            // Обновляем скрытое поле с тегами
            hiddenInput.value = tags.join(",");
        }
    }

    // Инициализация каждого поля ввода
    setupTagInput("tags-input", "tags-list", "tags");
    setupTagInput("subscribers-input", "subscribers-list", "subscribers");
    setupTagInput("attachments-input", "attachments-list", "attachments");
    setupTagInput("relations-input", "relations-list", "relations");
});
