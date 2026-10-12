@if ($pagina === 'inicio')
<!DOCTYPE html><html lang="es"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Asis GO+ - Ultra-PRO Hybrid Attendance</title>
<script src="/js/home.config.js"></script><script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">


<link rel="stylesheet" href="/css/home.css">
<link rel="stylesheet" href="/css/inline.css">
</head>
<body class="min-h-screen flex flex-col relative overflow-x-hidden text-white selection:bg-accent-orange/30">
<canvas id="shader-canvas" width="1280" height="1466"></canvas>
<!-- Nav -->
<header class="flex justify-between items-center z-50 relative px-6 md:px-16 py-8">
<div class="flex items-center gap-3 group cursor-pointer">
<div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center transition-all duration-500 group-hover:border-accent-orange group-hover:shadow-[0_0_20px_rgba(249,115,22,0.3)]">
<svg class="h-5 w-5 text-accent-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
</div>
<span class="text-white font-bold text-xl tracking-tight group-hover:text-accent-orange transition-colors">ASIS GO+</span>
</div>
<div class="flex items-center gap-6">
<a class="text-sm font-semibold text-gray-400 hover:text-white transition-colors" href="/iniciar_sesion">Iniciar Sesión</a>
<a class="relative px-7 py-2.5 bg-white text-black text-sm font-bold rounded-full transition-all duration-300 hover:bg-accent-orange hover:text-white hover:shadow-[0_0_25px_rgba(249,115,22,0.4)] transform hover:-translate-y-0.5" href="/sign_up">Registrarse</a>
</div>
</header>
<!-- Main Content -->
<main class="flex-1 flex flex-col relative z-10 px-6 md:px-16 lg:px-24">
<div class="flex flex-col lg:flex-row min-h-[calc(100vh-120px)] items-center">
<!-- Left Column: Content -->
<div class="flex-1 flex flex-col justify-center py-12 lg:py-0 z-20">
<div class="opacity-0 animate-fade-up stagger-1">
<span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-accent-orange/10 border border-accent-orange/20 text-accent-orange text-xs font-bold uppercase tracking-widest mb-8 shadow-[0_0_15px_rgba(249,115,22,0.1)]">
<span class="relative flex h-2 w-2">
<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-orange opacity-75"></span>
<span class="relative inline-flex rounded-full h-2 w-2 bg-accent-orange"></span>
</span>
        El Futuro del Trabajo
      </span>
<h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold leading-[1.05] tracking-tight mb-8">
<span class="text-gradient block">Asistencia sin</span>
<span class="accent-gradient block relative">
          fricciones.
          <span class="absolute -bottom-2 left-0 w-24 h-1.5 bg-accent-orange rounded-full opacity-30 blur-[4px]"></span>
</span>
</h1>
<p class="text-gray-400 text-lg md:text-xl max-w-xl leading-relaxed mb-12 font-medium">
        Gestión de asistencia híbrida diseñada para la claridad y la velocidad. QR dinámico y validación por ubicación sin interrupciones para equipos de alto rendimiento.
      </p>
