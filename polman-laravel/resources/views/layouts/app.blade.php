<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? '' }}@yield('title', 'Dashboard') - Polman Improvement Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    @vite('resources/js/app.js')
    <style>
        :root {
            --primary-900: #00334E;
            --primary-700: #145374;
            --primary-500: #5588A3;
            --primary-100: #E8E8E8;
            --surface-900: rgba(0, 51, 78, 0.92);
            --surface-800: rgba(0, 51, 78, 0.78);
            --surface-700: rgba(0, 51, 78, 0.68);
            --text-light: #E8E8E8;
            --text-muted: rgba(232, 232, 232, 0.72);
        }
        body {
            background: radial-gradient(circle at top left, rgba(85, 136, 163, 0.18), transparent 18%), radial-gradient(circle at bottom right, rgba(20, 83, 116, 0.16), transparent 28%), #00334E;
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
        }
        html.light body {
            background: radial-gradient(circle at top left, rgba(6, 182, 212, 0.08), transparent 24%), linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
            color: #0f172a;
        }
        html.light .navbar {
            background: rgba(255,255,255,0.92);
            border-color: rgba(148,163,184,0.35);
            color: #0f172a;
        }
        html.light .navbar-title,
        html.light .navbar-subtitle,
        html.light .navbar-breadcrumbs,
        html.light .sidebar-label,
        html.light .sidebar-link,
        html.light .sidebar-link i,
        html.light .sidebar-link span,
        html.light .sidebar .sidebar-badge,
        html.light .navbar-user-name,
        html.light .navbar-user-role {
            color: #0f172a !important;
        }
        html.light .navbar-user {
            background: rgba(255,255,255,0.92);
        }
        html.light .sidebar {
            background: rgba(255,255,255,0.92);
            border-color: rgba(148,163,184,0.35);
        }
        html.light .sidebar-link {
            color: #334155;
        }
        html.light .sidebar-link:hover,
        html.light .sidebar-link.active {
            background: rgba(14,165,233,0.12);
            color: #0284c7;
        }
        html.light .main-content {
            background: radial-gradient(circle at top left, rgba(14,165,233,0.08), transparent 24%), linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        }
        html.light .page-header,
        html.light .dashboard-hero,
        html.light .stat-card,
        html.light .card,
        html.light .tree-panel,
        html.light .tree-node,
        html.light .alert {
            background: rgba(255,255,255,0.94);
            border-color: rgba(148,163,184,0.2);
            color: #0f172a;
            box-shadow: 0 20px 50px rgba(15,23,42,0.08);
        }
        html.light .stat-icon {
            background: rgba(6,182,212,0.12);
            color: #0e7490;
        }
        html.light .stat-label,
        html.light .card-header h3,
        html.light .dashboard-hero p,
        html.light .page-header p,
        html.light .table th,
        html.light .table td {
            color: #475569 !important;
        }
        .navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 2rem;
            background: rgba(0, 51, 78, 0.88);
            backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(85, 136, 163, 0.18);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.16);
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            min-width: 0;
        }
        .navbar-brand img {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 0 24px rgba(6, 182, 212, 0.18);
        }
        .navbar-title-group {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            max-width: fit-content;
            flex-shrink: 0;
        }
        .navbar-title-group {
            display: flex;
            flex-direction: column;
            gap: 0.12rem;
            max-width: fit-content;
            flex-shrink: 0;
        }
        .navbar-title {
            font-size: 1.05rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: #f8fafc;
            white-space: nowrap;
        }
        .navbar-subtitle {
            font-size: 0.82rem;
            line-height: 1.4;
            letter-spacing: 0.01em;
            color: rgba(148, 163, 184, 0.95);
        }
        .navbar-subtitle.hidden-mobile {
            color: rgba(148, 163, 184, 0.95);
        }
        .navbar-content {
            display: flex;
            align-items: center;
            gap: 0.95rem;
            justify-content: flex-end;
            min-width: 0;
        }
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .navbar-badge {
            position: relative;
        }
        .navbar-badge::after {
            content: '';
            position: absolute;
            top: -6px;
            right: -6px;
            width: 12px;
            height: 12px;
            border-radius: 9999px;
            background: #fb7185;
            box-shadow: 0 0 0 4px rgba(248, 113, 128, 0.12);
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            position: relative;
            cursor: pointer;
            padding: 0.55rem 0.8rem;
            border-radius: 1rem;
            transition: background 0.2s ease;
        }
        .navbar-user:hover {
            background: rgba(30, 41, 59, 0.35);
        }
        .navbar-user-avatar {
            display: grid;
            place-items: center;
            min-width: 44px;
            min-height: 44px;
            border-radius: 9999px;
            background: linear-gradient(135deg, rgba(34,211,238,0.14), rgba(14,165,233,0.25));
            color: #e2e8f0;
            font-weight: 700;
            box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.18);
            position: relative;
        }
        .navbar-user-status {
            position: absolute;
            right: 2px;
            bottom: 2px;
            width: 10px;
            height: 10px;
            border-radius: 9999px;
            background: #22c55e;
            box-shadow: 0 0 0 3px rgba(34,197,94,0.18);
        }
        .navbar-user-info {
            min-width: 0;
        }
        .navbar-user-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #f8fafc;
        }
        .navbar-user-role {
            font-size: 0.78rem;
            color: #94a3b8;
        }
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 40;
            width: 280px;
            max-width: 100%;
            min-height: 100vh;
            padding: 1.1rem 1rem 1rem 1rem;
            background: rgba(15, 23, 42, 0.92);
            border-right: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(18px);
            box-shadow: 0 24px 90px rgba(0,0,0,0.32);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: width 0.28s ease, transform 0.28s ease;
        }
        .sidebar.collapsed {
            width: 88px;
        }
        .sidebar-section {
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            padding: 0.7rem 0.5rem;
            border-radius: 1.25rem;
            background: rgba(15, 23, 42, 0.35);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
            padding-left: 0.2rem;
        }
        .sidebar-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.95rem 0.9rem;
            border-radius: 1rem;
            color: #e2e8f0;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            font-size: 0.95rem;
        }
        .sidebar-link i {
            min-width: 20px;
            min-height: 20px;
            color: #38bdf8;
        }
        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(56, 189, 248, 0.10);
            color: #38bdf8;
        }
        .sidebar-link.active {
            border-left: 3px solid #22d3ee;
        }
        .sidebar .sidebar-badge {
            margin-left: auto;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            background: rgba(255,255,255,0.08);
            color: #cbd5e1;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .sidebar-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.85rem 0.85rem 0.65rem;
        }
        .sidebar-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #f8fafc;
        }
        .sidebar-collapse {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            border: 1px solid rgba(148, 163, 184, 0.12);
            background: rgba(148, 163, 184, 0.06);
            color: #94a3b8;
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .sidebar-collapse:hover {
            background: rgba(56, 189, 248, 0.14);
            transform: translateX(-1px);
            color: #38bdf8;
        }
        .sidebar.collapsed .sidebar-label,
        .sidebar.collapsed .sidebar-link span:not(.sidebar-badge),
        .sidebar.collapsed .sidebar-top .sidebar-title {
            display: none;
        }
        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        .sidebar.collapsed .sidebar-link .sidebar-badge {
            position: absolute;
            right: 12px;
        }
        .main-content {
            margin-left: 280px;
            padding: 1.5rem 1.5rem 2rem 1.5rem;
            min-height: 100vh;
            transition: margin-left 0.28s ease;
            background: radial-gradient(circle at top left, rgba(85, 136, 163, 0.08), transparent 24%), linear-gradient(180deg, #00334E 0%, #021d33 100%);
        }
        #sidebar.collapsed ~ .main-content {
            margin-left: 88px;
        }
        .page-header,
        .dashboard-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1.3rem 1.35rem;
            background: rgba(0, 51, 78, 0.92);
            border: 1px solid rgba(85,136,163,0.18);
            border-radius: 1.5rem;
            box-shadow: 0 20px 65px rgba(0,0,0,0.14);
            backdrop-filter: blur(16px);
        }
        .tree-panel {
            background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.12), transparent 20%), rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 2rem;
            padding: 1.25rem;
            box-shadow: 0 28px 90px rgba(0,0,0,0.28);
            backdrop-filter: blur(20px);
        }
        .tree-node {
            background: rgba(15, 23, 42, 0.72);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 1.75rem;
            transition: background 0.25s ease, transform 0.25s ease, border-color 0.25s ease;
        }
        .tree-node:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-1px);
        }
        .tree-node-head {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 1rem 1.2rem;
        }
        .tree-node-label {
            min-width: 0;
            overflow: hidden;
        }
        .tree-node-label h3,
        .tree-node-label p {
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .tree-node-label h3 {
            color: #f8fafc;
            font-size: 0.98rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .tree-node-label p {
            color: #94a3b8;
            font-size: 0.78rem;
        }
        .tree-node-actions {
            margin-left: auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.45rem;
        }
        .tree-icon {
            display: grid;
            place-items: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 1rem;
            background: rgba(255,255,255,0.06);
            color: #cbd5e1;
        }
        .tree-status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #cbd5e1;
            background: rgba(255,255,255,0.06);
        }
        .tree-add-panel {
            margin-top: 0.75rem;
            padding: 1rem;
            border: 1px dashed rgba(148, 163, 184, 0.22);
            border-radius: 1.35rem;
            background: rgba(148, 163, 184, 0.04);
        }
        .tree-empty-state {
            padding: 1rem;
            border-radius: 1.5rem;
            border: 1px dashed rgba(148, 163, 184, 0.16);
            background: rgba(148, 163, 184, 0.05);
            color: #94a3b8;
            font-size: 0.93rem;
        }
        .tree-node-children {
            margin-top: 1rem;
            padding-left: 1rem;
            border-left: 1px solid rgba(148, 163, 184, 0.12);
        }
        .tree-action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            border: 1px solid rgba(255,255,255,0.09);
            background: rgba(15, 23, 42, 0.72);
            color: #cbd5e1;
            transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }
        .tree-action-btn:hover {
            background: rgba(255,255,255,0.08);
            box-shadow: 0 0 14px rgba(56, 189, 248, 0.18);
            transform: translateY(-1px);
        }
        .tree-level {
            margin-left: 1rem;
            padding-left: 1rem;
            border-left: 1px solid rgba(255,255,255,0.1);
        }
        .tree-leaf {
            border-left-color: rgba(16, 185, 129, 0.35) !important;
        }
        .tree-toggle-btn {
            width: 2.25rem;
            height: 2.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            border: 1px solid rgba(148, 163, 184, 0.24);
            background: rgba(255,255,255,0.06);
            color: #94a3b8;
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .tree-toggle-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        .tree-toggle-btn.rotate-90 {
            transform: rotate(90deg);
        }
        .btn-plus-glow:hover {
            box-shadow: 0 0 20px rgba(8,145,178,0.3);
        }
        .page-header h1,
        .dashboard-hero h1 {
            margin: 0;
            font-size: 1.4rem;
            color: var(--primary-100);
        }
        .page-header p,
        .dashboard-hero p {
            margin: 0;
            color: var(--text-muted);
        }
        .dashboard-hero .subtext {
            color: rgba(232, 232, 232, 0.78);
            margin-top: 0.25rem;
        }
        .dashboard-actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }
        .stat-card,
        .card {
            background: var(--surface-900);
            border: 1px solid rgba(85, 136, 163, 0.22);
            border-radius: 1.45rem;
            box-shadow: 0 24px 60px rgba(0,0,0,0.24);
            backdrop-filter: blur(18px);
            padding: 1.35rem;
            overflow: hidden;
        }
        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-height: 124px;
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: rgba(85, 136, 163, 0.18);
            color: var(--primary-100);
            box-shadow: 0 0 18px rgba(85, 136, 163, 0.15);
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #f8fafc;
        }
        .stat-label {
            color: #94a3b8;
            font-size: 0.95rem;
            margin-top: 0.35rem;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .card-header h3 {
            margin: 0;
            font-size: 1.05rem;
            color: #f8fafc;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            border-radius: 1rem;
            padding: 0.9rem 1.2rem;
            font-weight: 600;
            transition: transform 0.22s ease, background 0.22s ease, box-shadow 0.22s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
            color: var(--primary-100);
            box-shadow: 0 18px 40px rgba(85, 136, 163, 0.24);
        }
        .btn-primary {
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }
        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(20, 83, 116, 0.32);
            background: linear-gradient(135deg, var(--primary-900), var(--primary-700));
        }
        .btn-outline {
            color: #cbd5e1;
            border: 1px solid rgba(148, 163, 184, 0.2);
            background: transparent;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease, color 0.3s ease, background 0.3s ease;
        }
        .btn-sm {
            padding: 0.65rem 0.95rem;
            font-size: 0.86rem;
        }
        .table-wrapper {
            overflow-x: auto;
            background: rgba(0, 51, 78, 0.42);
            border: 1px solid rgba(85, 136, 163, 0.18);
            backdrop-filter: blur(14px);
            border-radius: 1.5rem;
            padding: 0.75rem;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
            background: transparent;
        }
        .table thead tr {
            background: rgba(3, 51, 78, 0.94);
            border-bottom: 1px solid rgba(85, 136, 163, 0.15);
        }
        .table th,
        .table td {
            padding: 1rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            text-align: left;
            color: #e2e8f0;
        }
        .table th {
            padding: 1rem 1.5rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #f8fafc;
            font-weight: 700;
        }
        .table tbody tr {
            transition: background 0.24s ease, transform 0.24s ease;
        }
        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.04);
        }
        .text-muted {
            color: #94a3b8;
        }
        .text-primary-strong {
            color: #e2e8f0;
        }
        .badge-info { background: rgba(56, 189, 248, 0.12); color: #7dd3fc; }
        .badge-warning { background: rgba(245, 158, 11, 0.12); color: #fbbf24; }
        .badge-success { background: rgba(16, 185, 129, 0.18); color: #4ade80; }
        .badge-danger { background: rgba(239, 68, 68, 0.16); color: #f87171; }
        .badge-primary { background: rgba(85, 136, 163, 0.2); color: var(--primary-100); }
        .badge-neutral { background: rgba(148, 163, 184, 0.16); color: #cbd5e1; }
        .theme-toggle-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 9999px;
            border: 1px solid rgba(85, 136, 163, 0.3);
            background: rgba(0, 51, 78, 0.72);
            color: var(--text-light);
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease, color 0.2s ease;
        }
        .theme-toggle-button:hover {
            background: rgba(85, 136, 163, 0.32);
            color: var(--primary-100);
        }
        html.light body {
            background: radial-gradient(circle at top left, rgba(14, 165, 233, 0.12), transparent 18%), radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.06), transparent 26%), #f8fafc;
            color: #0f172a;
        }
        html.light .main-content {
            background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.06), transparent 20%), #f8fafc;
        }
        html.light .navbar {
            background: rgba(255,255,255,0.92);
            border-color: rgba(148,163,184,0.28);
            box-shadow: 0 12px 30px rgba(15,23,42,0.08);
        }
        html.light .navbar-title,
        html.light .navbar-user-name {
            color: #0f172a;
        }
        html.light .navbar-subtitle,
        html.light .navbar-breadcrumbs,
        html.light .navbar-user-role {
            color: #64748b;
        }
        html.light .navbar-user {
            background: rgba(255,255,255,0.94);
            border: 1px solid rgba(148,163,184,0.24);
        }
        html.light .sidebar {
            background: rgba(255,255,255,0.94);
            border-color: rgba(148,163,184,0.24);
            box-shadow: 0 24px 90px rgba(15,23,42,0.08);
        }
        html.light .sidebar-section {
            background: rgba(255,255,255,0.86);
            border-color: rgba(148,163,184,0.24);
        }
        html.light .sidebar-link {
            color: #0f172a;
        }
        html.light .sidebar-link i {
            color: #0ea5e9;
        }
        html.light .sidebar-link:hover,
        html.light .sidebar-link.active {
            background: rgba(56,189,248,0.12);
            color: #0f172a;
        }
        html.light .sidebar-link.active {
            border-left-color: #22d3ee;
        }
        html.light .sidebar .sidebar-badge {
            background: rgba(148,163,184,0.18);
            color: #475569;
        }
        html.light .card,
        html.light .stat-card,
        html.light .tree-panel,
        html.light .table-wrapper {
            background: rgba(255,255,255,0.92);
            border-color: rgba(148,163,184,0.24);
            box-shadow: 0 24px 60px rgba(15,23,42,0.08);
        }
        html.light .card:hover {
            filter: brightness(0.98);
            border-color: rgba(56,189,248,0.16);
        }
        html.light .page-header,
        html.light .dashboard-hero {
            background: rgba(255,255,255,0.94);
            border-color: rgba(148,163,184,0.24);
        }
        html.light .page-header h1,
        html.light .dashboard-hero h1 {
            color: #0f172a;
        }
        html.light .page-header p,
        html.light .dashboard-hero p {
            color: #64748b;
        }
        html.light .table thead tr {
            background: #f8fafc;
            border-color: rgba(148,163,184,0.22);
        }
        html.light .table th,
        html.light .table td {
            color: #0f172a;
            border-bottom-color: rgba(148,163,184,0.22);
        }
        html.light .table th {
            color: #0f172a;
        }
        html.light .text-muted {
            color: #64748b;
        }
        html.light .badge-info { background: rgba(56,189,248,0.12); color: #0f172a; }
        html.light .badge-warning { background: rgba(245,158,11,0.12); color: #92400e; }
        html.light .badge-success { background: rgba(16,185,129,0.12); color: #166534; }
        html.light .badge-danger { background: rgba(239,68,68,0.12); color: #991b1b; }
        html.light .badge-primary { background: rgba(59,130,246,0.14); color: #1d4ed8; }
        html.light .badge-neutral { background: rgba(148,163,184,0.16); color: #475569; }
        html.light .tree-node { background: rgba(255,255,255,0.92); border-color: rgba(148,163,184,0.25); }
        html.light .tree-node.border-l-4 { border-left-color: rgba(148,163,184,0.55) !important; }
        html.light .tree-node.tree-level.border-l-4 { border-left-color: rgba(148,163,184,0.55) !important; }
        html.light .tree-node.tree-leaf.border-l-4 { border-left-color: rgba(148,163,184,0.45) !important; }
        html.light .tree-node:hover { background: rgba(241,245,249,0.95); }
        html.light .tree-node-label h3,
        html.light .tree-node-label p {
            color: #0f172a;
        }
        html.light .tree-status-pill { background: rgba(148,163,184,0.16); color: #475569; }
        html.light .tree-action-btn {
            background: rgba(241,245,249,0.92);
            border-color: rgba(148,163,184,0.24);
            color: #475569;
        }
        html.light .tree-action-btn:hover {
            background: rgba(255,255,255,0.98);
            box-shadow: 0 0 12px rgba(56,189,248,0.12);
        }
        html.light .tree-toggle-btn {
            background: rgba(255,255,255,0.9);
            border-color: rgba(148,163,184,0.3);
            color: #475569;
        }
        html.light .btn-outline {
            color: #0f172a;
            border-color: rgba(148,163,184,0.4);
        }
        html.light .btn-outline:hover {
            background: rgba(241,245,249,0.9);
        }
        .btn-dashboard-action {
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
            transform: translateZ(0);
        }
        .btn-dashboard-action:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.25);
        }
        .btn-approve {
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }
        .btn-approve:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.32);
        }
        .btn-reject {
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }
        .btn-reject:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.28);
        }
        .card:hover {
            filter: brightness(1.02);
            border-color: rgba(56, 189, 248, 0.16);
        }
        .period-card {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.40), rgba(15, 23, 42, 0.60));
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.04), 0 24px 70px rgba(0,0,0,0.20);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .period-card .badge-success {
            position: relative;
            background: rgba(16, 185, 129, 0.10);
            color: #4ade80;
            box-shadow: 0 0 18px rgba(16, 185, 129, 0.12);
        }
        .period-card .badge-success::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            box-shadow: 0 0 16px rgba(16, 185, 129, 0.16);
            opacity: 0.3;
        }
        .btn-close-period {
            border: 1px solid rgba(255,255,255,0.10);
            color: #f87171;
            background: transparent;
        }
        .btn-close-period:hover {
            border-color: rgba(239, 68, 68, 0.50);
            color: #fda4af;
            background: rgba(239, 68, 68, 0.08);
            transform: scale(1.02);
        }
        .sidebar-link:hover {
            background: rgba(14, 165, 233, 0.12);
            color: #38bdf8;
            transform: translateX(5px);
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            position: relative;
            cursor: pointer;
            padding: 0.55rem 0.8rem;
            border-radius: 1.25rem;
            transition: background 0.2s ease;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255,255,255,0.06);
            backdrop-filter: blur(14px);
        }
        .navbar-breadcrumbs {
            color: #94a3b8;
            font-size: 0.82rem;
            margin-top: 0.3rem;
            display: inline-block;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-info { background: rgba(6, 182, 212, 0.12); color: #38bdf8; }
        .badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
        .badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
        .badge-danger { background: rgba(239, 68, 68, 0.12); color: #ef4444; }
        .badge-primary { background: rgba(59, 130, 246, 0.12); color: #3b82f6; }
        .badge-neutral { background: rgba(148, 163, 184, 0.12); color: #94a3b8; }
        .empty-state {
            min-height: 220px;
            display: grid;
            place-items: center;
            gap: 0.75rem;
            text-align: center;
            color: #94a3b8;
            padding: 2rem 1rem;
        }
        .empty-state i {
            color: #38bdf8;
        }
        .empty-state a {
            color: #38bdf8;
            text-decoration: none;
        }
        .animate-in {
            opacity: 0;
            transform: translateY(18px) scale(0.98);
        }
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.24s ease, visibility 0.24s ease;
            z-index: 30;
        }
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .hidden-mobile { display: inline-flex; }
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding-top: 5.25rem;
            }
            .hidden-mobile { display: none; }
        }

        nav[aria-label="Pagination Navigation"] {
            padding: 1rem 0 0.8rem;
        }
        nav[aria-label="Pagination Navigation"] ul {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            justify-content: flex-end;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        nav[aria-label="Pagination Navigation"] li {
            margin: 0;
        }
        nav[aria-label="Pagination Navigation"] li span,
        nav[aria-label="Pagination Navigation"] li a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            padding: 0.72rem 0.95rem;
            border-radius: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.10);
            background: rgba(255, 255, 255, 0.05);
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        nav[aria-label="Pagination Navigation"] li a:hover {
            background: rgba(56, 189, 248, 0.16);
            border-color: rgba(56, 189, 248, 0.24);
            color: #f8fafc;
        }
        nav[aria-label="Pagination Navigation"] li span[aria-current],
        nav[aria-label="Pagination Navigation"] li.active span,
        nav[aria-label="Pagination Navigation"] li.active a {
            background: rgba(34, 211, 238, 0.24);
            border-color: rgba(34, 211, 238, 0.50);
            color: #22d3ee;
            font-weight: 600;
        }
        nav[aria-label="Pagination Navigation"] li.disabled span,
        nav[aria-label="Pagination Navigation"] li.disabled a {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
    @livewireStyles
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Sidebar Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Main Content --}}
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success animate-in">
                <i data-lucide="check-circle" style="width:18px;height:18px;flex-shrink:0;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger animate-in">
                <i data-lucide="alert-circle" style="width:18px;height:18px;flex-shrink:0;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @livewireScripts
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        function toggleSidebarCollapse() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }

            const animated = document.querySelectorAll('.animate-in');
            if (window.anime && typeof anime === 'function' && animated.length) {
                anime({
                    targets: animated,
                    opacity: [0, 1],
                    scale: [0.96, 1],
                    translateY: [18, 0],
                    delay: anime.stagger(80),
                    duration: 650,
                    easing: 'easeOutExpo'
                });
            } else {
                animated.forEach((el, index) => {
                    el.style.opacity = '0';
                    setTimeout(() => {
                        el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0) scale(1)';
                    }, 80 * index);
                });
            }
        });

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const trigger = document.getElementById('userMenuTrigger');
            if (dropdown && trigger && !trigger.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        document.addEventListener('livewire:navigated', () => lucide.createIcons());
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => lucide.createIcons());
        }
    </script>
    @stack('scripts')
</body>
</html>
