// Добавить значение на экран
function appendValue(value) {
    document.getElementById("display").value += value;
}

// Очистить экран
function clearDisplay() {
    document.getElementById("display").value = "";
}

// Удалить последний символ
function deleteLast() {
    let display = document.getElementById("display").value;
    document.getElementById("display").value = display.slice(0, -1);
}

// Вычислить результат
function calculate() {
    try {
        let result = eval(document.getElementById("display").value);
        document.getElementById("display").value = result;
    } catch (error) {
        alert("Ошибка в вычислении");
        clearDisplay();
    }
}

