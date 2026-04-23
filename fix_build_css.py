import re

with open('polman-laravel/public/build/assets/navbar-BSefXS1y.css', 'r', encoding='utf-8') as f:
    content = f.read()

# Remove :root.dark-theme{...}:root.light-theme{...} and .theme-toggle{...}
# Keep everything from .navbar onwards
pattern = r'^:root\.dark-theme\{[^}]*\}:root\.light-theme\{[^}]*\}\.theme-toggle\{[^}]*\}'
content = re.sub(pattern, '', content)

with open('polman-laravel/public/build/assets/navbar-BSefXS1y.css', 'w', encoding='utf-8') as f:
    f.write(content)

print('Done removing theme selectors from built navbar CSS')

