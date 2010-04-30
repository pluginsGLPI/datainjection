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

$LANG["datainjection"]["name"][1] = "Импорт из файла";
$title = $LANG["datainjection"]["name"][1] ;

$LANG["datainjection"]["config"][1]=$title." настройка плагина";

$LANG["datainjection"]["setup"][1] = $title." настройка плагина";
$LANG["datainjection"]["setup"][3] = "Установка плагина $title";
$LANG["datainjection"]["setup"][4] = "Update plugin $title";
$LANG["datainjection"]["setup"][5] = "Удалить плагин $title";
$LANG["datainjection"]["setup"][9] = "Настройка прав";
$LANG["datainjection"]["setup"][10] = "PHP 5 или выше необходим для этого плагина";
$LANG["datainjection"]["setup"][11] = "Инструкции";

$LANG["datainjection"]["presentation"][1] = "Добро пожаловать в мастер импорта";
$LANG["datainjection"]["presentation"][2] = "Этот мастер поможет импортировать CSV файлы в GLPI";
$LANG["datainjection"]["presentation"][3] = "Для начала нажмите кнопку Далее.";

$LANG["datainjection"]["step"][1] = "Шаг 1 : ";
$LANG["datainjection"]["step"][2] = "Шаг 2 : ";
$LANG["datainjection"]["step"][3] = "Шаг 3 : ";
$LANG["datainjection"]["step"][4] = "Шаг 4 : ";
$LANG["datainjection"]["step"][5] = "Шаг 5 : ";
$LANG["datainjection"]["step"][6] = "Шаг 6 : ";

$LANG["datainjection"]["choiceStep"][1] = "Создание или использование модели";
$LANG["datainjection"]["choiceStep"][2] = "Этот шаг позволяет создать, редактировать, удалять или использовать модель.";
$LANG["datainjection"]["choiceStep"][3] = "Создать новую модель";
$LANG["datainjection"]["choiceStep"][4] = "Редактировать сохранённую модель";
$LANG["datainjection"]["choiceStep"][5] = "Удалить сохранённую модель";
$LANG["datainjection"]["choiceStep"][6] = "Использовать сохранённую модель";
$LANG["datainjection"]["choiceStep"][7] = "Комментарий к модели";
$LANG["datainjection"]["choiceStep"][8] = "Нет комментария";
$LANG["datainjection"]["choiceStep"][9] = "Сделайте выбор";
$LANG["datainjection"]["choiceStep"][10] = "Список возможностей зависит от прав.";
$LANG["datainjection"]["choiceStep"][11] = "Необходимо выбрать модель для использования, изменения или удаления.";

$LANG["datainjection"]["model"][1] = "Сбор информации о файле";
$LANG["datainjection"]["model"][2] = "Редактирование модели";
$LANG["datainjection"]["model"][3] = "Выберите тип файла для импорта.";
$LANG["datainjection"]["model"][4] = "Тип импортируемых данных :";
$LANG["datainjection"]["model"][5] = "Тип файла :";
$LANG["datainjection"]["model"][6] = "Создавать новые записи :";
$LANG["datainjection"]["model"][7] = "Обновлять существующие записи :";
$LANG["datainjection"]["model"][8] = "Создавать списки :";
$LANG["datainjection"]["model"][9] = "Название колонок в 1й строке :";
$LANG["datainjection"]["model"][10] = "Разделитель :";
$LANG["datainjection"]["model"][11] = "Заполните поле «Разделитель»!";
$LANG["datainjection"]["model"][12] = "Обновлять существующие записи :";
$LANG["datainjection"]["model"][13] = "Основная информация";
$LANG["datainjection"]["model"][14] = "Настройки ";
$LANG["datainjection"]["model"][15] = "Дополнительные настройки";
$LANG["datainjection"]["model"][16] = "Diffusion :";
$LANG["datainjection"]["model"][17] = "Public";
$LANG["datainjection"]["model"][18] = "Private";
$LANG["datainjection"]["model"][19] = "Дополнительные настройки предоставляют улучшенный контроль процесса импорта. Редактировать их должны только опытные пользователи.";
$LANG["datainjection"]["model"][20] = "Пытаться заполнять сетевые соединения";
$LANG["datainjection"]["model"][21] = "Формат дат";
$LANG["datainjection"]["model"][22] = "dd-mm-yyyy";
$LANG["datainjection"]["model"][23] = "mm-dd-yyyy";
$LANG["datainjection"]["model"][24] = "yyyy-mm-dd";
$LANG["datainjection"]["model"][25] = "1 234.56";
$LANG["datainjection"]["model"][26] = "1 234,56";
$LANG["datainjection"]["model"][27] = "1,234.56";
$LANG["datainjection"]["model"][28] = "Float format";

