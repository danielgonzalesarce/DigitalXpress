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

console.log('Copying Laravel files...');
filesToCopy.forEach(file => {
  if (fs.existsSync(file)) {
    fs.copyFileSync(file, path.join('dist', file));
  }
});

console.log('Build completed successfully!');

