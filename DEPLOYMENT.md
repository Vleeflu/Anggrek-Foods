# Deployment Guide for Anggrek Laravel App

## Railway.app Deployment (Recommended)

### Prerequisites
- GitHub account with your code pushed
- Railway.app account (sign up at railway.app)

### Step 1: Create New Project
1. Go to https://railway.app/
2. Click "Start a New Project"
3. Choose "Deploy from GitHub repo"
4. Select your `anggrek` repository

### Step 2: Add MySQL Database
1. In your Railway project, click "+ New"
2. Select "Database" → "MySQL"
3. Railway will automatically create a database

### Step 3: Configure Environment Variables
In Railway project settings, add these variables:

```
APP_NAME=Anggrek
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

**Note**: Railway auto-injects database variables with `${{MySQL.*}}` syntax.

### Step 4: Generate APP_KEY
Run locally:
```bash
php artisan key:generate --show
```
Copy the output and paste it as `APP_KEY` in Railway.

### Step 5: Deploy
1. Railway will automatically deploy on push
2. After first deployment, run migrations:
   - Go to Railway project
   - Click on your service
   - Open "Settings" → "Deploy"
   - Add custom start command (one-time):
     ```
     php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
     ```

### Step 6: Access Your App
- Railway provides a public URL: `https://your-app-name.railway.app`
- Update `APP_URL` in environment variables with this URL

---

## Alternative: Render.com Deployment

### Step 1: Create Web Service
1. Go to https://render.com/
2. Connect your GitHub repository
3. Create new "Web Service"

### Step 2: Configure Build
- **Build Command**: 
  ```
  composer install --no-dev --optimize-autoloader && npm ci && npm run build
  ```
- **Start Command**: 
  ```
  php artisan serve --host=0.0.0.0 --port=$PORT
  ```

### Step 3: Add PostgreSQL Database
1. Create new PostgreSQL database
2. Link to your web service

### Step 4: Environment Variables
Same as Railway, but adjust database variables to PostgreSQL format.

---

## Post-Deployment Checklist

- [ ] Run database migrations
- [ ] Seed initial data if needed
- [ ] Test login functionality
- [ ] Check file upload works (configure storage disk)
- [ ] Set up SSL (usually automatic)
- [ ] Configure custom domain (optional)
- [ ] Set up monitoring/logging

## Troubleshooting

### "No application encryption key has been specified"
- Generate key: `php artisan key:generate --show`
- Add to environment variables as `APP_KEY`

### Database connection errors
- Verify database credentials in environment
- Check database is running
- Ensure migrations have run

### 500 Internal Server Error
- Set `APP_DEBUG=true` temporarily to see error
- Check logs in deployment platform
- Verify all environment variables are set

### Assets not loading
- Run `npm run build` before deployment
- Check `APP_URL` matches your domain
- Verify `VITE_*` variables if using Vite

## Support
For issues, check platform-specific documentation:
- Railway: https://docs.railway.app/
- Render: https://render.com/docs
