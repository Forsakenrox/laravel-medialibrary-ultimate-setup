# laravel-medialibrary-ultimate-setup
Most optimized and tuned setup of laravel medialibrary with descriptions

Здесь собраны лучшие практики по использованию библиотеки laravel-medialibrary
Добавлен упрощающий работу Trait, Переопределна модель Media, добавлена возможность сохранять
не привязанные к моделям файлы, правильно настроен диск при использовании storage:link
добавлена возможность сохранять сразу переименованные файлы $model->add($file) или $post->addHashedMedia($file) (прим.)
Настроены пути в хранилище что бы не перегружать и сделать это безопасным для файловой системы сервера вида /a4/be/{{uuid}}/
Добавлен специальный класс очистки мусора в каталогах с файлами и бд php artisan app:clear-unused-medias