<div class="flex items-center">
<a class="group relative flex items-center gap-4 bg-accent-orange px-12 py-5 rounded-full text-white font-bold text-lg overflow-hidden transition-all duration-300 hover:scale-105 active:scale-95 shadow-[0_20px_40px_-10px_rgba(249,115,22,0.4)]" href="/sign_up">
<span class="relative z-10">Comenzar ahora</span>
<svg class="w-5 h-5 relative z-10 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
</svg>
<div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
</a>
</div>
</div>
</div>
<!-- Right Column: Interactive Mockup -->
<div class="flex-1 w-full relative flex items-center justify-center min-h-[650px] lg:min-h-0 opacity-0 animate-fade-up stagger-3">
<div class="relative w-full max-w-[600px] aspect-square" data-style="">
<!-- Glow Backgrounds -->
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-[80%] bg-accent-orange/20 rounded-full blur-[120px] opacity-20"></div>
<!-- Main Glass Dashboard Mockup -->
<div class="absolute inset-0 flex items-center justify-center animate-float">
<div class="w-full max-w-[440px] bg-slate-900/30 backdrop-blur-xl rounded-[48px] p-8 relative overflow-hidden shadow-2xl border border-white/10 transition-all duration-400 hover:border-accent-orange/40 hover:shadow-[0_0_30px_-10px_rgba(249,115,22,0.3)]">
<div class="flex justify-between items-start mb-8">
<div>
<p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Panel de Control</p>
<h3 class="text-xl font-bold">Dashboard Profesional</h3>
</div>
<div class="w-10 h-10 rounded-full bg-accent-orange/20 border border-accent-orange/30 flex items-center justify-center text-accent-orange">
<span class="material-symbols-outlined text-xl">settings</span>
</div>
</div>
<!-- QR Widget -->
<div class="relative bg-white/5 rounded-3xl p-6 border border-white/10 mb-8 overflow-hidden group">
<div class="flex items-center justify-between mb-4">
<span class="text-xs font-bold text-gray-400">QR Dinámico Válido</span>
<span class="text-[10px] px-2 py-1 bg-green-500/20 text-green-400 rounded-md font-bold uppercase">Activo</span>
</div>
<div class="aspect-square w-40 mx-auto relative rounded-xl border border-white/5 p-4 bg-slate-900/40">
<div class="w-full h-full qr-pattern opacity-40"></div>
<div class="absolute left-0 right-0 h-[2px] bg-accent-orange shadow-[0_0_15px_rgba(249,115,22,1)] animate-scan z-10"></div>
<div class="absolute inset-4 border-2 border-accent-orange/30 rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined text-accent-orange/50 text-4xl">qr_code_2</span>
</div>
</div>
</div>
<!-- Quick Stats in Mockup -->
<div class="grid grid-cols-2 gap-4">
<div class="bg-white/5 rounded-2xl p-4 border border-white/10">
<p class="text-[10px] text-gray-500 font-bold mb-1 uppercase">Presentes</p>
<div class="flex items-end gap-2">
<span class="text-2xl font-bold">142</span>
<span class="text-[10px] text-green-400 font-bold mb-1">+5%</span>
</div>
</div>
<div class="bg-white/5 rounded-2xl p-4 border border-white/10">
<p class="text-[10px] text-gray-500 font-bold mb-1 uppercase">En Campo</p>
<div class="flex items-end gap-2">
<span class="text-2xl font-bold">28</span>
<span class="text-[10px] text-accent-orange font-bold mb-1 animate-pulse">Live</span>
</div>
</div>
</div>
</div>
</div>
<!-- Floating Widget 1: Marcaje Widget (más grande, misma posición) -->
<div class="hidden lg:block absolute -top-20 -right-12 md:-right-24 glass-card rounded-2xl p-[28px] w-[380px] shadow-2xl animate-float stagger-1 border-white/20">
<div class="flex items-center gap-3 mb-3">
<div class="w-[62px] h-[62px] rounded-full bg-gradient-to-tr from-accent-orange to-amber-400 p-[1px]">
<div class="w-full h-full rounded-full bg-bg-dark flex items-center justify-center text-[17px] font-bold">MS</div>
</div>
<div>
<p class="text-[17px] font-bold text-white leading-tight">Marta Sánchez</p>
<p class="text-[15px] text-gray-400">Diseño UX/UI</p>
</div>
<div class="ml-auto w-[13px] h-[13px] rounded-full bg-green-500 animate-ping-slow"></div>
</div>
<div class="flex items-center justify-between">
<span class="text-[15px] font-bold text-green-400 bg-green-400/10 px-[13px] py-[3px] rounded border border-green-500/20">Check-in Ok</span>
<span class="text-[15px] text-gray-500 font-medium">09:00 AM</span>
</div>
</div>
<!-- Floating Widget 2: Geolocalización Widget (más grande, misma posición) -->
<div class="hidden lg:block absolute -bottom-12 -left-12 md:-left-20 glass-card rounded-3xl p-[34px] w-[405px] shadow-2xl animate-float stagger-4 border-white/20">
<div class="flex items-center gap-3 mb-3">
<div class="w-[68px] h-[68px] rounded-2xl bg-blue-500/20 flex items-center justify-center text-blue-400 border border-blue-500/20">
<span class="material-symbols-outlined text-[30px]">location_on</span>
</div>
<div>
<p class="text-[18px] font-bold text-white">Geolocalización</p>
<p class="text-[15px] text-gray-500">Rastreo en tiempo real</p>
</div>
</div>
<div class="space-y-2">
<div class="h-[10px] w-full bg-white/10 rounded-full overflow-hidden">
<div class="h-full w-[88%] bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
</div>
<div class="flex justify-between text-[15px] font-bold uppercase tracking-wider">
<span class="text-blue-400">Rango: 15m</span>
<span class="text-gray-400">Alta Precisión</span>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Metrics Bar Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-16 opacity-0 animate-fade-up stagger-4 relative z-20">
<!-- Card 1: GPS Precision (Gray) -->
<div class="bg-gray-400 rounded-[32px] p-8 flex flex-col justify-between min-h-[400px] transition-transform hover:scale-[1.02] duration-300">
<div class="flex justify-start">
<span class="text-black font-bold text-xl tracking-tight">ASIS GO+</span>
</div>
<div class="flex flex-col items-center justify-center">
<h4 class="text-6xl md:text-7xl font-black text-black leading-none mb-4">99.9%</h4>
<p class="text-black/60 font-bold uppercase tracking-widest text-sm">Precisión GPS</p>
</div>
<div class="flex justify-between items-end">
<div class="flex gap-2">
<div class="w-10 h-10 rounded-full bg-accent-orange flex items-center justify-center text-white">
<span class="material-symbols-outlined">gps_fixed</span>
</div>
<div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-black">
<span class="material-symbols-outlined">database</span>
</div>
</div>
<span class="material-symbols-outlined text-4xl text-black">north_east</span>
</div>
</div>
<!-- Card 2: Registration Time (White) -->
<div class="bg-white rounded-[32px] p-8 flex flex-col items-center text-center min-h-[400px] transition-transform hover:scale-[1.02] duration-300">
<div class="w-full flex justify-start mb-4">
<span class="text-black font-bold text-xl tracking-tight">ASIS GO+</span>
</div>
<div class="flex-1 flex flex-col items-center justify-center">
<div class="w-32 h-32 rounded-full bg-gray-100 flex items-center justify-center mb-6 overflow-hidden">
<span class="material-symbols-outlined text-6xl text-gray-300">person</span>
</div>
<h4 class="text-6xl md:text-7xl font-black text-black leading-none mb-2">&lt; 2s</h4>
<p class="text-gray-500 font-bold uppercase tracking-widest text-sm">Tiempo de registro</p>
</div>
<div class="w-full mt-4 pt-4 border-t border-gray-100">
<p class="text-black font-bold">Optimización — Global</p>
</div>
</div>
<!-- Card 3: Fraud Prevention (Vibrant Orange) -->
<div class="bg-accent-orange rounded-[32px] p-8 flex flex-col justify-between min-h-[400px] transition-transform hover:scale-[1.02] duration-300">
<div class="flex justify-between items-start">
<span class="text-white font-bold text-xl tracking-tight">ASIS GO+</span>
<span class="material-symbols-outlined text-white">verified</span>
</div>
<div class="mt-auto text-white">
<p class="text-white/80 font-bold uppercase tracking-widest text-sm mb-4">Seguridad Total</p>
<h4 class="text-7xl md:text-8xl font-black leading-none mb-6">ZERO</h4>
<div class="flex flex-col items-center">
<span class="material-symbols-outlined text-5xl mb-2">filter_vintage</span>
<p class="text-white/90 font-bold">Intento de fraude</p>
</div>
<div class="flex justify-between items-center mt-8 pt-4 border-t border-white/20">
<span class="text-xs font-bold">Global Security — US</span>
<span class="material-symbols-outlined">north_east</span>
</div>
</div>
</div>
</div>
</main>
<!-- Footer & Trust -->
<footer class="relative z-20 px-8 md:px-16 py-12 flex flex-col md:flex-row justify-between items-center gap-8 border-t border-white/5 mt-12">
<div class="flex flex-col gap-4 items-center md:items-start">
<p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Confían en nosotros instituciones y organizaciones de clase mundial</p>
<div class="flex flex-wrap items-center justify-center md:justify-start gap-10 opacity-50 grayscale transition-all duration-500 hover:opacity-80">
<div class="font-black text-xl italic tracking-tighter">UNAM</div>
<div class="font-black text-xl italic tracking-tighter">U. DE BARCELONA</div>
<div class="font-black text-xl italic tracking-tighter">UBA</div>
<div class="font-black text-xl italic tracking-tighter">TEC DE MONTERREY</div>
</div>
</div>
<div class="flex flex-col items-center md:items-end gap-3">
<div class="flex -space-x-2">
<div class="w-9 h-9 rounded-full border-2 border-bg-dark bg-gray-800 flex items-center justify-center overflow-hidden">
<div class="w-full h-full bg-slate-700"></div>
</div>
<div class="w-9 h-9 rounded-full border-2 border-bg-dark bg-gray-700 flex items-center justify-center overflow-hidden">
<div class="w-full h-full bg-slate-600"></div>
</div>
<div class="w-9 h-9 rounded-full border-2 border-bg-dark bg-gray-600 flex items-center justify-center overflow-hidden">
<div class="w-full h-full bg-slate-500"></div>
</div>
<div class="w-9 h-9 rounded-full border-2 border-bg-dark bg-accent-orange flex items-center justify-center text-[10px] font-bold shadow-[0_0_15px_rgba(249,115,22,0.5)]">+500</div>
</div>
<p class="text-xs text-gray-500 font-bold">Instituciones confían en ASIS GO+</p>
</div>
</footer>



<script src="/js/home.js" defer></script>
<script src="/js/app.js" defer></script>
</body></html>
@endif

@if ($pagina === 'login')
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport">
<title>Iniciar Sesión — Asis GO+</title>
<script src="/js/login.config.js"></script><script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="/css/login.css">
<link rel="stylesheet" href="/css/inline.css">
</head>
<body class="bg-background text-on-surface min-h-screen flex items-center justify-center p-0 sm:p-4 lg:p-8" data-style="">

