const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

const inputDir = path.join(__dirname, 'src/img');
const outputDir = path.join(__dirname, 'html/wp-content/themes/lol_dictionary/assets/img/items');

if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
}

fs.readdirSync(inputDir).forEach(file => {
    const inputFile = path.join(inputDir, file);
    const outputFile = path.join(outputDir, file.replace(/\.(png|jpe?g|gif)$/i, '.webp'));

    sharp(inputFile)
        .resize(32) // 32pxにリサイズ
        .toFormat('webp', { quality: 75 }) // WebP形式に変換し、品質を75に設定
        .toFile(outputFile, (err, info) => {
            if (err) {
                console.error('Error processing file:', inputFile, err);
            } else {
                console.log('Processed file:', outputFile, info);
            }
        });
});