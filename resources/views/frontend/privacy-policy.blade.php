@extends('frontend.layout')

@php
    $title = __('Politique de Confidentialité');
@endphp

<x-meta-tags :meta-tag-content="$seoInfo" :page-heading="$title" />

@section('style')
<style>
    /* ── Privacy Policy – Custom Styles ── */
    .privacy-hero {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b0030 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .privacy-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        pointer-events: none;
    }
    .privacy-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -40px;
        width: 260px; height: 260px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
        pointer-events: none;
    }
    .privacy-hero .breadcrumb-nav a,
    .privacy-hero .breadcrumb-nav span {
        font-size: 13px;
        color: rgba(255,255,255,.75);
        text-decoration: none;
    }
    .privacy-hero .breadcrumb-nav .separator { margin: 0 8px; }
    .privacy-hero .breadcrumb-nav .current { color: #fff; }
    .privacy-hero h1 { font-size: clamp(28px, 4vw, 44px); font-weight: 700; color: #fff; margin: 16px 0 12px; line-height: 1.2; }
    .privacy-hero .update-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        color: rgba(255,255,255,.9);
        font-size: 13px; font-weight: 500;
        padding: 6px 16px; border-radius: var(--radius-pill);
        backdrop-filter: blur(4px);
    }

    /* ── Layout ── */
    .privacy-body { padding: 80px 0 100px; background: var(--bg-2); }

    /* ── Sidebar TOC ── */
    .toc-card {
        background: #fff;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        overflow: hidden;
        position: sticky;
        top: 30px;
    }
    .toc-header {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b0030 100%);
        padding: 20px 24px;
        display: flex; align-items: center; gap: 10px;
    }
    .toc-header i { color: #fff; font-size: 16px; }
    .toc-header h6 { color: #fff; font-weight: 600; font-size: 14px; margin: 0; letter-spacing: .4px; text-transform: uppercase; }
    .toc-list { list-style: none; padding: 12px 0; margin: 0; }
    .toc-list li a {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 24px;
        color: var(--color-medium); font-size: 13.5px;
        text-decoration: none; transition: all .2s ease;
        border-left: 3px solid transparent;
    }
    .toc-list li a:hover,
    .toc-list li a.active {
        background: var(--bg-primary-light);
        color: var(--color-primary);
        border-left-color: var(--color-primary);
    }
    .toc-list li a .toc-num {
        display: inline-flex; align-items: center; justify-content: center;
        width: 22px; height: 22px; flex-shrink: 0;
        background: var(--bg-1); border-radius: 50%;
        font-size: 11px; font-weight: 700;
        color: var(--color-medium);
        transition: all .2s ease;
    }
    .toc-list li a:hover .toc-num,
    .toc-list li a.active .toc-num {
        background: var(--color-primary);
        color: #fff;
    }
    .toc-contact-strip {
        margin: 0; padding: 16px 24px;
        background: var(--bg-1);
        border-top: 1px solid var(--border-color);
    }
    .toc-contact-strip p { font-size: 12.5px; color: var(--color-medium); margin-bottom: 10px; }
    .toc-contact-strip a {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 13px; font-weight: 600; color: var(--color-primary);
        text-decoration: none;
    }

    /* ── Sections ── */
    .policy-section {
        background: #fff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 16px -4px rgba(8,0,42,.06);
        padding: 40px 44px;
        margin-bottom: 28px;
        scroll-margin-top: 40px;
    }
    @media (max-width: 767.98px) {
        .policy-section { padding: 28px 20px; }
    }
    .section-icon-title {
        display: flex; align-items: flex-start; gap: 18px;
        margin-bottom: 24px; padding-bottom: 20px;
        border-bottom: 2px solid var(--bg-1);
    }
    .section-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 48px; height: 48px; flex-shrink: 0;
        background: var(--bg-primary-light);
        border-radius: var(--radius-md);
    }
    .section-badge i { font-size: 20px; color: var(--color-primary); }
    .section-title-block h3 {
        font-size: 20px; font-weight: 700;
        color: var(--color-dark); margin: 0 0 4px;
        line-height: 1.3;
    }
    .section-title-block .section-number {
        font-size: 12px; font-weight: 600; letter-spacing: .6px;
        color: var(--color-primary); text-transform: uppercase;
    }

    .policy-section p {
        color: var(--color-medium); font-size: 15px; line-height: 1.8;
        margin-bottom: 14px;
    }
    .policy-section p:last-child { margin-bottom: 0; }

    /* Data categories grid */
    .data-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; margin-top: 20px; }
    .data-card {
        background: var(--bg-2); border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        padding: 20px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .data-card:hover { border-color: var(--color-primary); box-shadow: 0 4px 16px rgba(var(--color-primary-rgb),.1); }
    .data-card .data-card-icon {
        width: 40px; height: 40px;
        background: var(--bg-primary-light);
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 12px;
    }
    .data-card .data-card-icon i { color: var(--color-primary); font-size: 16px; }
    .data-card h6 { font-size: 13.5px; font-weight: 700; color: var(--color-dark); margin-bottom: 10px; }
    .data-card ul { list-style: none; padding: 0; margin: 0; }
    .data-card ul li {
        font-size: 13px; color: var(--color-medium);
        padding: 4px 0;
        display: flex; align-items: flex-start; gap: 6px;
    }
    .data-card ul li::before {
        content: ''; display: inline-block;
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--color-primary);
        margin-top: 7px; flex-shrink: 0;
    }

    /* Purpose list */
    .purpose-list { list-style: none; padding: 0; margin: 20px 0 0; display: flex; flex-direction: column; gap: 12px; }
    .purpose-list li {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 14px 18px;
        background: var(--bg-2); border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        font-size: 14px; color: var(--color-medium); line-height: 1.6;
    }
    .purpose-list li i { color: var(--color-primary); font-size: 14px; margin-top: 2px; flex-shrink: 0; }

    /* Sharing partners */
    .partner-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-top: 20px; }
    .partner-card {
        padding: 20px; border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        background: var(--bg-2);
    }
    .partner-card h6 { font-size: 13px; font-weight: 700; color: var(--color-dark); margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
    .partner-card h6 i { color: var(--color-primary); }
    .partner-card p { font-size: 13px; color: var(--color-medium); margin: 0; }

    /* Rights list */
    .rights-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px; margin-top: 20px; }
    .right-chip {
        display: flex; align-items: center; gap: 10px;
        padding: 14px 16px;
        background: var(--bg-primary-light);
        border-radius: var(--radius-md);
        border: 1px solid rgba(var(--color-primary-rgb),.15);
    }
    .right-chip i { color: var(--color-primary); font-size: 14px; flex-shrink: 0; }
    .right-chip span { font-size: 13.5px; font-weight: 600; color: var(--color-dark); }

    /* Alert notice */
    .policy-notice {
        display: flex; align-items: flex-start; gap: 14px;
        background: #fff8e7;
        border: 1px solid #f5c842;
        border-left: 4px solid #e59819;
        border-radius: var(--radius-md);
        padding: 16px 18px;
        margin-top: 20px;
    }
    .policy-notice i { color: #e59819; font-size: 16px; margin-top: 2px; flex-shrink: 0; }
    .policy-notice p { font-size: 13.5px; color: #7a5600; margin: 0; line-height: 1.6; }

    /* Info callout */
    .policy-info-callout {
        display: flex; align-items: flex-start; gap: 14px;
        background: #f0f9ff;
        border: 1px solid #bee3f8;
        border-left: 4px solid #0d6efd;
        border-radius: var(--radius-md);
        padding: 16px 18px;
        margin-top: 20px;
    }
    .policy-info-callout i { color: #0d6efd; font-size: 16px; margin-top: 2px; flex-shrink: 0; }
    .policy-info-callout p { font-size: 13.5px; color: #1a3a5c; margin: 0; line-height: 1.6; }

    /* CTA footer card */
    .privacy-cta {
        background: linear-gradient(135deg, var(--color-primary) 0%, #8b0030 100%);
        border-radius: var(--radius-xl);
        padding: 50px 40px;
        text-align: center;
        color: #fff;
        margin-top: 40px;
    }
    .privacy-cta h4 { font-size: 24px; font-weight: 700; margin-bottom: 10px; }
    .privacy-cta p { font-size: 15px; color: rgba(255,255,255,.85); margin-bottom: 24px; }
    .privacy-cta a.btn-white {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fff; color: var(--color-primary);
        font-weight: 700; font-size: 14px;
        padding: 14px 32px; border-radius: var(--radius-pill);
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .privacy-cta a.btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.2); }
</style>
@endsection

@section('content')

{{-- ── HERO BREADCRUMB ── --}}
<section class="privacy-hero">
    <div class="container">
        <nav class="breadcrumb-nav" aria-label="breadcrumb">
            <a href="{{ route('index') }}">{{ __('Accueil') }}</a>
            <span class="separator">/</span>
            <span class="current">{{ __('Politique de Confidentialité') }}</span>
        </nav>
        <h1>{{ __('Politique de Confidentialité') }}</h1>
        <div class="update-badge">
            <i class="far fa-calendar-check"></i>
            {{ __('Dernière mise à jour') }} : 28 mars 2026
        </div>
    </div>
</section>

{{-- ── BODY ── --}}
<section class="privacy-body">
    <div class="container">
        <div class="row gx-xl-5">

            {{-- SIDEBAR – Table des matières --}}
            <div class="col-xl-3 col-lg-4 d-none d-lg-block" data-aos="fade-right">
                <div class="toc-card">
                    <div class="toc-header">
                        <i class="far fa-list-ul"></i>
                        <h6>{{ __('Sommaire') }}</h6>
                    </div>
                    <ul class="toc-list">
                        <li><a href="#section-1"><span class="toc-num">1</span>{{ __('Introduction') }}</a></li>
                        <li><a href="#section-2"><span class="toc-num">2</span>{{ __('Qui sommes-nous ?') }}</a></li>
                        <li><a href="#section-3"><span class="toc-num">3</span>{{ __('Données collectées') }}</a></li>
                        <li><a href="#section-4"><span class="toc-num">4</span>{{ __('Utilisation des données') }}</a></li>
                        <li><a href="#section-5"><span class="toc-num">5</span>{{ __('Partage des données') }}</a></li>
                        <li><a href="#section-6"><span class="toc-num">6</span>{{ __('Sécurité des données') }}</a></li>
                        <li><a href="#section-7"><span class="toc-num">7</span>{{ __('Conservation des données') }}</a></li>
                        <li><a href="#section-8"><span class="toc-num">8</span>{{ __('Vos droits') }}</a></li>
                        <li><a href="#section-9"><span class="toc-num">9</span>{{ __('Cookies') }}</a></li>
                        <li><a href="#section-10"><span class="toc-num">10</span>{{ __('Contexte africain') }}</a></li>
                        <li><a href="#section-11"><span class="toc-num">11</span>{{ __('Protection des mineurs') }}</a></li>
                        <li><a href="#section-12"><span class="toc-num">12</span>{{ __('Modifications') }}</a></li>
                    </ul>
                    <div class="toc-contact-strip">
                        <p>{{ __('Une question sur vos données ?') }}</p>
                        <a href="{{ route('contact') }}">
                            <i class="far fa-envelope"></i>
                            {{ __('Nous contacter') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="col-xl-9 col-lg-8" data-aos="fade-up">

                {{-- Section 1 – Introduction --}}
                <div class="policy-section" id="section-1">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-shield-alt"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 01</span>
                            <h3>{{ __('Introduction') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('Chez SPACEHUB, la protection de vos données personnelles constitue une priorité fondamentale. Nous nous engageons à garantir la sécurité, la confidentialité et la transparence dans le traitement de toutes vos informations.') }}</p>
                    <p>{{ __('La présente Politique de Confidentialité a pour objet de vous informer de manière claire et complète sur les points suivants :') }}</p>
                    <div class="purpose-list" style="margin-top: 16px;">
                        <div style="display:flex;align-items:flex-start;gap:14px;padding:12px 18px;background:var(--bg-2);border-radius:var(--radius-md);border:1px solid var(--border-color);">
                            <i class="far fa-check-circle" style="color:var(--color-primary);margin-top:3px;flex-shrink:0;"></i>
                            <span style="font-size:14px;color:var(--color-medium);">{{ __('Les données que nous collectons et les modalités de leur collecte') }}</span>
                        </div>
                        <div style="display:flex;align-items:flex-start;gap:14px;padding:12px 18px;background:var(--bg-2);border-radius:var(--radius-md);border:1px solid var(--border-color);">
                            <i class="far fa-check-circle" style="color:var(--color-primary);margin-top:3px;flex-shrink:0;"></i>
                            <span style="font-size:14px;color:var(--color-medium);">{{ __('Les finalités pour lesquelles ces données sont utilisées') }}</span>
                        </div>
                        <div style="display:flex;align-items:flex-start;gap:14px;padding:12px 18px;background:var(--bg-2);border-radius:var(--radius-md);border:1px solid var(--border-color);">
                            <i class="far fa-check-circle" style="color:var(--color-primary);margin-top:3px;flex-shrink:0;"></i>
                            <span style="font-size:14px;color:var(--color-medium);">{{ __('Les mesures mises en place pour assurer leur protection') }}</span>
                        </div>
                        <div style="display:flex;align-items:flex-start;gap:14px;padding:12px 18px;background:var(--bg-2);border-radius:var(--radius-md);border:1px solid var(--border-color);">
                            <i class="far fa-check-circle" style="color:var(--color-primary);margin-top:3px;flex-shrink:0;"></i>
                            <span style="font-size:14px;color:var(--color-medium);">{{ __('Vos droits en tant qu\'utilisateur et les moyens de les exercer') }}</span>
                        </div>
                    </div>
                    <div class="policy-info-callout">
                        <i class="far fa-info-circle"></i>
                        <p>{{ __('En accédant à notre plateforme et en utilisant nos services, vous reconnaissez avoir pris connaissance de la présente politique et acceptez les pratiques qui y sont décrites.') }}</p>
                    </div>
                </div>

                {{-- Section 2 – Qui sommes-nous --}}
                <div class="policy-section" id="section-2">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-building"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 02</span>
                            <h3>{{ __('Qui sommes-nous ?') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('SPACEHUB est une plateforme digitale innovante dédiée à la mise en relation entre propriétaires d\'espaces et utilisateurs à la recherche de lieux adaptés à leurs besoins.') }}</p>
                    <div class="partner-row" style="margin-top: 20px;">
                        <div class="partner-card">
                            <h6><i class="far fa-map-marker-alt"></i>{{ __('Réservation d\'espaces') }}</h6>
                            <p>{{ __('Bureaux, salles de réunion, studios, espaces événementiels et bien plus.') }}</p>
                        </div>
                        <div class="partner-card">
                            <h6><i class="far fa-handshake"></i>{{ __('Mise en relation') }}</h6>
                            <p>{{ __('Connexion directe entre propriétaires et utilisateurs finaux.') }}</p>
                        </div>
                        <div class="partner-card">
                            <h6><i class="far fa-concierge-bell"></i>{{ __('Services associés') }}</h6>
                            <p>{{ __('Gestion des services et prestations liés à chaque espace réservé.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Section 3 – Données collectées --}}
                <div class="policy-section" id="section-3">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-database"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 03</span>
                            <h3>{{ __('Données collectées') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('Dans le cadre de la fourniture de nos services, nous sommes amenés à collecter différentes catégories de données selon votre profil et vos interactions avec la plateforme.') }}</p>
                    <div class="data-grid">
                        <div class="data-card">
                            <div class="data-card-icon"><i class="far fa-user"></i></div>
                            <h6>{{ __('Données personnelles') }}</h6>
                            <ul>
                                <li>{{ __('Nom et prénom') }}</li>
                                <li>{{ __('Numéro de téléphone') }}</li>
                                <li>{{ __('Adresse e-mail') }}</li>
                                <li>{{ __('Photo de profil (optionnelle)') }}</li>
                            </ul>
                        </div>
                        <div class="data-card">
                            <div class="data-card-icon"><i class="far fa-briefcase"></i></div>
                            <h6>{{ __('Données professionnelles') }}</h6>
                            <ul>
                                <li>{{ __('Nom de l\'entreprise') }}</li>
                                <li>{{ __('Adresse de l\'espace') }}</li>
                                <li>{{ __('Informations de facturation') }}</li>
                                <li>{{ __('N° d\'identification (si applicable)') }}</li>
                            </ul>
                        </div>
                        <div class="data-card">
                            <div class="data-card-icon"><i class="far fa-calendar-check"></i></div>
                            <h6>{{ __('Données de réservation') }}</h6>
                            <ul>
                                <li>{{ __('Détails des réservations') }}</li>
                                <li>{{ __('Historique des transactions') }}</li>
                                <li>{{ __('Préférences utilisateur') }}</li>
                            </ul>
                        </div>
                        <div class="data-card">
                            <div class="data-card-icon"><i class="far fa-laptop"></i></div>
                            <h6>{{ __('Données techniques') }}</h6>
                            <ul>
                                <li>{{ __('Adresse IP') }}</li>
                                <li>{{ __('Type d\'appareil') }}</li>
                                <li>{{ __('Navigateur utilisé') }}</li>
                                <li>{{ __('Données de navigation') }}</li>
                            </ul>
                        </div>
                        <div class="data-card">
                            <div class="data-card-icon"><i class="far fa-credit-card"></i></div>
                            <h6>{{ __('Données de paiement') }}</h6>
                            <ul>
                                <li>{{ __('Informations liées aux transactions') }}</li>
                                <li>{{ __('Mobile Money') }}</li>
                                <li>{{ __('Cartes bancaires (partiel)') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="policy-notice">
                        <i class="far fa-exclamation-triangle"></i>
                        <p>{{ __('Nous ne stockons jamais vos données bancaires complètes. Les informations sensibles de paiement sont traitées par des prestataires certifiés et sécurisés.') }}</p>
                    </div>
                </div>

                {{-- Section 4 – Utilisation --}}
                <div class="policy-section" id="section-4">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-cogs"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 04</span>
                            <h3>{{ __('Utilisation des données') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('Les données collectées sont utilisées exclusivement dans le cadre des finalités suivantes, en adéquation avec les services que vous sollicitez :') }}</p>
                    <ul class="purpose-list">
                        <li><i class="far fa-check"></i>{{ __('Fournir nos services et assurer leur amélioration continue') }}</li>
                        <li><i class="far fa-check"></i>{{ __('Gérer, confirmer et suivre vos réservations') }}</li>
                        <li><i class="far fa-check"></i>{{ __('Faciliter et sécuriser les transactions de paiement') }}</li>
                        <li><i class="far fa-check"></i>{{ __('Assurer la sécurité et l\'intégrité de la plateforme') }}</li>
                        <li><i class="far fa-check"></i>{{ __('Communiquer avec vous par SMS, WhatsApp ou e-mail') }}</li>
                        <li><i class="far fa-check"></i>{{ __('Personnaliser votre expérience sur la plateforme') }}</li>
                        <li><i class="far fa-check"></i>{{ __('Respecter nos obligations légales et réglementaires') }}</li>
                    </ul>
                </div>

                {{-- Section 5 – Partage --}}
                <div class="policy-section" id="section-5">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-share-alt"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 05</span>
                            <h3>{{ __('Partage des données') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('SPACEHUB ne vend, ne loue ni ne cède vos données personnelles à des tiers à des fins commerciales. Cependant, le bon fonctionnement de la plateforme implique de partager certaines informations avec des partenaires de confiance.') }}</p>
                    <div class="partner-row">
                        <div class="partner-card">
                            <h6><i class="far fa-server"></i>{{ __('Partenaires techniques') }}</h6>
                            <p>{{ __('Services de paiement (API Mobile Money), hébergeurs et fournisseurs d\'infrastructure cloud.') }}</p>
                        </div>
                        <div class="partner-card">
                            <h6><i class="far fa-headset"></i>{{ __('Prestataires de services') }}</h6>
                            <p>{{ __('Outils de support client, solutions d\'envoi de SMS et d\'e-mails transactionnels.') }}</p>
                        </div>
                        <div class="partner-card">
                            <h6><i class="far fa-balance-scale"></i>{{ __('Autorités compétentes') }}</h6>
                            <p>{{ __('En cas d\'obligation légale, judiciaire ou réglementaire dûment établie.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Section 6 – Sécurité --}}
                <div class="policy-section" id="section-6">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-lock"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 06</span>
                            <h3>{{ __('Sécurité des données') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('Nous mettons en œuvre des mesures techniques et organisationnelles rigoureuses pour protéger vos données contre tout accès non autorisé, perte, altération ou divulgation.') }}</p>
                    <div class="rights-grid" style="margin-top: 20px;">
                        <div class="right-chip"><i class="far fa-lock"></i><span>{{ __('Chiffrement des données sensibles') }}</span></div>
                        <div class="right-chip"><i class="far fa-key"></i><span>{{ __('Accès sécurisé aux comptes') }}</span></div>
                        <div class="right-chip"><i class="far fa-eye"></i><span>{{ __('Surveillance des activités suspectes') }}</span></div>
                        <div class="right-chip"><i class="far fa-server"></i><span>{{ __('Hébergement sur serveurs sécurisés') }}</span></div>
                    </div>
                    <div class="policy-notice">
                        <i class="far fa-exclamation-triangle"></i>
                        <p>{{ __('Malgré la rigueur de nos pratiques de sécurité, aucun système d\'information n\'est infaillible. Nous vous recommandons de protéger votre compte avec un mot de passe robuste et de ne jamais le partager.') }}</p>
                    </div>
                </div>

                {{-- Section 7 – Conservation --}}
                <div class="policy-section" id="section-7">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-archive"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 07</span>
                            <h3>{{ __('Conservation des données') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('Nous conservons vos données personnelles uniquement pour la durée nécessaire à l\'accomplissement des finalités pour lesquelles elles ont été collectées, selon les critères suivants :') }}</p>
                    <ul class="purpose-list">
                        <li><i class="far fa-clock"></i>{{ __('Tant que votre compte est actif et nécessaire pour la fourniture de nos services') }}</li>
                        <li><i class="far fa-clock"></i>{{ __('Pour satisfaire nos obligations légales, fiscales et comptables') }}</li>
                        <li><i class="far fa-clock"></i>{{ __('Jusqu\'à la suppression de votre compte, sur demande expresse de votre part') }}</li>
                    </ul>
                </div>

                {{-- Section 8 – Vos droits --}}
                <div class="policy-section" id="section-8">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-user-shield"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 08</span>
                            <h3>{{ __('Vos droits') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('Conformément aux bonnes pratiques internationales en matière de protection des données, vous disposez des droits suivants concernant vos informations personnelles :') }}</p>
                    <div class="rights-grid">
                        <div class="right-chip"><i class="far fa-eye"></i><span>{{ __('Droit d\'accès à vos données') }}</span></div>
                        <div class="right-chip"><i class="far fa-edit"></i><span>{{ __('Droit de rectification') }}</span></div>
                        <div class="right-chip"><i class="far fa-trash-alt"></i><span>{{ __('Droit à l\'effacement') }}</span></div>
                        <div class="right-chip"><i class="far fa-ban"></i><span>{{ __('Droit d\'opposition') }}</span></div>
                        <div class="right-chip"><i class="far fa-undo"></i><span>{{ __('Retrait du consentement') }}</span></div>
                        <div class="right-chip"><i class="far fa-download"></i><span>{{ __('Portabilité des données') }}</span></div>
                    </div>
                    <div class="policy-info-callout">
                        <i class="far fa-info-circle"></i>
                        <p>{{ __('Pour exercer l\'un de vos droits, contactez-nous directement via le formulaire de contact disponible sur la plateforme. Nous nous engageons à répondre à votre demande dans les meilleurs délais.') }}</p>
                    </div>
                </div>

                {{-- Section 9 – Cookies --}}
                <div class="policy-section" id="section-9">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-cookie-bite"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 09</span>
                            <h3>{{ __('Cookies et technologies similaires') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('SPACEHUB utilise des cookies et des technologies de suivi similaires afin d\'améliorer votre expérience de navigation et d\'optimiser nos services.') }}</p>
                    <ul class="purpose-list">
                        <li><i class="far fa-check"></i>{{ __('Amélioration de la navigation et de la fluidité de l\'interface') }}</li>
                        <li><i class="far fa-check"></i>{{ __('Analyse statistique de l\'utilisation de la plateforme') }}</li>
                        <li><i class="far fa-check"></i>{{ __('Personnalisation du contenu affiché selon vos préférences') }}</li>
                    </ul>
                    <p style="margin-top: 16px;">{{ __('Vous avez la possibilité de paramétrer votre navigateur afin de refuser tout ou partie des cookies. Cette action peut toutefois affecter certaines fonctionnalités de la plateforme.') }}</p>
                </div>

                {{-- Section 10 – Contexte africain --}}
                <div class="policy-section" id="section-10">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-globe-africa"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 10</span>
                            <h3>{{ __('Spécificités du marché africain') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('SPACEHUB est conçu pour répondre aux réalités économiques et technologiques du marché africain, et en particulier de la République Démocratique du Congo. Notre approche intègre ces spécificités à tous les niveaux.') }}</p>
                    <div class="partner-row">
                        <div class="partner-card">
                            <h6><i class="far fa-mobile-alt"></i>{{ __('Paiements locaux') }}</h6>
                            <p>{{ __('Intégration de solutions Mobile Money adaptées aux habitudes de paiement locales.') }}</p>
                        </div>
                        <div class="partner-card">
                            <h6><i class="far fa-store"></i>{{ __('Économie informelle') }}</h6>
                            <p>{{ __('Collecte de données adaptée à la réalité du marché, sans exigences administratives excessives.') }}</p>
                        </div>
                        <div class="partner-card">
                            <h6><i class="far fa-users"></i>{{ __('Inclusion numérique') }}</h6>
                            <p>{{ __('Limitation des prérequis pour favoriser l\'accès au plus grand nombre d\'utilisateurs.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Section 11 – Mineurs --}}
                <div class="policy-section" id="section-11">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-child"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 11</span>
                            <h3>{{ __('Protection des mineurs') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('Les services proposés par SPACEHUB sont exclusivement destinés aux personnes âgées de 18 ans et plus. Nous ne collectons pas sciemment de données personnelles concernant des mineurs.') }}</p>
                    <p>{{ __('Si vous êtes parent ou tuteur légal et que vous avez connaissance qu\'un mineur sous votre responsabilité nous a communiqué des données personnelles, nous vous invitons à nous contacter afin que nous puissions procéder à leur suppression dans les meilleurs délais.') }}</p>
                </div>

                {{-- Section 12 – Modifications --}}
                <div class="policy-section" id="section-12">
                    <div class="section-icon-title">
                        <div class="section-badge"><i class="far fa-sync-alt"></i></div>
                        <div class="section-title-block">
                            <span class="section-number">{{ __('Article') }} 12</span>
                            <h3>{{ __('Modifications de la politique') }}</h3>
                        </div>
                    </div>
                    <p>{{ __('SPACEHUB se réserve le droit de mettre à jour la présente Politique de Confidentialité à tout moment, notamment pour s\'adapter aux évolutions légales, technologiques ou opérationnelles.') }}</p>
                    <p>{{ __('Toute modification substantielle vous sera notifiée par les canaux de communication habituels (e-mail, notification in-app). La date de dernière mise à jour figurant en tête de ce document fait foi.') }}</p>
                    <p>{{ __('Nous vous encourageons à consulter régulièrement cette page afin de rester informé des éventuelles évolutions de nos pratiques.') }}</p>
                </div>

                {{-- CTA Contact --}}
                <div class="privacy-cta">
                    <h4>{{ __('Une question sur vos données personnelles ?') }}</h4>
                    <p>{{ __('Notre équipe est disponible pour répondre à toutes vos questions relatives à la confidentialité et à la gestion de vos informations.') }}</p>
                    <a href="{{ route('contact') }}" class="btn-white">
                        <i class="far fa-envelope"></i>
                        {{ __('Contacter notre équipe') }}
                    </a>
                </div>

            </div>{{-- /col main --}}
        </div>{{-- /row --}}
    </div>{{-- /container --}}
</section>

@endsection

@section('script')
<script>
    // Active TOC link on scroll
    (function () {
        var sections = document.querySelectorAll('.policy-section[id]');
        var tocLinks = document.querySelectorAll('.toc-list li a');
        if (!sections.length || !tocLinks.length) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    tocLinks.forEach(function (link) { link.classList.remove('active'); });
                    var active = document.querySelector('.toc-list li a[href="#' + entry.target.id + '"]');
                    if (active) active.classList.add('active');
                }
            });
        }, { rootMargin: '-30% 0px -60% 0px' });

        sections.forEach(function (sec) { observer.observe(sec); });
    })();
</script>
@endsection
