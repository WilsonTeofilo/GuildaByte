const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const dir = path.join(__dirname, 'public', 'assets');
const files = fs.readdirSync(dir);

files.forEach(file => {
    if (file.endsWith('.png')) {
        const source = path.join(dir, file);
        const dest = path.join(dir, file.replace('.png', '.webp'));
        
        sharp(source)
            .webp({ quality: 80 })
            .toFile(dest)
            .then(info => console.log(`Converted ${file} to WebP`))
            .catch(err => console.error(`Error converting ${file}:`, err));
    }
});
