### Components
Всё, что может переиспользоваться

### Pages
Всё, на что ссылается router


### Структура директорий
Избегаем тавтологии.
Неправильно:
```
Clients/ClientsIndex.vue
```
Правильно:
```
Client/Index.vue
...
import ClientIndex from '@/components/Client/Index'
```
