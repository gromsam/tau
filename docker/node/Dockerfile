FROM node:20-alpine

WORKDIR /var/www

RUN npm install -g npm@latest

COPY package.json package-lock.json ./
RUN npm ci --legacy-peer-deps

COPY . .

EXPOSE 5173
