
module.exports = function(grunt) { // jshint ignore:line

    // Import modules.
    var path = require('path');

    // PHP strings for exec task.
    var moodleroot = path.dirname(path.dirname(__dirname)), // jshint ignore:line
        configfile = '',
        dirrootopt = grunt.option('dirroot') || process.env.MOODLE_DIR || ''; // jshint ignore:line

    // Allow user to explicitly define Moodle root dir.
    if ('' !== dirrootopt) {
        moodleroot = path.resolve(dirrootopt);
    }

    var PWD = process.cwd(); // jshint ignore:line
    configfile = path.join(moodleroot, 'config.php');

    var decachephp = '../../admin/cli/purge_caches.php';

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
            tasks: ["compile"],
            options: {
                spawn: false,
                livereload: true
            }
        }
    });

    // Load contrib tasks.
    grunt.loadNpmTasks("grunt-contrib-watch");
    grunt.loadNpmTasks("grunt-exec");

    // Load core tasks.
    grunt.loadNpmTasks('grunt-contrib-jshint');

    // Register tasks.
    grunt.registerTask("default", ["watch"]);
    grunt.registerTask("decache", ["exec:decache"]);

    grunt.registerTask("compile", ["exec:decache"]);

    grunt.registerTask(["decache"]);
};
