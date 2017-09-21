# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "assets/flags/css"
sass_dir = "assets/flags/sass"
images_dir = "assets/flags/flags"

# To enable relative paths to assets via compass helper functions. Uncomment:
relative_assets = true

output_style = (environment == :production) ? :compressed : :expanded
line_comments = (environment == :production) ? false : true
sourcemap = (environment == :production) ? false : true

