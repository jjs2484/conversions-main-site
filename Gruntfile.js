module.exports = function(grunt) {
	
	// Force use of Unix newlines
	grunt.util.linefeed = '\n';
	
	// Configuration
	grunt.initConfig({
		sass: {
			dist: {
				options: {
					style: 'nested',
					precision: 5,
				},
				files: {
					'css/additional.css': 'css/additional.scss',
				}
			}
		},
		postcss: {
			options: {
				processors: [
					require('autoprefixer')({
						overrideBrowserslist: ['> 0.5%, last 2 versions, Firefox ESR, not dead']
					})
				]
			},
			dist: {
				src: 'css/additional.css'
			}
		},
		cssmin: {
			target: {
				files: {
					'css/additional.min.css': ['css/additional.css'],
				}
			}
		},
	});

	// Load plugins
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('@lodder/grunt-postcss');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	
	// Run All Tasks
	grunt.registerTask('all', ['sass', 'postcss', 'cssmin']);

};