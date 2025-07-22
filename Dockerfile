FROM ubuntu:22.04

RUN apt-get update && \
    apt-get install -y php php-cli python3 python3-pip

WORKDIR /app
COPY . .

CMD ["php", "-S", "0.0.0.0:10000"]
