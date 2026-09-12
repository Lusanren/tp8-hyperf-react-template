# Stage 1: Build React Frontend
FROM node:20-alpine AS builder

WORKDIR /app

COPY react-web/package*.json ./
RUN npm install

COPY react-web/ ./
RUN npm run build

# Stage 2: Nginx Gateway & Static Hosting
FROM nginx:1.24-alpine

COPY --from=builder /app/dist /usr/share/nginx/html
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