<div class="flex flex-col lg:flex-row w-full max-w-6xl min-h-[700px] bg-surface shadow-2xl rounded-3xl overflow-hidden border border-white/5">

  <!-- Columna Izquierda: Ilustración -->
  <div class="hidden lg:flex lg:w-1/2 bg-surface-container p-12 flex-col items-center justify-center text-center relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary rounded-full blur-[120px]"></div>
      <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-tertiary rounded-full blur-[120px]"></div>
    </div>
    <div class="relative z-10 flex flex-col items-center max-w-md">

      <!-- Ilustración SVG (escudo + candado), reemplaza el <img> roto -->
      <div class="w-full max-w-[320px] aspect-square mb-12 drop-shadow-2xl flex items-center justify-center">
        <svg viewBox="0 0 200 200" class="w-full h-full">
          <defs>
            <linearGradient id="shieldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#ffb693"/>
              <stop offset="100%" stop-color="#8bdc00"/>
            </linearGradient>
          </defs>
          <!-- líneas tipo "circuito" de fondo -->
          <g stroke="url(#shieldGrad)" stroke-width="0.6" opacity="0.35" fill="none">
            <path d="M20 60 H55 M20 60 V75 M145 55 H180 M145 55 V40 M25 140 H60 M25 140 V125 M140 145 H175 M140 145 V160"/>
            <circle cx="20" cy="60" r="2" fill="#ffb693"/>
            <circle cx="180" cy="55" r="2" fill="#8bdc00"/>
            <circle cx="25" cy="140" r="2" fill="#ffb693"/>
            <circle cx="175" cy="145" r="2" fill="#8bdc00"/>
          </g>
          <!-- escudo -->
          <path d="M100 20 L165 45 V95 C165 135 137 165 100 180 C63 165 35 135 35 95 V45 Z"
                fill="none" stroke="url(#shieldGrad)" stroke-width="3.5" stroke-linejoin="round"/>
          <path d="M100 32 L152 52 V94 C152 127 129 152 100 165 C71 152 48 127 48 94 V52 Z"
                fill="none" stroke="url(#shieldGrad)" stroke-width="1.5" opacity="0.5" stroke-linejoin="round"/>
          <!-- candado -->
          <rect x="80" y="95" width="40" height="32" rx="6" fill="none" stroke="url(#shieldGrad)" stroke-width="3.5"/>
          <path d="M87 95 V80 a13 13 0 0 1 26 0 V95" fill="none" stroke="url(#shieldGrad)" stroke-width="3.5"/>
          <circle cx="100" cy="108" r="4.5" fill="url(#shieldGrad)"/>
          <line x1="100" y1="112" x2="100" y2="119" stroke="url(#shieldGrad)" stroke-width="3.5" stroke-linecap="round"/>
        </svg>
      </div>

      <h2 class="text-3xl font-bold text-on-surface mb-4 tracking-tight">Gestión Inteligente</h2>
      <p class="text-body-md text-on-surface-variant">Optimiza el seguimiento de asistencia con tecnología de vanguardia. Precisión, seguridad y simplicidad en un solo lugar.</p>
    </div>
  </div>

  <!-- Columna Derecha: Formulario -->
  <div class="flex-1 bg-background flex flex-col items-center justify-center p-8 sm:p-12 lg:p-20">
    <div class="w-full max-w-sm flex flex-col">

      <!-- Logo SVG (reemplaza el <img> roto) -->
      <div class="mb-10 flex items-center justify-center lg:justify-start gap-3">
        <div class="w-11 h-11 rounded-xl bg-surface-container-high border border-outline-variant/20 flex items-center justify-center flex-shrink-0">
          <svg viewBox="0 0 24 24" class="w-6 h-6">
            <path d="M12 2 L21 20 H3 Z" fill="none" stroke="#ffb693" stroke-width="2" stroke-linejoin="round"/>
            <path d="M12 9 L17 20 H7 Z" fill="none" stroke="#8bdc00" stroke-width="1.6" stroke-linejoin="round"/>
          </svg>
        </div>
        <span class="text-lg font-bold tracking-tight text-on-surface">Asis <span class="text-primary">GO+</span></span>
      </div>

      <div class="mb-10 text-center lg:text-left">
        <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-on-surface mb-2">Bienvenido de nuevo</h1>
        <p class="text-body-sm text-on-surface-variant">Ingresa tus credenciales para acceder al sistema.</p>
      </div>

      <form class="flex flex-col gap-6" id="formulario-login" data-submit="handleLogin">

        <!-- Usuario -->
        <div class="flex flex-col gap-2">
          <label class="text-label-caps text-on-surface-variant uppercase ml-1" for="usuario">Usuario</label>
          <div class="relative group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">person</span>
            <input id="usuario" type="text" required placeholder="Ingresa tu usuario"
              class="w-full bg-surface-container border border-outline-variant/30 text-on-surface rounded-xl pl-12 pr-4 py-4 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-on-surface-variant/30">
          </div>
        </div>

        <!-- Contraseña -->
        <div class="flex flex-col gap-2">
          <div class="flex justify-between items-center px-1">
            <label class="text-label-caps text-on-surface-variant uppercase" for="contrasena">Contraseña</label>
            <a class="text-label-bold text-primary hover:text-primary-fixed transition-colors text-xs" href="#">¿Olvidaste tu contraseña?</a>
          </div>
          <div class="relative group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">lock</span>
            <input id="contrasena" type="password" required placeholder="••••••••"
              class="w-full bg-surface-container border border-outline-variant/30 text-on-surface rounded-xl pl-12 pr-12 py-4 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-on-surface-variant/30">
            <button type="button" data-action="togglePass" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors p-2">
              <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
            </button>
          </div>
        </div>

        <!-- Options -->
        <div class="flex items-center justify-between mt-1">
          <label class="flex items-center gap-3 cursor-pointer group">
            <div class="w-5 h-5 rounded border border-outline-variant bg-surface-container flex items-center justify-center group-hover:border-primary transition-colors relative overflow-hidden">
              <input type="checkbox" checked class="opacity-0 absolute inset-0 cursor-pointer peer">
              <div class="absolute inset-0 bg-primary opacity-0 peer-checked:opacity-100 transition-opacity flex items-center justify-center">
                <span class="material-symbols-outlined text-[14px] text-on-primary font-bold">check</span>
              </div>
            </div>
            <span class="text-body-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Recordarme</span>
          </label>
        </div>

        <!-- Mensaje error -->
        <p id="msg-error" class="text-sm font-semibold text-center text-error min-h-[18px]"></p>

        <!-- Submit -->
        <button type="submit" id="btn-submit"
          class="w-full h-14 bg-primary text-on-primary rounded-xl font-label-bold text-label-bold flex items-center justify-center gap-2 hover:bg-primary-fixed hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-primary/20 mt-1">
          <span id="btn-text">Iniciar Sesión</span>
          <span class="material-symbols-outlined text-[20px]" id="btn-icon" data-style="">login</span>
          <span id="btn-loader" class="hidden"><span class="material-symbols-outlined animate-spin">progress_activity</span></span>
        </button>
      </form>

      <!-- Footer -->
      <div class="mt-10 text-center lg:text-left">
        <p class="font-body-sm text-body-sm text-on-surface-variant">
          ¿No tienes cuenta?
          <a href="/sign_up" class="text-primary hover:text-primary-fixed transition-colors ml-1 font-semibold">Regístrate aquí</a>
        </p>
        <div class="mt-6 flex items-center gap-4 text-xs text-on-surface-variant/40 justify-center lg:justify-start">
          <span>Asis GO+ v2.4</span>
          <span class="w-1 h-1 bg-on-surface-variant/20 rounded-full"></span>
          <a class="hover:text-on-surface transition-colors" href="/">Página principal</a>
        </div>
      </div>

    </div>
  </div>

