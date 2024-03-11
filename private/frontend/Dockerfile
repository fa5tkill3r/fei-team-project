FROM node:20 as build-stage
WORKDIR /private/frontend
COPY ./package*.json ./
RUN npm install
COPY ./ .
RUN mv .env.example .env

RUN npm run build

FROM nginx as production-stage

COPY --from=build-stage /private/frontend/dist /app