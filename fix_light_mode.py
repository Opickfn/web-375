import re

# Fix app.css
with open('polman-laravel/resources/css/app.css', 'r', encoding='utf-8') as f:
    content = f.read()

replacements = [
    # Old accent rgba(85,136,163, x) -> new accent rgba(0,51,78, x)
    ('rgba(85,136,163,0.15)', 'rgba(0,51,78,0.08)'),
    ('rgba(85,136,163,0.35)', 'rgba(0,51,78,0.2)'),
    ('rgba(85,136,163,0.06)', 'rgba(0,51,78,0.04)'),
    ('rgba(85,136,163,0.12)', 'rgba(0,51,78,0.06)'),
    ('rgba(85,136,163,0.1)', 'rgba(0,51,78,0.05)'),
    ('rgba(85,136,163,0.14)', 'rgba(0,51,78,0.07)'),
    ('rgba(85,136,163,0.22)', 'rgba(0,51,78,0.12)'),
    ('rgba(85,136,163,0.55)', 'rgba(0,51,78,0.35)'),
    ('rgba(85,136,163,0.45)', 'rgba(0,51,78,0.25)'),
    ('rgba(85,136,163,0.18)', 'rgba(0,51,78,0.1)'),
    ('rgba(85,136,163,0.2)', 'rgba(0,51,78,0.1)'),
    # Old border rgba(20,83,116, x) -> new dark blue rgba
    ('rgba(20,83,116,0.18)', 'rgba(0,51,78,0.08)'),
    ('rgba(20,83,116,0.4)', 'rgba(0,51,78,0.2)'),
    ('rgba(20,83,116,0.3)', 'rgba(0,51,78,0.15)'),
    ('rgba(20,83,116,0.5)', 'rgba(0,51,78,0.25)'),
    ('rgba(20,83,116,0.2)', 'rgba(160,174,192,0.35)'),
    # Old dark backgrounds
    ('rgba(0,40,65,0.5)', 'rgba(0,51,78,0.04)'),
    ('rgba(0,40,65,0.55)', 'rgba(0,51,78,0.04)'),
    ('rgba(0,40,65,0.7)', 'rgba(0,51,78,0.06)'),
    ('rgba(0,30,50,0.3)', 'rgba(0,51,78,0.04)'),
    ('rgba(0,30,50,0.25)', 'rgba(0,51,78,0.04)'),
    ('rgba(0,30,50,0.6)', 'rgba(0,51,78,0.04)'),
    # Neutral old text colors
    ('rgba(232,232,232,0.08)', 'rgba(0,51,78,0.04)'),
    ('rgba(232,232,232,0.15)', 'rgba(0,51,78,0.1)'),
    # Badge text colors
    ('color:#5588A3;', 'color:#00334E;'),
    ('color: #5588A3;', 'color: #00334E;'),
    # Other hardcoded dark blues
    ('#001e35', '#FFFFFF'),
]

for old, new in replacements:
    content = content.replace(old, new)

with open('polman-laravel/resources/css/app.css', 'w', encoding='utf-8') as f:
    f.write(content)

print('Done replacing dark rgba values in app.css')

