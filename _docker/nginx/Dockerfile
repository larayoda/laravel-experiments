# docker/nginx/Dockerfile
FROM nginx:alpine

# Удаление стандартной конфигурации nginx
RUN rm /etc/nginx/conf.d/default.conf

# Копирование всех файлов конфигурации из локальной директории в контейнер
COPY ./*.conf /etc/nginx/conf.d/
