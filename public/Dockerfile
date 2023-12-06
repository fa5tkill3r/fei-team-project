FROM node:20-slim AS base
ENV PNPM_HOME="/pnpm"
ENV PATH="$PNPM_HOME:$PATH"
RUN corepack enable
COPY . /app
WORKDIR /app

FROM base AS deps
RUN --mount=type=cache,id=pnpm,target=/pnpm/store pnpm install --frozen-lockfile

FROM golang:1.21-bookworm AS builder
WORKDIR /app
COPY ./api/* /app
RUN go build

FROM base
COPY --from=deps /app/node_modules /app/node_modules
COPY --from=builder /app /app
CMD [ "./public-api" ]
