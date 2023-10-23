# Creating multi-stage build for production
# FROM node:20-slim as build
# RUN apk update && apk add --no-cache build-base gcc autoconf automake zlib-dev libpng-dev vips-dev git > /dev/null 2>&1
# ENV NODE_ENV=production

# FROM node:20-slim AS base
# ENV PNPM_HOME="/pnpm"
# ENV PATH="$PNPM_HOME:$PATH"
# RUN apk update && apk add --no-cache build-base gcc autoconf automake zlib-dev libpng-dev vips-dev git > /dev/null 2>&1
# RUN corepack enable
# ENV NODE_ENV=production
# COPY . /app
# WORKDIR /app

# FROM base AS build-deps
# RUN --mount=type=cache,id=pnpm,target=/pnpm/store pnpm install --prod --frozen-lockfile
# # RUN yarn global add node-gyp
# # RUN yarn config set network-timeout 600000 -g && yarn install --production
# # ENV PATH /opt/node_modules/.bin:$PATH
# # WORKDIR /opt/app
# # COPY . .
# # RUN yarn build

# FROM base AS build
# RUN --mount=type=cache,id=pnpm,target=/pnpm/store pnpm install --frozen-lockfile
# RUN pnpm run build

# # Creating final production image
# FROM base
# COPY --from=build-deps /app/node_modules /app/node_modules
# COPY --from=build /app/.next /app/.next
# RUN apk add --no-cache vips-dev
# WORKDIR /opt/
# COPY --from=build /opt/node_modules ./node_modules
# WORKDIR /opt/app
# COPY --from=build /opt/app ./
# ENV PATH /opt/node_modules/.bin:$PATH

# RUN chown -R node:node /opt/app
# USER node
# EXPOSE 1337
# CMD ["yarn", "start"]

# Creating multi-stage build for production
FROM node:20-alpine as build
RUN apk update && apk add --no-cache build-base gcc autoconf automake zlib-dev libpng-dev vips-dev git > /dev/null 2>&1
ARG NODE_ENV=production
ENV NODE_ENV=${NODE_ENV}

WORKDIR /opt/
COPY package.json package-lock.json ./
RUN npm install -g node-gyp
RUN npm config set fetch-retry-maxtimeout 600000 -g && npm install --only=production
ENV PATH /opt/node_modules/.bin:$PATH
WORKDIR /opt/app
COPY . .
RUN npm run build

# Creating final production image
FROM node:20-alpine
RUN apk add --no-cache vips-dev
ARG NODE_ENV=production
ENV NODE_ENV=${NODE_ENV}
WORKDIR /opt/
COPY --from=build /opt/node_modules ./node_modules
WORKDIR /opt/app
COPY --from=build /opt/app ./
ENV PATH /opt/node_modules/.bin:$PATH

RUN chown -R node:node /opt/app
USER node
EXPOSE 1337
CMD ["npm", "run", "start"]
