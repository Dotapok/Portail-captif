# On utilise la version CLI (sans Apache) qui est beaucoup plus légère
FROM php:8.2-cli

# On copie le projet dans un dossier /app
COPY . /app
WORKDIR /app

# On donne les permissions maximales pour que le Mock puisse écrire ses logs
RUN chmod -R 777 logs config assets/img

# Railway attribue un port aléatoire via la variable $PORT.
# On lance le serveur natif PHP pour qu'il écoute sur ce port.
CMD [ "sh", "-c", "php -S 0.0.0.0:$PORT" ]