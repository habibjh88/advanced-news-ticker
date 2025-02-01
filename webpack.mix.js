const mix = require('laravel-mix')
const fs = require('fs-extra')
const path = require('path')
const cliColor = require('cli-color')
const emojic = require('emojic')
const wpPot = require('wp-pot')
const archiver = require('archiver')
const min = mix.inProduction() ? '.min' : ''

const package_path = path.resolve(__dirname)
const package_slug = path.basename(path.resolve(package_path))
const temDirectory = package_path + '/build'

require('@tinypixelco/laravel-mix-wp-blocks')

// Autloading jQuery to make it accessible to all the packages, because, you know, reasons
// You can comment this line if you don't need jQuery.
mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'],
})

if (mix.inProduction()) {
    let languages = path.resolve('languages')
    fs.ensureDir(languages, function (err) {
        if (err) {
            return console.error(err)
        } // if file or folder does not exist
        wpPot({
            package: 'Advanced News Ticker',
            bugReport: '',
            src: '**/*.php',
            domain: 'advanced-news-ticker',
            destFile: 'languages/advanced-news-ticker.pot',
        })
    })
} else {
    mix.webpackConfig({ output: { devtoolModuleFilenameTemplate: '[resource-path]' } }).sourceMaps(false, 'inline-source-map')
}

mix.sass('src/sass/style.scss', `assets/css/style${min}.css`)
mix.sass('src/sass/el-image-selector.scss', `assets/css/el-image-selector.css`)
.options({
    terser: {
        extractComments: false,
    },
    processCssUrls: false,
}).postCss(`assets/css/style.css`, `assets/css/style-rtl${min}.css`, [
    require('rtlcss'),
])
//Admin
mix.scripts('src/js/admin/el-editor.js', `assets/js/admin/el-editor.js`)
//Front-end
mix.scripts('src/js/frontend/scripts.js', `assets/js/scripts.js`)
mix.scripts('src/lib/newsticker.js', `assets/js/lib/newsticker.js`)

//Merge all front-end js file
if (mix.inProduction()) {
    mix.scripts([
        'src/lib/newsticker.js',
        'src/js/frontend/scripts.js',
    ], 'assets/js/frontend/frontend.min.js')
}

// Make package
if (process.env.npm_config_package) {
    mix.then(function () {
        const copyTo = path.resolve(`${temDirectory}/${package_slug}`)
        // Select All file then paste on list
        let includes = [
            'app',
            'assets',
            'languages',
            'src',
            'templates',
            'vendor',
            'index.php',
            'readme.txt',
            'composer.json',
            'package.json',
            'webpack.mix.js',
            `${package_slug}.php`,
        ]
        fs.ensureDir(copyTo, function (err) {
            if (err) {
                return console.error(err)
            }
            includes.map((include) => {
                fs.copy(
                    `${package_path}/${include}`,
                    `${copyTo}/${include}`,
                    function (err) {
                        if (err) {
                            return console.error(err)
                        }
                        console.log(
                            cliColor.white(`=> ${emojic.smiley}  ${include} copied...`),
                        )
                    },
                )
            })
            console.log(
                cliColor.white(`=> ${emojic.whiteCheckMark}  Build directory created`),
            )
        })
    })

    return
}

if (process.env.npm_config_zip) {
    async function getVersion () {
        let data
        try {
            data = await fs.readFile(package_path + `/${package_slug}.php`, 'utf-8')
        } catch (err) {
            console.error(err)
        }
        const lines = data.split(/\r?\n/)
        let version = ''
        for (let i = 0; i < lines.length; i++) {
            if (lines[i].includes('* Version:') || lines[i].includes('*Version:')) {
                version = lines[i].replace('* Version:', '').replace('*Version:', '').trim()
                break
            }
        }
        return version
    }

    const version_get = getVersion()
    version_get.then(function (version) {
        const destinationPath = `${temDirectory}/${package_slug}.${version}.zip`
        const output = fs.createWriteStream(destinationPath)
        const archive = archiver('zip', { zlib: { level: 9 } })
        output.on('close', function () {
            console.log(archive.pointer() + ' total bytes')
            console.log(
                'Archive has been finalized and the output file descriptor has closed.',
            )
            fs.removeSync(`${temDirectory}/${package_slug}`)
        })
        output.on('end', function () {
            console.log('Data has been drained')
        })
        archive.on('error', function (err) {
            throw err
        })

        archive.pipe(output)
        archive.directory(`${temDirectory}/${package_slug}`, package_slug)
        archive.finalize()
    })
}
