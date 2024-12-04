# Usa un'immagine base di Ubuntu
FROM ubuntu:22.04

# Imposta le variabili per non richiedere input interattivo
ENV DEBIAN_FRONTEND=noninteractive TZ=Europe/Rome

# Aggiorna e installa Apache2, PHP e OpenSSL
RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    libapache2-mod-php \
    openssl \
    net-tools \
    iputils-ping \
    && apt-get clean

# Abilita il modulo SSL e il virtual host HTTPS di Apache
RUN a2enmod ssl
RUN a2enmod rewrite

# Genera un certificato SSL autofirmato
RUN mkdir -p /etc/ssl/certs /etc/ssl/private && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/server.key \
    -out /etc/ssl/certs/server.crt \
    -subj "/C=IT/ST=Italy/L=Rome/O=Test Inc/OU=IT/CN=targetsite.local"


# Copia un file di configurazione personalizzato per Apache (virtual host)
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Espone le porte HTTP (80) e HTTPS (443)
EXPOSE 80 443

# Comando di avvio per Apache
CMD ["apachectl", "-D", "FOREGROUND"]
