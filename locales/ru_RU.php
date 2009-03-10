<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------

$LANG["data_injection"]["name"][1] = "Импорт из файла";
$title = $LANG["data_injection"]["name"][1] ;

$LANG["data_injection"]["config"][1]=$title." настройка плагина";

$LANG["data_injection"]["setup"][1] = $title." настройка плагина";
$LANG["data_injection"]["setup"][3] = "Установка плагина $title";
$LANG["data_injection"]["setup"][5] = "Удалить плагин $title";
$LANG["data_injection"]["setup"][9] = "Настройка прав";
$LANG["data_injection"]["setup"][10] = "PHP 5 или выше необходим для этого плагина";
$LANG["data_injection"]["setup"][11] = "Инструкции";

$LANG["data_injection"]["presentation"][1] = "Добро пожаловать в мастер импорта";
$LANG["data_injection"]["presentation"][2] = "Этот мастер поможет импортировать CSV файлы в GLPI";
$LANG["data_injection"]["presentation"][3] = "Для начала нажмите кнопку Далее.";

$LANG["data_injection"]["step"][1] = "Шаг 1 : ";
$LANG["data_injection"]["step"][2] = "Шаг 2 : ";
$LANG["data_injection"]["step"][3] = "Шаг 3 : ";
$LANG["data_injection"]["step"][4] = "Шаг 4 : ";
$LANG["data_injection"]["step"][5] = "Шаг 5 : ";
$LANG["data_injection"]["step"][6] = "Шаг 6 : ";

$LANG["data_injection"]["choiceStep"][1] = "Создание или использование модели";
$LANG["data_injection"]["choiceStep"][2] = "Этот шаг позволяет создать, редактировать, удалять или использовать модель.";
$LANG["data_injection"]["choiceStep"][3] = "Создать новую модель";
$LANG["data_injection"]["choiceStep"][4] = "Редактировать сохранённую модель";
$LANG["data_injection"]["choiceStep"][5] = "Удалить сохранённую модель";
$LANG["data_injection"]["choiceStep"][6] = "Использовать сохранённую модель";
$LANG["data_injection"]["choiceStep"][7] = "Комментарий к модели";
$LANG["data_injection"]["choiceStep"][8] = "Нет комментария";
$LANG["data_injection"]["choiceStep"][9] = "Сделайте выбор";
$LANG["data_injection"]["choiceStep"][10] = "Список возможностей зависит от прав.";
$LANG["data_injection"]["choiceStep"][11] = "Необходимо выбрать модель для использования, изменения или удаления.";

$LANG["data_injection"]["modelStep"][1] = "Сбор информации о файле";
$LANG["data_injection"]["modelStep"][2] = "Редактирование модели";
$LANG["data_injection"]["modelStep"][3] = "Выберите тип файла для импорта.";
$LANG["data_injection"]["modelStep"][4] = "Тип импортируемых данных :";
$LANG["data_injection"]["modelStep"][5] = "Тип файла :";
$LANG["data_injection"]["modelStep"][6] = "Создавать новые записи :";
$LANG["data_injection"]["modelStep"][7] = "Обновлять существующие записи :";
$LANG["data_injection"]["modelStep"][8] = "Создавать списки :";
$LANG["data_injection"]["modelStep"][9] = "Название колонок в 1й строке :";
$LANG["data_injection"]["modelStep"][10] = "Разделитель :";
$LANG["data_injection"]["modelStep"][11] = "Заполните поле «Разделитель»!";
$LANG["data_injection"]["modelStep"][12] = "Обновлять существующие записи :";
$LANG["data_injection"]["modelStep"][13] = "Основная информация";
$LANG["data_injection"]["modelStep"][14] = "Настройки ";
$LANG["data_injection"]["modelStep"][15] = "Дополнительные настройки";
$LANG["data_injection"]["modelStep"][16] = "Diffusion :";
$LANG["data_injection"]["modelStep"][17] = "Public";
$LANG["data_injection"]["modelStep"][18] = "Private";
$LANG["data_injection"]["modelStep"][19] = "Дополнительные настройки предоставляют улучшенный контроль процесса импорта. Редактировать их должны только опытные пользователи.";
$LANG["data_injection"]["modelStep"][20] = "Пытаться заполнять сетевые соединения";
$LANG["data_injection"]["modelStep"][21] = "Формат дат";
$LANG["data_injection"]["modelStep"][22] = "dd-mm-yyyy";
$LANG["data_injection"]["modelStep"][23] = "mm-dd-yyyy";
$LANG["data_injection"]["modelStep"][24] = "yyyy-mm-dd";

