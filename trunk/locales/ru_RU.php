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

$DATAINJECTIONLANG["name"][1] = "Импорт из файла";
$title = $DATAINJECTIONLANG["name"][1] ;

$DATAINJECTIONLANG["config"][1]=$title." настройка плагина";

$DATAINJECTIONLANG["setup"][1] = $title." настройка плагина";
$DATAINJECTIONLANG["setup"][3] = "Установка плагина $title";
$DATAINJECTIONLANG["setup"][5] = "Удалить плагин $title";
$DATAINJECTIONLANG["setup"][9] = "Настройка прав";
$DATAINJECTIONLANG["setup"][10] = "PHP 5 или выше необходим для этого плагина";
$DATAINJECTIONLANG["setup"][11] = "Инструкции";

$DATAINJECTIONLANG["presentation"][1] = "Добро пожаловать в мастер импорта";
$DATAINJECTIONLANG["presentation"][2] = "Этот мастер поможет импортировать CSV файлы в GLPI";
$DATAINJECTIONLANG["presentation"][3] = "Для начала нажмите кнопку Далее.";

$DATAINJECTIONLANG["step"][1] = "Шаг 1 : ";
$DATAINJECTIONLANG["step"][2] = "Шаг 2 : ";
$DATAINJECTIONLANG["step"][3] = "Шаг 3 : ";
$DATAINJECTIONLANG["step"][4] = "Шаг 4 : ";
$DATAINJECTIONLANG["step"][5] = "Шаг 5 : ";
$DATAINJECTIONLANG["step"][6] = "Шаг 6 : ";

$DATAINJECTIONLANG["choiceStep"][1] = "Создание или использование модели";
$DATAINJECTIONLANG["choiceStep"][2] = "Этот шаг позволяет создать, редактировать, удалять или использовать модель.";
$DATAINJECTIONLANG["choiceStep"][3] = "Создать новую модель";
$DATAINJECTIONLANG["choiceStep"][4] = "Редактировать сохранённую модель";
$DATAINJECTIONLANG["choiceStep"][5] = "Удалить сохранённую модель";
$DATAINJECTIONLANG["choiceStep"][6] = "Использовать сохранённую модель";
$DATAINJECTIONLANG["choiceStep"][7] = "Комментарий к модели";
$DATAINJECTIONLANG["choiceStep"][8] = "Нет комментария";
$DATAINJECTIONLANG["choiceStep"][9] = "Сделайте выбор";
$DATAINJECTIONLANG["choiceStep"][10] = "Список возможностей зависит от прав.";
$DATAINJECTIONLANG["choiceStep"][11] = "Необходимо выбрать модель для использования, изменения или удаления.";

$DATAINJECTIONLANG["modelStep"][1] = "Сбор информации о файле";
$DATAINJECTIONLANG["modelStep"][2] = "Редактирование модели";
$DATAINJECTIONLANG["modelStep"][3] = "Выберите тип файла для импорта.";
$DATAINJECTIONLANG["modelStep"][4] = "Тип импортируемых данных :";
$DATAINJECTIONLANG["modelStep"][5] = "Тип файла :";
$DATAINJECTIONLANG["modelStep"][6] = "Создавать новые записи :";
$DATAINJECTIONLANG["modelStep"][7] = "Обновлять существующие записи :";
$DATAINJECTIONLANG["modelStep"][8] = "Создавать списки :";
$DATAINJECTIONLANG["modelStep"][9] = "Название колонок в 1й строке :";
$DATAINJECTIONLANG["modelStep"][10] = "Разделитель :";
$DATAINJECTIONLANG["modelStep"][11] = "Заполните поле «Разделитель»!";
$DATAINJECTIONLANG["modelStep"][12] = "Обновлять существующие записи :";
$DATAINJECTIONLANG["modelStep"][13] = "Основная информация";
$DATAINJECTIONLANG["modelStep"][14] = "Настройки ";
$DATAINJECTIONLANG["modelStep"][15] = "Дополнительные настройки";
$DATAINJECTIONLANG["modelStep"][16] = "Diffusion :";
$DATAINJECTIONLANG["modelStep"][17] = "Public";
$DATAINJECTIONLANG["modelStep"][18] = "Private";
$DATAINJECTIONLANG["modelStep"][19] = "Дополнительные настройки предоставляют улучшенный контроль процесса импорта. Редактировать их должны только опытные пользователи.";
$DATAINJECTIONLANG["modelStep"][20] = "Пытаться заполнять сетевые соединения";
$DATAINJECTIONLANG["modelStep"][21] = "Формат дат";
$DATAINJECTIONLANG["modelStep"][22] = "dd-mm-yyyy";
$DATAINJECTIONLANG["modelStep"][23] = "mm-dd-yyyy";
$DATAINJECTIONLANG["modelStep"][24] = "yyyy-mm-dd";

