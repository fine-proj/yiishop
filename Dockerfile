# Use an official Python runtime as a parent image
FROM ubuntu:16.04

# Set the working directory to /app
WORKDIR /app

# Copy the current directory contents into the container at /app
ADD ./sh /var

# Install any needed packages specified in requirements.txt
#RUN pip install -r requirements.txt

RUN apt-get update && \
    apt-get install -y apache2 && \
    apt-get install -y php7.0 libapache2-mod-php7.0 && \
    apt-get install -y php7.0-gd && \
    apt-get install -y php7.0-xml && \
    apt-get install -y php-mbstring && \
    apt-get install -y php-curl && \
    apt-get install -y php7.0-bcmath && \
    apt-get install -y php7.0-mysql


RUN rm -rf /var/www/html && ln -fs /app/web /var/www/html
RUN sed -i 's/;openssl.cafile=/openssl.cafile=\/app\/cacert.pem/g' /etc/php/7.0/cli/php.ini


RUN echo mysql-server mysql-server/root_password password 123 | debconf-set-selections;
RUN echo mysql-server mysql-server/root_password_again password 123 | debconf-set-selections;

RUN apt-get install -y mysql-server && \
    sed -i 's/bind-address/#bind-address/g' /etc/mysql/mysql.conf.d/mysqld.cnf && \
    service mysql start && \
    mysql -uroot -p123 -e "CREATE DATABASE \`yii2_loc\` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci" && \
    mysql -uroot -p123 -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '123' WITH GRANT OPTION"

#RUN cd /app/NCHL_backend && php ./composer.phar install

# Make port 80 available to the world outside this container
#EXPOSE 80
EXPOSE 80
EXPOSE 3306

# Define environment variable
ENV NAME World

# Run app.py when the container launches
CMD ["/var/run.sh"]
#ENTRYPOINT service mysql start && service apache2 start && /bin/bash