$LANG["data_injection"]["deleteStep"][1] = "Удаление модели";
$LANG["data_injection"]["deleteStep"][2] = "Внимание! Все настройки этой модели удаляются вместе с ней.";
$LANG["data_injection"]["deleteStep"][3] = "Действительно удалить";
$LANG["data_injection"]["deleteStep"][4] = "модель ?";
$LANG["data_injection"]["deleteStep"][5] = "Модель ";
$LANG["data_injection"]["deleteStep"][6] = "была удалена.";
$LANG["data_injection"]["deleteStep"][7] = "Не удалось удалить модель.";

$LANG["data_injection"]["fileStep"][1] = "Выбор файла для импорта";
$LANG["data_injection"]["fileStep"][2] = "Выберете файл для загрузки на сервер.";
$LANG["data_injection"]["fileStep"][3] = "Выберете файл :";
$LANG["data_injection"]["fileStep"][4] = "The file could not be found";
$LANG["data_injection"]["fileStep"][5] = "Неправильный формат файла";
$LANG["data_injection"]["fileStep"][6] = "Необходимо использовать";
$LANG["data_injection"]["fileStep"][7] = "расширение файла";
$LANG["data_injection"]["fileStep"][8] = "Impossible to copy the file in";
$LANG["data_injection"]["fileStep"][9] = "Кодировка файла :";
$LANG["data_injection"]["fileStep"][10] = "Определить автоматически";
$LANG["data_injection"]["fileStep"][11] = "UTF-8";
$LANG["data_injection"]["fileStep"][12] = "ISO8859-1";

$LANG["data_injection"]["mappingStep"][1] = "колонок было распознано";
$LANG["data_injection"]["mappingStep"][2] = "Названия столбцов";
$LANG["data_injection"]["mappingStep"][3] = "Таблица";
$LANG["data_injection"]["mappingStep"][4] = "Поле";
$LANG["data_injection"]["mappingStep"][5] = "Обязательно";
$LANG["data_injection"]["mappingStep"][6] = "-------Выберете таблицу-------";
$LANG["data_injection"]["mappingStep"][7] = "-------Выберете поле-------";
$LANG["data_injection"]["mappingStep"][8] = "Необходимо выбрать обязательную колонку!";
$LANG["data_injection"]["mappingStep"][9] = "Этот шаг настраивает связи колонок и полей в базе данных.";
$LANG["data_injection"]["mappingStep"][10] = "Названия столбцов соответствуют колонкам в файле. Список таблиц формируется исходя из выбранного типа импорта данных в шаге 2.";
$LANG["data_injection"]["mappingStep"][11] = "Обязательная колонка должна быть заполнена во всех строках файла. Необходимо назначить не менее одной обязательной колонки.";
$LANG["data_injection"]["mappingStep"][12] = "Количество строк";

$LANG["data_injection"]["infoStep"][1] = "Дополнительные сведения";
$LANG["data_injection"]["infoStep"][2] = "Изменение дополнительной информации";
$LANG["data_injection"]["infoStep"][3] = "Этот шаг позволяет заполнить поля не указанные в импортируемом файле. Данные будут запрошены при использовании модели.";
$LANG["data_injection"]["infoStep"][4] = "Данные будут присвоены всем импортируемым объектам.";
$LANG["data_injection"]["infoStep"][5] = "Обязательная информация";

$LANG["data_injection"]["saveStep"][1] = "Сохранение модели";
$LANG["data_injection"]["saveStep"][2] = "Сохранить модель ?";
$LANG["data_injection"]["saveStep"][3] = "Обновить модель ?";
$LANG["data_injection"]["saveStep"][4] = "Введите название модели :";
$LANG["data_injection"]["saveStep"][5] = "Добавить комментарий :";
$LANG["data_injection"]["saveStep"][6] = "Your model has not been saved, but is still ready to use.";
$LANG["data_injection"]["saveStep"][7] = "Your model has not been updated, but is still ready to use.";
$LANG["data_injection"]["saveStep"][8] = "Модель сохранена и готова для использования.";
$LANG["data_injection"]["saveStep"][9] = "Модель обновлена и готова к использованию.";
$LANG["data_injection"]["saveStep"][10] = "Использовать модель сейчас ?";
$LANG["data_injection"]["saveStep"][11] = "Количество столбцов в файле неправильное!";
$LANG["data_injection"]["saveStep"][12] = "Как минимум одна колонка не совпадает с моделью";
$LANG["data_injection"]["saveStep"][13] = "Сохранить модель для последующего использования.";
$LANG["data_injection"]["saveStep"][14] = "Для использования необходимо будет выбрать модель в 1 шаге.";
$LANG["data_injection"]["saveStep"][15] = "Вы можете добавить комментарий к модели.";
$LANG["data_injection"]["saveStep"][16] = " колонок необходимо ";
$LANG["data_injection"]["saveStep"][17] = " колонок найдено";
$LANG["data_injection"]["saveStep"][18] = " В файле : ";
$LANG["data_injection"]["saveStep"][19] = " В модели : ";

