FROM node:20 as build-stage
WORKDIR /private/frontend
COPY ./package*.json ./
RUN npm install
COPY ./ .

RUN mv .env.production .env
RUN npm run build

FROM nginx
COPY --from=build-stage /private/frontend/dist /var/www/private/frontend