</div>


<script src="/js/login.js" defer></script>
<script src="/js/app.js" defer></script>
</body>
</html>
@endif

@if ($pagina === 'registro')
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport">
<title>Registro — Asis GO+</title>
<script src="/js/signup.config.js"></script><script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="/css/signup.css">
<link rel="stylesheet" href="/css/inline.css">
</head>
<body class="bg-background text-on-surface overflow-x-hidden" data-style="">
<main class="flex min-h-screen w-full">

  <!-- Left Side: Immersive Visual -->
  <section class="hidden lg:flex relative w-1/2 h-screen overflow-hidden sticky top-0">
    <img alt="Asis GO+ Visual" class="absolute inset-0 w-full h-full object-cover"
         src="https://lh3.googleusercontent.com/aida-public/AB6AXuD1WLDqJZVZYW6t5TXro6c-zkVBCdElKygfcVbRfhkis1IMOIQJuXXPNkCLpOhcFmMwFR5-Gab-qsye92sK08Jzn0uCSNSu_J95vZvzNuMYpq7SpphB98ewA-2Df-YzCAngzzoPet8jSVn8EC_PnGVNhoM-b2TmFx2hnAE5yZDdn_Wl_-LnjlNdLzNe6XZkrlf4wzi8yrSfaDCYVIuzQe1JKjijCb4bydb1K6Ka2ZsAsx8Nz-yzD48CZQ">
    <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent opacity-80"></div>
    <div class="absolute bottom-0 left-0 w-full p-16 animate-fade-in">
      <h2 class="font-headline-xl text-5xl font-bold mb-4 bg-gradient-to-r from-white to-primary bg-clip-text text-transparent tracking-tight">Gestión Inteligente de Asistencia</h2>
      <p class="font-body-md text-lg text-on-surface/80 max-w-lg leading-relaxed">
        Optimiza el control académico con tecnología de vanguardia. Diseñado para administradores que buscan precisión y eficiencia en tiempo real.
      </p>
    </div>
  </section>

  <!-- Right Side: Signup Form -->
  <section class="flex flex-col items-center justify-center w-full lg:w-1/2 min-h-screen p-6 md:p-12 lg:p-24 bg-background">
    <div class="w-full max-w-md animate-fade-in">

      <!-- Mobile Logo -->
      <div class="lg:hidden flex flex-col items-center text-center mb-10">
        <div class="w-16 h-16 rounded-2xl bg-surface-container-high flex items-center justify-center shadow-lg relative overflow-hidden mb-4">
          <div class="absolute inset-0 bg-primary/20 blur-xl rounded-full"></div>
          <span class="material-symbols-outlined text-4xl text-primary relative z-10" data-style="">school</span>
        </div>
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Crear Cuenta</h1>
      </div>

      <div class="hidden lg:block mb-10">
        <h1 class="text-4xl font-bold text-on-surface mb-2">Crear Cuenta</h1>
        <p class="text-on-surface-variant">Configura tu perfil de administrador para comenzar.</p>
      </div>

      <!-- Form -->
      <form class="flex flex-col gap-5 w-full" id="formulario-registro" data-submit="handleSignup">

        <!-- Nombre -->
        <div class="flex flex-col gap-1.5">
          <label class="font-label-caps text-[10px] text-on-surface-variant/70 uppercase tracking-widest px-1">Nombre Completo</label>
          <div class="relative group">
            <input id="nombre" type="text" required placeholder="Ej. María García"
              class="w-full h-12 bg-surface-container rounded-xl px-5 font-body-md text-body-md text-on-surface border border-transparent placeholder:text-on-surface-variant/30 focus:outline-none focus:border-primary/50 focus:bg-surface-container-high transition-all">
            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-[20px]">person</span>
          </div>
        </div>

        <!-- Correo -->
        <div class="flex flex-col gap-1.5">
          <label class="font-label-caps text-[10px] text-on-surface-variant/70 uppercase tracking-widest px-1">Correo Electrónico</label>
          <div class="relative group">
            <input id="correo" type="email" required placeholder="usuario@universidad.edu"
              class="w-full h-12 bg-surface-container rounded-xl px-5 font-body-md text-body-md text-on-surface border border-transparent placeholder:text-on-surface-variant/30 focus:outline-none focus:border-primary/50 focus:bg-surface-container-high transition-all">
            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-[20px]">mail</span>
          </div>
        </div>

        <!-- Teléfono -->
        <div class="flex flex-col gap-1.5">
          <label class="font-label-caps text-[10px] text-on-surface-variant/70 uppercase tracking-widest px-1">Teléfono</label>
          <div class="relative group">
            <input id="telefono" type="text" required placeholder="+504 9999-9999"
              class="w-full h-12 bg-surface-container rounded-xl px-5 font-body-md text-body-md text-on-surface border border-transparent placeholder:text-on-surface-variant/30 focus:outline-none focus:border-primary/50 focus:bg-surface-container-high transition-all">
            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-[20px]">call</span>
          </div>
        </div>

        <!-- Usuario + Contraseña en grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Usuario -->
          <div class="flex flex-col gap-1.5">
            <label class="font-label-caps text-[10px] text-on-surface-variant/70 uppercase tracking-widest px-1">Usuario</label>
            <div class="relative group">
              <input id="usuario" type="text" required placeholder="mi_usuario"
                class="w-full h-12 bg-surface-container rounded-xl px-5 font-body-sm text-body-sm text-on-surface border border-transparent placeholder:text-on-surface-variant/30 focus:outline-none focus:border-primary/50 focus:bg-surface-container-high transition-all">
              <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-[20px]">alternate_email</span>
            </div>
          </div>

          <!-- Contraseña -->
          <div class="flex flex-col gap-1.5">
            <label class="font-label-caps text-[10px] text-on-surface-variant/70 uppercase tracking-widest px-1">Contraseña</label>
            <div class="relative group">
              <input id="contrasena" type="password" required placeholder="••••••••"
                class="w-full h-12 bg-surface-container rounded-xl px-5 font-body-sm text-body-sm text-on-surface border border-transparent placeholder:text-on-surface-variant/30 focus:outline-none focus:border-primary/50 focus:bg-surface-container-high transition-all">
              <button type="button" data-action="togglePass"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Mensaje error -->
        <p id="msg-error" class="text-sm font-semibold text-center text-error min-h-[18px] mt-1"></p>

        <!-- Submit -->
        <button type="submit" id="btn-submit"
          class="w-full h-14 mt-2 bg-primary text-on-primary rounded-xl font-label-bold text-label-bold flex items-center justify-center gap-2 hover:brightness-110 active:scale-[0.98] transition-all shadow-xl shadow-primary/10">
          <span id="btn-text">Crear cuenta</span>
          <span class="material-symbols-outlined text-sm" id="btn-icon" data-style="">arrow_forward</span>
          <span id="btn-loader" class="hidden"><span class="material-symbols-outlined animate-spin">progress_activity</span></span>
        </button>

        <p class="font-body-sm text-body-sm text-on-surface-variant text-center mt-4">
          ¿Ya tienes cuenta?
          <a href="/iniciar_sesion" class="text-primary hover:text-primary-fixed transition-colors ml-1 font-semibold">Inicia sesión</a>
        </p>
      </form>

      <!-- Success State -->
      <div id="success-state" class="hidden flex-col items-center justify-center text-center py-12 animate-fade-in glass-card rounded-3xl p-10">
        <div class="w-20 h-20 rounded-full bg-tertiary/10 border border-tertiary/20 flex items-center justify-center mb-6">
          <span class="material-symbols-outlined text-5xl text-tertiary" data-style="">check_circle</span>
        </div>
        <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mb-3">¡Registro completado!</h2>
        <p class="font-body-md text-lg text-on-surface/80 max-w-lg leading-relaxed">
          Tu cuenta ha sido creada con éxito. Redirigiendo al panel de control...
        </p>
      </div>

    </div>
  </section>
