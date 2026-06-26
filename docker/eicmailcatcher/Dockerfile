FROM debian:12

ENV DEBIAN_FRONTEND=noninteractive
ENV MAIL_DOMAIN=test.local
ENV CATCHALL_USER=inbox
ENV MAIL_USERS="inbox:password"


RUN apt-get update && apt-get install -y \
    postfix \
    dovecot-core dovecot-imapd dovecot-lmtpd \
    apache2 \
    roundcube roundcube-sqlite3 \
    supervisor \
    rsyslog \
    sqlite3 \
    gettext-base \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN groupadd -g 5000 vmail || true \
    && useradd -u 5000 -g vmail -d /var/mail/vhosts -s /usr/sbin/nologin vmail || true \
    && mkdir -p /var/mail/vhosts /etc/postfix/maps /var/log/supervisor /var/lib/roundcube \
    && chown -R vmail:vmail /var/mail/vhosts \
    && chown -R www-data:www-data /var/lib/roundcube

COPY postfix-main.cf /etc/postfix/main.cf.template
COPY dovecot.conf /etc/dovecot/dovecot.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY entrypoint.sh /entrypoint.sh

COPY vhosts/roundcube.conf /etc/apache2/conf-available/roundcube.conf

RUN a2enconf roundcube \
    && echo 'RedirectMatch ^/$ /roundcube/' > /etc/apache2/conf-available/devmail-root.conf \
    && a2enconf devmail-root \
    && rm -f /var/www/html/index.html

RUN chmod +x /entrypoint.sh

EXPOSE 25 143 80

CMD ["/entrypoint.sh"]
