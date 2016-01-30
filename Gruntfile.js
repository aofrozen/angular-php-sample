module.exports = function(grunt) {

    // Configuration goes here
    grunt.initConfig({
        copy:{
            main:{
                files:[
                    {expand: true,cwd: 'bower_components/bootstrap/dist/', src:'**', dest:'public/bootstrap/'}]
            }
        },
        watch:{
            files:['public/js/app/components/**/*.js', 'public/js/app/shared/**/*.js', 'public/js/app/app.js'],
            tasks:['concat']
        },
        browserify:{
            "options" :{
                "transform": [ "browserify-shim" ]
            },
            everlistjs:{
                src:'public/js/app/app.js',
                dest:'public/js/app/app-bundle.js'
            }
         },
        uglify:{
            js:{
                src:['public/js/app/app-bundle.js'],
                dest:'public/js/app/app.min.js'
            }
        },
       /* clean:{
            js:['public/js/app/app-bundle.js']
        },*/
        cssmin:{
            socialSamplecss:{
                files:{
                    'public/css/socialSample.min.css' : ['bower_components/medium-editor/dist/css/medium-editor.css', 'bower_components/famous-angular/dist/famous-angular.css', 'public/bootstrap/css/bootstrap.css', 'public/css/socialSample.css', 'public/css/genericons.css']
                }
            }
        }
    });

    // Load plugins here
    grunt.loadNpmTasks('grunt-contrib-copy');
    //grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-browserify');
    grunt.loadNpmTasks('grunt-browserify-bower');
    //grunt.loadNpmTasks('grunt-contrib-clean');
    //grunt.loadNpmTasks('grunt-react');
    grunt.registerTask('Develop', ['browserify', 'cssmin']);
    grunt.registerTask('Final', ['copy', 'browserify', 'uglify', 'cssmin']);
};