</main>


<script src="/js/signup.js" defer></script>
<script src="/js/app.js" defer></script>
</body>
</html>
@endif

@if ($pagina === 'interface')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel — Asis GO+</title>
    <script src="/js/interface.config.js"></script><script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet"/>
    
<link rel="stylesheet" href="/css/interface.css">
<link rel="stylesheet" href="/css/inline.css">
</head>
<body class="bg-background text-on-background min-h-screen">

<!-- Header -->
<header class="bg-surface/80 backdrop-blur-xl border-b border-outline-variant/10 px-6 md:px-10 h-16 flex items-center justify-between sticky top-0 z-40">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-primary-fixed rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-on-primary-fixed" data-style="">rocket_launch</span>
        </div>
        <span class="font-black tracking-tighter text-lg">Asis <span class="text-primary">GO+</span></span>
    </div>
    <nav class="flex items-center gap-4 text-sm">
        <a href="/workspace" class="flex items-center gap-1.5 text-primary font-bold hover:underline underline-offset-4">
            <span class="material-symbols-outlined text-base">grid_view</span> Mis clases
        </a>
        <a href="#" data-action="cerrarSesion" class="flex items-center gap-1.5 text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
            <span class="material-symbols-outlined text-base">logout</span> Salir
        </a>
    </nav>
</header>

<main class="max-w-2xl mx-auto px-6 py-12">

    <!-- Title -->
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-3">
            <div class="h-0.5 w-8 bg-primary rounded-full"></div>
            <span class="text-xs font-bold text-primary tracking-widest uppercase">Panel del Profesor</span>
        </div>
        <h1 class="text-4xl font-black tracking-tighter text-on-background">Crear Nueva Clase</h1>
        <p class="text-on-surface-variant text-sm mt-2">Registra una nueva materia con su sección y horario.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-surface-container rounded-2xl p-8 shadow-xl relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>

        <form id="formulario-materias" class="flex flex-col gap-5">

            <!-- Nombre -->
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Nombre de la Clase</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">school</span>
                    <input id="nombre" type="text" placeholder="Ej. Cálculo Diferencial" required
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
                </div>
            </div>

            <!-- Sección -->
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Sección</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">tag</span>
                    <input id="seccion" type="text" placeholder="Ej. 0801" required
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
                </div>
            </div>

            <!-- Día de la semana -->
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Día de la Semana</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">calendar_today</span>
                    <select id="dia_semana" required
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all appearance-none cursor-pointer">
                        <option value="" disabled selected>Selecciona un día</option>
                        <option value="0">Lunes</option>
                        <option value="1">Martes</option>
                        <option value="2">Miércoles</option>
                        <option value="3">Jueves</option>
                        <option value="4">Viernes</option>
                        <option value="5">Sábado</option>
                        <option value="6">Domingo</option>
                    </select>
                </div>
            </div>

            <!-- Hora inicio / Hora fin -->
            <div class="grid grid-cols-2 gap-3">
                <div class="group">
                    <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Hora Inicio</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">schedule</span>
                        <input id="hora_inicio" type="time" required
                            class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all [color-scheme:dark]"/>
                    </div>
                </div>
                <div class="group">
                    <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Hora Fin</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">schedule</span>
                        <input id="hora_fin" type="time" required
                            class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all [color-scheme:dark]"/>
                    </div>
                </div>
            </div>

            <!-- Fecha de inicio del periodo -->
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Fecha de Inicio del Periodo</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">event</span>
                    <input id="fecha_inicio_periodo" type="date" required
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all [color-scheme:dark]"/>
                </div>
            </div>

            <!-- Semanas de duración -->
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Semanas de Duración</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">date_range</span>
                    <input id="semanas_duracion" type="number" min="1" max="52" value="11" required
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
                </div>
            </div>

            <div id="creado" class="text-sm font-semibold text-center min-h-[18px]"></div>

            <button type="submit" id="btn-crear"
                class="w-full bg-primary-fixed text-on-primary-fixed font-bold text-sm py-4 rounded-xl flex items-center justify-center gap-2 hover:bg-primary transition-all active:scale-95 shadow-md shadow-primary/20 tracking-widest uppercase mt-1">
                <span class="material-symbols-outlined text-lg">add_circle</span>
                Registrar Clase
            </button>
        </form>
    </div>

    <!-- Link to workspace -->
    <div class="mt-6 text-center">
        <a href="/workspace" class="inline-flex items-center gap-2 text-sm text-primary font-bold hover:underline underline-offset-4">
            <span class="material-symbols-outlined text-base">grid_view</span>
            Ver mis clases registradas
        </a>
    </div>
</main>


<script src="/js/interface.js" defer></script>
<script src="/js/app.js" defer></script>
</body>
</html>
@endif

@if ($pagina === 'workspace')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Clases — Asis GO+</title>
    <script src="/js/workspace.config.js"></script><script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@100..900&family=Inter:wght@100..900&family=JetBrains+Mono:wght@400..700&display=swap" rel="stylesheet"/>
    
<link rel="stylesheet" href="/css/workspace.css">
<link rel="stylesheet" href="/css/inline.css">
</head>
<body class="bg-background text-on-background min-h-screen">

<!-- ── HEADER ─────────────────────────────────── -->
<header class="bg-surface/80 backdrop-blur-xl border-b border-outline-variant/10 px-6 md:px-10 h-16 flex items-center justify-between sticky top-0 z-40">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-on-primary" data-style="">school</span>
        </div>
        <span class="font-display font-bold tracking-tight text-lg">Asis <span class="text-primary">GO+</span></span>
    </div>
    <nav class="flex items-center gap-4 text-sm">
        <a href="/interface" class="flex items-center gap-1.5 text-on-surface-variant hover:text-primary transition-colors font-semibold">
            <span class="material-symbols-outlined text-base">add_circle</span> Nueva clase
        </a>
        <a href="#" data-action="cerrarSesion" class="flex items-center gap-1.5 text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
            <span class="material-symbols-outlined text-base">logout</span> Salir
        </a>
    </nav>
