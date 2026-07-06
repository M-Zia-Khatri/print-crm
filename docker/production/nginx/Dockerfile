FROM node:22-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.ts tsconfig.json components.json ./
RUN npm run build

FROM nginx:alpine
WORKDIR /var/www/html
COPY public /var/www/html/public
COPY --from=frontend /app/public/build /var/www/html/public/build
COPY docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf
