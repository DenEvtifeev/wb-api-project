# Laravel API Fetcher and Database Storage

## Описание проекта
Этот проект представляет собой Laravel-приложение, которое подключается к API, извлекает данные в формате JSON с различных эндпоинтов (`stocks`, `incomes`, `orders`, `sales`), преобразует их и сохраняет в базу данных MySQL. Приложение разработано с использованием сервисного подхода для легкости сопровождения и масштабируемости.

---

## Установка

1. Клонируйте репозиторий:
   ```bash
   git clone https://github.com/DenEvtifeev/wb-api-project
   cd <repository_folder>
   
2. .evn файл.
    ```
    для корректной работы проекта скопируйте данные из .env.example в ваш .env файл.
    далее добавьте строки:
    WB_API_BASE_URL="http://89.108.115.241:6969/api"
    API_KEY="E6kUTYrYwZq2tN4QEtyzsbEBk3ie"
    в полях, отвечающих за соединение с базой данных вставить следующие строки:
    DB_CONNECTION=mysql
    DB_HOST=174.138.91.93
    DB_PORT=3306
    DB_DATABASE=gncawbpnnu
    DB_USERNAME=gncawbpnnu
    DB_PASSWORD=dP2SHymPxT
## Комментарии по проекту.
База данных была развернута на хостинге cloudways.com
для выполнения запроса перейдите по адресу /fetchData, после чего запрос будет выполнен
в проекте был использован подход с созданием отдельного сервиса, в котором реализована логика и построение API запросов.
для отправки запросов был использован встроенный в ларавел модуль HTTP, с использованием которого была реализована логика отпраки запросов.
функция fetchEndpointData отвечает за построение get запросов по конкретным эндпоинтам
функции fetch$endpoint, где $endpoint - эндпоинты, по которым получались данные с API.
Для реализации записи полученных данных в базу данных был использован встроенный в ларавель модуль DB
такая структура способствует легкому масштабированию кода, удобсту вызова методов с нужными параметрами
для валидации данных были созданы модели, в которых прописано поле $fillable, в котором указаны обязательные поля, которые должны присутствовать для записи данных в таблицу
Если бы в задании была предоставлена информация по количеству запросов и мутабельности данных, можно было бы реализовать очереди и кеширование
База данных была развернута на хостинге cloudways.com
