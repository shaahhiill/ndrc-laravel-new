$envVars = @{
    "DB_CONNECTION" = "mysql"
    "DB_HOST" = "gateway01.us-east-1.prod.aws.tidbcloud.com"
    "DB_PORT" = "4000"
    "DB_DATABASE" = "test"
    "DB_USERNAME" = "3tQCtvUfgTwmE45.root"
    "DB_PASSWORD" = "hSbcU1BY5LGIiGfx"
    "PDO_MYSQL_ATTR_SSL_CA" = "isrgrootx1.pem"
}

foreach ($key in $envVars.Keys) {
    $val = $envVars[$key]
    Write-Host "Adding $key to Vercel production environment..."
    # Remove existing to avoid prompt
    npx vercel env rm $key production -y 2>$null
    # Add new
    $val | npx vercel env add $key production
}

Write-Host "Triggering a new deployment to apply environment variables..."
npx vercel --prod --yes