$LANG["datainjection"]["deleteStep"][1] = "Удаление модели";
$LANG["datainjection"]["deleteStep"][2] = "Внимание! Все настройки этой модели удаляются вместе с ней.";
$LANG["datainjection"]["deleteStep"][3] = "Действительно удалить";
$LANG["datainjection"]["deleteStep"][4] = "модель ?";
$LANG["datainjection"]["deleteStep"][5] = "Модель ";
$LANG["datainjection"]["deleteStep"][6] = "была удалена.";
$LANG["datainjection"]["deleteStep"][7] = "Не удалось удалить модель.";

$LANG["datainjection"]["fileStep"][1] = "Выбор файла для импорта";
$LANG["datainjection"]["fileStep"][2] = "Выберете файл для загрузки на сервер.";
$LANG["datainjection"]["fileStep"][3] = "Выберете файл :";
$LANG["datainjection"]["fileStep"][4] = "The file could not be found";
$LANG["datainjection"]["fileStep"][5] = "Неправильный формат файла";
$LANG["datainjection"]["fileStep"][6] = "Необходимо использовать";
$LANG["datainjection"]["fileStep"][7] = "расширение файла";
$LANG["datainjection"]["fileStep"][8] = "Impossible to copy the file in";
$LANG["datainjection"]["fileStep"][9] = "Кодировка файла :";
$LANG["datainjection"]["fileStep"][10] = "Определить автоматически";
$LANG["datainjection"]["fileStep"][11] = "UTF-8";
$LANG["datainjection"]["fileStep"][12] = "ISO8859-1";

$LANG["datainjection"]["mapping"][1] = "колонок было распознано";
$LANG["datainjection"]["mappingStep"][2] = "Названия столбцов";
$LANG["datainjection"]["mappingStep"][3] = "Таблица";
$LANG["datainjection"]["mappingStep"][4] = "Поле";
$LANG["datainjection"]["mappingStep"][5] = "Обязательно";
$LANG["datainjection"]["mappingStep"][6] = "-------Выберете таблицу-------";
$LANG["datainjection"]["mappingStep"][7] = "-------Выберете поле-------";
$LANG["datainjection"]["mappingStep"][8] = "Необходимо выбрать обязательную колонку!";
$LANG["datainjection"]["mappingStep"][9] = "Этот шаг настраивает связи колонок и полей в базе данных.";
$LANG["datainjection"]["mappingStep"][10] = "Названия столбцов соответствуют колонкам в файле. Список таблиц формируется исходя из выбранного типа импорта данных в шаге 2.";
$LANG["datainjection"]["mappingStep"][11] = "Обязательная колонка должна быть заполнена во всех строках файла. Необходимо назначить не менее одной обязательной колонки.";
$LANG["datainjection"]["mappingStep"][12] = "Количество строк";

$LANG["datainjection"]["info"][1] = "Дополнительные сведения";
$LANG["datainjection"]["info"][2] = "Изменение дополнительной информации";
$LANG["datainjection"]["info"][3] = "Этот шаг позволяет заполнить поля не указанные в импортируемом файле. Данные будут запрошены при использовании модели.";
$LANG["datainjection"]["info"][4] = "Данные будут присвоены всем импортируемым объектам.";
$LANG["datainjection"]["info"][5] = "Обязательная информация";

$LANG["datainjection"]["saveStep"][1] = "Сохранение модели";
$LANG["datainjection"]["saveStep"][2] = "Сохранить модель ?";
$LANG["datainjection"]["saveStep"][3] = "Обновить модель ?";
$LANG["datainjection"]["saveStep"][4] = "Введите название модели :";
$LANG["datainjection"]["saveStep"][5] = "Добавить комментарий :";
$LANG["datainjection"]["saveStep"][6] = "Your model has not been saved, but is still ready to use.";
$LANG["datainjection"]["saveStep"][7] = "Your model has not been updated, but is still ready to use.";
$LANG["datainjection"]["saveStep"][8] = "Модель сохранена и готова для использования.";
$LANG["datainjection"]["saveStep"][9] = "Модель обновлена и готова к использованию.";
$LANG["datainjection"]["saveStep"][10] = "Использовать модель сейчас ?";
$LANG["datainjection"]["saveStep"][11] = "Количество столбцов в файле неправильное!";
$LANG["datainjection"]["saveStep"][12] = "Как минимум одна колонка не совпадает с моделью";
$LANG["datainjection"]["saveStep"][13] = "Сохранить модель для последующего использования.";
$LANG["datainjection"]["saveStep"][14] = "Для использования необходимо будет выбрать модель в 1 шаге.";
$LANG["datainjection"]["saveStep"][15] = "Вы можете добавить комментарий к модели.";
$LANG["datainjection"]["saveStep"][16] = " колонок необходимо ";
$LANG["datainjection"]["saveStep"][17] = " колонок найдено";
$LANG["datainjection"]["saveStep"][18] = " В файле : ";
$LANG["datainjection"]["saveStep"][19] = " В модели : ";