</header>

<div class="w-full px-6 md:px-10 py-10 min-h-screen max-w-[1440px] mx-auto">

<!-- ════ VISTA LISTA ════ -->
<div id="vista-lista" class="max-w-7xl mx-auto">
    <div class="flex flex-col w-full mb-12">
        <div class="flex items-center gap-3 mb-2">
            <div class="h-1 w-8 bg-primary rounded-full"></div>
            <span class="font-mono-caps text-xs font-bold text-primary tracking-widest uppercase">Profesor</span>
        </div>
        <h1 class="font-display text-4xl md:text-5xl font-bold tracking-tight text-on-background mb-3">Mis Clases</h1>
        <p class="text-on-surface-variant text-base max-w-2xl">
            Bienvenido de nuevo. Gestiona tu asistencia y contenido educativo desde un solo panel.
        </p>
    </div>
    <div id="contenedor-materias"><p class="text-on-surface-variant text-sm">Cargando clases...</p></div>
</div>

<!-- ════ VISTA DETALLE (bento grid estilo Stitch) ════ -->
<div id="vista-detalle" data-style="">
    <button data-action="volverALista" class="flex items-center gap-2 text-sm text-on-surface-variant hover:text-on-surface transition-colors font-semibold mb-8 group">
        <span class="material-symbols-outlined text-base group-hover:-translate-x-1 transition-transform">arrow_back</span> Volver a mis clases
    </button>

    <!-- Encabezado de la clase -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 card-in" data-style="">
        <div class="flex flex-col items-start gap-1">
            <div class="flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-[18px]">school</span>
                <span class="font-mono-caps text-[10px] tracking-widest opacity-80">ASIGNATURA</span>
            </div>
            <h1 id="titulo-clase" class="font-display text-4xl sm:text-5xl font-bold text-on-surface mb-1"></h1>
            <p id="link-inscripcion" class="font-mono-caps text-[11px] text-on-surface-variant tracking-normal lowercase bg-surface-container-high/50 py-2 px-4 rounded-full border border-white/5 mt-1"></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">

        <!-- ── 1. QR (span 4) ── -->
        <div class="col-span-1 lg:col-span-4 flex flex-col glass-card p-6 card-in" data-style="">
            <div class="flex justify-between items-start mb-4">
                <h2 class="font-title text-title-md text-on-surface font-semibold">Código de Asistencia</h2>
            </div>
            <p class="text-xs text-on-surface-variant mb-4 -mt-2">Muestra el QR al inicio de la clase. Se renueva cada 15 segundos.</p>

            <div class="flex-1 flex flex-col items-center justify-center bg-surface/30 rounded-xl mb-5 p-4 border border-outline-variant/10 relative overflow-hidden min-h-[200px]">
                <div class="bg-white rounded-2xl p-3 inline-block shadow-lg" id="qr-inline-wrapper" data-style="">
                    <div id="contenedor-qr-inline"></div>
                </div>
                <div id="qr-placeholder" class="flex flex-col items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl opacity-30">qr_code_2</span>
                    <span class="text-xs opacity-60">Presiona "Generar QR"</span>
                </div>
            </div>

            <p id="estado-ubicacion-aula" class="text-xs mb-3 min-h-[16px] text-on-surface-variant">Presiona aquí para establecer la ubicación</p>

            <div class="flex gap-3 mt-auto">
                <button data-action="abrirModalQR"
                    class="flex-1 bg-primary/90 border border-primary text-on-primary font-body font-bold text-sm py-3 rounded-xl hover:bg-primary active:scale-[0.98] transition-all shadow-[0_4px_15px_rgba(255,182,147,0.2)]">
                    Generar QR
                </button>
                <button data-action="configurarUbicacionAula" aria-label="Config. Aula" title="Config. Aula — presiona aquí para establecer la ubicación"
                    class="w-12 border border-white/10 bg-white/5 rounded-xl text-on-surface hover:bg-white/10 active:scale-[0.98] transition-all flex items-center justify-center">
                    <span class="material-symbols-outlined">location_on</span>
                </button>
            </div>
        </div>

        <!-- ── 2. Asistencia en Vivo (span 8) ── -->
        <div id="seccion-vivo" data-style="" class="col-span-1 lg:col-span-8 flex flex-col glass-card p-6 card-in" data-style="">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
                <h2 class="font-title text-title-md text-on-surface font-semibold flex items-center gap-2">
                    Asistencia en Vivo
                </h2>
                <div class="bg-error/10 text-error py-1 px-3 rounded-full flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-error animate-pulse"></span>
                    <span class="font-mono-caps text-[10px] tracking-widest">CLASE EN CURSO</span>
                </div>
            </div>
            <p class="text-xs text-on-surface-variant mb-4">Se actualiza cada 10 s. Desaparece 3 horas después del primer QR.</p>
            <div id="contenedor-vivo" class="flex-1"><p class="text-sm text-on-surface-variant">Cargando...</p></div>
        </div>

        <!-- ── 3. Estudiantes (span 8) ── -->
        <div class="col-span-1 lg:col-span-8 flex flex-col glass-card p-6 card-in" data-style="">
            <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
                <h2 class="font-title text-title-md text-on-surface font-semibold">Estudiantes Inscritos</h2>
                <button data-action="abrirModalAgregar"
                    class="w-10 h-10 rounded-full bg-primary/10 border border-primary/10 text-primary hover:bg-primary/20 flex items-center justify-center transition-colors active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                </button>
            </div>
            <div id="contenedor-tabla" class="overflow-x-auto rounded-xl border border-white/5 bg-surface/20 mb-4"><p class="text-sm text-on-surface-variant p-4">Cargando...</p></div>
        </div>

        <!-- ── 4. Grabación (span 4) ── -->
        <div class="col-span-1 lg:col-span-4 flex flex-col bg-primary-fixed/70 backdrop-blur-md border border-white/20 rounded-[2rem] p-8 relative overflow-hidden card-in min-h-[320px]" data-style="">
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <h2 class="font-display text-3xl font-bold text-on-primary-fixed leading-tight mb-3 tracking-tight">Publicar<br>Grabación</h2>
                    <p class="text-sm text-on-primary-fixed-variant leading-relaxed">Pega el URL de Teams, Drive u OneDrive. Se enviará por correo a todos los estudiantes.</p>
                </div>
                <div class="flex flex-col gap-3 mt-6">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-primary-fixed-variant/60 text-[20px]">link</span>
                        <input type="text" id="url-grabacion" placeholder="https://..."
                            class="w-full bg-white/30 backdrop-blur-sm border border-white/40 rounded-xl py-3.5 pl-12 pr-4 text-sm text-on-primary-fixed placeholder:text-on-primary-fixed-variant/70 outline-none focus:ring-2 focus:ring-on-primary-fixed/20 transition-all"/>
                    </div>
                    <button data-action="publicarGrabacion"
                        class="w-full bg-white/90 border border-white text-on-primary-fixed font-title font-bold text-sm py-3.5 rounded-xl hover:bg-white active:scale-[0.98] transition-all flex justify-center items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">send</span> Enviar a Clase
                    </button>
                </div>
                <p id="estado-grabacion" class="text-xs font-semibold mt-2 min-h-[16px] text-on-primary-fixed"></p>
            </div>
        </div>
    </div>