$DATAINJECTIONLANG["deleteStep"][1] = "Удаление модели";
$DATAINJECTIONLANG["deleteStep"][2] = "Внимание! Все настройки этой модели удаляются вместе с ней.";
$DATAINJECTIONLANG["deleteStep"][3] = "Действительно удалить";
$DATAINJECTIONLANG["deleteStep"][4] = "модель ?";
$DATAINJECTIONLANG["deleteStep"][5] = "Модель ";
$DATAINJECTIONLANG["deleteStep"][6] = "была удалена.";
$DATAINJECTIONLANG["deleteStep"][7] = "Не удалось удалить модель.";

$DATAINJECTIONLANG["fileStep"][1] = "Выбор файла для импорта";
$DATAINJECTIONLANG["fileStep"][2] = "Выберете файл для загрузки на сервер.";
$DATAINJECTIONLANG["fileStep"][3] = "Выберете файл :";
$DATAINJECTIONLANG["fileStep"][4] = "The file could not be found";
$DATAINJECTIONLANG["fileStep"][5] = "Неправильный формат файла";
$DATAINJECTIONLANG["fileStep"][6] = "Необходимо использовать";
$DATAINJECTIONLANG["fileStep"][7] = "расширение файла";
$DATAINJECTIONLANG["fileStep"][8] = "Impossible to copy the file in";
$DATAINJECTIONLANG["fileStep"][9] = "Кодировка файла :";
$DATAINJECTIONLANG["fileStep"][10] = "Определить автоматически";
$DATAINJECTIONLANG["fileStep"][11] = "UTF-8";
$DATAINJECTIONLANG["fileStep"][12] = "ISO8859-1";

$DATAINJECTIONLANG["mappingStep"][1] = "колонок было распознано";
$DATAINJECTIONLANG["mappingStep"][2] = "Названия столбцов";
$DATAINJECTIONLANG["mappingStep"][3] = "Таблица";
$DATAINJECTIONLANG["mappingStep"][4] = "Поле";
$DATAINJECTIONLANG["mappingStep"][5] = "Обязательно";
$DATAINJECTIONLANG["mappingStep"][6] = "-------Выберете таблицу-------";
$DATAINJECTIONLANG["mappingStep"][7] = "-------Выберете поле-------";
$DATAINJECTIONLANG["mappingStep"][8] = "Необходимо выбрать обязательную колонку!";
$DATAINJECTIONLANG["mappingStep"][9] = "Этот шаг настраивает связи колонок и полей в базе данных.";
$DATAINJECTIONLANG["mappingStep"][10] = "Названия столбцов соответствуют колонкам в файле. Список таблиц формируется исходя из выбранного типа импорта данных в шаге 2.";
$DATAINJECTIONLANG["mappingStep"][11] = "Обязательная колонка должна быть заполнена во всех строках файла. Необходимо назначить не менее одной обязательной колонки.";
$DATAINJECTIONLANG["mappingStep"][12] = "Количество строк";

$DATAINJECTIONLANG["infoStep"][1] = "Дополнительные сведения";
$DATAINJECTIONLANG["infoStep"][2] = "Изменение дополнительной информации";
$DATAINJECTIONLANG["infoStep"][3] = "Этот шаг позволяет заполнить поля не указанные в импортируемом файле. Данные будут запрошены при использовании модели.";
$DATAINJECTIONLANG["infoStep"][4] = "Данные будут присвоены всем импортируемым объектам.";
$DATAINJECTIONLANG["infoStep"][5] = "Обязательная информация";

$DATAINJECTIONLANG["saveStep"][1] = "Сохранение модели";
$DATAINJECTIONLANG["saveStep"][2] = "Сохранить модель ?";
$DATAINJECTIONLANG["saveStep"][3] = "Обновить модель ?";
$DATAINJECTIONLANG["saveStep"][4] = "Введите название модели :";
$DATAINJECTIONLANG["saveStep"][5] = "Добавить комментарий :";
$DATAINJECTIONLANG["saveStep"][6] = "Your model has not been saved, but is still ready to use.";
$DATAINJECTIONLANG["saveStep"][7] = "Your model has not been updated, but is still ready to use.";
$DATAINJECTIONLANG["saveStep"][8] = "Модель сохранена и готова для использования.";
$DATAINJECTIONLANG["saveStep"][9] = "Модель обновлена и готова к использованию.";
$DATAINJECTIONLANG["saveStep"][10] = "Использовать модель сейчас ?";
$DATAINJECTIONLANG["saveStep"][11] = "Количество столбцов в файле неправильное!";
$DATAINJECTIONLANG["saveStep"][12] = "Как минимум одна колонка не совпадает с моделью";
$DATAINJECTIONLANG["saveStep"][13] = "Сохранить модель для последующего использования.";
$DATAINJECTIONLANG["saveStep"][14] = "Для использования необходимо будет выбрать модель в 1 шаге.";
$DATAINJECTIONLANG["saveStep"][15] = "Вы можете добавить комментарий к модели.";
$DATAINJECTIONLANG["saveStep"][16] = " колонок необходимо ";
$DATAINJECTIONLANG["saveStep"][17] = " колонок найдено";
$DATAINJECTIONLANG["saveStep"][18] = " В файле : ";
$DATAINJECTIONLANG["saveStep"][19] = " В модели : ";

