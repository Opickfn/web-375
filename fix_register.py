replacements = [
    ('background: #00334E;', 'background: #F0F9F4;'),
    ('background: rgba(85, 136, 163, 0.18);', 'background: rgba(0, 51, 78, 0.1);'),
    ('background: rgba(20, 83, 116, 0.36);', 'background: rgba(0, 51, 78, 0.08);'),
    ('background: rgba(0, 51, 78, 0.72);', 'background: rgba(255, 255, 255, 0.95);'),
    ('border: 1px solid rgba(85, 136, 163, 0.22);', 'border: 1px solid #A0AEC0;'),
    ('box-shadow: 0 45px 90px rgba(0, 0, 0, 0.35);', 'box-shadow: 0 25px 60px rgba(0, 0, 0, 0.1);'),
    ('color: #E8E8E8;', 'color: #00334E;'),
    ('color: #e2e8f0;', 'color: #00334E;'),
    ('color: #94a3b8;', 'color: #64748b;'),
    ('color: #5588A3;', 'color: #00334E;'),
    ('color: rgba(232, 232, 232, 0.9);', 'color: #475569;'),
    ('filter: drop-shadow(0 0 28px rgba(85, 136, 163, 0.45));', 'filter: drop-shadow(0 0 12px rgba(0, 51, 78, 0.15));'),
    ('background: linear-gradient(90deg, #5588A3, rgba(85,136,163,0.5));', 'background: linear-gradient(90deg, #00334E, rgba(0,51,78,0.35));'),
    ('background: rgba(0, 51, 78, 0.55) !important;', 'background: #FFFFFF !important;'),
    ('border: 1px solid rgba(85, 136, 163, 0.22) !important;', 'border: 1px solid #A0AEC0 !important;'),
    ('color: rgba(203, 213, 225, 0.65);', 'color: rgba(100, 116, 139, 0.65);'),
    ("stroke='%239ca3af'", "stroke='%2300334E'"),
    ('border-color: rgba(85, 136, 163, 0.95);', 'border-color: #00334E;'),
    ('box-shadow: 0 0 10px rgba(85, 136, 163, 0.2);', 'box-shadow: 0 0 10px rgba(0, 51, 78, 0.15);'),
    ('background: linear-gradient(135deg, #145374, #5588A3);', 'background: #00334E;'),
    ('box-shadow: 0 20px 50px rgba(85, 136, 163, 0.24);', 'box-shadow: 0 12px 30px rgba(0, 51, 78, 0.25);'),
    ('box-shadow: 0 24px 60px rgba(20, 83, 116, 0.32);', 'box-shadow: 0 16px 40px rgba(0, 51, 78, 0.35);'),
    ('background: linear-gradient(135deg, #00334E, #145374);', 'background: #001f35;'),
    ('background: rgba(34, 197, 94, 0.18);', 'background: rgba(34, 197, 94, 0.1);'),
    ('border-color: rgba(34, 197, 94, 0.32);', 'border-color: rgba(34, 197, 94, 0.35);'),
    ('color: #bbf7d0;', 'color: #15803d;'),
]

with open('polman-laravel/resources/views/auth/register.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

for old, new in replacements:
    content = content.replace(old, new)

with open('polman-laravel/resources/views/auth/register.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print('Done updating register.blade.php')

