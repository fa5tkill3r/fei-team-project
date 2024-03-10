FROM node:20 as build-stage
WORKDIR /private/frontend
COPY ./package*.json ./
RUN npm install
COPY ./ .
RUN npm run build

FROM nginx as production-stage
EXPOSE 3000
COPY --from=build-stage /private/frontend/dist /app