</div><!-- /vista-detalle -->

</div><!-- /container -->


<!-- ════ MODAL QR ════ -->
<div id="modal-qr" class="modal-overlay">
    <div class="modal-card bg-surface-container border border-primary/20 rounded-3xl p-8 text-center w-full max-w-xs shadow-2xl mx-4">
        <h3 class="font-display text-xl font-bold tracking-tighter text-on-background mb-1">Código QR</h3>
        <p class="text-xs text-on-surface-variant mb-5">Estudiantes: escanea para marcar asistencia</p>
        <div class="bg-white rounded-2xl p-3 inline-block shadow-lg">
            <div id="contenedor-qr"></div>
        </div>
        <p id="contador-qr" class="text-xs text-on-surface-variant mt-3"></p>
        <div class="flex gap-2 mt-5 justify-center">
            <button data-action="abrirVistGrande"
                class="flex items-center gap-1.5 bg-primary/15 text-primary border border-primary/30 font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-primary/25 transition-all active:scale-95">
                <span class="material-symbols-outlined text-base">open_in_new</span> Vista grande
            </button>
            <button data-action="cerrarModalQR"
                class="flex items-center gap-1.5 bg-surface-container-high text-on-surface-variant font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-outline-variant/20 transition-all active:scale-95 border border-outline-variant/20">
                <span class="material-symbols-outlined text-base">close</span> Cerrar
            </button>
        </div>
    </div>
</div>


<!-- ════ MODAL AJUSTAR FALTAS ════ -->
<div id="modal-faltas" class="modal-overlay">
    <div class="modal-card bg-surface-container border border-outline-variant/20 rounded-3xl p-7 w-full max-w-sm shadow-2xl mx-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
        <h3 class="font-display text-xl font-bold tracking-tighter text-on-background mb-1">Ajustar Faltas</h3>
        <p id="modal-faltas-nombre" class="text-sm text-on-surface-variant mb-5"></p>
        <input type="hidden" id="faltas-id-estudiante">
        <div class="flex flex-col gap-3">
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 tracking-widest uppercase group-focus-within:text-primary transition-colors">
                    Número de faltas totales
                </label>
                <input id="faltas-cantidad" type="number" min="0" max="99" placeholder="0"
                    class="w-full bg-surface-container-low rounded-xl py-3 px-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all"/>
            </div>
            <p class="text-xs text-on-surface-variant">El sistema redistribuirá las faltas hacia las clases más recientes del periodo.</p>
        </div>
        <p id="modal-faltas-msg" class="text-xs font-semibold text-center mt-3 min-h-[16px]"></p>
        <div class="flex gap-2 mt-4">
            <button id="btn-guardar-faltas" data-action="guardarFaltas"
                class="flex-1 bg-primary text-on-primary font-bold text-xs py-3.5 rounded-xl hover:opacity-90 transition-all active:scale-95 tracking-widest uppercase shadow-md shadow-primary/20">
                Guardar
            </button>
            <button data-action="cerrar-faltas"
                class="flex-1 bg-surface-container-high text-on-surface-variant font-bold text-xs py-3.5 rounded-xl hover:bg-outline-variant/20 transition-all active:scale-95 border border-outline-variant/20">
                Cancelar
            </button>
        </div>
    </div>
</div>

<!-- ════ MODAL FORM ESTUDIANTE ════ -->
<div id="modal-form" class="modal-overlay">
    <div class="modal-card bg-surface-container border border-outline-variant/20 rounded-3xl p-7 w-full max-w-md shadow-2xl mx-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
        <h3 id="modal-form-titulo" class="font-display text-xl font-bold tracking-tighter text-on-background mb-5">Agregar Estudiante</h3>
        <input type="hidden" id="form-id-estudiante">
        <div class="flex flex-col gap-3">
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 tracking-widest uppercase group-focus-within:text-primary transition-colors">Nombre</label>
                <input id="form-nombre" type="text" placeholder="Nombre completo"
                    class="w-full bg-surface-container-low rounded-xl py-3 px-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
            </div>
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 tracking-widest uppercase group-focus-within:text-primary transition-colors">Correo</label>
                <input id="form-correo" type="email" placeholder="correo@universidad.edu"
                    class="w-full bg-surface-container-low rounded-xl py-3 px-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
            </div>
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 tracking-widest uppercase group-focus-within:text-primary transition-colors">Teléfono</label>
                <input id="form-telefono" type="text" placeholder="+504 9999-9999"
                    class="w-full bg-surface-container-low rounded-xl py-3 px-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
            </div>
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 tracking-widest uppercase group-focus-within:text-primary transition-colors">Número de Cuenta</label>
                <input id="form-cuenta" type="text" placeholder="20210001234"
                    class="w-full bg-surface-container-low rounded-xl py-3 px-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
            </div>
            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 tracking-widest uppercase group-focus-within:text-primary transition-colors">Modalidad</label>
                <select id="form-modalidad"
                    class="w-full bg-surface-container-low rounded-xl py-3 px-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all appearance-none cursor-pointer">
                    <option value="presencial">Presencial</option>
                    <option value="virtual">Virtual</option>
                </select>
            </div>
        </div>
        <p id="modal-form-mensaje" class="text-xs font-semibold text-center mt-3 min-h-[16px]"></p>
        <div class="flex gap-2 mt-4">
            <button data-action="guardarEstudiante"
                class="flex-1 bg-primary text-on-primary font-bold text-xs py-3.5 rounded-xl hover:opacity-90 transition-all active:scale-95 tracking-widest uppercase shadow-md shadow-primary/20">
                Guardar
            </button>
            <button data-action="cerrarModalForm"
                class="flex-1 bg-surface-container-high text-on-surface-variant font-bold text-xs py-3.5 rounded-xl hover:bg-outline-variant/20 transition-all active:scale-95 border border-outline-variant/20">
                Cancelar
            </button>
        </div>
    </div>
</div>



<script src="/js/workspace.js" defer></script>
<script src="/js/app.js" defer></script>
</body>
</html>
@endif

@if ($pagina === 'inscripcion')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción — Asis GO+</title>
    <script src="/js/inscripcion.config.js"></script><script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet"/>
    
<link rel="stylesheet" href="/css/inscripcion.css">
<link rel="stylesheet" href="/css/inline.css">
</head>
<body class="bg-background text-on-background min-h-screen flex items-center justify-center p-4">

<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
    <div class="absolute top-1/4 right-1/4 w-80 h-80 bg-primary opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 left-1/4 w-60 h-60 bg-primary opacity-5 rounded-full blur-3xl"></div>
</div>

