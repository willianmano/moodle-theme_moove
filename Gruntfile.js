/* eslint no-undef: "error" */
/* eslint camelcase: 2 */
/* eslint-env node */

"use strict";

module.exports = function(grunt) {

    // Import modules.
    var path = require("path");

    var PWD = process.cwd();

    var decachephp = "../../admin/cli/purge_caches.php";

    grunt.initConfig({
        exec: {
            decache: {
                cmd: 'php "' + decachephp + '"',
                callback: function(error) {
                    // The exec process will output error messages.
                    // Just add one to confirm success.
                    if (!error) {
                        grunt.log.writeln("Moodle theme cache reset.");
                    }
                }
            }
        },
        watch: {
            // Watch for any changes to less files and compile.
            files: ["scss/**/*.scss", "templates/*.mustache"],
            tasks: ["decache"],
            options: {
                spawn: false,
                livereload: true
            }
        },
        stylelint: {
            scss: {
                options: {
                    configFile: ".stylelintrc",
                    syntax: "scss"
                },
                src: ["scss/**/*.scss"]
            },
            css: {
                src: ["*/**/*.css"],
                options: {
                    configOverrides: {
                        rules: {
                            // These rules have to be disabled in .stylelintrc for scss compat.
                            "at-rule-no-unknown": true,
                            "no-browser-hacks": [true, {"severity": "warning"}]
                        }
                    }
                }
            }
        },
        jshint: {
            options: {
                jshintrc: true
            },
            files: ["*.js"]
        },
        uglify: {
            dynamic_mappings: {
                files: grunt.file.expandMapping(
                    ["**/src/*.js", "!**/node_modules/**"],
                    "",
                    {
                        cwd: PWD,
                        rename: function(destBase, destPath) {
                            destPath = destPath.replace("src", "build");
                            destPath = destPath.replace(".js", ".min.js");
                            destPath = path.resolve(PWD, destPath);
                            return destPath;
                        }
                    }
                )
            }
        }
    });

    // Load contrib tasks.
    grunt.loadNpmTasks("grunt-contrib-watch");
    grunt.loadNpmTasks("grunt-exec");

    // Load core tasks.
    grunt.loadNpmTasks("grunt-contrib-uglify");
    grunt.loadNpmTasks("grunt-contrib-jshint");
    grunt.loadNpmTasks("grunt-stylelint");

    // Register CSS taks.
    grunt.registerTask("css", ["stylelint:scss", "stylelint:css"]);

    // Register tasks.
    grunt.registerTask("default", ["watch"]);
    grunt.registerTask("decache", ["exec:decache"]);

    grunt.registerTask("compile", [
        "jshint",
        "uglify",
        "decache"
    ]);

    grunt.registerTask("amd", ["jshint", "uglify"]);
};
