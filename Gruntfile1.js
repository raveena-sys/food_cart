!(function () {
    'use strict';
    module.exports = function (grunt) {
         const sass = require('node-sass');
       
        grunt.initConfig({
            pkg: grunt.file.readJSON('package.json'),
            
            sass: {
                options: {
                    implementation: sass,
                    sourceMap: true,
                    outputStyle: 'compressed',                   
                },
                dist: {
                    files: {
                        'public/css/custom.min.css': 'public/scss/main.scss'
                    }
                },
                admin: {
                    files: {
                        'public/backend/css/admin.min.css': 'public/backend/scss/main.scss'
                    },
                }

            },
           
            watch: {
                scripts: {
                    files: ['public/scss/***/*.scss','public/scss/**/*.scss','public/scss/*/*.scss',
                    'public/backend/scss/*/*.scss','public/backend/scss/**/*.scss','public/backend/scss/***/*.scss',
                    'public/backend/sass/*/*.scss',
                    ['Gruntfile.js']],
                    tasks: ['sass']

                }
            }

        });
        // Load the plugin that provides the "uglify" task.

        grunt.loadNpmTasks('grunt-sass');           
        grunt.loadNpmTasks('grunt-contrib-watch');
        // Default task(s).
        grunt.registerTask('default', ['sass','watch']);
    };
})();