$LANG["data_injection"]["fillInfoStep"][1] = "Внимание! Всё готово для импорта в GLPI. Действительно выполнить импорт данных ?";
$LANG["data_injection"]["fillInfoStep"][2] = "Заполните дополнительную информацию для импорта.";
$LANG["data_injection"]["fillInfoStep"][3] = "* Обязательное поле";
$LANG["data_injection"]["fillInfoStep"][4] = "Не заполнены обязательные поля!";

$LANG["data_injection"]["importStep"][1] = "Импорт файла";
$LANG["data_injection"]["importStep"][2] = "Импорт файла может занять несколько минут в зависимости от настроек и размера файла. Подождите пожалуйста.";
$LANG["data_injection"]["importStep"][3] = "Импорт завершен";

$LANG["data_injection"]["button"][1] = "< Предыдущий";
$LANG["data_injection"]["button"][2] = "Далее >";
$LANG["data_injection"]["button"][3] = "Просмотреть загруженный файл";
$LANG["data_injection"]["button"][4] = "Просмотреть отчет";
$LANG["data_injection"]["button"][5] = "Сохранить ошибочные";
$LANG["data_injection"]["button"][6] = "Закончить";
$LANG["data_injection"]["button"][7] = "Экспортировать в PDF";
$LANG["data_injection"]["button"][8] = "Закрыть";

$LANG["data_injection"]["logStep"][1] = "Результаты импорта";
$LANG["data_injection"]["logStep"][2] = "Вы можете просмотреть или экспортировать отчет используя кнопки ".$LANG["data_injection"]["button"][4]." или «".$LANG["data_injection"]["button"][7]."».";
$LANG["data_injection"]["logStep"][3] = "Импорт данных завершился успешно";
$LANG["data_injection"]["logStep"][4] = "Список импортированных данных";
$LANG["data_injection"]["logStep"][5] = "Список не импортированных данных";
$LANG["data_injection"]["logStep"][6] = "Кнопка «".$LANG["data_injection"]["button"][7]."» позволяет сохранить отчет об выполненном импорте данных на диск.";
$LANG["data_injection"]["logStep"][7] = "Кнопка «".$LANG["data_injection"]["button"][5]."» позволяет сохранить только те данные, которые содержали ошибки и небыли импортированы.";
$LANG["data_injection"]["logStep"][8] = "При импорте были ошибки!";

$LANG["data_injection"]["result"][1] = "Дата в неправильном формате";
$LANG["data_injection"]["result"][2] = "Данные не обработаны";
$LANG["data_injection"]["result"][3] = "Данные уже в базе";
$LANG["data_injection"]["result"][4] = "Не заполнены обязательные данные";
$LANG["data_injection"]["result"][5] = "Создание новых записей отключено";
$LANG["data_injection"]["result"][6] = "Обновление данных отключено";
$LANG["data_injection"]["result"][7] = "Импорт завершился успешно";
$LANG["data_injection"]["result"][8] = "Добавление";
$LANG["data_injection"]["result"][9] = "Обновление";
$LANG["data_injection"]["result"][10] = "Проверка данных";
$LANG["data_injection"]["result"][11] = "Импорт данных";
$LANG["data_injection"]["result"][12] = "Тип импорта";
$LANG["data_injection"]["result"][13] = "ID объекта";
$LANG["data_injection"]["result"][14] = "Строка";
$LANG["data_injection"]["result"][15] = "Data not found";
$LANG["data_injection"]["result"][16] = "Data already used";
$LANG["data_injection"]["result"][17] = "No data to insert";
$LANG["data_injection"]["result"][18] = "Итоги импорта файла";

$LANG["data_injection"]["profiles"][1] = "Создать модель";
$LANG["data_injection"]["profiles"][3] = "Использовать модель";
$LANG["data_injection"]["profiles"][4] = "Список настроенных профилей";

$LANG["data_injection"]["mappings"][1] = "Количество портов";
$LANG["data_injection"]["mappings"][2] = "Сетевой порт";
$LANG["data_injection"]["mappings"][3] = "Подключено : имя устройства";
$LANG["data_injection"]["mappings"][4] = "Подключено : номер порта";
$LANG["data_injection"]["mappings"][5] = "Компьютер";

$LANG["data_injection"]["history"][1] = "from CSV file";
$LANG["data_injection"]["logevent"][1] = "injection of a CSV file.";

$LANG["data_injection"]["entity"][0] = "Parent entity";
?>