<main class="relative z-10 w-full max-w-md py-8">

    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-14 h-14 bg-primary-fixed rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-on-primary-fixed" data-style="">how_to_reg</span>
        </div>
        <h1 class="text-3xl font-black tracking-tighter text-on-background">Inscríbete a la clase</h1>
        <p class="text-sm text-on-surface-variant mt-2">
            Sección: <span id="mostrar-seccion" class="text-primary font-bold"></span>
        </p>
    </div>

    <!-- Card -->
    <div class="bg-surface-container rounded-2xl p-6 shadow-xl relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>

        <form id="formulario-inscripcion" class="flex flex-col gap-4">

            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Tu Nombre</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">person</span>
                    <input id="nombre" type="text" required placeholder="Nombre completo"
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
                </div>
            </div>

            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Tu Correo</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">mail</span>
                    <input id="correo" type="email" required placeholder="correo@universidad.edu"
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
                </div>
            </div>

            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Tu Teléfono</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">call</span>
                    <input id="telefono" type="text" required placeholder="+504 9999-9999"
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
                </div>
            </div>

            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Número de Cuenta</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">badge</span>
                    <input id="numero_cuenta" type="text" required placeholder="20210001234"
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
                </div>
            </div>

            <div class="group">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Modalidad</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">devices</span>
                    <select id="modalidad" required
                        class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all appearance-none cursor-pointer">
                        <option value="">-- Selecciona --</option>
                        <option value="presencial">Presencial</option>
                        <option value="virtual">Virtual</option>
                    </select>
                </div>
            </div>

            <p id="mensaje" class="text-sm font-semibold text-center min-h-[18px]"></p>

            <button type="submit" id="btn-inscribir"
                class="w-full bg-primary-fixed text-on-primary-fixed font-bold text-sm py-4 rounded-xl flex items-center justify-center gap-2 hover:bg-primary transition-all active:scale-95 shadow-md shadow-primary/20 tracking-widest uppercase mt-1">
                <span class="material-symbols-outlined text-lg">how_to_reg</span>
                Inscribirme
            </button>
        </form>
    </div>
</main>


<script src="/js/inscripcion.js" defer></script>
<script src="/js/app.js" defer></script>
</body>
</html>
@endif

@if ($pagina === 'marcar')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcar Asistencia — Asis GO+</title>
    <script src="/js/marcar_asistencia.config.js"></script><script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet"/>
    
<link rel="stylesheet" href="/css/marcar_asistencia.css">
<link rel="stylesheet" href="/css/inline.css">
</head>
<body data-id-materia="{{ $id_materia }}" data-token="{{ $token }}" class="bg-background text-on-background min-h-screen flex items-center justify-center p-4">

<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
    <div class="absolute top-1/3 right-1/3 w-72 h-72 bg-primary opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/3 left-1/3 w-48 h-48 bg-primary opacity-5 rounded-full blur-3xl"></div>
</div>

<main class="relative z-10 w-full max-w-sm py-8">

    <!-- Logo -->
    <div class="text-center mb-8">
        <span class="text-xl font-black tracking-tighter">Asis <span class="text-primary">GO+</span></span>
    </div>

    <!-- Location status -->
    <div id="estado-ubicacion-card" class="bg-surface-container rounded-2xl p-5 mb-4 flex items-center gap-4 border border-outline-variant/20">
        <div class="relative flex-shrink-0">
            <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-xl" id="icono-ubicacion" data-style="">my_location</span>
            </div>
            <div class="absolute inset-0 bg-primary/20 rounded-full pulse-ring" id="pulse-anim"></div>
        </div>
        <div class="flex-1">
            <p id="estado-ubicacion" class="text-sm text-on-surface-variant">Intentando obtener ubicación...</p>
            <!-- Botón para pedir ubicación manualmente si el browser no la pidió solo -->
            <button id="btn-pedir-ubicacion" data-action="pedirUbicacionManual"
                class="mt-2 text-xs font-bold text-primary underline underline-offset-2 hover:opacity-70 transition-opacity hidden">
                Intentar de nuevo con ubicación
            </button>
        </div>
    </div>

    <!-- Form card — se muestra en cuanto se resuelve la ubicación (con o sin éxito) -->
    <div id="formulario-container" data-style="">
        <div class="bg-surface-container rounded-2xl p-6 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>

            <h1 class="text-2xl font-black tracking-tighter text-on-background mb-1">Marcar Asistencia</h1>
            <p class="text-xs text-on-surface-variant mb-5">Ingresa tu número de cuenta para registrar tu presencia.</p>

            <form id="formulario-asistencia" class="flex flex-col gap-4">
                <div class="group">
                    <label class="block text-xs font-bold text-on-surface-variant mb-1.5 tracking-widest uppercase group-focus-within:text-primary transition-colors">Número de Cuenta</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-xl group-focus-within:text-primary transition-colors">badge</span>
                        <input id="numero_cuenta" type="text" required placeholder="20210001234"
                            class="w-full bg-surface-container-low rounded-xl py-3.5 pl-11 pr-4 text-on-surface text-sm outline-none focus:ring-2 focus:ring-primary/40 transition-all placeholder:text-outline/50"/>
                    </div>
                </div>

                <p id="mensaje" class="text-sm font-semibold text-center min-h-[18px]"></p>

                <button type="submit" id="btn-marcar"
                    class="w-full bg-primary-fixed text-on-primary-fixed font-bold text-sm py-4 rounded-xl flex items-center justify-center gap-2 hover:bg-primary transition-all active:scale-95 shadow-md shadow-primary/20 tracking-widest uppercase">
                    <span class="material-symbols-outlined text-lg">how_to_reg</span>
                    Marcar Asistencia
                </button>
            </form>
        </div>
    </div>

    <!-- Success state -->
    <div id="estado-exito" data-style="" class="bg-surface-container rounded-2xl p-8 text-center shadow-xl border border-primary/20">
        <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-primary" data-style="">check_circle</span>
        </div>
        <p id="mensaje-exito" class="text-lg font-bold text-on-background"></p>
        <p class="text-sm text-on-surface-variant mt-2">Tu asistencia ha sido registrada.</p>
    </div>

</main>


<script src="/js/marcar_asistencia.js" defer></script>
<script src="/js/app.js" defer></script>
</body>
</html>
@endif

@if ($pagina === 'qr')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Asistencia — Asis GO+</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
<link rel="stylesheet" href="/css/qr_pantalla.css">
<link rel="stylesheet" href="/css/inline.css">
</head>
<body data-id-materia="{{ $id_materia }}">
    <div class="logo">Asis GO+</div>
    <p class="instruccion">Escanea el código para marcar tu asistencia</p>

    <div class="qr-wrapper" id="qr-wrapper">
        <div id="contenedor-qr"></div>
    </div>

    <div class="barra-container">
        <div class="barra-progreso" id="barra"></div>
    </div>

    <p class="contador-texto" id="contador-texto">Se renueva en 15s</p>
    <p class="aviso">El código cambia automáticamente cada 15 segundos</p>

    
<script src="/js/qr_pantalla.js" defer></script>
<script src="/js/app.js" defer></script>
</body>
</html>
@endif