$LANG["datainjection"]["fillInfoStep"][1] = "Внимание! Всё готово для импорта в GLPI. Действительно выполнить импорт данных ?";
$LANG["datainjection"]["fillInfoStep"][2] = "Заполните дополнительную информацию для импорта.";
$LANG["datainjection"]["fillInfoStep"][3] = "* Обязательное поле";
$LANG["datainjection"]["fillInfoStep"][4] = "Не заполнены обязательные поля!";

$LANG["datainjection"]["importStep"][1] = "Импорт файла";
$LANG["datainjection"]["importStep"][2] = "Импорт файла может занять несколько минут в зависимости от настроек и размера файла. Подождите пожалуйста.";
$LANG["datainjection"]["importStep"][3] = "Импорт завершен";

$LANG["datainjection"]["button"][1] = "< Предыдущий";
$LANG["datainjection"]["button"][2] = "Далее >";
$LANG["datainjection"]["button"][3] = "Просмотреть загруженный файл";
$LANG["datainjection"]["button"][4] = "Просмотреть отчет";
$LANG["datainjection"]["button"][5] = "Сохранить ошибочные";
$LANG["datainjection"]["button"][6] = "Закончить";
$LANG["datainjection"]["button"][7] = "Экспортировать в PDF";
$LANG["datainjection"]["button"][8] = "Закрыть";

$LANG["datainjection"]["logStep"][1] = "Результаты импорта";
$LANG["datainjection"]["logStep"][2] = "Вы можете просмотреть или экспортировать отчет используя кнопки ".$LANG["datainjection"]["button"][4]." или «".$LANG["datainjection"]["button"][7]."».";
$LANG["datainjection"]["logStep"][3] = "Импорт данных завершился успешно";
$LANG["datainjection"]["logStep"][4] = "Список импортированных данных";
$LANG["datainjection"]["logStep"][5] = "Список не импортированных данных";
$LANG["datainjection"]["logStep"][6] = "Кнопка «".$LANG["datainjection"]["button"][7]."» позволяет сохранить отчет об выполненном импорте данных на диск.";
$LANG["datainjection"]["logStep"][7] = "Кнопка «".$LANG["datainjection"]["button"][5]."» позволяет сохранить только те данные, которые содержали ошибки и небыли импортированы.";
$LANG["datainjection"]["logStep"][8] = "При импорте были ошибки!";

$LANG["datainjection"]["result"][1] = "Дата в неправильном формате";
$LANG["datainjection"]["result"][2] = "Данные не обработаны";
$LANG["datainjection"]["result"][3] = "Данные уже в базе";
$LANG["datainjection"]["result"][4] = "Не заполнены обязательные данные";
$LANG["datainjection"]["result"][5] = "Создание новых записей отключено";
$LANG["datainjection"]["result"][6] = "Обновление данных отключено";
$LANG["datainjection"]["result"][7] = "Импорт завершился успешно";
$LANG["datainjection"]["result"][8] = "Добавление";
$LANG["datainjection"]["result"][9] = "Обновление";
$LANG["datainjection"]["result"][10] = "Проверка данных";
$LANG["datainjection"]["result"][11] = "Импорт данных";
$LANG["datainjection"]["result"][12] = "Тип импорта";
$LANG["datainjection"]["result"][13] = "ID объекта";
$LANG["datainjection"]["result"][14] = "Строка";
$LANG["datainjection"]["result"][15] = "Data not found";
$LANG["datainjection"]["result"][16] = "Data already used";
$LANG["datainjection"]["result"][17] = "No data to insert";
$LANG["datainjection"]["result"][18] = "Итоги импорта файла";
$LANG["datainjection"]["result"][19] = "More than one value found";
$LANG["datainjection"]["result"][20] = "Object is already linked";
$LANG["datainjection"]["result"][21] = "Import is impossible";

$LANG["datainjection"]["profiles"][1] = "Создать модель";
$LANG["datainjection"]["profiles"][3] = "Использовать модель";
$LANG["datainjection"]["profiles"][4] = "Список настроенных профилей";

$LANG["datainjection"]["mappings"][1] = "Количество портов";
$LANG["datainjection"]["mappings"][2] = "Сетевой порт";
$LANG["datainjection"]["mappings"][3] = "Подключено : имя устройства";
$LANG["datainjection"]["mappings"][4] = "Подключено : номер порта";
$LANG["datainjection"]["mappings"][5] = "Компьютер";
$LANG["datainjection"]["mappings"][6] = "Connected to : port MAC address";
$LANG["datainjection"]["mappings"][7] = "Port unicity criteria";

$LANG["datainjection"]["history"][1] = "from CSV file";
$LANG["datainjection"]["logevent"][1] = "injection of a CSV file.";

$LANG["datainjection"]["entity"][0] = "Parent entity";
?>
