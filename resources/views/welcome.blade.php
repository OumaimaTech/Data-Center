<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Center Management - Gestion Professionnelle des Ressources</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-dark: #1e293b;
            --secondary-dark: #334155;
            --accent-blue: #3b82f6;
            --accent-green: #10b981;
            --text-light: #f1f5f9;
            --text-gray: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: var(--primary-dark);
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            position: static;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            flex-wrap: nowrap;
        }

        .navbar-brand {
            flex-shrink: 0;
            white-space: nowrap;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: nowrap;
        }

        .nav-links a {
            color: var(--primary-dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--accent-green);
        }

        .btn-nav {
            padding: 0.75rem 1.5rem;
            background: var(--accent-green);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.4);
        }

        /* Hero Section avec Slider */
        .hero {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .slider {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .slide.active {
            opacity: 1;
        }

        .slide-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(10, 25, 41, 0.85) 0%, rgba(30, 58, 95, 0.75) 100%);
            display: flex;
            align-items: center;
            padding: 0 5%;
        }

        .hero-content {
            max-width: 800px;
            z-index: 10;
        }

        .hero-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--accent-green);
            border-radius: 50px;
            color: var(--accent-green);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, var(--accent-green) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .hero-content p {
            font-size: 1.3rem;
            color: #cbd5e1;
            margin-bottom: 2.5rem;
            line-height: 1.8;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .btn-hero {
            padding: 1.2rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--accent-green);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: var(--primary-dark);
            transform: translateY(-3px);
        }

        /* Slider Controls */
        .slider-nav {
            position: absolute;
            bottom: 3rem;
            left: 5%;
            display: flex;
            gap: 1rem;
            z-index: 20;
        }

        .slider-dot {
            width: 50px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 2px;
        }

        .slider-dot.active {
            background: var(--accent-green);
            width: 80px;
        }

        /* Services Section */
        .services {
            padding: 8rem 5%;
            background: white;
            position: relative;
        }

        .services::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent-green), transparent);
        }

        .section-header {
            text-align: center;
            margin-bottom: 5rem;
        }

        .section-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--accent-green);
            border-radius: 50px;
            color: var(--accent-green);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-dark);
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: var(--text-gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }

        .service-card {
            background: #f8fafc;
            padding: 2.5rem;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent-green);
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.1);
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
        }

        .service-card p {
            color: var(--text-gray);
            line-height: 1.8;
        }

        /* Stats Section */
        .stats {
            padding: 6rem 5%;
            background: #f8fafc;
            position: relative;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            text-align: center;
        }

        .stat-item {
            position: relative;
        }


        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--accent-green);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.1rem;
            color: var(--text-gray);
            font-weight: 500;
        }

        /* CTA Section */
        .cta {
            padding: 8rem 5%;
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--primary-dark) 100%);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect width="1" height="1" fill="%2310b981" opacity="0.1"/></svg>');
            background-size: 50px 50px;
            opacity: 0.1;
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .cta h2 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: white;
        }

        .cta p {
            font-size: 1.3rem;
            color: #cbd5e1;
            margin-bottom: 2.5rem;
        }

        /* Footer */
        .footer {
            background: white;
            padding: 4rem 5% 2rem;
            border-top: 1px solid var(--border-color);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            color: var(--accent-green);
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }

        .footer-section p,
        .footer-section a {
            color: var(--text-gray);
            text-decoration: none;
            display: block;
            margin-bottom: 0.8rem;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: var(--accent-green);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-gray);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem 5%;
            }

            .nav-links {
                display: none;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn-hero {
                width: 100%;
                justify-content: center;
            }

            .section-title {
                font-size: 2rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-brand">
            <a href="{{ route('home') }}" style="font-size: 1.5rem; font-weight: 700; color: var(--accent-green); text-decoration: none;">
                Data Center
            </a>
        </div>
        <div class="nav-links">
            <a href="#services">Services</a>
            <a href="#stats">Statistiques</a>
            <a href="#contact">Contact</a>
            <a href="{{ route('login') }}" class="btn-nav">Connexion</a>
        </div>
    </nav>

    <!-- Hero Section with Real Images -->
    <section class="hero">
        <div class="slider">
            <!-- Slide 1: Data Center Server Racks -->
            <div class="slide active">
                <img class="slide-bg" src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=1920&h=1080&fit=crop" alt="Data Center Server Racks">
            </div>

            <!-- Slide 2: Network Infrastructure -->
            <div class="slide">
                <img class="slide-bg" src="https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=1920&h=1080&fit=crop" alt="Network Infrastructure">
            </div>

            <!-- Slide 3: Server Room -->
            <div class="slide">
                <img class="slide-bg" src="https://images.unsplash.com/photo-1551808525-51a94da548ce?w=1920&h=1080&fit=crop" alt="Server Room">
            </div>
        </div>

        <div class="hero-overlay">
            <div class="hero-content">
                <span class="hero-badge">Solution Professionnelle</span>
                <h1>Gestion Intelligente de Votre Data Center</h1>
                <p>Optimisez vos ressources informatiques avec notre plateforme de gestion avancée. Réservation, monitoring et allocation en temps réel.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn-hero btn-primary">
                        Commencer Maintenant
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero btn-secondary">
                        Se Connecter
                    </a>
                </div>
            </div>
        </div>

        <div class="slider-nav">
            <span class="slider-dot active" data-slide="0"></span>
            <span class="slider-dot" data-slide="1"></span>
            <span class="slider-dot" data-slide="2"></span>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="section-header">
            <span class="section-badge">Nos Services</span>
            <h2 class="section-title">Solutions Complètes</h2>
            <p class="section-subtitle">Une plateforme tout-en-un pour gérer efficacement vos ressources informatiques</p>
        </div>

        <div class="services-grid">
            <div class="service-card">
                <h3>Gestion des Ressources</h3>
                <p>Gérez serveurs, machines virtuelles, stockage et équipements réseau depuis une interface unique et intuitive.</p>
            </div>

            <div class="service-card">
                <h3>Réservation Intelligente</h3>
                <p>Système de réservation avec vérification automatique des disponibilités et gestion des conflits.</p>
            </div>

            <div class="service-card">
                <h3>Gestion Multi-Rôles</h3>
                <p>4 types d'utilisateurs avec permissions différenciées pour une sécurité optimale.</p>
            </div>

            <div class="service-card">
                <h3>Notifications Temps Réel</h3>
                <p>Restez informé de toutes les actions importantes avec notre système de notifications instantanées.</p>
            </div>

            <div class="service-card">
                <h3>Statistiques Avancées</h3>
                <p>Tableaux de bord détaillés avec graphiques et métriques pour suivre l'utilisation de vos ressources.</p>
            </div>

            <div class="service-card">
                <h3>Sécurité Renforcée</h3>
                <p>Authentification sécurisée, gestion des permissions et journalisation complète des actions.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats" id="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">99.9%</div>
                <div class="stat-label">Disponibilité</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100+</div>
                <div class="stat-label">Ressources Gérées</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">4</div>
                <div class="stat-label">Types d'Utilisateurs</div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="contact">
        <div class="cta-content">
            <h2>Prêt à Optimiser Votre Data Center?</h2>
            <p>Rejoignez-nous dès aujourd'hui et découvrez comment notre plateforme peut transformer la gestion de vos ressources informatiques.</p>
            <div class="hero-buttons" style="justify-content: center;">
                <a href="{{ route('register') }}" class="btn-hero btn-primary">
                    Créer un Compte
                </a>
                <a href="{{ route('login') }}" class="btn-hero btn-secondary">
                    Se Connecter
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>DataCenter Management</h3>
                <p>Solution professionnelle de gestion des ressources informatiques pour les data centers modernes.</p>
            </div>
            <div class="footer-section">
                <h3>Liens Rapides</h3>
                <a href="#services">Services</a>
                <a href="#stats">Statistiques</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: contact@datacenter.com</p>
                <p>Téléphone: +212 5 22 12 34 56</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 DataCenter Management. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
        // Slider functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slider-dot');

        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        // Auto advance slides
        setInterval(nextSlide, 5000);

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => showSlide(index));
        });
    </script>
</body>
</html>
