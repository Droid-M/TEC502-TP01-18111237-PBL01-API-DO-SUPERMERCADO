# Use a imagem oficial do PHP 8.1 com Apache
FROM php:8.1.17-apache

# Habilita o módulo de reescrita do Apache
RUN a2enmod rewrite

# Atualiza a lista de pacotes e instala as dependências necessárias
RUN apt-get update && apt-get install -y \
  libzip-dev \
  unzip \
  && docker-php-ext-install zip pdo pdo_mysql

# Copia os arquivos do seu projeto para o diretório /var/www/html no container
COPY . /var/www/html

# Define o diretório de trabalho como /var/www/html
WORKDIR /var/www/html

# Expõe a porta 80 do container
EXPOSE 80

# Inicializa o Apache quando o container for iniciado
CMD ["apache2-foreground"]