$DATAINJECTIONLANG["fillInfoStep"][1] = "Внимание! Всё готово для импорта в GLPI. Действительно выполнить импорт данных ?";
$DATAINJECTIONLANG["fillInfoStep"][2] = "Заполните дополнительную информацию для импорта.";
$DATAINJECTIONLANG["fillInfoStep"][3] = "* Обязательное поле";
$DATAINJECTIONLANG["fillInfoStep"][4] = "Не заполнены обязательные поля!";

$DATAINJECTIONLANG["importStep"][1] = "Импорт файла";
$DATAINJECTIONLANG["importStep"][2] = "Импорт файла может занять несколько минут в зависимости от настроек и размера файла. Подождите пожалуйста.";
$DATAINJECTIONLANG["importStep"][3] = "Импорт завершен";

$DATAINJECTIONLANG["button"][1] = "< Предыдущий";
$DATAINJECTIONLANG["button"][2] = "Далее >";
$DATAINJECTIONLANG["button"][3] = "Просмотреть загруженный файл";
$DATAINJECTIONLANG["button"][4] = "Просмотреть отчет";
$DATAINJECTIONLANG["button"][5] = "Сохранить ошибочные";
$DATAINJECTIONLANG["button"][6] = "Закончить";
$DATAINJECTIONLANG["button"][7] = "Экспортировать в PDF";
$DATAINJECTIONLANG["button"][8] = "Закрыть";

$DATAINJECTIONLANG["logStep"][1] = "Результаты импорта";
$DATAINJECTIONLANG["logStep"][2] = "Вы можете просмотреть или экспортировать отчет используя кнопки ".$DATAINJECTIONLANG["button"][4]." или «".$DATAINJECTIONLANG["button"][7]."».";
$DATAINJECTIONLANG["logStep"][3] = "Импорт данных завершился успешно";
$DATAINJECTIONLANG["logStep"][4] = "Список импортированных данных";
$DATAINJECTIONLANG["logStep"][5] = "Список не импортированных данных";
$DATAINJECTIONLANG["logStep"][6] = "Кнопка «".$DATAINJECTIONLANG["button"][7]."» позволяет сохранить отчет об выполненном импорте данных на диск.";
$DATAINJECTIONLANG["logStep"][7] = "Кнопка «".$DATAINJECTIONLANG["button"][5]."» позволяет сохранить только те данные, которые содержали ошибки и небыли импортированы.";
$DATAINJECTIONLANG["logStep"][8] = "При импорте были ошибки!";

$DATAINJECTIONLANG["result"][1] = "Дата в неправильном формате";
$DATAINJECTIONLANG["result"][2] = "Данные не обработаны";
$DATAINJECTIONLANG["result"][3] = "Данные уже в базе";
$DATAINJECTIONLANG["result"][4] = "Не заполнены обязательные данные";
$DATAINJECTIONLANG["result"][5] = "Создание новых записей отключено";
$DATAINJECTIONLANG["result"][6] = "Обновление данных отключено";
$DATAINJECTIONLANG["result"][7] = "Импорт завершился успешно";
$DATAINJECTIONLANG["result"][8] = "Добавление";
$DATAINJECTIONLANG["result"][9] = "Обновление";
$DATAINJECTIONLANG["result"][10] = "Проверка данных";
$DATAINJECTIONLANG["result"][11] = "Импорт данных";
$DATAINJECTIONLANG["result"][12] = "Тип импорта";
$DATAINJECTIONLANG["result"][13] = "ID объекта";
$DATAINJECTIONLANG["result"][14] = "Строка";
$DATAINJECTIONLANG["result"][15] = "Data not found";
$DATAINJECTIONLANG["result"][16] = "Data already used";
$DATAINJECTIONLANG["result"][17] = "No data to insert";
$DATAINJECTIONLANG["result"][18] = "Итоги импорта файла";

$DATAINJECTIONLANG["profiles"][1] = "Создать модель";
$DATAINJECTIONLANG["profiles"][3] = "Использовать модель";
$DATAINJECTIONLANG["profiles"][4] = "Список настроенных профилей";

$DATAINJECTIONLANG["mappings"][1] = "Количество портов";
$DATAINJECTIONLANG["mappings"][2] = "Сетевой порт";
$DATAINJECTIONLANG["mappings"][3] = "Подключено : имя устройства";
$DATAINJECTIONLANG["mappings"][4] = "Подключено : номер порта";

$DATAINJECTIONLANG["history"][1] = "from CSV file";
$DATAINJECTIONLANG["logevent"][1] = "injection of a CSV file.";

?>
