# enot.io-test

В рамках задания :
    "Имеется система настроек пользователя
    Задача: Реализовать систему подтверждения смены конкретной настройки пользователя по коду из смс / email / telegram с возможностью выбора пользователем другого метода
    Какие вы выделили бы слои, абстракции, таблицы?
    Реализуйте данную схему без интеграции конкретных сервисов / ORM / прочее на уровне интерфейсов / контроллеров"
а так же уточнения в виде : 
    "Т.е. условная реализация на уровне интерфейсов / контроллеров",
попытался реализовать решение.

Сразу прошу прощения,за некоторые недочеты,так как писал все вне приложения,но постарался сохранить подобие фреймворка (Ларавель,в моем случае). Подобную фичу я разрабатываю впервые и по срокам я, несколько не укладывался,потому торопился и где-то код недописал (уверен,что мелкие ошибки, вроде перепутанного имени переменной,либо забытого use, вы найдете). 
Таким образом,мне не удалось реализовать возможность выбора другого метода. Своей целью я посчитал, показать представление о реализации подобного приложения.
В случае,если бы смог реализовать 3 и более методов, прибегнул бы к паттерну проектирования фабрика. А именно, создал был класс настройки, от которого бы наследовал остальные с единым интерфейсом содержащим описание всех методов,а саму реализацию распихал бы по сервисам.

Описание работы приложения:
1. Представлен файл миграции migration_file_users_table.php с необходимыми столбцами. Если настроек больше,то я бы предпочел создать отдельную таблицу(ы) для настроек пользователя
и таким образом оптимизировать работу бд. В случае,если для каждой из настроек есть доп. параметры, было бы лучше для каждой из подобных настроек завести отдельную таблицу и 
обращаться посредством foreign_key.
2. некоторая логика вынесена в отдельные сервисы,такие как : ConfirmationCodeGenerator и EmailService. согласно принципу единственной ответственности.
3. класс сервиса для управления сменой настроек UserSettingsService.php,который будет отвечать за логику смены настроек пользователя. его так же при необходимости
можно разделить на еще несколько классов-сервисов, согласно вышеупомянутому принципу.
4. создана модель User.php и все общение с бд происходит через билдер. (я посчитал,что написание и формирование запросов займет слишком много времени. к тому же средства квери билдера экранируют запросы,что мне тоже не сильно хотелось делать).
внутри модели, так же можно заметить валидацию. это сделано с целью инкапсуляции.
5. в контроллере же происходит вызов необходимых методов и возврат вьюшек(выдуманных,конечно же).
6. предполагается,что вьюшки содержат все необходимое для корректной работы.

