FROM node:16-alpine

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package.json yarn.lock ./
RUN yarn install

# Copy application files
COPY . .

# Expose port
EXPOSE 3000

# Start React app
CMD ["yarn", "start"]
