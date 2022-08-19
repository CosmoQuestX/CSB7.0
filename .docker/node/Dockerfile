FROM node:lts-alpine

COPY .docker/node/entrypoint.sh /bin/entrypoint.sh
RUN chmod +x /bin/entrypoint.sh

RUN npm install sass --location=global

ENTRYPOINT [ "/bin/entrypoint.sh" ]