# Utilise une image officielle PHP avec le serveur web Apache
FROM php:8.2-apache

# Active les modules de réécriture d'URL d'Apache (utile pour la propreté)
RUN a2enmod rewrite

# Copie tout le contenu de votre projet dans le dossier web du serveur
COPY . /var/www/html/

# Donne les droits d'écriture au serveur pour qu'il puisse écrire dans les logs et la config
RUN chown -R www-data:www-data /var/www/html/logs /var/www/html/config /var/www/html/assets/img
RUN chmod -R 777 /var/www/html/logs /var/www/html/config /var/www/html/assets/img

# Expose le port 80 pour Railway
EXPOSE 80