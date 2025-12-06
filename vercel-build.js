const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

function copyRecursiveSync(src, dest) {
  const exists = fs.existsSync(src);
  const stats = exists && fs.statSync(src);
  const isDirectory = exists && stats.isDirectory();
  
  if (isDirectory) {
    if (!fs.existsSync(dest)) {
      fs.mkdirSync(dest, { recursive: true });
    }
    fs.readdirSync(src).forEach(childItemName => {
      copyRecursiveSync(
        path.join(src, childItemName),
        path.join(dest, childItemName)
      );
    });
  } else {
    if (!fs.existsSync(path.dirname(dest))) {
      fs.mkdirSync(path.dirname(dest), { recursive: true });
    }
    fs.copyFileSync(src, dest);
  }
}

console.log('Building assets with Vite...');
execSync('npm run build', { stdio: 'inherit' });

console.log('Creating dist directory...');
if (fs.existsSync('dist')) {
  fs.rmSync('dist', { recursive: true, force: true });
}
fs.mkdirSync('dist', { recursive: true });

// Copiar archivos públicos
console.log('Copying public files...');
if (fs.existsSync('public')) {
  copyRecursiveSync('public', 'dist');
}

// Copiar archivos necesarios de Laravel
const dirsToCopy = ['app', 'bootstrap', 'config', 'database', 'resources', 'routes', 'storage', 'vendor'];
const filesToCopy = ['composer.json', 'composer.lock'];

console.log('Copying Laravel directories...');
dirsToCopy.forEach(dir => {
  if (fs.existsSync(dir)) {
    copyRecursiveSync(dir, path.join('dist', dir));
  }
});

// Asegurar que storage tenga los directorios necesarios
console.log('Setting up storage directories...');
const storageDirs = [
  'dist/storage/app/public',
  'dist/storage/framework/cache',
  'dist/storage/framework/sessions',
  'dist/storage/framework/views',
  'dist/storage/logs'
];
storageDirs.forEach(dir => {
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
  }
});

console.log('Copying Laravel files...');
filesToCopy.forEach(file => {
  if (fs.existsSync(file)) {
    fs.copyFileSync(file, path.join('dist', file));
  }
});

// Ajustar las rutas en index.php para que funcionen en dist
console.log('Adjusting index.php paths...');
const indexPath = path.join('dist', 'index.php');
if (fs.existsSync(indexPath)) {
  let indexContent = fs.readFileSync(indexPath, 'utf8');
  // Cambiar __DIR__.'/../vendor' a __DIR__.'/vendor' porque todo está en dist/
  indexContent = indexContent.replace(
    /__DIR__\.'\/\.\.\/vendor\/autoload\.php'/g,
    "__DIR__ . '/vendor/autoload.php'"
  );
  indexContent = indexContent.replace(
    /__DIR__\.'\/\.\.\/bootstrap\/app\.php'/g,
    "__DIR__ . '/bootstrap/app.php'"
  );
  indexContent = indexContent.replace(
    /__DIR__\.'\/\.\.\/storage\/framework\/maintenance\.php'/g,
    "__DIR__ . '/storage/framework/maintenance.php'"
  );
  fs.writeFileSync(indexPath, indexContent);
}

console.log('Build completed successfully!');

